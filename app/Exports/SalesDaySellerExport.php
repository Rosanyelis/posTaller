<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SalesDaySellerExport implements FromView
{
    public $vendedor;
    public $dia;

    public function __construct($vendedor, $dia)
    {
        $this->vendedor = $vendedor;
        $this->dia = $dia;
    }
    public function view(): View
    {
        $seller = $this->vendedor;
        $day = $this->dia;

        $sales = Sale::join('users', 'sales.user_id', '=', 'users.id')
            ->select('sales.*', 'users.name as user_name')
            ->where(function ($query) use ($day, $seller) {
                if ($day != 'Todos' && $day != '') {
                    $query->whereDate('created_at', '=', $day);
                }
                if ($seller != 'Todos' && $seller != '') {
                    $query->where('user_id', $seller);
                }
            })
            ->get();

            $informe = [];
            $total = 0;
            $propina = 0;
            $efectivo = 0;
            $credito = 0;
            $cheque = 0;
            $transferencia = 0;

            foreach ($sales as $sale) {
                $informe[] = [
                    'id'        => $sale->id,
                    'fecha'     => Carbon::parse($sale->created_at)->format('d/m/Y'),
                    'cliente'   => $sale->customer_name,
                    'vendedor'  => $sale->user_name,
                    'total'     => $sale->grand_total,
                ];
                $total += $sale->grand_total;
                $propina += $sale->perquisite;

                $payments = DB::table('sales')
                    ->join('sale_payments', 'sales.id', '=', 'sale_payments.sale_id')
                    ->select(DB::raw('SUM(sale_payments.pos_paid) AS total'), 'sale_payments.payment_method')
                    ->groupBy('sale_payments.payment_method')
                    ->where(function ($query) use ($day, $seller) {
                        if ($day != 'Todos' && $day != '') {
                            $query->whereDate('created_at', '=', $day);
                        }
                        if ($seller != 'Todos' && $seller != '') {
                            $query->where('user_id', $seller);
                        }
                    })
                    ->get();

                    foreach ($payments as $key) {
                        if ($key->payment_method == 'Efectivo') {
                            $efectivo = floatval($key->total);
                        } else if ($key->payment_method == 'Tarjeta de credito') {
                            $credito = floatval($key->total);
                        } else if ($key->payment_method == 'Cheque') {
                            $cheque = floatval($key->total);
                        } else if ($key->payment_method == 'Transferencia') {
                            $transferencia = floatval($key->total);
                        }
                    }
            }



        return view('exports.sales_day_seller', [
            'informe' => $informe,
            'vendedor' => $seller,
            'dia' => $day,
            'total' => $total,
            'propina' => $propina,
            'efectivo' => $efectivo,
            'credito' => $credito,
            'cheque' => $cheque,
            'transferencia' => $transferencia
        ]);
    }
}
