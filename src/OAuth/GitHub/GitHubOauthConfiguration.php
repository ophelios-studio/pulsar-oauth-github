<?php namespace Pulsar\OAuth\GitHub;

class GitHubOauthConfiguration
{
    public const array DEFAULT_CONFIGURATIONS = [
        'client_id' => "YOUR_ID",
        'client_secret' => "YOUR_SECRET",
        'callback' => "/oauth/github/callback"
    ];

    private array $configurations;
    private string $clientId;
    private string $clientSecret;
    private string $callback;

    public function __construct(array $configurations = self::DEFAULT_CONFIGURATIONS)
    {
        $this->initializeConfigurations($configurations);
        $this->initializeClientId();
        $this->initializeClientSecret();
        $this->initializeCallback();
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getCallback(): string
    {
        return $this->callback;
    }

    private function initializeConfigurations(array $configurations): void
    {
        $this->configurations = $configurations;
    }

    private function initializeClientId(): void
    {
        $this->clientId = ((isset($this->configurations['client_id']))
            ? $this->configurations['client_id']
            : self::DEFAULT_CONFIGURATIONS['client_id']);
    }

    private function initializeClientSecret(): void
    {
        $this->clientSecret = ((isset($this->configurations['client_secret']))
            ? $this->configurations['client_secret']
            : self::DEFAULT_CONFIGURATIONS['client_secret']);
    }

    private function initializeCallback(): void
    {
        $this->callback = ((isset($this->configurations['callback']))
            ? $this->configurations['callback']
            : self::DEFAULT_CONFIGURATIONS['callback']);
    }
}
