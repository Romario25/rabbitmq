<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/../vendor/autoload.php';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$message = new AMQPMessage('Hello world');

// create two queue
$channel->queue_declare('queue1');
$channel->queue_declare('queue2');

// create exchange
$channel->exchange_declare('ex', 'fanout');

// create queue bind
$channel->queue_bind('queue1', 'ex');
$channel->queue_bind('queue2', 'ex');

// send message
$channel->basic_publish($message, 'ex');