<?php

namespace pizzashop\gateway\domain\service;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class GatewayService implements iGateway
{

    public function request(string $method, string $uri = '', array $options = []): ResponseInterface
    {
        $client = new Client([
            'timeout' => 30.0,
        ]);

        $options['headers']['Content-Type'] = 'application/json';
        return $client->request($method, $uri, $options);
    }

}