<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessYooKassaWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public array $data) {}

    public function handle(): void
    {
        $event = $this->data['event'] ?? null;

        if ($event === 'payment.succeeded') {
            $paymentId = $this->data['object']['id'];
            // Логика обновления заказа
            $order = Order::where('payment_id', $paymentId)->first();
            if ($order) {
                $order->update(['status' => 'paid']);
            }
        }
    }
}
