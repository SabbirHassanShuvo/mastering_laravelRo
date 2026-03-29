@extends('backend.master')
@section('title', 'All Conversations')

@push('styles-bottom')
    <style>
        .conv-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            flex-shrink: 0;
        }

        .filter-bar {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .status-dot.active {
            background: #0ab39c;
        }

        .status-dot.inactive {
            background: #adb5bd;
        }

        .conv-row:hover {
            background: rgba(64, 81, 137, 0.04) !important;
            cursor: default;
        }

        .msg-bubble {
            background: #f1f3f9;
            border-radius: 8px;
            padding: 2px 10px;
            font-size: 12px;
            font-weight: 600;
            color: #405189;
        }

        .detail-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #adb5bd;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 600;
            color: #343a40;
        }

        .modal-header-premium {
            background: linear-gradient(135deg, #405189, #0ab39c);
            color: #fff;
            border-radius: 12px 12px 0 0;
        }

        .modal-header-premium .btn-close {
            filter: invert(1);
        }

        .quick-stat-mini {
            border-radius: 10px;
            padding: 10px 14px;
            flex: 1;
            text-align: center;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">All Conversations</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Messaging</a></li>
                        <li class="breadcrumb-item active">Conversations</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card border-0 shadow-sm" style="border-left: 4px solid #405189 !important;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="avatar-sm bg-soft-primary rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ri-message-3-line fs-22 text-primary"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-12 fw-bold text-uppercase">Total</p>
                        <h4 class="mb-0 fw-black text-primary">{{ $conversations->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card border-0 shadow-sm" style="border-left: 4px solid #0ab39c !important;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="avatar-sm bg-soft-success rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ri-checkbox-circle-line fs-22 text-success"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-12 fw-bold text-uppercase">Active</p>
                        <h4 class="mb-0 fw-black text-success" id="activeCount">—</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card border-0 shadow-sm" style="border-left: 4px solid #adb5bd !important;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div
                        class="avatar-sm bg-soft-secondary rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ri-archive-line fs-22 text-secondary"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-12 fw-bold text-uppercase">Inactive</p>
                        <h4 class="mb-0 fw-black text-secondary" id="inactiveCount">—</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm" style="border-left: 4px solid #f7b84b !important;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="avatar-sm bg-soft-warning rounded-circle d-flex align-items-center justify-content-center">
                        <i class="ri-chat-4-line fs-22 text-warning"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-12 fw-bold text-uppercase">This Page</p>
                        <h4 class="mb-0 fw-black text-warning">{{ $conversations->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header border-bottom-dashed d-flex align-items-center gap-2 flex-wrap">
                    <h5 class="card-title mb-0 flex-grow-1">
                        <i class="ri-message-3-fill me-1 text-primary"></i> Conversation History
                    </h5>
                    {{-- Filter Bar --}}
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="input-group" style="width: 220px;">
                            <span class="input-group-text bg-light border-0"><i
                                    class="ri-search-line text-muted"></i></span>
                            <input type="text" id="convSearch" class="form-control border-0 bg-light"
                                placeholder="Search user or product...">
                        </div>
                        <select id="statusFilter" class="form-select border-0 bg-light" style="width: 150px;">
                            <option value="all">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="convTable">
                            <thead class="bg-light text-muted"
                                style="font-size:11px; text-transform:uppercase; letter-spacing:.5px;">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Product</th>
                                    <th>Initiator</th>
                                    <th>Receiver</th>
                                    <th class="text-center">Messages</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="convBody">
                                @foreach ($conversations as $index => $conv)
                                    <tr class="conv-row border-bottom" data-status="{{ $conv->status }}"
                                        data-search="{{ strtolower(($conv->userOne->name ?? '') . ' ' . ($conv->userTwo->name ?? '') . ' ' . ($conv->product->title ?? '')) }}">
                                        <td class="ps-4 text-muted fw-bold fs-12">
                                            {{ ($conversations->currentPage() - 1) * $conversations->perPage() + $index + 1 }}
                                        </td>
                                        <td>
                                            @if ($conv->product)
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="conv-avatar bg-soft-primary text-primary">
                                                        <i class="ri-box-3-line"></i>
                                                    </div>
                                                    <div>
                                                        <p class="mb-0 fw-semibold fs-13 text-truncate"
                                                            style="max-width:130px;">{{ $conv->product->title }}</p>
                                                        <small class="text-muted">ID #{{ $conv->product_id }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted fs-12 fst-italic">No product</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="conv-avatar bg-soft-info text-info"
                                                    style="background: #cff4fc;">
                                                    {{ strtoupper(substr($conv->userOne->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="mb-0 fw-semibold fs-13">{{ $conv->userOne->name ?? 'N/A' }}
                                                    </p>
                                                    <small class="text-muted">{{ $conv->userOne->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="conv-avatar bg-soft-warning text-warning"
                                                    style="background: #fff3cd;">
                                                    {{ strtoupper(substr($conv->userTwo->name ?? 'U', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="mb-0 fw-semibold fs-13">{{ $conv->userTwo->name ?? 'N/A' }}
                                                    </p>
                                                    <small class="text-muted">{{ $conv->userTwo->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="msg-bubble">{{ $conv->messages_count }}</span>
                                        </td>
                                        <td>
                                            <span class="d-flex align-items-center">
                                                <span
                                                    class="status-dot {{ $conv->status == 'active' ? 'active' : 'inactive' }}"></span>
                                                <span
                                                    class="badge {{ $conv->status == 'active' ? 'bg-soft-success text-success' : 'bg-soft-secondary text-secondary' }} fs-11 fw-bold">
                                                    {{ ucfirst($conv->status ?? 'N/A') }}
                                                </span>
                                            </span>
                                        </td>
                                        <td>
                                            <p class="mb-0 fs-13">{{ $conv->created_at->format('d M Y') }}</p>
                                            <small class="text-muted">{{ $conv->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-soft-primary btn-sm rounded-pill px-3"
                                                data-bs-toggle="modal" data-bs-target="#convDetailModal"
                                                data-id="{{ $conv->id }}"
                                                data-product="{{ $conv->product->title ?? 'N/A' }}"
                                                data-product-id="{{ $conv->product_id ?? 'N/A' }}"
                                                data-user-one="{{ $conv->userOne->name ?? 'N/A' }}"
                                                data-user-one-email="{{ $conv->userOne->email ?? '' }}"
                                                data-user-two="{{ $conv->userTwo->name ?? 'N/A' }}"
                                                data-user-two-email="{{ $conv->userTwo->email ?? '' }}"
                                                data-messages="{{ $conv->messages_count }}"
                                                data-status="{{ $conv->status }}"
                                                data-created="{{ $conv->created_at->format('d M Y, h:i A') }}">
                                                <i class="ri-eye-line me-1"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div id="noResults" class="text-center py-5 d-none">
                            <i class="ri-search-2-line fs-40 text-muted"></i>
                            <p class="text-muted mt-2">No conversations found matching your filters.</p>
                        </div>
                    </div>
                    <div class="p-3 border-top">
                        {{ $conversations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Conversation Detail Modal --}}
    <div class="modal fade" id="convDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
                <div class="modal-header modal-header-premium py-3 px-4">
                    <div>
                        <h5 class="modal-title mb-0 fw-bold text-white"><i class="ri-message-3-fill me-2"></i>Conversation
                            Detail</h5>
                        <small class="text-white opacity-75" id="modal-conv-id">ID —</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    {{-- Product --}}
                    <div class="bg-soft-primary rounded-3 p-3 mb-3">
                        <p class="detail-label mb-1"><i class="ri-box-3-line me-1"></i> Product</p>
                        <p class="detail-value mb-0" id="modal-product">—</p>
                        <small class="text-muted" id="modal-product-id">ID —</small>
                    </div>
                    {{-- Users --}}
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="bg-soft-info rounded-3 p-3 h-100">
                                <p class="detail-label mb-1"><i class="ri-user-send-line me-1"></i> Initiator</p>
                                <p class="detail-value mb-0" id="modal-user-one">—</p>
                                <small class="text-muted" id="modal-user-one-email"></small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-soft-warning rounded-3 p-3 h-100">
                                <p class="detail-label mb-1"><i class="ri-user-received-line me-1"></i> Receiver</p>
                                <p class="detail-value mb-0" id="modal-user-two">—</p>
                                <small class="text-muted" id="modal-user-two-email"></small>
                            </div>
                        </div>
                    </div>
                    {{-- Stats row --}}
                    <div class="d-flex gap-2 mb-3">
                        <div class="quick-stat-mini bg-soft-primary">
                            <p class="detail-label mb-0">Messages</p>
                            <h4 class="fw-black text-primary mb-0" id="modal-messages">—</h4>
                        </div>
                        <div class="quick-stat-mini bg-soft-success">
                            <p class="detail-label mb-0">Status</p>
                            <span id="modal-status-badge" class="badge fs-12 fw-bold mt-1">—</span>
                        </div>
                        <div class="quick-stat-mini bg-soft-secondary">
                            <p class="detail-label mb-0">Created</p>
                            <p class="fw-bold text-dark mb-0 fs-12" id="modal-created">—</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts-bottom')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Count active vs inactive
            let active = 0,
                inactive = 0;
            document.querySelectorAll('#convBody tr').forEach(row => {
                if (row.dataset.status === 'active') active++;
                else inactive++;
            });
            document.getElementById('activeCount').textContent = active;
            document.getElementById('inactiveCount').textContent = inactive;

            // Filter logic
            function applyFilters() {
                const search = document.getElementById('convSearch').value.toLowerCase();
                const status = document.getElementById('statusFilter').value;
                let visible = 0;

                document.querySelectorAll('#convBody tr').forEach(row => {
                    const matchSearch = !search || row.dataset.search.includes(search);
                    const matchStatus = status === 'all' || row.dataset.status === status;
                    const show = matchSearch && matchStatus;
                    row.classList.toggle('d-none', !show);
                    if (show) visible++;
                });

                document.getElementById('noResults').classList.toggle('d-none', visible > 0);
            }

            document.getElementById('convSearch').addEventListener('keyup', applyFilters);
            document.getElementById('statusFilter').addEventListener('change', applyFilters);

            // Modal population
            document.getElementById('convDetailModal').addEventListener('show.bs.modal', function(e) {
                const btn = e.relatedTarget;
                document.getElementById('modal-conv-id').textContent = 'ID #' + btn.dataset.id;
                document.getElementById('modal-product').textContent = btn.dataset.product;
                document.getElementById('modal-product-id').textContent = 'Product ID: ' + btn.dataset
                    .productId;
                document.getElementById('modal-user-one').textContent = btn.dataset.userOne;
                document.getElementById('modal-user-one-email').textContent = btn.dataset.userOneEmail;
                document.getElementById('modal-user-two').textContent = btn.dataset.userTwo;
                document.getElementById('modal-user-two-email').textContent = btn.dataset.userTwoEmail;
                document.getElementById('modal-messages').textContent = btn.dataset.messages;
                document.getElementById('modal-created').textContent = btn.dataset.created;

                const statusBadge = document.getElementById('modal-status-badge');
                const isActive = btn.dataset.status === 'active';
                statusBadge.textContent = btn.dataset.status ? btn.dataset.status.charAt(0).toUpperCase() +
                    btn.dataset.status.slice(1) : 'N/A';
                statusBadge.className = 'badge fs-12 fw-bold mt-1 ' + (isActive ? 'bg-success' :
                    'bg-secondary');
            });
        });
    </script>
@endpush
