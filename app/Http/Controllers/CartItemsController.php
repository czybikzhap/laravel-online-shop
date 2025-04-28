<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\CartItems;
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

        $cartItems = CartItems::query()
            ->where('user_id', $user->id)
            ->get();
        //print_r($cartItems);die;

        $productIds = CartItems::query()
            ->where('user_id', $user->id)
            ->pluck('product_id');  // возвращает все product_id данного пользователя
        //print_r($productIds);die;

        $productsInCart = Product::query()
            ->whereIn('id', $productIds)
            ->get();
        //print_r($products);die;


        return view('cartItems', compact( 'cartItems', 'productsInCart'));
    }

    public function addToCart(CartItemRequest $request)
    {
        $user = Auth::user();

        $productId = $request->get('product_id');
        $amount = $request->get('amount');
        //print_r($amount);die;

        $cartItem = CartItems::query()
            ->where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->amount += $amount;
            $cartItem->save();
        } else {
            CartItems::query()->create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'amount' => $amount,
            ]);
        }

        return redirect('/catalog');
    }

    public function deleteProduct(ProductRequest $request)
    {
        $user = Auth::user();

        $productIds = $request->get('product_id');
        //print_r($productIds);die;

        CartItems::query()
            ->where('user_id', $user->id)
            ->where('product_id', $productIds)
            ->delete();

        return redirect('/cartItems');

    }

    public function deleteCart()
    {
        $user = Auth::user();

        CartItems::query()
            ->where('user_id', $user->id)
            ->delete();

        return redirect('/catalog');

    }

}
