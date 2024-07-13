<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\TypeProduct;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\ProductsImport;
use App\Models\ProductStoreQty;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('products')
                    ->join('categories', 'products.category_id', '=', 'categories.id')
                    ->join('product_store_qties', 'products.id', '=', 'product_store_qties.product_id')
                    ->select('products.*', 'categories.name as category', 'product_store_qties.quantity as quantity')
                    ->get();
            return DataTables::of($data)
                ->addColumn('actions', function ($data) {
                    return view('products.partials.actions', ['id' => $data->id]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $category= Category::all();
        $typeproduct = TypeProduct::all();
        return view('products.create', compact('category', 'typeproduct'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->all();
        $data['image'] = null;
        if ($request->hasFile('image')) {
            $uploadPath = public_path('/storage/productos/');
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $uuid = Str::uuid(4);
            $fileName = $uuid . '.' . $extension;
            $file->move($uploadPath, $fileName);
            $url = '/storage/productos/'.$fileName;
            $foto = $url;
            $data['image'] = $url;
        }

        $producto = Product::create([
            'code'              => $data['code'],
            'name'              => $data['name'],
            'category_id'       => $data['category_id'],
            'type'              => $data['type'],
            'cost'              => $data['cost'],
            'price'             => $data['price'],
            'image'             => $data['image'],
            'description'       => $data['description'],
            'barcode_symbology' => $data['barcode_symbology'],
            'alert_quantity'    => $data['alert_quantity'],
            'max_quantity'      => $data['max_quantity'],
            'cellar'            => $data['cellar'],
            'hail'              => $data['hail'],
            'rack'              => $data['rack'],
            'position'          => $data['position'],
        ]);

        ProductStoreQty::create([
            'store_id'   => 1,
            'product_id' => $producto->id,
            'quantity'   => $data['quantity'],
            'price'      => $data['price'],
        ]);

        return redirect()->route('productos.index')->with('success', 'Producto creado con exito');

    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($product)
    {
        $category= Category::all();
        $typeproduct = TypeProduct::all();
        $product = Product::find($product);
        return view('products.edit', compact('product', 'category', 'typeproduct'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $product)
    {
        $data = $request->all();
        $data['image'] = null;
        if ($request->hasFile('image')) {
            $uploadPath = public_path('/storage/productos/');
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $uuid = Str::uuid(4);
            $fileName = $uuid . '.' . $extension;
            $file->move($uploadPath, $fileName);
            $url = '/storage/productos/'.$fileName;
            $foto = $url;
            $data['image'] = $url;
        }

        $product = Product::find($product);
        $product->update([
            'code'              => $data['code'],
            'name'              => $data['name'],
            'category_id'       => $data['category_id'],
            'type'              => $data['type'],
            'cost'              => $data['cost'],
            'price'             => $data['price'],
            'image'             => $data['image'],
            'description'       => $data['description'],
            'barcode_symbology' => $data['barcode_symbology'],
            'alert_quantity'    => $data['alert_quantity'],
            'max_quantity'      => $data['max_quantity'],
            'cellar'            => $data['cellar'],
            'hail'              => $data['hail'],
            'rack'              => $data['rack'],
            'position'          => $data['position'],
        ]);

        ProductStoreQty::where('product_id', $product->id)->update([
            'quantity'   => $data['quantity'],
            'price'      => $data['price'],
        ]);
        return redirect()->route('productos.index')->with('success', 'Producto actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product)
    {
        $data = Product::find($product);
        $data->delete();

        ProductStoreQty::where('product_id', $product)->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado con exito');
    }

    /**
     * Renders the view for importing categories.
     *
     */
    public function view_import()
    {
        return view('products.import');
    }

    public function import(Request $request)
    {
        Excel::import(new ProductsImport, $request->file('file'));
        return redirect()->route('productos.index')->with('success', 'Productos importados con exito');
    }

    public function allproductpdf()
    {
        $products = Product::all();
        return Pdf::loadView('pdfs.allproducts', compact('products'))
                ->stream(''.config('app.name', 'Laravel').' - Listado de Productos.pdf');
    }
}
