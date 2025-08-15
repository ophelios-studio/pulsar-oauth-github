# Pulsar OAuth GitHub

## Abstract
Provides a set of classes to help OAuth integration of GitHub into a project.

## Installation

### Composer dependency
The first step is to include the PHP composer dependency in the project. If the 
library is private, be sure to include the repository.

```json
"repositories": [
    {
      "type": "vcs",
      "url":  "https://github.com/ophelios-studio/pulsar-oauth-github.git"
    }
]
```

```json
"require": {
    "ophelios/pulsar-oauth-gitbug": "dev-main"
}
```

Once it's done, update the project's dependencies. Should download and place the necessary 
files into the project with the standard Publisher class.

```shell
composer update
```

### Configurations
This library requires to add properties to the project's configurations to identify the GitHub OAuth app to use. First,
add in the `.env` file the following constants.

```dotenv
# GitHub
GITHUB_CLIENT_ID="<YOUR CLIENT ID>"
GITHUB_CLIENT_SECRET="<YOUR CLIENT SECRET>"
```

Then, add the following into the `config.yml` file.

```yml
services:
  github:
    client_id: !env GITHUB_CLIENT_ID
    client_secret: !env GITHUB_CLIENT_SECRET
    callback: "/oauth/github/callback"
```

## Usage

### Authorization

```php
$config = new GitHubOauthConfiguration(Configuration::read('services')['github']);
$githubOAuth = new GitHubOauth($config);
$token = $githubOAuth->getAccessToken($_GET['code']);
```

### Features
```php
$service = new GitHubService($token);

// Get user data
$user = $service->getUser();
$html = "Welcome, $user->login (GitHub ID: $user->id)<br>";

// Get user repositories
$repos = $service->getUserRepositories();
$html .=  "<h3>Your Repositories:</h3>";
foreach ($repos as $repo) {
    Debugger::barDump($repo);
    $html .=  "- <a href='$repo->html_url'>$repo->name</a><br>";
}

// Get organization repositories
$html .=  "<h3>Ophelios Repositories:</h3>";
$repos = $service->getOrganizationRepositories("ophelios-studio");
foreach ($repos as $repo) {
    $html .=  "- <a href='$repo->html_url'>$repo->name</a><br>";
}

// Get branches and releases
$service->getBranches("ophelios-studio","fondation-cst");
$service->getReleases("ophelios-studio","zephyrus");

// Download repository
$destination = ROOT_DIR . "/temp/github";
$service->downloadRepositoryZip("ophelios-studio", "fondation-cst", "68954def9778bd8cf3096b37ea34dab1316912e6", $destination);
```