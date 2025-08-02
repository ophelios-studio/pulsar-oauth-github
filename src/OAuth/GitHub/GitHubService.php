<?php namespace Pulsar\OAuth\GitHub;

use Exception;
use Pulsar\OAuth\GitHub\Entities\GitHubBranch;
use Pulsar\OAuth\GitHub\Entities\GitHubRelease;
use Pulsar\OAuth\GitHub\Entities\GitHubRepository;
use Pulsar\OAuth\GitHub\Entities\GitHubUser;
use Zephyrus\Utilities\FileSystem\Directory;

class GitHubService
{
    private string $accessToken;

    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Get the authenticated user's data.
     *
     * @return GitHubUser
     * @throws Exception
     */
    public function getUser(): GitHubUser
    {
        $response = $this->apiRequest('https://api.github.com/user');
        return GitHubUser::build((object) $response);
    }

    /**
     * Get the list of repositories the user has access to.
     *
     * @return GitHubRepository[] List of repositories as an associative array
     * @throws Exception
     */
    public function getUserRepositories(): array
    {
        $repos = $this->apiRequest('https://api.github.com/user/repos');
        foreach ($repos as &$repo) {
            $repo = (object) $repo;
        }
        return GitHubRepository::buildArray($repos);
    }

    /**
     * Get the list of repositories for a specified organization.
     *
     * @param string $organization The organization name
     * @return GitHubRepository[] List of organization repositories as an associative array
     * @throws Exception
     */
    public function getOrganizationRepositories(string $organization): array
    {
        $repos = $this->apiRequest("https://api.github.com/orgs/$organization/repos");
        foreach ($repos as &$repo) {
            $repo = (object) $repo;
        }
        return GitHubRepository::buildArray($repos);
    }

    /**
     * Download the repository ZIP for a private repository.
     *
     * @param string $owner Repository owner
     * @param string $repo Repository name
     * @param string $commitOrBranch SHA of the commit, branch, or tag name
     * @param string $destination Path to save the ZIP file
     * @return string Path to the downloaded ZIP file
     * @throws Exception If the download fails
     */
    public function downloadRepositoryZip(string $owner, string $repo, string $commitOrBranch, string $destination): string
    {
        $url = "https://github.com/$owner/$repo/archive/$commitOrBranch.zip";

        $headers = [
            "Authorization: token $this->accessToken",
            "User-Agent: MyApp (http://localhost)"
        ];

        $context = stream_context_create([
            'http' => [
                'header' => $headers,
                'method' => 'GET',
            ],
        ]);

        if (!Directory::exists($destination)) {
            Directory::create($destination);
        }
        $zipFilePath = "$destination/$repo-$commitOrBranch.zip";

        // Fetch the ZIP file
        $fileContents = file_get_contents($url, false, $context);
        if ($fileContents === false) {
            throw new Exception("Failed to download ZIP file for the private repository at $url");
        }
        file_put_contents($zipFilePath, $fileContents);

        return $zipFilePath;
    }

    /**
     * Get all branches of a repository.
     *
     * @param string $owner Repository owner
     * @param string $repo Repository name
     * @return GitHubBranch[] A list of branches with their names and commit SHAs
     * @throws Exception If the request fails
     */
    public function getBranches(string $owner, string $repo): array
    {
        $url = "https://api.github.com/repos/$owner/$repo/branches";
        $response = $this->apiRequest($url);
        $branches = [];
        foreach ($response as $branch) {
            $branches[] = GitHubBranch::build((object) [
                'name' => $branch['name'],
                'commit' => (object) $branch['commit'],
                'protected' => $branch['protected']
            ]);
        }
        return $branches;
    }

    /**
     * Get all releases of a repository.
     *
     * @param string $owner Repository owner
     * @param string $repo Repository name
     * @return array A list of releases with tags, names, and publish dates
     * @throws Exception If the request fails
     */
    public function getReleases(string $owner, string $repo): array
    {
        $response = $this->apiRequest("https://api.github.com/repos/$owner/$repo/releases");
        $releases = [];
        foreach ($response as &$release) {
            $release['author'] = (object) $release['author'];
            $releases[] = GitHubRelease::build((object) $release);
        }
        return $releases;
    }

    /**
     * Perform a GET request to the GitHub API.
     *
     * @param string $url The API URL to call
     * @return array The JSON-decoded response as an array
     * @throws Exception
     */
    private function apiRequest(string $url): array
    {
        if (empty($this->accessToken)) {
            throw new Exception("Access token is not set. Please authenticate first.");
        }

        $context = stream_context_create([
            'http' => [
                'header' => [
                    "Authorization: token $this->accessToken",
                    "User-Agent: MyApp (http://localhost)",
                ],
                'method' => 'GET',
            ],
        ]);

        $response = file_get_contents($url, false, $context);

        if ($response === false) {
            throw new Exception("Failed to fetch data from $url.");
        }

        return json_decode($response, true);
    }
}
