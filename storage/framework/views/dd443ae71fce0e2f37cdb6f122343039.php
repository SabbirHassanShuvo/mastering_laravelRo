<?php $__env->startSection('content'); ?>
    <div class="col-lg-6">
        <div class="p-lg-5 p-4">
            <p class="text-muted">Reset password with <?php echo e($settings->app_name); ?></p>

            <div class="mt-2 text-center">
                <lord-icon src="https://cdn.lordicon.com/rhvddzym.json" trigger="loop" colors="primary:#0ab39c"
                    class="avatar-xl">
                </lord-icon>
            </div>
                                

            <div class="alert alert-borderless alert-warning text-center mb-2 mx-2" role="alert">
                Enter your OTP for <b><?php echo e($email); ?></b>
            </div>
            <div class="p-2">
                <form method="post" action="<?php echo e(route('auth.otp.post')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4">
                        <label class="form-label">OTP</label>
                        <input type="text" class="form-control" name="otp"  placeholder="Give OPT here">
                    </div>
                    <input type="text" hidden name="email" value="<?php echo e($email); ?>">
                    <div class="text-center mt-4">
                        <button class="btn btn-success w-100" type="submit">Submit</button>
                    </div>
                </form><!-- end form -->
            </div>
          
           
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layout.auth.auth-app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Downloads\Sabbir\mastering_laravelRo\resources\views/backend/layout/auth/otp-page.blade.php ENDPATH**/ ?>