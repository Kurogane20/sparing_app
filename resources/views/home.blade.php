@extends('adminlte.layouts.app')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row align-items-center mb-2">
          <div class="col-sm-6 d-flex align-items-center">
            <div>
              <h1 class="m-0" style="font-size:1.4rem;font-weight:700;">
                <i class="fas fa-tachometer-alt mr-2" style="color:#4f46e5;font-size:1.2rem;"></i>Dashboard
              </h1>
              <p class="m-0" style="font-size:0.75rem;color:#94a3b8;">Monitoring kualitas air real-time</p>
            </div>

            {{-- Filter: Lokasi + Tipe Data --}}
            @if($lokasiList->count() > 0)
            <div class="ml-3 d-flex align-items-center" style="gap:10px;flex-wrap:wrap;">

              {{-- Dropdown Lokasi --}}
              <div style="position:relative;">
                <span style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#4f46e5;font-size:0.75rem;pointer-events:none;">
                  <i class="fas fa-map-marker-alt"></i>
                </span>
                <select id="lokasiSelect" class="form-control" style="font-size:0.82rem;padding-left:28px;min-width:160px;">
                  @foreach($lokasiList as $lok)
                    <option value="{{ $lok }}" {{ $lok == $selectLokasi ? 'selected' : '' }}>{{ $lok }}</option>
                  @endforeach
                </select>
              </div>

              {{-- Toggle Tipe Data --}}
              <div id="tipeToggle" style="display:flex;background:#f1f5f9;border-radius:8px;padding:3px;gap:3px;">
                @foreach(['internal','klhk'] as $tipe)
                  @php $available = $tipeOptions->contains($tipe); @endphp
                  <button
                    class="tipe-btn {{ $selectTipe == $tipe ? 'active' : '' }} {{ !$available ? 'disabled-tipe' : '' }}"
                    data-tipe="{{ $tipe }}"
                    {{ !$available ? 'disabled' : '' }}
                    style="border:none;border-radius:6px;padding:5px 14px;font-size:0.78rem;font-weight:600;cursor:{{ $available ? 'pointer' : 'not-allowed' }};transition:all 0.2s;
                           background:{{ $selectTipe == $tipe ? '#4f46e5' : 'transparent' }};
                           color:{{ $selectTipe == $tipe ? '#fff' : ($available ? '#64748b' : '#cbd5e1') }};">
                    @if($tipe === 'internal')
                      <i class="fas fa-building mr-1" style="font-size:0.7rem;"></i>Internal
                    @else
                      <i class="fas fa-globe mr-1" style="font-size:0.7rem;"></i>KLHK
                    @endif
                  </button>
                @endforeach
              </div>


            </div>
            @endif
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <!-- Metric cards -->
        <div class="row">
            <!-- pH -->
            <div class="col-lg col-md-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="latest-pH">{{ $latestData ? $latestData->pH : 'N/A' }}</h3>
                        <p>pH</p>
                    </div>
                    <div class="icon"><i class="fas fa-tint"></i></div>
                    <a href="view-data" class="small-box-footer">Detail <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
            <!-- TSS -->
            <div class="col-lg col-md-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="latest-tss">{{ $latestData ? $latestData->tss : 'N/A' }}</h3>
                        <p>TSS <small style="font-size:0.65rem;opacity:0.8;">mg/L</small></p>
                    </div>
                    <div class="icon"><i class="fas fa-water"></i></div>
                    <a href="#" class="small-box-footer">Detail <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
            <!-- COD -->
            <div class="col-lg col-md-4 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="latest-cod">{{ $latestData ? $latestData->cod : 'N/A' }}</h3>
                        <p>COD <small style="font-size:0.65rem;opacity:0.8;">mg/L</small></p>
                    </div>
                    <div class="icon"><i class="fas fa-vial"></i></div>
                    <a href="#" class="small-box-footer">Detail <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
            <!-- NH3N -->
            <div class="col-lg col-md-4 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="latest-nh3n">{{ $latestData ? $latestData->nh3n : 'N/A' }}</h3>
                        <p>NH3N <small style="font-size:0.65rem;opacity:0.8;">mg/L</small></p>
                    </div>
                    <div class="icon"><i class="fas fa-flask"></i></div>
                    <a href="#" class="small-box-footer">Detail <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
            <!-- Debit -->
            <div class="col-lg col-md-4 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3 id="latest-debit">{{ $latestData ? $latestData->debit : 'N/A' }}</h3>
                        <p>Debit <small style="font-size:0.65rem;opacity:0.8;">m³/s</small></p>
                    </div>
                    <div class="icon"><i class="fas fa-wind"></i></div>
                    <a href="#" class="small-box-footer">Detail <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>
        </div>
    </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-lg-6">
              <div class="card card-primary card-outline">
                  <div class="card-header d-flex align-items-center">
                      <div>
                        <h5 class="m-0"><i class="fas fa-chart-line mr-2" style="color:#4f46e5;"></i>Grafik Parameter</h5>
                        <p class="m-0" style="font-size:0.72rem;color:#94a3b8;">30 menit terakhir</p>
                      </div>
                      <div class="ml-auto">
                          <select id="dataType" class="form-control" style="font-size:0.82rem;min-width:110px;">
                              <option value="pH">pH</option>
                              <option value="cod">COD</option>
                              <option value="tss">TSS</option>
                              <option value="nh3n">NH3N</option>
                              <option value="debit">Debit</option>
                          </select>
                      </div>
                  </div>
                  <div class="card-body">
                    <!-- Pie Chart -->
                    <div class="chart tab-pane active" id="stats-chart" style="position: relative; height: 200px; margin-bottom: 30px;">
                        <canvas id="statsChart" class="w-100" style="min-height: 200px; height: 200px; max-height: 200px; max-width: 100%; display: block; margin: 0 auto;"></canvas>
                    </div>

                    <!-- Data Label -->
                    <h3 class="text-center" id="dataLabel" style="margin-bottom: 20px;">Data</h3>

                    <!-- Line Chart -->
                    <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                        <canvas id="dataChart" class="w-100" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; margin: 0 auto;"></canvas>
                    </div>
                </div>
              </div>
          </div>

          <!-- /.col-md-6 -->
          <div class="col-lg-6">
            <div class="card card-primary card-outline">
              <div class="card-header d-flex align-items-center">
                <div>
                  <h5 class="m-0"><i class="fas fa-table mr-2" style="color:#4f46e5;"></i>Data Terbaru</h5>
                  <p class="m-0" style="font-size:0.72rem;color:#94a3b8;">10 data terakhir</p>
                </div>
                <a href="{{ route('view-data.index') }}" class="btn btn-primary btn-sm ml-auto" style="font-size:0.75rem;">
                  <i class="fas fa-external-link-alt mr-1"></i>Lihat Semua
                </a>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                          <thead>
                              <tr>
                                  <th>pH</th>
                                  <th>TSS</th>
                                  <th>Debit</th>
                                  <th>COD</th>
                                  <th>NH3N</th>
                                  <th>Date</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($recentData as $item)
                                  <tr></tr>
                                      <td>{{ $item->pH }}</td>
                                      <td>{{ $item->tss }}</td>
                                      <td>{{ $item->debit }}</td>
                                      <td>{{ $item->cod }}</td>
                                      <td>{{ $item->nh3n }}</td>
                                      <td>{{ $item->created_at }}</td>
                                  </tr>
                                  
                              @endforeach                              
                          </tbody>
                      </table>
                  </div>
              </div>
            </div>            
          </div>
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->

        <!-- Live Log Feed -->
        @if($selectUid)
        <div class="row mt-2">
          <div class="col-12">
            <div class="card card-outline" style="border-top:3px solid #0f172a;">
              <div class="card-header d-flex align-items-center">
                <div>
                  <h5 class="m-0">
                    <i class="fas fa-terminal mr-2" style="color:#0f172a;"></i>Log Logger
                    <span id="log-live-badge" style="display:inline-flex;align-items:center;gap:4px;background:#d1fae5;color:#065f46;font-size:0.68rem;font-weight:600;padding:2px 8px;border-radius:20px;margin-left:8px;vertical-align:middle;">
                      <span style="width:6px;height:6px;background:#10b981;border-radius:50%;display:inline-block;animation:blink 1.2s infinite;"></span> LIVE
                    </span>
                  </h5>
                  <p class="m-0" style="font-size:0.72rem;color:#94a3b8;">50 log terakhir — auto-update setiap 3 detik</p>
                </div>
                <button id="log-pause-btn" class="btn btn-sm ml-auto"
                  style="background:#f1f5f9;color:#475569;font-size:0.75rem;font-weight:600;box-shadow:none;border:1px solid #e2e8f0;">
                  <i class="fas fa-pause mr-1"></i>Pause
                </button>
              </div>
              <div class="card-body p-0">
                <div id="log-container"
                  style="background:#0f172a;border-radius:0 0 12px 12px;height:280px;overflow-y:auto;padding:14px 16px;font-family:'Courier New',monospace;font-size:0.78rem;line-height:1.7;">
                  <div id="log-entries"></div>
                  <div id="log-empty" style="color:#475569;text-align:center;padding:40px 0;">
                    <i class="fas fa-satellite-dish" style="font-size:1.5rem;display:block;margin-bottom:8px;"></i>
                    Menunggu log dari logger...
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endif
        <!-- /.Live Log Feed -->

      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  <!-- /.content-wrapper -->
