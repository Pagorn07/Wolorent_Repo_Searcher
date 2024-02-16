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
            $result = [];

            $gitApiResponse = $this->httpClient->get("$ownerName/$repositoryName/git/trees/$branchName?recursive=1");

            $body = $gitApiResponse->getBody()->getContents();
            $elements = json_decode($body, true)["tree"];

            foreach ($elements as $element) {
                $filename = basename($element["path"]);

                if (pathinfo($filename, PATHINFO_EXTENSION) !== "php") {
                    continue;
                }

                $filenameWithoutExtension = ucfirst(pathinfo($filename, PATHINFO_FILENAME));
                
                $filenameWords = preg_split('/(?=[A-Z])/', $filenameWithoutExtension);

                foreach ($filenameWords as $filenameWord) {
                    if ($filenameWord === "") {
                        continue;
                    }

                    if (isset($result[$filenameWord])) {
                        $result[$filenameWord]++;
                    } else {
                        $result[$filenameWord] = 1;
                    }
                }
            }

            return $result;
        } catch (GuzzleException $exception) {
            return ['error' => $exception->getMessage()];
        }
    }
}