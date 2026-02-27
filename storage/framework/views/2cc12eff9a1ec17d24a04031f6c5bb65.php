<?php $__env->startSection('title', 'Dashboard | faq form'); ?>

<?php $__env->startSection('content'); ?>

        <!-- start page title -->
        <div class="row">
                <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                        <h4 class="mb-sm-0">Create FAQ</h4>
                                        <a href="<?php echo e(route('backend.feature.faq.index')); ?>" class="btn btn-sm btn-outline-secondary">
                                                <i class="mdi mdi-arrow-left"></i> Back
                                        </a>
                                </div>

                                <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item"><a href="javascript:void(0);">FAQ</a></li>
                                                <li class="breadcrumb-item active">Create FAQ</li>
                                        </ol>
                                </div>
                        </div>
                </div>
        </div>
        <!-- end page title -->

        <form method="post" action="<?php echo e(@$faq ? route('backend.feature.faq.update', @$faq->id) : route('backend.feature.faq.store')); ?>"
                class="row">
                <?php echo csrf_field(); ?>
                <?php if(@$faq): ?>
                        <?php echo method_field('PATCH'); ?>
                <?php endif; ?>
                <div class="col-lg-8">
                        <div class="card">
                                <div class="card-body">
                                        <div class="row">
                                                <div class="col-lg-6">
                                                        <div class="mb-3">
                                                                <label class="form-label" for="project-title-input">FAQ
                                                                        Question</label>
                                                                <input type="text" name="question"
                                                                        value="<?php echo e(old('question', @$faq->question)); ?>"
                                                                        class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                        name="title" id="project-title-input"
                                                                        placeholder="Enter project title">
                                                                <?php $__errorArgs = ['title'];
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
                                                                <label for="choices-priority-input"
                                                                        class="form-label">Priority</label>
                                                                <input type="number" name="priority"
                                                                        value="<?php echo e(old('priority', @$faq->priority)); ?>"
                                                                        class="form-control <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                                <?php $__errorArgs = ['priority'];
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
                                        <div class="row">
                                                <div class="mb-3">
                                                        <label class="form-label">FAQ Answer</label>
                                                        <textarea name='answer' id="ckeditor-classic"><?php echo e(old('answer', @$faq->answer)); ?>

                                                                </textarea>
                                                        <?php $__errorArgs = ['answer'];
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
                                <!-- end card body -->
                        </div>
                        <!-- end card -->


                        <!-- end card -->
                        <div class="text-end mb-4">
                                <a href="<?php echo e(route('backend.feature.faq.index')); ?>" class="btn btn-danger w-sm">Cancel</a>
                                
                                <button type="submit" class="btn btn-success w-sm"><?php echo e(@$faq ? 'Update' : 'Create'); ?></button>
                        </div>
                </div>
                <!-- end col -->
                <div class="col-lg-4">
                        <div class="card">
                                <div class="card-header">
                                        <h5 class="card-title mb-0">Visibility</h5>
                                </div>
                                <div class="card-body">
                                        <div>
                                                <label for="choices-privacy-status-input" class="form-label">Status</label>
                                                <select name="status" class="form-select" data-choices data-choices-search-false
                                                        id="choices-privacy-status-input">
                                                        <option value="" disabled selected>Select Option</option>
                                                        <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($item); ?>" <?php echo e(old('status', $item) == @$faq->status ? 'selected' : ''); ?>><?php echo e($key); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                        </div>
                                </div>
                                <!-- end card body -->
                        </div>
                        <!-- end card -->
                        
                </div>
                <!-- end col -->
        </form>
        <!-- end row -->

<?php $__env->stopSection(); ?>
<?php $__env->startPush('style-bottom'); ?>
        <style>
                .dropify-wrapper .dropify-message p {
                        line-height: 2;
                        /* increase spacing */
                        font-size: 16px;
                        /* adjust font size if needed */
                        color: #555;
                        /* custom text color */
                }
        </style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts-top'); ?>

        <!-- ckeditor -->
        <script src="<?php echo e(asset('assets/libs/%40ckeditor/ckeditor5-build-classic/build/ckeditor.js')); ?>"></script>

        <!-- project-create init -->
        <script src="<?php echo e(asset('')); ?>assets/js/pages/project-create.init.js"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Downloads\Sabbir\mastering_laravelRo\resources\views/backend/layout/faqs/form.blade.php ENDPATH**/ ?>