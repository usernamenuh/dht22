<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monitoring Suhu dan Kelembaban</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Chart.js untuk visualisasi data -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <style>
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        background: linear-gradient(135deg, #0a0e27 0%, #1a1f3a 50%, #0f1729 100%);
        color: #e2e8f0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
        padding: 40px 20px;
        overflow-x: hidden;
      }

      /* Header Section */
      .header-section {
        margin-bottom: 60px;
        padding: 40px 0;
        text-align: center;
        animation: fadeInDown 0.8s ease-out;
      }

      .header-section h1 {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(135deg, #00f0ff, #4f46e5);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 15px;
        letter-spacing: -1px;
      }

      .header-section p {
        color: #94a3b8;
        font-size: 1.1rem;
        font-weight: 300;
        letter-spacing: 0.5px;
      }

      /* Main Container */
      .container-main {
        max-width: 1600px;
        margin: 0 auto;
      }

      /* Sensor Cards Grid */
      .sensor-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        margin-bottom: 60px;
      }

      /* Circular gauge card design instead of rectangular */
      .sensor-card {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.5) 0%, rgba(15, 23, 42, 0.3) 100%);
        border: 1px solid rgba(79, 70, 229, 0.2);
        border-radius: 40px;
        padding: 50px 30px;
        backdrop-filter: blur(20px);
        transition: all 0.4s cubic-bezier(0.23, 1, 0.320, 1);
        animation: slideInUp 0.6s ease-out;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 400px;
      }

      /* Animated gradient border */
      .sensor-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 40px;
        padding: 1px;
        background: linear-gradient(135deg, rgba(0, 240, 255, 0.3), rgba(79, 70, 229, 0.3));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        animation: borderGlow 3s ease-in-out infinite;
      }

      .sensor-card:hover {
        transform: translateY(-8px);
        border-color: rgba(79, 70, 229, 0.5);
        box-shadow: 0 30px 60px rgba(79, 70, 229, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.7) 0%, rgba(15, 23, 42, 0.5) 100%);
      }

      /* Circular gauge element */
      .gauge-wrapper {
        position: relative;
        width: 180px;
        height: 180px;
        margin-bottom: 20px;
      }

      .gauge-background {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: conic-gradient(from 0deg, rgba(79, 70, 229, 0.1) 0deg, rgba(0, 240, 255, 0.1) 180deg, rgba(79, 70, 229, 0.1) 360deg);
        border: 2px solid rgba(79, 70, 229, 0.2);
      }

      .gauge-fill {
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: conic-gradient(
          from 0deg,
          #4f46e5 0deg,
          #00f0ff 180deg,
          #4f46e5 360deg
        );
        animation: rotateGauge 8s linear infinite;
        opacity: 0.8;
      }

      .gauge-value {
        position: absolute;
        inset: 15px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(10, 14, 39, 0.8), rgba(15, 23, 42, 0.8));
        backdrop-filter: blur(10px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(79, 70, 229, 0.2);
      }

      .gauge-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #00f0ff;
        text-shadow: 0 0 20px rgba(0, 240, 255, 0.4);
        animation: pulse 2s ease-in-out infinite;
      }

      .gauge-unit {
        font-size: 0.9rem;
        color: #94a3b8;
        margin-top: 5px;
        letter-spacing: 1px;
      }

      .sensor-label {
        font-size: 0.95rem;
        color: #cbd5e1;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 15px;
        font-weight: 600;
      }

      .sensor-status {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 20px;
        font-size: 0.85rem;
        color: #34d399;
      }

      .status-indicator {
        width: 8px;
        height: 8px;
        background: #34d399;
        border-radius: 50%;
        animation: blink 1.5s ease-in-out infinite;
        box-shadow: 0 0 10px rgba(52, 211, 153, 0.6);
      }

      /* Charts Section */
      .charts-section {
        margin-bottom: 60px;
      }

      .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 30px;
        color: #e2e8f0;
        display: flex;
        align-items: center;
        gap: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
      }

      .section-title::before {
        content: '';
        width: 4px;
        height: 28px;
        background: linear-gradient(180deg, #4f46e5, #00f0ff);
        border-radius: 2px;
      }

      .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
        gap: 30px;
      }

      /* Enhanced chart card with glassmorphism */
      .chart-card {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.5) 0%, rgba(15, 23, 42, 0.3) 100%);
        border: 1px solid rgba(79, 70, 229, 0.2);
        border-radius: 30px;
        padding: 35px;
        backdrop-filter: blur(20px);
        animation: slideInUp 0.6s ease-out 0.2s both;
        transition: all 0.4s ease;
        position: relative;
      }

      .chart-card:hover {
        border-color: rgba(79, 70, 229, 0.4);
        box-shadow: 0 20px 50px rgba(79, 70, 229, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        transform: translateY(-5px);
      }

      .chart-container {
        position: relative;
        height: 320px;
      }

      /* Stats Display */
      .stats-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 20px;
        margin-top: 30px;
      }

      /* Enhanced stat items with gradient background */
      .stat-item {
        background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(0, 240, 255, 0.05));
        border: 1px solid rgba(79, 70, 229, 0.2);
        border-radius: 20px;
        padding: 25px 20px;
        text-align: center;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
      }

      .stat-item:hover {
        transform: translateY(-4px);
        border-color: rgba(79, 70, 229, 0.4);
        box-shadow: 0 15px 40px rgba(79, 70, 229, 0.1);
      }

      .stat-label {
        font-size: 0.8rem;
        color: #94a3b8;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
      }

      .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        background: linear-gradient(135deg, #4f46e5, #00f0ff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }

      /* Animations */
      @keyframes fadeInDown {
        from {
          opacity: 0;
          transform: translateY(-30px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      @keyframes slideInUp {
        from {
          opacity: 0;
          transform: translateY(40px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      @keyframes pulse {
        0%, 100% {
          transform: scale(1);
          text-shadow: 0 0 20px rgba(0, 240, 255, 0.4);
        }
        50% {
          transform: scale(1.05);
          text-shadow: 0 0 30px rgba(0, 240, 255, 0.6);
        }
      }

      @keyframes blink {
        0%, 100% {
          opacity: 1;
        }
        50% {
          opacity: 0.4;
        }
      }

      @keyframes rotateGauge {
        from {
          transform: rotate(0deg);
        }
        to {
          transform: rotate(360deg);
        }
      }

      @keyframes borderGlow {
        0%, 100% {
          opacity: 0;
        }
        50% {
          opacity: 1;
        }
      }

      /* Responsive */
      @media (max-width: 768px) {
        .header-section h1 {
          font-size: 2rem;
        }

        .sensor-grid {
          grid-template-columns: 1fr;
        }

        .charts-grid {
          grid-template-columns: 1fr;
        }

        .section-title {
          font-size: 1.2rem;
        }

        .gauge-wrapper {
          width: 140px;
          height: 140px;
        }

        .gauge-number {
          font-size: 1.8rem;
        }
      }
    </style>
  </head>
  <body>
    <div class="container-main">
      <!-- Header -->
      <div class="header-section">
        <h1>Environmental Monitoring</h1>
        <p>Real-time sensor data dashboard</p>
      </div>

      <!-- Sensor Cards with Circular Gauges -->
      <div class="sensor-grid">
        <div class="sensor-card">
          <div class="gauge-wrapper">
            <div class="gauge-background"></div>
            <div class="gauge-fill"></div>
            <div class="gauge-value">
              <div class="gauge-number" id="temperature">--</div>
              <div class="gauge-unit">°C</div>
            </div>
          </div>
          <div class="sensor-label">Temperature</div>
          <div class="sensor-status">
            <span class="status-indicator"></span>
            <span>Live Monitoring</span>
          </div>
        </div>

        <div class="sensor-card">
          <div class="gauge-wrapper">
            <div class="gauge-background"></div>
            <div class="gauge-fill"></div>
            <div class="gauge-value">
              <div class="gauge-number" id="humidity">--</div>
              <div class="gauge-unit">%</div>
            </div>
          </div>
          <div class="sensor-label">Humidity</div>
          <div class="sensor-status">
            <span class="status-indicator"></span>
            <span>Live Monitoring</span>
          </div>
        </div>
      </div>

      <div class="card">
        <h3>Setting Batas Suhu</h3>
        <form action="/update-setting" method="POST">
          @csrf
          <label>Batas Suhu (°C): </label>
          <input type="number" step="0.1" name="threshold_temp" value="{{ $setting->threshold_temp ?? 30 }}">
          <button type="submit">Simpan</button>
        </form>
        @if(session('success'))
          <p style="color:green">{{ session('success') }}</p>
        @endif
      </div>
    

      <!-- Charts Section -->
      <div class="charts-section">
        <h2 class="section-title">Historical Data</h2>
        <div class="charts-grid">
          <div class="chart-card">
            <div class="chart-container">
              <canvas id="temperatureChart"></canvas>
            </div>
          </div>
          <div class="chart-card">
            <div class="chart-container">
              <canvas id="humidityChart"></canvas>
            </div>
          </div>
        </div>
      </div>

<table class="table table-striped table-bordered align-middle">
    <thead class="table-dark text-center">
        <tr>
            <th style="width: 30%">Nama Lampu</th>
            <th style="width: 20%">Status</th>
            <th style="width: 50%">Aksi</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($lampuStatuses as $l)
        <tr>
            <!-- Nama Lampu -->
            <td id="lampu-name-{{ $l->id }}" class="fw-semibold">
                {{ $l->name }}
            </td>

            <!-- Toggle Lampu -->
            <td class="text-center">
                <div class="form-check form-switch d-flex justify-content-center">
                    <input 
                        class="form-check-input switch-lampu" 
                        type="checkbox" 
                        data-id="{{ $l->id }}"
                        {{ $l->status ? 'checked' : '' }}
                    >
                </div>
            </td>

            <!-- Edit Nama -->
            <td>
                <div class="d-flex align-items-center gap-3">
                    <input
                        type="text"
                        class="form-control device-name-input"
                        style="max-width: 250px;"
                        data-id="{{ $l->id }}"
                        value="{{ $l->name }}"
                    >

                    <button class="btn btn-primary btn-sm save-name-btn"
                        data-id="{{ $l->id }}">
                        Simpan Nama
                    </button>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

      <!-- Stats -->
      <div class="chart-card">
        <h3 class="section-title">Statistics</h3>
        <div class="stats-section">
          <div class="stat-item">
            <div class="stat-label">Avg Temp</div>
            <div class="stat-value" id="avgTemp">--</div>
          </div>
          <div class="stat-item">
            <div class="stat-label">Max Temp</div>
            <div class="stat-value" id="maxTemp">--</div>
          </div>
          <div class="stat-item">
            <div class="stat-label">Min Temp</div>
            <div class="stat-value" id="minTemp">--</div>
          </div>
          <div class="stat-item">
            <div class="stat-label">Avg Humidity</div>
            <div class="stat-value" id="avgHumidity">--</div>
          </div>
          <div class="stat-item">
            <div class="stat-label">Max Humidity</div>
            <div class="stat-value" id="maxHumidity">--</div>
          </div>
          <div class="stat-item">
            <div class="stat-label">Min Humidity</div>
            <div class="stat-value" id="minHumidity">--</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    
    <script>
      // Data storage
      let temperatureData = [];
      let humidityData = [];
      let timestamps = [];
      let temperatureChart = null;
      let humidityChart = null;

      // Initialize charts
      function initCharts() {
        const ctxTemp = document.getElementById('temperatureChart').getContext('2d');
        temperatureChart = new Chart(ctxTemp, {
          type: 'line',
          data: {
            labels: timestamps,
            datasets: [{
              label: 'Temperature (°C)',
              data: temperatureData,
              borderColor: '#4f46e5',
              backgroundColor: 'rgba(79, 70, 229, 0.1)',
              borderWidth: 3,
              fill: true,
              tension: 0.5,
              pointRadius: 5,
              pointBackgroundColor: '#4f46e5',
              pointBorderColor: '#00f0ff',
              pointBorderWidth: 2,
              pointHoverRadius: 7,
              pointHoverBackgroundColor: '#00f0ff'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: true,
                labels: {
                  color: '#94a3b8',
                  font: { size: 13, weight: '600' },
                  padding: 20
                }
              },
              tooltip: {
                backgroundColor: 'rgba(10, 14, 39, 0.95)',
                titleColor: '#e2e8f0',
                bodyColor: '#94a3b8',
                borderColor: '#4f46e5',
                borderWidth: 2,
                padding: 15,
                titleFont: { size: 14, weight: 'bold' }
              }
            },
            scales: {
              y: {
                beginAtZero: false,
                grid: { color: 'rgba(79, 70, 229, 0.1)', lineWidth: 1 },
                ticks: { color: '#94a3b8', font: { size: 12 } }
              },
              x: {
                grid: { color: 'rgba(79, 70, 229, 0.05)', lineWidth: 1 },
                ticks: { color: '#94a3b8', font: { size: 12 } }
              }
            }
          }
        });

        const ctxHumidity = document.getElementById('humidityChart').getContext('2d');
        humidityChart = new Chart(ctxHumidity, {
          type: 'line',
          data: {
            labels: timestamps,
            datasets: [{
              label: 'Humidity (%)',
              data: humidityData,
              borderColor: '#00f0ff',
              backgroundColor: 'rgba(0, 240, 255, 0.1)',
              borderWidth: 3,
              fill: true,
              tension: 0.5,
              pointRadius: 5,
              pointBackgroundColor: '#00f0ff',
              pointBorderColor: '#4f46e5',
              pointBorderWidth: 2,
              pointHoverRadius: 7,
              pointHoverBackgroundColor: '#4f46e5'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: {
                display: true,
                labels: {
                  color: '#94a3b8',
                  font: { size: 13, weight: '600' },
                  padding: 20
                }
              },
              tooltip: {
                backgroundColor: 'rgba(10, 14, 39, 0.95)',
                titleColor: '#e2e8f0',
                bodyColor: '#94a3b8',
                borderColor: '#00f0ff',
                borderWidth: 2,
                padding: 15,
                titleFont: { size: 14, weight: 'bold' }
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                max: 100,
                grid: { color: 'rgba(79, 70, 229, 0.1)', lineWidth: 1 },
                ticks: { color: '#94a3b8', font: { size: 12 } }
              },
              x: {
                grid: { color: 'rgba(79, 70, 229, 0.05)', lineWidth: 1 },
                ticks: { color: '#94a3b8', font: { size: 12 } }
              }
            }
          }
        });
      }

      // Get data from API
      function getData() {
        $.ajax({
          type: "GET",
          url: "/get-data",
          success: function(response) {
            let temperature = response.data.temperature;
            let humidity = response.data.humidity;
            
            // Update display values
            $("#temperature").text(temperature.toFixed(1));
            $("#humidity").text(humidity.toFixed(1));

            // Add to data arrays (keep last 20 data points)
            const now = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            temperatureData.push(temperature);
            humidityData.push(humidity);
            timestamps.push(now);

            if (temperatureData.length > 20) {
              temperatureData.shift();
              humidityData.shift();
              timestamps.shift();
            }

            // Update stats
            updateStats();

            // Update charts
            if (temperatureChart) {
              temperatureChart.data.labels = timestamps;
              temperatureChart.data.datasets[0].data = temperatureData;
              temperatureChart.update('none');
            }

            if (humidityChart) {
              humidityChart.data.labels = timestamps;
              humidityChart.data.datasets[0].data = humidityData;
              humidityChart.update('none');
            }
          }
        });
      }

      // Update statistics
      function updateStats() {
        if (temperatureData.length > 0) {
          const avgTemp = (temperatureData.reduce((a, b) => a + b) / temperatureData.length).toFixed(1);
          const maxTemp = Math.max(...temperatureData).toFixed(1);
          const minTemp = Math.min(...temperatureData).toFixed(1);
          const avgHumidity = (humidityData.reduce((a, b) => a + b) / humidityData.length).toFixed(1);
          const maxHumidity = Math.max(...humidityData).toFixed(1);
          const minHumidity = Math.min(...humidityData).toFixed(1);

          $("#avgTemp").text(avgTemp);
          $("#maxTemp").text(maxTemp);
          $("#minTemp").text(minTemp);
          $("#avgHumidity").text(avgHumidity);
          $("#maxHumidity").text(maxHumidity);
          $("#minHumidity").text(minHumidity);
        }
      }

      // Initialize on document ready
      $(document).ready(function() {
        initCharts();
        getData();
        
        // Update data every 2 seconds
        setInterval(() => {
          getData();
        }, 2000);
      });

      // Toggle Lampu
$(document).on('change', '.switch-lampu', function () {

    let id = $(this).data('id');
    let status = $(this).is(':checked') ? 1 : 0;

    $.ajax({
        url: "/lampu/toggle/" + id,
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            status: status
        },
        success: function (res) {
            console.log("Lampu " + id + " status: " + res.status);
        }
    });

});

document.querySelectorAll('.device-name-input').forEach(input => {
    input.addEventListener('change', function() {
        let id = this.dataset.id;
        let name = this.value;

        fetch('{{ route('devices.updateName') }}', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                id: id,
                name: name
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                console.log("Nama berhasil diupdate:", data.name);
            }
        });
    });
});

document.querySelectorAll('.save-name-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        let id = this.dataset.id;
        let name = document.querySelector(`.device-name-input[data-id="${id}"]`).value;

        fetch('{{ route('devices.updateName') }}', {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ id, name })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {

                // Ubah nama di tabel tanpa reload
                document.getElementById(`lampu-name-${id}`).textContent = data.name;

                // Kasih efek berhasil
                let input = document.querySelector(`.device-name-input[data-id="${id}"]`);
                input.classList.add('is-valid');
                setTimeout(() => input.classList.remove('is-valid'), 1000);

            }
        });
    });
});


    </script>
  </body>
</html>
