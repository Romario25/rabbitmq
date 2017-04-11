<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;


require_once __DIR__ . '/../vendor/autoload.php';


$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
// create queue
$channel->queue_declare('hello', false, false, false, false);

// create callback function

$callback = function ($msg) {
  echo "RESPONSE : ".$msg->body."\n";
};

// registration function
$channel->basic_consume('hello', '', false, true, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}