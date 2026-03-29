@extends('backend.master')
@section('title', 'Spotlight & Boost Payments')

@push('styles-bottom')
<style>
    .card-title-highlight { font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .top-product-img { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; border: 2px solid #f1f1f1; transition: transform 0.2s; }
    .top-product-row:hover .top-product-img { transform: scale(1.1); }
    .top-product-row:hover { background-color: rgba(var(--vz-primary-rgb), 0.03) !important; cursor: pointer; }
    .expiry-alert-item { border-left: 3px solid #f06548; padding: 10px; margin-bottom: 8px; background: #fff5f5; border-radius: 4px; }
    
    /* Rank Badges */
    .rank-badge { width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: 700; font-size: 11px; }
    .rank-1 { background: linear-gradient(135deg, #f7b84b, #eeba3c); color: #fff; box-shadow: 0 2px 4px rgba(247, 184, 75, 0.4); }
    .rank-2 { background: linear-gradient(135deg, #adb5bd, #6c757d); color: #fff; box-shadow: 0 2px 4px rgba(173, 181, 189, 0.4); }
    .rank-3 { background: linear-gradient(135deg, #e39a71, #a85d32); color: #fff; box-shadow: 0 2px 4px rgba(227, 154, 113, 0.4); }
    .rank-other { background: #f3f3f9; color: #495057; border: 1px solid #e9ebec; }
</style>
@endpush

@section('content')

    {{-- PAGE-HEADER --}}
    <div class="page-header mb-4">
        <div>
            <h1 class="page-title text-primary fw-bold">Spotlight Dashboard</h1>
            <p class="text-muted mb-0">Overview of spotlight and boost payment analytics</p>
        </div>
        <div class="ms-auto pageheader-btn">
            <div class="d-flex align-items-center gap-2">
                <div class="vr mx-2"></div>
                <ol class="breadcrumb d-inline-flex mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Analytics</li>
                </ol>
            </div>
        </div>
    </div>
    {{-- PAGE-HEADER --}}

    {{-- STAT CARDS --}}
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6 col-6">
            <div class="card card-animate border-0 shadow-sm" style="border-left: 4px solid #0ab39c !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-bold text-muted mb-1 fs-11">Total Revenue</p>
                            <h3 class="fs-22 fw-black ff-secondary mb-0 text-success">
                                ${{ number_format($stats['total_revenue'], 2) }}
                            </h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-soft-success d-flex justify-content-center align-items-center">
                                <i class="ri-money-dollar-circle-fill fs-24 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-6">
            <div class="card card-animate border-0 shadow-sm" style="border-left: 4px solid #4b38b3 !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-bold text-muted mb-1 fs-11">Active Boosts</p>
                            <h3 class="fs-22 fw-black ff-secondary mb-0 text-primary">{{ $stats['active_boosts'] }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-soft-primary d-flex justify-content-center align-items-center">
                                <i class="ri-flashlight-fill fs-24 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-6">
            <div class="card card-animate border-0 shadow-sm" style="border-left: 4px solid #f7b84b !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-bold text-muted mb-1 fs-11">Pending Payments</p>
                            <h3 class="fs-22 fw-black ff-secondary mb-0 text-warning">{{ $stats['pending_count'] }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-soft-warning d-flex justify-content-center align-items-center">
                                <i class="ri-timer-2-fill fs-24 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-6">
            <div class="card card-animate border-0 shadow-sm" style="border-left: 4px solid #f06548 !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-bold text-muted mb-1 fs-11">Failed</p>
                            <h3 class="fs-22 fw-black ff-secondary mb-0 text-danger">{{ $stats['failed_count'] }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="avatar-sm rounded-circle bg-soft-danger d-flex justify-content-center align-items-center">
                                <i class="ri-error-warning-fill fs-24 text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END STAT CARDS --}}

    {{-- ADVANCED ANALYTICS ROW --}}
    <div class="row mb-4">
        <!-- Top Products -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header align-items-center d-flex bg-transparent border-bottom-dashed">
                    <h4 class="card-title mb-0 flex-grow-1 card-title-highlight text-primary">
                        <i class="ri-medal-line align-bottom me-1"></i> Top Products
                    </h4>
                </div>
                <div class="card-body p-0" style="height: 350px; overflow-y: auto;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="text-muted bg-soft-light" style="position: sticky; top: 0; z-index: 1;">
                                <tr class="fs-11">
                                    <th class="ps-3">Rank</th>
                                    <th>Product</th>
                                    <th class="text-end">Earn</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts->take(10) as $index => $item)
                                @php 
                                    $rank = $index + 1;
                                    $rankClass = $rank <= 3 ? "rank-$rank" : "rank-other";
                                    $img = $item->product->product_image ? asset('storage/'.$item->product->product_image) : asset('assets/images/no-image.png'); 
                                @endphp
                                <tr class="top-product-row">
                                    <td class="ps-3"><span class="rank-badge {{ $rankClass }}">{{ $rank }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $img }}" class="top-product-img me-2 shadow-sm" style="width:32px; height:32px;">
                                            <h6 class="fs-12 mb-0 fw-semibold text-dark text-truncate" style="max-width: 100px;">{{ $item->product->title }}</h6>
                                        </div>
                                    </td>
                                    <td class="text-end pe-3">
                                        <h6 class="text-success mb-0 fw-bold fs-12">${{ number_format($item->total_spent, 2) }}</h6>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-dashed py-2 text-center">
                    <a href="javascript:void(0);" class="link-primary fw-medium fs-11">View All <i class="ri-arrow-right-line align-middle"></i></a>
                </div>
            </div>
        </div>

        <!-- Compact City Analytics -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header align-items-center d-flex bg-transparent border-bottom-dashed">
                    <h4 class="card-title mb-0 flex-grow-1 card-title-highlight text-info">
                        <i class="ri-map-pin-line align-bottom me-1"></i> City Revenue
                    </h4>
                    <a href="{{ route('backend.spotlight.cities') }}" class="btn btn-soft-info btn-sm fs-10">Details</a>
                </div>
                <div class="card-body p-0" style="height: 350px; overflow-y: auto;">
                    <div id="city_revenue_chart" class="apex-charts" style="min-height: 220px;"></div>
                    <div class="px-3 pb-3">
                        <div class="p-2 bg-soft-info rounded border border-info border-opacity-10 text-center">
                            <h6 class="fs-12 mb-1 fw-bold text-dark">{{ $cityRevenue->first()->city ?? 'None' }}</h6>
                            <p class="text-muted mb-0 fs-11">Top City: <strong>{{ number_format($cityRevenue->first()->contribution ?? 0, 1) }}%</strong> contribution</p>
                        </div>
                        <div class="row g-2 mt-2">
                            <div class="col-6">
                                <div class="p-2 bg-light rounded text-center">
                                    <span class="text-muted d-block fs-10">Revenue</span>
                                    <span class="fw-bold fs-12 text-dark">${{ number_format($cityRevenue->first()->revenue ?? 0, 0) }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 bg-light rounded text-center">
                                    <span class="text-muted d-block fs-10">Boosts</span>
                                    <span class="fw-bold fs-12 text-dark">{{ $cityRevenue->first()->boost_count ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expiry Alerts -->
        <div class="col-xl-4 col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header align-items-center d-flex bg-transparent border-bottom-dashed">
                    <h4 class="card-title mb-0 flex-grow-1 card-title-highlight text-danger">
                        <i class="ri-notification-3-line align-bottom me-1"></i> Expiry
                    </h4>
                    <span class="badge bg-soft-danger text-danger fs-10">Next 24h</span>
                </div>
                <div class="card-body p-3" style="height: 350px; overflow-y: auto;">
                    @forelse($expiringSoon as $alert)
                        <div class="expiry-alert-item d-flex align-items-center border-0 bg-transparent mb-2 p-0">
                            <div class="avatar-xs flex-shrink-0 me-2">
                                <div class="avatar-title bg-soft-danger text-danger rounded fs-14">
                                    <i class="ri-time-line"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <h6 class="fs-12 mb-0 text-truncate fw-bold">{{ $alert->product->title }}</h6>
                                <p class="text-muted mb-0 fs-10">{{ $alert->spotlight_end_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex-shrink-0 ms-2">
                                <span class="badge bg-soft-warning text-warning fs-10">{{ $alert->spotlight_end_at->format('H:i') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <p class="text-muted fs-12">No alerts.</p>
                        </div>
                    @endforelse
                </div>
                <div class="card-footer bg-transparent py-2 border-top-dashed text-center">
                    <a href="javascript:void(0);" class="link-danger fw-medium fs-11">Bulk Notification <i class="ri-mail-send-line ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
    {{-- END ANALYTICS ROW --}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom-dashed d-flex align-items-center">
                    <h4 class="card-title mb-0 flex-grow-1">Payment History</h4>
                    <div class="ms-auto d-flex gap-1">
                        <div class="dropdown">
                            <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-download-2-line me-1"></i> Export
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('backend.spotlight.export.csv') }}"><i class="ri-file-text-line me-2 text-muted"></i> Export CSV</a></li>
                                <li><a class="dropdown-item" href="{{ route('backend.spotlight.export.excel') }}"><i class="ri-file-excel-2-line me-2 text-success"></i> Export Excel</a></li>
                                <li><a class="dropdown-item" href="{{ route('backend.spotlight.export.pdf') }}"><i class="ri-file-pdf-line me-2 text-danger"></i> Export PDF</a></li>
                                <li><a class="dropdown-item" href="{{ route('backend.spotlight.export.json') }}"><i class="ri-braces-line me-2 text-info"></i> Export JSON</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered text-nowrap border-bottom w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Product</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Boost Period</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Modal --}}
    <div class="modal fade" id="paymentDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Transaction Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="list-group list-group-flush border-dashed">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">User</span>
                            <span id="p_user" class="fw-medium"></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Product</span>
                            <span id="p_product" class="fw-medium"></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Amount</span>
                            <span id="p_amount" class="text-success fw-bold"></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Plan / Hours</span>
                            <span id="p_plan" class="badge bg-soft-info text-info"></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Stripe Intent ID</span>
                            <small id="p_stripe" class="text-muted"></small>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Start At</span>
                            <span id="p_start" class="small"></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">End At</span>
                            <span id="p_end" class="small"></span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Paid At</span>
                            <span id="p_paid" class="small"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-primary px-4" data-bs-dismiss="modal">Done</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts-bottom')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(document).ready(function () {
        // --- DataTable ---
        var table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('backend.spotlight.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'user', name: 'user' },
                { data: 'product', name: 'product' },
                { data: 'formatted_amount', name: 'total_fee' },
                { data: 'status_badge', name: 'status' },
                { data: 'period', name: 'period', orderable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });

        // --- City Revenue Chart ---
        var options = {
            series: {!! json_encode($cityRevenue->take(5)->pluck('revenue'), JSON_NUMERIC_CHECK) !!},
            labels: {!! json_encode($cityRevenue->take(5)->pluck('city')) !!},
            chart: { type: 'donut', height: 280 },
            plotOptions: {
                pie: {
                    donut: { size: '65%' }
                }
            },
            dataLabels: { enabled: false },
            legend: { position: 'bottom' },
            colors: ['#4b38b3', '#45cbff', '#0ab39c', '#f7b84b', '#f06548']
        };

        var chart = new ApexCharts(document.querySelector("#city_revenue_chart"), options);
        chart.render();

        // --- City Search Filter ---
        $('#citySearchInput').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $("#cityRevenueTable .city-row").filter(function() {
                $(this).toggle($(this).find('.city-name').text().toLowerCase().indexOf(value) > -1)
            });
        });

        window.viewPaymentDetail = function(id) {
            let url = "{{ route('backend.spotlight.show', ':id') }}".replace(':id', id);
            $.get(url, function(response) {
                if(response.success) {
                    const d = response.data;
                    $('#p_user').text(response.user_name);
                    $('#p_product').text(response.product_title);
                    $('#p_amount').text(response.formatted_amount);
                    $('#p_plan').text(`${d.boost_plan} (${d.boost_hours}h)`);
                    $('#p_stripe').text(d.stripe_payment_intent_id);
                    $('#p_start').text(response.dates.start);
                    $('#p_end').text(response.dates.end);
                    $('#p_paid').text(response.dates.pay);
                    $('#paymentDetailModal').modal('show');
                }
            });
        };
    });
</script>
@endpush
