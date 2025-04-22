<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartItemsController extends Controller
{
    public function getCartItems()
    {
        $cartItems = session()->get('cartItems', []);

        return view('cartItems', compact('cartItems'));
    }



}
