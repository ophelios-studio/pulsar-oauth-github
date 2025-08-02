<?php namespace Pulsar\OAuth\GitHub\Entities;

use Zephyrus\Core\Entity\Entity;

class GitHubBranch extends Entity
{
    public string $name;
    public GitHubCommit $commit;
    public bool $protected;
}
