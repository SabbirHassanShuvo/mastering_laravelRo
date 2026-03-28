<?php $__env->startSection('content'); ?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">System Settings</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                        <li class="breadcrumb-item active">System Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <form action="<?php echo e(route('backend.settings.system.update')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="row">
            <!-- Branding Section -->
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0 flex-grow-1"><i class="ri-palette-line align-middle me-1 text-primary"></i> Branding & Logos</h4>
                        <div class="flex-shrink-0">
                            <button type="submit" class="btn btn-primary btn-label waves-effect waves-light">
                                <i class="ri-save-line label-icon align-middle fs-16 me-2"></i> Save Settings
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Logo -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Company Logo</label>
                                <input type="file" name="logo"
                                    class="dropify <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-height="120"
                                    <?php if(!empty($settings->logo)): ?> data-default-file="<?php echo e(asset($settings->logo)); ?>" <?php endif; ?>>
                                <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <p class="text-muted mt-2 fs-12 text-center">Transparent background recommended (PNG/SVG)</p>
                            </div>

                            <!-- Mini Logo -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Mini Logo / Sidebar Logo</label>
                                <input type="file" name="mini_logo"
                                    class="dropify <?php $__errorArgs = ['mini_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-height="120"
                                    <?php if(!empty($settings->mini_logo)): ?> data-default-file="<?php echo e(asset($settings->mini_logo)); ?>" <?php endif; ?>>
                                <?php $__errorArgs = ['mini_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <p class="text-muted mt-2 fs-12 text-center">Small icon for collapsed sidebar</p>
                            </div>

                            <!-- Icon -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Favicon / Browser Icon</label>
                                <input type="file" name="icon"
                                    class="dropify <?php $__errorArgs = ['icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" data-height="120"
                                    <?php if(!empty($settings->icon)): ?> data-default-file="<?php echo e(asset($settings->icon)); ?>" <?php endif; ?>>
                                <?php $__errorArgs = ['icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                <p class="text-muted mt-2 fs-12 text-center">Standard 32x32 or 64x64 icon</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- General Settings -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><i class="ri-settings-4-line align-middle me-1 text-primary"></i> General Settings</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label">Website Title</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-global-line"></i></span>
                                <input type="text" name="site_title"
                                    class="form-control <?php $__errorArgs = ['site_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="e.g. SwapApp - Professional Services"
                                    value="<?php echo e(old('site_title', $settings->site_title ?? '')); ?>">
                            </div>
                            <?php $__errorArgs = ['site_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">App Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-app-store-line"></i></span>
                                <input type="text" name="app_name"
                                    class="form-control <?php $__errorArgs = ['app_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="e.g. SwapApp"
                                    value="<?php echo e(old('app_name', $settings->app_name ?? 'SwapApp')); ?>">
                            </div>
                            <?php $__errorArgs = ['app_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">Admin Dashboard Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-dashboard-line"></i></span>
                                <input type="text" name="admin_name"
                                    class="form-control <?php $__errorArgs = ['admin_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="e.g. SwapApp Admin"
                                    value="<?php echo e(old('admin_name', $settings->admin_name ?? 'SwapApp')); ?>">
                            </div>
                            <?php $__errorArgs = ['admin_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact & Footer -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><i class="ri-contacts-book-2-line align-middle me-1 text-primary"></i> Contact & Footer</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label class="form-label">Copyright Text</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-copyright-line"></i></span>
                                <input type="text" name="copyright"
                                    class="form-control <?php $__errorArgs = ['copyright'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="© 2025 SwapApp. All rights reserved."
                                    value="<?php echo e(old('copyright', $settings->copyright ?? '')); ?>">
                            </div>
                            <?php $__errorArgs = ['copyright'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Contact Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-phone-line"></i></span>
                                    <input type="text" name="contact"
                                        class="form-control <?php $__errorArgs = ['contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="+1234567890"
                                        value="<?php echo e(old('contact', $settings->contact ?? '')); ?>">
                                </div>
                                <?php $__errorArgs = ['contact'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">Contact Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-mail-line"></i></span>
                                    <input type="email" name="email"
                                        class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="contact@swapapp.com"
                                        value="<?php echo e(old('email', $settings->email ?? '')); ?>">
                                </div>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label">About Section</label>
                            <textarea id="about-editor" name="about" rows="3" class="form-control"
                                placeholder="Brief summary for footer/about page..."><?php echo e(old('about', $settings->about ?? '')); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Service Fees -->
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><i class="ri-money-dollar-circle-line align-middle me-1 text-primary"></i> Service Fees</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Garage Sale Creation Fee ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-coins-line"></i></span>
                                    <input type="number" step="0.01" name="garage_fee"
                                        class="form-control <?php $__errorArgs = ['garage_fee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="2.99"
                                        value="<?php echo e(old('garage_fee', $settings->garage_fee ?? '2.99')); ?>">
                                </div>
                                <?php $__errorArgs = ['garage_fee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label">Product Spotlight Boost Fee ($)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="ri-flashlight-line"></i></span>
                                    <input type="number" step="0.01" name="spotlight_fee"
                                        class="form-control <?php $__errorArgs = ['spotlight_fee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="2.99"
                                        value="<?php echo e(old('spotlight_fee', $settings->spotlight_fee ?? '2.99')); ?>">
                                </div>
                                <?php $__errorArgs = ['spotlight_fee'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback d-block"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-2 mb-4">
                <div class="text-end">
                    <button type="submit" class="btn btn-primary btn-label waves-effect waves-light">
                        <i class="ri-save-line label-icon align-middle fs-16 me-2"></i> Save All Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style-bottom'); ?>
    <style>
        .dropify-wrapper .dropify-message p {
            line-height: 1.5;
            font-size: 14px;
            color: #555;
            font-weight: 500;
        }
        .card-title {
            font-weight: 600;
        }
        .input-group-text {
            background-color: #f3f6f9;
            color: #495057;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts-top'); ?>
<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    
<script>
    document.addEventListener("DOMContentLoaded", function () {
        if(document.querySelector('#about-editor')){
            ClassicEditor
                .create(document.querySelector('#about-editor'))
                .then(editor \=\> {
                    console.log('CKEditor initialized');
                })
                .catch(error \=\> {
                    console.error('CKEditor error:', error);
                });
        }
    });
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Herd\mastering_laravelRo\resources\views/backend/layout/settings/system.blade.php ENDPATH**/ ?>