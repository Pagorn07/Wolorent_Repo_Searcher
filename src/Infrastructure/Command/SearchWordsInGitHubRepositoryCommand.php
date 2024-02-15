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
    
    private const REPOSITORY_TO_FIND_ARGUMENT_NAME = "repositoryToFind";
    private const REPOSITORY_TO_FIND_ARGUMENT_DESCRIPTION = "Name of the GitHub repository to search.";

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
            ->addArgument(self::REPOSITORY_TO_FIND_ARGUMENT_NAME, InputArgument::REQUIRED, self::REPOSITORY_TO_FIND_ARGUMENT_DESCRIPTION);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $repositoryToFind = $input->getArgument(self::REPOSITORY_TO_FIND_ARGUMENT_NAME);

        $a = $this->searchWordsInGitHubRepositoryService->execute($repositoryToFind);

        $output->writeln("Searching GitHub repository: " . $repositoryToFind);
        $output->writeln($a);

        return Command::SUCCESS;
    }
}