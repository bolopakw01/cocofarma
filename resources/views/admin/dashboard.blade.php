@extends('admin.layouts.app')

@php
    $pageTitle = 'Dashboard';
@endphp

@section('content')
<link rel="stylesheet" href="{{ asset('bolopa/back/css/admin-dashboard.css') }}">

<div class="bolopa-container">
    <div class="row g-3">
        <!-- Card 1 -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card bolopa-card bolopa-bg-teal text-white h-100" role="region" aria-labelledby="card1-title">
                <div class="card-body d-flex align-items-start justify-content-between">
                    <div>
                        <h2 id="card1-title" class="fw-bold display-6 mb-0" aria-label="{{ $newOrdersToday }} new orders">{{ $newOrdersToday }}</h2>
                        <div class="small opacity-85">New Orders</div>
                    </div>
                    <div class="bolopa-card-icon">
                        <i class="fas fa-shopping-bag fa-2x" aria-hidden="true"></i>
                    </div>
                </div>
                <a href="#" class="card-footer text-white-50 text-decoration-none">
                    <div class="d-flex justify-content-between align-items-center px-3 py-2">
                        <span>More info</span>
                        <div class="d-flex align-items-center">
                          <span class="me-2 {{ $newOrdersDir === 'up' ? 'text-success' : ($newOrdersDir === 'down' ? 'text-danger' : 'text-warning') }}">{{ $newOrdersPct }}%</span>
                          <i class="fas fa-arrow-circle-right" aria-hidden="true"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card bolopa-card bolopa-bg-success-variant text-white h-100">
                <div class="card-body d-flex align-items-start justify-content-between">
                    <div>
                        <h2 class="fw-bold display-6 mb-0">{{ number_format($produksiLast7,0,',','.') }}</h2>
                        <div class="small opacity-85">Produksi (7d)</div>
                    </div>
                    <div class="bolopa-card-icon">
                        <i class="fas fa-chart-bar fa-2x" aria-hidden="true"></i>
                    </div>
                </div>
                <a href="#" class="card-footer text-white-50 text-decoration-none">
                    <div class="d-flex justify-content-between align-items-center px-3 py-2">
                        <span>More info</span>
                        <div class="d-flex align-items-center">
                          <span class="me-2 {{ $produksiDir === 'up' ? 'text-success' : ($produksiDir === 'down' ? 'text-danger' : 'text-warning') }}">{{ $produksiPct }}%</span>
                          <i class="fas fa-arrow-circle-right" aria-hidden="true"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card bolopa-card bolopa-bg-warning-variant text-dark h-100">
                <div class="card-body d-flex align-items-start justify-content-between">
                    <div>
                        <h2 class="fw-bold display-6 mb-0">{{ $usersToday }}</h2>
                        <div class="small opacity-85">User Registrations (Today)</div>
                    </div>
                    <div class="bolopa-card-icon text-dark">
                        <i class="fas fa-user-plus fa-2x" aria-hidden="true"></i>
                    </div>
                </div>
                <a href="#" class="card-footer text-dark-50 text-decoration-none">
                    <div class="d-flex justify-content-between align-items-center px-3 py-2">
                        <span>More info</span>
                        <div class="d-flex align-items-center">
                          <span class="me-2 {{ $usersDir === 'up' ? 'text-success' : ($usersDir === 'down' ? 'text-danger' : 'text-warning') }}">{{ $usersPct }}%</span>
                          <i class="fas fa-arrow-circle-right" aria-hidden="true"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card bolopa-card bolopa-bg-danger-variant text-white h-100">
                <div class="card-body d-flex align-items-start justify-content-between">
                    <div>
                        <h2 class="fw-bold display-6 mb-0">{{ $uniqueVisitors }}</h2>
                        <div class="small opacity-85">Unique Customers (30d)</div>
                    </div>
                    <div class="bolopa-card-icon">
                        <i class="fas fa-chart-pie fa-2x" aria-hidden="true"></i>
                    </div>
                </div>
                <a href="#" class="card-footer text-white-50 text-decoration-none">
                    <div class="d-flex justify-content-between align-items-center px-3 py-2">
                        <span>More info</span>
                        <div class="d-flex align-items-center">
                          <span class="me-2 {{ $uniqueDir === 'up' ? 'text-success' : ($uniqueDir === 'down' ? 'text-danger' : 'text-warning') }}">{{ $uniquePct }}%</span>
                          <i class="fas fa-arrow-circle-right" aria-hidden="true"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </div>

    <div class="row g-3 mt-3">
        <div class="col-12 col-lg-8">
            <div class="card bolopa-card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Grafik Utama</h5>
                        <div class="btn-group bolopa-btn-filter" role="group" aria-label="Filter chart">
                            <button type="button" class="btn btn-sm btn-outline-primary active" data-range="daily">Harian</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-range="weekly">Mingguan</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-range="monthly">Bulanan</button>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-range="yearly">Tahunan</button>
                        </div>
                    </div>
                    <div id="main-chart"></div>
                    <div class="stats-strip-wrapper mt-3">
                        <div class="stats-strip">
                            <div class="stats-item" data-dir="up">
                                <div class="stat-top">
                                    <img class="stat-icon" aria-hidden="true" src="{{ asset('bolopa/back/images/icon/line-md--hazard-lights-loop.svg') }}" width="16" height="16" alt="indicator">
                                    <div class="pct text-success">17%</div>
                                </div>
                                <div class="amount">Rp35.210,43</div>
                                <div class="label">TOTAL REVENUE</div>
                            </div>
                            <div class="stats-item" data-dir="same">
                                <div class="stat-top">
                                    <img class="stat-icon" aria-hidden="true" src="{{ asset('bolopa/back/images/icon/line-md--hazard-lights-loop.svg') }}" width="16" height="16" alt="indicator">
                                    <div class="pct text-warning">0%</div>
                                </div>
                                <div class="amount">Rp10.390,90</div>
                                <div class="label">TOTAL COST</div>
                            </div>
                            <div class="stats-item" data-dir="up">
                                <div class="stat-top">
                                    <img class="stat-icon" aria-hidden="true" src="{{ asset('bolopa/back/images/icon/line-md--hazard-lights-loop.svg') }}" width="16" height="16" alt="indicator">
                                    <div class="pct text-success">20%</div>
                                </div>
                                <div class="amount">Rp24.813,53</div>
                                <div class="label">TOTAL PROFIT</div>
                            </div>
                            <div class="stats-item" data-dir="down">
                                <div class="stat-top">
                                    <img class="stat-icon" aria-hidden="true" src="{{ asset('bolopa/back/images/icon/line-md--hazard-lights-loop.svg') }}" width="16" height="16" alt="indicator">
                                    <div class="pct text-danger">18%</div>
                                </div>
                                <div class="amount">1200</div>
                                <div class="label">GOAL COMPLETIONS</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card bolopa-card mb-4">
                <div class="card-body">
                    <div class="bolopa-goals-panel">
                        <div class="bolopa-goals-inner">
                            <h6 class="mb-3">Goals Completion</h6>
                            @foreach($goals as $g)
                                <div class="bolopa-goal-item mb-3">
                                    <div class="d-flex justify-content-between">
                                        <div class="small">{{ $g['label'] }}</div>
                                        <div class="fw-bold">{{ number_format($g['value'],0,',','.') }} / {{ number_format($g['target'],0,',','.') }}</div>
                                    </div>
                                    <div class="progress mt-2">
                                        <div class="bolopa-goal-bar bolopa-bar-{{ $g['color'] }}" role="progressbar"
                                             data-value="{{ $g['value'] }}" data-target="{{ $g['target'] }}" data-pct="{{ $g['pct'] }}"
                                             style="width:0%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="bolopa-goals-inner bolopa-last-orders mt-3">
                            <h6 class="mb-3">Last Orders</h6>
                            <div id="last-orders-list" class="bolopa-last-orders-list" aria-live="polite" role="list"></div>
                            <div id="more-orders-wrap" class="text-center d-none mt-2 bolopa-more-orders-wrap">
                              <button id="load-more-orders" class="btn btn-sm btn-outline-light" type="button">Load more</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Scripts required for chart -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@if(isset($chart) || isset($goals) || isset($lastActivities))
