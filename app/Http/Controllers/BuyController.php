<?php

namespace App\Http\Controllers;

use App\Models\BuyModel;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;

class BuyController extends Controller
{
    public function getBuy($id)
    {
        $buy = Product::where('id', $id)->with('buy')->get();
        return response()->json([
            'status' => 'success',
            "data" => $buy
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
        } else {
            $product->buy()->create(['user_id' => $user->id]);
        }
        return response()->json([
            'status' => 'success',
            'data' => $product
        ]);
    }
}
