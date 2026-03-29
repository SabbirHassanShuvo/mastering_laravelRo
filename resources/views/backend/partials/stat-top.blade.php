<div class="row">
    <!-- Total Users -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate bg-primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-white-50 text-truncate mb-0"> Total Users</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-white"><span class="counter-value" data-target="{{ $totalUsers }}">0</span></h4>
                        <span class="text-white-50">{{ $verifiedUsers }} Verified</span>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-white-50 rounded fs-3">
                            <i class="bx bx-group text-white"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate bg-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-white-50 text-truncate mb-0"> Total Revenue</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-white">$<span class="counter-value" data-target="{{ round($totalRevenue, 2) }}">0</span></h4>
                        <span class="text-white-50">Combined Earnings</span>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-white-50 rounded fs-3">
                            <i class="bx bx-dollar-circle text-white"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Products -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate bg-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-white-50 text-truncate mb-0"> Active Products</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-white"><span class="counter-value" data-target="{{ $totalProducts }}">0</span></h4>
                        <span class="text-white-50">{{ $spotlightedProducts }} Spotlighted</span>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-white-50 rounded fs-3">
                            <i class="bx bx-package text-white"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Garage Sales -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate bg-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-white-50 text-truncate mb-0"> Garage Sales</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-white"><span class="counter-value" data-target="{{ $totalGarageSales }}">0</span></h4>
                        <span class="text-white-50">Active Events</span>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-white-50 rounded fs-3">
                            <i class="bx bx-store-alt text-white"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Total Matches -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Matches</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $totalMatches }}">0</span></h4>
                        <span class="text-muted">User connections</span>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-primary rounded fs-3">
                            <i class="bx bx-git-merge text-primary"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Completed Pickups -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Match Conversion</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $matchConversionRate }}">0</span>%</h4>
                        <span class="text-muted">{{ $completedPickups }} Completed Pickups</span>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-success rounded fs-3">
                            <i class="bx bx-check-double text-success"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Messages -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Total Messages</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $totalMessages }}">0</span></h4>
                        <span class="text-muted">Chat interactions</span>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-info rounded fs-3">
                            <i class="bx bx-message-square-dots text-info"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports -->
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0"> Reports</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $totalReports }}">0</span></h4>
                        <span class="text-muted">Needs Attention</span>
                    </div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-soft-danger rounded fs-3">
                            <i class="bx bx-error text-danger"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>