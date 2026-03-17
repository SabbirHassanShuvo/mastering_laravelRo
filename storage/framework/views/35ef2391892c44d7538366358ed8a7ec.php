

<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Revenue</h4>
                <div>
                    <button type="button" class="btn btn-soft-secondary btn-sm chart-switcher" data-chart-type="area">
                        AREA
                    </button>
                    <button type="button" class="btn btn-soft-secondary btn-sm chart-switcher" data-chart-type="mixed">
                        MIXED
                    </button>
                    <div class="btn-group ms-2">
                        <button type="button" class="btn btn-soft-primary btn-sm time-filter" data-filter="all">
                            ALL
                        </button>
                        <button type="button" class="btn btn-soft-secondary btn-sm time-filter" data-filter="1M">
                            1M
                        </button>
                        <button type="button" class="btn btn-soft-secondary btn-sm time-filter" data-filter="6M">
                            6M
                        </button>
                        <button type="button" class="btn btn-soft-secondary btn-sm time-filter" data-filter="1Y">
                            1Y
                        </button>
                        <button type="button" class="btn btn-soft-secondary btn-sm time-filter" data-filter="custom" id="customFilterBtn">
                            CUSTOM
                        </button>
                    </div>
                </div>
            </div><!-- end card header -->

            <!-- Custom Date Range Modal -->
            <div class="modal fade" id="customDateModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Select Custom Date Range</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="startDate" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="startDate">
                                </div>
                                <div class="col-md-6">
                                    <label for="endDate" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="endDate">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="applyCustomDate">Apply</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-header p-0 border-0 bg-soft-light">
                <div class="row g-0 text-center">
                    <div class="col-6">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1"><span class="counter-value" data-target="<?php echo e($totalOrders); ?>">0</span></h5>
                            <p class="text-muted mb-0">Total Paid Actions</p>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-6">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1">$<span class="counter-value" data-target="<?php echo e(round($totalRevenue, 2)); ?>">0</span></h5>
                            <p class="text-muted mb-0">Total Revenue</p>
                        </div>
                    </div>
                    <!--end col-->
                </div>
            </div>
            <!-- end card header -->

            <div class="card-body p-0 pb-2">
                <div class="w-100">
                    <div id="customer_impression_charts" data-colors='["--vz-primary", "--vz-success", "--vz-danger"]' class="apex-charts" dir="ltr"></div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->

    <div class="col-xl-4">
        <!-- card -->
        <div class="card card-height-100 card-animate">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Top Garage Sale Cities</h4>
                <div class="flex-shrink-0">
                    <button type="button" class="btn btn-soft-primary btn-sm">
                        Export Report
                    </button>
                </div>
            </div>
            <!-- end card header -->
            <!-- card body -->
             <div class="card-body">
                <div class="table-responsive table-card">
                    <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                        <thead class="text-muted table-light">
                            <tr>
                                <th>City</th>
                                <th>Total Events</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $topGarageCities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded p-1 me-2">
                                            <div class="avatar-title bg-soft-success text-success rounded fs-13">
                                                <i class="bx bx-map"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h5 class="fs-14 my-1 fw-medium"><?php echo e($city->pickup_location); ?></h5>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-soft-success fs-12"><?php echo e($city->total); ?> Events</span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="2" class="text-center text-muted py-4">No data available today</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div> <!-- end row --><?php /**PATH C:\Users\88013\Downloads\Sabbir\mastering_laravelRo\resources\views/backend/partials/charts/sales-months.blade.php ENDPATH**/ ?>