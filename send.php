<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;



$connection = new AMQPStreamConnection('localhost', 5673,
        'admin', 'admin');                // Создаем соединение с RabbitMQ
$channel = $connection->channel();                      // Открываем канал

$channel->queue_declare('hello', false, false,
    false, false);               // Объявляем очередь с именем 'hello'

$msg = new AMQPMessage('Hello World!');       // Создаем сообщение
$channel->basic_publish($msg, '', 'hello');  // Публикуем сообщение в очередь 'hello'

echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();                               // Закрываем канал и соединение
