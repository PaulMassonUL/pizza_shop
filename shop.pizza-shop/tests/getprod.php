<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as DB;
use pizzashop\shop\domain\entities\catalogue\Categorie;
use pizzashop\shop\domain\entities\catalogue\Taille;
use pizzashop\shop\domain\entities\catalogue\Produit;

$dbconf = __DIR__ . '/../config/catalog.db.ini';
$db = new DB();
$db->addConnection(parse_ini_file($dbconf), 'catalog');
$db->setAsGlobal();
$db->bootEloquent();

