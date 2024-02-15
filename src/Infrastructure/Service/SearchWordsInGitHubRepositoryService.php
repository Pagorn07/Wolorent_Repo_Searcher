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
        $result = [
            "Test" => 0,
            "Service" => 0,
            "Controller" => 0,
            "Main" => 0
        ];

        try {
            $gitApiResponse = $this->httpClient->get("$ownerName/$repositoryName/git/trees/$branchName?recursive=1");

            $body = $gitApiResponse->getBody()->getContents();
            $elements = json_decode($body, true)["tree"];

            foreach ($elements as $element) {
                $elementNameToLower = strtolower(basename($element["path"]));

                if (str_contains($elementNameToLower, "test")) {
                    $result["Test"]++;
                } else if (str_contains($elementNameToLower, "service")) {
                    $result["Service"]++;
                } else if (str_contains($elementNameToLower, "controller")) {
                    $result["Controller"]++;
                } else if (str_contains($elementNameToLower, "main")) {
                    $result["Main"]++;
                }
            }

            return $result;
        } catch (GuzzleException $exception) {
            return ['error' => $exception->getMessage()];
        }
    }

    function extractFileName(string $path): string
    {
        // Get the last segment of the path using basename function
        $fileName = basename($path);

        // If the last segment contains a dot, get the substring before the dot
        $dotPosition = strpos($fileName, '.');
        
        if ($dotPosition !== false) {
            $fileName = substr($fileName, 0, $dotPosition);
        }

        return $fileName;
    }
}