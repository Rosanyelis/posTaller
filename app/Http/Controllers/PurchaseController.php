<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Support\Str;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('purchases')
                ->join('users', 'purchases.user_id', '=', 'users.id')
                ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
                ->select('purchases.*', 'users.name as user', 'suppliers.name as supplier')
                ->get();
            return DataTables::of($data)
                ->addColumn('actions', function ($data) {
                    return view('purchases.partials.actions', ['id' => $data->id]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('purchases.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('purchases.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request)
    {
        $urlfile = null;
        if ($request->hasFile('archivo')) {
            $uploadPath = public_path('/storage/compras/');
            $file = $request->file('archivo');
            $extension = $file->getClientOriginalExtension();
            $uuid = Str::uuid(4);
            $fileName = $uuid . '.' . $extension;
            $file->move($uploadPath, $fileName);
            $url = '/storage/compras/'.$fileName;
            $urlfile = $url;
        }
        $productos = json_decode($request->array_products);
        $purchase = Purchase::create([
            'supplier_id'    => $request->supplier,
            'user_id'        => auth()->user()->id,
            'store_id'       => 1,
            'total'          => $request->total,
            'reference'      => $request->reference,
            'files'          => $urlfile,
            'note'           => $request->note,
            'received'       => $request->received
        ]);

        foreach ($productos as $key) {
            $product = Product::where('name', $key->producto)->first();
            PurchaseItem::create([
                'purchase_id'       => $purchase->id,
                'product_id'        => $product->id,
                'quantity'          => $key->quantity,
                'cost'              => $key->cost,
                'subtotal'          => $key->total,
            ]);
        }
        return redirect()->route('compras.index')->with('success', 'Compra Guardada Exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($purchase)
    {
        $data = Purchase::with('purchaseItems', 'supplier', 'purchaseItems.product')->find($purchase);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($purchase)
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        $purchase = Purchase::with('purchaseItems', 'purchaseItems.product')->find($purchase);
        return view('purchases.edit', compact('suppliers', 'products', 'purchase'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseRequest $request, $purchase)
    {
        $urlfile = null;
        if ($request->hasFile('archivo')) {
            $uploadPath = public_path('/storage/compras/');
            $file = $request->file('archivo');
            $extension = $file->getClientOriginalExtension();
            $uuid = Str::uuid(4);
            $fileName = $uuid . '.' . $extension;
            $file->move($uploadPath, $fileName);
            $url = '/storage/compras/'.$fileName;
            $urlfile = $url;
        }

        $productos = json_decode($request->array_products);
        $purchase = Purchase::find($purchase);
        $purchase->update([
            'supplier_id'    => $request->supplier,
            'user_id'        => auth()->user()->id,
            'store_id'       => 1,
            'total'          => $request->total,
            'reference'      => $request->reference,
            'files'          => $urlfile,
            'note'           => $request->note,
            'received'       => $request->received
        ]);
        PurchaseItem::where('purchase_id', $purchase->id)->delete();
        foreach ($productos as $key) {
            $product = Product::where('name', $key->producto)->first();
            PurchaseItem::create([
                'purchase_id'       => $purchase->id,
                'product_id'        => $product->id,
                'quantity'          => $key->quantity,
                'cost'              => $key->cost,
                'subtotal'          => $key->total,
            ]);
        }
        return redirect()->route('compras.index')->with('success', 'Compra Actualizada Exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($purchase)
    {
        $purchase = Purchase::find($purchase);
        $purchase->delete();

        $purchaseItems = PurchaseItem::where('purchase_id', $purchase->id)->get();
        foreach ($purchaseItems as $key) {
            $key->delete();
        }
        return redirect()->route('compras.index')->with('success', 'Compra Eliminada Exitosamente');
    }

    public function purchasepdf($purchase)
    {
        $purchase = Purchase::with('purchaseItems', 'purchaseItems.product', 'supplier')->find($purchase);
        return Pdf::loadView('pdfs.purchase', compact('purchase'))
                ->stream(''.config('app.name', 'Laravel').' - Compra.pdf');
    }
}
