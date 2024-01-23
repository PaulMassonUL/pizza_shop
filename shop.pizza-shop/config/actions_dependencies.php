<?php

use \pizzashop\shop\app\actions\AccederCommandeAction;
use pizzashop\shop\app\actions\CreerCommandeAction;
use \pizzashop\shop\app\actions\ValiderCommandeAction;
use Psr\Container\ContainerInterface;

return [

    ValiderCommandeAction::class => function (ContainerInterface $container) {
        $commandeService = $container->get('commande.service');
        $rabbitmqChannel = $container->get('rabbitmq.channel');
        return new ValiderCommandeAction($commandeService, $rabbitmqChannel);
    },

    AccederCommandeAction::class => function (ContainerInterface $container) {
        return new AccederCommandeAction($container->get('commande.service'));
    },

    CreerCommandeAction::class => function (ContainerInterface $container) {
        return new CreerCommandeAction($container->get('commande.service'));
    },

];