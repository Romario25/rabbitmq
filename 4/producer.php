<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;

require_once __DIR__ . '/../vendor/autoload.php';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('error');
$channel->queue_declare('warning');
$channel->queue_declare('info');

$channel->exchange_declare('ex_direct', 'direct');

$channel->queue_bind('error', 'ex_direct', 'error_msg');
$channel->queue_bind('warning', 'ex_direct', 'warning_msg');
$channel->queue_bind('info', 'ex_direct', 'info_msg');

$type_error = $argv[1];

switch ($type_error) {
    case 1:
        $msg = 'ERROR message';
        $routing_key = 'error_msg';
        break;
    case 2:
        $msg = 'WARNING message';
        $routing_key = 'warning_msg';
        break;
    default:
        $msg = 'INFO message';
        $routing_key = 'info_msg';
        break;
}

echo $type_error;

$message = new \PhpAmqpLib\Message\AMQPMessage($msg);

$channel->basic_publish($message, 'ex_direct', $routing_key);


$channel->close();
$connection->close();