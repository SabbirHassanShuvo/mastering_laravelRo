<?php $__env->startSection('content'); ?>
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Mail Configuration</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Settings</a></li>
                        <li class="breadcrumb-item active">Mail Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row justify-content-center">
        <div class="col-xxl-8">
            <div class="card shadow-lg border-0">
                <div class="card-header align-items-center d-flex bg-primary p-3">
                    <h4 class="card-title mb-0 flex-grow-1 text-white"><i class="ri-mail-send-line align-middle me-1"></i> SMTP Server Settings</h4>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="<?php echo e(route('backend.settings.mail.update')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <div class="row g-4">
                            <!-- Mailer & Host -->
                            <div class="col-md-6">
                                <label for="mail_mailer" class="form-label fw-bold">Mail Mailer</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ri-server-line"></i></span>
                                    <input class="form-control border-start-0 <?php $__errorArgs = ['mail_mailer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="mail_mailer" name="mail_mailer" placeholder="e.g. smtp"
                                        type="text" value="<?php echo e(env('MAIL_MAILER')); ?>">
                                </div>
                                <?php $__errorArgs = ['mail_mailer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label for="mail_host" class="form-label fw-bold">Mail Host</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ri-link"></i></span>
                                    <input class="form-control border-start-0 <?php $__errorArgs = ['mail_host'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="mail_host" name="mail_host" placeholder="e.g. smtp.mailtrap.io" type="text"
                                        value="<?php echo e(env('MAIL_HOST')); ?>">
                                </div>
                                <?php $__errorArgs = ['mail_host'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Port & Encryption -->
                            <div class="col-md-6">
                                <label for="mail_port" class="form-label fw-bold">Mail Port</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ri-door-open-line"></i></span>
                                    <input class="form-control border-start-0 <?php $__errorArgs = ['mail_port'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="mail_port" name="mail_port" placeholder="e.g. 2525"
                                        type="text" value="<?php echo e(env('MAIL_PORT')); ?>">
                                </div>
                                <?php $__errorArgs = ['mail_port'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label for="mail_encryption" class="form-label fw-bold">Encryption</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ri-shield-keyhole-line"></i></span>
                                    <input class="form-control border-start-0 <?php $__errorArgs = ['mail_encryption'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="mail_encryption" name="mail_encryption"
                                        placeholder="e.g. tls" type="text"
                                        value="<?php echo e(env('MAIL_ENCRYPTION')); ?>">
                                </div>
                                <?php $__errorArgs = ['mail_encryption'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Credentials -->
                            <div class="col-md-6">
                                <label for="mail_username" class="form-label fw-bold">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ri-user-settings-line"></i></span>
                                    <input class="form-control border-start-0 <?php $__errorArgs = ['mail_username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="mail_username" name="mail_username"
                                        placeholder="Enter SMTP username" type="text"
                                        value="<?php echo e(env('MAIL_USERNAME')); ?>">
                                </div>
                                <?php $__errorArgs = ['mail_username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label for="mail_password" class="form-label fw-bold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ri-lock-password-line"></i></span>
                                    <input class="form-control border-start-0 <?php $__errorArgs = ['mail_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="mail_password" name="mail_password"
                                        placeholder="Enter SMTP password" type="password"
                                        value="<?php echo e(env('MAIL_PASSWORD')); ?>">
                                </div>
                                <?php $__errorArgs = ['mail_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- From Address -->
                            <div class="col-12">
                                <label for="mail_from_address" class="form-label fw-bold">Mail From Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ri-at-line"></i></span>
                                    <input class="form-control border-start-0 <?php $__errorArgs = ['mail_from_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="mail_from_address" name="mail_from_address"
                                        placeholder="e.g. info@swapapp.com" type="email"
                                        value="<?php echo e(env('MAIL_FROM_ADDRESS')); ?>">
                                </div>
                                <?php $__errorArgs = ['mail_from_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <!-- Action -->
                            <div class="col-12 mt-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <button class="btn btn-primary btn-label waves-effect waves-light px-4" type="submit">
                                        <i class="ri-refresh-line label-icon align-middle fs-16 me-2"></i> Update SMTP Configuration
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-light-subtle p-3">
                    <p class="text-muted mb-0"><i class="ri-information-line align-middle me-1"></i> Changes will take effect immediately after saving.</p>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Herd\mastering_laravelRo\resources\views/backend/layout/settings/mail-settings.blade.php ENDPATH**/ ?>