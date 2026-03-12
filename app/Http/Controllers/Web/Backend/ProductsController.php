<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductPhoto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProductsController extends Controller
{
    /**
     * Display products list with Yajra DataTable + filters + stat cards.
     */
    public function index(Request $request)
    {
        // ── Stat cards ──────────────────────────────────────────────────────
        $stats = [
            'total'       => Product::count(),
            'active'      => Product::where('status', 'active')->count(),
            'sold'        => Product::where('status', 'sold')->count(),
            'expired'     => Product::where('status', 'expired')->count(),
            'archived'    => Product::where('status', 'archived')->count(),
            'spotlighted' => Product::where('is_spotlighted', true)->count(),
        ];

        // ── Ajax request → DataTables response ──────────────────────────────
        if ($request->ajax()) {

            $query = Product::with(['user', 'category'])
                ->select('products.*');

            // ── Filters ──────────────────────────────────────────────────────
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('type')) {
                $query->where('product_type', $request->type);
            }
            if ($request->filled('spotlight')) {
                $query->where('is_spotlighted', (bool) $request->spotlight);
            }
            if ($request->filled('urgent')) {
                $query->where('is_urgent', (bool) $request->urgent);
            }

            return DataTables::of($query)
                ->addIndexColumn()

                // ── Explicit Search Logic ─────────────────────────────────
                ->filterColumn('title', function($query, $keyword) {
                    $query->where('products.title', 'like', "%{$keyword}%");
                })
                ->filterColumn('owner', function($query, $keyword) {
                    $query->whereHas('user', function($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('category', function($query, $keyword) {
                    $query->whereHas('category', function($q) use ($keyword) {
                        $q->where('title', 'like', "%{$keyword}%");
                    });
                })

                // ── Thumbnail ─────────────────────────────────────────────
                ->addColumn('image', function (Product $product) {

                    $src = $product->product_image
                        ? asset('storage/' . $product->product_image)
                        : asset('assets/images/no-image.png');

                    return '<img src="' . $src . '" 
                                style="width:52px;height:52px;object-fit:cover;border-radius:6px;"
                                class="shadow-sm">';
                    })

                // ── Title ─────────────────────────────────────────────────
                ->addColumn('title', function (Product $product) {
                    $urgent = $product->is_urgent
                        ? '<span class="badge bg-danger ms-1">Urgent</span>'
                        : '';
                    return '<span class="fw-semibold">' . e($product->title) . '</span>' . $urgent;
                })

                // ── Owner ─────────────────────────────────────────────────
                ->addColumn('owner', fn(Product $p) =>
                    $p->user ? e($p->user->name) : '<span class="text-muted">—</span>'
                )

                // ── Category ──────────────────────────────────────────────
                ->addColumn('category', fn(Product $p) =>
                    $p->category ? e($p->category->title) : '<span class="text-muted">—</span>'
                )

                // ── Type / Price ──────────────────────────────────────────
                ->addColumn('type_price', function (Product $product) {
                    if ($product->product_type === 'free') {
                        return '<span class="badge bg-soft-success text-success">Free</span>';
                    }
                    $price = $product->price
                        ? '$' . number_format($product->price, 2)
                        : '<span class="text-muted">—</span>';
                    return '<span class="badge bg-soft-primary text-primary">Paid</span> ' . $price;
                })

                // ── Status badge ──────────────────────────────────────────
                ->addColumn('status', function (Product $product) {
                    $map = [
                        'active'   => 'success',
                        'sold'     => 'info',
                        'expired'  => 'warning',
                        'archived' => 'secondary',
                    ];
                    $color = $map[$product->status] ?? 'dark';
                    return '<span class="badge bg-' . $color . '">' . ucfirst($product->status) . '</span>';
                })

                // ── Spotlight badge ───────────────────────────────────────
                ->addColumn('spotlight', function (Product $product) {
                    if ($product->is_spotlighted) {
                        return '<span class="badge bg-danger"><i class="ri-flashlight-line"></i> Spotlighted</span>';
                    }
                    return '<span class="badge bg-soft-secondary text-secondary">—</span>';
                })

                // ── Posted At ─────────────────────────────────────────────
                ->addColumn('posted_at', fn(Product $p) =>
                    $p->posted_at ? $p->posted_at->format('d M Y') : '—'
                )

                // ── Action buttons ────────────────────────────────────────
                ->addColumn('action', function (Product $product) {
                    return '
                        <div class="d-flex gap-1">
                            <button onclick="viewProduct(' . $product->id . ')"
                                class="btn btn-soft-info btn-sm" title="View">
                                <i class="ri-eye-line"></i>
                            </button>
                            <button onclick="showStatusChangeAlert(' . $product->id . ', \'' . $product->status . '\')"
                                class="btn btn-soft-warning btn-sm" title="Change Status">
                                <i class="ri-refresh-line"></i>
                            </button>
                            <button onclick="showDeleteConfirm(' . $product->id . ')"
                                class="btn btn-soft-danger btn-sm" title="Delete">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </div>';
                })

                ->rawColumns(['image', 'title', 'owner', 'category', 'type_price', 'status', 'spotlight', 'action'])
                ->make(true);
        }

        return view('backend.layout.products.index', compact('stats'));
    }

    /**
     * Show create product form.
     */
    public function create()
    {
        $categories = Category::orderBy('title')->get();
        $users = User::orderBy('name')->get(); 
        
        return view('backend.layout.products.create', compact('categories', 'users'));
    }

    /**
     * Store new product (Admins can create for themselves or others).
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'         => 'required|exists:users,id',
            'category_id'     => 'required|exists:categories,id',
            'title'           => 'required|string|max:255',
            'product_type'    => 'required|in:paid,free',
            'price'           => 'nullable|numeric|min:0',
            'condition_status'=> 'nullable|string',
            'description'     => 'nullable|string',
            'pickup_location' => 'nullable|string',
            'is_urgent'       => 'nullable|boolean',
            'urgent_pickup_date' => 'nullable|date',
            'urgent_pickup_notes'=> 'nullable|string',
            'product_image'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery.*'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only([
                'user_id', 'category_id', 'title', 'product_type', 'price', 
                'condition_status', 'description', 'pickup_location',
                'urgent_pickup_date', 'urgent_pickup_notes'
            ]);
            $data['is_urgent'] = $request->has('is_urgent');
            $data['status'] = 'active';
            $data['posted_at'] = now();
            
            // Handle Main Image
            if ($request->hasFile('product_image')) {
                $path = $request->file('product_image')->store('products/main', 'public');
                $data['product_image'] = $path;
            }

            $product = Product::create($data);

            // Handle Gallery Images
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $path = $file->store('products/gallery', 'public');
                    ProductPhoto::create([
                        'product_id' => $product->id,
                        'photo_url'  => $path
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('backend.products.index')->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating product: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show single product detail (Phase 1 stub — to be built in show.blade.php).
     */
    public function show(Product $product)
    {
        $product->load(['user', 'category', 'photos', 'loves']);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'data'    => $product,
                'formatted_price' => $product->product_type === 'free' ? 'Free' : '$' . number_format($product->price, 2),
                'category_name'   => $product->category->title ?? 'N/A',
                'owner_name'      => $product->user->name ?? 'N/A',
                'posted_date'     => $product->posted_at ? $product->posted_at->format('d M Y, h:i A') : 'N/A',
                'expires_date'    => $product->expires_at ? $product->expires_at->format('d M Y') : 'N/A',
                'image_path'      => $product->product_image ? asset('storage/' . $product->product_image) : asset('assets/images/no-image.png'),
                'gallery'         => $product->photos->map(function ($p) {
                    return asset('storage/' . $p->photo_url);
                }),
                'is_urgent'       => (bool)$product->is_urgent,
                'is_spotlighted'  => (bool)$product->is_spotlighted,
                'loves_count'     => $product->loves->count(),
                'condition'       => ucfirst($product->condition_status ?? 'N/A'),
                'location'        => $product->pickup_location ?? 'N/A',
            ]);
        }

        return view('backend.layout.products.show', compact('product'));
    }

    /**
     * Change product status via AJAX.
     */
    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,sold,expired,archived',
        ]);

        $product = Product::findOrFail($id);
        $product->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Product status updated to ' . ucfirst($request->status),
        ]);
    }

    /**
     * Delete a product and its associated photos.
     */
    public function destroy(Product $product)
    {
        // Delete storage images
        if ($product->product_image && file_exists(public_path($product->product_image))) {
            @unlink(public_path($product->product_image));
        }
        foreach ($product->photos as $photo) {
            if (file_exists(public_path($photo->photo_url))) {
                @unlink(public_path($photo->photo_url));
            }
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.',
        ]);
    }
    /**
     * Export products to CSV/Excel compatible format.
     */
    public function exportCsv()
    {
        $products = Product::with(['user', 'category'])->latest()->get();
        $filename = "products_export_" . date('Y-m-d_H-i-s') . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Title', 'Owner', 'Category', 'Type', 'Price', 'Status', 'Spotlight', 'Urgent', 'Posted At'];

        $callback = function() use($products, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($products as $p) {
                fputcsv($file, [
                    $p->id,
                    $p->title,
                    $p->user->name ?? 'N/A',
                    $p->category->title ?? 'N/A',
                    $p->product_type,
                    $p->price ?? 0,
                    $p->status,
                    $p->is_spotlighted ? 'Yes' : 'No',
                    $p->is_urgent ? 'Yes' : 'No',
                    $p->posted_at ? $p->posted_at->format('Y-m-d') : 'N/A',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export products to PDF.
     */
    public function exportPdf()
    {
        $products = Product::with(['user', 'category'])->latest()->get();
        $data = [
            'products' => $products,
            'date'     => date('d M Y'),
            'title'    => 'Products Inventory Report'
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('backend.layout.products.export_pdf', $data);
        return $pdf->download('products_report_' . date('Y-m-d') . '.pdf');
    }
    /**
     * Export products to Excel.
     */
    public function exportExcel()
    {
        $products = Product::with(['user', 'category'])->latest()->get();
        $filename = "products_export_" . date('Y-m-d_H-i-s') . ".xls";
        
        $html = '<table border="1">';
        $html .= '<tr><th>ID</th><th>Title</th><th>Owner</th><th>Category</th><th>Type</th><th>Price</th><th>Status</th><th>Spotlight</th><th>Urgent</th><th>Posted At</th></tr>';
        foreach ($products as $p) {
            $html .= '<tr>';
            $html .= '<td>' . $p->id . '</td>';
            $html .= '<td>' . htmlspecialchars($p->title) . '</td>';
            $html .= '<td>' . htmlspecialchars($p->user->name ?? 'N/A') . '</td>';
            $html .= '<td>' . htmlspecialchars($p->category->title ?? 'N/A') . '</td>';
            $html .= '<td>' . $p->product_type . '</td>';
            $html .= '<td>' . ($p->price ?? 0) . '</td>';
            $html .= '<td>' . ucfirst($p->status) . '</td>';
            $html .= '<td>' . ($p->is_spotlighted ? 'Yes' : 'No') . '</td>';
            $html .= '<td>' . ($p->is_urgent ? 'Yes' : 'No') . '</td>';
            $html .= '<td>' . ($p->posted_at ? $p->posted_at->format('Y-m-d') : 'N/A') . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        return response($html)
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Export products to JSON.
     */
    public function exportJson()
    {
        $products = Product::with(['user', 'category'])->latest()->get();
        $filename = "products_export_" . date('Y-m-d_H-i-s') . ".json";
        
        return response()->json($products)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}

