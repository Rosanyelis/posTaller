<?php

namespace App\Http\Controllers;

use App\Models\Kardex;
use App\Models\Product;
use App\Models\Category;
use App\Models\TypeProduct;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use App\Models\ProductStoreQty;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Exports\ProductsInternationalExport;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorys = Category::all();
        return view('products.index', compact('categorys'));
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->join('product_store_qties', 'products.id', '=', 'product_store_qties.product_id')
            ->select('products.*', 'product_store_qties.quantity as stock', 'categories.name as category_name')
            ->orderBy('categories.name', 'asc', 'products.code', 'asc');
            return DataTables::of($data)
                ->filter(function ($query) use ($request) {
                    if ($request->has('category_id') && $request->get('category_id') != '') {
                        $query->where('category_id', $request->get('category_id'));
                    }

                    if ($request->has('search') && $request->get('search')['value'] != '') {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($subQuery) use ($searchValue) {
                            $subQuery->where('name', 'like', "%{$searchValue}%")
                                     ->orWhere('code', 'like', "%{$searchValue}%")
                                     ->orWhere('type', 'like', "%{$searchValue}%");
                        });
                    }
                })
                ->addColumn('actions', function ($data) {
                    return view('products.partials.actions', ['id' => $data->id]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    public function totalProductos(Request $request)
    {
        if ($request->ajax()) {
            $total = DB::table('products')
            ->join('product_store_qties', 'products.id', '=', 'product_store_qties.product_id')
            ->where('type', '!=', 'SERVICIOS')
            ->select(DB::raw('SUM(product_store_qties.quantity) as total_products'),
                    DB::raw('SUM(products.cost * product_store_qties.quantity) as total'))
            ->where(function ($query) use ($request) {
                if ($request->has('category_id') && $request->get('category_id') != '') {
                    $query->where('products.category_id', $request->get('category_id'));
                }
            })
            ->first();

            $totalp = Product::where(function ($query) use ($request) {
                if ($request->has('category_id') && $request->get('category_id') != '') {
                    $query->where('products.category_id', $request->get('category_id'));
                }
            })
            ->count();

            $data = [
                'total' => $totalp,
                'stock' => $total->total_products,
                'totalclp' => $total->total
            ];

            return response()->json($data);
        }

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

        $code = trim($data['code']);
        $codeclean = str_replace(' ', '', $code);

        $producto = Product::create([
            'code'              => $codeclean,
            'name'              => $data['name'],
            'category_id'       => $data['category_id'],
            'type'              => $data['type'],
            'cost'              => $data['cost'],
            'price'             => $data['price'],
            'image'             => $data['image'],
            'description'       => $data['description'],
            'barcode_symbology' => 'code128',
            'alert_quantity'    => $data['alert_quantity'],
            'max_quantity'      => $data['max_quantity'],
            'weight'            => $data['weight'],
            'nacionality'       => $data['nacionality'],
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

        Kardex::create([
            'product_id'    => $producto->id,
            'quantity'      => $data['quantity'],
            'price'         => $data['cost'],
            'total'         => $data['cost'],
            'type'          => 1,
            'description'   => 'Registro del producto ' . $data['name']
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

        $product = Product::find($product);
        $data['image'] = $product->image;
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
        $code = trim($data['code']);
        $codeclean = str_replace(' ', '', $code);
        $product->update([
            'code'              => $codeclean,
            'name'              => $data['name'],
            'category_id'       => $data['category_id'],
            'type'              => $data['type'],
            'cost'              => $data['cost'],
            'price'             => $data['price'],
            'image'             => $data['image'],
            'description'       => $data['description'],
            'barcode_symbology' => 'code128',
            'alert_quantity'    => $data['alert_quantity'],
            'max_quantity'      => $data['max_quantity'],
            'weight'            => $data['weight'],
            'nacionality'       => $data['nacionality'],
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
        ProductStoreQty::where('product_id', $product)->delete();

        $data = Product::find($product);
        $data->delete();

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
        $products = Product::join('categories', 'products.category_id', '=', 'categories.id')
                ->join('product_store_qties', 'products.id', '=', 'product_store_qties.product_id')
                ->select('products.*', 'product_store_qties.quantity as stock', 'categories.name as category_name')
                ->orderBy('categories.name', 'asc', 'products.code', 'asc')
                ->get();
        return Pdf::loadView('pdfs.allproducts', compact('products'))
                ->setPaper('letter', 'landscape')
                ->stream(''.config('app.name', 'Laravel').' - Listado de Productos.pdf');
    }

    public function generateInformefilter(Request $request)
    {
        $products = Product::where('category_id', $request->category_id)->get();
        return Pdf::loadView('pdfs.porcategoria', compact('products'))
                ->setPaper('letter', 'landscape')
                ->stream(''.config('app.name', 'Laravel').' - Listado de Productos.pdf');
    }

    public function kardex(Request $request, $product)
    {
        $producto = Product::find($product);
        if ($request->ajax()) {
            $data = DB::table('kardexes')
                ->join('products', 'kardexes.product_id', '=', 'products.id')
                ->where('kardexes.product_id', $product)
                ->select('kardexes.*', 'products.name as product_name');
            return DataTables::of($data)
                ->filter(function ($query) use ($request) {
                    if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                        $query->whereBetween('kardexes.created_at', [$request->get('start'), $request->get('end')]);
                    }

                    if ($request->has('search') && $request->get('search')['value'] != '') {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($subQuery) use ($searchValue) {
                            $subQuery->where('products.name', 'like', "%{$searchValue}%")
                                     ->orWhere('kardexes.description', 'like', "%{$searchValue}%")
                                     ->orWhere('kardexes.type', 'like', "%{$searchValue}%");
                        });
                    }

                })
                ->make(true);
        }
        return view('products.kardex', compact('producto'));
    }

    public function kardexpdf($product)
    {
        $producto = Product::find($product);
        $kardexes = DB::table('kardexes')
            ->join('products', 'kardexes.product_id', '=', 'products.id')
            ->where('kardexes.product_id', $producto->id)
            ->select('kardexes.*', 'products.name as product_name')
            ->get();
        return Pdf::loadView('pdfs.kardex', compact('kardexes', 'producto'))
                ->setPaper('letter', 'landscape')
                ->stream(''.config('app.name', 'Laravel').' - Listado de Kardex.pdf');
    }

    public function allproductbarspdf()
    {
        $products = Product::all();
        return Pdf::loadView('pdfs.productsbars', compact('products'))
                ->setPaper('letter', 'landscape')
                ->stream(''.config('app.name', 'Laravel').' - Listado de Productos con codigo de barrras.pdf');
    }

    public function kardexpdffilter(Request $request, $product)
    {
        $producto = Product::find($product);
        $kardexes = DB::table('kardexes')
            ->join('products', 'kardexes.product_id', '=', 'products.id')
            ->where('kardexes.product_id', $producto->id)
            ->whereBetween('kardexes.created_at', [$request->start, $request->end])
            ->select('kardexes.*', 'products.name as product_name')
            ->get();
        return Pdf::loadView('pdfs.kardex', compact('kardexes', 'producto'))
                ->setPaper('letter', 'landscape')
                ->stream(''.config('app.name', 'Laravel').' - Listado de Kardex.pdf');
    }

    public function export()
    {
        return Excel::download(new ProductsInternationalExport, 'neumaticos-internacionales.xlsx');
    }

    public function exportproduct()
    {
        return Excel::download(new ProductsExport, 'productos.xlsx');
    }
}
