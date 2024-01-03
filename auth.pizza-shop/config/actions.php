<?php

use pizzashop\auth\app\actions\SigninAction;
use Psr\Container\ContainerInterface;

return [

    SigninAction::class => function (ContainerInterface $container) {
        return new SigninAction($container->get('AuthService'));
    },

];