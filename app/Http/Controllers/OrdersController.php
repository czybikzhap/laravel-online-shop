<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Jobs\CreateOrderTask;
use App\Models\CartItems;
use App\Models\Order;
use App\Models\OrderProducts;
use App\Services\Client\YougileClient;
use app\Services\YooKassaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use YooKassa\Client;

class OrdersController extends Controller
{

    protected CartItemsController $cartItemsController;
    protected YooKassaService $yooKassaService;

    public function __construct(CartItemsController $cartItemsController, YooKassaService $yooKassaService)
    {
        $this->cartItemsController = $cartItemsController;
        $this->yooKassaService = $yooKassaService;
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
                'status' => 'pending',
            ]);

            foreach ($cartItems as $cartItem) {
                OrderProducts::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'amount' => $cartItem->amount,
                ]);
            }

            $totalCost = $this->cartItemsController->totalCost();

            CartItems::query()->where('user_id', $user->id)->delete();

            DB::commit();

            CreateOrderTask::dispatch(
                $order,
                $user,
                $cartItems->toArray(),
                $request->address,
                $request->phone
            );

            $paymentData = $this->yooKassaService->createPayment(
                totalCost: $totalCost,
                orderId: $order->id,
                returnUrl: route('payment.success')
            );

            $order->update(['payment_id' => $paymentData['id']]);

            return redirect($paymentData['url']);

        } catch (\Exception $exception) {
            DB::rollBack();

            $order->update(['status' => 'failed']);

            throw $exception;


        }


    }
}
