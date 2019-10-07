<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;

class VkDonate {
    const BASE_URL = 'https://api.vkdonate.ru';

    private $client;

    public function __construct($apiKey)
    {
        $handler = new HandlerStack();
        $handler->setHandler(new CurlHandler());

        $handler->unshift(Middleware::mapRequest(function (RequestInterface $request) use ($apiKey) {
            return $request->withUri(Uri::withQueryValue($request->getUri(), 'key', $apiKey));
        }));

        $this->client = new Client([
            'base_uri' => self::BASE_URL,
            'handler' => $handler
        ]);
    }

    private function get($endPoint, array $params = [])
    {
        $response = $this->client->get($endPoint, ['query' => $params]);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function lastDonates($params = []) {
        $params = array_merge($params, [
            'action' => 'donates',
            'count' => 50,
            'sort' => 'date',
            'order' => 'desc'
        ]);

        return $this->get('/', $params);
    }
}
