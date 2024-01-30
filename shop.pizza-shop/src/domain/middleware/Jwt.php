<?php

namespace pizzashop\shop\domain\middleware;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;

class Jwt
{
    private string $auth_uri;

    public function __construct(string $auth_uri)
    {
        $this->auth_uri = $auth_uri;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $next)
    {

        if (!$request->hasHeader('Authorization'))
            throw new HttpUnauthorizedException($request, 'No authorization token provided');

        $client = new Client([
            'base_uri' => $this->auth_uri,
            'timeout' => 30.0
        ]);

        $headers = [
            'Origin' => $_SERVER['HTTP_HOST'],
            'Authorization' => $request->getHeader('Authorization'),
        ];

        try {
            $client->request('GET', '/api/users/validate', [
                'headers' => $headers
            ]);
        } catch (RequestException $e) {
            $response = $e->getResponse();

            // Le serveur répond avec une erreur et une url de redirection
            if (!$response->hasHeader('Location'))
                throw new HttpUnauthorizedException($request, 'No redirection url provided');

            throw new HttpUnauthorizedException($request, "Invalid or expired token, redirect to " . $response->getHeader('Location')[0]);
        }

        return $next->handle($request);
    }
}