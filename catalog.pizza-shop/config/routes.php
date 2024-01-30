<?php
declare(strict_types=1);

return function(\Slim\App $app):void {

    $app->get('/api/produits[/]', \pizzashop\catalog\app\actions\GetProduitsAction::class)
        ->setName('produits');

    $app->get('/api/produits/{id_produit}[/]', \pizzashop\catalog\app\actions\GetProduitAction::class)
        ->setName('produit_id');

    $app->get('/api/categories/{id_categorie}/produits[/]', \pizzashop\catalog\app\actions\GetProduitsCategorieAction::class)
        ->setName('produits_categorie');

    $app->get('/produits-commande[/]', \pizzashop\catalog\app\actions\GetProduitsCommandeAction::class)
        ->setName('produits_commande');

};