<?php namespace Pulsar\OAuth\GitHub\Entities;

use Zephyrus\Core\Entity\Entity;

class GitHubUser extends Entity
{
    public string $login;
    public int $id;
    public string $name;
    public ?string $company;
    public string $node_id;
    public ?string $gravatar_id;
    public string $url;
    public string $html_url;
    public string $followers_url;
    public string $following_url;
    public string $gists_url;
    public string $starred_url;
    public string $subscriptions_url;
    public string $organizations_url;
    public string $repos_url;
    public string $events_url;
    public string $received_events_url;
    public string $type;
    public ?string $blog;
    public ?string $location;
    public string $avatar_url;
    public string $email;
    public bool $site_admin;
    public int $public_repos;
    public int $public_gists;
    public int $followers;
    public int $following;
    public int $created_at;
    public int $updated_at;
    public int $private_gists;
    public int $total_private_repos;
    public int $owned_private_repos;
    public int $disk_usage;
    public int $collaborators;
    public string $user_view_type;
    public ?bool $hireable;
    public ?string $bio;
    public ?string $twitter_username;
    public ?string $notification_email;
}
