<?php namespace Pulsar\OAuth\GitHub;

use Exception;
use Tracy\Debugger;
use Zephyrus\Core\Application;

class GitHubOauth
{
    private GitHubOauthConfiguration $configuration;
    private string $redirectUri;

    public function __construct(GitHubOauthConfiguration $configuration)
    {
        $baseUrl = Application::getInstance()->getRequest()->getUrl()->getBaseUrl();
        $this->configuration = $configuration;
        $this->redirectUri =  $baseUrl . $this->configuration->getCallback();
    }

    /**
     * Generate the GitHub authorization URL based on provided scopes.
     *
     * @param array $scopes List of scopes to request (e.g., ['user', 'repo', read:org])
     * @return string Authorization URL
     */
    public function getAuthorizationUrl(array $scopes = ['user']): string
    {
        $scopeString = implode(' ', $scopes);
        return "https://github.com/login/oauth/authorize?" . http_build_query([
            'client_id' => $this->configuration->getClientId(),
            'redirect_uri' => $this->redirectUri,
            'scope' => $scopeString,
            'response_type' => 'code'
        ]);
    }

    /**
     * Exchange the OAuth code for an access token.
     *
     * @param string $code Authorization code received in callback
     * @return string
     * @throws Exception
     */
    public function getAccessToken(string $code): string
    {
        $url = 'https://github.com/login/oauth/access_token';

        $data = [
            'client_id' => $this->configuration->getClientId(),
            'client_secret' => $this->configuration->getClientSecret(),
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
        ];

        $context = stream_context_create([
            'http' => [
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ],
        ]);

        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            throw new Exception("Failed to fetch access token.");
        }

        parse_str($response, $responseArray);
        Debugger::barDump($responseArray);
        if (isset($responseArray['access_token'])) {
            return $responseArray['access_token'];
        } else {
            throw new Exception("Access token not found in the response.");
        }
    }

    public function refreshAccessToken(string $refreshToken): ?string
    {
        $url = 'https://github.com/login/oauth/access_token';
        $data = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => $this->configuration->getClientId(),
            'client_secret' => $this->configuration->getClientSecret()
        ];
        $options = [
            'http' => [
                'header' => "Accept: application/json\r\nContent-Type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            throw new Exception('Failed to refresh access token.');
        }

        $result = json_decode($response, true);
        return $result['access_token'] ?? null;
    }
}
