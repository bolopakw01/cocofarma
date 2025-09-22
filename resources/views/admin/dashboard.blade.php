@extends('admin.layouts.app')

@php
    $pageTitle = 'Dashboard';
    // Data dinamis untuk kartu
    $totalPesananBaru = \App\Models\Pesanan::where('status', 'baru')->count() ?? 0;
    $produkTerjual = \App\Models\TransaksiItem::sum('jumlah') ?? 0; // Asumsikan TransaksiItem memiliki jumlah terjual
    $totalProduksi = \App\Models\Produksi::count() ?? 0;
    $totalUser = \App\Models\User::count() ?? 0;

    // Ambil goals dari pengaturan
    $goalsSetting = \App\Models\Pengaturan::where('nama_pengaturan', 'dashboard_goals')->first();
    $goals = [];
    if ($goalsSetting) {
        $decoded = json_decode($goalsSetting->nilai, true);
        if (is_array($decoded) && !empty($decoded)) $goals = $decoded;
    }
    // Jika tidak ada goals, gunakan default kosong untuk placeholder
    if (empty($goals)) {
        $goals = []; // Kosongkan agar tampil placeholder
    }

    // Hitung value untuk setiap goal
    foreach ($goals as &$goal) {
        $key = $goal['key'] ?? '';
        $value = 0;
        switch ($key) {
            case 'produk':
                $value = \App\Models\Produk::count() ?? 0;
                break;
            case 'penjualan':
                $value = \App\Models\Transaksi::sum('total') ?? 0;
                break;
            case 'bahan_baku':
                $value = \App\Models\MasterBahanBaku::count() ?? 0;
                break;
            case 'produksi':
                $value = \App\Models\Produksi::whereDate('created_at', today())->count() ?? 0;
                break;
            case 'packing':
                // Asumsikan packing adalah bagian dari produksi atau model lain
                $value = \App\Models\Produksi::where('status', 'packed')->whereDate('created_at', today())->count() ?? 0;
                break;
            case 'qc':
                $value = \App\Models\Produksi::where('status', 'qc_passed')->whereDate('created_at', today())->count() ?? 0;
                break;
            // Tambahkan case lain jika perlu
        }
        $goal['value'] = $value;
        $goal['pct'] = $goal['target'] > 0 ? round(($value / $goal['target']) * 100) : 0;
    }

    // Filter range untuk grafik
    $range = request('range', 'weekly');
    switch ($range) {
        case 'monthly':
            $start = now()->startOfMonth();
            $end = now()->endOfMonth();
            $days = $end->diffInDays($start) + 1;
            $penjualanData = [];
            $produksiData = [];
            $pesananData = [];
            for ($i = 0; $i < $days; $i++) {
                $date = $start->copy()->addDays($i);
                $penjualanData[] = \App\Models\Transaksi::whereDate('tanggal_transaksi', $date)->sum('total') ?? 0;
                $produksiData[] = \App\Models\Produksi::whereDate('created_at', $date)->count() ?? 0;
                $pesananData[] = \App\Models\Pesanan::whereDate('created_at', $date)->where('status', 'baru')->count() ?? 0;
            }
            $categories = range(1, $days);
            $title = 'Ringkasan Bulanan: Penjualan, Produksi & Pesanan';
            break;
        case 'yearly':
            $penjualanData = [];
            $produksiData = [];
            $pesananData = [];
            $categories = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
            for ($i = 0; $i < 12; $i++) {
                $month = $i + 1;
                $penjualanData[] = \App\Models\Transaksi::whereYear('tanggal_transaksi', now()->year)->whereMonth('tanggal_transaksi', $month)->sum('total') ?? 0;
                $produksiData[] = \App\Models\Produksi::whereYear('created_at', now()->year)->whereMonth('created_at', $month)->count() ?? 0;
                $pesananData[] = \App\Models\Pesanan::whereYear('created_at', now()->year)->whereMonth('created_at', $month)->where('status', 'baru')->count() ?? 0;
            }
            $title = 'Ringkasan Tahunan: Penjualan, Produksi & Pesanan';
            break;
        default: // weekly
            $penjualanData = [];
            $produksiData = [];
            $pesananData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->toDateString();
                $penjualanData[] = \App\Models\Transaksi::whereDate('tanggal_transaksi', $date)->sum('total') ?? 0;
                $produksiData[] = \App\Models\Produksi::whereDate('created_at', $date)->count() ?? 0;
                $pesananData[] = \App\Models\Pesanan::whereDate('created_at', $date)->where('status', 'baru')->count() ?? 0;
            }
            $categories = ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
            $title = 'Ringkasan Harian: Penjualan, Produksi & Pesanan';
            break;
    }

    // Data untuk last orders
    $lastOrders = \App\Models\Pesanan::with('user')->latest()->take(10)->get()->map(function($order) {
        return [
            'id' => $order->kode_pesanan ?? '#ORD-' . $order->id,
            'amount' => 'Rp ' . number_format($order->total ?? 0, 0, ',', '.'),
            'time' => $order->created_at->diffForHumans(),
            'status' => $order->status
        ];
    });

    // Data bahan baku terbaru
    $bahanBaku = \App\Models\MasterBahanBaku::latest()->take(5)->get();
