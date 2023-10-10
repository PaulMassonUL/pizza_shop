<?php

namespace pizzashop\shop\app\actions;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use pizzashop\shop\domain\service\catalogue\ServiceCatalogue;
use pizzashop\shop\domain\service\commande\ServiceCommande;
use pizzashop\shop\domain\service\commande\ServiceCommandeInvalidTransitionException;
use pizzashop\shop\domain\service\commande\ServiceCommandeNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class ValiderCommandeAction extends Action
{
    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $id_commande = $args['id_commande'] ?? null;
        if (is_null($id_commande)) throw new HttpBadRequestException($rq, "Missing id_commande");


        //En cas d'erreur, un message JSON est retourné, dans une réponse avec un status de retour adéquat :
        //• 404 pour une commande inexistante
        //• 400 pour une requête invalide : la requête est déjà validée, ou la transition demandée n'est
        //pas correcte (par exemple : { "etat" : "payee" })
        //• 500 pour une erreur interne du serveur.
        //En cas de succès, la réponse retournée est formatée

        $catalogueService = new ServiceCatalogue();
        $logger = new Logger('api.actions.validate_command');
        $logger->pushHandler(new StreamHandler('../log/log.log'));
        $commandeService = new ServiceCommande($catalogueService, $logger);

        try {
            $commande = $commandeService->validerCommande($id_commande);

            $routeParser = \Slim\Routing\RouteContext::fromRequest($rq)->getRouteParser();

            $rs->getBody()->write(json_encode([
                'type' => 'resource',
                'commande' => [
                    'id' => $commande->id,
                    'date_commande' => $commande->date,
                    'type_livraison' => $commande->type_livraison,
                    'etat' => $commande->etat,
                    'mail_client' => $commande->mail_client,
                    'montant_total' => $commande->montant_total,
                    'delai' => $commande->delai
                ],
                'links' => [
                    'self' => [
                        'href' => $routeParser->urlFor('commande', ['id_commande' => $commande->id]),
                    ]
                ]
            ]));
        } catch (ServiceCommandeNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        } catch (ServiceCommandeInvalidTransitionException $e) {
            throw new HttpBadRequestException($rq, $e->getMessage());
        }

        return $rs->withHeader('Content-Type', 'application/json');
    }
}