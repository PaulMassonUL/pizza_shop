<?php

namespace pizzashop\shop\app\actions;

use PhpAmqpLib\Message\AMQPMessage;
use pizzashop\shop\domain\entities\commande\Commande;
use pizzashop\shop\domain\service\commande\iCommander;
use pizzashop\shop\domain\service\commande\ServiceCommande;
use pizzashop\shop\domain\service\commande\ServiceCommandeInvalidTransitionException;
use pizzashop\shop\domain\service\commande\ServiceCommandeNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

class ValiderCommandeAction extends Action
{

    private ServiceCommande $serviceCommande;
    private $channel;

    public function __construct(iCommander $serviceCommande, $channel)
    {
        $this->serviceCommande = $serviceCommande;
        $this->channel = $channel;
    }

    public function __invoke(Request $rq, Response $rs, array $args): Response
    {
        $id_commande = $args['id_commande'] ?? null;
        if (is_null($id_commande)) throw new HttpBadRequestException($rq, "Missing id_commande");

        try {
            $commande = $this->serviceCommande->validerCommande($id_commande);

            $etat = match ($commande->etat) {
                Commande::ETAT_CREE => Commande::ETAT_CREE_LIBELLE,
                Commande::ETAT_VALIDE => Commande::ETAT_VALIDE_LIBELLE,
                Commande::ETAT_PAYEE => Commande::ETAT_PAYEE_LIBELLE,
                default => '',
            };

            $rs->getBody()->write(json_encode(['etat' => $etat]));
            $this->channel->basic_publish(new AMQPMessage(json_encode($commande)),
                'pizzashop',
                'nouvelle'
            );
            $this->channel->close();
        } catch (ServiceCommandeNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        } catch (ServiceCommandeInvalidTransitionException $e) {
            $rs->getBody()->write(json_encode(['etat' => $e->getMessage()]));
            return $rs->withStatus(400)->withHeader('Content-Type', 'application/json;charset=utf-8');
        }

        return $rs->withHeader('Content-Type', 'application/json;charset=utf-8');
    }
}