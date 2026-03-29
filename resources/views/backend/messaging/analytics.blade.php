@extends('backend.master')
@section('title', 'Messaging Analytics')

@push('styles-bottom')
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
@endpush

@section('content')

    {{-- Page Title --}}
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

    @php
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
    @endphp

    {{-- ══ KPI CARDS ══════════════════════════════════════════════════ --}}
    <div class="row g-3 mb-4">
        @php
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
        @endphp
        @foreach ($kpis as $kpi)
            <div class="col-xl-3 col-md-6">
                <div class="card kpi-card shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3 py-4">
                        <div class="kpi-icon-box bg-soft-{{ $kpi['color'] }} text-{{ $kpi['color'] }}">
                            <i class="{{ $kpi['icon'] }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="text-muted mb-0 fs-12 fw-bold text-uppercase">{{ $kpi['label'] }}</p>
                            <h2 class="mb-0 fw-black text-{{ $kpi['color'] }}">{{ number_format($kpi['value']) }}</h2>
                            <p class="text-muted mb-0 fs-11 mt-1">{{ $kpi['desc'] }}</p>
                        </div>
                        <i class="{{ $kpi['icon'] }} kpi-bg-icon text-{{ $kpi['color'] }}"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ══ ROW 2: Pickup Breakdown + Top Matched Products ══════════════ --}}
    <div class="row g-3 mb-4">

        {{-- Pickup Breakdown --}}
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius:18px;">
                <div class="card-body">
                    <p class="sec-title"><i class="ri-truck-fill me-1 text-warning"></i> Pickup Request Breakdown</p>

                    @if ($pickupStats->isEmpty())
                        <div class="empty-box">
                            <i class="ri-truck-line"></i>
                            <p>No pickup data yet</p>
                        </div>
                    @else
                        {{-- Mini stat boxes --}}
                        <div class="d-flex gap-2 mb-4 flex-wrap">
                            @foreach ($pickupStats as $ps)
                                @php $cfg = $pickupStatusConfig[$ps->status] ?? ['soft'=>'bg-light','text'=>'text-dark','label'=>ucfirst($ps->status),'color'=>'#adb5bd']; @endphp
                                <div class="pstat-box {{ $cfg['soft'] }}">
                                    <div class="pstat-num {{ $cfg['text'] }}">{{ number_format($ps->count) }}</div>
                                    <div class="pstat-label {{ $cfg['text'] }}">{{ $cfg['label'] }}</div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Progress bars per status --}}
                        @foreach ($pickupStats as $ps)
                            @php
                                $cfg = $pickupStatusConfig[$ps->status] ?? [
                                    'label' => ucfirst($ps->status),
                                    'color' => '#adb5bd',
                                ];
                                $pct = round(($ps->count / $totalPickups) * 100);
                            @endphp
                            <div class="prog-row">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-semibold fs-13">
                                        <span
                                            style="display:inline-block;width:9px;height:9px;border-radius:50%;background:{{ $cfg['color'] }};vertical-align:middle;margin-right:6px;"></span>
                                        {{ $cfg['label'] }}
                                    </span>
                                    <span class="fw-bold fs-12 text-muted">{{ $pct }}%&nbsp;
                                        ({{ $ps->count }})</span>
                                </div>
                                <div class="prog-bar-wrap">
                                    <div class="prog-bar-fill"
                                        style="width:{{ $pct }}%; background:{{ $cfg['color'] }};"></div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Donut chart --}}
                        @if ($hasPickupData)
                            <div id="pickupDonutChart" class="mt-3" style="min-height:220px;"></div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- Top Matched Products --}}
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius:18px;">
                <div class="card-body">
                    <p class="sec-title">
                        <i class="ri-heart-3-fill me-1 text-danger"></i> Top Matched Products
                        @if ($hasTmData)
                            <span class="float-end badge bg-soft-danger text-danger fs-11">Top
                                {{ count($tmCounts) }}</span>
                        @endif
                    </p>

                    @if ($hasTmData)
                        @foreach ($topMatchedProducts->take(8) as $idx => $item)
                            @php $pct = round(($item->matches_count / $tmMax) * 100); @endphp
                            <div class="prog-row">
                                <div class="d-flex align-items-center gap-3">
                                    <span
                                        class="rank-chip {{ $idx < 3 ? 'rank-' . ($idx + 1) : 'rank-n' }}">{{ $idx + 1 }}</span>
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-semibold fs-13 text-truncate" style="max-width:260px;">
                                                {{ $item->product->title ?? 'Deleted Product' }}
                                            </span>
                                            <span class="badge bg-soft-danger text-danger fs-12 fw-bold ms-2 flex-shrink-0">
                                                {{ number_format($item->matches_count) }} matches
                                            </span>
                                        </div>
                                        <div class="prog-bar-wrap">
                                            <div class="prog-bar-fill"
                                                style="width:{{ $pct }}%; background: linear-gradient(90deg,#f06548,#f7b84b);">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-box">
                            <i class="ri-heart-line"></i>
                            <p>No match data available yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ══ ROW 3: Top Sellers + System Overview ════════════════════════ --}}
    <div class="row g-3 mb-4">

        {{-- Top Sellers --}}
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius:18px;">
                <div class="card-body">
                    <p class="sec-title">
                        <i class="ri-store-3-fill me-1 text-success"></i> Top Sellers — Most Products Listed
                        @if ($hasPuData)
                            <span class="float-end badge bg-soft-success text-success fs-11">Top
                                {{ count($puCounts) }}</span>
                        @endif
                    </p>

                    @if ($hasPuData)
                        @foreach ($powerUsers->take(8) as $idx => $item)
                            @php $pct = round(($item->products_count / $puMax) * 100); @endphp
                            <div class="prog-row">
                                <div class="d-flex align-items-center gap-3">
                                    <span
                                        class="rank-chip {{ $idx < 3 ? 'rank-' . ($idx + 1) : 'rank-n' }}">{{ $idx + 1 }}</span>
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-soft-success text-success fw-black"
                                        style="width:36px;height:36px;font-size:14px;flex-shrink:0;">
                                        {{ strtoupper(substr($item->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <div class="min-width-0">
                                                <span class="fw-semibold fs-13 text-truncate d-inline-block"
                                                    style="max-width:180px;">
                                                    {{ $item->user->name ?? 'N/A' }}
                                                </span>
                                                <small
                                                    class="text-muted ms-1 d-none d-md-inline">{{ $item->user->email ?? '' }}</small>
                                            </div>
                                            <span
                                                class="badge bg-soft-success text-success fs-12 fw-bold ms-2 flex-shrink-0">
                                                {{ number_format($item->products_count) }} products
                                            </span>
                                        </div>
                                        <div class="prog-bar-wrap">
                                            <div class="prog-bar-fill"
                                                style="width:{{ $pct }}%; background: linear-gradient(90deg,#0ab39c,#405189);">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-box">
                            <i class="ri-store-line"></i>
                            <p>No seller data available yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- System Overview --}}
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius:18px;">
                <div class="card-body">
                    <p class="sec-title"><i class="ri-dashboard-fill me-1 text-primary"></i> System Overview</p>
                    @php
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
                    @endphp
                    @foreach ($overviewItems as $ov)
                        <div class="ov-row d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-3 bg-soft-{{ $ov['color'] }} text-{{ $ov['color'] }} d-flex align-items-center justify-content-center"
                                    style="width:38px;height:38px;font-size:17px;flex-shrink:0;">
                                    <i class="{{ $ov['icon'] }}"></i>
                                </div>
                                <span class="fw-semibold fs-13 text-dark">{{ $ov['label'] }}</span>
                            </div>
                            <span
                                class="fw-black fs-16 text-{{ $ov['color'] }}">{{ number_format($ov['value']) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ══ ROW 4: ApexCharts Bar Charts ═══════════════════════════════ --}}
    @if ($hasTmData || $hasPuData)
        <div class="row g-3">
            @if ($hasTmData)
                <div class="col-xl-6">
                    <div class="card border-0 shadow-sm" style="border-radius:18px;">
                        <div class="card-body">
                            <p class="sec-title"><i class="ri-bar-chart-fill me-1 text-danger"></i> Match Count — Top
                                Products</p>
                            <div id="topMatchedBarChart" style="min-height:280px;"></div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($hasPuData)
                <div class="{{ $hasTmData ? 'col-xl-6' : 'col-12' }}">
                    <div class="card border-0 shadow-sm" style="border-radius:18px;">
                        <div class="card-body">
                            <p class="sec-title"><i class="ri-bar-chart-2-fill me-1 text-success"></i> Products Listed —
                                Top Sellers</p>
                            <div id="powerUsersBarChart" style="min-height:280px;"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

@endsection

@push('scripts-bottom')
    @if ($hasPickupData || $hasPuData || $hasTmData)
        <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.2/dist/apexcharts.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                if (typeof ApexCharts === 'undefined') return;

                // ── Pickup Donut ─────────────────────────────────────────────
                @if ($hasPickupData)
                    try {
                        new ApexCharts(document.getElementById('pickupDonutChart'), {
                            series: {!! json_encode($donutValues) !!},
                            labels: {!! json_encode($donutLabels) !!},
                            chart: {
                                type: 'donut',
                                height: 220,
                                toolbar: {
                                    show: false
                                }
                            },
                            colors: {!! json_encode($donutColorArr) !!},
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
                @endif

                // ── Top Matched Bar ───────────────────────────────────────────
                @if ($hasTmData)
                    try {
                        new ApexCharts(document.getElementById('topMatchedBarChart'), {
                            series: [{
                                name: 'Matches',
                                data: {!! json_encode($tmCounts) !!}
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
                                categories: {!! json_encode($tmTitles) !!},
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
                @endif

                // ── Top Sellers Bar ───────────────────────────────────────────
                @if ($hasPuData)
                    try {
                        new ApexCharts(document.getElementById('powerUsersBarChart'), {
                            series: [{
                                name: 'Products',
                                data: {!! json_encode($puCounts) !!}
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
                                categories: {!! json_encode($puNames) !!},
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
                @endif

            });
        </script>
    @endif
@endpush
