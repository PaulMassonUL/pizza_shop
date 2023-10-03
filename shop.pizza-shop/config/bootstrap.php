<?php

$app = \Slim\Factory\AppFactory::create();
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, false, false);

$capsule = new \Illuminate\Database\Capsule\Manager();

$capsule->addConnection(parse_ini_file("commande.db.ini"), 'commande');
$capsule->addConnection(parse_ini_file("catalog.db.ini"), 'catalog');
$capsule->setAsGlobal();
$capsule->bootEloquent();

return $app;
