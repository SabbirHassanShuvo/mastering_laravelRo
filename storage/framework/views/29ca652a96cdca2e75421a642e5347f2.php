<?php $__env->startSection('title', 'Sign Up | admin'); ?>
<?php $__env->startSection('content'); ?>
    <form class="needs-validation" novalidate method="post" action="<?php echo e(route('auth.signup.post')); ?>">

        <div class="mb-3">
            <label for="useremail" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="useremail" 
                name="email"
            placeholder="Enter email address" required>
            <div class="invalid-feedback">
                Please enter email
            </div>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="username" 
                name="name"
            placeholder="Enter username" required>
            <div class="invalid-feedback">
                Please enter username
            </div>
        </div>
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label class="form-label" for="password-input">Password</label>
            <div class="position-relative auth-pass-inputgroup">
                <input type="password" class="form-control pe-5 password-input" 
                name="password"
                onpaste="return false" placeholder="Enter password" id="password-input" aria-describedby="passwordInput" 
                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                <div class="invalid-feedback">
                    Please enter password
                </div>
            </div>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <span class="text-red-600 text-sm mt-1 block"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password-input">Confirm Password</label>
            <div class="position-relative auth-pass-inputgroup">
                <input type="password" class="form-control pe-5 password-input" 
                name="password_confirmation"
                onpaste="return false" placeholder="Enter password" id="password-input" aria-describedby="passwordInput" 
                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required>
                <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                <div class="invalid-feedback">
                    Please enter password
                </div>
            </div>
        </div>
        <div class="mb-4">
            <p class="mb-0 fs-12 text-muted fst-italic">By registering you agree to the <?php echo e($settings->app_name); ?> <a href="#" class="text-primary text-decoration-underline fst-normal fw-medium">Terms of Use</a></p>
        </div>

        <div id="password-contain" class="p-3 bg-light mb-2 rounded">
            <h5 class="fs-13">Password must contain:</h5>
            <p id="pass-length" class="invalid fs-12 mb-2">Minimum <b>8 characters</b></p>
            <p id="pass-lower" class="invalid fs-12 mb-2">At <b>lowercase</b> letter (a-z)</p>
            <p id="pass-upper" class="invalid fs-12 mb-2">At least <b>uppercase</b> letter (A-Z)</p>
            <p id="pass-number" class="invalid fs-12 mb-0">A least <b>number</b> (0-9)</p>
        </div>

        <div class="mt-4">
            <button class="btn btn-success w-100" type="submit">Sign Up</button>
        </div>

        
    </form>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('srcipts-bottom'); ?>
    <!-- password-create init -->
    <script src="<?php echo e(asset('')); ?>assets/js/pages/passowrd-create.init.js"></script>
    <!-- password-addon init -->
    <script src="<?php echo e(asset('assets/js/pages/password-addon.init.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('backend.layout.auth.auth-app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Downloads\Sabbir\mastering_laravelRo\resources\views/backend/layout/auth/signup.blade.php ENDPATH**/ ?>