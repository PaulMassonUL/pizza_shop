<?php

namespace pizzashop\auth\app\actions;

use pizzashop\auth\domain\dto\CredentialsDTO;
use pizzashop\auth\domain\service\AuthServiceCredentialsException;
use pizzashop\auth\domain\service\iAuth;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ValidateAction extends Action
{
    private iAuth $serviceAuth;

    public function __construct(iAuth $serviceAuth)
    {
        $this->serviceAuth = $serviceAuth;
    }

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {

        if (!$rq->hasHeader('Authorization')) return $rs->withStatus(400);

        try {
            $credentials = $rq->getHeader('Authorization')[0];
            $credentials = str_replace('Basic ', '', $credentials);
            $credentials = base64_decode($credentials);
            $credentials = explode(':', $credentials);

            $tokenDTO = $this->serviceAuth->signin(new CredentialsDTO($credentials[0], $credentials[1]));

            $rs->getBody()->write($tokenDTO->toJson());

            return $rs->withStatus(201);
        } catch (AuthServiceCredentialsException) {
            return $rs->withStatus(401);
        }
    }
}