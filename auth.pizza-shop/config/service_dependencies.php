<?php

return [

    'commande.logger' => function (\Psr\Container\ContainerInterface $c) {
        $log = new \Monolog\Logger($c->get('log.commande.name'));
        $log->pushHandler(new \Monolog\Handler\StreamHandler($c->get('log.commande.file'), $c->get('log.commande.level')));
        return $log;
    },

    'jwt.manager' => function (\Psr\Container\ContainerInterface $c) {
        return new \pizzashop\auth\api\manager\JwtManager();
    },

    'auth.provider' => function (\Psr\Container\ContainerInterface $c) {
        return new \pizzashop\auth\api\provider\AuthProvider();
    },

    'auth.service' => function (\Psr\Container\ContainerInterface $c) {
        return new \pizzashop\auth\api\service\AuthService($c->get('jwt.manager'), $c->get('auth.provider'), $c->get('auth.logger'));
    },


];