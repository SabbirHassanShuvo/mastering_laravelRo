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
                ->addColumn('title', fn($data) => $data->title)
                ->addColumn('slug', fn($data) => $data->slug)
                ->addColumn('image', function($data){
                    $img = $data->image ? asset($data->image) : 'https://via.placeholder.com/60';
                    return '<img src="'.$img.'" width="60"/>';
                })
                ->addColumn('status', function($data){
                    $backgroundColor = $data->status ? '#4CAF50' : '#ccc';
                    $sliderTranslateX = $data->status ? '26px' : '2px';
                    return getStatusHTML($data, $backgroundColor, $sliderTranslateX);
                })
                ->addColumn('action', function ($data) {
                    return '
                    <button onclick="edit(' . $data->id . ')" class="btn btn-info btn-sm"><i class="mdi mdi-pencil"></i></button>
                    <button onclick="showDeleteConfirm(' . $data->id . ')" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i></button>
                    ';
                })
                ->rawColumns(['image','status','action'])
                ->make(true);
        }

        return view("backend.layout.categories.index");
    }

    public function create()
    {
        $data['statuses'] = Category::_status();
        return view("backend.layout.categories.form", $data);
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

        return redirect()->route('backend.feature.category.index')
                         ->with('success','Category created successfully');
    }

    public function edit(Category $category)
    {
        $data['category'] = $category;
        $data['statuses'] = Category::_status();
        return view("backend.layout.categories.form", $data);
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

        return redirect()->route('backend.feature.category.index')
                         ->with('success','Category updated successfully');
    }

    public function destroy(Category $category)
    {
        if($category->image && file_exists(public_path($category->image))){
            unlink(public_path($category->image));
        }
        $category->delete();

        return redirect()->route('backend.feature.category.index')
                         ->with('success','Category deleted successfully');
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