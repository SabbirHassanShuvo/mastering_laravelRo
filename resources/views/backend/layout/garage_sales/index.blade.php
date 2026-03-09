@extends('backend.master')
@section('title', 'Garage Sales Management')

@section('content')

    <div class="page-header">
        <div>
            <h1 class="page-title">Garage Sales</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Garage Sales</li>
            </ol>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="row mb-3">
        <div class="col-xl-3 col-md-6 col-12">
            <div class="card bg-soft-primary border-start border-primary border-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-bold text-muted mb-0 fs-12">Total Events</p>
                            <h4 class="fs-24 fw-black text-primary mb-0">{{ $stats['total'] }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ri-home-gear-line fs-32 text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="card bg-soft-success border-start border-success border-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-bold text-muted mb-0 fs-12">Live Sales</p>
                            <h4 class="fs-24 fw-black text-success mb-0">{{ $stats['live'] }}</h4>
                        </div>
                        <div class="flex-shrink-0 position-relative">
                            <i class="ri-flashlight-line fs-32 text-success opacity-50"></i>
                            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle animate-pulse"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="card bg-soft-info border-start border-info border-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-bold text-muted mb-0 fs-12">Upcoming</p>
                            <h4 class="fs-24 fw-black text-info mb-0">{{ $stats['upcoming'] }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ri-calendar-event-line fs-32 text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-12">
            <div class="card bg-soft-warning border-start border-warning border-4 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-uppercase fw-bold text-muted mb-0 fs-12">Expired / Past</p>
                            <h4 class="fs-24 fw-black text-warning mb-0">{{ $stats['expired'] }}</h4>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ri-history-line fs-32 text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header py-3">
                    <div class="d-flex align-items-center gap-3">
                        <h5 class="mb-0 fw-bold flex-grow-1">Garage Event Directory</h5>
                        <select id="filterStatus" class="form-select form-select-sm w-auto">
                            <option value="">All Events</option>
                            <option value="active">Active</option>
                            <option value="expired">Expired</option>
                            <option value="sold">Sold</option>
                            <option value="archived">Archived</option>
                        </select>
                        <button id="resetFilters" class="btn btn-soft-secondary btn-sm me-1">
                            <i class="ri-refresh-line me-1"></i>Reset
                        </button>
                        <a href="{{ route('backend.garage.create') }}" class="btn btn-primary btn-sm">
                            <i class="ri-add-line me-1"></i>Add Event
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="garage_table" class="table table-bordered text-nowrap border-bottom w-100 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Event Title</th>
                                    <th>Owner</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Filled via DataTables --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Garage Sale Detail Modal --}}
    <div class="modal fade" id="garageDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="modal_event_title"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="text-muted fw-bold text-uppercase fs-11 ls-1 mb-2">Event Information</h6>
                            <div class="bg-light p-3 rounded-3">
                                <p class="mb-2"><strong>Owner:</strong> <span id="modal_owner"></span></p>
                                <p class="mb-2"><strong>Location:</strong> <span id="modal_location"></span></p>
                                <p class="mb-2"><strong>Starts:</strong> <span id="modal_start"></span></p>
                                <p class="mb-0"><strong>Ends:</strong> <span id="modal_end"></span></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted fw-bold text-uppercase fs-11 ls-1 mb-2">About the Sale</h6>
                            <p id="modal_description" class="text-muted small lh-base mb-0"></p>
                        </div>
                        <div class="col-12">
                            <h6 class="text-muted fw-bold text-uppercase fs-11 ls-1 mb-2">Inventory Items</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Price</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody id="modal_inventory_items">
                                        <!-- Items via JS -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts-bottom')
    <script>
        $(document).ready(function() {
            var table = $('#garage_table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: '{{ route('backend.garage.index') }}',
                    data: function(d) {
                        d.status = $('#filterStatus').val();
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'event_title', name: 'event_title'},
                    {data: 'owner', name: 'owner'},
                    {data: 'date', name: 'date'},
                    {data: 'items_count', name: 'items_count', searchable: false},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                language: {
                    paginate: {
                        next: '<i class="ri-arrow-right-s-line"></i>',
                        previous: '<i class="ri-arrow-left-s-line"></i>'
                    }
                }
            });

            $('#filterStatus').on('change', function() { table.ajax.reload(); });
            $('#resetFilters').on('click', function() {
                $('#filterStatus').val('');
                table.ajax.reload();
            });

            // ─── View Modal logic ──────────────────────────────────────────
            window.viewGarageSale = function(id) {
                let url = '{{ route('backend.garage.show', ':id') }}'.replace(':id', id);
                $.get(url, function(res) {
                    if(res.success) {
                        const s = res.data;
                        $('#modal_event_title').text(s.event_title);
                        $('#modal_owner').text(res.owner);
                        $('#modal_location').text(s.pickup_location);
                        $('#modal_start').text(res.dates.start);
                        $('#modal_end').text(res.dates.end);
                        $('#modal_description').text(s.description || 'No description provided.');

                        let itemsHtml = '';
                        if(s.items && s.items.length > 0) {
                            s.items.forEach(item => {
                                itemsHtml += `<tr>
                                    <td class="fw-semibold">${item.title}</td>
                                    <td class="text-success fw-bold">$${item.price}</td>
                                    <td><small class="text-muted">${item.description || '-'}</small></td>
                                </tr>`;
                            });
                        } else {
                            itemsHtml = '<tr><td colspan="3" class="text-center">No items listed.</td></tr>';
                        }
                        $('#modal_inventory_items').html(itemsHtml);
                        $('#garageDetailModal').modal('show');
                    }
                });
            };

            // ─── Delete logic ──────────────────────────────────────────────
            window.showDeleteConfirm = function(id) {
                Swal.fire({
                    title: 'Delete this Garage Sale?',
                    text: 'This will remove the event and all associated items.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('backend.garage.destroy', ':id') }}'.replace(':id', id),
                            type: 'DELETE',
                            data: {_token: '{{ csrf_token() }}'},
                            success: function(res) {
                                if(res.success) {
                                    table.ajax.reload();
                                    toastr.success(res.message);
                                }
                            }
                        });
                    }
                });
            };
        });
    </script>

    <style>
        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
        }
    </style>
@endpush
