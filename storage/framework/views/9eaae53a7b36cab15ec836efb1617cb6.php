<?php $__env->startSection('content'); ?>
<!-- start page title -->
        <div class="row">
                <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                        <h4 class="mb-sm-0">Create FAQ</h4>
                                        <a href="<?php echo e(route('backend.system-user.index')); ?>" class="btn btn-sm btn-outline-secondary">
                                                <i class="mdi mdi-arrow-left"></i> Back
                                        </a>
                                </div>

                                <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item"><a href="javascript:void(0);">Admin User</a></li>
                                                <li class="breadcrumb-item active">Create Admin User</li>
                                        </ol>
                                </div>
                        </div>
                </div>
        </div>
        <!-- end page title -->

        <form method="post" action="<?php echo e(@$system_user ? route('backend.system-user.update', @$system_user->id) : route('backend.system-user.store')); ?>"
                class="row">
                <?php echo csrf_field(); ?>
                <?php if(@$system_user): ?>
                        <?php echo method_field('PATCH'); ?>
                <?php endif; ?>
                <div class="col-lg-8">
                        <div class="card">
                                <div class="card-body">
                                        <div class="row">
                                                <div class="col-lg-6">
                                                        <div class="mb-3">
                                                                <label class="form-label" for="project-title-input">Name</label>
                                                                <input type="text"
                                                                 name="name"
                                                                        value="<?php echo e(old('name', @$system_user->name)); ?>"
                                                                        class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                        placeholder="Enter user name">
                                                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                        <small class="text-danger"><?php echo e($message); ?></small>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                </div>
                                                <div class="col-lg-6">
                                                        <div class="mb-3 mb-lg-0">
                                                                <label for="email"
                                                                        class="form-label">Email</label>
                                                                <input type="email" name="email" <?php echo e(@$system_user ? 'disabled' : ''); ?>

                                                                        value="<?php echo e(old('email', @$system_user->email)); ?>"
                                                                        class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                        <small class="text-danger"><?php echo e($message); ?></small>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                </div>
                                        </div>
                                        <input type="text" name="is_admin_user" hidden value="1">
                                        <div class="row">
                                                <div class="col-lg-6">
                                                        <div class="mb-3 mb-lg-0">
                                                                <label for="email"
                                                                        class="form-label">Password</label>
                                                                <input type="text" name="password" 
                                                                        class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                                        <small class="text-danger"><?php echo e($message); ?></small>
                                                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                        </div>
                                                </div>
                                        </div>
                                </div>
                                <!-- end card body -->
                        </div>
                        <!-- end card -->


                        <!-- end card -->
                        <div class="text-end mb-4">
                                <a href="<?php echo e(route('backend.system-user.index')); ?>" class="btn btn-danger w-sm">Cancel</a>
                                
                                <button type="submit" class="btn btn-success w-sm"><?php echo e(@$system_user ? 'Update' : 'Create'); ?></button>
                        </div>
                </div>
                <!-- end col -->
        </form>
        <!-- end row -->
<?php $__env->stopSection(); ?>
<?php $__env->startPush('styles-top'); ?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<?php $__env->stopPush(); ?>
<?php $__env->startPush('scripts-bottom'); ?>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#role').select2({
            placeholder: "Select roles",
            allowClear: true,
            width: '100%'
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Downloads\Sabbir\mastering_laravelRo\resources\views/backend/layout/users/system_users/form.blade.php ENDPATH**/ ?>