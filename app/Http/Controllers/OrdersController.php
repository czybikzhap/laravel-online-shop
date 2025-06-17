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
use Illuminate\Support\Str;
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

            DB::commit(); // <-- транзакция завершена

            CreateOrderTask::dispatch(
                $order,
                $user,
                $cartItems->toArray(),
                $request->address,
                $request->phone
            );

            $shopId = env('YOOKASSA_SHOP_ID');
            $secretKey = env('YOOKASSA_SECRET_KEY');

            $paymentData = [
                "amount" => [
                    "value" => number_format($totalCost, 2, '.', ''),
                    "currency" => "RUB",
                ],
                "payment_method_data" => [
                    "type" => "bank_card"
                ],
                "confirmation" => [
                    "type" => "redirect",
                    "return_url" => route('payment.success'),
                ],
                "capture" => true,
                "description" => "Order #{$order->id}",
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Idempotence-Key' => (string) Str::uuid(),
            ])->withBasicAuth($shopId, $secretKey)
                ->post('https://api.yookassa.ru/v3/payments', $paymentData);

            if ($response->successful()) {
                $payment = $response->json();

                $order->update(['payment_id' => $payment['id']]);

                return redirect($payment['confirmation']['confirmation_url']);

            } else {

                Log::error('Ошибка при создании платежа в YooKassa', [
                    'response' => $response->body()
                ]);

                $order->query()->update(['status' => 'failed']);

                throw new \Exception('Не удалось создать платёж в YooKassa.');
            }

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
