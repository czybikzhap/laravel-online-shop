<?php

namespace App\Http\Controllers;

class PaymentStatusController extends Controller
{
    public function success()
    {
        return view('payment.success');
    }

    public function canceled()
    {
        return view('payment.canceled');
    }
}
