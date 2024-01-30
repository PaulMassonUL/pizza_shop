<?php

namespace pizzashop\gateway\app\actions;

use pizzashop\gateway\domain\service\iGateway;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UserAction extends Action
{
    private string $base_uri;

    public function __construct(iGateway $gatewayService, string $base_uri)
    {
        $this->serviceGateway = $gatewayService;
        $this->base_uri = $base_uri;
    }

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        return $this->serviceGateway->request($rq->getMethod(), $this->base_uri . $rq->getUri()->getPath(), [
            'headers' => $rq->getHeaders(),
            'json' => $rq->getParsedBody()
        ]);
    }
}