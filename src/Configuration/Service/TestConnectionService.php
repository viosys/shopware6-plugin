<?php

declare(strict_types=1);

namespace Omikron\FactFinder\Shopware6\Configuration\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Omikron\FactFinder\Shopware6\Credentials\Credentials;

class TestConnectionService
{
    /** @var string */
    private $apiQuery = 'FACT-Finder version';

    /**
     * @throws RequestException
     */
    public function execute(string $serverUrl, string $channel, Credentials $credentials): void
    {
        $client = new Client([
            'base_uri' => $serverUrl,
            'headers'  => [
                'Accept'        => 'application/json',
                'Authorization' => $credentials->__toString(),
            ],
        ]);

        $endpoint = sprintf('%s/rest/v3/search/%s?%s', rtrim($serverUrl, '/'), $channel,  http_build_query(['query' => $this->apiQuery]));

        $client->get($endpoint);
    }
}
