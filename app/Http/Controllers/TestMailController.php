<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class TestMailController extends Controller
{
    public function send()
    {
        $data = ['name' => 'sigma'];

        Mail::to('czybikzhap@mail.ru')->send(new TestMail($data));

        echo "письмо отправилось";

    }

    /**
     * @throws \Exception
     */
    public function receive()
    {
        require_once __DIR__ . '/../../../vendor/autoload.php';

        $connection = new AMQPStreamConnection('rabbitmq', 5673, 'admin', 'admin');
        $channel = $connection->channel();

        $channel->queue_declare('hello', false, false, false, false);

        $callback = function ($msg) use ($channel) {
            $deliveryTag = $msg->delivery_info['delivery_tag']; // получаем delivery tag сообщения

            $userId = $msg->body;
            $user = User::query()->find($userId);

            Log::info("Получено сообщение с userId: {$userId}, deliveryTag: {$deliveryTag}");

            if ($user) {
                try {
                    Mail::to($user->email)->send(new TestMail(['name' => $user->name]));
                    Log::info("Письмо успешно отправлено пользователю {$user->email}");
                    Log::info("Подтверждение сообщения с deliveryTag: {$deliveryTag}");
                    $channel->basic_ack($deliveryTag);
                } catch (\Exception $e) {
                    Log::error("Ошибка при отправке письма: " . $e->getMessage());
                    // Сообщение не подтверждаем, чтобы оно осталось в очереди и могло быть повторно обработано
                    $channel->basic_nack($deliveryTag, false, true);
                }
            } else {
                Log::error("Пользователь с ID {$userId} не найден. Сообщение будет подтверждено и пропущено.");
                // Подтверждаем обработку сообщения, чтобы убрать его из очереди
                $channel->basic_ack($deliveryTag);
            }
        };

        $channel->basic_consume('hello', '', false, true, false, false, $callback);
        // Подписываемся на очередь и регистрируем callback
        try {
            $channel->consume();
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }

    }
}
