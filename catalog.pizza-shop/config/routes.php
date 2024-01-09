<?php
declare(strict_types=1);

return function(\Slim\App $app):void {

    $app->get('/produits[/]', \pizzashop\catalog\app\actions\GetProduitsAction::class)
        ->setName('produits');

    //$app->get('/produits/{id_produit}[/]', \pizzashop\shop\app\actions\AccederCommandeAction::class)
        //->setName('produit_id');

    //$app->get('/categories/{id_categorie}/produits[/]', \pizzashop\shop\app\actions\ValiderCommandeAction::class)
        //->setName('produits_categorie');

};