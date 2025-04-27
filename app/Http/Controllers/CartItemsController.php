<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Http\Requests\CartItemRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartItemsController extends Controller
{
    public function getCartItems()
    {
        $user = Auth::user();
        //print_r($user->id);die;

        $cartItems = CartItem::query()
            ->where('user_id', $user->id)
            ->get();
        //print_r($cartItems);die;

        $productIds = CartItem::query()
            ->where('user_id', $user->id)
            ->pluck('product_id');
        //print_r($productIds);die;

        $productsInCart = Product::query()
            ->whereIn('id', $productIds)
            ->get();
        //print_r($products);die;

        //$item = $productsInCart->whereIn('id', $productIds);
        //print_r($item);die;


        return view('cartItems', compact( 'cartItems', 'productsInCart'));
    }

    public function addToCart(CartItemRequest $request)
    {
        $user = Auth::user();

        $productId = $request->get('product_id');
        $amount = $request->get('amount');
        //print_r($amount);die;

        $cartItem = CartItem::query()
            ->where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // Если запись существует, увеличиваем количество
            $cartItem->amount += $amount;
            $cartItem->save(); // Сохраняем изменения
        } else {
            // Если записи нет, создаем новую
            CartItem::query()->create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'amount' => $amount,
            ]);
        }

        return redirect('/catalog');
    }

}
