<?php

namespace pizzashop\auth\app\actions;

use pizzashop\auth\domain\dto\TokenDTO;
use pizzashop\auth\domain\service\AuthServiceInvalidTokenException;
use pizzashop\auth\domain\service\iAuth;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class RefreshAction extends Action
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
            $token = $rq->getHeader('Authorization')[0];
            $token = str_replace('Bearer ', '', $token);

            $tokenDTO = $this->serviceAuth->refresh(new TokenDTO(null, $token));

            $rs->getBody()->write($tokenDTO->toJson());

            return $rs->withStatus(201);
        } catch (AuthServiceInvalidTokenException $e) {
            $rs->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $rs->withStatus(401);
        }
    }
}