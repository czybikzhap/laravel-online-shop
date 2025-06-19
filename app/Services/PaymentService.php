<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function createPayment(float $amount, int $orderId, string $returnUrl): array
    {
        $shopId = config('services.yookassa.shop_id');
        $secretKey = config('services.yookassa.secret_key');

        $paymentData = [
            "amount" => [
                "value" => number_format($amount, 2, '.', ''),
                "currency" => "RUB",
            ],
            "payment_method_data" => [
                "type" => "bank_card"
            ],
            "confirmation" => [
                "type" => "redirect",
                "return_url" => $returnUrl,
            ],
            "capture" => true,
            "description" => "Order #{$orderId}",
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Idempotence-Key' => (string) Str::uuid(),
        ])->withBasicAuth($shopId, $secretKey)
            ->post('https://api.yookassa.ru/v3/payments', $paymentData);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Ошибка при создании платежа в YooKassa', [
            'response' => $response->body()
        ]);

        throw new \Exception('Ошибка при создании платежа: ' . $response->body());
    }
}
