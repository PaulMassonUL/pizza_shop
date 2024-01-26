<?php

return [

    'logger' => function (\Psr\Container\ContainerInterface $c) {
        $log = new \Monolog\Logger($c->get('auth.log.name'));
        $log->pushHandler(new \Monolog\Handler\StreamHandler($c->get('auth.log.file'), $c->get('auth.log.level')));
        return $log;
    },

    'GatewayService' => function (\Psr\Container\ContainerInterface $c) {
        return new \pizzashop\gateway\domain\service\GatewayService();
    },

];