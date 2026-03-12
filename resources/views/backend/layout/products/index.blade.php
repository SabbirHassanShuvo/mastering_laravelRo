@extends('backend.master')
@section('title', 'Products')

@push('styles-bottom')
    <style>
        .gallery-swiper {
            width: 100%;
            height: 180px; /* Increased height for better swipe view */
            border-radius: 6px;
            overflow: hidden;
            background: #f8f9fa;
        }

        .gallery-swiper .swiper-slide {
            background: #f3f3f3; /* Light grey background to fill space */
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gallery-swiper .swiper-slide img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain; /* Shows full image without cutting */
            cursor: pointer;
        }

        .gallery-swiper .swiper-button-next,
        .gallery-swiper .swiper-button-prev {
            color: #fff;
            background: rgba(0, 0, 0, 0.4);
            width: 28px;
            height: 28px;
            border-radius: 50%;
            &::after {
                font-size: 11px;
            }
        }

        .swiper-pagination-bullet-active {
            background: #4b38b3 !important; /* Theme primary color */
        }
        
        #modal_main_image {
            height: 300px;
            width: 100%;
            object-fit: contain; /* Shows full image */
            border-radius: 8px;
            background: #f9f9f9; /* Contrast for smaller images */
            border: 1px solid #eee;
        }
        
        .modal-body-content {
            border-left: 1px solid #eee;
            padding-left: 2rem;
        }

        @media (max-width: 768px) {
            .modal-body-content {
                border-left: none;
                padding-left: 0.75rem;
                margin-top: 1.5rem;
            }
            .swiper-container {
                height: 250px;
            }
        }
    </style>
@endpush

