<?php

namespace app\Services;

use YooKassa\Client;

class YooKassaService
{
    public function createPayment(float $totalCost, int $orderId, string $returnUrl): array
    {
        try {
            $client = new Client();
            $client->setAuth(config('services.yookassa.shop_id'), config('services.yookassa.secret_key'));

            $payment = $client->createPayment(
                [
                    'amount' => [
                        'value' => number_format($totalCost, 2, '.', ''),
                        'currency' => 'RUB',
                    ],
                    'confirmation' => [
                        'type' => 'redirect',
                        'return_url' => $returnUrl,
                    ],
                    'capture' => true,
                    'description' => "Order #{$orderId}",
                ],
                uniqid('', true)
            );

            return [
                'id' => $payment->getId(),
                'url' => $payment->getConfirmation()->getConfirmationUrl()
            ];
        } catch (\Throwable $e) {
            report($e); // Laravel сам залогирует в storage/logs/laravel.log

            // Для отладки: можно вернуть реальное сообщение (временно!)
            throw new \Exception("Ошибка в YooKassa: " . $e->getMessage());
            // Логируем или оборачиваем в кастомное исключение
            //report($e);
            //throw new \Exception("Ошибка создания платежа в YooKassa");
        }
    }
}
