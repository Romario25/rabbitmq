<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;

require_once __DIR__ . '/../vendor/autoload.php';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('queue1');
$channel->exchange_declare('ex', 'fanout');
$channel->queue_bind('queue1', 'ex');

$func = function ($msg) {
    echo $msg->body."\n";
};

$channel->basic_consume('queue1', '', false, false, false, false, $func);

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();