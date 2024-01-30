<?php
declare(strict_types=1);

use pizzashop\gateway\app\actions\CommandeAction;
use pizzashop\gateway\app\actions\ProduitAction;
use pizzashop\gateway\app\actions\UserAction;

return function (\Slim\App $app): void {

    $app->group('/api/', function ($app) {
        $app->group('users', function ($app) {
            $app->post('/signin[/]', UserAction::class);
            $app->post('/signup[/]', UserAction::class);
            $app->get('/validate[/]', UserAction::class);
            $app->post('/refresh[/]', UserAction::class);
        });

        $app->group('commandes', function ($app) {
            $app->post('/creer[/]', CommandeAction::class);
            $app->get('/{id}[/]', CommandeAction::class); // get
            $app->patch('/{id}[/]', CommandeAction::class); // validate
        });

        $app->group('produits', function ($app) {
            $app->get('[/]', ProduitAction::class);
            $app->get('/{id}[/]', ProduitAction::class);
            $app->get('/categories/{id_categorie}[/]', ProduitAction::class);
        });

        $app->group('categories', function ($app) {
            $app->get('/{id}/produits[/]', ProduitAction::class);
        });
    });

};