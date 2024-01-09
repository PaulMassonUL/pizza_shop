<?php

use pizzashop\catalog\app\actions\GetProduitsAction;
use Psr\Container\ContainerInterface;

return [

    GetProduitsAction::class => function (ContainerInterface $container) {
        return new GetProduitsAction($container->get('catalog.service'));
    },

];