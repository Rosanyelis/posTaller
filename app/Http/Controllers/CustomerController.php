<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Customer;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::all();
            return DataTables::of($data)
                ->addColumn('actions', function ($data) {
                    return view('customers.partials.actions', ['id' => $data->id]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('customers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCustomerRequest $request)
    {
        $data = $request->all();
        $customer = Customer::create($data);
        return redirect()->route('clientes.index')->with('success', 'Cliente creado con exito');
    }

    /**
     * Display the specified resource.
     */
    public function show($customer)
    {
        $customer = Customer::find($customer);

        return response()->json($customer);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($customer)
    {
        $customer = Customer::find($customer);
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, $customer)
    {
        $customer = Customer::find($customer);
        $customer->update($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado con exito');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($customer)
    {
        $customer = Customer::find($customer);
        $customer->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado con exito');
    }
}
