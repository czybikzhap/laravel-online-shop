<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderProducts;

class Controller
{
    public function getTestInfo()
    {
        $orderProducts = OrderProducts::with('product')->where('order_id', 203)->get();

        dd($orderProducts->toArray());  // Для отладки

        return $data;
    }
}
