<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\WorkOrderController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    # Roles
    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RolController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RolController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}/edit', [RolController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [RolController::class, 'update'])->name('roles.update');
    Route::get('/roles/{role}', [RolController::class, 'destroy'])->name('roles.destroy');

    # Usuarios
    Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
    Route::get('/usuarios/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/usuarios', [UserController::class, 'store'])->name('users.store');
    Route::get('/usuarios/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('users.update');
    Route::get('/usuarios/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    # Tiendas
    Route::get('/tiendas', [StoreController::class, 'index'])->name('tiendas.index');
    Route::get('/tiendas/create', [StoreController::class, 'create'])->name('tiendas.create');
    Route::post('/tiendas', [StoreController::class, 'store'])->name('tiendas.store');
    Route::get('/tiendas/{tienda}/edit', [StoreController::class, 'edit'])->name('tiendas.edit');
    Route::put('/tiendas/{tienda}', [StoreController::class, 'update'])->name('tiendas.update');
    Route::get('/tiendas/{tienda}', [StoreController::class, 'destroy'])->name('tiendas.destroy');

    # Productos
    Route::get('/productos', [ProductController::class, 'index'])->name('productos.index');
    Route::get('/productos/create', [ProductController::class, 'create'])->name('productos.create');
    Route::post('/productos', [ProductController::class, 'store'])->name('productos.store');
    Route::get('/productos/{product}/show', [ProductController::class, 'show'])->name('productos.show');
    Route::get('/productos/{product}/edit', [ProductController::class, 'edit'])->name('productos.edit');
    Route::put('/productos/{product}/update', [ProductController::class, 'update'])->name('productos.update');
    Route::get('/productos/{product}/delete', [ProductController::class, 'destroy'])->name('productos.destroy');

    Route::get('/productos/importar-productos', [ProductController::class, 'view_import'])->name('productos.viewimport');
    Route::post('/productos/import-data', [ProductController::class, 'import'])->name('productos.import');
    Route::get('/productos/todos-los-productos', [ProductController::class, 'allproductpdf'])->name('products.allproductpdf');

    # Categorias
    Route::get('/categorias', [CategoryController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/create', [CategoryController::class, 'create'])->name('categorias.create');
    Route::post('/categorias/guardar', [CategoryController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/{category}/editar', [CategoryController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{category}/actualizar', [CategoryController::class, 'update'])->name('categorias.update');
    Route::get('/categorias/{category}/eliminar', [CategoryController::class, 'destroy'])->name('categorias.destroy');

    Route::get('/categorias/importar-categorias', [CategoryController::class, 'view_import'])->name('categorias.viewimport');
    Route::post('/categorias/import-data', [CategoryController::class, 'import'])->name('categorias.import');
    // Route::get('/categorias/todos-las-categorias', [CategoryController::class, 'allcategorypdf'])->name('categories.allcategorypdf');
    Route::get('/categorias/{category}/productos-por-categoria', [CategoryController::class, 'productcategory'])->name('categories.productcategory');

    # Ventas
    Route::get('/ventas', [SaleController::class, 'index'])->name('ventas.index');
    Route::get('/ventas/create', [SaleController::class, 'create'])->name('ventas.create');
    Route::post('/ventas', [SaleController::class, 'store'])->name('ventas.store');
    Route::get('/ventas/{sale}/show', [SaleController::class, 'show'])->name('ventas.show');
    Route::get('/ventas/{sale}/edit', [SaleController::class, 'edit'])->name('ventas.edit');
    Route::put('/ventas/{sale}', [SaleController::class, 'update'])->name('ventas.update');
    Route::get('/ventas/{sale}', [SaleController::class, 'destroy'])->name('ventas.destroy');

    # Cotizaciones
    Route::get('/cotizaciones', [QuotationController::class, 'index'])->name('cotizaciones.index');
    Route::get('/cotizaciones/create', [QuotationController::class, 'create'])->name('cotizaciones.create');
    Route::post('/cotizaciones', [QuotationController::class, 'store'])->name('cotizaciones.store');
    Route::get('/cotizaciones/{quotation}/show', [QuotationController::class, 'show'])->name('cotizaciones.show');
    Route::get('/cotizaciones/{quotation}/edit', [QuotationController::class, 'edit'])->name('cotizaciones.edit');
    Route::put('/cotizaciones/{quotation}/update', [QuotationController::class, 'update'])->name('cotizaciones.update');
    Route::get('/cotizaciones/{quotation}/delete', [QuotationController::class, 'destroy'])->name('cotizaciones.destroy');

    Route::get('/cotizaciones/{quotation}/productjson', [QuotationController::class, 'productjson'])->name('cotizaciones.productjson');
    Route::get('/cotizaciones/{quotation}/quotepdf', [QuotationController::class, 'quotepdf'])->name('cotizaciones.quotepdf');

    # proveedores
    Route::get('/proveedores', [SupplierController::class, 'index'])->name('proveedor.index');
    Route::get('/proveedores/create', [SupplierController::class, 'create'])->name('proveedor.create');
    Route::post('/proveedores', [SupplierController::class, 'store'])->name('proveedor.store');
    Route::get('/proveedores/{proveedor}/edit', [SupplierController::class, 'edit'])->name('proveedor.edit');
    Route::put('/proveedores/{proveedor}', [SupplierController::class, 'update'])->name('proveedor.update');
    Route::get('/proveedores/{proveedor}', [SupplierController::class, 'destroy'])->name('proveedor.destroy');

    # Clientes
    Route::get('/clientes', [CustomerController::class, 'index'])->name('clientes.index');
    Route::get('/clientes/create', [CustomerController::class, 'create'])->name('clientes.create');
    Route::post('/clientes', [CustomerController::class, 'store'])->name('clientes.store');
    Route::get('/clientes/{cliente}/show', [CustomerController::class, 'show'])->name('clientes.show');
    Route::get('/clientes/{cliente}/edit', [CustomerController::class, 'edit'])->name('clientes.edit');
    Route::put('/clientes/{cliente}', [CustomerController::class, 'update'])->name('clientes.update');
    Route::get('/clientes/{cliente}', [CustomerController::class, 'destroy'])->name('clientes.destroy');

    # Gastos
    Route::get('/gastos', [ExpenseController::class, 'index'])->name('gastos.index');
    Route::get('/gastos/create', [ExpenseController::class, 'create'])->name('gastos.create');
    Route::post('/gastos', [ExpenseController::class, 'store'])->name('gastos.store');
    Route::get('/gastos/{gasto}/show', [ExpenseController::class, 'show'])->name('gastos.show');
    Route::get('/gastos/{gasto}/edit', [ExpenseController::class, 'edit'])->name('gastos.edit');
    Route::put('/gastos/{gasto}', [ExpenseController::class, 'update'])->name('gastos.update');
    Route::get('/gastos/{gasto}', [ExpenseController::class, 'destroy'])->name('gastos.destroy');

    # compras
    Route::get('/compras', [PurchaseController::class, 'index'])->name('compras.index');
    Route::get('/compras/create', [PurchaseController::class, 'create'])->name('compras.create');
    Route::post('/compras', [PurchaseController::class, 'store'])->name('compras.store');
    Route::get('/compras/{compra}/show', [PurchaseController::class, 'show'])->name('compras.show');
    Route::get('/compras/{compra}/edit', [PurchaseController::class, 'edit'])->name('compras.edit');
    Route::put('/compras/{compra}', [PurchaseController::class, 'update'])->name('compras.update');
    Route::get('/compras/{compra}', [PurchaseController::class, 'destroy'])->name('compras.destroy');

    Route::get('/compras/{compra}/purchasepdf', [PurchaseController::class, 'purchasepdf'])->name('cotizaciones.purchasepdf');

    # Ordenes de Trabajo
    Route::get('/ordenes-trabajo', [WorkOrderController::class, 'index'])->name('ordenes-trabajo.index');
    Route::get('/ordenes-trabajo/create', [WorkOrderController::class, 'create'])->name('ordenes-trabajo.create');
    Route::post('/ordenes-trabajo', [WorkOrderController::class, 'store'])->name('ordenes-trabajo.store');
    Route::get('/ordenes-trabajo/{workOrder}/show', [WorkOrderController::class, 'show'])->name('ordenes-trabajo.show');
    Route::get('/ordenes-trabajo/{workOrder}/edit', [WorkOrderController::class, 'edit'])->name('ordenes-trabajo.edit');
    Route::put('/ordenes-trabajo/{workOrder}', [WorkOrderController::class, 'update'])->name('ordenes-trabajo.update');
    Route::get('/ordenes-trabajo/{workOrder}', [WorkOrderController::class, 'destroy'])->name('ordenes-trabajo.destroy');

    # Reportes
    Route::get('/reportes', [App\Http\Controllers\ReporteController::class, 'index'])->name('reportes.index');

});
Route::get('comandos', function () {
    Artisan::call('optimize');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:cache');
    Artisan::call('route:cache');

    return 'Comandos ejecutados con éxitos';
});

require __DIR__.'/auth.php';
