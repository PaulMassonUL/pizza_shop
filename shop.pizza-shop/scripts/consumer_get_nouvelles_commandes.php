<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;

require_once __DIR__ . '/../vendor/autoload.php';

$faker = Faker\Factory::create('fr_FR');

$message_host = 'rabbitmq';
$message_port = 5672;
$message_user = 'user';
$message_password = 'password';

$connection = new AMQPStreamConnection(
    $message_host,
    $message_port,
    $message_user,
    $message_password
);

$channel = $connection->channel();

$message_queue = 'nouvelles_commandes';

$msg = $channel->basic_get($message_queue);
if ($msg) {
    $content = json_decode($msg->body, true);
    print "[x] message reçu : \n" ;
    print json_encode($content, JSON_PRETTY_PRINT);
    $channel->basic_ack($msg->getDeliveryTag());
    print "\n";
} else {
    print "[x] pas de message reçu\n"; exit(0);
}

$channel->close();
$connection->close();
