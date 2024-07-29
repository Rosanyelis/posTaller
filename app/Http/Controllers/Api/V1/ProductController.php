<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Kardex;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductStoreQty;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getStock($product)
    {
        $count = Product::where('id', $product)->count();
        if ($count > 0) {
            $data = Product::join('product_store_qties', 'products.id', '=', 'product_store_qties.product_id')
                    ->select('products.id', 'products.name', 'products.code', 'products.price', 'product_store_qties.quantity')
                    ->where('products.id', $product)
                    ->first();

            return response()->json($data, 200);
        } else {
            return response()->json(null, 404);
        }

    }

    /**
     * Display a listing of the resource.
     */
    public function updateStock(Request $request)
    {
        $count = Product::where('id', $request->product_id)->count();

        if ($count > 0) {
            $productqty = ProductStoreQty::where('product_id', $request->product_id)->first();
            $productqty->quantity = $productqty->quantity - $request->quantity;
            $productqty->save();

            $product = Product::with('storeqty')->find($request->product_id);
            # ingresamos informacion en kardex del producto
            Kardex::create([
                'product_id'    => $request->product_id,
                'quantity'      => $request->quantity,
                'price'         => $request->price,
                'total'         => $request->total,
                'type'          => 2,
                'description'   => 'Venta de ' . $product->name.' en la tienda en linea',
            ]);
        }

        return response()->json($product, 200);
    }

}
