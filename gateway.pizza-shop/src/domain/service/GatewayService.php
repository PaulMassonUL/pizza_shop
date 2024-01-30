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
            'http_errors' => false, // Important pour ne pas lancer d'exceptions en cas d'erreurs 4xx ou 5xx
        ]);

        $options['headers']['Content-Type'] = 'application/json;charset=utf-8';
        return $client->request($method, $uri, $options);
    }

}