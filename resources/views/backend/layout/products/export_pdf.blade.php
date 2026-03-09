<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #4b38b3; padding-bottom: 10px; }
        .header h2 { color: #4b38b3; margin: 0; text-transform: uppercase; }
        .info { margin-bottom: 20px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f5f5f9; color: #333; font-weight: bold; text-align: left; padding: 10px; border: 1px solid #e2e5ec; }
        td { padding: 10px; border: 1px solid #e2e5ec; vertical-align: top; }
        tr:nth-child(even) { background-color: #fafafa; }
        .badge { padding: 3px 7px; border-radius: 4px; font-size: 10px; color: #fff; text-transform: uppercase; }
        .bg-active { background-color: #0ab39c; }
        .bg-sold { background-color: #299cdb; }
        .bg-expired { background-color: #f7b84b; }
        .bg-archived { background-color: #878a99; }
        .text-primary { color: #4b38b3; font-weight: bold; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #999; padding: 10px 0; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $title }}</h2>
        <p>Generated on: {{ $date }}</p>
    </div>

    <div class="info">
        Total Products: <strong>{{ count($products) }}</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Details</th>
                <th>Owner</th>
                <th>Category</th>
                <th>Price</th>
                <th>Status</th>
                <th>Attributes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $p)
            <tr>
                <td>#{{ $p->id }}</td>
                <td>
                    <span class="text-primary">{{ $p->title }}</span><br>
                    <small style="color:#666">Posted: {{ $p->posted_at ? $p->posted_at->format('d M Y') : 'N/A' }}</small>
                </td>
                <td>{{ $p->user->name ?? 'N/A' }}</td>
                <td>{{ $p->category->title ?? 'N/A' }}</td>
                <td>
                    @if($p->product_type === 'free')
                        FREE
                    @else
                        ${{ number_format($p->price, 2) }}
                    @endif
                </td>
                <td>
                    <span class="badge bg-{{ $p->status }}">{{ $p->status }}</span>
                </td>
                <td>
                    @if($p->is_spotlighted) <small style="color: #f06548; font-weight:bold">• Spotlight</small><br> @endif
                    @if($p->is_urgent) <small style="color: #ed5e5e; font-weight:bold">• Urgent</small> @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        © {{ date('Y') }} Mastering Laravel - Admin Inventory Report
    </div>
</body>
</html>
