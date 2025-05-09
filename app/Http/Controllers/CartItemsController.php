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

        $cartItems = $user->userProducts()->get();

        $products = $user->products()->get();

        $totalCost = $this->totalCost();

        return view('cartItems', compact( 'cartItems', 'products', 'totalCost'));
    }

    public function addToCart(CartItemRequest $request)
    {
        $user = Auth::user();

        $productId = $request->get('product_id');
        $amount = $request->get('amount');
        //print_r($amount);die;

        $cartItems = $user->userProducts()->where('product_id', $productId)->first();

        if ($cartItems) {
            $cartItems->amount += $amount;
            $cartItems->save();
        } else {
            CartItems::query()->create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'amount' => $amount,
            ]);
        }

        return redirect('/catalog');
    }

    public function totalCost()
    {
        $user = Auth::user();
        $cartItems = $user->userProducts()->get();
        $products = $user->products()->get();

        $totalCost = 0;

        foreach($cartItems as $item) {
            $elem = $products->firstWhere('id', $item->product_id);
            $cost = $item->amount * $elem->price;
            $totalCost += $cost;
        }

        return $totalCost;
    }

    public function deleteProduct(ProductRequest $request)
    {
        $user = Auth::user();

        $productIds = $request->get('product_id');
        //print_r($productIds);die;

        $user->userProducts()->where('product_id', $productIds)->delete();

        return redirect('/cartItems');

    }

    public function deleteCart()
    {
        $user = Auth::user();

        $user->userProducts()->where('user_id', $user->id)->delete();

        return redirect('/catalog');

    }

}
