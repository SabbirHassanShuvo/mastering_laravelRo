<?php $__env->startSection('title', 'Edit Garage Sale'); ?>

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
            <h1 class="page-title">Edit Garage Sale</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('backend.garage.index')); ?>">Garage Sales</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </div>
    </div>

    <form action="<?php echo e(route('backend.garage.update', $sale->id)); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
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
                                <input type="text" name="event_title" class="form-control" value="<?php echo e(old('event_title', $sale->event_title)); ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Select Owner <span class="text-danger">*</span></label>
                                <select name="user_id" class="form-select select2" required>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($user->id); ?>" <?php echo e($user->id == old('user_id', $sale->user_id) ? 'selected' : ''); ?>>
                                            <?php echo e($user->name); ?> (<?php echo e($user->email); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Pickup Location <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ri-map-pin-line"></i></span>
                                    <input type="text" name="pickup_location" class="form-control" value="<?php echo e(old('pickup_location', $sale->pickup_location)); ?>" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3"><?php echo e(old('description', $sale->description)); ?></textarea>
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
                            <?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="row g-3 item-row mb-3 border-bottom pb-3 mt-1" id="item_<?php echo e($index); ?>">
                                    <div class="col-md-7">
                                        <label class="form-label">Item Name <span class="text-danger">*</span></label>
                                        <input type="text" name="items[<?php echo e($index); ?>][title]" class="form-control" value="<?php echo e($item->title); ?>" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Price ($) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="items[<?php echo e($index); ?>][price]" class="form-control" value="<?php echo e($item->price); ?>" required>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <textarea name="items[<?php echo e($index); ?>][description]" class="form-control form-control-sm" rows="1"><?php echo e($item->description); ?></textarea>
                                    </div>
                                    
                                    <div class="col-12 mt-2">
                                        <label class="form-label small">Add More Images</label>
                                        <input type="file" name="items[<?php echo e($index); ?>][images][]" class="form-control form-control-sm" multiple accept="image/*">
                                        
                                        <?php if($item->images->count() > 0): ?>
                                            <div class="d-flex flex-wrap gap-2 mt-2">
                                                <?php $__currentLoopData = $item->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="position-relative">
                                                        <img src="<?php echo e(asset('storage/' . $img->photo)); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if($index > 0): ?>
                                        <i class="ri-delete-bin-line remove-item" onclick="removeItemRow(<?php echo e($index); ?>)"></i>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-3">
                        <h5 class="mb-0 fw-bold">Scheduling & Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="active" <?php echo e($sale->status == 'active' ? 'selected' : ''); ?>>Active</option>
                                <option value="expired" <?php echo e($sale->status == 'expired' ? 'selected' : ''); ?>>Expired</option>
                                <option value="sold" <?php echo e($sale->status == 'sold' ? 'selected' : ''); ?>>Sold</option>
                                <option value="archived" <?php echo e($sale->status == 'archived' ? 'selected' : ''); ?>>Archived</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Main Event Date <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control" value="<?php echo e($sale->date ? $sale->date->format('Y-m-d') : ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sale Starts At <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="sale_start_date" class="form-control" value="<?php echo e($sale->sale_start_date ? $sale->sale_start_date->format('Y-m-d\TH:i') : ''); ?>" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Sale Ends At <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="sale_end_date" class="form-control" value="<?php echo e($sale->sale_end_date ? $sale->sale_end_date->format('Y-m-d\TH:i') : ''); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-body p-4 text-center">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg shadow-warning">
                                <i class="ri-save-line me-1"></i> Update Event
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

            let itemIndex = <?php echo e($sale->items->count()); ?>;
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
                        <div class="col-12 mt-2 mb-3">
                            <label class="form-label small">Item Images (Max 5)</label>
                            <input type="file" name="items[${itemIndex}][images][]" class="form-control form-control-sm" multiple accept="image/*">
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

<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Downloads\Sabbir\mastering_laravelRo\resources\views/backend/layout/garage_sales/edit.blade.php ENDPATH**/ ?>