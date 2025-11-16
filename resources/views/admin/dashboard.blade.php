@extends('admin.layouts.app')

@php($pageTitle = $pageTitle ?? 'Dashboard')

@section('styles')
<link rel="stylesheet" href="{{ asset('bolopa/back/css/admin-dashboard.css') }}">
<style>
.bolopa-btn-filter .btn {
  border: none !important;
  position: relative;
  z-index: 1;
}

.bolopa-btn-filter .btn:hover:not(.active) {
  background-color: rgba(113, 128, 150, 0.3) !important;
  color: #e2e8f0 !important;
  transform: none;
  box-shadow: none !important;
}

.bolopa-btn-filter .btn-outline-secondary:not(.active) {
  background: rgba(45, 55, 72, 0.8);
  color: #a0aec0 !important;
  border-color: rgba(113, 128, 150, 0.3) !important;
}

.bolopa-btn-filter .btn-outline-secondary:not(.active):hover {
  background: rgba(113, 128, 150, 0.3) !important;
  color: #e2e8f0 !important;
  border-color: rgba(113, 128, 150, 0.5) !important;
}

.bolopa-btn-filter .btn-primary.active {
  background: linear-gradient(135deg, #87ceeb 0%, #00bfff 100%) !important;
  color: white !important;
  font-weight: 700;
  border-color: transparent !important;
  transform: translateY(-1px);
}

.bolopa-btn-filter .btn-primary.active:hover {
  background: linear-gradient(135deg, #00bfff 0%, #1e90ff 100%) !important;
  transform: translateY(-2px);
}
</style>
@endsection

@section('content')
<div class="container py-4">
  <div class="row g-3">
    @foreach($summaryCards as $card)
    <div class="col-12 col-sm-6 col-md-3">
      <div class="card {{ $card['text_class'] }} {{ $card['background_class'] }} h-100 overflow-hidden" role="region" aria-labelledby="{{ $card['id'] }}">
        <div class="card-body d-flex align-items-start justify-content-between">
          <div>
            <h2 id="{{ $card['id'] }}" class="fw-bold display-6 mb-0" aria-label="{{ $card['aria_label'] }}">{{ $card['formatted'] }}</h2>
            <div class="small opacity-85">{{ $card['label'] }}</div>
          </div>
          <div class="{{ $card['icon_wrapper_class'] }}">
            <i class="{{ $card['icon'] }} fa-3x" aria-hidden="true"></i>
          </div>
        </div>
        <a href="{{ $card['link'] ?? '#' }}" class="card-footer {{ $card['footer_text_class'] ?? 'text-white-50' }} text-decoration-none" aria-label="Informasi lebih lanjut tentang {{ $card['label'] }}">
          <div class="d-flex justify-content-between align-items-center px-3 py-2">
            <span>Informasi lebih lanjut</span>
            <i class="fas fa-arrow-circle-right" aria-hidden="true"></i>
          </div>
        </a>
      </div>
    </div>
    @endforeach

  </div>
</div>

<!-- Main chart section with right-side Goals Completion card -->
<div class="container">
  <div class="row g-3">
    <div class="col-12 col-lg-8">
      <div class="card mb-4 no-hover-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Grafik Utama</h5>
            <div class="btn-group bolopa-btn-filter shadow-sm" role="group" aria-label="Filter grafik" style="border-radius: 25px; overflow: hidden; background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%); padding: 3px;">
              <button type="button" class="btn btn-sm {{ $range === 'weekly' ? 'btn-active active' : 'btn-outline-secondary' }} position-relative" data-range="weekly" style="border-radius: 20px; font-weight: 600; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); {{ $range === 'weekly' ? 'background: linear-gradient(135deg, #87ceeb 0%, #00bfff 100%); color: white;' : 'color: #a0aec0;' }}">
                <i class="fas fa-calendar-day me-1" aria-hidden="true"></i>Harian
              </button>
              <button type="button" class="btn btn-sm {{ $range === 'monthly' ? 'btn-active active' : 'btn-outline-secondary' }} position-relative" data-range="monthly" style="border-radius: 20px; font-weight: 600; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); {{ $range === 'monthly' ? 'background: linear-gradient(135deg, #87ceeb 0%, #00bfff 100%); color: white;' : 'color: #a0aec0;' }}">
                <i class="fas fa-calendar-alt me-1" aria-hidden="true"></i>Bulanan
              </button>
              <button type="button" class="btn btn-sm {{ $range === 'yearly' ? 'btn-active active' : 'btn-outline-secondary' }} position-relative" data-range="yearly" style="border-radius: 20px; font-weight: 600; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); {{ $range === 'yearly' ? 'background: linear-gradient(135deg, #87ceeb 0%, #00bfff 100%); color: white;' : 'color: #a0aec0;' }}">
                <i class="fas fa-calendar me-1" aria-hidden="true"></i>Tahunan
              </button>
            </div>
          </div>
          <div id="main-chart"></div>
          <div class="stats-strip-wrapper mt-3">
            <div class="stats-strip">
              @foreach($statsStrip as $stat)
              <div class="stats-item" data-dir="{{ $stat['direction'] }}">
                <div class="stat-top">
                  <img class="stat-icon" aria-hidden="true" src="{{ asset('bolopa/back/images/icon/line-md--hazard-lights-loop.svg') }}" width="16" height="16" alt="indikator">
                  <div class="pct {{ $stat['pct_class'] }}">{{ number_format($stat['pct'], 1, ',', '.') }}%</div>
                </div>
                <div class="amount">{{ $stat['amount'] }}</div>
                <div class="label">{{ $stat['label'] }}</div>
              </div>
              @endforeach
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
              <div class="bolopa-goals-list" style="height: 100px; max-height: 100px; overflow-y: auto; overflow-x: hidden; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.3) transparent; flex-shrink: 0;">
                <style>
                  .bolopa-goals-list::-webkit-scrollbar {
                    width: 6px;
                  }
                  .bolopa-goals-list::-webkit-scrollbar-track {
                    background: rgba(255,255,255,0.1);
                    border-radius: 3px;
                  }
                  .bolopa-goals-list::-webkit-scrollbar-thumb {
                    background: rgba(255,255,255,0.3);
                    border-radius: 3px;
                  }
                  .bolopa-goals-list::-webkit-scrollbar-thumb:hover {
                    background: rgba(255,255,255,0.5);
                  }
                </style>
                @foreach($goals as $goal)
                @php($pct = (int) ($goal['pct'] ?? 0))
                <div class="bolopa-goal-item mb-2 {{ $goal['state_class'] ?? '' }}">
                  <div class="d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                      <div class="small fw-medium text-truncate flex-grow-1 me-2" style="min-width: 0;" title="{{ $goal['label'] }}">{{ $goal['label'] }}</div>
                      <div class="fw-bold small text-nowrap">{{ number_format((float) ($goal['value'] ?? 0), 0, ',', '.') }} / {{ number_format((float) ($goal['target'] ?? 0), 0, ',', '.') }}</div>
                    </div>
                    <div class="progress mt-1" style="height: 8px;">
                      <div class="bolopa-goal-bar bolopa-bar-{{ $goal['color_class'] ?? 'blue' }}" role="progressbar" data-value="{{ $goal['value'] ?? 0 }}" data-target="{{ $goal['target'] ?? 0 }}" data-pct="{{ $pct }}" style="width:{{ min(max($pct, 0), 100) }}%"></div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
              @endif
            </div>

            <div class="bolopa-goals-inner bolopa-last-orders">
              <h6 class="mb-3">Pesanan Terakhir</h6>
              <div class="bolopa-last-orders-wrapper" style="min-height: 300px; display: flex; flex-direction: column;">
                <div id="last-orders-list" class="bolopa-last-orders-list" aria-live="polite" role="list" style="flex: 1;"></div>
                <div id="more-orders-wrap" class="text-center bolopa-more-orders-wrap" style="margin-top: auto; padding-top: 10px;">
                  <button id="load-more-orders" class="btn btn-sm btn-outline-light" type="button">
                    <i class="fas fa-external-link-alt me-1"></i>Lihat Semua Pesanan
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
                <tr class="{{ ($bahan->stok_minimum && $bahan->total_stok <= $bahan->stok_minimum) ? 'table-danger' : '' }}">
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
                    <span class="fw-bold {{ ($bahan->stok_minimum && $bahan->total_stok <= $bahan->stok_minimum) ? 'text-danger' : 'text-dark' }}">{{ $bahan->total_stok == floor($bahan->total_stok) ? number_format($bahan->total_stok, 0) : number_format($bahan->total_stok, 2) }}</span>
                    @if($bahan->stok_minimum && $bahan->total_stok <= $bahan->stok_minimum)
                      <span class="badge bg-danger ms-2">Stok Rendah</span>
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
  // create chart with improved styling and two series
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
    markers: { size: 5, colors: ['#007bff', '#28a745', '#ffc107'], strokeColors: '#fff', strokeWidth: 3, hover: { size: 8 } },
    fill: {
      type: 'gradient',
      gradient: {
        shade: 'light',
        type: 'vertical',
        shadeIntensity: 0.5,
        gradientToColors: ['#4dabf7', '#34d399', '#ffda6a'],
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
      labels: { style: { colors: '#6c757d', fontSize: '12px' }, formatter: function(val) { return val.toLocaleString('id-ID'); } },
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
          }
          if (opts.seriesIndex === 1) {
            return val.toLocaleString('id-ID') + ' produksi';
          }
          return val.toLocaleString('id-ID') + ' pesanan';
        }
      },
      marker: { show: true }
    },
    grid: { borderColor: '#e9ecef', strokeDashArray: 2, xaxis: { lines: { show: false } }, yaxis: { lines: { show: true } } },
    dataLabels: { enabled: false },
    legend: { show: true, position: 'bottom', horizontalAlign: 'center', offsetY: 10, markers: { width: 12, height: 12, radius: 6 } },
    title: { text: '{{ $chartTitle }}', align: 'left', style: { fontSize: '18px', fontWeight: 700, color: '#495057' } },
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
    btns.forEach(b=>{
      b.classList.remove('active', 'btn-primary');
      b.classList.add('btn-outline-secondary');
      b.style.background = 'rgba(45, 55, 72, 0.8)';
      b.style.color = '#a0aec0';
    });
    btn.classList.remove('btn-outline-secondary');
    btn.classList.add('active', 'btn-primary');
    btn.style.background = 'linear-gradient(135deg, #87ceeb 0%, #00bfff 100%)';
    btn.style.color = 'white';
  }

  function updateChart(range) {
    fetch('{{ route("backoffice.dashboard.chart-data") }}?range=' + range)
      .then(response => response.json())
      .then(data => {
        // Update series data
        chart.updateSeries([
          { name: 'Penjualan', data: data.penjualanData },
          { name: 'Produksi', data: data.produksiData },
          { name: 'Pesanan Baru', data: data.pesananData }
        ]);

        // Update xaxis categories and title
        chart.updateOptions({
          xaxis: { categories: data.categories },
          title: { text: data.chartTitle }
        });
      })
      .catch(error => console.error('Error fetching chart data:', error));
  }

  btns.forEach(b=>{
    b.addEventListener('click', function(e){
      var range = this.getAttribute('data-range');
      setActive(this);
      updateChart(range);
    });
  });

  function animateGoals(){
    var bars = Array.from(document.querySelectorAll('.goals-panel .goal-bar'))
      .concat(Array.from(document.querySelectorAll('.bolopa-goals-panel .bolopa-goal-bar')));
    bars.forEach(function(bar){
      var valAttr = bar.getAttribute('data-value');
      var tgtAttr = bar.getAttribute('data-target');
      var pctAttr = bar.getAttribute('data-pct');

      var value = valAttr !== null ? parseFloat(valAttr) : null;
      var target = tgtAttr !== null ? parseFloat(tgtAttr) : null;

      var pct = 0;
      if (value !== null && target !== null && target > 0) {
        pct = Math.round((value / target) * 100);
      } else if (pctAttr !== null) {
        pct = parseInt(pctAttr || '0', 10);
      }

      var width = Math.min(Math.max(pct, 0), 100);
      bar.style.width = width + '%';
      bar.setAttribute('aria-valuenow', String(width));
      bar.setAttribute('aria-valuemin', '0');
      bar.setAttribute('aria-valuemax', '100');
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
      for(var j = rendered; j < end; j++){
        var o = allOrders[j] || {};
        var row = document.createElement('div');
        var rowClass = 'order-row' + (j % 2 === 0 ? ' order-row-even' : ' order-row-odd');
        row.className = rowClass;

        var wrapper = document.createElement('div');
        wrapper.className = 'order-compact';

        var number = document.createElement('div');
        number.className = 'order-number';
        number.textContent = String(j + 1);

        var nameEl = document.createElement('div');
        nameEl.className = 'order-name';
        nameEl.textContent = o.id || '-';

        var details = document.createElement('div');
        details.className = 'order-details';

        var customer = document.createElement('span');
        customer.className = 'order-customer';
        customer.textContent = o.customer || 'N/A';

        var date = document.createElement('span');
        date.className = 'order-date';
        date.textContent = o.time || '-';

        var price = document.createElement('span');
        price.className = 'order-price';
        price.textContent = o.amount || 'Rp 0';

        details.appendChild(customer);
        details.appendChild(date);
        details.appendChild(price);

        var statusWrap = document.createElement('div');
        statusWrap.className = 'order-status';

        var fallbackClass = 'badge-secondary';
        if (o.status === 'selesai') fallbackClass = 'badge-success';
        else if (o.status === 'diproses') fallbackClass = 'badge-warning';
        else if (o.status === 'dibatalkan') fallbackClass = 'badge-danger';

        var statusBadge = document.createElement('span');
        statusBadge.className = 'badge ' + (o.badge_class || fallbackClass);
        statusBadge.textContent = o.status_label || o.status || '-';

        statusWrap.appendChild(statusBadge);

        wrapper.appendChild(number);
        wrapper.appendChild(nameEl);
        wrapper.appendChild(details);
        wrapper.appendChild(statusWrap);

        row.appendChild(wrapper);
        listEl.appendChild(row);
      }
      rendered = end;
    }

    loadMoreBtn.addEventListener('click', function(){
      window.location.href = '{{ route("backoffice.pesanan.index") }}';
    });

    // initial render
    renderNext();
  }

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
