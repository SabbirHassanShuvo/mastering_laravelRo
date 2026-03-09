<?php $__env->startSection('title', 'Create Garage Sale'); ?>

<?php $__env->startPush('styles-bottom'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .form-label { font-weight: 600; color: #344767; }
        .item-row { position: relative; padding-right: 40px; }
        .remove-item { position: absolute; right: 0; top: 32px; color: #ff3d57; cursor: pointer; }
        .card-header { background: transparent; border-bottom: 1px solid #f0f2f5; }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <div class="page-header">
        <div>
            <h1 class="page-title">Host a Garage Sale</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('backend.garage.index')); ?>">Garage Sales</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </div>
    </div>

    <form action="<?php echo e(route('backend.garage.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-3">
                        <h5 class="mb-0 fw-bold">Event Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Event Title <span class="text-danger">*</span></label>
                                <input type="text" name="event_title" class="form-control" placeholder="e.g. Big Spring Garage Cleanup" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Select Owner <span class="text-danger">*</span></label>
                                <select name="user_id" class="form-select select2" required>
                                    <option value="">Select User</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php 
                                            $isMe = $user->id == auth()->id();
                                        ?>
                                        <option value="<?php echo e($user->id); ?>" <?php echo e($isMe ? 'selected' : ''); ?>>
                                            <?php echo e($user->name); ?> <?php echo e($isMe ? '(Me - Administrator)' : ''); ?> (<?php echo e($user->email); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Pickup Location <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ri-map-pin-line"></i></span>
                                    <input type="text" name="pickup_location" class="form-control" placeholder="City, State, ZIP" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Tell visitors what to expect..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DYNAMIC INVENTORY ITEMS -->
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header py-3 d-flex align-items-center">
                        <h5 class="mb-0 fw-bold flex-grow-1">Inventory Management</h5>
                        <button type="button" id="addItem" class="btn btn-soft-primary btn-sm">
                            <i class="ri-add-line me-1"></i>Add Item
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="itemsContainer">
                            <div class="row g-3 item-row mb-3 border-bottom pb-3">
                                <div class="col-md-7">
                                    <label class="form-label">Item Name <span class="text-danger">*</span></label>
                                    <input type="text" name="items[0][title]" class="form-control" placeholder="Item name" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Price ($) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="items[0][price]" class="form-control" placeholder="0.00" required>
                                </div>
                                <div class="col-12 mt-2">
                                    <textarea name="items[0][description]" class="form-control form-control-sm" rows="1" placeholder="Short description (optional)"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-3">
                        <h5 class="mb-0 fw-bold">Scheduling</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Main Event Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sale Starts At <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="sale_start_date" class="form-control" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Sale Ends At <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="sale_end_date" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-body p-4 text-center">
                        <p class="text-muted small mb-4">You are creating this event as an administrator. It will be marked as "Active" immediately.</p>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg shadow-primary">
                                <i class="ri-rocket-line me-1"></i> Launch Event
                            </button>
                            <a href="<?php echo e(route('backend.garage.index')); ?>" class="btn btn-soft-secondary">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts-bottom'); ?>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            let itemIndex = 1;
            $('#addItem').on('click', function() {
                const html = `
                    <div class="row g-3 item-row mb-3 border-bottom pb-3 mt-1" id="item_${itemIndex}">
                        <div class="col-md-7">
                            <label class="form-label">Item Name <span class="text-danger">*</span></label>
                            <input type="text" name="items[${itemIndex}][title]" class="form-control" placeholder="Item name" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Price ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="items[${itemIndex}][price]" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-12 mt-2">
                            <textarea name="items[${itemIndex}][description]" class="form-control form-control-sm" rows="1" placeholder="Short description (optional)"></textarea>
                        </div>
                        <i class="ri-delete-bin-line remove-item" onclick="removeItemRow(${itemIndex})"></i>
                    </div>
                `;
                $('#itemsContainer').append(html);
                itemIndex++;
            });

            window.removeItemRow = function(index) {
                $(`#item_${index}`).fadeOut(300, function() {
                    $(this).remove();
                });
            };
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Downloads\Sabbir\mastering_laravelRo\resources\views/backend/layout/garage_sales/create.blade.php ENDPATH**/ ?>