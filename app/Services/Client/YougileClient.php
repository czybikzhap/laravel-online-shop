<?php

namespace App\Services\Client;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class YougileClient
{
    private string $baseUrl;
    private string $apiKey;


    public function __construct()
    {
        $this->baseUrl = 'https://ru.yougile.com/api-v2';
        $this->apiKey = env('YOUGILE_API_KEY');
    }

    public function createTask(array $data): string
    {

        try {
            // Создание задачи в Yougile
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey
            ])->post($this->baseUrl . '/tasks', $data);

            if ($response->failed()) {
                Log::error('Ошибка создания задачи в Yougile: ' . $response->body());
                throw new \Exception('Ошибка создания задачи в Yougile: ' . $response->body());
            }

            Log::info('Задача успешно создана в Yougile: ' . $response->body());

            $taskId = $response->json();

            return $taskId['id'];

        } catch (\Exception $exception) {
            Log::error('Ошибка при обработке задачи в Yougile: ' . $exception->getMessage());
            throw $exception;  // Это позволит повторить задачу в очереди в случае ошибки
        }

    }

    public function deleteTask()
    {

    }

}
