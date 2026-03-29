<div class="row">
    <div class="col-xl-6">
        <div class="card card-height-100 card-animate">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Top Spotlighted Cities</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div class="table-responsive table-card">
                    <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                        <thead class="text-muted table-light">
                            <tr>
                                <th>City</th>
                                <th>Boosted Items</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $topSpotlightCities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded p-1 me-2">
                                            <div class="avatar-title bg-soft-warning text-warning rounded fs-13">
                                                <i class="bx bxs-star"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h5 class="fs-14 my-1 fw-medium"><?php echo e($city->pickup_location); ?></h5>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-soft-warning fs-12"><?php echo e($city->total); ?> Boosts</span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="2" class="text-center text-muted py-4">No data available today</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> <?php /**PATH C:\Users\88013\Herd\mastering_laravelRo\resources\views/backend/partials/chart-2.blade.php ENDPATH**/ ?>