@endphp

@section('styles')
<link rel="stylesheet" href="{{ asset('bolopa/back/css/admin-dashboard.css') }}">
@endsection

@section('content')
<div class="container py-4">
  <div class="row g-3">
    <!-- Card 1 -->
    <div class="col-12 col-sm-6 col-md-3">
      <div class="card text-white bolopa-bg-teal h-100 overflow-hidden" role="region" aria-labelledby="card1-title">
        <div class="card-body d-flex align-items-start justify-content-between">
          <div>
            <h2 id="card1-title" class="fw-bold display-6 mb-0" aria-label="{{ $totalPesananBaru }} pesanan baru">{{ $totalPesananBaru }}</h2>
            <div class="small opacity-85">Pesanan Baru</div>
          </div>
          <div class="bolopa-card-icon">
            <i class="fas fa-shopping-bag fa-3x" aria-hidden="true"></i>
          </div>
        </div>
        <a href="#" class="card-footer text-white-50 text-decoration-none" aria-label="Informasi lebih lanjut tentang Pesanan Baru">
          <div class="d-flex justify-content-between align-items-center px-3 py-2">
            <span>Informasi lebih lanjut</span>
            <i class="fas fa-arrow-circle-right" aria-hidden="true"></i>
          </div>
        </a>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="col-12 col-sm-6 col-md-3">
      <div class="card text-white bolopa-bg-success-variant h-100 overflow-hidden" role="region" aria-labelledby="card2-title">
        <div class="card-body d-flex align-items-start justify-content-between">
          <div>
            <h2 id="card2-title" class="fw-bold display-6 mb-0" aria-label="{{ $produkTerjual }} produk terjual">{{ $produkTerjual }}</h2>
            <div class="small opacity-85">Produk Terjual</div>
          </div>
          <div class="bolopa-card-icon">
            <i class="fas fa-box fa-3x" aria-hidden="true"></i>
          </div>
        </div>
        <a href="#" class="card-footer text-white-50 text-decoration-none" aria-label="Informasi lebih lanjut tentang Produk Terjual">
          <div class="d-flex justify-content-between align-items-center px-3 py-2">
            <span>Informasi lebih lanjut</span>
            <i class="fas fa-arrow-circle-right" aria-hidden="true"></i>
          </div>
        </a>
      </div>
    </div>

    <!-- Card 3 -->
    <div class="col-12 col-sm-6 col-md-3">
      <div class="card text-dark bolopa-bg-warning-variant h-100 overflow-hidden" role="region" aria-labelledby="card3-title">
        <div class="card-body d-flex align-items-start justify-content-between">
          <div>
            <h2 id="card3-title" class="fw-bold display-6 mb-0" aria-label="{{ $totalProduksi }} produksi">{{ $totalProduksi }}</h2>
            <div class="small opacity-85">Produksi</div>
          </div>
          <div class="bolopa-card-icon text-dark">
            <i class="fas fa-cogs fa-3x" aria-hidden="true"></i>
          </div>
        </div>
        <a href="#" class="card-footer text-dark-50 text-decoration-none" aria-label="Informasi lebih lanjut tentang Produksi">
          <div class="d-flex justify-content-between align-items-center px-3 py-2">
            <span>Informasi lebih lanjut</span>
            <i class="fas fa-arrow-circle-right" aria-hidden="true"></i>
          </div>
        </a>
      </div>
    </div>

    <!-- Card 4 -->
    <div class="col-12 col-sm-6 col-md-3">
      <div class="card text-white bolopa-bg-danger-variant h-100 overflow-hidden" role="region" aria-labelledby="card4-title">
        <div class="card-body d-flex align-items-start justify-content-between">
          <div>
            <h2 id="card4-title" class="fw-bold display-6 mb-0" aria-label="{{ $totalUser }} user">{{ $totalUser }}</h2>
            <div class="small opacity-85">User Aktif</div>
          </div>
          <div class="bolopa-card-icon">
            <i class="fas fa-users fa-3x" aria-hidden="true"></i>
          </div>
        </div>
        <a href="#" class="card-footer text-white-50 text-decoration-none" aria-label="Informasi lebih lanjut tentang User Aktif">
          <div class="d-flex justify-content-between align-items-center px-3 py-2">
            <span>Informasi lebih lanjut</span>
            <i class="fas fa-arrow-circle-right" aria-hidden="true"></i>
          </div>
        </a>
      </div>
    </div>

  </div>
