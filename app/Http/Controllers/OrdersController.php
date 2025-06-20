<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Jobs\CreateOrderTask;
use App\Models\CartItems;
use App\Models\Order;
use App\Models\OrderProducts;
use App\Services\Client\YougileClient;
use App\Services\PaymentService;
use app\Services\YooKassaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use YooKassa\Client;

class OrdersController extends Controller
{

    protected CartItemsController $cartItemsController;
    protected YooKassaService $yooKassaService;

    protected PaymentService $paymentService;

    public function __construct(CartItemsController $cartItemsController,
                                YooKassaService $yooKassaService,
                                PaymentService $paymentService
            )
    {
        $this->cartItemsController = $cartItemsController;
        $this->yooKassaService = $yooKassaService;
        $this->paymentService = $paymentService;
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

            DB::commit(); // <-- транзакция завершена

            CreateOrderTask::dispatch(
                $order,
                $user,
                $cartItems->toArray(),
                $request->address,
                $request->phone
            );

            $payment = $this->paymentService->createPayment(
                amount: $totalCost,
                orderId: $order->id,
                returnUrl: route('payment.success')
            );

            $order->update(['payment_id' => $payment['id']]);

            return redirect($payment['confirmation']['confirmation_url']);

        } catch (\Exception $exception) {

            DB::rollBack();

            if (isset($order)) {
                $order->query()->update(['status' => 'failed']);
            }

            report($exception);

            return redirect()->back()->withErrors('Произошла ошибка при создании заказа: ' . $exception->getMessage());
        }

    }
}
