<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Product;
use App\Models\Review;
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
        $product = Cache::remember("product" . $id, 3600, function () use ($id) {
           return Product::query()->findOrFail($id);
        });

        $reviews = Cache::remember('reviews_product_' . $id, 3600, function () use ($id) {
            return Review::query()->where('product_id', $id)->get();
        });

        return view('product', compact('product', 'reviews'));

    }

    public function addReview(ReviewRequest $request)
    {
        $user = Auth::user();

        $userName = $user->name;


        $data = $request->all();
        $id = $data['product_id'];

        $product = Cache::remember("product" . $id, 3600, function () use ($id) {
            return Product::query()->findOrFail($id);
        });

        Review::query()->create([
            'user_id' => $user->id,
            'product_id' => $data['product_id'],
            'review' => $data['review']
        ]);

        Cache::forget('reviews_product_' . $id);

        $reviews = Review::query()->where('product_id', $id)->get();

        return view('product', compact('product','reviews'));

    }

}
