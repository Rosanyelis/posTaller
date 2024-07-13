<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Quotation;
use Illuminate\Http\Request;
use App\Models\QuotationItems;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;

class QuotationController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('quotations')
                ->join('users', 'quotations.user_id', '=', 'users.id')
                ->join('customers', 'quotations.customer_id', '=', 'customers.id')
                ->select('quotations.*', 'users.name as user', 'customers.name as customer', 'customers.rut as rut')
                ->get();
            return DataTables::of($data)
                ->addColumn('actions', function ($data) {
                    return view('quotes.partials.actions', ['id' => $data->id]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('quotes.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('quotes.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuotationRequest $request)
    {
        $customer = Customer::where('name', $request->customer)->first();
        $productos = json_decode($request->array_products);
        $quote = Quotation::create([
            'customer_id'    => $customer->id,
            'user_id'        => auth()->user()->id,
            'store_id'       => 1,
            'customer_name'  => $request->customer,
            'total'          => $request->total,
            'grand_total'    => $request->total,
            'total_items'    => count($productos),
            'note'           => $request->note
        ]);

        foreach ($productos as $key) {
            $product = Product::where('name', $key->producto)->first();
            QuotationItems::create([
                'quotation_id'   => $quote->id,
                'product_id'     => $product->id,
                'product_name'   => $key->producto,
                'product_code'   => $product->code,
                'quantity'       => $key->quantity,
                'unit_price'     => $key->price,
                'net_unit_price' => $key->price,
                'discount'       => $key->discount,
                'subtotal'       => $key->total,
                'real_unit_price' => $key->total,
            ]);
        }

        return redirect()->route('cotizaciones.index')->with('success', 'Cotización Guardada Exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($quotation)
    {
        $data = Quotation::with('items')->find($quotation);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($quotation)
    {
        $customers = Customer::all();
        $products = Product::all();
        $quotation = Quotation::with('items')->find($quotation);
        return view('quotes.edit', compact('quotation', 'customers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuotationRequest $request, $quotation)
    {
        $customer = Customer::where('name', $request->customer)->first();
        $productos = json_decode($request->array_products);
        $quote = Quotation::find($quotation);
        $quote->update([
            'customer_id'    => $customer->id,
            'user_id'        => auth()->user()->id,
            'store_id'       => 1,
            'customer_name'  => $request->customer,
            'total'          => $request->total,
            'grand_total'    => $request->total,
            'total_items'    => count($productos),
            'note'           => $request->note
        ]);

        $quote->items()->delete();

        foreach ($productos as $key) {
            $product = Product::where('name', $key->producto)->first();
            QuotationItems::create([
                'quotation_id'   => $quote->id,
                'product_id'     => $product->id,
                'product_name'   => $key->producto,
                'product_code'   => $product->code,
                'quantity'       => $key->quantity,
                'unit_price'     => $key->price,
                'net_unit_price' => $key->price,
                'discount'       => $key->discount,
                'subtotal'       => $key->total,
                'real_unit_price' => $key->total,
            ]);
        }

        return redirect()->route('cotizaciones.index')->with('success', 'Cotización Actualizada Exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($quotation)
    {
        $quotation = Quotation::find($quotation);
        $quotation->delete();

        $quotationItems = QuotationItems::where('quotation_id', $quotation->id)->get();
        foreach ($quotationItems as $key) {
            $key->delete();
        }

        return redirect()->route('cotizaciones.index')->with('success', 'Cotización eliminada con exito');
    }

    public function quotepdf($quotation)
    {
        $quotation = Quotation::find($quotation);
        return Pdf::loadView('pdfs.quotation', compact('quotation'))
                ->stream(''.config('app.name', 'Laravel').' - Cotizacion.pdf');
    }

    public function productjson($quotation)
    {
        $data = Product::find($quotation);
        return response()->json($data);
    }
}
