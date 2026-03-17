<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; }
        .email-container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background-color: #f06548; color: #ffffff; padding: 25px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 30px; color: #333333; line-height: 1.6; }
        .content p { margin: 0 0 15px; font-size: 16px; }
        .reason-box { background-color: #fff5f3; border-left: 4px solid #f06548; padding: 15px; margin: 20px 0; border-radius: 0 4px 4px 0; color: #d13010; font-weight: 500; font-size: 15px; }
        .footer { background-color: #f8f9fa; text-align: center; padding: 20px; font-size: 13px; color: #878a99; border-top: 1px solid #e9ebec; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Account Suspension Notice</h1>
        </div>
        <div class="content">
            <p>Dear <?php echo e($name); ?>,</p>
            <p>This email is to notify you that your administrative account has been suspended by the management team.</p>
            
            <p><strong>Reason for suspension:</strong></p>
            <div class="reason-box">
                <?php echo e($reason); ?>

            </div>
            
            <p>While your account is suspended, you will not be able to log in or access any administrative features.</p>
            <p>If you believe this is an error or wish to appeal this decision, please contact the system administrators.</p>
            
            <br>
            <p>Sincerely,</p>
            <p><strong>The Management Team</strong></p>
        </div>
        <div class="footer">
            &copy; <?php echo e(date('Y')); ?> Admin System. All rights reserved.
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\88013\Downloads\Sabbir\mastering_laravelRo\resources\views/emails/suspension.blade.php ENDPATH**/ ?>