@section('content')

    {{-- PAGE-HEADER --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Products Inventory</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Products</li>
            </ol>
        </div>
    </div>
    {{-- PAGE-HEADER --}}

    {{-- STAT CARDS --}}
    <div class="row mb-3">
        <div class="col-xl col-md-4 col-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0 fs-12">Total</p>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                                <span class="counter-value" data-target="{{ $stats['total'] }}">{{ $stats['total'] }}</span>
                            </h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-sm rounded-circle bg-soft-primary d-flex justify-content-center align-items-center">
                                <i class="ri-store-2-line fs-20 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl col-md-4 col-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0 fs-12">Active</p>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-success">{{ $stats['active'] }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-sm rounded-circle bg-soft-success d-flex justify-content-center align-items-center">
                                <i class="ri-checkbox-circle-line fs-20 text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl col-md-4 col-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0 fs-12">Sold</p>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-info">{{ $stats['sold'] }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-sm rounded-circle bg-soft-info d-flex justify-content-center align-items-center">
                                <i class="ri-shopping-bag-line fs-20 text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl col-md-4 col-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0 fs-12">Expired</p>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-warning">{{ $stats['expired'] }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-sm rounded-circle bg-soft-warning d-flex justify-content-center align-items-center">
                                <i class="ri-time-line fs-20 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl col-md-4 col-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0 fs-12">Archived</p>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-secondary">{{ $stats['archived'] }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-sm rounded-circle bg-soft-secondary d-flex justify-content-center align-items-center">
                                <i class="ri-archive-line fs-20 text-secondary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl col-md-4 col-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-medium text-muted mb-0 fs-12">Spotlighted</p>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-0 text-danger">{{ $stats['spotlighted'] }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <div
                                class="avatar-sm rounded-circle bg-soft-danger d-flex justify-content-center align-items-center">
                                <i class="ri-flashlight-line fs-20 text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END STAT CARDS --}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-wrap align-items-center gap-2">

                        {{-- Status filter --}}
                        <select id="filterStatus" class="form-select form-select-sm" style="width:140px;">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="sold">Sold</option>
                            <option value="expired">Expired</option>
                            <option value="archived">Archived</option>
                        </select>

                        {{-- Type filter --}}
                        <select id="filterType" class="form-select form-select-sm" style="width:130px;">
                            <option value="">All Types</option>
                            <option value="paid">Paid</option>
                            <option value="free">Free</option>
                        </select>

                        {{-- Spotlight filter --}}
                        <select id="filterSpotlight" class="form-select form-select-sm" style="width:150px;">
                            <option value="">All Listings</option>
                            <option value="1">Spotlighted</option>
                            <option value="0">Not Spotlighted</option>
                        </select>

                        {{-- Urgent filter --}}
                        <select id="filterUrgent" class="form-select form-select-sm" style="width:130px;">
                            <option value="">All Urgency</option>
                            <option value="1">Urgent</option>
                            <option value="0">Not Urgent</option>
                        </select>

                        {{-- Reset filters --}}
                        <button id="resetFilters" class="btn btn-soft-secondary btn-sm">
                            <i class="ri-refresh-line me-1"></i>Reset
                        </button>

                        <div class="ms-auto d-flex gap-1">
                            <a href="{{ route('backend.products.create') }}" class="btn btn-primary btn-sm">
                                <i class="ri-add-line me-1"></i> Add Product
                            </a>
                            <div class="dropdown">
                                <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-download-2-line me-1"></i> Export
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('backend.products.export.csv') }}"><i class="ri-file-text-line me-2 text-muted"></i> Export CSV</a></li>
                                    <li><a class="dropdown-item" href="{{ route('backend.products.export.excel') }}"><i class="ri-file-excel-2-line me-2 text-success"></i> Export Excel</a></li>
                                    <li><a class="dropdown-item" href="{{ route('backend.products.export.pdf') }}"><i class="ri-file-pdf-line me-2 text-danger"></i> Export PDF</a></li>
                                    <li><a class="dropdown-item" href="{{ route('backend.products.export.json') }}"><i class="ri-braces-line me-2 text-info"></i> Export JSON</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered text-nowrap border-bottom w-100">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Owner</th>
                                    <th>Category</th>
                                    <th>Type / Price</th>
                                    <th>Status</th>
                                    <th>Spotlight</th>
                                    <th>Posted At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- filled via DataTable AJAX --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Product Detail Modal --}}
    <div class="modal fade" id="productDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <img id="modal_main_image" src="" alt="Product" class="shadow-sm mb-3">
                            
                            <div id="gallery_section" class="d-none">
                                <h6 class="fw-semibold mb-2 text-muted fs-12 uppercase">Gallery</h6>
                                <!-- Swiper -->
                                <div class="swiper-container gallery-swiper mySwiper position-relative">
                                    <div class="swiper-wrapper" id="modal_swiper_wrapper">
                                        <!-- Slides via JS -->
                                    </div>
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-pagination"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 modal-body-content">
                            <h4 id="modal_title" class="fw-bold mb-3 text-primary"></h4>
                            <div class="table-responsive">
                                <table class="table table-sm table-borderless align-middle">
                                    <tr>
                                        <td class="fw-bold text-muted" style="width:120px;">Category</td>
                                        <td>: <span id="modal_category" class="badge bg-soft-info text-info fs-12"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted">Owner</td>
                                        <td>: <span id="modal_owner" class="text-dark fw-medium"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted">Price</td>
                                        <td>: <span id="modal_price" class="fw-bold text-danger fs-16"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted">Status</td>
                                        <td>: <span id="modal_status" class="fs-12"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted">Condition</td>
                                        <td>: <span id="modal_condition" class="text-dark"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted">Location</td>
                                        <td>: <span id="modal_location" class="text-dark"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted">Loves</td>
                                        <td>: <span id="modal_loves" class="badge bg-soft-danger text-danger"></span></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-muted">Short Dates</td>
                                        <td>: <span class="text-muted fs-11" title="Posted">P: <span
                                                    id="modal_posted"></span></span> <br>
                                            &nbsp;&nbsp;<span class="text-muted fs-11" title="Expires">E: <span
                                                    id="modal_expires"></span></span>
                                        </td>
                                    </tr>
                                    <tr id="badges_row">
                                        <td></td>
                                        <td>
                                            <span id="modal_urgent_badge" class="badge bg-danger d-none">Urgent</span>
                                            <span id="modal_spotlight_badge"
                                                class="badge bg-warning text-dark d-none">Spotlighted</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <hr class="my-3 text-muted opacity-25">
                            <h6 class="fw-semibold mb-2">Description</h6>
                            <div id="modal_description" class="text-muted small lh-base"
                                style="max-height: 150px; overflow-y: auto;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-soft-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success px-4" data-bs-dismiss="modal">Done</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts-bottom')
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // ─── Initialise DataTable ───────────────────────────────────────────
            var table = $('#datatable').DataTable({
                order: [
                    [8, 'desc']
                ],
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All']
                ],
                processing: true,
                responsive: true,
                serverSide: true,
                language: {
                    processing: `<div class="text-center">
                    <div class="spinner-border text-primary" style="width:3rem;height:3rem;" role="status">
                    <span class="visually-hidden">Loading...</span></div></div>`
                },
                pagingType: 'full_numbers',
                dom: "<'row justify-content-between table-topbar'<'col-md-2 col-sm-4 px-0'l><'col-md-2 col-sm-4 px-0'f>>tipr",
                ajax: {
                    url: '{{ route('backend.products.index') }}',
                    type: 'GET',
                    data: function(d) {
                        d.status = $('#filterStatus').val();
                        d.type = $('#filterType').val();
                        d.spotlight = $('#filterSpotlight').val();
                        d.urgent = $('#filterUrgent').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'image',
                        name: 'product_image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title',
                        searchable: true
                    },
                    {
                        data: 'owner',
                        name: 'owner',
                        searchable: true
                    },
                    {
                        data: 'category',
                        name: 'category',
                        searchable: true
                    },
                    {
                        data: 'type_price',
                        name: 'product_type',
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: false
                    },
                    {
                        data: 'spotlight',
                        name: 'is_spotlighted',
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'posted_at',
                        name: 'posted_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // ─── Re-draw on filter change ───────────────────────────────────────
            $('#filterStatus, #filterType, #filterSpotlight, #filterUrgent').on('change', function() {
                table.ajax.reload();
            });

            $('#resetFilters').on('click', function() {
                $('#filterStatus, #filterType, #filterSpotlight, #filterUrgent').val('');
                table.ajax.reload();
            });

            // ─── Delete confirm ─────────────────────────────────────────────────
            window.showDeleteConfirm = function(id) {
                event.preventDefault();
                Swal.fire({
                    title: 'Delete this product?',
                    text: 'This will permanently delete the product and all its photos.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                }).then(result => {
                    if (result.isConfirmed) deleteItem(id);
                });
            };

            function deleteItem(id) {
                let url = '{{ route('backend.products.destroy', ':id') }}'.replace(':id', id);
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.ajax.reload(null, false);
                        response.success ?
                            toastr.success(response.message) :
                            toastr.error(response.message ?? 'Delete failed');
                    },
                    error: function() {
                        toastr.error('An error occurred. Please try again.');
                    }
                });
            }

            // ─── Status change confirm ──────────────────────────────────────────
            window.showStatusChangeAlert = function(id, currentStatus) {
                event.preventDefault();
                Swal.fire({
                    title: 'Change Status?',
                    text: 'Update status for this product?',
                    icon: 'info',
                    input: 'select',
                    inputOptions: {
                        active: 'Active',
                        sold: 'Sold',
                        expired: 'Expired',
                        archived: 'Archived',
                    },
                    inputValue: currentStatus,
                    showCancelButton: true,
                    confirmButtonText: 'Update',
                }).then(result => {
                    if (result.isConfirmed) changeStatus(id, result.value);
                });
            };

            function changeStatus(id, status) {
                let url = '{{ route('backend.products.status', ':id') }}'.replace(':id', id);
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        _token: '{{ csrf_token() }}',
                        status: status
                    },
                    success: function(response) {
                        table.ajax.reload(null, false);
                        response.success ?
                            toastr.success(response.message) :
                            toastr.error(response.message ?? 'Failed to update status');
                    },
                    error: function() {
                        toastr.error('Server error.');
                    }
                });
            }

            // ─── View details in Modal ──────────────────────────────────────────
            let detailSwiper = null;

            window.viewProduct = function(id) {
                let url = '{{ route('backend.products.show', ':id') }}'.replace(':id', id);

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            const p = response.data;

                            // Populate modal fields
                            $('#modal_title').text(p.title);
                            $('#modal_category').text(response.category_name);
                            $('#modal_owner').text(response.owner_name);
                            $('#modal_price').text(response.formatted_price);
                            $('#modal_posted').text(response.posted_date);
                            $('#modal_expires').text(response.expires_date);
                            $('#modal_condition').text(response.condition);
                            $('#modal_location').text(response.location);
                            $('#modal_loves').text(response.loves_count);
                            $('#modal_description').text(p.description || 'No description provided.');
                            $('#modal_main_image').attr('src', response.image_path);

                            // Populate Swiper Slides for Gallery
                            let slidesHtml = '';
                            if (response.gallery && response.gallery.length > 0) {
                                $('#gallery_section').removeClass('d-none');
                                response.gallery.forEach(img => {
                                    slidesHtml += `
                                        <div class="swiper-slide">
                                            <img src="${img}" alt="Gallery" onclick="window.open('${img}', '_blank')">
                                        </div>`;
                                });
                                $('#modal_swiper_wrapper').html(slidesHtml);
                            } else {
                                $('#gallery_section').addClass('d-none');
                                $('#modal_swiper_wrapper').html('');
                            }

                            // Badges
                            response.is_urgent ? $('#modal_urgent_badge').removeClass('d-none') : $(
                                '#modal_urgent_badge').addClass('d-none');
                            response.is_spotlighted ? $('#modal_spotlight_badge').removeClass(
                                'd-none') : $('#modal_spotlight_badge').addClass('d-none');

                            // Status badge logic
                            let statusMap = {
                                active: 'success',
                                sold: 'info',
                                expired: 'warning',
                                archived: 'secondary'
                            };
                            let color = statusMap[p.status] || 'dark';
                            $('#modal_status').html(
                                `<span class="badge bg-${color}">${p.status.toUpperCase()}</span>`
                            );

                            // Show the modal
                            $('#productDetailModal').modal('show');

                            // Initialize or update Swiper after modal is visible
                            $('#productDetailModal').off('shown.bs.modal').on('shown.bs.modal', function() {
                                if (detailSwiper) {
                                    detailSwiper.destroy();
                                }
                                if (slidesHtml !== '') {
                                    detailSwiper = new Swiper(".mySwiper", {
                                        slidesPerView: 1, // Full width for better "swiper way"
                                        spaceBetween: 10,
                                        loop: response.gallery.length > 1,
                                        navigation: {
                                            nextEl: ".swiper-button-next",
                                            prevEl: ".swiper-button-prev",
                                        },
                                        pagination: {
                                            el: ".swiper-pagination",
                                            clickable: true,
                                            dynamicBullets: true,
                                        },
                                        observer: true,
                                        observeParents: true,
                                    });
                                }
                            });
                        }
                    },
                    error: function() {
                        toastr.error('Failed to load product details.');
                    }
                });
            };
        });
    </script>
@endpush
