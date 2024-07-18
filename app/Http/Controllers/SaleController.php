<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;

class SaleController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $users = User::where('rol_id', '!=' ,'1')->get();
        return view('sales.index', compact('users'));
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('sales')
                ->join('users', 'sales.user_id', '=', 'users.id')
                ->select('sales.*', 'users.name as user_name');
            return DataTables::of($data)
                ->filter(function ($query) use ($request) {
                    if ($request->has('user_id') && $request->get('user_id') != '') {
                        $query->where('sales.user_id', $request->get('user_id'));
                    }

                    if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                        $query->whereBetween('sales.created_at', [$request->get('start'), $request->get('end')]);
                    }

                    if ($request->has('day') && $request->get('day') != '') {
                        $query->whereDate('sales.created_at', '=', $request->get('day'));
                    }
                })
                ->addColumn('actions', function ($data) {
                    return view('sales.partials.actions', ['id' => $data->id]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($sale)
    {
        $data = Sale::with('user', 'saleitems', 'customer', 'payments')->find($sale);
        return response()->json($data);
    }

    public function generateInvoice($sale)
    {
        $sale = Sale::with('user', 'saleitems', 'customer', 'payments')->find($sale);
        return Pdf::loadView('pdfs.invoice', compact('sale'))
                ->stream(''.config('app.name', 'Laravel').' - Factura - ' . $sale->customer_name. '.pdf');
    }

    public function generateInforme()
    {
        $informe = [];
        $data = DB::table('sales')
                ->join('users', 'sales.user_id', '=', 'users.id')
                ->select('sales.*', 'users.name as user_name')
                ->get();
        $total = 0;
        $totalefectivo = 0;
        $totalcredito = 0;
        $totalcheque = 0;
        $totaltransferencia = 0;
        foreach ($data as $sale) {
            $informe[] = [
                'id' => $sale->id,
                'fecha' => Carbon::parse($sale->created_at)->format('d/m/Y'),
                'cliente' => $sale->customer_name,
                'vendedor' => $sale->user_name,
                'total' => $sale->grand_total,

            ];
            $total += $sale->grand_total;

            $payments = DB::table('sales')
                ->join('sale_payments', 'sales.id', '=', 'sale_payments.sale_id')
                ->select(DB::raw('SUM(sale_payments.pos_paid) AS total'), 'sale_payments.payment_method')
                ->groupBy('sale_payments.payment_method')
                ->where('sales.id', $sale->id)
                ->get();

            foreach ($payments as $key) {
                if ($key->payment_method == 'Efectivo') {
                    $totalefectivo = $key->total;
                } else if ($key->payment_method == 'Tarjeta de credito') {
                    $totalcredito = $key->total;
                } else if ($key->payment_method == 'Cheque') {
                    $totalcheque = $key->total;
                } else if ($key->payment_method == 'Transferencia') {
                    $totaltransferencia = $key->total;
                }
            }
        }

        return Pdf::loadView('pdfs.informesales', compact('informe', 'total', 'totalefectivo', 'totalcredito', 'totalcheque', 'totaltransferencia'))
                ->stream(''.config('app.name', 'Laravel').' - Informe de ventas totales - ' . Carbon::now(). '.pdf');
    }
}
