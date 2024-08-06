<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // $data = DB::table('products')
        // ->join('product_store_qties', 'products.id', '=', 'product_store_qties.product_id')
        // ->where('product_store_qties.quantity', '<=', 'products.alert_quantity')
        // ->select('products.id', 'products.name', 'products.price', 'product_store_qties.quantity')
        // ->get();

        // View::share('productsQty', $data);

    }
}
