<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function getCatalog()
    {
        $catalog = Cache::remember('catalog', 3600, function () {
            return Product::all();
        });

        return view('catalog', compact('catalog'));
    }

    public function getProduct(int $id)
    {
        //print_r($id);die;
        $product = Cache::remember("product" . $id, 3600, function () use ($id) {
           return Product::query()->findOrFail($id);
        });

        //print_r($product);die;

        return view('product', compact('product'));
    }

}
