<?php

use pizzashop\gateway\app\actions\CommandeAction;
use pizzashop\gateway\app\actions\ProduitAction;
use pizzashop\gateway\app\actions\UserAction;
use Psr\Container\ContainerInterface;

return [

    UserAction::class => function (ContainerInterface $container) {
        return new UserAction($container->get('GatewayService'), $container->get('gateway.auth_origin'));
    },

    CommandeAction::class => function (ContainerInterface $container) {
        return new CommandeAction($container->get('GatewayService'), $container->get('gateway.shop_origin'));
    },

    ProduitAction::class => function (ContainerInterface $container) {
        return new ProduitAction($container->get('GatewayService'), $container->get('gateway.catalog_origin'));
    },

];