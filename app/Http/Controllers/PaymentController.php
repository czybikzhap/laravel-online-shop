<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessYooKassaWebhook;


class PaymentController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $data = $request->all();

        // Подстраховка от некорректного запроса
        if (!isset($data['event'])) {
            abort(400, 'Bad request');
        }

        // Асинхронная обработка
        dispatch(new ProcessYooKassaWebhook($data));

        return response()->json(['status' => 'ok']);
    }
}
