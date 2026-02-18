<?php $__env->startSection('content'); ?>

    
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h1 class="page-title">Create Form</h1>
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
            <a href="<?php echo e(route('backend.page.index')); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="mdi mdi-arrow-left"></i> Back
            </a>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dynamic page</li>
            </ol>
        </div>
    </div>
    


    <div class="row">
        <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
            <div class="card box-shadow-0">
                <div class="card-body">
                    <form action="<?php echo e(@$page ? route('backend.page.update', @$page->id) : route('backend.page.store')); ?>"
                        method="POST">
                        <?php echo csrf_field(); ?>
                        <?php if(@$page): ?>
                            <?php echo method_field('PATCH'); ?>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="page_title" class="form-label">Page Title</label>
                            <input type="text" name="page_title" id="page_title" class="form-control"
                                value="<?php echo e(old('page_title', @$page->page_title)); ?>" placeholder="page_title">
                            <?php $__errorArgs = ['page_title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="mb-3">
                            <label for="page_content" class="form-label">Page Content</label>
                            <textarea name="page_content" id="ckeditor-classic" class="form-control" rows="5"
                                placeholder="Enter content..."><?php echo e(old('page_content', @$page->page_content)); ?></textarea>
                            <?php $__errorArgs = ['page_content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts-top'); ?>
<!-- ✅ CKEditor 5 Classic Editor from CDN -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor
            .create(document.querySelector('#ckeditor-classic'))
            .catch(error => {
                console.error(error);
            });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Downloads\Sabbir\mastering_laravelRo\resources\views/backend/layout/pages/form.blade.php ENDPATH**/ ?>