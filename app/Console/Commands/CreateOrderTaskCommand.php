<?php

namespace App\Console\Commands;

use App\Jobs\CreateOrderTask;
use App\Models\Order;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CreateOrderTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-order-task-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::query()->get();

        if ($orders->isEmpty()) {
            $this->info('Нет заказов без task_id для обработки.');
            return; // Если нет заказов без task_id, то выходим
        }

        foreach ($orders as $order) {

            // Получаем пользователя с id = $order->user_id
            $user = User::where('id', $order->user_id)->first();

            $cartItems = $order->orderProducts()->get();  // Получаем товары пользователя


            $cartItemsArray = $cartItems->toArray();

            $address = $order->address;
            $phone = $order->phone;

            // Отправляем задачу в очередь
            CreateOrderTask::dispatch($order, $user, $cartItemsArray, $address, $phone);

        }
    }
}
