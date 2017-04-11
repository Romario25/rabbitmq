<?php
use PhpAmqpLib\Connection\AMQPStreamConnection;

require_once __DIR__ . '/../vendor/autoload.php';

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// create queue
$channel->queue_declare('task_queue', false, true, false, false);

// create callback function
$callback = function ($msg) {
    echo " [x] Received ", $msg->body, "\n";
    sleep(substr_count($msg->body, '.'));
    echo " [x] Done", "\n";

    // отправляем серверу что задача выполнена, можешь удалять сообщение из очереди
    $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

// сообщаем серверу, чтобы равномерно распределял сообщения. Отсылал только тогда когда уже выполнена задача подписчиком
$channel->basic_qos(null, 1, null);

$channel->basic_consume('task_queue', '', false, false, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();