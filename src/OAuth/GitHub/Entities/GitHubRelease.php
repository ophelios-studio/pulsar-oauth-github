<?php namespace Pulsar\OAuth\GitHub\Entities;

use Zephyrus\Core\Entity\Entity;

/**
 * 'url' => 'https://api.github.com/repos/ophelios-studio/zephyrus/releases/202352325'
 * 'assets_url' => 'https://api.github.com/repos/ophelios-studio/zephyrus/releases/202352325/assets'
 * 'upload_url' => 'https://uploads.github.com/repos/ophelios-studio/zephyrus/releases/202352325/assets{?name,label}'
 * 'html_url' => 'https://github.com/ophelios-studio/zephyrus/releases/tag/0.9.0'
 * 'id' => 202352325
 * 'author' => array
 * 'node_id' => 'RE_kwDOLUoDY84MD6bF'
 * 'tag_name' => '0.9.0'
 * 'target_commitish' => 'main'
 * 'name' => '0.9.0'
 * 'draft' => false
 * 'immutable' => false
 * 'prerelease' => false
 * 'created_at' => '2025-02-25T16:14:40Z'
 * 'published_at' => '2025-02-25T16:16:35Z'
 * 'assets' => array (0)
 * 'tarball_url' => 'https://api.github.com/repos/ophelios-studio/zephyrus/tarball/0.9.0'
 * 'zipball_url' => 'https://api.github.com/repos/ophelios-studio/zephyrus/zipball/0.9.0'
 * 'body' => ...
 */
class GitHubRelease extends Entity
{
    public int $id;
    public string $url;
    public string $assets_url;
    public string $upload_url;
    public string $html_url;
    public GitHubUser $author;
    public string $node_id;
    public string $tag_name;
    public string $target_commitish;
    public string $name;
    public bool $draft;
    public bool $immutable;
    public bool $prerelease;
    public string $created_at;
    public string $published_at;
    public array $assets;
    public string $tarball_url;
    public string $zipball_url;
    public ?string $body;
}
