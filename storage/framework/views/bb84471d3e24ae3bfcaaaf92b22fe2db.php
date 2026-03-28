<?php $__env->startSection('title', 'All Conversations'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">All Conversations</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Messaging</a></li>
                        <li class="breadcrumb-item active">Conversations</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Conversation History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product</th>
                                    <th>Initiator</th>
                                    <th>Receiver</th>
                                    <th>Messages</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($conv->id); ?></td>
                                    <td><?php echo e($conv->product->title ?? 'N/A'); ?></td>
                                    <td><?php echo e($conv->userOne->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($conv->userTwo->name ?? 'N/A'); ?></td>
                                    <td><span class="badge badge-soft-info"><?php echo e($conv->messages_count); ?></span></td>
                                    <td>
                                        <span class="badge <?php echo e($conv->status == 'active' ? 'bg-success' : 'bg-secondary'); ?>">
                                            <?php echo e(ucfirst($conv->status)); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($conv->created_at->format('d M, Y H:i')); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-soft-primary">View Details</button>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <?php echo e($conversations->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Herd\mastering_laravelRo\resources\views/backend/messaging/conversations.blade.php ENDPATH**/ ?>