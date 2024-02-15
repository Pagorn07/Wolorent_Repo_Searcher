<?php declare(strict_types=1);

namespace App\Infrastructure\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SearchWordsInGitHubRepositoryService
{
    private $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function execute(string $ownerName, string $repositoryName, string $branchName): array
    {
        try {
            $response = $this->httpClient->get("$ownerName/$repositoryName/git/trees/$branchName?recursive=1");

            $body = $response->getBody()->getContents();

            return json_decode($body, true);
        } catch (GuzzleException $exception) {
            return ['error' => $exception->getMessage()];
        }
    }
}