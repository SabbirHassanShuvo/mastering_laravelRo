<?php $__env->startSection('title', 'Pickup Requests'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Pickup Requests</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Messaging</a></li>
                        <li class="breadcrumb-item active">Pickups</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Pickup Scheduling Overview</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product</th>
                                    <th>Requester</th>
                                    <th>Date/Time</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $pickups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pickup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($pickup->id); ?></td>
                                    <td><?php echo e($pickup->product->title ?? 'N/A'); ?></td>
                                    <td><?php echo e($pickup->requester->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($pickup->pickup_date); ?> <?php echo e($pickup->pickup_time); ?></td>
                                    <td><?php echo e(Str::limit($pickup->location, 30)); ?></td>
                                    <td>
                                        <?php if($pickup->status == 'pending'): ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php elseif($pickup->status == 'accepted'): ?>
                                            <span class="badge bg-success">Accepted</span>
                                        <?php elseif($pickup->status == 'rejected'): ?>
                                            <span class="badge bg-danger">Rejected</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?php echo e($pickup->status); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($pickup->created_at->format('d M, Y')); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-soft-primary">View</button>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <?php echo e($pickups->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Downloads\Sabbir\mastering_laravelRo\resources\views/backend/messaging/pickups.blade.php ENDPATH**/ ?>