<?php $__env->startSection('title', 'Pickup Requests'); ?>

<?php $__env->startPush('styles-bottom'); ?>
<style>
    .pickup-card { border-radius: 14px; overflow: hidden; }
    .status-badge-pending  { background: #fff3cd; color: #856404; font-weight: 700; border-radius: 20px; padding: 4px 12px; font-size: 11px; }
    .status-badge-accepted { background: #d1e7dd; color: #0f5132; font-weight: 700; border-radius: 20px; padding: 4px 12px; font-size: 11px; }
    .status-badge-rejected { background: #f8d7da; color: #842029; font-weight: 700; border-radius: 20px; padding: 4px 12px; font-size: 11px; }
    .status-badge-completed { background: #cff4fc; color: #055160; font-weight: 700; border-radius: 20px; padding: 4px 12px; font-size: 11px; }
    .status-badge-default   { background: #e9ecef; color: #495057; font-weight: 700; border-radius: 20px; padding: 4px 12px; font-size: 11px; }
    .pickup-avatar { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; }
    .row-hover:hover { background: rgba(10,179,156,0.04) !important; }
    .modal-pickup-header { background: linear-gradient(135deg, #0ab39c, #405189); border-radius: 14px 14px 0 0; }
    .info-block { border-radius: 10px; padding: 14px 16px; margin-bottom: 12px; }
    .info-label { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .8px; color: #adb5bd; margin-bottom: 2px; }
    .info-value { font-size: 14px; font-weight: 700; color: #212529; }
    .pickup-stat-card { border-radius: 12px; padding: 16px; text-align: center; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0"><i class="ri-truck-line me-2 text-success"></i>Pickup Requests</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Messaging</a></li>
                        <li class="breadcrumb-item active">Pickups</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mb-4">
        <?php
            $statMap = ['pending' => ['color'=>'warning','icon'=>'ri-time-line'], 'accepted' => ['color'=>'success','icon'=>'ri-checkbox-circle-line'], 'rejected' => ['color'=>'danger','icon'=>'ri-close-circle-line'], 'completed' => ['color'=>'info','icon'=>'ri-check-double-line']];
        ?>
        <?php $__currentLoopData = $statMap; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $cfg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
            <div class="card border-0 shadow-sm" style="border-left: 4px solid var(--vz-<?php echo e($cfg['color']); ?>) !important;">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="avatar-sm bg-soft-<?php echo e($cfg['color']); ?> rounded-circle d-flex align-items-center justify-content-center">
                        <i class="<?php echo e($cfg['icon']); ?> fs-22 text-<?php echo e($cfg['color']); ?>"></i>
                    </div>
                    <div>
                        <p class="text-muted mb-0 fs-12 fw-bold text-uppercase"><?php echo e(ucfirst($status)); ?></p>
                        <h4 class="mb-0 fw-black text-<?php echo e($cfg['color']); ?>"><?php echo e($pickupStatusCounts[$status] ?? 0); ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm pickup-card">
                <div class="card-header border-bottom-dashed d-flex align-items-center gap-2 flex-wrap">
                    <h5 class="card-title mb-0 flex-grow-1">
                        <i class="ri-calendar-check-fill me-1 text-success"></i> Pickup Scheduling Overview
                    </h5>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="input-group" style="width: 230px;">
                            <span class="input-group-text bg-light border-0"><i class="ri-search-line text-muted"></i></span>
                            <input type="text" id="pickupSearch" class="form-control border-0 bg-light" placeholder="Search product or user...">
                        </div>
                        <select id="pickupStatusFilter" class="form-select border-0 bg-light" style="width: 160px;">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="accepted">Accepted</option>
                            <option value="rejected">Rejected</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="pickupTable">
                            <thead class="bg-light text-muted" style="font-size:11px; text-transform:uppercase; letter-spacing:.5px;">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Product</th>
                                    <th>Requester</th>
                                    <th>Pickup Schedule</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="pickupBody">
                                <?php $__currentLoopData = $pickups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pickup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $badgeClass = match($pickup->status) {
                                        'pending'   => 'status-badge-pending',
                                        'accepted'  => 'status-badge-accepted',
                                        'rejected'  => 'status-badge-rejected',
                                        'completed' => 'status-badge-completed',
                                        default     => 'status-badge-default',
                                    };
                                ?>
                                <tr class="row-hover border-bottom"
                                    data-status="<?php echo e($pickup->status); ?>"
                                    data-search="<?php echo e(strtolower(($pickup->product->title ?? '') . ' ' . ($pickup->requester->name ?? '') . ' ' . ($pickup->location ?? ''))); ?>">
                                    <td class="ps-4 text-muted fw-bold fs-12">
                                        <?php echo e(($pickups->currentPage() - 1) * $pickups->perPage() + $loop->index + 1); ?>

                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="pickup-avatar bg-soft-primary text-primary">
                                                <i class="ri-box-3-line"></i>
                                            </div>
                                            <div>
                                                <p class="mb-0 fw-semibold fs-13 text-truncate" style="max-width:130px;"><?php echo e($pickup->product->title ?? 'N/A'); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="pickup-avatar bg-soft-success text-success">
                                                <?php echo e(strtoupper(substr($pickup->requester->name ?? 'U', 0, 1))); ?>

                                            </div>
                                            <div>
                                                <p class="mb-0 fw-semibold fs-13"><?php echo e($pickup->requester->name ?? 'N/A'); ?></p>
                                                <small class="text-muted"><?php echo e($pickup->requester->email ?? ''); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="mb-0 fw-semibold fs-13"><i class="ri-calendar-line me-1 text-primary"></i><?php echo e($pickup->pickup_date ?? 'N/A'); ?></p>
                                        <small class="text-muted"><i class="ri-time-line me-1"></i><?php echo e($pickup->pickup_time ?? '—'); ?></small>
                                    </td>
                                    <td>
                                        <span class="fs-12 text-muted" title="<?php echo e($pickup->location); ?>">
                                            <i class="ri-map-pin-line me-1 text-danger"></i><?php echo e(Str::limit($pickup->location ?? '—', 28)); ?>

                                        </span>
                                    </td>
                                    <td><span class="<?php echo e($badgeClass); ?>"><?php echo e(ucfirst($pickup->status)); ?></span></td>
                                    <td>
                                        <p class="mb-0 fs-13"><?php echo e($pickup->created_at->format('d M Y')); ?></p>
                                        <small class="text-muted"><?php echo e($pickup->created_at->format('h:i A')); ?></small>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-soft-success btn-sm rounded-pill px-3"
                                            data-bs-toggle="modal"
                                            data-bs-target="#pickupDetailModal"
                                            data-id="<?php echo e($pickup->id); ?>"
                                            data-product="<?php echo e($pickup->product->title ?? 'N/A'); ?>"
                                            data-requester="<?php echo e($pickup->requester->name ?? 'N/A'); ?>"
                                            data-requester-email="<?php echo e($pickup->requester->email ?? ''); ?>"
                                            data-receiver="<?php echo e($pickup->receiver->name ?? 'N/A'); ?>"
                                            data-receiver-email="<?php echo e($pickup->receiver->email ?? ''); ?>"
                                            data-date="<?php echo e($pickup->pickup_date ?? 'N/A'); ?>"
                                            data-time="<?php echo e($pickup->pickup_time ?? 'N/A'); ?>"
                                            data-location="<?php echo e($pickup->location ?? 'N/A'); ?>"
                                            data-status="<?php echo e($pickup->status); ?>"
                                            data-badge-class="<?php echo e($badgeClass); ?>"
                                            data-created="<?php echo e($pickup->created_at->format('d M Y, h:i A')); ?>">
                                            <i class="ri-eye-line me-1"></i> Details
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <div id="pickupNoResults" class="text-center py-5 d-none">
                            <i class="ri-truck-line fs-40 text-muted"></i>
                            <p class="text-muted mt-2">No pickups found matching your filters.</p>
                        </div>
                    </div>
                    <div class="px-4 py-3 border-top d-flex align-items-center justify-content-between">
                        <div class="text-muted fs-13">
                            Showing <?php echo e($pickups->firstItem()); ?> to <?php echo e($pickups->lastItem()); ?> of <?php echo e($pickups->total()); ?> entries
                        </div>
                        <div class="pagination-separated mb-0">
                            <?php echo e($pickups->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="pickupDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-pickup-header p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-0 fw-bold text-white"><i class="ri-truck-line me-2"></i>Pickup Request Details</h5>
                        <small class="text-white opacity-75" id="pm-id">ID —</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1)"></button>
                </div>
                <div class="modal-body p-4">
                    
                    <div class="info-block bg-soft-primary">
                        <p class="info-label"><i class="ri-box-3-line me-1"></i> Product</p>
                        <p class="info-value mb-0" id="pm-product">—</p>
                    </div>
                    
                    <div class="row g-3 mb-2">
                        <div class="col-6">
                            <div class="info-block bg-soft-success h-100 mb-0">
                                <p class="info-label"><i class="ri-user-send-line me-1"></i> Requester</p>
                                <p class="info-value mb-0" id="pm-requester">—</p>
                                <small class="text-muted" id="pm-requester-email"></small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-block bg-soft-warning h-100 mb-0">
                                <p class="info-label"><i class="ri-user-received-line me-1"></i> Receiver</p>
                                <p class="info-value mb-0" id="pm-receiver">—</p>
                                <small class="text-muted" id="pm-receiver-email"></small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row g-3 mb-2 mt-0">
                        <div class="col-6">
                            <div class="info-block bg-soft-info mb-0">
                                <p class="info-label"><i class="ri-calendar-line me-1"></i> Date</p>
                                <p class="info-value mb-0" id="pm-date">—</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-block bg-soft-info mb-0">
                                <p class="info-label"><i class="ri-time-line me-1"></i> Time</p>
                                <p class="info-value mb-0" id="pm-time">—</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="info-block bg-light">
                        <p class="info-label"><i class="ri-map-pin-line me-1 text-danger"></i> Location</p>
                        <p class="info-value mb-0 fs-13" id="pm-location">—</p>
                    </div>
                    
                    <div class="d-flex gap-3">
                        <div class="info-block bg-light flex-fill mb-0">
                            <p class="info-label">Status</p>
                            <span id="pm-status">—</span>
                        </div>
                        <div class="info-block bg-light flex-fill mb-0">
                            <p class="info-label">Created At</p>
                            <p class="info-value mb-0 fs-12" id="pm-created">—</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts-bottom'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Filters
    function applyPickupFilters() {
        const search = document.getElementById('pickupSearch').value.toLowerCase();
        const status = document.getElementById('pickupStatusFilter').value;
        let visible = 0;
        document.querySelectorAll('#pickupBody tr').forEach(row => {
            const ms = !search || row.dataset.search.includes(search);
            const mst = status === 'all' || row.dataset.status === status;
            const show = ms && mst;
            row.classList.toggle('d-none', !show);
            if (show) visible++;
        });
        document.getElementById('pickupNoResults').classList.toggle('d-none', visible > 0);
    }
    document.getElementById('pickupSearch').addEventListener('keyup', applyPickupFilters);
    document.getElementById('pickupStatusFilter').addEventListener('change', applyPickupFilters);

    // Modal
    document.getElementById('pickupDetailModal').addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        document.getElementById('pm-id').textContent = 'ID #' + btn.dataset.id;
        document.getElementById('pm-product').textContent = btn.dataset.product;
        document.getElementById('pm-requester').textContent = btn.dataset.requester;
        document.getElementById('pm-requester-email').textContent = btn.dataset.requesterEmail;
        document.getElementById('pm-receiver').textContent = btn.dataset.receiver;
        document.getElementById('pm-receiver-email').textContent = btn.dataset.receiverEmail;
        document.getElementById('pm-date').textContent = btn.dataset.date;
        document.getElementById('pm-time').textContent = btn.dataset.time;
        document.getElementById('pm-location').textContent = btn.dataset.location;
        document.getElementById('pm-created').textContent = btn.dataset.created;

        const statusEl = document.getElementById('pm-status');
        statusEl.innerHTML = '<span class="' + btn.dataset.badgeClass + '">' + btn.dataset.status.charAt(0).toUpperCase() + btn.dataset.status.slice(1) + '</span>';
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Herd\mastering_laravelRo\resources\views/backend/messaging/pickups.blade.php ENDPATH**/ ?>