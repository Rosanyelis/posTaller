<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;
use App\Models\SaleItems;
use App\Models\WorkOrder;
use App\Models\SalePayment;
use Illuminate\Http\Request;

class PosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pos.index');
    }

    public function getCustomers()
    {
        $data = Customer::all();
        return response()->json($data);
    }

    public function getProducts(Request $request)
    {
        $data = Product::join('product_store_qties', 'products.id', '=', 'product_store_qties.product_id')
                    ->where('products.name', 'like', '%' . $request->term . '%')
                    ->orWhere('products.code', 'like', '%' . $request->term . '%')
                    ->select('products.id', 'products.name', 'products.code', 'products.price', 'product_store_qties.quantity')
                    ->get();

        return response()->json($data);
    }

    public function getWorkorders(Request $request)
    {
        $data = WorkOrder::join('customers', 'work_orders.customer_id', '=', 'customers.id')
                        ->where('correlativo', 'like', '%' . $request->term . '%')
                        ->where('status', 'Completado')
                        ->select('work_orders.id', 'work_orders.correlativo', 'work_orders.total', 'customers.name', 'customers.rut')
                        ->get();
        return response()->json($data);
    }

    public function storeCustomer(Request $request)
    {
        $data = $request->all();
        $data['store_id'] = 1;
        $customer = Customer::create($data);
        return response()->json($customer);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $customer = Customer::find($request->customer);
        $discount = $request->subtotal * ($request->discount / 100);
        $impuesto = $request->subtotal * ($request->tax / 100);
        $status= '';
        if ($request->methodpay == 'Total') {
            $status = 'paid';
        } else {
            $status = 'partial';
        }
        $sale = Sale::create([
            'store_id' => 1,
            'customer_id' => $request->customer,
            'user_id' => auth()->user()->id,
            'customer_name' => $customer->name,
            'total' => $request->subtotal,
            'order_discount_id' => $request->discount,
            'total_discount' => $discount,
            'order_tax_id' => $request->tax,
            'total_tax' => $impuesto,
            'grand_total' => $request->grandtotal,
            'total_items' => $request->total_items,
            'note' => $request->note_ref,
            'paid' => $request->amount,
            'payment_status' => $status
        ]);

        $products = json_decode($request->productos);

        foreach ($products as $key) {
            if ($key->type == 'product') {
                $product = Product::where('id', $key->id)->first();
                SaleItems::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_code' => $product->code,
                    'quantity' => $key->quantity,
                    'unit_price' => $key->price,
                    'net_unit_price' => $key->price,
                    'subtotal' => $key->subtotal,
                    'cost' => $product->cost
                ]);
            }

            if ($key->type == 'workorder') {
                SaleItems::create([
                    'sale_id' => $sale->id,
                    'work_order_id' => $key->id,
                    'product_name' => $key->name,
                    'product_code' => $key->code,
                    'quantity' => $key->quantity,
                    'unit_price' => $key->price,
                    'net_unit_price' => $key->price,
                    'subtotal' => $key->subtotal,
                    'cost' => $key->price
                ]);
            }

        }

        if ($request->methodpay == 'Total') {
            SalePayment::create([
                'store_id' => 1,
                'sale_id' => $sale->id,
                'user_id' => auth()->user()->id,
                'customer_id' => $request->customer,
                'amount' => $request->amount,
                'payment_method' => $request->paymentby,
                'note' => $request->notePay,
                'pos_paid' => $request->amount,
                'pos_balance' => $request->amount,
                'note' => $request->notepayments
            ]);
        } elseif ($request->methodpay == 'Parcial') {
            $paypartial = json_decode($request->paypartial);
            foreach ($paypartial as $key) {
                SalePayment::create([
                    'store_id' => 1,
                    'sale_id' => $sale->id,
                    'user_id' => auth()->user()->id,
                    'customer_id' => $request->customer,
                    'amount' => $key->amount,
                    'payment_method' => $key->payment,
                    'note' => $request->details,
                    'pos_paid' => $request->amount,
                    'pos_balance' => $request->amount,
                ]);
            }

        }

        return redirect()->route('pos.index')->with('success', 'Se ha guardado la venta satisfactoriamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
