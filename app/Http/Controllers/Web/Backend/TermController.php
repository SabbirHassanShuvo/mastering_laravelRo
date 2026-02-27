<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Term;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TermController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $terms = Term::orderByDesc('priority');

            return DataTables::of($terms)
                ->addIndexColumn()

                ->addColumn('status', function ($data) {
                    return '<div class="form-check form-switch mb-2">
                        <input class="form-check-input" 
                            onclick="statusTerm(' . $data->id . ')" 
                            type="checkbox" ' 
                            . ($data->status == Term::STATUS['ACTIVE'] ? 'checked' : '') . '>
                    </div>';
                })

                ->addColumn('action', function ($data) {
                    return '
                        <button onclick="editTerm(' . $data->id . ')" 
                            class="btn btn-info btn-sm">
                            <i class="mdi mdi-pencil"></i>
                        </button>

                        <button onclick="deleteData(\'' . route('backend.feature.terms.destroy', $data->id) . '\')" 
                            class="btn btn-danger btn-sm">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    ';
                })

                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('backend.layout.terms.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    
    public function create()
    {
        $status = Term::STATUS;
        return view('backend.layout.terms.form', compact('status'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|integer',
            'status' => 'required|in:0,1',
        ]);

        Term::create($validated);

        return redirect()
            ->route('backend.feature.terms.index')
            ->with('success', 'Terms created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $term = Term::findOrFail($id);
        $status = Term::STATUS;

        return view('backend.layout.terms.form', compact('term','status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|integer',
            'status' => 'required|in:0,1',
        ]);

        $term = Term::findOrFail($id);
        $term->update($validated);

        return redirect()
            ->route('backend.feature.terms.index')
            ->with('success', 'Terms updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $term = Term::findOrFail($id);
        $term->delete();

        return response()->json([
            'success' => true,
            'message' => 'Terms deleted successfully'
        ]);
    }

    public function status($id)
    {
        $term = Term::findOrFail($id);

        $term->update([
            'status' => $term->status == 1 ? 0 : 1
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }
}
