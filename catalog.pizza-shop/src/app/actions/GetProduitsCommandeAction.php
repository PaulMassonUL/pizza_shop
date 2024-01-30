<?php

namespace pizzashop\catalog\app\actions;


use pizzashop\catalog\domain\service\catalogue\iInfoCatalogue;
use pizzashop\catalog\domain\service\catalogue\ServiceCatalogue;
use pizzashop\catalog\domain\service\catalogue\ServiceCatalogueNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;

class GetProduitsCommandeAction extends Action
{
    private ServiceCatalogue $serviceCatalogue;

    public function __construct(iInfoCatalogue $serviceCatalogue)
    {
        $this->serviceCatalogue = $serviceCatalogue;
    }

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        try {
            $numerostaillesItems = $rq->getParsedBody();
            $produitsDTO = $this->serviceCatalogue->getProduitsCommande($numerostaillesItems);

            $data = [
                'type' => 'collection',
                'produits' => $produitsDTO
            ];

            $rs->getBody()->write(json_encode($data));
            return $rs->withStatus(200)->withHeader('Content-Type', 'application/json;charset=utf-8');
        } catch (ServiceCatalogueNotFoundException $e) {
            throw new HttpInternalServerErrorException($rq, $e->getMessage());
        }

    }

}