<?php

namespace App\Services;

use App\Mail\TestMail;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitmqService
{

    private AMQPStreamConnection $connection;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->connection = new AMQPStreamConnection('rabbitmq', 5672, 'admin', 'admin');
    }

    public function produce(array $data, string $queueName)
    {

        $channel = $this->connection->channel();

        $channel->queue_declare($queueName, false, false, false, false);

        $data = json_encode($data);                 //преобразуем массив в JSON-формат
        $msg = new AMQPMessage($data);
        $channel->basic_publish($msg, '', $queueName);
    }

    /**
     * @throws \Exception
     */

    public function consume(string $queueName, callable $callback): void
    {
        $channel = $this->connection->channel();

        $channel->queue_declare($queueName, false, false, false, false);

        $channel->basic_consume($queueName, '', false, true, false, false, $callback);
        // Подписываемся на очередь и регистрируем callback
        try {
            $channel->consume();
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }

    }

}
