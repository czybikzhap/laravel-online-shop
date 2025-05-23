<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Jobs\CreateOrderTask;
use App\Models\CartItems;
use App\Models\Order;
use App\Models\OrderProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OrdersController extends Controller
{

    protected $cartItemsController;
    public function __construct(CartItemsController $cartItemsController)
    {
        $this->cartItemsController = $cartItemsController;
    }

    public function getOrders()
    {
        $user = Auth::user();
        $totalCost = $this->cartItemsController->totalCost();

        return view('orders', ['user' => $user, 'totalCost' => $totalCost]);
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

            CreateOrderTask::dispatch($order, $user, $cartItems, $request->address, $request->phone);

        } catch (\Exception $exception) {
            DB::rollBack();

            throw $exception;

        }


        return redirect('/catalog');
    }
}
