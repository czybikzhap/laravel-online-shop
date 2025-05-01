<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\CartItems;
use App\Models\Order;
use App\Models\OrderProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    public function getOrders()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return view('orders', ['user' => $user]);
        } else {
            return redirect('/login');
        }

    }

    public function addOrder(OrderRequest $request)
    {
        $user = Auth::user();

        $order = Order::query()->create([
            'user_id' => $user->id,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        $cartItem = $user->userProducts()->get();
        $amountProducts = $cartItem->pluck('amount');

        $products = $user->products()->get();
        $productIds = $products->pluck('id');

        foreach ($products as $index => $product) {
            $amount = $amountProducts[$index];
            OrderProducts::query()->create([
                'order_id' => $order->id,
                'product_id' => $product['id'],
                'amount' => $amount,
            ]);
        }



        return redirect('/catalog');
    }
}
