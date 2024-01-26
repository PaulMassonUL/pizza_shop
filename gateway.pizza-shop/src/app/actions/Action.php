<?php

namespace pizzashop\gateway\app\actions;

use pizzashop\gateway\domain\service\iGateway;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

abstract class Action
{

    protected iGateway $serviceGateway;

    abstract public function __invoke(Request $rq, Response $rs, array $args): Response;
}