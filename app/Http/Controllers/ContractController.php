<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Contract;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreContractRequest;
use App\Http\Requests\UpdateContractRequest;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('contracts.index');
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $data = DB::table('contracts')
                ->join('users', 'contracts.user_id', '=', 'users.id')
                ->join('customers', 'contracts.customer_id', '=', 'customers.id')
                ->select('contracts.*', 'users.name as user', 'customers.name as customer');
            return DataTables::of($data)
                // ->filter(function ($query) use ($request) {
                //     if ($request->has('supplier_id') && $request->get('supplier_id') != '') {
                //         $query->where('purchases.supplier_id', $request->get('supplier_id'));
                //     }

                //     if ($request->has('start') && $request->has('end') && $request->get('start') != '' && $request->get('end') != '') {
                //         $query->whereBetween('purchases.created_at', [$request->get('start'), $request->get('end')]);
                //     }

                //     if ($request->has('search') && $request->get('search')['value'] != '') {
                //         $searchValue = $request->get('search')['value'];
                //         $query->where(function ($subQuery) use ($searchValue) {
                //             $subQuery->where('suppliers.name', 'like', "%{$searchValue}%")
                //                      ->orWhere('users.name', 'like', "%{$searchValue}%")
                //                      ->orWhere('purchases.reference', 'like', "%{$searchValue}%");
                //         });
                //     }
                // })
                ->addColumn('actions', function ($data) {
                    return view('contracts.partials.actions', ['id' => $data->id]);
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
        $clients = Customer::all();
        $products = Product::all();
        return view('contracts.create', compact('clients', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContractRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContractRequest $request, Contract $contract)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        //
    }
}
