<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ProductController extends Controller
{
    public function index(){
        $products = Product::with('user', 'buy')->latest()->paginate(5);
        foreach ($products as $product) {
            $product['buy_order'] = $product->buy->contains(function ($buy) {
                return $buy->user_id === auth()->id();
            });
            $product['buy_count'] = $product->buy->count();
        }
        return response()->json([
            'status' => 'success',
            'data' => $products
        ], 200);
    }
    public function store(Request $request){
        $user = auth()->user();
        $data =$request->all();
        $data['user_id'] = $user->id;
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/products');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
        }
        $product = Product::create($data);
        return response()->json([
            'status' => 'success',
            'data' => $product
        ]);
    }
    public function show($id){
        $product = Product::with('user', 'buy')->find($id);
        if (!$product) {
            return response()->json([
               'status' => 'error',
               'message' => 'Product not found'
            ], 404);
        }
        $product['buy_order'] = $product->buy->contains(function ($buy) {
            return $buy->user_id === auth()->id();
        });
        $product['buy_count'] = $product->buy->count();
        return response()->json([
           'status' => 'success',
            'data' => $product
        ]);
    }
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        $user = auth()->user();
        if ($user->id !== $product->user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }

        $data = $request->except(['photo']);

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/products');
            $image->move($destinationPath, $name);
            $data['image'] = $name;
            $oldImage = public_path('/products/').$product->image;
            if(file_exists($oldImage)){
                unlink($oldImage);
            }
        }

        $product->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Product successfully updated',
            'data' => $product
        ]);
    }
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found'
            ], 404);
        }

        $user = auth()->user();
        if ($user->id !== $product->user_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        }
        $oldImage = public_path('/products/') . $product->image;
        if (file_exists($oldImage)) {
            unlink($oldImage);
        }
        $product->buy()->delete();
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product successfully deleted'
        ]);
    }

}
