<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function($data){
                    $img = $data->image ? asset($data->image) : 'https://via.placeholder.com/60';
                    return '<img src="'.$img.'" class="category-img"/>';
                })
                ->addColumn('status', function($data){
                    $backgroundColor = $data->status ? '#4CAF50' : '#ccc';
                    $sliderTranslateX = $data->status ? '26px' : '2px';
                    return getStatusHTML($data, $backgroundColor, $sliderTranslateX);
                })
                ->addColumn('action', function ($data) {
                    return '
                    <div class="d-flex justify-content-center gap-1">
                        <button onclick="edit(' . $data->id . ')" class="btn btn-soft-info btn-sm" title="Edit">
                            <i class="ri-pencil-line"></i>
                        </button>
                        <button onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-soft-danger btn-sm" title="Delete">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>';
                })
                ->rawColumns(['image','status','action'])
                ->make(true);
        }

        return view("backend.layout.categories.index");
    }

    public function create()
    {
        return response()->json([
            'statuses' => Category::_status()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            "title"  => "required|string|max:255",
            "slug"   => "required|string|unique:categories,slug",
            "image"  => "nullable|image|mimes:jpg,jpeg,png,webp|max:2048",
            "status" => "required",
        ]);

        $data = $request->only(['title','slug','status']);

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/categories'), $imageName);
            $data['image'] = 'uploads/categories/'.$imageName;
        }

        Category::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully'
        ]);
    }

    public function edit(Category $category)
    {
        return response()->json([
            'success' => true,
            'category' => $category,
            'statuses' => Category::_status()
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            "title"  => "required|string|max:255",
            "slug"   => "required|string|unique:categories,slug,".$category->id,
            "image"  => "nullable|image|mimes:jpg,jpeg,png,webp|max:2048",
            "status" => "required",
        ]);

        $data = $request->only(['title','slug','status']);

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('uploads/categories'), $imageName);
            $data['image'] = 'uploads/categories/'.$imageName;
        }

        $category->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully'
        ]);
    }

    public function destroy(Category $category)
    {
        // Delete image if exists
        // if ($category->image && file_exists(public_path($category->image))) {
        //     unlink(public_path($category->image));
        // }

        // Delete category
        $category->delete();

        // Return JSON response for AJAX
        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    public function status($id)
    {
        $category = Category::findOrFail($id);
        $category->status = !$category->status;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated',
        ]);
    }
}