<?php

namespace pizzashop\auth\domain\middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;

class Cors
{

    private string $origin;

    public function __construct(string $origin)
    {
        $this->origin = $origin;
    }

    public function __invoke(ServerRequestInterface $rq, RequestHandlerInterface $next): ResponseInterface
    {

        if (!$rq->hasHeader('Origin')) {
            throw new HttpUnauthorizedException($rq, "missing Origin Header (cors)");
        }

        if (!$rq->hasHeader('Authorization')) {
            throw new HttpUnauthorizedException($rq, "missing Authorization Header");
        }

        $response = $next->handle($rq);
        return $response->withHeader('Access-Control-Allow-Origin', $this->origin)
            ->withHeader('Access-Control-Allow-Headers', $rq->getHeaderLine('Access-Control-Request-Headers'))
            ->withHeader('Access-Control-Allow-Methods', $rq->getHeaderLine('Access-Control-Request-Method'))
            ->withHeader('Access-Control-Max-Age', 3600)
            ->withHeader('Access-Control-Allow-Credentials', 'true');
    }
}