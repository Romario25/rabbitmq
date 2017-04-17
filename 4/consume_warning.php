<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;

require_once __DIR__ . '/../vendor/autoload.php';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('warning');
$channel->exchange_declare('ex_direct', 'direct');
$channel->queue_bind('warning', 'ex_direct');

$func = function ($msg) {
    echo $msg->body."\n";
};

$channel->basic_consume('warning', '', false, false, false, false, $func);

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();