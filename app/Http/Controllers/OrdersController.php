<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\CartItems;
use App\Models\Order;
use App\Models\OrderProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function getOrders()
    {
        $user = Auth::user();
        return view('orders', ['user' => $user]);
    }

    public function addOrder(OrderRequest $request)
    {
        $user = Auth::user();
        $cartItems = $user->userProducts()->get();

        try {
            DB::beginTransaction();

            $order = Order::query()->create([
                'user_id' => $user->id,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);

            //throw new \Exception('test');

            foreach ($cartItems as $cartitem) {

                OrderProducts::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $cartitem->product_id,
                    'amount' => $cartitem->amount,
                ]);
            }
            CartItems::query()->where('user_id', $user->id)->delete();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            throw $exception;

        }



        return redirect('/catalog');
    }
}
