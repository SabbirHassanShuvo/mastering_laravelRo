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
            @foreach($recentActivities as $activity)
            <div class="acitivity-item d-flex">
                <div class="flex-shrink-0">
                    <div class="avatar-xs acitivity-avatar">
                        <div class="avatar-title rounded-circle bg-soft-{{ $activity['color'] }} text-{{ $activity['color'] }}">
                            <i class="{{ $activity['icon'] }}"></i>
                        </div>
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="mb-1">{{ $activity['title'] }}</h6>
                    <p class="text-muted mb-2">{{ $activity['type'] }}</p>
                    <small class="mb-0 text-muted">{{ $activity['time']->diffForHumans() }}</small>
                </div>
            </div>
            @endforeach
        </div>
    </div><!-- end card body -->
</div><!-- end card -->
