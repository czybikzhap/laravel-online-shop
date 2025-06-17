<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class PaymentStatusController extends Controller
{
    public function success(Request $request)
    {
        // Найди заказ по payment_id, если сохранил его при создании платежа
        $paymentId = $request->query('payment_id');

        if ($paymentId !== null) {
            return view('payment.result', [
                'status' => 'failed',
                'message' => 'Неизвестный платёж. ID не передан.'
            ]);
        }

        $response = Http::withBasicAuth(
            config('services.yookassa.shop_id'),
            config('services.yookassa.secret_key')
        )->get("https://api.yookassa.ru/v3/payments/{$paymentId}");

        if (!$response->successful()) {
            Log::error('Ошибка при запросе статуса платежа в YooKassa', [
                'payment_id' => $paymentId,
                'response' => $response->body()
            ]);

            return view('payment.result', [
                'status' => 'failed',
                'message' => 'Ошибка при получении статуса платежа.'
            ]);
        }

        $payment = $response->json();
        $status = $payment['status'] ?? 'unknown';

        $order = Order::query()->where('payment_id', $paymentId)->first();

        if ($order) {
            $order->update(['status' => $status]);
        }

        return view('payment.result', [
            'status' => $status,
            'message' => $this->statusMessage($status)
        ]);
    }

    private function statusMessage(string $status): string
    {
        return match ($status) {
            'succeeded' => '✅ Оплата прошла успешно. Спасибо за покупку!',
            'pending' => '⌛ Оплата в процессе. Мы обновим статус заказа после подтверждения.',
            'canceled' => '❌ Оплата была отменена.',
            default => '⚠️ Неизвестный статус платежа.',
        };
    }


}
