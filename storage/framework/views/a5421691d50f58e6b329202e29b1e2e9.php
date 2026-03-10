<?php $__env->startSection('title', 'Garage Sales Analytics'); ?>

<?php $__env->startPush('styles-bottom'); ?>
    <style>
        .card-animate {
            transition: all 0.3s ease-out;
        }

        .card-animate:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        }

        .stat-card {
            border-radius: 12px;
            border: none;
            overflow: hidden;
        }

        .stat-card::after {
            content: "";
            position: absolute;
            right: -20px;
            bottom: -20px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .progress-sm {
            height: 6px;
        }

        .chart-container {
            min-height: 380px;
        }

        .city-row:hover {
            background-color: rgba(var(--vz-primary-rgb), 0.03) !important;
        }

        .badge-soft-primary {
            background: rgba(75, 56, 179, 0.1);
            color: #4b38b3;
        }

        .badge-soft-success {
            background: rgba(10, 179, 156, 0.1);
            color: #0ab39c;
        }

        .badge-soft-info {
            background: rgba(41, 156, 219, 0.1);
            color: #299cdb;
        }

        .glass-header {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="page-header mb-4">
        <div>
            <h1 class="page-title text-primary fw-bold">Revenue & City Insights</h1>
            <p class="text-muted mb-0">Deep dive into garage sale distributions and financial performance.</p>
        </div>
        <div class="ms-auto pageheader-btn">
            <div class="d-flex justify-content-between align-items-center gap-2">
                <ol class="breadcrumb d-inline-flex mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Garage Sales</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Analytics</li>
                </ol>
                <div class="vr mx-2"></div>
                <a href="<?php echo e(route('backend.garage.index')); ?>" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="ri-arrow-left-line me-1"></i> Back to Directory
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card card-animate bg-primary text-white shadow-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-white-50 text-uppercase fw-semibold mb-1 fs-12">Total Revenue</p>
                            <h2 class="text-white mb-0 fw-bold">$<?php echo e(number_format($totalRevenue, 2)); ?></h2>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-white bg-opacity-10 rounded fs-24">
                                <i class="ri-money-dollar-box-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card card-animate bg-success text-white shadow-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-white-50 text-uppercase fw-semibold mb-1 fs-12">Total Posts</p>
                            <h2 class="text-white mb-0 fw-bold"><?php echo e(number_format($totalSales)); ?></h2>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-white bg-opacity-10 rounded fs-24">
                                <i class="ri-layout-grid-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card card-animate bg-info text-white shadow-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-white-50 text-uppercase fw-semibold mb-1 fs-12">Top City: <?php echo e($topCity->city ?? 'N/A'); ?></p>
                            <h2 class="text-white mb-0 fw-bold">$<?php echo e(number_format($topCity->total_revenue ?? 0, 2)); ?></h2>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-white bg-opacity-10 rounded fs-24">
                                <i class="ri-trophy-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card card-animate bg-dark text-white shadow-dark">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-white-50 text-uppercase fw-semibold mb-1 fs-12">Total Registered Users</p>
                            <h2 class="text-white mb-0 fw-bold"><?php echo e(number_format($totalUsers)); ?></h2>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-white bg-opacity-10 rounded fs-24">
                                <i class="ri-user-follow-line"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom-dashed d-flex align-items-center flex-wrap gap-2">
                    <h5 class="card-title mb-0 fw-bold flex-grow-1">City Analytics Deep Dive</h5>
                    <div class="d-flex align-items-center gap-2">
                        <div class="search-box">
                            <input type="text" id="tableSearch" class="form-control form-control-sm"
                                placeholder="Search city...">
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-soft-success btn-sm dropdown-toggle d-flex align-items-center"
                                type="button" data-bs-toggle="dropdown">
                                <i class="ri-file-download-line me-1"></i> Export Data
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?php echo e(route('backend.garage.export.csv')); ?>">CSV Report</a>
                                </li>
                                <li><a class="dropdown-item" href="<?php echo e(route('backend.garage.export.excel')); ?>">Excel
                                        Spreadsheet</a></li>
                                <li><a class="dropdown-item" href="<?php echo e(route('backend.garage.export.pdf')); ?>">PDF
                                        Document</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="analyticsTable" class="table table-hover align-middle mb-0">
                            <thead class="text-muted bg-soft-light text-uppercase fs-11">
                                <tr>
                                    <th>Rank</th>
                                    <th>City Location</th>
                                    <th class="text-center">Total Users</th>
                                    <th class="text-center">Total Posts</th>
                                    <th class="text-end">Total Revenue</th>
                                    <th class="text-end">Rev / Post</th>
                                    <th class="text-end">Rev Share</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $cityAnalytics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="city-row">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="fw-bold text-muted me-2">#<?php echo e($index + 1); ?></span>
                                                <?php if($index == 0): ?>
                                                    <i class="ri-medal-fill text-warning fs-16"></i>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <h6 class="fs-14 mb-0 fw-bold text-dark"><?php echo e($item->city); ?></h6>
                                            <span class="text-muted fs-11">Primary Region</span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge badge-soft-warning"><?php echo e(number_format($item->user_count)); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge badge-soft-info"><?php echo e(number_format($item->post_count)); ?></span>
                                        </td>
                                        <td class="text-end fw-bold text-dark">
                                            $<?php echo e(number_format($item->total_revenue, 2)); ?>

                                        </td>
                                        <td class="text-end text-primary fw-medium">
                                            $<?php echo e(number_format($item->revenue_per_post, 2)); ?>

                                        </td>
                                        <td class="text-end">
                                            <div
                                                class="d-flex align-items-center justify-content-end gap-2 text-success fw-bold">
                                                <span><?php echo e(number_format($item->rev_contribution, 1)); ?>%</span>
                                                <i class="ri-donut-chart-line fs-14 pb-1"></i>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts-bottom'); ?>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        $(document).ready(function() {
            const cityData = <?php echo json_encode($cityAnalytics); ?>;

            if (cityData.length === 0) {
                $('.chart-container').html(
                    '<div class="d-flex align-items-center justify-content-center h-100"><p class="text-muted">No data available</p></div>'
                );
            }

            // Initialize DataTable for local filtering
            const table = $('#analyticsTable').DataTable({
                dom: 't<"d-flex align-items-center justify-content-between p-3"ip>',
                pageLength: 10,
                ordering: true,
                language: {
                    paginate: {
                        previous: '<i class="ri-arrow-left-s-line"></i>',
                        next: '<i class="ri-arrow-right-s-line"></i>'
                    }
                }
            });

            $('#tableSearch').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Downloads\Sabbir\mastering_laravelRo\resources\views/backend/layout/garage_sales/analytics.blade.php ENDPATH**/ ?>