<?php

namespace App\Service\GitHub;

use GuzzleHttp\Client;
use Teapot\StatusCode;

class Search
{
    const BASE_URI = 'https://api.github.com/';
    const SEARCH_PATH = 'search/commits';
    const ACCEPT = 'application/vnd.github.cloak-preview';

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
     * @return \stdClass[]
     * @throws GitHubConnectivityException
     */
    public function search(string $query): array
    {
        $response = $this->client->get(
            sprintf('%s?q=%s', self::SEARCH_PATH, $query),
            [
                'headers' => [
                    'Accept' => self::ACCEPT,
                ]
            ]
        );

        if ($response->getStatusCode() !== StatusCode::OK) {
            throw new GitHubConnectivityException('Failed to retrieve GitHub commits for search term: ' . $query);
        }

        $searchResults = json_decode($response->getBody()->getContents());

        return $searchResults->items;
    }
}
