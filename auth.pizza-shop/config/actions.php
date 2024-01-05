<?php

use pizzashop\auth\app\actions\RefreshAction;
use pizzashop\auth\app\actions\SigninAction;
use pizzashop\auth\app\actions\ValidateAction;
use Psr\Container\ContainerInterface;

return [

    SigninAction::class => function (ContainerInterface $container) {
        return new SigninAction($container->get('AuthService'));
    },

    ValidateAction::class => function (ContainerInterface $container) {
        return new ValidateAction($container->get('AuthService'));
    },

    RefreshAction::class => function (ContainerInterface $container) {
        return new RefreshAction($container->get('AuthService'));
    },

];