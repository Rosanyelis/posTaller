<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Customer;
use App\Models\WorkOrder;
use App\Mail\SendWorkorder;
use Illuminate\Http\Request;
use App\Models\WorkOrderItems;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\LogActivityWorkOrder;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreWorkOrderRequest;
use App\Http\Requests\UpdateWorkOrderRequest;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('work_orders')
                ->join('users', 'work_orders.user_id', '=', 'users.id')
                ->join('customers', 'work_orders.customer_id', '=', 'customers.id')
                ->select('work_orders.*', 'users.name as user', 'customers.name as customer')
                ->get();
            return DataTables::of($data)
                ->addColumn('actions', function ($data) {
                    return view('workorders.partials.actions', ['id' => $data->id]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('workorders.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('type', 'Servicios')->get();
        $customers = Customer::all();
        return view('workorders.create', compact('products', 'customers'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkOrderRequest $request)
    {
        $correlativoInicial = 1001;
        $nroOrden = 0;
        $count = WorkOrder::count();
        if ($count > 0) {
            $data = WorkOrder::latest()->first();
            $nroOrden = $data->correlativo + 1;
        } else {
            $nroOrden = 1001;
        }
        $productos = json_decode($request->array_products);
        $workorder = WorkOrder::create([
            'store_id'       => 1,
            'customer_id'    => $request->customer,
            'user_id'        => auth()->user()->id,
            'correlativo'    => $nroOrden,
            'marca'          => $request->marca,
            'modelo'         => $request->modelo,
            'patente_vehiculo' => $request->patente_vehiculo,
            'total'          => $request->total,
        ]);

        foreach ($productos as $key) {
            $product = Product::where('name', $key->producto)->first();
            WorkOrderItems::create([
                'work_order_id'  => $workorder->id,
                'product_id'     => $product->id,
                'quantity'       => $key->quantity,
                'details'        => $key->details,
                'price'          => $key->cost,
                'total'          => $key->total,
            ]);
        }


        return redirect()->route('ordenes-trabajo.index')->with('success', 'Orden de Trabajo Creada Correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show($workOrder)
    {
        $data = WorkOrder::with('items', 'customer', 'items.product', 'user')->find($workOrder);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($workOrder)
    {
        $workOrder = WorkOrder::with('items', 'customer', 'items.product', 'user')->find($workOrder);
        $products = Product::where('type', 'Servicios')->get();
        $customers = Customer::all();
        return view('workorders.edit', compact('workOrder', 'products', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkOrderRequest $request, $workOrder)
    {
        $productos = json_decode($request->array_products);
        $workorder = WorkOrder::find($workOrder);
        $workorder->update([
            'customer_id'    => $request->customer,
            'user_id'        => auth()->user()->id,
            'marca'          => $request->marca,
            'modelo'         => $request->modelo,
            'patente_vehiculo' => $request->patente_vehiculo,
            'total'          => $request->total,
        ]);

        foreach ($productos as $key) {
            $product = Product::where('name', $key->producto)->first();
            WorkOrderItems::create([
                'work_order_id'  => $workorder->id,
                'product_id'     => $product->id,
                'quantity'       => $key->quantity,
                'details'        => $key->details,
                'price'          => $key->cost,
                'total'          => $key->total,
            ]);
        }

        return redirect()->route('ordenes-trabajo.index')->with('success', 'Orden de Trabajo Actualizada Correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $workOrder = WorkOrder::find($request->id);
        $workOrder->update([
            'status' => $request->status
        ]);

        LogActivityWorkOrder::create([
            'work_order_id'    => $request->id,
            'user_id'    => auth()->user()->id,
            'action'     => 'Cambio de Status de Orden de Trabajo',
            'description'    => 'Se cambio el status de la orden de trabajo ' . $workOrder->correlativo. ', por el status:' . $request->status. ',  del cliente ' . $workOrder->customer->name. ' por el usuario ' . auth()->user()->name. '.',
            'ip'         => request()->ip()
        ]);

        return redirect()->route('ordenes-trabajo.index')->with('success', 'Status de Orden de Trabajo Actualizada Correctamente');
    }

    public function workorderpdf($workOrder)
    {
        $workOrder = WorkOrder::with('items', 'customer', 'items.product', 'user')->find($workOrder);
        return Pdf::loadView('pdfs.workOrder', compact('workOrder'))
                ->stream(''.config('app.name', 'Laravel').' - Orden-de-Trabajo-' . $workOrder->correlativo. '.pdf');
    }

    public function sendEmailWorkorderpdf($orden)
    {
        $workOrder = WorkOrder::with('items', 'customer', 'items.product', 'user')->find($orden);
        if ($workOrder->customer->email == null) {
            return redirect()->route('ordenes-trabajo.index')->with('error', 'El Cliente no posee correo para enviar la cotizacion');
        }

        $publicpath = public_path('storage/ordenes-trabajo/');
        $namepdf = config('app.name', 'Laravel').' - Orden de Trabajo - '.$workOrder->customer->name.' - '.date('Y-m-d').'.pdf';
        $urlpdf = $publicpath.$namepdf;


        $pdf = Pdf::loadView('pdfs.workOrder', compact('workOrder'))
                ->save($urlpdf);

        try {
            Mail::to($workOrder->customer->email)->send(new SendWorkorder($workOrder, $urlpdf, $namepdf));

            return redirect()->route('ordenes-trabajo.index')->with('success', 'Orden de Trabajo Enviada Exitosamente');
        } catch (\Throwable $th) {
            Log::error("error al enviar la orden de trabajo: ".$th->getMessage());

            return redirect()->route('ordenes-trabajo.index')->with('error', 'Error al enviar la Orden de Trabajo, verifique su correo');
        }

    }
}
