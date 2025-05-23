<?php

namespace App\Console\Commands;

use App\Mail\TestMail;
use App\Models\User;
use App\Services\RabbitmqService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SignUpNotifyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sign-up-notify-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    private RabbitmqService $rabbitmqService;
    public function __construct(RabbitmqService $rabbitmqService)
    {
        parent::__construct();
        $this->rabbitmqService = $rabbitmqService;
    }


    /**
     * @throws \Exception
     */

    public function handle()
    {
        $callback = function ($msg) {
            $body = $msg->getBody();
            $data = json_decode($body, true);    // Декодируем JSON

            $userId = isset($data['id']) ? (int)$data['id'] : null;
            $user = User::query()->find($userId);

            if ($user) {
                Mail::to($user->email)->send(new TestMail(['name' => $user->name]));
            } else {
                // Логирование ошибки, если пользователь не найден
                Log::error("Пользователь с ID {$userId} не найден.");
            }
        };

        $this->rabbitmqService->consume('signUpEmail', $callback);
    }
}
