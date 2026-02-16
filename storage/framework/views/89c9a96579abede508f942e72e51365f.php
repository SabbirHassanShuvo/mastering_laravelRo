<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('auth.reset.finish')); ?>" method="post">
    <?php echo csrf_field(); ?>
    <input type="text" hidden name="email" value="<?php echo e($email); ?>">
    <div class="row mb-3">
        <div class="col-lg-3">
            <label for="nameInput" class="form-label">New Password</label>
        </div>
        <div class="col-lg-9">
            <input type="password" class="form-control" id="nameInput" name="password" >
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-lg-3">
            <label for="nameInput" class="form-label">Confim Password</label>
        </div>
        <div class="col-lg-9">
            <input type="password" class="form-control" id="nameInput" name="password_confirmation">
        </div>
    </div>
    <div class="text-end">
        <button type="submit" class="btn btn-success">Update</button>
    </div>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layout.auth.auth-app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Downloads\Sabbir\mastering_laravelRo\resources\views/backend/layout/auth/reset-password.blade.php ENDPATH**/ ?>