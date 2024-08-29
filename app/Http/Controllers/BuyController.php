<?php

namespace App\Http\Controllers;

use App\Models\BuyModel;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class BuyController extends Controller
{
    public function getBuyOne($id)
    {
        $buy = BuyModel::where('product_id', $id)->with('user')->get();
        return response()->json([
            'status' => 'success',
            "data" => $buy
        ]);
    }
    public function getBuyAll()
    {
        $user = auth()->user();
        $purchases = BuyModel::with('product')
            ->where('user_id', $user->id)
            ->get();
        $purchasedProducts = $purchases->filter(function ($buy) {
            return $buy->product !== null;
        })->pluck('product');

        return response()->json([
            'status' => 'success',
            'products' => $purchasedProducts
        ]);
    }

    public function ToggleBuy($id)
    {
        $user = auth()->user();
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }
        $buyed = $product->buy->contains('user_id', $user->id);
        if ($buyed) {
            BuyModel::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->delete();
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            $buyed = BuyModel::create([
                'user_id' => $user->id,
                'product_id' => $product->id
            ]);
            return response()->json([
                'status' => 'success',
                'data' => $buyed,
            ]);
        }
    }
}
