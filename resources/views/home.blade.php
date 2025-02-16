@extends('adminlte.layouts.app')

@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6 d-flex">
            <h1 class="m-0">Dashboard </h1>            
            @if ($uids && $uids->count() > 0)
                <div class="form-group mb-0 d-flex ml-3">
                    <div class="form-group">
                        {{-- <label for="uidSelect" class="mr-2">Pilih UID:</label> --}}
                        <select id="uidSelect" class="form-control d-inline-block w-auto">
                            @foreach ($uids as $uid)
                                <option value="{{ $uid->uid }}" {{ $uid->uid == $selectUid ? 'selected' : '' }}>{{ $uid->uid }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif            
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row no-gutters">
            <!-- pH -->
            <div class="col-lg-2 col-6 mx-auto">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="latest-pH">{{ $latestData ? $latestData->pH : 'N/A' }}</h3>
                        <p>pH</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tint"></i>
                    </div>
                    <a href="view-data" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- TSS -->
            <div class="col-lg-2 col-6 mx-auto">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="latest-tss">{{ $latestData ? $latestData->tss : 'N/A' }}</h3>
                        <p>TSS</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-water"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- COD -->
            <div class="col-lg-2 col-6 mx-auto">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="latest-cod">{{ $latestData ? $latestData->cod : 'N/A' }}</h3>
                        <p>COD</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- NH3N -->
            <div class="col-lg-2 col-6 mx-auto">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="latest-nh3n">{{ $latestData ? $latestData->nh3n : 'N/A' }}</h3>
                        <p>NH3N</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-gas-pump"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- Debit -->
            <div class="col-lg-2 col-6 mx-auto">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3 id="latest-debit">{{ $latestData ? $latestData->debit : 'N/A' }}</h3>
                        <p>Debit</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-water"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-lg-6">
              <div class="card card-primary card-outline">
                  <div class="card-header d-flex align-items-center">
                      <h5 class="m-0">Chart</h5>
                      <div class="form-group ml-auto mb-0">
                          <select id="dataType" class="form-control">
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
              <div class="card-header">
                <h5 class="m-0">Last 10 Data</h5>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                          <thead>
                              <tr>
                                  <th>UID</th>
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
                                      <td>{{ $item->uid }}</td>
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
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content --> 
  <!-- /.content-wrapper -->
<script>
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
                                    backgroundColor: ['#3b8bba', '#00a65a', 'red'],
                                }],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: 'bottom',
                                        labels: {
                                            boxWidth: 20,
                                        }
                                    }
                                }
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

        // Set interval untuk memperbarui chart setiap 5 detik
        setInterval(() => {
            const uid = document.getElementById('uidSelect') 
                ? document.getElementById('uidSelect').value 
                : '{{ $selectUid }}';
            fetchStats(uid);
        }, 5000);

        // Panggil fetchStats saat tipe data atau UID berubah
        document.getElementById('dataType').addEventListener('change', function () {
            const uid = document.getElementById('uidSelect') 
                ? document.getElementById('uidSelect').value 
                : '{{ $selectUid }}';
            fetchStats(uid);
        });

        if (document.getElementById('uidSelect')) {
            document.getElementById('uidSelect').addEventListener('change', function () {
                const uid = this.value;
                fetchStats(uid);
            });
        }

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
          tss: { min: 20, max: 2000, midMin: 50, midMax: 300 },
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

                    // Gunakan style AdminLTE untuk container chart
                    chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: `${type.toUpperCase()} Data`,
                                    data: values,                                    
                                    borderColor         : 'black',
                                    borderWidth         : 3,
                                    pointRadius         : true,
                                    pointColor          : 'green',
                                    pointStrokeColor    : 'green',
                                    pointHighlightFill  : 'green',
                                    pointHighlightStroke: 'green',
                                    fill: false,
                                },
                                {
                                    label: `Batas Bawah (${currentThreshold.mid})`,
                                    data: Array(values.length).fill(currentThreshold.min),
                                    // backgroundColor     :'blue',
                                    borderColor         : 'blue',
                                    borderWidth: 2,
                                    fill: false,
                                    pointRadius         : true,
                                    pointColor          : 'rgba(60,141,188,0.8)',
                                    pointStrokeColor    : 'rgba(60,141,188,0.8)',
                                    pointHighlightFill  : 'rgba(60,141,188,0.8)',
                                    pointHighlightStroke: 'rgba(60,141,188,0.8)',
                                },
                                {
                                    label: `Batas Atas (${currentThreshold.max})`,
                                    data: Array(values.length).fill(currentThreshold.max),
                                    borderColor         : 'red',
                                    borderWidth: 2,
                                    fill: false,
                                    pointRadius         : true,
                                    pointColor          : 'rgba(60,141,188,0.8)',
                                    pointStrokeColor    : 'rgba(60,141,188,0.8)',
                                    pointHighlightFill  : 'rgba(60,141,188,0.8)',
                                    pointHighlightStroke: 'rgba(60,141,188,0.8)',
                                },
                                {
                                label: `Batas Tengah (${currentThreshold.midMin}-${currentThreshold.midMax})`,
                                data: Array(values.length).fill(currentThreshold.midMin),
                                 borderColor         : 'green',
                                    fill: false,
                                    borderWidth: 1,
                                    pointRadius         : true,
                                    pointColor          : 'rgba(60,141,188,0.8)',
                                    pointStrokeColor    : 'rgba(60,141,188,0.8)',
                                    pointHighlightFill  : 'rgba(60,141,188,0.8)',
                                    pointHighlightStroke: 'rgba(60,141,188,0.8)',
                            },
                            {
                                label: `Batas Tengah (${currentThreshold.midMin}-${currentThreshold.midMax})`,
                                data: Array(values.length).fill(currentThreshold.midMax),
                                 borderColor         : 'orange',
                                    fill: false,
                                    borderWidth: 1,
                                    pointRadius         : true,
                                    pointColor          : 'rgba(60,141,188,0.8)',
                                    pointStrokeColor    : 'rgba(60,141,188,0.8)',
                                    pointHighlightFill  : 'rgba(60,141,188,0.8)',
                                    pointHighlightStroke: 'rgba(60,141,188,0.8)',
                            }
                            ]
                        },
                        options: {
                            maintainAspectRatio : false,
                            responsive : true,
                            legend: {
                              display: true,
                              position: 'bottom',
                              labels: {
                                boxWidth: 10
                              }
                            },
                            plugins: {
                              zoom: {
                                  pan: {
                                      enabled: true,
                                      mode: 'x',
                                      speed: 10, // Kecepatan pan
                                  },
                                  zoom: {
                                      enabled: true,
                                      drag: false, // Zoom menggunakan drag
                                      mode: 'x',
                                      speed: 10, // Kecepatan zoom
                                  },
                              },
                          },
                             scales: {
                                xAxes: [{
                                    time: {
                                    unit: 'hour', // Interval per jam
                                    unitStepSize: 1, // Menentukan langkah per jam
                                    tooltipFormat: 'll HH:mm', // Format tanggal dan waktu pada tooltip
                                    displayFormats: {
                                        hour: 'HH:mm', // Menampilkan jam dalam format HH:mm
                                    }
                                },
                                ticks: {
                                    autoSkip: true,  // Otomatis melewati label untuk mencegah tumpang tindih
                                    maxTicksLimit: 10, // Batasi jumlah ticks
                                },                             
                                  gridLines : {
                                    display : true,
                                  }
                                }],
                                yAxes: [{
                                  gridLines : {
                                    display : false,
                                  }
                                }]
                              }
                            
                        }
                    });
                    console.log('Chart created successfully'); // Debug log
                })
                .catch(error => console.error('Error fetching data:', error));
        }
        // setInterval(fetchData, 3000);

        document.getElementById('dataType').addEventListener('change', function() {
            const uid = document.getElementById('uidSelect') ? document.getElementById('uidSelect').value : '{{ $selectUid }}';
            fetchData(this.value, uid);
        });

        if (document.getElementById('uidSelect')) {
            document.getElementById('uidSelect').addEventListener('change', function() {
                window.location.href = `{{ route('home') }}?uid=${this.value}`;
            });
        }

        // Fetch data awal
        const initialType = document.getElementById('dataType').value;
        fetchData(initialType, '{{ $selectUid }}');
    });

    document.addEventListener('DOMContentLoaded', function () {
          // Fungsi untuk mengambil data terbaru
          function fetchLatestData() {
              const uid = document.getElementById('uidSelect') ? document.getElementById('uidSelect').value : '{{ $selectUid }}';
              fetch(`{{ route("api.latestData") }}?uid=${uid}`)
                  .then(response => response.json())
                  .then(data => {
                      // Perbarui nilai di tampilan
                      document.getElementById('latest-pH').textContent = data.pH || 'N/A';
                      document.getElementById('latest-tss').textContent = data.tss || 'N/A';
                      document.getElementById('latest-cod').textContent = data.cod || 'N/A';
                      document.getElementById('latest-nh3n').textContent = data.nh3n || 'N/A';
                      document.getElementById('latest-debit').textContent = data.debit || 'N/A';
                  })
                  .catch(error => console.error('Error fetching latest data:', error));
          }

          // Jalankan fetchLatestData setiap 5 detik
          setInterval(fetchLatestData, 5000);

          // Jalankan fetchLatestData saat UID berubah
          if (document.getElementById('uidSelect')) {
              document.getElementById('uidSelect').addEventListener('change', fetchLatestData);
          }

          // Jalankan fetchLatestData saat halaman pertama kali dimuat
          fetchLatestData();
      });
</script>

@endsection
