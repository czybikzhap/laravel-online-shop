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
        //dd($request->all());
        // print_r($request);die;

        Order::query()->create([
            'user_id' => $user->id,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        $orders = Order::query()
            ->where('user_id', $user->id)
            ->first();

        //var_dump($orders);die;
        //foreach ($orders as $order) {print_r($order);}die;


        $cartItem = CartItems::query()
            ->where('user_id', $user->id)
            ->get();


        foreach ($cartItem as $item) {
            $productIds[] = $item->product_id;
        }
        //print_r($productIds);

        foreach ($cartItem as $item) {
            $amounts[] = $item->amount;
        }
        //print_r($amounts);die;


        foreach ($productIds as $index => $productId) {
            $amount = $amounts[$index];

            OrderProducts::query()->create([
                'order_id' => $orders->id,
                'product_id' => $productId,
                'amount' => $amount,
            ]);
        }



        return redirect('/catalog');
    }
}
