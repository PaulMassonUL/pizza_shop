<?php
declare(strict_types=1);

use pizzashop\shop\domain\middleware\Jwt;

return function(\Slim\App $app):void {

    $JwtVerification = new Jwt($app->getContainer()->get('auth.api.base_uri'));

    $app->post('/api/commandes/creer[/]', \pizzashop\shop\app\actions\CreerCommandeAction::class)
        ->setName('creer_commande')->add($JwtVerification);

    $app->get('/api/commandes/{id_commande}[/]', \pizzashop\shop\app\actions\AccederCommandeAction::class)
        ->setName('commande')->add($JwtVerification);

    $app->patch('/api/commandes/{id_commande}[/]', \pizzashop\shop\app\actions\ValiderCommandeAction::class)
        ->setName('valider_commande')->add($JwtVerification);
    
};