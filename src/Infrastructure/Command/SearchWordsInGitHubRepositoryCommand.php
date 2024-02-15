<?php declare(strict_types=1);

namespace App\Infrastructure\Command;

use App\Infrastructure\Service\SearchWordsInGitHubRepositoryService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SearchWordsInGitHubRepositoryCommand extends Command
{
    private const COMMAND_NAME = "app:search-words-in-github-repository-command";
    private const COMMAND_DESCRIPTION = "This command searches certain words inside a GitHub repo!";

    private $searchWordsInGitHubRepositoryService;

    public function __construct(SearchWordsInGitHubRepositoryService $searchWordsInGitHubRepositoryService)
    {
        parent::__construct();
        $this->searchWordsInGitHubRepositoryService = $searchWordsInGitHubRepositoryService;
    }

    protected function configure(): void
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription(self::COMMAND_DESCRIPTION)
            ->addArgument("ownerName", InputArgument::REQUIRED, "Owner of the GitHub repository.")
            ->addArgument("repositoryName", InputArgument::REQUIRED, "Name of the GitHub repository.")
            ->addArgument("branchName", InputArgument::REQUIRED, "Branch of the GitHub repository.");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ownerName = $input->getArgument("ownerName");
        $repositoryName = $input->getArgument("repositoryName");
        $branchName = $input->getArgument("branchName");

        $a = $this->searchWordsInGitHubRepositoryService->execute($ownerName, $repositoryName, $branchName);

        $output->writeln("Searching GitHub repository: " . $ownerName . "/" . $repositoryName . " Branch: " . $branchName);
        
        $output->writeln(print_r($a, true));

        return Command::SUCCESS;
    }
}