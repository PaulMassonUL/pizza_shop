<?php

namespace pizzashop\catalog\app\actions;

use pizzashop\catalog\domain\service\catalogue\iInfoCatalogue;
use pizzashop\catalog\domain\service\catalogue\ServiceCatalogue;
use pizzashop\catalog\domain\service\catalogue\ServiceCatalogueNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class GetProduitsCategorieAction extends Action
{
    private ServiceCatalogue $serviceCatalogue;

    public function __construct(iInfoCatalogue $serviceCatalogue)
    {
        $this->serviceCatalogue = $serviceCatalogue;
    }

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        try{
            $produits = $this->serviceCatalogue->getProduitsCategorie($args['id_categorie']);
            $data = [
                'type' => 'collection',
                'produits' => []
            ];
            foreach ($produits as $produit) {
                $data['produits'][] = [
                    'id' => $produit->id,
                    'numero' => $produit->numero,
                    'libelle' => $produit->libelle_produit,
                    'links' => [
                        [
                            'rel' => 'detail',
                            'href' => $rq->getUri()->getScheme() . '://' . $rq->getUri()->getHost() . ':' . $rq->getUri()->getPort() . '/produit/' . $produit->id
                        ]
                    ]
                ];
            }
            $rs->getBody()->write(json_encode($data));
            return $rs->withHeader('Content-Type', 'application/json;charset=utf-8');
        } catch (ServiceCatalogueNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }

    }

}