<script>
window.dashboardData = {
    chart: {!! json_encode($chart ?? []) !!},
    goals: {!! json_encode($goals ?? []) !!},
    lastActivities: {!! json_encode($lastActivities ?? []) !!}
};
</script>
@endif
<script>
// inlined from public/bolopa/back/js/main.js - initializes ApexCharts and handles filter buttons
(function(){
    function rand(max=100){ return Math.floor(Math.random()*max); }

    // sample dataset generators
    function generateDaily(){ return Array.from({length:24}, ()=>rand(200)); }
    function generateWeekly(){ return Array.from({length:7}, ()=>rand(800)); }
    function generateMonthly(){ return Array.from({length:30}, ()=>rand(1000)); }
    function generateYearly(){ return Array.from({length:12}, ()=>rand(5000)); }

    function labelsFor(range){
        if(range==='daily') return Array.from({length:24}, (_,i)=>i+':00');
        if(range==='weekly') return ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
        if(range==='monthly') return Array.from({length:30}, (_,i)=>String(i+1));
        if(range==='yearly') return ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        return [];
    }

    var palette = { visits: '#0d6efd', conv: '#20c997' };
        var options = {
        chart: { height: 380, type: 'area', animations: { enabled: true }, toolbar: { show: false }, zoom: { enabled: false }, dropShadow: { enabled: true, top: 6, left: 0, blur: 8, color: 'rgba(13,110,253,0.08)', opacity: 0.6 } },
            // if server provided data exists, use the monthly series; otherwise fall back to random generators
            series: (window.dashboardData && window.dashboardData.chart && window.dashboardData.chart.length) ? [
                    { name: 'Produksi', data: window.dashboardData.chart.map(c=>c.produksi) },
                    { name: 'Penjualan', data: window.dashboardData.chart.map(c=>c.penjualan) }
            ] : [ { name: 'Visits', data: generateDaily() }, { name: 'Conversions', data: generateDaily().map(d=>Math.round(d*0.12)) } ],
        colors: [palette.visits, palette.conv],
        stroke: { curve: 'smooth', width: 3 },
        markers: { size: 4, hover: { size: 6 }, strokeColors: '#ffffff', strokeWidth: 2 },
        fill: { type: 'gradient', gradient: { shade: 'light', type: 'vertical', shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.03, stops: [0, 90, 100] } },
        xaxis: { categories: (window.dashboardData && window.dashboardData.chart && window.dashboardData.chart.length) ? window.dashboardData.chart.map(c=>c.month) : labelsFor('daily'), labels: { style: { colors: '#6c757d', fontSize: '12px' } }, axisBorder: { show: false }, axisTicks: { show: false } },
        yaxis: { labels: { style: { colors: '#6c757d' } }, tickAmount: 5 },
        tooltip: { shared: true, intersect: false, theme: 'light', x: { show: true }, y: { formatter: function(val){ return val.toLocaleString(); } } },
        grid: { borderColor: '#eef2f6', strokeDashArray: 4 }, dataLabels: { enabled: false }, legend: { show: true, position: 'bottom', horizontalAlign: 'center', offsetY: 6 }, title: { text: 'Sales overview', align: 'left', style: { fontWeight: 600, color: '#333' } }
    };

    var chartEl = document.querySelector('#main-chart');
    var chart = null;
    function renderChart(){
        if(!chartEl || typeof ApexCharts === 'undefined') return;
        if(chart) chart.destroy();
        chart = new ApexCharts(chartEl, options);
        chart.render();
    }
    renderChart();

    var btnGroup = document.querySelector('.btn-filter') || document.querySelector('.bolopa-btn-filter');
    var btns = btnGroup ? btnGroup.querySelectorAll('button') : document.querySelectorAll('.btn-filter button');
    function setActive(btn){ btns.forEach(b=>b.classList.remove('active')); btn.classList.add('active'); }

    function updateRange(range){
        var data = [];
        if(range==='daily') data = generateDaily();
        if(range==='weekly') data = generateWeekly();
        if(range==='monthly') data = generateMonthly();
        if(range==='yearly') data = generateYearly();
        var conv = data.map(d=>Math.round(d * 0.12));
        if(chart){ chart.updateOptions({ series: [ { name: 'Visits', data: data }, { name: 'Conversions', data: conv } ], xaxis: { categories: labelsFor(range) } }, true, true); }
    }

    btns.forEach(b=>{ b.addEventListener('click', function(){ setActive(this); updateRange(this.getAttribute('data-range')); }); });

    // Goals animation and last orders
    function animateGoals(){
        var bars = Array.from(document.querySelectorAll('.goals-panel .goal-bar')).concat(Array.from(document.querySelectorAll('.bolopa-goals-panel .bolopa-goal-bar')));
        bars.forEach((bar, idx)=>{
            var valAttr = bar.getAttribute('data-value');
            var tgtAttr = bar.getAttribute('data-target');
            var pctAttr = bar.getAttribute('data-pct');
            var value = (valAttr !== null) ? parseFloat(valAttr) : null;
            var target = (tgtAttr !== null) ? parseFloat(tgtAttr) : null;
            var pct = 0;
            if(value !== null && target !== null && target > 0){ pct = Math.round((value / target) * 100); } else if(pctAttr !== null){ pct = parseInt(pctAttr || '0', 10); }
            if(pct < 0) pct = 0; if(pct > 100) pct = 100;
            bar.style.width = pct + '%'; bar.setAttribute('aria-valuenow', pct);
            var container = bar.closest('.goal-item') || bar.closest('.bolopa-goal-item');
            if(container){ var labelEl = container.querySelector('.fw-bold'); if(labelEl){ if(value !== null && target !== null){ labelEl.textContent = value + ' / ' + target; } else if(pctAttr !== null){ labelEl.textContent = pct + '%'; } } }
        });
    }
    setTimeout(animateGoals, 300);

    // Last Orders demo renderer
    var allOrders = [];
    for(var i=0;i<10;i++){ var id = '#ORD-' + (1100 - i); var amount = (Math.floor(Math.random()*900)+20).toFixed(2); var ago = (i<3)? ( (i+1) + ' hours ago') : ((i<24)? (i+1)+' hours ago' : Math.ceil((i+1)/24)+' days ago'); allOrders.push({ id: id, amount: '$' + amount, time: ago }); }
        var listEl = document.getElementById('last-orders-list'); var moreWrap = document.getElementById('more-orders-wrap'); var loadMoreBtn = document.getElementById('load-more-orders');
        if(listEl){
            // prefer server provided lastActivities
            var activities = (window.dashboardData && window.dashboardData.lastActivities && window.dashboardData.lastActivities.length) ? window.dashboardData.lastActivities : allOrders.map(function(o){ return { id: o.id, amount: o.amount, time: o.time }; });
            activities.forEach(function(a){ var row = document.createElement('div'); row.className = 'order-row'; var initial = (a.label || a.id || '').toString().replace(/[^A-Z]/g,'').charAt(0) || 'O'; var meta = a.meta ? (a.meta+'') : (a.amount || ''); row.innerHTML = '<div style="display:flex;align-items:center;">' + '<div class="avatar-sm bg-secondary text-white">'+ initial +'</div>' + '<div>' + '<a class="order-link" href="#">'+ (a.label || a.id) +'</a>' + '<div class="meta">'+ (a.date || a.time || '') +'</div>' + '</div>' + '</div>' + '<div class="amount">'+ meta +'</div>'; listEl.appendChild(row); });
            if(!activities.length){ moreWrap.classList.add('d-none'); }
        }

    // Inline external SVG icons from the public images folder so they can inherit currentColor
    (function inlineStatIcons(){
        var imgPath = '/bolopa/back/images/icon/line-md--hazard-lights-loop.svg';
        fetch(imgPath).then(function(res){ return res.text(); }).then(function(svgText){ var parser = new DOMParser(); var doc = parser.parseFromString(svgText, 'image/svg+xml'); var svgNode = doc.querySelector('svg'); if(!svgNode) return; svgNode.setAttribute('fill', 'currentColor'); svgNode.setAttribute('width', '16'); svgNode.setAttribute('height', '16'); var imgs = document.querySelectorAll('img.stat-icon'); imgs.forEach(function(img){ var clone = svgNode.cloneNode(true); clone.classList.add('stat-icon'); if(img.hasAttribute('aria-hidden')) clone.setAttribute('aria-hidden', img.getAttribute('aria-hidden')); if(img.hasAttribute('alt')) clone.setAttribute('role','img'); img.parentNode.replaceChild(clone, img); }); }).catch(function(){ /* fail silently */ });
    })();

})();
</script>

@endsection
