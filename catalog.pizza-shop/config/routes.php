<?php
declare(strict_types=1);

return function(\Slim\App $app):void {

    $app->get('/produits[/]', \pizzashop\catalog\app\actions\GetProduitsAction::class)
        ->setName('produits');

    $app->get('/produit/{id_produit}[/]', \pizzashop\catalog\app\actions\GetProduitAction::class)
        ->setName('produit_id');

    $app->get('/categories/{id_categorie}/produits[/]', \pizzashop\catalog\app\actions\GetProduitsCategorieAction::class)
        ->setName('produits_categorie');

};