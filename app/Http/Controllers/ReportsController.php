<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    /**
     * Informe de ventas
     */
    public function informeventas()
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

    /**
     * Informe de gastos
     */
    public function informegastos()
    {
        $informe = [];
        $data = DB::table('expenses')
        ->join('stores', 'expenses.store_id', '=', 'stores.id')
        ->join('users', 'expenses.user_id', '=', 'users.id')
        ->select('expenses.*', 'stores.name as store_name', 'users.name as user_name')
        ->get();
        $total = 0;
        foreach ($data as $sale) {
            $informe[] = [
                'fecha' => Carbon::parse($sale->created_at)->format('d/m/Y'),
                'motivo' => $sale->name,
                'monto' => $sale->amount,
                'creado_por' => $sale->user_name,
            ];
            $total += $sale->amount;
        }

        return Pdf::loadView('pdfs.informegastos', compact('informe', 'total'))
                ->stream(''.config('app.name', 'Laravel').' - Informe de gastos totales - ' . Carbon::now(). '.pdf');
    }

    public function informeproductos()
    {
        $products = Product::all();
        return Pdf::loadView('pdfs.porcategoria', compact('products'))
                ->stream(''.config('app.name', 'Laravel').' - Listado de Productos.pdf');
    }


}
