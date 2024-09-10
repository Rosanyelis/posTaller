<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TodoListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        if ($request->ajax()) {
            $data = TodoList::select('todo_lists.*');
            return DataTables::of($data)
                ->addColumn('actions', function ($data) {
                    return view('todolist.partials.actions', ['id' => $data->id]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('todolist.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->task) {
            TodoList::create([
                'user_id' => auth()->user()->id,
                'task' => $request->task]);

            return response()->json(['message' => 'Tarea agregada correctamente', 'status' => 'success']);
        } else {
            return response()->json(['message' => 'Error al crear la Tarea', 'status' => 'error']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function changeStatus(Request $request)
    {
        if ($request->status == 1) {
            $status = '0';
        }

        if ($request->status == 0) {
            $status = '1';
        }
        $todo = TodoList::find($request->id);
        $todo->update(['status' => $status]);

        return response()->json(['message' => 'Tarea actualizada correctamente', 'status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $todo = TodoList::find($request->id);
        $todo->delete();
        return response()->json(['message' => 'Tarea eliminada correctamente', 'status' => 'success']);
    }
}
