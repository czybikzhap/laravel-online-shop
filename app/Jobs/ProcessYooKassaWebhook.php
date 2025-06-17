<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
            $order = Order::query()->where('payment_id', $paymentId)->first();
            if ($order) {
                $order->update(['status' => 'paid']);

                Log::info('Order payment succeeded', [
                    'order_id' => $order->id,
                    'payment_id' => $paymentId,
                    'timestamp' => now(),
                ]);

            } else {
                Log::warning('Order not found for successful payment', [
                    'payment_id' => $paymentId,
                    'timestamp' => now(),
                ]);
            }
        } else {
            Log::notice('Unhandled YooKassa event', [
                'event' => $event,
                'timestamp' => now(),
            ]);
        }
    }
}
