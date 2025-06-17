<?php

namespace app\Services;

use Illuminate\Support\Facades\Log;
use YooKassa\Client;

class YooKassaService
{
    public function createPayment(float $totalCost, int $orderId, string $returnUrl): array
    {
        try {

            $client = new Client();
            $client->setAuth(config('services.yookassa.shop_id'), config('services.yookassa.secret_key'));

            $paymentData =  [
                "amount" => [
                    "value" => "100.00",
                    "currency" => "RUB",
                ],
                "payment_method_data" => [
                    "type" => "bank_card"
                ],
                "confirmation" => [
                    "type" => "redirect",
                    "return_url" => "https://httpbin.org/get",
                ],
                "description" => "Тестовая оплата",
            ];

            // Логируем данные перед отправкой
            Log::info('Отправка данных на YooKassa:', $paymentData);

            Log::info('YooKassa Configuration:', [
                'shop_id' => config('services.yookassa.shop_id'),
                'secret_key' => config('services.yookassa.secret_key'),
            ]);

            $payment = $client->createPayment($paymentData, uniqid('', true));

            return [
                'id' => $payment->getId(),
                'url' => $payment->getConfirmation()->getConfirmationUrl()
            ];
        } catch (\Throwable $e) {
            report($e);

            throw new \Exception("Ошибка в YooKassa: " . $e->getMessage());
        }
    }
}