<script>
  // ── Baku Mutu (threshold) global ──────────────────────────────────────
  const BAKU_MUTU = {
    pH:    { min: 5,  max: 10,   label: 'pH',    satuan: '' },
    tss:   { min: 20, max: 70,   label: 'TSS',   satuan: 'mg/L' },
    cod:   { min: 50, max: 2000, label: 'COD',   satuan: 'mg/L' },
    nh3n:  { min: 50, max: 2000, label: 'NH3N',  satuan: 'mg/L' },
    debit: { min: 50, max: 2000, label: 'Debit', satuan: 'm³/s' },
  };

  // Cooldown: simpan waktu terakhir alert per parameter (60 detik)
  const ALERT_COOLDOWN_MS = 60000;
  const lastAlertTime = {};

  function checkBakuMutu(data) {
    const violated = [];

    Object.keys(BAKU_MUTU).forEach(param => {
      const val = parseFloat(data[param]);
      const bm  = BAKU_MUTU[param];
      if (isNaN(val)) return;

      const now = Date.now();
      const cooldownOk = !lastAlertTime[param] || (now - lastAlertTime[param]) > ALERT_COOLDOWN_MS;

      if (cooldownOk && (val < bm.min || val > bm.max)) {
        const status = val < bm.min ? 'di bawah' : 'di atas';
        const limit  = val < bm.min ? bm.min : bm.max;
        violated.push({ param, label: bm.label, val, status, limit, satuan: bm.satuan });
        lastAlertTime[param] = now;
      }
    });

    if (violated.length === 0) return;

    const rows = violated.map(v => `
      <tr>
        <td style="padding:6px 10px;font-weight:600;color:#1e293b;">${v.label}</td>
        <td style="padding:6px 10px;color:#ef4444;font-weight:700;">${v.val} ${v.satuan}</td>
        <td style="padding:6px 10px;color:#64748b;">${v.status} batas (${v.limit} ${v.satuan})</td>
      </tr>
    `).join('');

    Swal.fire({
      title: '<i class="fas fa-exclamation-triangle" style="color:#ef4444;margin-right:8px;"></i>Baku Mutu Terlampaui!',
      html: `
        <p style="font-family:Poppins,sans-serif;font-size:0.85rem;color:#475569;margin-bottom:14px;">
          Parameter berikut melebihi batas baku mutu yang ditetapkan:
        </p>
        <div style="overflow:auto;border-radius:8px;border:1px solid #fee2e2;">
          <table style="width:100%;border-collapse:collapse;font-family:Poppins,sans-serif;font-size:0.82rem;">
            <thead>
              <tr style="background:#fef2f2;">
                <th style="padding:8px 10px;text-align:left;color:#b91c1c;font-weight:600;border-bottom:1px solid #fecaca;">Parameter</th>
                <th style="padding:8px 10px;text-align:left;color:#b91c1c;font-weight:600;border-bottom:1px solid #fecaca;">Nilai</th>
                <th style="padding:8px 10px;text-align:left;color:#b91c1c;font-weight:600;border-bottom:1px solid #fecaca;">Status</th>
              </tr>
            </thead>
            <tbody>${rows}</tbody>
          </table>
        </div>
        <p style="font-family:Poppins,sans-serif;font-size:0.75rem;color:#94a3b8;margin-top:12px;">
          <i class="fas fa-clock mr-1"></i>${new Date().toLocaleString('id-ID')}
        </p>
      `,
      icon: false,
      showConfirmButton: true,
      confirmButtonText: '<i class="fas fa-check mr-1"></i> Mengerti',
      confirmButtonColor: '#4f46e5',
      showCloseButton: true,
      timer: 20000,
      timerProgressBar: true,
      customClass: { popup: 'swal-custom-popup', confirmButton: 'swal-confirm-btn' },
      didOpen: (popup) => {
        popup.style.fontFamily = 'Poppins, sans-serif';
        popup.style.borderRadius = '16px';
        popup.style.maxWidth = '460px';
      }
    });
  }
  // ──────────────────────────────────────────────────────────────────────

  // Initialize with the default value "pH"
    document.addEventListener('DOMContentLoaded', function () {
        var dataTypeSelect = document.getElementById('dataType');
        var dataLabel = document.getElementById('dataLabel');

        // Set the default selected value to "pH" and update the label
        dataTypeSelect.value = 'pH';
        dataLabel.textContent = 'pH Data';

        // Listen for changes and update the label accordingly
        dataTypeSelect.addEventListener('change', function () {
            var selectedValue = this.value;
            switch (selectedValue) {
                case 'pH':
                    dataLabel.textContent = 'pH Data';
                    break;
                case 'cod':
                    dataLabel.textContent = 'COD Data';
                    break;
                case 'tss':
                    dataLabel.textContent = 'TSS Data';
                    break;
                case 'nh3n':
                    dataLabel.textContent = 'NH3N Data';
                    break;
                case 'debit':
                    dataLabel.textContent = 'Debit Data';
                    break;
                default:
                    dataLabel.textContent = 'Data';
                    break;
            }
        });
    });

  document.addEventListener('DOMContentLoaded', function () {
        const statsCtx = document.getElementById('statsChart').getContext('2d');
        let statsChart;

        function fetchStats(uid) {
            fetch(`{{ route("statapi.data") }}?uid=${uid}`)
                .then(response => response.json())
                .then(data => {
                    const stats = data.stats;
                    
                    if (!statsChart) {
                        // Jika chart belum dibuat, buat chart baru
                        statsChart = new Chart(statsCtx, {
                            type: 'doughnut',
                            data: {
                                labels: ['Total Masuk', 'Data Sesuai', 'Tidak Sesuai'],
                                datasets: [{
                                    data: [stats.total, stats.valid, stats.invalid],
                                    backgroundColor: ['#4f46e5', '#10b981', '#ef4444'],
                                    borderColor: '#ffffff',
                                    borderWidth: 3,
                                    hoverOffset: 6,
                                }],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutoutPercentage: 72,
                                legend: {
                                    display: true,
                                    position: 'bottom',
                                    labels: {
                                        fontFamily: "'Poppins', sans-serif",
                                        fontSize: 11,
                                        boxWidth: 10,
                                        padding: 14,
                                        usePointStyle: true,
                                        fontColor: '#475569',
                                    }
                                },
                                tooltips: {
                                    backgroundColor: '#1e293b',
                                    titleFontFamily: "'Poppins', sans-serif",
                                    bodyFontFamily: "'Poppins', sans-serif",
                                    titleFontSize: 12,
                                    bodyFontSize: 11,
                                    padding: 10,
                                    cornerRadius: 8,
                                },
                                animation: {
                                    animateRotate: true,
                                    animateScale: true,
                                    duration: 600,
                                    easing: 'easeInOutQuart',
                                },
                            },
                        });
                    } else {
                        // Jika chart sudah ada, update data saja
                        statsChart.data.datasets[0].data = [stats.total, stats.valid, stats.invalid];
                        statsChart.update();
                    }
                })
                .catch(error => console.error('Error fetching stats:', error));
        }

        // Perbarui stats setiap 5 detik
        setInterval(() => fetchStats('{{ $selectUid }}'), 5000);

        // Panggil fetchStats saat tipe parameter berubah
        document.getElementById('dataType').addEventListener('change', function () {
            fetchStats('{{ $selectUid }}');
        });

        // Fetch data awal untuk stats
        fetchStats('{{ $selectUid }}');
    });

    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('dataChart').getContext('2d');
        let chart;

        // Batas ambang untuk masing-masing parameter
        const thresholds = {
          pH: { min: 5, max: 10, midMin: 6, midMax: 9 },
          cod: { min: 50, max: 2000, midMin: 100, midMax: 500 },
          tss: { min: 20, max: 70, midMin: 40, midMax: 60 },
          nh3n: { min: 50, max: 2000, midMin: 10, midMax: 50 },
          debit: { min: 50, max: 2000, midMin: 100, midMax: 600 },
          };

        function fetchData(type, uid) {
            fetch(`{{ route("api.data") }}?uid=${uid}`)
                .then(response => response.json())
                .then(data => {
                    const now = new Date();
                    const thirtyMinutesAgo = new Date(now.getTime() - 30 * 60 * 1000);

                    // Filter data agar hanya dalam 30 menit terakhir
                    const filteredData = data.filter(item => {
                        const date = new Date(item.datetime * 1000);
                        return date >= thirtyMinutesAgo;
                    });

                    const labels = filteredData.map(item => {
                        const date = new Date(item.datetime * 1000);
                        return date.toLocaleString('id-ID', { hour: '2-digit', minute: '2-digit' });
                    });

                    const values = filteredData.map(item => item[type]);

                    if (chart) {
                        chart.destroy();
                    }

                    const currentThreshold = thresholds[type];

                    // Gradient fill di bawah garis data
                    const gradient = ctx.createLinearGradient(0, 0, 0, 280);
                    gradient.addColorStop(0,   'rgba(79,70,229,0.28)');
                    gradient.addColorStop(0.65, 'rgba(79,70,229,0.06)');
                    gradient.addColorStop(1,   'rgba(79,70,229,0)');

                    chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: type.toUpperCase(),
                                    data: values,
                                    borderColor: '#4f46e5',
                                    borderWidth: 2.5,
                                    backgroundColor: gradient,
                                    fill: true,
                                    lineTension: 0.4,
                                    pointBackgroundColor: '#4f46e5',
                                    pointBorderColor: '#ffffff',
                                    pointBorderWidth: 2,
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                    pointHoverBackgroundColor: '#4f46e5',
                                    pointHoverBorderColor: '#ffffff',
                                    pointHoverBorderWidth: 2,
                                },
                                {
                                    label: `Batas Atas (${currentThreshold.max})`,
                                    data: Array(values.length).fill(currentThreshold.max),
                                    borderColor: 'rgba(239,68,68,0.8)',
                                    borderWidth: 1.5,
                                    borderDash: [6, 4],
                                    fill: false,
                                    lineTension: 0,
                                    pointRadius: 0,
                                    pointHoverRadius: 4,
                                    pointHoverBackgroundColor: 'rgba(239,68,68,0.8)',
                                },
                                {
                                    label: `Batas Bawah (${currentThreshold.min})`,
                                    data: Array(values.length).fill(currentThreshold.min),
                                    borderColor: 'rgba(59,130,246,0.8)',
                                    borderWidth: 1.5,
                                    borderDash: [6, 4],
                                    fill: false,
                                    lineTension: 0,
                                    pointRadius: 0,
                                    pointHoverRadius: 4,
                                    pointHoverBackgroundColor: 'rgba(59,130,246,0.8)',
                                },
                            ]
                        },
                        options: {
                            maintainAspectRatio: false,
                            responsive: true,
                            legend: {
                                display: true,
                                position: 'bottom',
                                labels: {
                                    fontFamily: "'Poppins', sans-serif",
                                    fontSize: 11,
                                    boxWidth: 10,
                                    padding: 16,
                                    usePointStyle: true,
                                    fontColor: '#475569',
                                }
                            },
                            tooltips: {
                                mode: 'index',
                                intersect: false,
                                backgroundColor: '#1e293b',
                                titleFontFamily: "'Poppins', sans-serif",
                                bodyFontFamily: "'Poppins', sans-serif",
                                titleFontSize: 12,
                                bodyFontSize: 11,
                                titleFontColor: '#f1f5f9',
                                bodyFontColor: '#94a3b8',
                                padding: 12,
                                cornerRadius: 10,
                                caretSize: 5,
                                displayColors: true,
                            },
                            plugins: {
                                zoom: {
                                    pan: { enabled: true, mode: 'x', speed: 10 },
                                    zoom: { enabled: true, drag: false, mode: 'x', speed: 10 },
                                },
                            },
                            scales: {
                                xAxes: [{
                                    gridLines: {
                                        display: true,
                                        color: 'rgba(226,232,240,0.7)',
                                        zeroLineColor: 'rgba(226,232,240,1)',
                                        drawBorder: false,
                                    },
                                    ticks: {
                                        autoSkip: true,
                                        maxTicksLimit: 8,
                                        fontFamily: "'Poppins', sans-serif",
                                        fontSize: 10,
                                        fontColor: '#94a3b8',
                                        padding: 6,
                                    }
                                }],
                                yAxes: [{
                                    gridLines: {
                                        display: true,
                                        color: 'rgba(226,232,240,0.7)',
                                        zeroLineColor: 'rgba(226,232,240,1)',
                                        drawBorder: false,
                                    },
                                    ticks: {
                                        fontFamily: "'Poppins', sans-serif",
                                        fontSize: 10,
                                        fontColor: '#94a3b8',
                                        padding: 8,
                                        beginAtZero: false,
                                    }
                                }]
                            },
                            animation: {
                                duration: 600,
                                easing: 'easeInOutQuart',
                            },
                        }
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        }
        // setInterval(fetchData, 3000);

        document.getElementById('dataType').addEventListener('change', function() {
            fetchData(this.value, '{{ $selectUid }}');
        });

        // Fetch data awal
        const initialType = document.getElementById('dataType').value;
        fetchData(initialType, '{{ $selectUid }}');
    });

    document.addEventListener('DOMContentLoaded', function () {
          // Fungsi untuk mengambil data terbaru
          function fetchLatestData() {
              fetch(`{{ route("api.latestData") }}?uid={{ $selectUid }}`)
                  .then(response => response.json())
                  .then(data => {
                      // Perbarui nilai di tampilan
                      document.getElementById('latest-pH').textContent    = data.pH    || 'N/A';
                      document.getElementById('latest-tss').textContent   = data.tss   || 'N/A';
                      document.getElementById('latest-cod').textContent   = data.cod   || 'N/A';
                      document.getElementById('latest-nh3n').textContent  = data.nh3n  || 'N/A';
                      document.getElementById('latest-debit').textContent = data.debit || 'N/A';

                      // Cek baku mutu — tampilkan alert jika ada yang terlampaui
                      checkBakuMutu(data);

                      // Highlight stat box yang melebihi baku mutu
                      const paramMap = {
                          pH: 'latest-pH', tss: 'latest-tss', cod: 'latest-cod',
                          nh3n: 'latest-nh3n', debit: 'latest-debit'
                      };
                      Object.keys(BAKU_MUTU).forEach(param => {
                          const val = parseFloat(data[param]);
                          const bm  = BAKU_MUTU[param];
                          const el  = document.getElementById(paramMap[param]);
                          if (!el) return;
                          const box = el.closest('.small-box');
                          if (!box) return;
                          if (!isNaN(val) && (val < bm.min || val > bm.max)) {
                              box.style.animation = 'pulse-danger 1.5s ease-in-out 3';
                          } else {
                              box.style.animation = '';
                          }
                      });
                  })
                  .catch(error => console.error('Error fetching latest data:', error));
          }

          // Perbarui setiap 5 detik
          setInterval(fetchLatestData, 5000);

          // Jalankan saat halaman pertama kali dimuat
          fetchLatestData();
      });

    // ── Live Log Feed ─────────────────────────────────────────────────────
    @if($selectUid)
    document.addEventListener('DOMContentLoaded', function () {
        const uid        = '{{ $selectUid }}';
        const container  = document.getElementById('log-container');
        const entries    = document.getElementById('log-entries');
        const emptyMsg   = document.getElementById('log-empty');
        const pauseBtn   = document.getElementById('log-pause-btn');
        const liveBadge  = document.getElementById('log-live-badge');

        let lastId   = 0;
        let paused   = false;
        let interval;

        const LEVEL_STYLE = {
            INFO:    { color: '#38bdf8', icon: 'fa-info-circle' },
            WARNING: { color: '#fbbf24', icon: 'fa-exclamation-triangle' },
            ERROR:   { color: '#f87171', icon: 'fa-times-circle' },
            DEBUG:   { color: '#a78bfa', icon: 'fa-bug' },
        };

        function formatTime(str) {
            if (!str) return '';
            const d = new Date(str);
            return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }

        function appendLog(log) {
            const style = LEVEL_STYLE[log.level] || LEVEL_STYLE['INFO'];
            const el = document.createElement('div');
            el.style.cssText = 'display:flex;gap:8px;align-items:flex-start;padding:2px 0;border-bottom:1px solid rgba(255,255,255,0.03);';
            el.innerHTML = `
                <span style="color:#475569;white-space:nowrap;flex-shrink:0;">[${formatTime(log.logged_at)}]</span>
                <span style="color:${style.color};flex-shrink:0;"><i class="fas ${style.icon}"></i> ${log.level}</span>
                <span style="color:#cbd5e1;word-break:break-all;">${escapeHtml(log.message)}</span>
            `;
            entries.appendChild(el);
        }

        function escapeHtml(str) {
            return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        }

        function scrollBottom() {
            container.scrollTop = container.scrollHeight;
        }

        const API_BASE = window.location.origin + '/api';

        function fetchLogs() {
            if (paused) return;

            fetch(`${API_BASE}/logs?uid=${uid}&after_id=${lastId}`)
                .then(r => r.json().then(body => ({ ok: r.ok, status: r.status, body })))
                .then(({ ok, status, body }) => {
                    if (!ok) { console.warn('getLogs error:', status, body); return; }
                    if (!Array.isArray(body) || body.length === 0) return;
                    emptyMsg.style.display = 'none';
                    body.forEach(log => {
                        appendLog(log);
                        if (log.id > lastId) lastId = log.id;
                    });
                    scrollBottom();
                })
                .catch(err => console.warn('Log fetch error:', err));
        }

        // Load pertama — ambil 50 log terakhir
        fetch(`${API_BASE}/logs?uid=${uid}&limit=50`)
            .then(r => {
                if (!r.ok) throw new Error(`HTTP ${r.status}`);
                return r.json();
            })
            .then(logs => {
                if (!Array.isArray(logs) || logs.length === 0) return;
                emptyMsg.style.display = 'none';
                logs.forEach(log => {
                    appendLog(log);
                    if (log.id > lastId) lastId = log.id;
                });
                scrollBottom();
            })
            .catch(err => console.warn('Log initial load error:', err));

        // Polling setiap 3 detik untuk log baru
        interval = setInterval(fetchLogs, 3000);

        // Pause / resume
        pauseBtn.addEventListener('click', function () {
            paused = !paused;
            if (paused) {
                pauseBtn.innerHTML = '<i class="fas fa-play mr-1"></i>Resume';
                pauseBtn.style.color = '#4f46e5';
                liveBadge.style.opacity = '0.4';
            } else {
                pauseBtn.innerHTML = '<i class="fas fa-pause mr-1"></i>Pause';
                pauseBtn.style.color = '#475569';
                liveBadge.style.opacity = '1';
                fetchLogs();
            }
        });
    });
    @endif
    // ──────────────────────────────────────────────────────────────────────

    // ── Navigasi Filter Lokasi & Tipe Data ────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        const uidMap   = @json($uidMap);
        const homeUrl  = '{{ route("home") }}';

        function navigate(lokasi, tipe) {
            window.location.href = `${homeUrl}?lokasi=${encodeURIComponent(lokasi)}&tipe_data=${tipe}`;
        }

        // Dropdown lokasi berubah → ambil tipe pertama yang tersedia di lokasi baru
        const lokasiSelect = document.getElementById('lokasiSelect');
        if (lokasiSelect) {
            lokasiSelect.addEventListener('change', function () {
                const lokasi = this.value;
                const match  = uidMap.find(u => u.lokasi === lokasi);
                const tipe   = match ? match.tipe_data : 'internal';
                navigate(lokasi, tipe);
            });
        }

        // Toggle tipe data
        document.querySelectorAll('.tipe-btn:not([disabled])').forEach(btn => {
            btn.addEventListener('click', function () {
                const tipe   = this.dataset.tipe;
                const lokasi = lokasiSelect ? lokasiSelect.value : '{{ $selectLokasi }}';
                navigate(lokasi, tipe);
            });
        });
    });
    // ──────────────────────────────────────────────────────────────────────
</script>

@endsection
