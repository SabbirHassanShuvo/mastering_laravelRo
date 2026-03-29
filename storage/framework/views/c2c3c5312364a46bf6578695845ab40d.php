<div class="card card-height-100">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0 flex-grow-1">Recent Activities</h4>
        <div class="flex-shrink-0">
            <div class="dropdown card-header-dropdown">
                <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="text-muted">All <i class="mdi mdi-chevron-down ms-1"></i></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="#">View All</a>
                </div>
            </div>
        </div>
    </div><!-- end card header -->

    <div class="card-body">
        <div class="acitivity-timeline">
            <?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="acitivity-item d-flex">
                <div class="flex-shrink-0">
                    <div class="avatar-xs acitivity-avatar">
                        <div class="avatar-title rounded-circle bg-soft-<?php echo e($activity['color']); ?> text-<?php echo e($activity['color']); ?>">
                            <i class="<?php echo e($activity['icon']); ?>"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1"><?php echo e($activity['title']); ?></h6>
                    <p class="text-muted mb-2"><?php echo e($activity['type']); ?></p>
                    <small class="mb-0 text-muted"><?php echo e($activity['time']->diffForHumans()); ?></small>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div><!-- end card body -->
</div><!-- end card -->
<?php /**PATH C:\Users\88013\Herd\mastering_laravelRo\resources\views/backend/partials/activity-list.blade.php ENDPATH**/ ?>