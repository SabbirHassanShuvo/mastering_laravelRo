<?php $__env->startSection('title', 'Create Product'); ?>

<?php $__env->startPush('styles-bottom'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--bootstrap-5 .select2-selection {
            border: 1px solid #ced4da;
            height: calc(1.5em + 0.75rem + 2px);
        }
        .form-label { font-weight: 600; color: #344767; }
        .card-header { background: transparent; border-bottom: 1px solid #f0f2f5; }
        .dropify-wrapper { border-radius: 8px; }
        .gallery-preview-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .gallery-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #f0f2f5;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .gallery-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            border-color: #4b38b3;
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .gallery-remove {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 24px;
            height: 24px;
            background: rgba(255, 61, 87, 0.9);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            border: none;
            transition: scale 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        .gallery-remove:hover {
            scale: 1.1;
            background: #ff3d57;
        }
        .upload-placeholder {
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #f9fafb;
        }
        .upload-placeholder:hover {
            border-color: #4b38b3;
            background: #f3f4f6;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <div class="page-header">
        <div>
            <h1 class="page-title">Add New Listing</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('backend.products.index')); ?>">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </div>
    </div>

    <form id="productForm" action="<?php echo e(route('backend.products.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-3">
                        <h5 class="mb-0 fw-bold">Primary Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Product Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                       placeholder="e.g. Vintage Camera" value="<?php echo e(old('title')); ?>" required>
                                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Select Owner (User) <span class="text-danger">*</span></label>
                                <select name="user_id" class="form-select select2 <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="">Select User</option>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php 
                                            $isMe = $user->id == auth()->id();
                                            $selected = old('user_id') == $user->id || (!old('user_id') && $isMe);
                                        ?>
                                        <option value="<?php echo e($user->id); ?>" <?php echo e($selected ? 'selected' : ''); ?>>
                                            <?php echo e($user->name); ?> <?php echo e($isMe ? '(Me - Administrator)' : ''); ?> (<?php echo e($user->email); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select select2 <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="">Select Category</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cat->id); ?>" <?php echo e(old('category_id') == $cat->id ? 'selected' : ''); ?>>
                                            <?php echo e($cat->title); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="product_type" id="product_type" class="form-select <?php $__errorArgs = ['product_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                    <option value="paid" <?php echo e(old('product_type') == 'paid' ? 'selected' : ''); ?>>Paid</option>
                                    <option value="free" <?php echo e(old('product_type') == 'free' ? 'selected' : ''); ?>>Free Item</option>
                                </select>
                            </div>

                            <div class="col-md-4" id="price_container">
                                <label class="form-label">Price ($)</label>
                                <input type="number" step="0.01" name="price" class="form-control" 
                                       value="<?php echo e(old('price')); ?>" placeholder="0.00">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Condition</label>
                                <select name="condition_status" class="form-select">
                                    <option value="new" <?php echo e(old('condition_status') == 'new' ? 'selected' : ''); ?>>Brand New</option>
                                    <option value="like-new" <?php echo e(old('condition_status') == 'like-new' ? 'selected' : ''); ?>>Like New</option>
                                    <option value="good" <?php echo e(old('condition_status') == 'good' ? 'selected' : ''); ?>>Good Condition</option>
                                    <option value="fair" <?php echo e(old('condition_status') == 'fair' ? 'selected' : ''); ?>>Fair / Used</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4" 
                                          placeholder="Describe your product..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header py-3">
                        <h5 class="mb-0 fw-bold">Image Gallery</h5>
                    </div>
                    <div class="card-body">
                        <label class="form-label">Gallery Images</label>
                        <div class="upload-placeholder" onclick="document.getElementById('galleryInput').click()">
                            <i class="ri-image-add-line fs-32 text-muted"></i>
                            <div class="mt-2 text-dark fw-semibold">Click to upload gallery photos</div>
                            <div class="text-muted small">JPG, PNG or WebP (Max 2MB per image)</div>
                        </div>
                        <input type="file" name="gallery[]" class="d-none" multiple accept="image/*" id="galleryInput">
                        
                        <div class="gallery-preview-container" id="galleryPreview">
                            <!-- Preview items will be injected here -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header py-3">
                        <h5 class="mb-0 fw-bold">Main Cover</h5>
                    </div>
                    <div class="card-body">
                        <input type="file" name="product_image" class="dropify" data-height="250" 
                               accept="image/*" data-allowed-file-extensions="jpg jpeg png webp" />
                        <small class="text-muted mt-2 d-block">Display photo for listings.</small>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header py-3 bg-soft-danger">
                        <h5 class="mb-0 fw-bold text-danger"><i class="ri-flashlight-line me-1"></i> Urgency & Visibility</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="is_urgent" id="is_urgent" value="1">
                            <label class="form-check-label fw-bold" for="is_urgent">Mark as Urgent Listing</label>
                        </div>
                        
                        <div id="urgent_details" style="display: none;">
                            <div class="mb-3">
                                <label class="form-label">Urgent Pickup Date</label>
                                <input type="date" name="urgent_pickup_date" class="form-control">
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Urgent Notes</label>
                                <textarea name="urgent_pickup_notes" class="form-control" rows="2" placeholder="e.g. Must pick up by evening"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header py-3">
                        <h5 class="mb-0 fw-bold">Localization</h5>
                    </div>
                    <div class="card-body">
                        <label class="form-label">Pickup Location</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="ri-map-pin-line"></i></span>
                            <input type="text" name="pickup_location" class="form-control" 
                                   placeholder="e.g. New York, USA">
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-body p-4 text-center">
                        <p class="text-muted small mb-4">By creating this listing, it will be immediately visible as "Active" in the marketplace.</p>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg shadow-primary">
                                <i class="ri-save-line me-1"></i> Launch Product
                            </button>
                            <a href="<?php echo e(route('backend.products.index')); ?>" class="btn btn-soft-secondary">
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
    <script src="https://cdn.jsdelivr.net/npm/dropify@0.2.2/dist/js/dropify.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            $('.dropify').dropify();

            // Toggle price field based on product type
            $('#product_type').on('change', function() {
                if ($(this).val() === 'free') {
                    $('#price_container').fadeOut();
                } else {
                    $('#price_container').fadeIn();
                }
            });

            // Toggle Urgent Details
            $('#is_urgent').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#urgent_details').slideDown();
                } else {
                    $('#urgent_details').slideUp();
                }
            });

            // ─── Modern Gallery Logic ──────────────────────────────────────────
            let selectedFiles = [];
            const galleryInput = document.getElementById('galleryInput');
            const galleryPreview = document.getElementById('galleryPreview');

            $('#galleryInput').on('change', function(e) {
                const files = e.target.files;
                
                // Add new files to our array
                for (let i = 0; i < files.length; i++) {
                    selectedFiles.push(files[i]);
                }

                renderGallery();
                syncFiles();
            });

            function renderGallery() {
                galleryPreview.innerHTML = '';
                
                selectedFiles.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const html = `
                            <div class="gallery-item" data-index="${index}">
                                <button type="button" class="gallery-remove" onclick="removeGalleryImage(${index})">
                                    <i class="ri-close-line"></i>
                                </button>
                                <img src="${e.target.result}">
                            </div>
                        `;
                        galleryPreview.insertAdjacentHTML('beforeend', html);
                    };
                    reader.readAsDataURL(file);
                });
            }

            window.removeGalleryImage = function(index) {
                selectedFiles.splice(index, 1);
                renderGallery();
                syncFiles();
            };

            function syncFiles() {
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                galleryInput.files = dataTransfer.files;
            }
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Herd\mastering_laravelRo\resources\views/backend/layout/products/create.blade.php ENDPATH**/ ?>