<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Quotation;
use App\Mail\SendQuotation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\QuotationItems;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreQuotationRequest;
use App\Http\Requests\UpdateQuotationRequest;

class QuotationController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('rol_id', '!=' ,'1')->get();
        $clients = Customer::all();
        return view('quotes.index', compact('users', 'clients'));
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('quotations')
                ->join('users', 'quotations.user_id', '=', 'users.id')
                ->join('customers', 'quotations.customer_id', '=', 'customers.id')
                ->select('quotations.*', 'users.name as user', 'customers.name as customer', 'customers.rut as rut')
                ->orderBy('quotations.created_at', 'desc')
                ->where('quotations.status', '!=', 'Pagado');
            return DataTables::of($data)
                ->filter(function ($query) use ($request) {
                    if ($request->has('user_id') && $request->get('user_id') != '') {
                        $query->where('quotations.user_id', $request->get('user_id'));
                    }

                    if ($request->has('customer_id') && $request->get('customer_id') != '') {
                        $query->where('quotations.customer_id', $request->get('customer_id'));
                    }

                    if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                        $query->whereBetween('quotations.created_at', [$request->get('start'), $request->get('end')]);
                    }

                    if ($request->has('status') && $request->get('status') != '') {
                        $query->where('quotations.status', $request->get('status'));
                    }

                    if ($request->has('search') && $request->get('search')['value'] != '') {
                        $searchValue = $request->get('search')['value'];
                        $query->where(function ($subQuery) use ($searchValue) {
                            $subQuery->where('customers.name', 'like', "%{$searchValue}%")
                                     ->orWhere('users.name', 'like', "%{$searchValue}%")
                                     ->orWhere('customers.rut', 'like', "%{$searchValue}%");
                        });
                    }
                })
                ->addColumn('actions', function ($data) {
                    return view('quotes.partials.actions', ['data' => $data]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
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
        $file_propuesta = null;
        $correlativoInicial = 1001;
        $nroOrden = 0;
        $count = Quotation::count();
        if ($count > 0) {
            $data = Quotation::latest()->first();
            $nroOrden = $data->correlativo + 1;
        } else {
            $nroOrden = 1001;
        }
        if ($request->hasFile('file_propuesta')) {
            $uploadPath = public_path('/storage/cotizaciones/');
            $file = $request->file('file_propuesta');
            $extension = $file->getClientOriginalExtension();
            $uuid = Str::uuid(4);
            $fileName = $uuid . '.' . $extension;
            $file->move($uploadPath, $fileName);
            $url = '/storage/cotizaciones/'.$fileName;
            $foto = $url;
            $file_propuesta = $url;
        }

        $quote = Quotation::create([
            'customer_id'    => $customer->id,
            'user_id'        => auth()->user()->id,
            'customer_name'  => $request->customer,
            'subtotal'       => $request->subtotal,
            'iva'            => $request->iva,
            'grand_total'    => $request->total,
            'file_propuesta' => $file_propuesta,
            'note'           => $request->note,
            'correlativo'    => $nroOrden,
            'closing_date'   => $request->closing_date,
            'closing_percentage' => $request->closing_percentage,
        ]);

        foreach ($productos as $key) {
            $product = Product::where('code', $key->code)->first();
            QuotationItems::create([
                'quotation_id'   => $quote->id,
                'product_id'     => $product->id,
                'product_name'   => $product->name,
                'product_code'   => $product->code,
                'quantity'       => $key->quantity,
                'price'          => $key->price,
                'profit'         => $key->profit,
                'margen'         => $key->margen,
                'subtotal'       => $key->subtotal,
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
        $file_propuesta = null;
        if ($request->hasFile('file_propuesta')) {
            $uploadPath = public_path('/storage/cotizaciones/');
            $file = $request->file('file_propuesta');
            $extension = $file->getClientOriginalExtension();
            $uuid = Str::uuid(4);
            $fileName = $uuid . '.' . $extension;
            $file->move($uploadPath, $fileName);
            $url = '/storage/cotizaciones/'.$fileName;
            $foto = $url;
            $file_propuesta = $url;
        }

        $quote = Quotation::find($quotation);
        $quote->update([
            'customer_id'    => $customer->id,
           'user_id'        => auth()->user()->id,
            'customer_name'  => $request->customer,
            'subtotal'       => $request->subtotal,
            'iva'            => $request->iva,
            'grand_total'    => $request->total,
            'file_propuesta' => $file_propuesta,
            'note'           => $request->note
        ]);

        $quote->items()->delete();

        foreach ($productos as $key) {
            $product = Product::where('code', $key->code)->first();
            QuotationItems::create([
                'quotation_id'   => $quote->id,
                'product_id'     => $product->id,
                'product_name'   => $product->name,
                'product_code'   => $product->code,
                'quantity'       => $key->quantity,
                'price'          => $key->price,
                'profit'         => $key->profit,
                'margen'         => $key->margen,
                'subtotal'       => $key->subtotal,
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

    public function sendEmailQuotepdf($quotation)
    {
        $quotation = Quotation::with('customer')->find($quotation);

        if ($quotation->customer->email == null) {
            return redirect()->route('cotizaciones.index')->with('error', 'El Cliente no posee correo para enviar la cotizacion');
        }

        $publicpath = public_path('storage/cotizaciones/');
        $namepdf = config('app.name', 'Laravel').' - cotizacion - '.$quotation->customer_name.' - '.date('Y-m-d').'.pdf';
        $urlpdf = $publicpath.$namepdf;


        $pdf = Pdf::loadView('pdfs.quotation', compact('quotation'))
                ->save($urlpdf);

        try {
            Mail::to($quotation->customer->email)->send(new SendQuotation($quotation, $urlpdf, $namepdf));

            return redirect()->route('cotizaciones.index')->with('success', 'Cotizacion Enviada Exitosamente');
        } catch (\Throwable $th) {
            Log::error("error al enviar cotizacion: ".$th->getMessage());

            return redirect()->route('cotizaciones.index')->with('error', 'Error al enviar la cotizacion, verifique su correo');
        }

    }

    public function cambiarStatus(Request $request)
    {
        $quote = Quotation::find($request->id);
        $quote->update([
            'status' => $request->status
        ]);

        return redirect()->route('cotizaciones.index')->with('success', 'Status de Cotización Actualizada Correctamente');
    }

    public function addReferencias(Request $request)
    {
        $quotation = Quotation::find($request->id);
        $quotation->update([
            'invoice_number' => $request->invoice_number
        ]);

        return redirect()->route('cotizaciones.index')->with('success', 'Número de Factura de Cotización agregada Correctamente');
    }
}
