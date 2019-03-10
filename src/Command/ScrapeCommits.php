<?php

namespace App\Command;

use App\Service\GitHub\Search;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScrapeCommits extends Command
{
    protected static $defaultName = 'app:scrape-commits';

    /** @var Search */
    private $gitHubSearch;

    public function __construct(Search $gitHubSearch)
    {
        parent::__construct();
        $this->gitHubSearch = $gitHubSearch;
    }

    protected function configure()
    {
        $this
            ->setDescription('Scrapes commit messages from GitHub.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commits = $this->gitHubSearch->search('shit');

        var_dump($commits[0]);
    }
}
