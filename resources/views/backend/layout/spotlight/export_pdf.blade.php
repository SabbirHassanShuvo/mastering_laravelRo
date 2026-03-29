<!DOCTYPE html>
<html>
<head>
    <title>Spotlight Payments Report</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #4b38b3; padding-bottom: 10px; }
        .header h2 { color: #4b38b3; margin: 0; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #f8f9fa; color: #4b38b3; font-weight: bold; text-align: left; padding: 10px; border: 1px solid #dee2e6; }
        td { padding: 8px; border: 1px solid #dee2e6; vertical-align: top; }
        .status-paid { color: #0ab39c; font-weight: bold; }
        .status-pending { color: #f7b84b; font-weight: bold; }
        .footer { text-align: right; font-size: 10px; color: #999; margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Spotlight Payments Report</h2>
        <p>Generated on: {{ date('d M Y, h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Date</th>
                <th style="width: 20%;">User</th>
                <th style="width: 25%;">Product</th>
                <th style="width: 15%;">Transaction ID</th>
                <th style="width: 10%;">Amount</th>
                <th style="width: 15%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->created_at->format('d M Y') }}</td>
                <td>
                    {{ $payment->user->name ?? 'N/A' }}<br>
                    <small style="color: #888;">{{ $payment->user->email ?? '' }}</small>
                </td>
                <td>{{ $payment->product->title ?? 'N/A' }}</td>
                <td>{{ strtoupper($payment->currency) }}</td>
                <td><strong>${{ number_format($payment->total_fee, 2) }}</strong></td>
                <td class="status-{{ $payment->status }}">{{ ucfirst($payment->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Mastering Laravel | Admin Spotlight Management System
    </div>
</body>
</html>
