<?php
require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;




$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
// create queue
$channel->queue_declare('hello', false, false, false, false);
// create message
$msg = new AMQPMessage('HELLO WORLD');
// send message
$channel->basic_publish($msg, '', 'hello');

$channel->close();
$connection->close();