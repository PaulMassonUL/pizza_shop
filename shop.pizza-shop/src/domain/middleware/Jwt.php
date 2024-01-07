<?php

namespace pizzashop\shop\domain\middleware;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use pizzashop\auth\domain\service\iAuth;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;

class Jwt
{
    private iAuth $authService;

    public function __construct(iAuth $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $next)
    {

        if (!$request->hasHeader('Authorization'))
            throw new HttpUnauthorizedException($request, 'No authorization token provided');

        $client = new Client([
            'base_uri' => $this->settings['auth.service'],
            'timeout' => 5.0
        ]);

        try {
            $response = $client->request('GET', '/api/users/validate', [
                'headers' => ['Authorization' => $request->getHeader('Authorization')]
            ]);

            if ($response->getStatusCode() === 200) {
                return $next->handle($request);
            } else if ($response->getStatusCode() === 401) {
                if ($response->hasHeader('Location')) {
                    // Le serveur répond avec un code 401 et une url de redirection vers l'authentification

                    $response = $client->request('POST', '/api/users/signin', [
                        'headers' => ['Authorization' => $request->getHeader('Authorization')]
                    ]);


                } else {
                    // Lorsque l'access token n'est plus valide, le client utilise le refresh token pour obtenir un nouvel access token

                    $response = $client->request('POST', '/api/users/refresh', [
                        'headers' => ['Authorization' => $request->getHeader('Authorization')]
                    ]);


                }
            }

        } catch (GuzzleException $e) {
            throw new HttpUnauthorizedException($request, 'Invalid authorization token provided');
        }

        return $next->handle($request);
    }
}