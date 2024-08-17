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
        $count = Product::where('code', $product)->count();
        if ($count > 0) {
            $data = Product::join('product_store_qties', 'products.id', '=', 'product_store_qties.product_id')
                    ->select('products.id', 'products.name', 'products.code', 'products.price', 'product_store_qties.quantity')
                    ->where('products.code', $product)
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
        $product = Product::with('storeqty')->where('code', $request->code)->first();

        $productqty = ProductStoreQty::where('product_id', $product->id)->first();

        if ($productqty->quantity > 0) {

            $pqty = ProductStoreQty::where('product_id', $product->id)->first();
            $pqty->quantity = $pqty->quantity - $request->quantity;
            $pqty->save();

            # ingresamos informacion en kardex del producto
            Kardex::create([
                'product_id'    => $product->id,
                'quantity'      => $request->quantity,
                'price'         => $request->price,
                'total'         => $request->total,
                'type'          => 2,
                'description'   => 'Venta de ' . $product->name.' en la tienda en linea',
            ]);

            $p = Product::with('storeqty')->where('code', $request->code)->first();

            return response()->json($p, 200);
        } else {

            return response()->json('No hay stock para este producto', 404);
        }


    }

}
