<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductStoreQty;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToCollection, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $count = Category::where('code', $row['codigo_categoria'])->count();
            if ($count == 0) {
                $category = Category::create([
                    'name' => 'categoria - ' . $row['codigo_categoria'],
                    'code' => $row['codigocategoria'],
                ]);
            }

            $categoria = Category::where('code', $row['codigo_categoria'])->first();

            $product = Product::create([
                'category_id'        => $categoria->id,
                'code'               => $row['codigo'],
                'name'               => $row['producto'],
                'cost'               => $row['precio_compra'],
                'barcode_symbology'  => 'code128',
                'price'              => $row['precio_venta'],
                'type'               => $row['tipo_producto'],
            ]);

            $productqty = ProductStoreQty::updateOrCreate([
                'store_id'   => 1,
                'product_id' => $product->id,
                'quantity'   => $row['cantidad'],
                'price'      => $row['precio_venta'],
            ]);
        }
    }
}
