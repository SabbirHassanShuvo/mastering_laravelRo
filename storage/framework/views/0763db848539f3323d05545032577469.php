<?php $__env->startSection('title', 'Messaging Analytics'); ?>

<?php $__env->startPush('styles-bottom'); ?>
    <style>
        /* ── KPI Cards ── */
        .kpi-card {
            border-radius: 18px;
            border: none;
            position: relative;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }

        .kpi-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, .10) !important;
        }

        .kpi-card .kpi-bg-icon {
            position: absolute;
            right: -10px;
            bottom: -10px;
            font-size: 72px;
            opacity: .07;
        }

        .kpi-icon-box {
            width: 54px;
            height: 54px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        /* ── Section Headers ── */
        .sec-title {
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #adb5bd;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f3f9;
            margin-bottom: 18px;
        }

        /* ── Rank chips ── */
        .rank-chip {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            font-size: 11px;
            font-weight: 900;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .rank-1 {
            background: linear-gradient(135deg, #f7b84b, #e5a100);
            color: #fff;
        }

        .rank-2 {
            background: linear-gradient(135deg, #ced4da, #9eaab3);
            color: #fff;
        }

        .rank-3 {
            background: linear-gradient(135deg, #cd7f50, #a0522d);
            color: #fff;
        }

        .rank-n {
            background: #f1f3f9;
            color: #6c757d;
        }

        /* ── Progress bar rows ── */
        .prog-row {
            padding: 10px 0;
            border-bottom: 1px solid #f1f3f9;
        }

        .prog-row:last-child {
            border-bottom: none;
        }

        .prog-bar-wrap {
            height: 7px;
            border-radius: 10px;
            background: #f1f3f9;
            overflow: hidden;
        }

        .prog-bar-fill {
            height: 100%;
            border-radius: 10px;
            transition: width .6s cubic-bezier(.4, 0, .2, 1);
        }

        /* ── Empty state ── */
        .empty-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 200px;
        }

        .empty-box i {
            font-size: 48px;
            margin-bottom: 12px;
            color: #dee2e6;
        }

        .empty-box p {
            font-size: 13px;
            font-weight: 600;
            margin: 0;
            color: #adb5bd;
        }

        /* ── Pickup stat mini ── */
        .pstat-box {
            border-radius: 14px;
            padding: 14px 16px;
            flex: 1;
            text-align: center;
            min-width: 0;
        }

        .pstat-box .pstat-num {
            font-size: 26px;
            font-weight: 900;
            line-height: 1;
        }

        .pstat-box .pstat-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-top: 4px;
        }

        /* ── Overview list ── */
        .ov-row {
            padding: 12px 0;
            border-bottom: 1px solid #f1f3f9;
        }

        .ov-row:last-child {
            border-bottom: none;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0"><i class="ri-bar-chart-box-fill me-2 text-primary"></i> Messaging Analytics</h4>
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Messaging</a></li>
                    <li class="breadcrumb-item active">Analytics</li>
                </ol>
            </div>
        </div>
    </div>

    <?php
        $donutLabels = $pickupStats->pluck('status')->map(fn($s) => ucfirst($s))->values()->toArray();
        $donutValues = $pickupStats->pluck('count')->map(fn($c) => (int) $c)->values()->toArray();
        $donutColorMap = [
            'pending' => '#f7b84b',
            'accepted' => '#0ab39c',
            'rejected' => '#f06548',
            'completed' => '#405189',
            'cancelled' => '#adb5bd',
        ];
        $donutColorArr = $pickupStats
            ->pluck('status')
            ->map(fn($s) => $donutColorMap[$s] ?? '#adb5bd')
            ->values()
            ->toArray();
        $hasPickupData = count($donutValues) > 0 && array_sum($donutValues) > 0;

        $puNames = $powerUsers
            ->take(8)
            ->map(fn($u) => \Illuminate\Support\Str::limit($u->user->name ?? 'N/A', 18))
            ->values()
            ->toArray();
        $puCounts = $powerUsers->take(8)->map(fn($u) => (int) $u->products_count)->values()->toArray();
        $puMax = count($puCounts) ? max($puCounts) : 1;
        $hasPuData = count($puCounts) > 0;

        $tmTitles = $topMatchedProducts
            ->take(8)
            ->map(fn($m) => \Illuminate\Support\Str::limit($m->product->title ?? 'N/A', 18))
            ->values()
            ->toArray();
        $tmCounts = $topMatchedProducts->take(8)->map(fn($m) => (int) $m->matches_count)->values()->toArray();
        $tmMax = count($tmCounts) ? max($tmCounts) : 1;
        $hasTmData = count($tmCounts) > 0;

        $pickupStatusConfig = [
            'pending' => [
                'label' => 'Pending',
                'color' => '#f7b84b',
                'soft' => 'bg-soft-warning',
                'text' => 'text-warning',
            ],
            'accepted' => [
                'label' => 'Accepted',
                'color' => '#0ab39c',
                'soft' => 'bg-soft-success',
                'text' => 'text-success',
            ],
            'rejected' => [
                'label' => 'Rejected',
                'color' => '#f06548',
                'soft' => 'bg-soft-danger',
                'text' => 'text-danger',
            ],
            'completed' => [
                'label' => 'Completed',
                'color' => '#405189',
                'soft' => 'bg-soft-primary',
                'text' => 'text-primary',
            ],
            'cancelled' => [
                'label' => 'Cancelled',
                'color' => '#adb5bd',
                'soft' => 'bg-soft-secondary',
                'text' => 'text-secondary',
            ],
        ];
        $totalPickups = $pickupStats->sum('count') ?: 1;
    ?>

    
    <div class="row g-3 mb-4">
        <?php
            $kpis = [
                [
                    'label' => 'Total Matches',
                    'value' => $totals['total_matches'],
                    'icon' => 'ri-heart-3-fill',
                    'color' => 'danger',
                    'desc' => 'Product match events',
                ],
                [
                    'label' => 'Pickup Requests',
                    'value' => $totals['total_pickups'],
                    'icon' => 'ri-truck-fill',
                    'color' => 'warning',
                    'desc' => 'Scheduling requests',
                ],
                [
                    'label' => 'Contact Shares',
                    'value' => $totals['total_contact_shares'],
                    'icon' => 'ri-contacts-book-fill',
                    'color' => 'info',
                    'desc' => 'Phone numbers shared',
                ],
                [
                    'label' => 'Products Listed',
                    'value' => $hasPuData ? $powerUsers->sum('products_count') : 0,
                    'icon' => 'ri-store-3-fill',
                    'color' => 'success',
                    'desc' => 'Total products listed',
                ],
            ];
        ?>
        <?php $__currentLoopData = $kpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-xl-3 col-md-6">
                <div class="card kpi-card shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3 py-4">
                        <div class="kpi-icon-box bg-soft-<?php echo e($kpi['color']); ?> text-<?php echo e($kpi['color']); ?>">
                            <i class="<?php echo e($kpi['icon']); ?>"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-0 fs-12 fw-bold text-uppercase"><?php echo e($kpi['label']); ?></p>
                            <h2 class="mb-0 fw-black text-<?php echo e($kpi['color']); ?>"><?php echo e(number_format($kpi['value'])); ?></h2>
                            <p class="text-muted mb-0 fs-11 mt-1"><?php echo e($kpi['desc']); ?></p>
                        </div>
                        <i class="<?php echo e($kpi['icon']); ?> kpi-bg-icon text-<?php echo e($kpi['color']); ?>"></i>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div class="row g-3 mb-4">

        
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius:18px;">
                <div class="card-body">
                    <p class="sec-title"><i class="ri-truck-fill me-1 text-warning"></i> Pickup Request Breakdown</p>

                    <?php if($pickupStats->isEmpty()): ?>
                        <div class="empty-box">
                            <i class="ri-truck-line"></i>
                            <p>No pickup data yet</p>
                        </div>
                    <?php else: ?>
                        
                        <div class="d-flex gap-2 mb-4 flex-wrap">
                            <?php $__currentLoopData = $pickupStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $cfg = $pickupStatusConfig[$ps->status] ?? ['soft'=>'bg-light','text'=>'text-dark','label'=>ucfirst($ps->status),'color'=>'#adb5bd']; ?>
                                <div class="pstat-box <?php echo e($cfg['soft']); ?>">
                                    <div class="pstat-num <?php echo e($cfg['text']); ?>"><?php echo e(number_format($ps->count)); ?></div>
                                    <div class="pstat-label <?php echo e($cfg['text']); ?>"><?php echo e($cfg['label']); ?></div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        
                        <?php $__currentLoopData = $pickupStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $cfg = $pickupStatusConfig[$ps->status] ?? [
                                    'label' => ucfirst($ps->status),
                                    'color' => '#adb5bd',
                                ];
                                $pct = round(($ps->count / $totalPickups) * 100);
                            ?>
                            <div class="prog-row">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold fs-13">
                                        <span
                                            style="display:inline-block;width:9px;height:9px;border-radius:50%;background:<?php echo e($cfg['color']); ?>;vertical-align:middle;margin-right:6px;"></span>
                                        <?php echo e($cfg['label']); ?>

                                    </span>
                                    <span class="fw-bold fs-12 text-muted"><?php echo e($pct); ?>%&nbsp;
                                        (<?php echo e($ps->count); ?>)</span>
                                </div>
                                <div class="prog-bar-wrap">
                                    <div class="prog-bar-fill"
                                        style="width:<?php echo e($pct); ?>%; background:<?php echo e($cfg['color']); ?>;"></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        
                        <?php if($hasPickupData): ?>
                            <div id="pickupDonutChart" class="mt-3" style="min-height:220px;"></div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius:18px;">
                <div class="card-body">
                    <p class="sec-title">
                        <i class="ri-heart-3-fill me-1 text-danger"></i> Top Matched Products
                        <?php if($hasTmData): ?>
                            <span class="float-end badge bg-soft-danger text-danger fs-11">Top
                                <?php echo e(count($tmCounts)); ?></span>
                        <?php endif; ?>
                    </p>

                    <?php if($hasTmData): ?>
                        <?php $__currentLoopData = $topMatchedProducts->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $pct = round(($item->matches_count / $tmMax) * 100); ?>
                            <div class="prog-row">
                                <div class="d-flex align-items-center gap-3">
                                    <span
                                        class="rank-chip <?php echo e($idx < 3 ? 'rank-' . ($idx + 1) : 'rank-n'); ?>"><?php echo e($idx + 1); ?></span>
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-semibold fs-13 text-truncate" style="max-width:260px;">
                                                <?php echo e($item->product->title ?? 'Deleted Product'); ?>

                                            </span>
                                            <span class="badge bg-soft-danger text-danger fs-12 fw-bold ms-2 flex-shrink-0">
                                                <?php echo e(number_format($item->matches_count)); ?> matches
                                            </span>
                                        </div>
                                        <div class="prog-bar-wrap">
                                            <div class="prog-bar-fill"
                                                style="width:<?php echo e($pct); ?>%; background: linear-gradient(90deg,#f06548,#f7b84b);">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="empty-box">
                            <i class="ri-heart-line"></i>
                            <p>No match data available yet</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row g-3 mb-4">

        
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius:18px;">
                <div class="card-body">
                    <p class="sec-title">
                        <i class="ri-store-3-fill me-1 text-success"></i> Top Sellers — Most Products Listed
                        <?php if($hasPuData): ?>
                            <span class="float-end badge bg-soft-success text-success fs-11">Top
                                <?php echo e(count($puCounts)); ?></span>
                        <?php endif; ?>
                    </p>

                    <?php if($hasPuData): ?>
                        <?php $__currentLoopData = $powerUsers->take(8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $pct = round(($item->products_count / $puMax) * 100); ?>
                            <div class="prog-row">
                                <div class="d-flex align-items-center gap-3">
                                    <span
                                        class="rank-chip <?php echo e($idx < 3 ? 'rank-' . ($idx + 1) : 'rank-n'); ?>"><?php echo e($idx + 1); ?></span>
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-soft-success text-success fw-black"
                                        style="width:36px;height:36px;font-size:14px;flex-shrink:0;">
                                        <?php echo e(strtoupper(substr($item->user->name ?? 'U', 0, 1))); ?>

                                    </div>
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <div class="min-width-0">
                                                <span class="fw-semibold fs-13 text-truncate d-inline-block"
                                                    style="max-width:180px;">
                                                    <?php echo e($item->user->name ?? 'N/A'); ?>

                                                </span>
                                                <small
                                                    class="text-muted ms-1 d-none d-md-inline"><?php echo e($item->user->email ?? ''); ?></small>
                                            </div>
                                            <span
                                                class="badge bg-soft-success text-success fs-12 fw-bold ms-2 flex-shrink-0">
                                                <?php echo e(number_format($item->products_count)); ?> products
                                            </span>
                                        </div>
                                        <div class="prog-bar-wrap">
                                            <div class="prog-bar-fill"
                                                style="width:<?php echo e($pct); ?>%; background: linear-gradient(90deg,#0ab39c,#405189);">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <div class="empty-box">
                            <i class="ri-store-line"></i>
                            <p>No seller data available yet</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius:18px;">
                <div class="card-body">
                    <p class="sec-title"><i class="ri-dashboard-fill me-1 text-primary"></i> System Overview</p>
                    <?php
                        $overviewItems = [
                            [
                                'label' => 'Total Matches',
                                'value' => $totals['total_matches'],
                                'icon' => 'ri-heart-3-fill',
                                'color' => 'danger',
                            ],
                            [
                                'label' => 'Total Pickups',
                                'value' => $totals['total_pickups'],
                                'icon' => 'ri-truck-fill',
                                'color' => 'warning',
                            ],
                            [
                                'label' => 'Contact Shares',
                                'value' => $totals['total_contact_shares'],
                                'icon' => 'ri-contacts-book-fill',
                                'color' => 'info',
                            ],
                            [
                                'label' => 'Products Listed',
                                'value' => $hasPuData ? $powerUsers->sum('products_count') : 0,
                                'icon' => 'ri-box-3-fill',
                                'color' => 'success',
                            ],
                            [
                                'label' => 'Active Sellers',
                                'value' => $powerUsers->count(),
                                'icon' => 'ri-store-3-fill',
                                'color' => 'primary',
                            ],
                        ];
                    ?>
                    <?php $__currentLoopData = $overviewItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ov): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="ov-row d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 bg-soft-<?php echo e($ov['color']); ?> text-<?php echo e($ov['color']); ?> d-flex align-items-center justify-content-center"
                                    style="width:38px;height:38px;font-size:17px;flex-shrink:0;">
                                    <i class="<?php echo e($ov['icon']); ?>"></i>
                                </div>
                                <span class="fw-semibold fs-13 text-dark"><?php echo e($ov['label']); ?></span>
                            </div>
                            <span
                                class="fw-black fs-16 text-<?php echo e($ov['color']); ?>"><?php echo e(number_format($ov['value'])); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    
    <?php if($hasTmData || $hasPuData): ?>
        <div class="row g-3">
            <?php if($hasTmData): ?>
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm" style="border-radius:18px;">
                        <div class="card-body">
                            <p class="sec-title"><i class="ri-bar-chart-fill me-1 text-danger"></i> Match Count — Top
                                Products</p>
                            <div id="topMatchedBarChart" style="min-height:280px;"></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <?php if($hasPuData): ?>
                <div class="<?php echo e($hasTmData ? 'col-xl-6' : 'col-12'); ?>">
                    <div class="card border-0 shadow-sm" style="border-radius:18px;">
                        <div class="card-body">
                            <p class="sec-title"><i class="ri-bar-chart-2-fill me-1 text-success"></i> Products Listed —
                                Top Sellers</p>
                            <div id="powerUsersBarChart" style="min-height:280px;"></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts-bottom'); ?>
    <?php if($hasPickupData || $hasPuData || $hasTmData): ?>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.2/dist/apexcharts.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                if (typeof ApexCharts === 'undefined') return;

                // ── Pickup Donut ─────────────────────────────────────────────
                <?php if($hasPickupData): ?>
                    try {
                        new ApexCharts(document.getElementById('pickupDonutChart'), {
                            series: <?php echo json_encode($donutValues); ?>,
                            labels: <?php echo json_encode($donutLabels); ?>,
                            chart: {
                                type: 'donut',
                                height: 220,
                                toolbar: {
                                    show: false
                                }
                            },
                            colors: <?php echo json_encode($donutColorArr); ?>,
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '65%',
                                        labels: {
                                            show: true,
                                            total: {
                                                show: true,
                                                label: 'Total',
                                                fontSize: '12px',
                                                fontWeight: 700,
                                                color: '#6c757d',
                                                formatter: w => w.globals.seriesTotals.reduce((a, b) => a + b,
                                                    0)
                                            }
                                        }
                                    }
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            legend: {
                                position: 'bottom',
                                fontSize: '12px',
                                fontWeight: 600,
                                markers: {
                                    width: 10,
                                    height: 10,
                                    radius: 50
                                }
                            },
                            stroke: {
                                width: 2,
                                colors: ['#fff']
                            },
                            tooltip: {
                                y: {
                                    formatter: val => val + ' requests'
                                }
                            }
                        }).render();
                    } catch (e) {
                        console.error(e);
                    }
                <?php endif; ?>

                // ── Top Matched Bar ───────────────────────────────────────────
                <?php if($hasTmData): ?>
                    try {
                        new ApexCharts(document.getElementById('topMatchedBarChart'), {
                            series: [{
                                name: 'Matches',
                                data: <?php echo json_encode($tmCounts); ?>

                            }],
                            chart: {
                                type: 'bar',
                                height: 280,
                                toolbar: {
                                    show: false
                                }
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: 6,
                                    horizontal: true,
                                    barHeight: '55%'
                                }
                            },
                            colors: ['#f06548'],
                            dataLabels: {
                                enabled: true,
                                style: {
                                    fontSize: '11px',
                                    fontWeight: 700,
                                    colors: ['#fff']
                                }
                            },
                            xaxis: {
                                categories: <?php echo json_encode($tmTitles); ?>,
                                labels: {
                                    style: {
                                        fontSize: '11px',
                                        fontWeight: 600
                                    }
                                }
                            },
                            yaxis: {
                                labels: {
                                    style: {
                                        fontSize: '11px',
                                        fontWeight: 600
                                    }
                                }
                            },
                            grid: {
                                borderColor: '#f1f3f9',
                                xaxis: {
                                    lines: {
                                        show: true
                                    }
                                }
                            },
                            tooltip: {
                                y: {
                                    formatter: val => val + ' matches'
                                }
                            }
                        }).render();
                    } catch (e) {
                        console.error(e);
                    }
                <?php endif; ?>

                // ── Top Sellers Bar ───────────────────────────────────────────
                <?php if($hasPuData): ?>
                    try {
                        new ApexCharts(document.getElementById('powerUsersBarChart'), {
                            series: [{
                                name: 'Products',
                                data: <?php echo json_encode($puCounts); ?>

                            }],
                            chart: {
                                type: 'bar',
                                height: 280,
                                toolbar: {
                                    show: false
                                }
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: 6,
                                    horizontal: true,
                                    barHeight: '55%'
                                }
                            },
                            colors: ['#0ab39c'],
                            dataLabels: {
                                enabled: true,
                                style: {
                                    fontSize: '11px',
                                    fontWeight: 700,
                                    colors: ['#fff']
                                }
                            },
                            xaxis: {
                                categories: <?php echo json_encode($puNames); ?>,
                                labels: {
                                    style: {
                                        fontSize: '11px',
                                        fontWeight: 600
                                    }
                                }
                            },
                            yaxis: {
                                labels: {
                                    style: {
                                        fontSize: '11px',
                                        fontWeight: 600
                                    }
                                }
                            },
                            grid: {
                                borderColor: '#f1f3f9',
                                xaxis: {
                                    lines: {
                                        show: true
                                    }
                                }
                            },
                            tooltip: {
                                y: {
                                    formatter: val => val + ' products'
                                }
                            }
                        }).render();
                    } catch (e) {
                        console.error(e);
                    }
                <?php endif; ?>

            });
        </script>
    <?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('backend.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\88013\Herd\mastering_laravelRo\resources\views/backend/messaging/analytics.blade.php ENDPATH**/ ?>