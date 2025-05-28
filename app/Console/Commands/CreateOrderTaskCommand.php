<?php

namespace App\Console\Commands;

use App\Jobs\CreateOrderTask;
use App\Models\Order;
use App\Models\OrderProducts;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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

        Log::info('handle() запущен');
        echo '==> Вызов handle()' . PHP_EOL;

        // Получаем все заказы без task_id
        $orders = Order::whereNull('task_id')->get();
        //$orders = DB::table('orders')->whereNull('task_id')->get();


        if ($orders->isEmpty()) {
            $this->info('Нет заказов для обработки.');
            return; // Выход из команды, если нет заказов
        }


        // Логируем количество заказов, которые нужно обработать
        $this->info("Обрабатывается " . $orders->count() . " заказов.");

        // Перебираем заказы
        foreach ($orders as $order) {
            // Получаем пользователя с id = $order->user_id
            $user = User::where('id', $order->user_id)->first();

            // Получаем товары из таблицы order_products для данного заказа
            $orderProducts = OrderProducts::with('product')->where('order_id', $order->id)->get();

            $cartItemsArray = $orderProducts->toArray();

            $address = $order->address;
            $phone = $order->phone;

            // Отправляем задачу в очередь
            CreateOrderTask::dispatch($order, $user, $cartItemsArray, $address, $phone);

            // Выводим информацию о том, что задача была отправлена
            $this->info("Задача для заказа #{$order->id} отправлена в очередь.");
        }

    }
}