</div>

<!-- Main chart section with right-side Goals Completion card -->
<div class="container py-4">
  <div class="row g-3">
    <div class="col-12 col-lg-8">
      <div class="card mb-4 no-hover-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Grafik Utama</h5>
            <div class="btn-group bolopa-btn-filter" role="group" aria-label="Filter grafik">
              <button type="button" class="btn btn-sm btn-outline-primary {{ $range == 'weekly' ? 'active' : '' }}" data-range="weekly">Harian</button>
              <button type="button" class="btn btn-sm btn-outline-primary {{ $range == 'monthly' ? 'active' : '' }}" data-range="monthly">Bulanan</button>
              <button type="button" class="btn btn-sm btn-outline-primary {{ $range == 'yearly' ? 'active' : '' }}" data-range="yearly">Tahunan</button>
            </div>
          </div>
          <div id="main-chart"></div>
          <div class="stats-strip-wrapper mt-3">
            <div class="stats-strip">
              <div class="stats-item" data-dir="up">
                <div class="stat-top">
                  <img class="stat-icon" aria-hidden="true" src="{{ asset('bolopa/back/images/icon/line-md--hazard-lights-loop.svg') }}" width="16" height="16" alt="indikator">
                  <div class="pct text-success">17%</div>
                </div>
                <div class="amount">Rp 35.210.43</div>
                <div class="label">TOTAL PENDAPATAN</div>
              </div>
              <div class="stats-item" data-dir="same">
                <div class="stat-top">
                  <img class="stat-icon" aria-hidden="true" src="{{ asset('bolopa/back/images/icon/line-md--hazard-lights-loop.svg') }}" width="16" height="16" alt="indikator">
                  <div class="pct text-warning">0%</div>
                </div>
                <div class="amount">Rp 10.390.90</div>
                <div class="label">TOTAL BIAYA</div>
              </div>
              <div class="stats-item" data-dir="up">
                <div class="stat-top">
                  <img class="stat-icon" aria-hidden="true" src="{{ asset('bolopa/back/images/icon/line-md--hazard-lights-loop.svg') }}" width="16" height="16" alt="indikator">
                  <div class="pct text-success">20%</div>
                </div>
                <div class="amount">Rp 24.813.53</div>
                <div class="label">TOTAL LABA</div>
              </div>
              <div class="stats-item" data-dir="down">
                <div class="stat-top">
                  <img class="stat-icon" aria-hidden="true" src="{{ asset('bolopa/back/images/icon/line-md--hazard-lights-loop.svg') }}" width="16" height="16" alt="indikator">
                  <div class="pct text-danger">18%</div>
                </div>
                <div class="amount">1200</div>
                <div class="label">PENYELESAIAN TUJUAN</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card mb-4 bolopa-no-hover">
        <div class="card-body">
          <div class="bolopa-goals-panel bolopa-right-column">
            <div class="bolopa-goals-inner">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Penyelesaian Tujuan</h6>
                <a href="{{ route('backoffice.pengaturan.goals') }}" class="text-white text-decoration-none" title="Atur Goals" style="font-size: 0.875rem;">
                  <i class="fas fa-cog text-white me-1"></i>Atur
                </a>
              </div>
              @if(empty($goals))
              <div class="text-center py-4">
                <i class="fas fa-bullseye fa-3x text-white mb-3"></i>
                <p class="text-white">Belum ada tujuan yang ditetapkan</p>
              </div>
              @else
              @foreach($goals as $goal)
              <div class="bolopa-goal-item mb-3">
                <div class="d-flex justify-content-between">
                  <div class="small">{{ $goal['label'] }}</div>
                  <div class="fw-bold">{{ $goal['value'] ?? 0 }} / {{ $goal['target'] }}</div>
                </div>
                <div class="progress mt-2">
                  <div class="bolopa-goal-bar bolopa-bar-{{ $goal['color'] ?? 'green' }}" role="progressbar" data-value="{{ $goal['value'] ?? 0 }}" data-target="{{ $goal['target'] }}" data-pct="{{ $goal['pct'] ?? 0 }}" style="width:{{ $goal['pct'] ?? 0 }}%"></div>
                </div>
              </div>
              @endforeach
              @endif
            </div>

            <div class="bolopa-goals-inner bolopa-last-orders">
              <h6 class="mb-3">Pesanan Terakhir</h6>
              <div class="bolopa-last-orders-wrapper">
                <div id="last-orders-list" class="bolopa-last-orders-list" aria-live="polite" role="list"></div>
                <div id="more-orders-wrap" class="text-center d-none mt-2 bolopa-more-orders-wrap">
                  <button id="load-more-orders" class="btn btn-sm btn-outline-light" type="button">
                    <i class="fas fa-plus-circle me-1"></i>Muat lebih banyak
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Tabel Bahan Baku Terbaru dan Grafik Radar Performance -->
<div class="container py-1">
  <div class="row g-3">
    <div class="col-12 col-lg-8">
      <div class="card">
        <div class="card-header bg-info text-white">
          <h5 class="mb-0"><i class="fas fa-boxes me-2" aria-hidden="true"></i>Daftar Bahan Baku Terbaru</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
              <thead class="table-light">
                <tr>
                  <th>Nama Bahan Baku</th>
                  <th>Satuan</th>
                  <th>Total Stok</th>
                </tr>
              </thead>
              <tbody>
                @forelse($bahanBaku as $bahan)
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <div class="avatar-sm bg-secondary text-white me-2" aria-hidden="true">{{ strtoupper(substr($bahan->nama_bahan,0,1)) }}</div>
                      <div>
                        <div class="fw-bold">{{ $bahan->nama_bahan }}</div>
                        @if(!empty($bahan->kode_bahan))
                          <div class="text-muted small">{{ $bahan->kode_bahan }}</div>
                        @endif
                      </div>
                    </div>
                  </td>
                  <td>{{ $bahan->satuan }}</td>
                  <td>
                    @php $lowThreshold = $bahan->minimum_stok ?? 10; @endphp
                    <span class="fw-bold {{ ($bahan->total_stok <= $lowThreshold) ? 'text-danger' : 'text-dark' }}">{{ $bahan->total_stok }}</span>
                    @if($bahan->total_stok <= $lowThreshold)
                      <span class="badge bg-danger ms-2">Rendah</span>
                    @endif
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="3" class="text-center py-4">
                    <div class="d-flex flex-column align-items-center">
                      <i class="fas fa-box-open fa-3x text-muted mb-2" aria-hidden="true"></i>
                      <div class="text-muted mb-2">Belum ada data bahan baku</div>
                      <a href="{{ route('backoffice.master-bahan.create') }}" class="btn btn-sm btn-primary">Tambah Bahan Baku</a>
                    </div>
                  </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4">
      <div class="card">
        <div class="card-body">
          <h5 class="mb-3 text-center">Grafik Performance</h5>
          <div id="radar-chart"></div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// main.js - initializes ApexCharts and handles filter buttons
