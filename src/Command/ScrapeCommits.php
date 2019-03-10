<?php

namespace App\Command;

use App\Entity\SearchTerm;
use App\Repository\CommitRepository;
use App\Repository\SearchTermRepository;
use App\Service\GitHub\GitHubConnectivityException;
use App\Service\GitHub\Search;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScrapeCommits extends Command
{
    protected static $defaultName = 'app:scrape-commits';

    /** @var EntityManagerInterface */
    private $em;

    /** @var SearchTermRepository */
    private $searchTermRepository;

    /** @var CommitRepository */
    private $commitRepository;

    /** @var Search */
    private $gitHubSearch;

    public function __construct(
        EntityManagerInterface $em,
        SearchTermRepository $searchTermRepository,
        CommitRepository $commitRepository,
        Search $gitHubSearch
    )
    {
        parent::__construct();

        $this->em = $em;
        $this->searchTermRepository = $searchTermRepository;
        $this->commitRepository = $commitRepository;
        $this->gitHubSearch = $gitHubSearch;
    }

    protected function configure()
    {
        $this
            ->setDescription('Scrapes commit messages from GitHub.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $searchTerms = $this->searchTermRepository->findBy([], ['lastSynced' => 'ASC']);

        $commits = [];
        foreach ($searchTerms as $searchTerm) {
            try {
                $commits = array_merge(
                    $commits,
                    $this->gitHubSearch->search($searchTerm->getQuery(), $searchTerm->getLastSynced())
                );
                $searchTerm->setLastSynced(new \DateTime);
                $this->em->persist($searchTerm);
            } catch (GitHubConnectivityException $e) {
                // @todo: log?
            }
        }

        foreach ($commits as $commit) {
            $this->em->persist($commit);
        }

        $this->em->flush();
    }
}
