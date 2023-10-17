<?php

$builder = new \DI\ContainerBuilder();

$builder->addDefinitions(__DIR__ . '/service_dependencies.php');
$builder->addDefinitions(__DIR__ . '/settings.php');

$c = $builder->build();

$app = \Slim\Factory\AppFactory::createFromContainer($c);

$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, false, false);

$errorHandler = $errorMiddleware->getDefaultErrorHandler();
$errorHandler->forceContentType('application/json');

$capsule = new \Illuminate\Database\Capsule\Manager();

$capsule->addConnection(parse_ini_file("auth.db.ini"), 'auth');
$capsule->setAsGlobal();
$capsule->bootEloquent();

return $app;
