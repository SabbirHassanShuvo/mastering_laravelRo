<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startPush('css'); ?>
    <style>
        /* ── Base ─────────────────────────────────────────────────── */
        .dash-page {
            padding: 0 0 40px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* ── Page Header ──────────────────────────────────────────── */
        .dash-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding-bottom: 4px;
        }

        .dash-welcome {
            font-size: 17px;
            font-weight: 600;
            margin: 0;
            color: var(--vz-heading-color, #2c2c2a);
        }

        .dash-welcome-sub {
            font-size: 13px;
            color: var(--vz-secondary-color, #73726c);
            margin: 3px 0 0;
        }

        .btn-add-product {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            background: #1d9e75;
            color: #fff;
            text-decoration: none;
            transition: background .18s, transform .12s;
        }

        .btn-add-product:hover {
            background: #178f68;
            color: #fff;
        }

        .btn-add-product:active {
            transform: scale(.97);
        }

        /* ── Stat Card Grid ───────────────────────────────────────── */
        .stat-grid-4 {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            padding: 18px 20px 16px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }

        .sc-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .05em;
            text-transform: uppercase;
            opacity: .72;
        }

        .sc-val {
            font-size: 24px;
            font-weight: 700;
            line-height: 1.15;
            margin: 2px 0 1px;
        }

        .sc-sub {
            font-size: 12px;
            opacity: .68;
        }

        .sc-val-green {
            color: #1d9e75 !important;
        }

        .sc-val-red {
            color: #d85a30 !important;
        }

        .sc-blue {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: #fff;
        }

        .sc-green {
            background: linear-gradient(135deg, #059669, #047857);
            color: #fff;
        }

        .sc-cyan {
            background: linear-gradient(135deg, #0891b2, #0e7490);
            color: #fff;
        }

        .sc-amber {
            background: linear-gradient(135deg, #d97706, #b45309);
            color: #fff;
        }

        .sc-neutral {
            background: var(--vz-card-bg, #fff);
            border: 0.5px solid var(--vz-border-color, rgba(0, 0, 0, .09));
            color: var(--vz-body-color, #3d3d3a);
        }

        .sc-icon {
            position: absolute;
            bottom: 12px;
            right: 14px;
            opacity: .18;
        }

        .sc-badge {
            position: absolute;
            bottom: 14px;
            right: 14px;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge-blue {
            background: rgba(37, 99, 235, .1);
            color: #2563eb;
        }

        .badge-green {
            background: rgba(5, 150, 105, .1);
            color: #059669;
        }

        .badge-cyan {
            background: rgba(8, 145, 178, .1);
            color: #0891b2;
        }

        .badge-red {
            background: rgba(220, 38, 38, .1);
            color: #dc2626;
        }

        /* ── Chart Layout ─────────────────────────────────────────── */
        .chart-row-main {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 16px;
            align-items: start;
        }

        .chart-row-3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
            align-items: start;
        }

        /* ── Chart Card ───────────────────────────────────────────── */
        .chart-card {
            background: var(--vz-card-bg, #fff);
            border: 0.5px solid var(--vz-border-color, rgba(0, 0, 0, .09));
            border-radius: 14px;
            padding: 18px 20px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .05);
        }

        .chart-card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .chart-card-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--vz-heading-color, #2c2c2a);
            line-height: 1.3;
        }

        .chart-card-sub {
            font-size: 12px;
            color: var(--vz-secondary-color, #73726c);
            margin-top: 2px;
        }

        .chart-wrap {
            position: relative;
            width: 100%;
        }

        /* ── Time Filter Pills ────────────────────────────────────── */
        .time-pills {
            display: flex;
            gap: 4px;
            flex-shrink: 0;
        }

        .tp {
            font-size: 11px;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 20px;
            border: 0.5px solid var(--vz-border-color, rgba(0, 0, 0, .12));
            background: transparent;
            color: var(--vz-secondary-color, #73726c);
            cursor: pointer;
            transition: all .16s;
        }

        .tp:hover {
            background: rgba(0, 0, 0, .04);
        }

        .tp.active {
            background: rgba(37, 99, 235, .1);
            color: #2563eb;
            border-color: rgba(37, 99, 235, .3);
        }

        /* ── Performance Summary Row ──────────────────────────────── */
        .perf-summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            background: var(--vz-light, #f8f8f6);
            border-radius: 10px;
            margin-bottom: 16px;
            overflow: hidden;
        }

        .ps-item {
            padding: 12px 16px;
            text-align: center;
            border-right: 0.5px solid var(--vz-border-color, rgba(0, 0, 0, .08));
        }

        .ps-item:last-child {
            border-right: none;
        }

        .ps-val {
            font-size: 18px;
            font-weight: 700;
            color: var(--vz-heading-color, #2c2c2a);
            line-height: 1.2;
        }

        .ps-green {
            color: #1d9e75 !important;
        }

        .ps-label {
            font-size: 11px;
            color: var(--vz-secondary-color, #73726c);
            margin-top: 3px;
        }

        /* ── Legend ───────────────────────────────────────────────── */
        .chart-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 12px;
        }

        .cl-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--vz-secondary-color, #73726c);
        }

        .cl-dot {
            width: 10px;
            height: 10px;
            border-radius: 3px;
            flex-shrink: 0;
        }

        /* ── Revenue Breakdown ────────────────────────────────────── */
        .rev-breakdown {
            margin-top: 14px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .rb-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .rb-border {
            padding-bottom: 10px;
            border-bottom: 0.5px solid var(--vz-border-color, rgba(0, 0, 0, .08));
        }

        .rb-val {
            font-size: 13px;
            font-weight: 600;
            color: var(--vz-heading-color, #2c2c2a);
        }

        /* ── Activity Feed ────────────────────────────────────────── */
        .activity-feed {
            display: flex;
            flex-direction: column;
        }

        .act-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 0.5px solid var(--vz-border-color, rgba(0, 0, 0, .06));
        }

        .act-item:last-child {
            border-bottom: none;
        }

        .act-dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .act-user {
            background: rgba(37, 99, 235, .12);
            color: #2563eb;
        }

        .act-product {
            background: rgba(5, 150, 105, .12);
            color: #059669;
        }

        .act-payment {
            background: rgba(8, 145, 178, .12);
            color: #0891b2;
        }

        .act-pickup {
            background: rgba(217, 119, 6, .12);
            color: #d97706;
        }

        .act-report {
            background: rgba(220, 38, 38, .12);
            color: #dc2626;
        }

        .act-body {
            flex: 1;
            min-width: 0;
        }

        .act-title {
            font-size: 13px;
            font-weight: 500;
            color: var(--vz-heading-color, #2c2c2a);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .act-meta {
            font-size: 11px;
            color: var(--vz-secondary-color, #73726c);
            margin-top: 3px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .act-badge {
            display: inline-block;
            padding: 1px 7px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .03em;
        }

        .act-badge-primary {
            background: rgba(37, 99, 235, .1);
            color: #2563eb;
        }

        .act-badge-success {
            background: rgba(5, 150, 105, .1);
            color: #059669;
        }

        .act-badge-info {
            background: rgba(8, 145, 178, .1);
            color: #0891b2;
        }

        .act-badge-warning {
            background: rgba(217, 119, 6, .1);
            color: #d97706;
        }

        .act-badge-danger {
            background: rgba(220, 38, 38, .1);
            color: #dc2626;
        }

        /* ── Responsive ───────────────────────────────────────────── */
        @media (max-width: 1200px) {
            .chart-row-main {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 992px) {
            .stat-grid-4 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .chart-row-3 {
                grid-template-columns: 1fr 1fr;
            }

            .perf-summary {
                grid-template-columns: repeat(2, 1fr);
            }

            .perf-summary .ps-item:nth-child(2) {
                border-right: none;
            }
        }

        @media (max-width: 640px) {
            .stat-grid-4 {
                grid-template-columns: 1fr 1fr;
            }

            .chart-row-3 {
                grid-template-columns: 1fr;
            }

            .time-pills {
                flex-wrap: wrap;
            }

            .chart-card {
                padding: 14px;
            }
        }

        @media (max-width: 480px) {
            .stat-grid-4 {
                grid-template-columns: 1fr;
            }

            .perf-summary {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    <div class="dash-page">

        
        <div class="dash-header">
            <div>
                <h4 class="dash-welcome">Welcome back, <?php echo e(Auth::user()->name); ?>! 👋</h4>
                <p class="dash-welcome-sub">Here is the latest snapshot of your platform performance.</p>
            </div>
            <a href="<?php echo e(route('backend.products.create')); ?>" class="btn-add-product">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Add Product
            </a>
        </div>

        
        <div class="stat-grid-4">
            <div class="stat-card sc-blue">
                <div class="sc-label">Total Users</div>
                <div class="sc-val"><?php echo e(number_format($totalUsers)); ?></div>
                <div class="sc-sub"><?php echo e($verifiedUsers); ?> Verified</div>
                <div class="sc-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
            </div>
            <div class="stat-card sc-green">
                <div class="sc-label">Total Revenue</div>
                <div class="sc-val">$<?php echo e(number_format($totalRevenue, 2)); ?></div>
                <div class="sc-sub">Combined Earnings</div>
                <div class="sc-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" />
                        <path d="M12 18V6" />
                    </svg>
                </div>
            </div>
            <div class="stat-card sc-cyan">
                <div class="sc-label">Active Products</div>
                <div class="sc-val"><?php echo e(number_format($totalProducts)); ?></div>
                <div class="sc-sub"><?php echo e($spotlightedProducts); ?> Spotlighted</div>
                <div class="sc-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path
                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card sc-amber">
                <div class="sc-label">Garage Sales</div>
                <div class="sc-val"><?php echo e(number_format($totalGarageSales)); ?></div>
                <div class="sc-sub">Active Events</div>
                <div class="sc-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8"
                        viewBox="0 0 24 24">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                        <polyline points="9 22 9 12 15 12 15 22" />
                    </svg>
                </div>
            </div>
        </div>

        
        <div class="stat-grid-4">
            <div class="stat-card sc-neutral">
                <div class="sc-label">Total Matches</div>
                <div class="sc-val"><?php echo e(number_format($totalMatches)); ?></div>
                <div class="sc-sub">User Connections</div>
                <div class="sc-badge badge-blue">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path
                            d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card sc-neutral">
                <div class="sc-label">Match Conversion</div>
                <div class="sc-val sc-val-green"><?php echo e($matchConversionRate); ?>%</div>
                <div class="sc-sub"><?php echo e($completedPickups); ?> Completed Pickups</div>
                <div class="sc-badge badge-green">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                </div>
            </div>
            <div class="stat-card sc-neutral">
                <div class="sc-label">Total Messages</div>
                <div class="sc-val"><?php echo e(number_format($totalMessages)); ?></div>
                <div class="sc-sub">Chat Interactions</div>
                <div class="sc-badge badge-cyan">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card sc-neutral">
                <div class="sc-label">Reports</div>
                <div class="sc-val sc-val-red"><?php echo e(number_format($totalReports)); ?></div>
                <div class="sc-sub">Needs Attention</div>
                <div class="sc-badge badge-red">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                </div>
            </div>
        </div>

        
        <div class="chart-row-main">

            
            <div class="chart-card">
                <div class="chart-card-head">
                    <div>
                        <div class="chart-card-title">Platform Performance Trends</div>
                        <div class="chart-card-sub">Pickups · Revenue · Messages</div>
                    </div>
                    <div class="time-pills">
                        <button class="tp" onclick="filterChart('1M',this)">1M</button>
                        <button class="tp" onclick="filterChart('3M',this)">3M</button>
                        <button class="tp" onclick="filterChart('6M',this)">6M</button>
                        <button class="tp active" onclick="filterChart('ALL',this)">All</button>
                    </div>
                </div>

                <div class="perf-summary">
                    <div class="ps-item">
                        <div class="ps-val" id="ps-orders"><?php echo e($totalOrders); ?></div>
                        <div class="ps-label">Pickup Requests</div>
                    </div>
                    <div class="ps-item">
                        <div class="ps-val" id="ps-rev">$<?php echo e(number_format($totalRevenue, 0)); ?></div>
                        <div class="ps-label">Total Revenue</div>
                    </div>
                    <div class="ps-item">
                        <div class="ps-val" id="ps-msg"><?php echo e($totalMessages); ?></div>
                        <div class="ps-label">Messages</div>
                    </div>
                    <div class="ps-item">
                        <div class="ps-val ps-green"><?php echo e($matchConversionRate); ?>%</div>
                        <div class="ps-label">Conversion</div>
                    </div>
                </div>

                <div class="chart-wrap" style="height:310px;">
                    <canvas id="performanceChart"></canvas>
                </div>

                <div class="chart-legend">
                    <span class="cl-item"><span class="cl-dot" style="background:#1d9e75"></span>Earnings ($)</span>
                    <span class="cl-item"><span class="cl-dot" style="background:#378add"></span>Pickups</span>
                    <span class="cl-item"><span class="cl-dot"
                            style="background:#ba7517;border-radius:0"></span>Messages</span>
                </div>
            </div>

            
            <div class="chart-card">
                <div class="chart-card-head">
                    <div class="chart-card-title">Revenue Sources</div>
                </div>
                <div class="chart-wrap" style="height:220px;">
                    <canvas id="revenueChart"></canvas>
                </div>
                <div class="rev-breakdown">
                    <div class="rb-row rb-border">
                        <span class="cl-item"><span class="cl-dot" style="background:#378add"></span>Spotlight</span>
                        <span class="rb-val">$<?php echo e(number_format($revenueSources['Spotlight'], 2)); ?></span>
                    </div>
                    <div class="rb-row">
                        <span class="cl-item"><span class="cl-dot" style="background:#1d9e75"></span>Garage Sales</span>
                        <span class="rb-val">$<?php echo e(number_format($revenueSources['Garage Sales'], 2)); ?></span>
                    </div>
                </div>
            </div>

        </div>

        
        <div class="chart-row-3">

            
            <div class="chart-card">
                <div class="chart-card-head">
                    <div class="chart-card-title">Pickup Status</div>
                </div>
                <div class="chart-wrap" style="height:200px;">
                    <canvas id="pickupChart"></canvas>
                </div>
                <div class="chart-legend" style="justify-content:center;margin-top:10px;">
                    <?php $__currentLoopData = $pickupStatusDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="cl-item">
                            <span class="cl-dot"
                                style="background:<?php echo e(['completed' => '#1d9e75', 'pending' => '#378add', 'scheduled' => '#ba7517', 'cancelled' => '#d85a30'][$ps->status] ?? '#888'); ?>"></span>
                            <?php echo e(ucfirst($ps->status)); ?> (<?php echo e($ps->total); ?>)
                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="chart-card">
                <div class="chart-card-head">
                    <div class="chart-card-title">Top Categories</div>
                </div>
                <div class="chart-wrap" style="height:<?php echo e(count($categoryDistribution) * 38 + 60); ?>px; min-height:200px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            
            <div class="chart-card">
                <div class="chart-card-head">
                    <div class="chart-card-title">Recent Activity</div>
                </div>
                <div class="activity-feed">
                    <?php $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $act): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="act-item">
                            <div class="act-dot act-<?php echo e(strtolower($act['type'])); ?>">
                                <i class="<?php echo e($act['icon']); ?>"></i>
                            </div>
                            <div class="act-body">
                                <div class="act-title"><?php echo e($act['title']); ?></div>
                                <div class="act-meta">
                                    <span class="act-badge act-badge-<?php echo e($act['color']); ?>"><?php echo e($act['type']); ?></span>
                                    <span><?php echo e($act['time']?->diffForHumans() ?? '—'); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts-bottom'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark' ||
                window.matchMedia('(prefers-color-scheme: dark)').matches;

            const GRID = isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.06)';
            const TICK = isDark ? '#9c9a92' : '#73726c';
            const COLORS = {
                green: '#1d9e75',
                blue: '#378add',
                amber: '#ba7517',
                coral: '#d85a30'
            };

            // ── Data from Laravel ─────────────────────────────────────────
            const ALL = {
                months: <?php echo json_encode($months, 15, 512) ?>,
                earnings: <?php echo json_encode($earnings, 15, 512) ?>,
                orders: <?php echo json_encode($orders, 15, 512) ?>,
                messages: <?php echo json_encode($messageActivity, 15, 512) ?>,
            };

            const REVENUE_SOURCES = <?php echo json_encode(array_values($revenueSources), 15, 512) ?>;
            const REVENUE_LABELS = <?php echo json_encode(array_keys($revenueSources), 15, 512) ?>;
            const PICKUP_DATA = <?php echo json_encode($pickupStatusDistribution, 15, 512) ?>;
            const CATEGORY_DATA = <?php echo json_encode($categoryDistribution, 15, 512) ?>;

            // ── Helpers ───────────────────────────────────────────────────
            function slice(key) {
                const n = {
                    ALL: 12,
                    '6M': 6,
                    '3M': 3,
                    '1M': 1
                } [key] || 12;
                return {
                    months: ALL.months.slice(-n),
                    earnings: ALL.earnings.slice(-n),
                    orders: ALL.orders.slice(-n),
                    messages: ALL.messages.slice(-n),
                };
            }

            function sum(arr) {
                return arr.reduce((a, b) => a + b, 0);
            }

            function fmt(n) {
                return new Intl.NumberFormat().format(n);
            }

            function updateSummary(d) {
                document.getElementById('ps-orders').textContent = fmt(sum(d.orders));
                document.getElementById('ps-rev').textContent = '$' + fmt(sum(d.earnings));
                document.getElementById('ps-msg').textContent = fmt(sum(d.messages));
            }

            // ── Chart Defaults ────────────────────────────────────────────
            Chart.defaults.font.family = 'inherit';

            const base = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: isDark ? '#2c2c2a' : '#fff',
                        titleColor: isDark ? '#e0ded6' : '#2c2c2a',
                        bodyColor: isDark ? '#9c9a92' : '#73726c',
                        borderColor: isDark ? 'rgba(255,255,255,.1)' : 'rgba(0,0,0,.08)',
                        borderWidth: 1,
                        padding: 10,
                    }
                }
            };

            // ── Performance Chart ─────────────────────────────────────────
            const perfCtx = document.getElementById('performanceChart').getContext('2d');
            const earningsGrad = perfCtx.createLinearGradient(0, 0, 0, 280);
            earningsGrad.addColorStop(0, 'rgba(29,158,117,0.20)');
            earningsGrad.addColorStop(1, 'rgba(29,158,117,0.00)');

            let d = slice('ALL');
            updateSummary(d);

            const perfChart = new Chart(perfCtx, {
                data: {
                    labels: d.months,
                    datasets: [{
                            type: 'bar',
                            label: 'Pickups',
                            data: d.orders,
                            yAxisID: 'y1',
                            backgroundColor: 'rgba(55,138,221,0.65)',
                            borderRadius: 5,
                            barThickness: 'flex',
                            maxBarThickness: 28,
                            order: 2
                        },
                        {
                            type: 'line',
                            label: 'Earnings ($)',
                            data: d.earnings,
                            yAxisID: 'y',
                            borderColor: COLORS.green,
                            backgroundColor: earningsGrad,
                            borderWidth: 2,
                            fill: true,
                            tension: 0.42,
                            pointRadius: 0,
                            pointHoverRadius: 5,
                            order: 1
                        },
                        {
                            type: 'line',
                            label: 'Messages',
                            data: d.messages,
                            yAxisID: 'y',
                            borderColor: COLORS.amber,
                            backgroundColor: 'transparent',
                            borderWidth: 1.8,
                            borderDash: [5, 4],
                            tension: 0.42,
                            pointRadius: 0,
                            pointHoverRadius: 5,
                            order: 0
                        },
                    ]
                },
                options: {
                    ...base,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        x: {
                            grid: {
                                color: GRID
                            },
                            ticks: {
                                color: TICK,
                                font: {
                                    size: 11
                                }
                            },
                            border: {
                                display: false
                            }
                        },
                        y: {
                            position: 'left',
                            grid: {
                                color: GRID
                            },
                            ticks: {
                                color: TICK,
                                font: {
                                    size: 11
                                },
                                callback: v => '$' + v
                            },
                            border: {
                                display: false
                            }
                        },
                        y1: {
                            position: 'right',
                            grid: {
                                drawOnChartArea: false
                            },
                            ticks: {
                                color: TICK,
                                font: {
                                    size: 11
                                }
                            },
                            border: {
                                display: false
                            }
                        },
                    }
                }
            });

            // ── Revenue Donut ─────────────────────────────────────────────
            new Chart(document.getElementById('revenueChart'), {
                type: 'doughnut',
                data: {
                    labels: REVENUE_LABELS,
                    datasets: [{
                        data: REVENUE_SOURCES,
                        backgroundColor: [COLORS.blue, COLORS.green],
                        borderWidth: 0,
                        hoverOffset: 6
                    }]
                },
                options: {
                    ...base,
                    cutout: '72%',
                    plugins: {
                        ...base.plugins,
                        tooltip: {
                            ...base.plugins.tooltip,
                            callbacks: {
                                label: c => ' $' + c.parsed.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })
                            }
                        }
                    }
                }
            });

            // ── Pickup Status Donut ───────────────────────────────────────
            const pickupColors = {
                completed: COLORS.green,
                pending: COLORS.blue,
                scheduled: COLORS.amber,
                cancelled: COLORS.coral
            };
            new Chart(document.getElementById('pickupChart'), {
                type: 'doughnut',
                data: {
                    labels: PICKUP_DATA.map(x => x.status.charAt(0).toUpperCase() + x.status.slice(1)),
                    datasets: [{
                        data: PICKUP_DATA.map(x => x.total),
                        backgroundColor: PICKUP_DATA.map(x => pickupColors[x.status] ?? '#888'),
                        borderWidth: 0,
                        hoverOffset: 6
                    }]
                },
                options: {
                    ...base,
                    cutout: '65%'
                }
            });

            // ── Category Bar ──────────────────────────────────────────────
            new Chart(document.getElementById('categoryChart'), {
                type: 'bar',
                data: {
                    labels: CATEGORY_DATA.map(x => x.title),
                    datasets: [{
                        data: CATEGORY_DATA.map(x => x.products_count),
                        backgroundColor: 'rgba(55,138,221,0.65)',
                        borderRadius: 4,
                        barThickness: 16
                    }]
                },
                options: {
                    ...base,
                    indexAxis: 'y',
                    scales: {
                        x: {
                            grid: {
                                color: GRID
                            },
                            ticks: {
                                color: TICK,
                                font: {
                                    size: 11
                                }
                            },
                            border: {
                                display: false
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: TICK,
                                font: {
                                    size: 11
                                }
                            },
                            border: {
                                display: false
                            }
                        }
                    }
                }
            });

            // ── Time Filter ───────────────────────────────────────────────
            window.filterChart = function(key, btn) {
                document.querySelectorAll('.tp').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                const d2 = slice(key);
                updateSummary(d2);
                perfChart.data.labels = d2.months;
                perfChart.data.datasets[0].data = d2.orders;
                perfChart.data.datasets[1].data = d2.earnings;
                perfChart.data.datasets[2].data = d2.messages;
                perfChart.update('active');
            };

        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Herd\mastering_laravelRo\resources\views/backend/index.blade.php ENDPATH**/ ?>