(function(){
  function rand(max=100){ return Math.floor(Math.random()*max); }

  // sample dataset generators
  function generateDaily(){
    // 24 points (hours)
    return Array.from({length:24}, ()=>rand(200));
  }
  function generateWeekly(){
    // 7 days
    return Array.from({length:7}, ()=>rand(800));
  }
  function generateMonthly(){
    // 30 days
    return Array.from({length:30}, ()=>rand(1000));
  }
  function generateYearly(){
    // 12 months
    return Array.from({length:12}, ()=>rand(5000));
  }

  // labels generator
  function labelsFor(range){
    if(range==='daily') return Array.from({length:24}, (_,i)=>i+':00');
    if(range==='weekly') return ['Sen','Sel','Rab','Kam','Jum','Sab','Min'];
    if(range==='monthly') return Array.from({length:30}, (_,i)=>String(i+1));
    if(range==='yearly') return ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
    return [];
  }

  // create chart with improved styling and two series
  var palette = { visits: '#0d6efd', conv: '#20c997' };
  // Data dari PHP
  var penjualanData = @json($penjualanData);
  var produksiData = @json($produksiData);
  var pesananData = @json($pesananData);

  var options = {
    chart: {
      height: 380,
      type: 'area',
      animations: { enabled: true, easing: 'easeinout', speed: 800, animateGradually: { enabled: true, delay: 150 } },
      toolbar: { show: false },
      zoom: { enabled: false },
      dropShadow: { enabled: true, top: 10, left: 0, blur: 15, color: 'rgba(13,110,253,0.2)', opacity: 0.8 },
      background: 'transparent'
    },
    series: [
      { name: 'Penjualan', data: penjualanData },
      { name: 'Produksi', data: produksiData },
      { name: 'Pesanan Baru', data: pesananData }
    ],
    colors: ['#007bff', '#28a745', '#ffc107'],
    stroke: { curve: 'smooth', width: 4, lineCap: 'round' },
    markers: { size: 5, colors: ['#007bff'], strokeColors: '#fff', strokeWidth: 3, hover: { size: 8 } },
    fill: {
      type: 'gradient',
      gradient: {
        shade: 'light',
        type: 'vertical',
        shadeIntensity: 0.5,
        gradientToColors: ['#28a745'],
        inverseColors: false,
        opacityFrom: 0.6,
        opacityTo: 0.1,
        stops: [0, 50, 100]
      }
    },
    xaxis: {
      categories: @json($categories),
      labels: { style: { colors: '#6c757d', fontSize: '13px', fontWeight: 500 } },
      axisBorder: { show: false },
      axisTicks: { show: false }
    },
    yaxis: {
      labels: { style: { colors: '#6c757d', fontSize: '12px' }, formatter: function(val) { return 'Rp ' + (val / 1000).toFixed(0) + 'k'; } },
      tickAmount: 5
    },
    tooltip: {
      shared: true,
      intersect: false,
      theme: 'light',
      x: { show: true },
      y: {
        formatter: function(val, opts) {
          if (opts.seriesIndex === 0) {
            return 'Rp ' + val.toLocaleString('id-ID');
          } else {
            return val + ' unit';
          }
        }
      },
      marker: { show: true }
    },
    grid: { borderColor: '#e9ecef', strokeDashArray: 2, xaxis: { lines: { show: false } }, yaxis: { lines: { show: true } } },
    dataLabels: { enabled: false },
    legend: { show: true, position: 'bottom', horizontalAlign: 'center', offsetY: 10, markers: { width: 12, height: 12, radius: 6 } },
    title: { text: '{{ $title }}', align: 'left', style: { fontSize: '18px', fontWeight: 700, color: '#495057' } },
    responsive: [{
      breakpoint: 1200,
      options: {
        chart: { height: 350 },
        title: { style: { fontSize: '17px' } }
      }
    }, {
      breakpoint: 768,
      options: {
        chart: { height: 300 },
        title: { style: { fontSize: '16px' } },
        xaxis: { labels: { style: { fontSize: '11px' } } },
        yaxis: { labels: { style: { fontSize: '11px' } } }
      }
    }, {
      breakpoint: 576,
      options: {
        chart: { height: 250 },
        title: { style: { fontSize: '14px' } },
        xaxis: { labels: { style: { fontSize: '10px' } } },
        yaxis: { labels: { style: { fontSize: '10px' } } },
        legend: { position: 'top', horizontalAlign: 'center', offsetY: -5 }
      }
    }]
  };

  var chart = new ApexCharts(document.querySelector('#main-chart'), options);
  chart.render();

  // Radar Chart for Performance
  var radarOptions = {
    chart: {
      height: 350,
      type: 'radar',
      toolbar: { show: false }
    },
    series: [{
      name: 'Aktual',
      data: [80, 90, 70, 85, 75, 88]
    }, {
      name: 'Target',
      data: [100, 100, 100, 100, 100, 100]
    }, {
      name: 'Rata-rata Industri',
      data: [75, 85, 80, 90, 70, 82]
    }],
    labels: ['Produksi', 'Penjualan', 'Stok', 'Kualitas', 'Efisiensi', 'Keuangan'],
    plotOptions: {
      radar: {
        size: 140,
        polygons: {
          strokeColors: '#e9ecef',
          fill: {
            colors: ['#f8f9fa', '#fff']
          }
        }
      }
    },
    colors: ['#007bff', '#28a745', '#ffc107'],
    markers: {
      size: 4,
      colors: ['#fff', '#fff', '#fff'],
      strokeColor: ['#007bff', '#28a745', '#ffc107'],
      strokeWidth: 2,
    },
    tooltip: {
      y: {
        formatter: function(val) {
          return val + '%';
        }
      }
    },
    yaxis: {
      tickAmount: 7,
      labels: {
        formatter: function(val, i) {
          if (i % 2 === 0) {
            return val;
          } else {
            return '';
          }
        }
      }
    }
  };

  var radarChart = new ApexCharts(document.querySelector('#radar-chart'), radarOptions);
  radarChart.render();

  // handle filter buttons - support both original and bolopa-prefixed container
  var btnGroup = document.querySelector('.btn-filter') || document.querySelector('.bolopa-btn-filter');
  var btns = btnGroup ? btnGroup.querySelectorAll('button') : document.querySelectorAll('.btn-filter button');
  function setActive(btn){
    btns.forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
  }

  btns.forEach(b=>{
    b.addEventListener('click', function(e){
      var range = this.getAttribute('data-range');
      setActive(this);
      window.location.href = '?range=' + range;
    });
  });

  // update stats icon orientation based on percent changes
  function parseNumber(text){
    if(!text) return 0;
    // remove non-digit and non-dot
    var n = text.replace(/[^0-9.\-]/g,'');
    return parseFloat(n) || 0;
  }

  function updateStatsDirection(){
    var items = document.querySelectorAll('.stats-item');
    items.forEach(function(it){
      var pctEl = it.querySelector('.pct');
      if(!pctEl) return;
      var cur = parseNumber(pctEl.textContent || pctEl.innerText);
      var prevAttr = it.getAttribute('data-prev');
      var prev = parseNumber(prevAttr);
      if(prevAttr === null || prevAttr === undefined || prevAttr === ''){
        // no previous value: initialize data-prev but do not override a manual data-dir
        it.setAttribute('data-prev', String(cur));
        return;
      }
      var dir = 'same';
      if(cur > prev) dir = 'up';
      if(cur < prev) dir = 'down';
      it.setAttribute('data-dir', dir);
      it.setAttribute('data-prev', String(cur));
    });
  }

  // call once on load to ensure icons match initial data
  setTimeout(updateStatsDirection, 150);

  // Goals data from PHP
  var goals = @json($goals);

  function animateGoals(){
    // animate goal bar elements according to data-value/data-target (show value/target)
    // support both original class names and bolopa-prefixed ones
    var bars = Array.from(document.querySelectorAll('.goals-panel .goal-bar'))
      .concat(Array.from(document.querySelectorAll('.bolopa-goals-panel .bolopa-goal-bar')));
    bars.forEach((bar, idx)=>{
      // prefer explicit value/target attributes
      var valAttr = bar.getAttribute('data-value');
      var tgtAttr = bar.getAttribute('data-target');
      var pctAttr = bar.getAttribute('data-pct');

      var value = (valAttr !== null) ? parseFloat(valAttr) : null;
      var target = (tgtAttr !== null) ? parseFloat(tgtAttr) : null;

      var pct = 0;
      if(value !== null && target !== null && target > 0){
        pct = Math.round((value / target) * 100);
      } else if(pctAttr !== null){
        pct = parseInt(pctAttr || '0', 10);
      }

      // clamp
      if(pct < 0) pct = 0;
      if(pct > 100) pct = 100;

      bar.style.width = pct + '%';
      bar.setAttribute('aria-valuenow', pct);

      // update the displayed text to show value / target if available
      // find the closest container, accomodating both class naming schemes
      var container = bar.closest('.goal-item') || bar.closest('.bolopa-goal-item');
      if(container){
        var labelEl = container.querySelector('.fw-bold');
        if(labelEl){
          if(value !== null && target !== null){
            labelEl.textContent = value + ' / ' + target;
          } else if(pctAttr !== null){
            labelEl.textContent = pct + '%';
          }
        }
      }
    });
  }

  // run initial animation once chart is rendered
  setTimeout(animateGoals, 300);

  /* Last Orders - render and lazy load more */
  var allOrders = @json($lastOrders);

  // If no orders, show placeholder
  if(allOrders.length === 0){
    var listEl = document.getElementById('last-orders-list');
    if(listEl){
      listEl.innerHTML = '<div class="text-center py-4"><i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i><p class="text-muted">Belum ada pesanan</p><a href="' + '{{ route("backoffice.pesanan.index") }}' + '" class="btn btn-primary btn-sm">Lihat Pesanan</a></div>';
    }
    return;
  }

  var listEl = document.getElementById('last-orders-list');
  var moreWrap = document.getElementById('more-orders-wrap');
  var loadMoreBtn = document.getElementById('load-more-orders');

  if(listEl && moreWrap && loadMoreBtn){
  var rendered = 0;
  var pageSize = 5; // render 5 orders per page as requested

    function renderNext(){
      var end = Math.min(rendered + pageSize, allOrders.length);
      for(var j=rendered;j<end;j++){
        var o = allOrders[j];
          var row = document.createElement('div');
          // use bolopa-prefixed role when inside bolopa-last-orders-list, but keep generic class for styling compatibility
          row.className = 'order-row';
        // avatar initial
        var initial = o.id.replace(/[^A-Z]/g,'').charAt(0) || 'O';
        row.innerHTML = '<div style="display:flex;align-items:center;">'
          + '<div class="avatar-sm bg-secondary text-white">'+ initial +'</div>'
          + '<div>'
            + '<a class="order-link" href="' + '{{ route("backoffice.pesanan.index") }}' + '">' + o.id + '</a>'
            + '<div class="meta">'+ o.time +'</div>'
          + '</div>'
        + '</div>'
        + '<div class="amount">'+ o.amount +'</div>';
  listEl.appendChild(row);
      }
      rendered = end;
      checkMoreVisibility();
    }

    function checkMoreVisibility(){
      if(rendered < allOrders.length){
        moreWrap.classList.remove('d-none');
      } else {
        moreWrap.classList.add('d-none');
      }
    }

    // show More when scrolled to bottom of list
    listEl.addEventListener('scroll', function(){
      if(listEl.scrollTop + listEl.clientHeight >= listEl.scrollHeight - 8){
        moreWrap.classList.remove('d-none');
      }
    });

    loadMoreBtn.addEventListener('click', function(){ renderNext(); listEl.scrollTop = listEl.scrollHeight; });

    // initial render
    renderNext();
  }

  // enhance goals table: add percent badges beside goal labels
  var goalRows = document.querySelectorAll('.goals-table tbody tr');
  goalRows.forEach(function(r, idx){
    if(idx % 2 === 0){
      // label row
      var lbl = r.querySelector('td');
      if(lbl){
        var bar = r.nextElementSibling ? r.nextElementSibling.querySelector('.goal-bar') : null;
        var pct = bar ? bar.getAttribute('data-pct') : null;
        if(pct){
          // mark this label row for easier styling
          r.classList.add('goal-row');
          var span = document.createElement('span');
          span.className = 'goal-pct';
          // color-code badge according to bar class
          var barClass = bar.className || '';
          if(barClass.indexOf('bg-success') !== -1) span.classList.add('badge-success');
          if(barClass.indexOf('bg-info') !== -1) span.classList.add('badge-info');
          if(barClass.indexOf('bg-warning') !== -1) span.classList.add('badge-warning');
          span.textContent = pct + '%';
          lbl.appendChild(span);
        }
      }
    }
  });

})();

