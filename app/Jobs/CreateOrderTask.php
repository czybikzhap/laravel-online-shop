<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CreateOrderTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Order $order;
    protected User $user;
    protected $cartItems;
    protected string $address;
    protected string $phone;

    // Конструктор, который принимает все необходимые данные
    public function __construct(Order $order, User $user, $cartItems, $address, $phone)
    {
        $this->order = $order;
        $this->user = $user;
        $this->cartItems = $cartItems;
        $this->address = $address;
        $this->phone = $phone;
    }

    public function handle()
    {
        try {
            // Создание задачи в Yougile
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . 'L4vpFnujCfiCky1+4jsf5jMAcfvw3JNyO2NfuB1irlQwp30JbaraZFN5MhmC9pip'
            ])->post('https://ru.yougile.com/api-v2/tasks', [
                'title' => 'Сборка заказа #' . $this->order->id,
                'columnId' => '4ca91005-e436-4d0a-b47f-f01c1b8d0d77',  // ID колонки
                'description' => json_encode([  // Сериализация данных заказа в строку
                    'order_id' => $this->order->id,
                    'user_id' => $this->user->id,
                    'address' => $this->address,
                    'phone' => $this->phone,
                    'cart_items' => $this->cartItems->map(function ($item) {
                        return [
                            'product_id' => $item->product_id,
                            'amount' => $item->amount,
                        ];
                    })->toArray(),
                ]),
            ]);

            // Проверка успешности запроса
            if ($response->failed()) {
                Log::error('Ошибка создания задачи в Yougile: ' . $response->body());
                throw new \Exception('Ошибка создания задачи в Yougile: ' . $response->body());
            }

            Log::info('Задача успешно создана в Yougile: ' . $response->body());

        } catch (\Exception $exception) {
            Log::error('Ошибка при обработке задачи в Yougile: ' . $exception->getMessage());
            throw $exception;  // Это позволит повторить задачу в очереди в случае ошибки
        }

    }
}
