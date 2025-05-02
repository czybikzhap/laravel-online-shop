<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function getCatalog()
    {
        $catalog = Product::all();

        return view('catalog', compact('catalog'));
    }

    public function getProduct(int $id)
    {
        $product = Product::query()->findOrFail($id);

        return view('product', compact('product'));
    }

}
