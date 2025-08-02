<?php namespace Pulsar\OAuth\GitHub\Entities;

use Zephyrus\Core\Entity\Entity;

class GitHubCommit extends Entity
{
    public string $sha;
    public string $url;
}
