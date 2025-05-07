<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5673, 'admin', 'admin');

$channel = $connection->channel();

$channel->queue_declare('hello', false, false, false, false);
                                    // Объявляем очередь (должна совпадать с очередью отправителя)
echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {                   // Функция-обработчик для полученных сообщений
    echo ' [x] Received ', $msg->body, "\n";
};

$channel->basic_consume('hello', '', false, true, false, false, $callback);
                                                // Подписываемся на очередь и регистрируем callback
try {
    $channel->consume();
} catch (\Throwable $exception) {
    echo $exception->getMessage();
}