// Inline external SVG icons so they can inherit CSS currentColor
(function inlineStatIcons(){
  var imgPath = '{{ asset("bolopa/back/images/icon/line-md--hazard-lights-loop.svg") }}';
  fetch(imgPath).then(function(res){ return res.text(); }).then(function(svgText){
    // parse the SVG text and extract the inner <svg> node
    var parser = new DOMParser();
    var doc = parser.parseFromString(svgText, 'image/svg+xml');
    var svgNode = doc.querySelector('svg');
    if(!svgNode) return;
    // make sure svg uses currentColor for fill/stroke
  svgNode.setAttribute('fill', 'currentColor');
  svgNode.setAttribute('width', '16');
  svgNode.setAttribute('height', '16');
    var imgs = document.querySelectorAll('img.stat-icon');
    imgs.forEach(function(img){
      var clone = svgNode.cloneNode(true);
      clone.classList.add('stat-icon');
      // preserve accessibility attributes
      if(img.hasAttribute('aria-hidden')) clone.setAttribute('aria-hidden', img.getAttribute('aria-hidden'));
      if(img.hasAttribute('alt')) clone.setAttribute('role','img');
      img.parentNode.replaceChild(clone, img);
    });
  }).catch(function(){ /* fail silently */ });
})();
</script>
@endpush
