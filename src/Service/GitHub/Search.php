<?php

namespace App\Service\GitHub;

use App\Entity\Commit;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Teapot\StatusCode;

class Search
{
    const BASE_URI = 'https://api.github.com/';
    const SEARCH_PATH = 'search/commits';
    const ACCEPT = 'application/vnd.github.cloak-preview';
    const DATE_FORMAT = 'Y-m-d\TH:i:s.uO';

    /** @var Client */
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => self::BASE_URI,
        ]);
    }

    /**
     * @param string $query
     * @param \DateTimeInterface|null $lastSync
     * @return Commit[]
     * @throws GitHubConnectivityException
     */
    public function search(string $query, \DateTimeInterface $lastSync = null): array
    {
        try {
            $response = $this->client->get(
                sprintf('%s?q=%s', self::SEARCH_PATH, $query),
                [
                    'headers' => [
                        'Accept' => self::ACCEPT,
                    ]
                ]
            );
        } catch (RequestException $e) {
            throw new GitHubConnectivityException('Failed to retrieve GitHub commits for search term: ' . $query);
        }

        $searchResults = json_decode($response->getBody()->getContents());

        $commits = [];

        foreach ($searchResults->items as $searchResult) {
            if (empty($searchResult->committer)) {
                continue;
            }

            $dateTime = \DateTime::createFromFormat(self::DATE_FORMAT, $searchResult->commit->author->date);

            if (!empty($lastSync) && $dateTime <= $lastSync) {
                break;
            }

            $commit = new Commit;
            $commit
                ->setUrl($searchResult->html_url)
                ->setMessage($searchResult->commit->message)
                ->setDate($dateTime)
                ->setAuthorName($searchResult->committer->login)
                ->setAuthorId($searchResult->committer->id);

            $commits[] = $commit;
        }

        return $commits;
    }
}
