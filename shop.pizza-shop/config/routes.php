<?php
declare(strict_types=1);

return function( \Slim\App $app):void {

    $app->post('/commandes[/]', \pizzashop\shop\app\actions\CreerCommandeAction::class)
        ->setName('creer_commande');

    $app->get('/commandes/{id_commande}[/]', \pizzashop\shop\app\actions\ValiderCommandeAction::class)
        ->setName('commande');

    $app->patch('/commandes/{id_commande}[/]', \pizzashop\shop\app\actions\ValiderCommandeAction::class)
        ->setName('valider_commande');
};