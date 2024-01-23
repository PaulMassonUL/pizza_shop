<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

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

$callback = function(AMQPMessage $msg) {
    $msg_body = json_decode($msg->body, true); print "[x] message reçu : \n";
    print json_encode($msg_body, JSON_PRETTY_PRINT);
    $msg->getChannel()->basic_ack($msg->getDeliveryTag());
};

$msg = $channel->basic_consume($message_queue, '', false, false, false, false, $callback );

try {
    $channel->consume();
} catch (Exception $e) { print $e->getMessage();
}
$channel->close(); $connection->close();
