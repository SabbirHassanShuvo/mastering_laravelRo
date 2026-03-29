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
                            @forelse($topSpotlightCities as $city)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded p-1 me-2">
                                            <div class="avatar-title bg-soft-warning text-warning rounded fs-13">
                                                <i class="bx bxs-star"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h5 class="fs-14 my-1 fw-medium">{{ $city->pickup_location }}</h5>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-soft-warning fs-12">{{ $city->total }} Boosts</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="text-center text-muted py-4">No data available today</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> 