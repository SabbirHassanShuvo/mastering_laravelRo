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
        <p>Generated on: <?php echo e(date('d M Y, h:i A')); ?></p>
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
            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($payment->created_at->format('d M Y')); ?></td>
                <td>
                    <?php echo e($payment->user->name ?? 'N/A'); ?><br>
                    <small style="color: #888;"><?php echo e($payment->user->email ?? ''); ?></small>
                </td>
                <td><?php echo e($payment->product->title ?? 'N/A'); ?></td>
                <td><small><?php echo e($payment->stripe_payment_intent_id); ?></small></td>
                <td><strong>$<?php echo e(number_format($payment->amount, 2)); ?></strong></td>
                <td class="status-<?php echo e($payment->status); ?>"><?php echo e(ucfirst($payment->status)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="footer">
        Mastering Laravel | Admin Spotlight Management System
    </div>
</body>
</html>
<?php /**PATH C:\Users\88013\Downloads\Sabbir\mastering_laravelRo\resources\views/backend/layout/spotlight/export_pdf.blade.php ENDPATH**/ ?>