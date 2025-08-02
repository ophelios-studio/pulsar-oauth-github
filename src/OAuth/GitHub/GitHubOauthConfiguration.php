<?php namespace Pulsar\OAuth\GitHub;

class GitHubOauthConfiguration
{
    public const array DEFAULT_CONFIGURATIONS = [
        'client_id' => "YOUR_ID",
        'client_secret' => "YOUR_SECRET"
    ];

    private array $configurations;
    private string $clientId;
    private string $clientSecret;

    public function __construct(array $configurations = self::DEFAULT_CONFIGURATIONS)
    {
        $this->initializeConfigurations($configurations);
        $this->initializeClientId();
        $this->initializeClientSecret();
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
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
}
