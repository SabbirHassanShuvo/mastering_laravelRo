<?php $__env->startSection('content'); ?>
    <div class="col-lg-6">
        <div class="p-lg-5 p-4">
            <h5 class="text-primary">Forgot Password?</h5>
            <p class="text-muted">Reset password with velzon</p>

            <div class="mt-2 text-center">
                <lord-icon src="https://cdn.lordicon.com/rhvddzym.json" trigger="loop" colors="primary:#0ab39c"
                    class="avatar-xl">
                </lord-icon>
            </div>
                                

            <div class="alert alert-borderless alert-warning text-center mb-2 mx-2" role="alert">
                Enter your email and instructions will be sent to you!
            </div>
            <div class="p-2">
                <form method="post" action="<?php echo e(route('auth.reset.link.post')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-4">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter email address">
                    </div>

                    <div class="text-center mt-4">
                        <button class="btn btn-success w-100" type="submit">Send Reset Link</button>
                    </div>
                </form><!-- end form -->
            </div>
            <div class="mt-3 text-center">
                <p class="mb-0">Wait, I remember my password... <a href="<?php echo e(route('login')); ?>" class="fw-semibold text-primary text-decoration-underline"> Click here </a> </p>
            </div>
           
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layout.auth.auth-app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Downloads\Sabbir\mastering_laravelRo\resources\views/backend/layout/auth/ask-reset-mail.blade.php ENDPATH**/ ?>