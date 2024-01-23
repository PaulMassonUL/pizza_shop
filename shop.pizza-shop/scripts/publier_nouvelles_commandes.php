<?php

use PhpAmqpLib\Message\AMQPMessage;
use pizzashop\shop\domain\dto\commande\CommandeDTO;

require_once __DIR__ . '/../vendor/autoload.php';

$faker = Faker\Factory::create('fr_FR');

$message_host = 'rabbitmq';
$message_port = 5672;
$message_user = 'user';
$message_password = 'password';

$connection = new \PhpAmqpLib\Connection\AMQPStreamConnection(
    $message_host,
    $message_port,
    $message_user,
    $message_password
);

$channel = $connection->channel();

$item1 = [
    'numero' => 1,
    'taille' => 1,
    'quantite' => 1
];

$item2 = [
    'numero' => 2,
    'taille' => 2,
    'quantite' => 1
];

$commande = new CommandeDTO($faker->email, 1, [$item1, $item2]);
$commande->id = $faker->uuid;
$commande->date_commande = $faker->dateTimeInInterval('-1 hour', '+3 days')->format('Y-m-d H:i:s');
$commande->etat = 2;
$commande->montant_total = $faker->randomFloat(2, 10, 100);

$channel->basic_publish(new AMQPMessage(json_encode($commande)),
    'pizzashop',
    'nouvelle'
);

print " [x] commande publiée : '\n";
print json_encode($commande, JSON_PRETTY_PRINT);
print "'\n";

$channel->close();
$connection->close();
