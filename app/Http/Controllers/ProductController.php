<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
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

        $users = [];

        foreach($reviews as $review) {
            $userId = $review->user_id;
            $user = Cache::remember("user_" . $userId, 3600, function () use ($userId) {
                return User::query()->where('id', $userId)->first();
            });

            if ($user instanceof \Illuminate\Database\Eloquent\Collection) {
                $user = $user->first(); // Преобразуем коллекцию в объект
            }

            // Проверяем, что $user существует, и добавляем имя
            if ($user) {
                $users[$userId] = $user->name; // Обращаемся к свойству name на модели
            }
        }


        return view('product', compact('product', 'reviews', 'users'));

    }

    public function addReview(ReviewRequest $request)
    {
        $user = Auth::user();

        $userId = $user->id;
        $data = $request->all();
        $productId = $data['product_id'];

        $orderExists = Order::where('user_id', $userId)
            ->whereHas('orderProducts', function($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists();

        If ($orderExists) {
            Review::query()->create([
                'user_id' => $user->id,
                'product_id' => $data['product_id'],
                'review' => $data['review']
            ]);

            Cache::forget('reviews_product_' . $productId);

        } else {
            return redirect("/product/{$productId}")
                ->with('error', 'Вы не заказали этот продукт, не можете оставить отзыв.');
        }

        return redirect("/product/{$productId}")->with('success', 'Ваш отзыв успешно оставлен!');

    }

}
