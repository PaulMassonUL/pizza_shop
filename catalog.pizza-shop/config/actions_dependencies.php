<?php

use pizzashop\catalog\app\actions\GetProduitAction;
use pizzashop\catalog\app\actions\GetProduitsAction;
use pizzashop\catalog\app\actions\GetProduitsCategorieAction;
use pizzashop\catalog\app\actions\GetProduitsCommandeAction;
use Psr\Container\ContainerInterface;

return [

    GetProduitsAction::class => function (ContainerInterface $container) {
        return new GetProduitsAction($container->get('catalog.service'));
    },

    GetProduitAction::class => function (ContainerInterface $container) {
        return new GetProduitAction($container->get('catalog.service'));
    },

    GetProduitsCategorieAction::class => function (ContainerInterface $container) {
        return new GetProduitsCategorieAction($container->get('catalog.service'));
    },

    GetProduitsCommandeAction::class => function (ContainerInterface $container) {
        return new GetProduitsCommandeAction($container->get('catalog.service'));
    },

];