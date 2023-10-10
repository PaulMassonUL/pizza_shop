<?php

namespace pizzashop\shop\app\actions;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use pizzashop\shop\domain\service\commande\ServiceCommande;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Symfony\Component\Console\Exception\CommandNotFoundException;

class AccederCommandeAction extends Action
{

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        if(is_null($args['id_commande'])) throw new HttpBadRequestException($rq, 'id_commande manquant');
        try {
            $serviceProduits = new \pizzashop\shop\domain\service\catalogue\ServiceCatalogue();
            $logger = new Logger('api.actions.access_command');
            $logger->pushHandler(new StreamHandler('../log/log.log'));
            $commandeService = new ServiceCommande($serviceProduits, $logger);

            $commande = $commandeService->accederCommande($args['id_commande']);

            $data = [
                'type' => 'resource',
                'commande' => [
                    'id' => $commande->id,
                    'date_commande' => $commande->date,
                    'type_livraison' => $commande->type_livraison,
                    'etat' => $commande->etat,
                    'mail_client' => $commande->mail_client,
                    'montant_total' => $commande->montant_total,
                    'delai' => $commande->delai
                ]
            ];
            foreach ($commande->items as $item) {
                $data['commande']['items'] = [
                    'numero' => $item->numero,
                    'taille' => $item->taille,
                    'quantite' => $item->quantite,
                    'libelle' => $item->libelle,
                    'libelle_taille' => $item->libelle_taille,
                    'tarif' => $item->tarif,
                ];
            }
            $routeParser = \Slim\Routing\RouteContext::fromRequest($rq)->getRouteParser();
            $data['links'] = [
                'self' => [
                    'href' => $routeParser->urlFor('commande', ['id_commande' => $commande->id]),
                ],
                'valider' => [
                    'href' => $routeParser->urlFor('valider_commande', ['id_commande' => $commande->id]),
                ]


            ];

            $rs->getBody()->write(json_encode($data));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(200);

        } catch (CommandNotFoundException) {
            return $rs->withHeader('Content-Type', 'application/json')->withStatus(404);
        }

    }

}