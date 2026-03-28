<?php $__env->startSection('title', 'Payment Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center py-4">
    <div class="col-xxl-9">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary p-3 d-flex align-items-center">
                <h4 class="card-title mb-0 flex-grow-1 text-white">
                    <i class="ri-secure-payment-line align-middle me-1"></i> Payment Gateway Configuration
                </h4>
            </div>
            <div class="card-body p-0">
                <div class="row g-0">
                    <!-- Tabs Navigation -->
                    <div class="col-md-3 border-end">
                        <div class="nav flex-column nav-pills nav-pills-custom p-3" id="settingsTabs" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active mb-2 text-start p-3" id="stripe-tab" data-bs-toggle="tab"
                                    data-bs-target="#stripe" type="button" role="tab"
                                    data-uri="<?php echo e(route('backend.settings.payments.stripe.update')); ?>">
                                <i class="ri-stripe-line align-middle me-2 fs-18"></i> Stripe
                            </button>
                            <button class="nav-link mb-2 text-start p-3" id="sslcommerz-tab" data-bs-toggle="tab"
                                    data-bs-target="#sslcommerz" type="button" role="tab"
                                    data-uri="<?php echo e(route('backend.settings.payments.stripe.test')); ?>">
                                <i class="ri-bank-card-line align-middle me-2 fs-18"></i> SSL COMMERZ
                            </button>
                            <button class="nav-link text-start p-3" id="other-tab" data-bs-toggle="tab"
                                    data-bs-target="#other" type="button" role="tab"
                                    data-uri="<?php echo e(route('backend.settings.payments.stripe.test')); ?>">
                                <i class="ri-more-2-line align-middle me-2 fs-18"></i> Other Settings
                            </button>
                        </div>
                    </div>

                    <!-- Tabs Content -->
                    <div class="col-md-9 p-4">
                        <div class="tab-content" id="settingsTabsContent">
                            <!-- Stripe Tab -->
                            <div class="tab-pane fade show active" id="stripe" role="tabpanel">
                                <div class="mb-4">
                                    <h5 class="fw-semibold">Stripe Configuration</h5>
                                    <p class="text-muted fs-13">Configure your Stripe API keys for processing credit card payments.</p>
                                </div>
                                <form class="ajax-form">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Stripe Key</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="ri-key-line"></i></span>
                                            <input type="text" name="stripe_key" class="form-control"
                                                   value="<?php echo e(env('STRIPE_KEY')); ?>" placeholder="pk_test_...">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Stripe Secret</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="ri-lock-line"></i></span>
                                            <input type="password" name="stripe_secret" class="form-control"
                                                   value="<?php echo e(env('STRIPE_SECRET')); ?>" placeholder="sk_test_...">
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Stripe Webhook Secret</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="ri-webhook-line"></i></span>
                                            <input type="password" name="stripe_websocket_secret" class="form-control"
                                                   value="<?php echo e(env('STRIPE_WEBHOOK_SECRET')); ?>" placeholder="whsec_...">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 py-2">
                                        <i class="ri-save-line align-middle me-1"></i> Save Stripe Settings
                                    </button>
                                </form>
                            </div>

                            <!-- SSL Commerz Tab -->
                            <div class="tab-pane fade" id="sslcommerz" role="tabpanel">
                                <div class="mb-4">
                                    <h5 class="fw-semibold">SSL Commerz Configuration</h5>
                                    <p class="text-muted fs-13">Bangladesh's leading payment gateway settings.</p>
                                </div>
                                <form class="ajax-form">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Store ID</label>
                                        <input type="text" name="mail_host" class="form-control"
                                               value="<?php echo e(env('MAIL_HOST')); ?>" placeholder="Enter Store ID">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Store Password</label>
                                        <input type="password" name="mail_username" class="form-control"
                                               value="<?php echo e(env('MAIL_USERNAME')); ?>" placeholder="Enter Store Password">
                                    </div>
                                    <button type="submit" class="btn btn-success w-100 py-2">
                                        <i class="ri-save-line align-middle me-1"></i> Save SSL Commerz
                                    </button>
                                </form>
                            </div>

                            <!-- Other Tab -->
                            <div class="tab-pane fade" id="other" role="tabpanel">
                                <div class="mb-4">
                                    <h5 class="fw-semibold">Miscellaneous Settings</h5>
                                    <p class="text-muted fs-13">General application and metadata configuration.</p>
                                </div>
                                <form class="ajax-form">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">App Name</label>
                                        <input type="text" name="app_name" class="form-control"
                                               value="<?php echo e(config('app.name')); ?>" placeholder="SwapApp">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">App URL</label>
                                        <input type="text" name="app_url" class="form-control"
                                               value="<?php echo e(config('app.url')); ?>" placeholder="https://swapapp.com">
                                    </div>
                                    <button type="submit" class="btn btn-warning w-100 py-2 text-dark fw-bold">
                                        <i class="ri-save-line align-middle me-1"></i> Save Other Settings
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Response Footer -->
            <div class="card-footer bg-dark border-0 p-3 mt-n1 rounded-bottom">
                <div class="d-flex align-items-center mb-2">
                    <div class="flex-grow-1">
                        <h6 class="text-white-50 mb-0 fs-12 uppercase tracking-wider"><i class="ri-terminal-box-line me-1"></i> System Response Log</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge bg-success-subtle text-success fs-10">Live Connection</span>
                    </div>
                </div>
                <pre id="responseBox" class="bg-black text-info border-0 rounded p-3 mb-0 fs-12 shadow-inner" style="min-height: 100px; max-height: 200px; overflow-y: auto;">Waiting for action...</pre>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style-bottom'); ?>
<style>
    .nav-pills-custom .nav-link {
        border-radius: 0;
        color: #495057;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .nav-pills-custom .nav-link.active {
        background-color: var(--vz-primary-bg-subtle) !important;
        color: var(--vz-primary) !important;
        border-right: 3px solid var(--vz-primary);
    }
    .nav-pills-custom .nav-link:hover:not(.active) {
        background-color: #f3f6f9;
        color: var(--vz-primary);
    }
    #responseBox {
        font-family: 'JetBrains Mono', 'Courier New', Courier, monospace;
        line-height: 1.6;
    }
    #responseBox::-webkit-scrollbar {
        width: 6px;
    }
    #responseBox::-webkit-scrollbar-thumb {
        background: #333;
        border-radius: 10px;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts-bottom'); ?>
    <script>
        $(function () {
            // ===== Remember Active Tab =====
            const activeTabKey = 'activePaymentSettingsTab';

            // On tab change — save ID
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                localStorage.setItem(activeTabKey, $(e.target).attr('id'));
            });

            // On page load — restore last active tab
            const lastTab = localStorage.getItem(activeTabKey);
            if (lastTab) {
                const trigger = document.getElementById(lastTab);
                if (trigger) {
                    const tab = new bootstrap.Tab(trigger);
                    tab.show();
                }
            }

            // ===== AJAX Form Submission =====
            $('.ajax-form').on('submit', function (e) {
                e.preventDefault();

                const $form = $(this);
                const activeTab = $('#settingsTabs .nav-link.active');
                const $url = activeTab.data('uri');
                const $btn = $form.find('button[type="submit"]');
                const originalText = $btn.html();
                
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Processing...');
                $('#responseBox').text('> Requesting ' + $url + '...\n> sending data payload...');

                const csrf = '<?php echo e(csrf_token()); ?>';

                $.ajax({
                    url: $url,
                    method: 'PUT',
                    data: $form.serialize(),
                    headers: { 'X-CSRF-TOKEN': csrf },
                    success: function (res) {
                        $('#responseBox').html('<span class="text-success">[SUCCESS]</span> ' + JSON.stringify(res, null, 2));
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Settings updated successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        setTimeout(() => {
                            location.reload();
                        }, 1200);
                    },
                    error: function (xhr) {
                        $btn.prop('disabled', false).html(originalText);
                        $('#responseBox').html('<span class="text-danger">[ERROR]</span> ' + xhr.responseText);
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Failed',
                            text: 'Something went wrong while saving settings.'
                        });
                    }
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Herd\mastering_laravelRo\resources\views/backend/layout/settings/payments-settings.blade.php ENDPATH**/ ?>