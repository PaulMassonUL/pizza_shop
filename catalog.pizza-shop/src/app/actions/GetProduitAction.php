<?php

namespace pizzashop\catalog\app\actions;


use pizzashop\catalog\domain\service\catalogue\iInfoCatalogue;
use pizzashop\catalog\domain\service\catalogue\ServiceCatalogue;
use pizzashop\catalog\domain\service\catalogue\ServiceCatalogueNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class GetProduitAction extends Action
{
    private ServiceCatalogue $serviceCatalogue;

    public function __construct(iInfoCatalogue $serviceCatalogue)
    {
        $this->serviceCatalogue = $serviceCatalogue;
    }

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        try {
            $produit = $this->serviceCatalogue->getProduitById((int)$args['id_produit']);
            $data = [
                'type' => 'resource',
                'produit' => [
                    'id' => $produit->id,
                    'numero' => $produit->numero,
                    'libelle' => $produit->libelle_produit,
                    'description' => $produit->description,
                    'tarif_normale' => $produit->tarif_normale,
                    'tarif_grande' => $produit->tarif_grande,
                    'image' => $produit->image,
                    'categorie' => $produit->categorie
                ]
            ];
            $rs->getBody()->write(json_encode($data));
            return $rs->withHeader('Content-Type', 'application/json;charset=utf-8');

        } catch (ServiceCatalogueNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }

    }

}