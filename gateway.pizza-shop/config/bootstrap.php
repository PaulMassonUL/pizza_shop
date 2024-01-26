<?php

use pizzashop\gateway\domain\middleware\Cors;

$builder = new \DI\ContainerBuilder();

$builder->addDefinitions(__DIR__ . '/service_dependencies.php');
$builder->addDefinitions(__DIR__ . '/settings.php');
$builder->addDefinitions(__DIR__ . '/actions.php');

$c = $builder->build();

$app = \Slim\Factory\AppFactory::createFromContainer($c);

$app->add(new Cors());

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, false, false);

$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json');

return $app;
