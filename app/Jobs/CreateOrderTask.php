<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\User;
use App\Services\Client\DTO\CreateTitle;
use App\Services\Client\YougileClient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;


class CreateOrderTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Order $order;
    protected User $user;
    protected Collection $cartItems;
    protected string $address;
    protected string $phone;

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
        // Создание объекта для генерации титульника
        $createTitle = new CreateTitle([
            'order_id' => $this->order->id,
            'user_id' => $this->user->id,
            'address' => $this->address,
            'phone' => $this->phone,
            'cart_items' => $this->cartItems,
        ]);

        $titleData = $createTitle->createTitle();

        $data = [
            'title' => $titleData['title'],
            'columnId' => $titleData['columnId'],
            'description' => $titleData['description']
        ];

        $yougileClient = new YougileClient();

        try {
            $yougileClient->createTask($data);
            Log::info('Задача успешно создана в Yougile.');
        } catch (\Exception $exception) {
            Log::error('Не удалось создать задачу: ' . $exception->getMessage());
        }
    }
}
