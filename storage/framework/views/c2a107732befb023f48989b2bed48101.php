<?php $__env->startSection('title', 'City Market Explorer'); ?>

<?php $__env->startPush('styles-bottom'); ?>
<style>
    /* Premium Intelligence Grid Styles */
    .market-hub {
        background: #fdfdfd;
        border-radius: 20px;
        padding: 30px;
    }

    .city-card-detailed {
        border: 1px solid #edf2f9;
        border-radius: 16px;
        background: #fff;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        position: relative;
        overflow: hidden;
        height: 100%;
    }

    .city-card-detailed:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        border-color: #4b38b3;
    }

    .rank-tag {
        position: absolute;
        top: 0;
        right: 0;
        padding: 6px 15px;
        background: #f1f3f9;
        color: #4b38b3;
        font-weight: 900;
        font-size: 11px;
        border-bottom-left-radius: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .city-name-large {
        font-weight: 900;
        font-size: 20px;
        color: #1a1a1a;
        margin-bottom: 5px;
        letter-spacing: -0.5px;
    }

    .revenue-display {
        background: #f8f7ff;
        border-radius: 12px;
        padding: 15px;
        margin: 15px 0;
    }

    .revenue-amount {
        font-weight: 900;
        font-size: 24px;
        color: #4b38b3;
        display: block;
        line-height: 1;
    }

    .share-badge {
        font-size: 11px;
        font-weight: 800;
        background: rgba(75, 56, 179, 0.1);
        color: #4b38b3;
        padding: 3px 8px;
        border-radius: 6px;
        margin-top: 5px;
        display: inline-block;
    }

    .metric-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px dashed #eee;
    }

    .metric-row:last-child {
        border-bottom: none;
    }

    .metric-label {
        color: #727cf5;
        font-size: 12px;
        font-weight: 700;
        display: flex;
        align-items: center;
    }

    .metric-value {
        font-weight: 800;
        color: #333;
        font-size: 13px;
    }

    .capture-track {
        height: 8px;
        background: #f0f0f0;
        border-radius: 10px;
        margin: 15px 0 5px 0;
        overflow: hidden;
    }

    .capture-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 1s ease;
    }

    .tier-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .tier-top { background: #fff3cd; color: #856404; }
    .tier-mid { background: #d1ecf1; color: #0c5460; }
    .tier-low { background: #f8d7da; color: #721c24; }

    /* Custom Scrollbar for Grid */
    .market-grid-container {
        max-height: 800px;
        overflow-y: auto;
        padding-right: 10px;
    }

    .market-grid-container::-webkit-scrollbar { width: 6px; }
    .market-grid-container::-webkit-scrollbar-thumb { background: #e2e5ec; border-radius: 10px; }

    /* Animations */
    .pop-in {
        animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        opacity: 0;
    }

    @keyframes popIn {
        from { transform: scale(0.9); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <!-- Premium Header -->
    <div class="row align-items-center mb-5 pop-in">
        <div class="col">
            <h4 class="fw-black text-dark fs-28 mb-0">Market Intelligence Explorer</h4>
            <p class="text-muted mt-1 fs-15">Real-time performance distribution across all city hubs.</p>
        </div>
        <div class="col-auto">
            <div class="d-flex gap-2">
                <input type="text" id="marketSearch" class="form-control border-0 shadow-sm px-4 rounded-pill" style="width: 300px;" placeholder="🔥 Quick find location...">
                <a href="<?php echo e(route('backend.spotlight.index')); ?>" class="btn btn-primary rounded-pill shadow-primary px-4">
                    <i class="ri-arrow-left-line me-1"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Performance Hub -->
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-md-6 pop-in" style="animation-delay: 0.1s;">
            <div class="card shadow-sm p-4 h-100 bg-soft-primary border-start border-primary border-4">
                <h6 class="text-muted fw-bold text-uppercase fs-11 ls-1 mb-3">Total Hubs</h6>
                <h2 class="fw-black text-primary mb-1 display-5"><?php echo e($cityRevenue->count()); ?></h2>
                <p class="text-dark fw-bold mb-0 mt-2 fs-14">
                    <i class="ri-map-pin-2-fill me-1"></i> Global Coverage
                </p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 pop-in" style="animation-delay: 0.2s;">
            <div class="card shadow-sm p-4 h-100 bg-soft-success border-start border-success border-4">
                <h6 class="text-muted fw-bold text-uppercase fs-11 ls-1 mb-3">Peak Revenue Hub</h6>
                <h2 class="fw-black text-success mb-1 display-5">$<?php echo e(number_format($cityRevenue->max('revenue'), 0)); ?></h2>
                <p class="text-dark fw-bold mb-0 mt-2 fs-14">
                    <i class="ri-building-2-fill me-1"></i> <?php echo e($cityRevenue->first()->city ?? 'None'); ?>

                </p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 pop-in" style="animation-delay: 0.3s;">
            <div class="card shadow-sm p-4 h-100 bg-soft-info border-start border-info border-4">
                <h6 class="text-muted fw-bold text-uppercase fs-11 ls-1 mb-3">Avg Market capture</h6>
                <h2 class="fw-black text-info mb-1 display-5"><?php echo e(number_format($cityRevenue->avg('capture_rate'), 1)); ?>%</h2>
                <p class="text-dark fw-bold mb-0 mt-2 fs-14">Penetration Rate</p>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 pop-in" style="animation-delay: 0.4s;">
            <div class="card shadow-sm p-4 h-100 bg-soft-warning border-start border-warning border-4">
                <h6 class="text-muted fw-bold text-uppercase fs-11 ls-1 mb-3">Highest Penetration</h6>
                <h2 class="fw-black text-warning mb-1 display-5"><?php echo e(number_format($cityRevenue->max('capture_rate'), 1)); ?>%</h2>
                <p class="text-dark fw-bold mb-0 mt-2 fs-14">
                    <i class="ri-flashlight-line me-1"></i> <?php echo e($cityRevenue->sortByDesc('capture_rate')->first()->city ?? 'None'); ?>

                </p>
            </div>
        </div>
    </div>

    <!-- Detailed Market Grid (Now containing ALL DATA) -->
    <div class="market-hub shadow-sm mb-5 pop-in" style="animation-delay: 0.5s;">
        <div class="d-flex align-items-center mb-4">
            <h5 class="fw-black text-dark mb-0 flex-grow-1"><i class="ri-node-tree text-primary me-2"></i>Location Intelligence Matrix</h5>
            <div class="text-muted fs-12 fw-bold bg-light px-3 py-1 rounded-pill">
                Showing <?php echo e($cityRevenue->count()); ?> Market Entities
            </div>
        </div>

        <div class="market-grid-container">
            <div class="row g-4" id="marketTiles">
                <?php $__currentLoopData = $cityRevenue; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php 
                    $rank = $idx + 1;
                    $tier = $idx < 3 ? 'tier-top' : ($idx < 7 ? 'tier-mid' : 'tier-low');
                    $tierLabel = $idx < 3 ? 'Market Leader' : ($idx < 7 ? 'Core Hub' : 'Emerging');
                    $progressColor = $city->capture_rate > 50 ? '#0ab39c' : ($city->capture_rate > 20 ? '#4b38b3' : '#f06548');
                ?>
                <div class="col-xl-4 col-lg-6 market-tile" data-name="<?php echo e(strtolower($city->city)); ?>">
                    <div class="city-card-detailed p-4">
                        <div class="rank-tag">#<?php echo e($rank); ?></div>
                        
                        <div class="d-flex align-items-start mb-2">
                            <div class="avatar-sm bg-soft-primary text-primary rounded-12 d-flex align-items-center justify-content-center me-3 p-2">
                                <i class="ri-building-4-fill fs-24"></i>
                            </div>
                            <div>
                                <h3 class="city-name-large mb-0"><?php echo e($city->city); ?></h3>
                                <span class="tier-badge <?php echo e($tier); ?>"><?php echo e($tierLabel); ?></span>
                            </div>
                        </div>

                        <div class="revenue-display">
                            <span class="text-muted fs-11 text-uppercase fw-bold ls-1 mb-1 d-block">Market Revenue</span>
                            <span class="revenue-amount">$<?php echo e(number_format($city->revenue, 2)); ?></span>
                            <span class="share-badge"><?php echo e(number_format($city->contribution, 1)); ?>% Global Share</span>
                        </div>

                        <div class="metrics-grid">
                            <div class="metric-row">
                                <span class="metric-label"><i class="ri-flashlight-fill me-1"></i> Paid Boosts</span>
                                <span class="metric-value"><?php echo e($city->boost_count); ?></span>
                            </div>
                            <div class="metric-row">
                                <span class="metric-label"><i class="ri-stack-line me-1"></i> Total Inventory</span>
                                <span class="metric-value"><?php echo e($city->total_products); ?></span>
                            </div>
                            <div class="metric-row">
                                <span class="metric-label"><i class="ri-scales-3-line me-1"></i> Avg. Boost Value</span>
                                <span class="metric-value">$<?php echo e(number_format($city->avg_boost_value, 1)); ?></span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="d-flex justify-content-between align-items-end mb-1">
                                <span class="text-muted fw-bold fs-11">MARKET CAPTURE</span>
                                <span class="fw-black fs-14" style="color: <?php echo e($progressColor); ?>"><?php echo e(number_format($city->capture_rate, 1)); ?>%</span>
                            </div>
                            <div class="capture-track">
                                <div class="capture-fill" style="width: <?php echo e($city->capture_rate); ?>%; background: <?php echo e($progressColor); ?>; box-shadow: 0 0 8px <?php echo e($progressColor); ?>40;"></div>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                            <span class="text-muted fs-11"><i class="ri-time-line me-1"></i><?php echo e($city->last_boost_at ? \Carbon\Carbon::parse($city->last_boost_at)->diffForHumans() : 'No activity'); ?></span>
                            <a href="javascript:void(0);" class="btn btn-soft-primary btn-sm rounded-pill px-3 fs-10 fw-bold">Detailed Audit</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts-bottom'); ?>
<script>
    $(document).ready(function() {
        // --- Premium Real-time Explorer Search ---
        $("#marketSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".market-tile").filter(function() {
                var isMatch = $(this).data('name').indexOf(value) > -1;
                $(this).toggle(isMatch);
                
                // Add pop-in effect on filter
                if(isMatch) {
                    $(this).addClass('pop-in');
                }
            });
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Downloads\Sabbir\mastering_laravelRo\resources\views/backend/layout/spotlight/city_analytics.blade.php ENDPATH**/ ?>