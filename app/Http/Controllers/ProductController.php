<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function getCatalog()
    {
        $user = Auth::user();

        $catalog = Product::all();

        return view('catalog', compact('catalog'));
    }

}
