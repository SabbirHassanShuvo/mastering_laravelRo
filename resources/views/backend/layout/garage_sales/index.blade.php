@extends('backend.master')
@section('title', 'Garage Sales Inventory')

@push('styles-bottom')
    <style>
        .card-title-highlight {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .top-sale-img {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #f1f1f1;
            transition: transform 0.2s;
        }

        .top-sale-row:hover .top-sale-img {
            transform: scale(1.1);
        }

        .top-sale-row:hover {
            background-color: rgba(var(--vz-primary-rgb), 0.03) !important;
            cursor: pointer;
        }

        .expiry-alert-item {
            border-left: 3px solid #f06548;
            padding: 10px;
            margin-bottom: 8px;
            background: #fff5f5;
            border-radius: 4px;
        }

        /* Rank Badges */
        .rank-badge {
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: 700;
            font-size: 11px;
        }

        .rank-1 {
            background: linear-gradient(135deg, #f7b84b, #eeba3c);
            color: #fff;
            box-shadow: 0 2px 4px rgba(247, 184, 75, 0.4);
        }

        .rank-2 {
            background: linear-gradient(135deg, #adb5bd, #6c757d);
            color: #fff;
            box-shadow: 0 2px 4px rgba(173, 181, 189, 0.4);
        }

        .rank-3 {
            background: linear-gradient(135deg, #e39a71, #a85d32);
            color: #fff;
            box-shadow: 0 2px 4px rgba(227, 154, 113, 0.4);
        }

        .rank-other {
            background: #f3f3f9;
            color: #495057;
            border: 1px solid #e9ebec;
        }

        /* Media Swiper for Modal */
        .gallery-swiper {
            width: 100%;
            height: 200px;
            border-radius: 8px;
            background: #f8f9fa;
        }

        .gallery-swiper .swiper-slide {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gallery-swiper .swiper-slide img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            cursor: pointer;
        }

        #modal_main_image {
            height: 280px;
            width: 100%;
            object-fit: contain;
            border-radius: 8px;
            background: #f9f9f9;
            border: 1px solid #eee;
        }

        .modal-body-content {
            border-left: 1px solid #eee;
            padding-left: 2rem;
        }

        @media (max-width: 768px) {
            .modal-body-content {
                border-left: none;
                padding-left: 0;
                margin-top: 1.5rem;
            }
        }
    </style>
@endpush

@section('content')

    {{-- PAGE-HEADER --}}
    <div class="page-header mb-4">
        <div>
            <h1 class="page-title text-primary fw-bold">Garage Sales Directory</h1>
            <p class="text-muted mb-0">Manage hosted garage sale events and revenue.</p>
        </div>
        <div class="ms-auto pageheader-btn">
            <div class="d-flex align-items-center gap-2">
                <ol class="breadcrumb d-inline-flex mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Garage Sales</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate border-0 shadow-sm" style="border-left: 4px solid #0ab39c !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-bold text-muted mb-1 fs-11">Total Sales</p>
                            <h3 class="fs-22 fw-black ff-secondary mb-0 text-success">{{ number_format($stats['total']) }}
                            </h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-sm rounded-circle bg-soft-success d-flex justify-content-center align-items-center">
                                <i class="ri-home-gear-line fs-24 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate border-0 shadow-sm" style="border-left: 4px solid #4b38b3 !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-bold text-muted mb-1 fs-11">Active Now</p>
                            <h3 class="fs-22 fw-black ff-secondary mb-0 text-primary">{{ number_format($stats['active']) }}
                            </h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-sm rounded-circle bg-soft-primary d-flex justify-content-center align-items-center">
                                <i class="ri-flashlight-fill fs-24 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate border-0 shadow-sm" style="border-left: 4px solid #f7b84b !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-bold text-muted mb-1 fs-11">Total Revenue</p>
                            <h3 class="fs-22 fw-black ff-secondary mb-0 text-warning">
                                ${{ number_format($stats['total_revenue'], 2) }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-sm rounded-circle bg-soft-warning d-flex justify-content-center align-items-center">
                                <i class="ri-money-dollar-circle-fill fs-24 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate border-0 shadow-sm" style="border-left: 4px solid #f06548 !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-bold text-muted mb-1 fs-11">Pending Fees</p>
                            <h3 class="fs-22 fw-black ff-secondary mb-0 text-danger">
                                ${{ number_format($stats['pending_revenue'], 2) }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-sm rounded-circle bg-soft-danger d-flex justify-content-center align-items-center">
                                <i class="ri-error-warning-fill fs-24 text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ANALYTICS ROW --}}
    <div class="row mb-4">
        <!-- Top Sales -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header align-items-center d-flex bg-transparent border-bottom-dashed">
                    <h4 class="card-title mb-0 flex-grow-1 card-title-highlight text-primary">
                        <i class="ri-medal-line align-bottom me-1"></i> Top Performing
                    </h4>
                </div>
                <div class="card-body p-0" style="height: 350px;">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle table-nowrap mb-0">
                            <thead class="text-muted bg-soft-light tr-fs-11">
                                <tr>
                                    <th class="ps-3">Rank</th>
                                    <th>Event</th>
                                    <th class="text-end">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topSales as $index => $sale)
                                    @php
                                        $rank = $index + 1;
                                        $rankClass = $rank <= 3 ? "rank-$rank" : 'rank-other';
                                    @endphp
                                    <tr class="top-sale-row">
                                        <td class="ps-3"><span
                                                class="rank-badge {{ $rankClass }}">{{ $rank }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <h6 class="fs-12 mb-0 fw-semibold text-dark text-truncate"
                                                    style="max-width: 150px;">{{ $sale->event_title }}</h6>
                                            </div>
                                        </td>
                                        <td class="text-end pe-3">
                                            <h6 class="text-success mb-0 fw-bold fs-12">
                                                ${{ number_format($sale->total_fee, 2) }}</h6>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- City Revenue (Spotlight Design) -->
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header align-items-center d-flex bg-transparent border-bottom-dashed">
                    <h4 class="card-title mb-0 flex-grow-1 fs-13 fw-bold text-primary">
                        <i class="ri-map-pin-line align-bottom me-1 text-info"></i> CITY REVENUE
                    </h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('backend.garage.analytics') }}" class="btn btn-soft-info btn-sm fs-11 px-2 py-0"
                            style="height: 24px; line-height: 24px;">Details</a>
                    </div>
                </div>
                <div class="card-body p-3 d-flex flex-column justify-content-center" style="height: 350px;">
                    @if (isset($topCity) && $topCity)
                        <div class="top-city-banner text-center py-3 px-2 mb-4"
                            style="background: rgba(41, 156, 219, 0.1); border: 1px solid rgba(41, 156, 219, 0.2); border-radius: 8px;">
                            <h5 class="mb-1 fw-bold text-dark">{{ $topCity->pickup_location }}</h5>
                            <p class="text-muted mb-0 fs-12">Top City: <span
                                    class="fw-bold text-primary">{{ number_format($topCity->contribution, 1) }}%</span>
                                contribution</p>
                        </div>

                        <div class="row g-2">
                            <div class="col-4">
                                <div class="p-2 text-center rounded bg-light bg-opacity-50 border border-dashed">
                                    <p class="text-muted mb-0 fs-10 text-uppercase fw-medium">Revenue</p>
                                    <h5 class="mb-0 fw-bold text-dark">${{ number_format($topCity->revenue, 0) }}</h5>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 text-center rounded bg-light bg-opacity-50 border border-dashed">
                                    <p class="text-muted mb-0 fs-10 text-uppercase fw-medium">Users</p>
                                    <h5 class="mb-0 fw-bold text-dark">{{ $topCity->user_count }}</h5>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 text-center rounded bg-light bg-opacity-50 border border-dashed">
                                    <p class="text-muted mb-0 fs-10 text-uppercase fw-medium">Posts</p>
                                    <h5 class="mb-0 fw-bold text-dark">{{ $topCity->post_count }}</h5>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ri-map-pin-user-line fs-48 text-light mb-2"></i>
                            <p class="text-muted fs-12">No city data available yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Expiry Alerts -->
        <div class="col-xl-4 col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header align-items-center d-flex bg-transparent border-bottom-dashed">
                    <h4 class="card-title mb-0 flex-grow-1 card-title-highlight text-danger">
                        <i class="ri-notification-3-line align-bottom me-1"></i> Ending Soon
                    </h4>
                    <span class="badge bg-soft-danger text-danger fs-10">Next 48h</span>
                </div>
                <div class="card-body p-3" style="height: 350px; overflow-y: auto;">
                    @forelse($expiringSoon as $sale)
                        <div class="expiry-alert-item d-flex align-items-center border-0 bg-transparent mb-2 p-0">
                            <div class="avatar-xs flex-shrink-0 me-2">
                                <div class="avatar-title bg-soft-danger text-danger rounded fs-14">
                                    <i class="ri-time-line"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <h6 class="fs-12 mb-0 text-truncate fw-bold">{{ $sale->event_title }}</h6>
                                <p class="text-muted mb-0 fs-10">
                                    {{ \Carbon\Carbon::parse($sale->sale_end_date)->diffForHumans() }}</p>
                            </div>
                            <div class="flex-shrink-0 ms-2">
                                <span
                                    class="badge bg-soft-warning text-warning fs-10">{{ \Carbon\Carbon::parse($sale->sale_end_date)->format('H:i') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <p class="text-muted fs-12">No urgent alerts.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN DIRECTORY TABLE --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-bottom d-flex align-items-center bg-transparent">
                    <h5 class="card-title mb-0 fw-bold flex-grow-1">Event Directory</h5>
                    <div class="flex-shrink-0 d-flex gap-2">
                        <a href="{{ route('backend.garage.create') }}"
                            class="btn btn-primary btn-sm d-flex align-items-center">
                            <i class="ri-add-line me-1"></i> Add Event
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-soft-success btn-sm dropdown-toggle d-flex align-items-center"
                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ri-file-download-line me-1"></i> Export
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><a class="dropdown-item fs-12" href="{{ route('backend.garage.export.csv') }}"><i
                                            class="ri-file-list-3-line me-2 align-middle text-muted"></i>CSV Report</a>
                                </li>
                                <li><a class="dropdown-item fs-12" href="javascript:void(0);"
                                        onclick="exportData('excel')"><i
                                            class="ri-file-excel-2-line me-2 align-middle text-muted"></i>Excel Sheets</a>
                                </li>
                                <li><a class="dropdown-item fs-12" href="javascript:void(0);"
                                        onclick="exportData('pdf')"><i
                                            class="ri-file-pdf-line me-2 align-middle text-muted"></i>PDF Document</a></li>
                            </ul>
                        </div>
                        <select id="filterStatus" class="form-select form-select-sm" style="width: 130px;">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="sold">Sold</option>
                            <option value="expired">Expired</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered text-nowrap border-bottom w-100 align-middle">
                            <thead class="bg-light fs-11 text-uppercase text-muted">
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Owner</th>
                                    <th>Period</th>
                                    <th>Revenue</th>
                                    <th>Status</th>
                                    <th>Payment</th>
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

    <style>
        /* Premium Modal Styles */
        #garageSaleModal .modal-content {
            border-radius: 1.25rem;
            overflow: hidden;
            background: #fff;
        }
        #garageSaleModal .modal-header {
            background: linear-gradient(135deg, #405189 0%, #0ab39c 100%);
            padding: 1.5rem 2rem;
        }
        #garageSaleModal .modal-title {
            color: #fff;
            letter-spacing: 0.5px;
            font-size: 1.25rem;
        }
        #garageSaleModal .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }
        
        /* Simplified Summary Card */
        .summary-card-premium {
            background: #f8f9fa;
            border: 1px solid #eff2f7;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }
        
        /* Item Section Premium */
        .item-section-premium {
            border: 1px solid #eff2f7;
            border-radius: 1rem;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            background: #fff;
        }
        .item-section-premium:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            border-color: rgba(10, 179, 156, 0.3);
        }
        
        .price-tag-premium {
            background: #e1f5f2;
            color: #0ab39c;
            border: 1px solid #0ab39c20;
            border-radius: 0.75rem;
            padding: 0.5rem 1rem;
            font-weight: 700;
        }
        
        /* Swiper Customization */
        .premium-swiper .swiper-button-next, 
        .premium-swiper .swiper-button-prev {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 50%;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            color: #405189;
        }
        .premium-swiper .swiper-button-next:after, 
        .premium-swiper .swiper-button-prev:after {
            font-size: 14px;
            font-weight: bold;
        }
        .premium-swiper .swiper-pagination-bullet-active {
            background: #0ab39c;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease backwards;
        }
    </style>

    {{-- MODAL: DETAILED VIEW (Matching Product Detail) --}}
    <div class="modal fade" id="garageSaleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="ri-information-line me-2"></i>Garage Sale Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="summary-card-premium animate-fade-in-up mb-4">
                        <div class="card-body p-4">
                            <div class="row align-items-center g-4">
                                <div class="col-md-8 border-end border-light">
                                    <h3 id="modal_title" class="fw-bold text-dark mb-2"></h3>
                                    <div class="d-flex flex-wrap align-items-center gap-4 fs-13">
                                        <span class="d-flex align-items-center"><i class="ri-user-star-line me-2 text-primary fs-16"></i> <span id="modal_owner" class="text-dark fw-medium"></span></span>
                                        <span class="d-flex align-items-center"><i class="ri-map-pin-2-line me-2 text-danger fs-16"></i> <span id="modal_location" class="text-dark"></span></span>
                                        <a href="" id="modal_email" class="btn btn-link text-primary p-0 fs-13 fw-semibold"><i class="ri-mail-send-line me-1"></i> Contact Owner</a>
                                    </div>
                                </div>
                                <div class="col-md-4 text-md-end">
                                    <div class="d-flex flex-column align-items-md-end gap-2 text-md-end">
                                        <div id="modal_status"></div>
                                        <div id="modal_payment_status"></div>
                                        <div class="text-success fs-24 fw-bold mt-1" id="modal_revenue" style="letter-spacing: -0.5px;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-top border-light">
                                <div class="row g-4">
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-auto rounded-3 bg-soft-info p-2 me-3">
                                                <i class="ri-calendar-todo-line text-info fs-20"></i>
                                            </div>
                                            <div>
                                                <p class="text-muted mb-0 fs-11 text-uppercase fw-bold ls-1">Sale Schedule</p>
                                                <p class="mb-0 fs-14 fw-medium"><span id="modal_start"></span> <span class="text-muted mx-1">/</span> <span id="modal_end"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-start">
                                            <div class="avatar-auto rounded-3 bg-soft-warning p-2 me-3">
                                                <i class="ri-file-list-3-line text-warning fs-20"></i>
                                            </div>
                                            <div>
                                                <p class="text-muted mb-0 fs-11 text-uppercase fw-bold ls-1">Event Description</p>
                                                <p class="mb-0 fs-13 text-muted lh-base" id="modal_description"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold text-uppercase fs-11 text-muted mb-3 letter-spacing-05 d-flex align-items-center">
                        <i class="ri-survey-line me-2 text-primary fs-14"></i> Inventory Items & Gallery
                    </h6>
                    
                    <div id="items_container" class="vstack gap-3">
                        <!-- Dynamic Sectional Items Rendered Here -->
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">

                    <button type="button" class="btn btn-soft-secondary px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts-bottom')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        $(document).ready(function() {
            // --- DataTable ---
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('backend.garage.index') }}",
                    data: function(d) {
                        d.status = $('#filterStatus').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'event_title',
                        name: 'event_title'
                    },
                    {
                        data: 'owner',
                        name: 'owner'
                    },
                    {
                        data: 'period',
                        name: 'period',
                        orderable: false
                    },
                    {
                        data: 'revenue',
                        name: 'total_fee'
                    },
                    {
                        data: 'status_badge',
                        name: 'status'
                    },
                    {
                        data: 'payment_badge',
                        name: 'payment_status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#filterStatus').on('change', function() {
                table.ajax.reload();
            });


            // --- View Modal Logic ---
            let detailSwiper = null;

            window.viewGarageSale = function(id) {
                $.get("{{ url('backend/garage-sales') }}/" + id, function(res) {
                    if (res.success) {
                        const s = res.data;
                        $('#modal_title').text(s.event_title);
                        $('#modal_owner').text(res.owner.name);
                        $('#modal_email').text(res.owner.email).attr('href', 'mailto:' + res.owner
                            .email);
                        $('#modal_phone').text(res.owner.phone);
                        $('#modal_revenue').text('$' + parseFloat(s.total_fee).toFixed(2));
                        $('#modal_start').text(res.dates.start);
                        $('#modal_end').text(res.dates.end);
                        $('#modal_location').text(s.pickup_location);
                        $('#modal_description').text(s.description || 'No description provided.');

                        // Inventory Gallery Rendering (Sectional)
                        let itemsHtml = '';
                        let itemSwipers = [];

                        if (s.items && s.items.length > 0) {
                            s.items.forEach((item, idx) => {
                                let slides = '';
                                if (item.images && item.images.length > 0) {
                                    item.images.forEach(img => {
                                        let imgUrl = "{{ asset('storage') }}/" + img.photo;
                                        slides += `<div class="swiper-slide"><img src="${imgUrl}" class="rounded border" style="width:100%; height:280px; object-fit:contain; background:#f9f9f9;"></div>`;
                                    });
                                } else {
                                    slides = `<div class="swiper-slide"><img src="{{ asset('assets/images/no-image.png') }}" class="rounded border" style="width:100%; height:280px; object-fit:contain; background:#f9f9f9;"></div>`;
                                }

                                const swiperId = `swiper_${item.id}_${idx}`;
                                itemSwipers.push(swiperId);

                                itemsHtml += `
                                    <div class="item-section-premium animate-fade-in-up overflow-hidden" style="animation-delay: ${idx * 0.1}s">
                                        <div class="row g-0">
                                            <div class="col-md-5 p-3">
                                                <div class="swiper swiper-container premium-swiper ${swiperId}" style="height:300px; border-radius: 0.75rem;">
                                                    <div class="swiper-wrapper">${slides}</div>
                                                    <div class="swiper-button-next"></div>
                                                    <div class="swiper-button-prev"></div>
                                                    <div class="swiper-pagination"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="card-body h-100 d-flex flex-column p-4">
                                                    <div class="mb-auto">
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            <h4 class="fw-bold text-dark mb-0">${item.title}</h4>
                                                            <div class="price-tag-premium">$${parseFloat(item.price).toFixed(2)}</div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <span class="text-muted fs-11 text-uppercase fw-bold ls-1 d-block mb-1">Item Details</span>
                                                            <p class="text-muted fs-14 mb-0 lh-base">${item.description || 'No specific description for this item.'}</p>
                                                        </div>
                                                    </div>
                                                    <div class="mt-4 pt-3 border-top border-light d-flex align-items-center justify-content-between">
                                                        <span class="text-muted fs-11 text-uppercase fw-bold ls-1">Item Reference #${idx + 1}</span>
                                                        <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill fs-11">Inventory Item</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                            });
                        }

                        $('#items_container').html(itemsHtml || '<div class="text-center py-5 bg-light rounded border border-light"><i class="ri-inbox-line fs-24 text-muted mb-2 d-block"></i><p class="text-muted mb-0">No inventory items found.</p></div>');
                        $('#garageSaleModal').modal('show');

                        // Badges Logic
                        let statusColor = { active: 'success', sold: 'info', expired: 'warning', archived: 'secondary' }[s.status] || 'dark';
                        $('#modal_status').html(`<span class="badge bg-soft-${statusColor} text-${statusColor} fs-11">${s.status.toUpperCase()}</span>`);
                        let payColor = s.payment_status === 'completed' ? 'success' : 'warning';
                        $('#modal_payment_status').html(`<span class="badge bg-${payColor} fs-11">${s.payment_status.toUpperCase()}</span>`);

                        // Initialize Swipers
                        $('#garageSaleModal').off('shown.bs.modal').on('shown.bs.modal', function() {
                            itemSwipers.forEach(id => {
                                new Swiper(`.${id}`, {
                                    slidesPerView: 1,
                                    spaceBetween: 10,
                                    navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
                                    pagination: { el: ".swiper-pagination", clickable: true },
                                    observer: true,
                                    observeParents: true
                                });
                            });
                        });
                    }
                });
            };

            // --- SweetAlerts ---
            window.showDeleteConfirm = function(id) {
                Swal.fire({
                    title: 'Delete this event?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#f06548',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ url('backend/garage-sales') }}/" + id,
                            type: 'DELETE',
                            data: {
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(res) {
                                table.ajax.reload();
                                if (res.success) toastr.success(res.message);
                                else toastr.error(res.message);
                            }
                        });
                    }
                });
            };

            window.archiveSale = function(id) {
                Swal.fire({
                    title: 'Archive this event?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Archive'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post("{{ url('backend/garage-sales') }}/" + id + "/archive", {
                            _token: "{{ csrf_token() }}"
                        }, function(res) {
                            table.ajax.reload();
                            toastr.success(res.message);
                        });
                    }
                });
            }

            window.exportData = function(format) {
                let url = "";
                if (format === 'csv') url = "{{ route('backend.garage.export.csv') }}";
                else if (format === 'excel') url = "{{ route('backend.garage.export.excel') }}";
                else if (format === 'pdf') url = "{{ route('backend.garage.export.pdf') }}";

                if (url) window.location.href = url;
            };
        });
    </script>
@endpush
