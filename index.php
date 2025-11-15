<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Internet Speed Dashboard</title>
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

<div class="container py-4">

  <!-- Header -->
  <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
    <h1 class="display-5 text-primary">Internet Speed Dashboard</h1>
  </div>

  <!-- Stats Cards -->
  <div class="row g-4 mb-4">
    <div class="col-12 col-md-3">
      <div class="card text-white bg-primary shadow h-100">
        <div class="card-body">
          <h5 class="card-title">Max Download</h5>
          <p class="card-text fs-3" id="maxDownload">-- Mbps</p>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card text-white bg-success shadow h-100">
        <div class="card-body">
          <h5 class="card-title">Max Upload</h5>
          <p class="card-text fs-3" id="maxUpload">-- Mbps</p>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card text-white bg-warning shadow h-100">
        <div class="card-body">
          <h5 class="card-title">Avg Ping</h5>
          <p class="card-text fs-3" id="avgPing">-- ms</p>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-3">
      <div class="card text-white bg-danger shadow h-100">
        <div class="card-body">
          <h5 class="card-title">Min Download</h5>
          <p class="card-text fs-3" id="minDownload">-- Mbps</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts Side by Side -->
  <div class="row g-4 mb-4">
    <div class="col-12 col-md-6">
      <div class="card shadow h-100">
        <div class="card-body">
          <h5 class="card-title text-primary">Download & Upload Speed</h5>
          <div style="height:300px;">
            <canvas id="speedChart"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6">
      <div class="card shadow h-100">
        <div class="card-body">
          <h5 class="card-title text-warning">Ping</h5>
          <div style="height:300px;">
            <canvas id="pingChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Last 10 Measurements Table -->
  <div class="card shadow mb-4">
    <div class="card-body">
      <h5 class="card-title text-secondary mb-3">Last 10 Measurements</h5>
      <div class="table-responsive">
        <table class="table table-striped table-bordered mb-0">
          <thead class="table-light">
            <tr>
              <th>Timestamp</th>
              <th>Download (Mbps)</th>
              <th>Upload (Mbps)</th>
              <th>Ping (ms)</th>
            </tr>
          </thead>
          <tbody id="recentRecords"></tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<!-- JS: Fetch data, populate stats and charts -->
<script>
async function fetchData() {
  const response = await fetch('data.php');
  const data = await response.json();

  // Stats
  document.getElementById('maxDownload').innerText = Math.max(...data.download).toFixed(2) + ' Mbps';
  document.getElementById('maxUpload').innerText = Math.max(...data.upload).toFixed(2) + ' Mbps';
  document.getElementById('avgPing').innerText = (data.ping.reduce((a,b)=>a+b,0)/data.ping.length).toFixed(2) + ' ms';
  document.getElementById('minDownload').innerText = Math.min(...data.download).toFixed(2) + ' Mbps';

  // Table
  const recentRecords = document.getElementById('recentRecords');
  recentRecords.innerHTML = '';
  data.timestamps.slice(-10).forEach((time,i) => {
    recentRecords.innerHTML += `<tr>
      <td>${time}</td>
      <td>${data.download[i]}</td>
      <td>${data.upload[i]}</td>
      <td>${data.ping[i]}</td>
    </tr>`;
  });

  // Charts
  const speedCtx = document.getElementById('speedChart').getContext('2d');
  new Chart(speedCtx, {
    type: 'line',
    data: {
      labels: data.timestamps,
      datasets: [
        { label: 'Download Mbps', data: data.download, borderColor: '#0d6efd', fill: false, tension: 0.3 },
        { label: 'Upload Mbps', data: data.upload, borderColor: '#198754', fill: false, tension: 0.3 }
      ]
    },
    options: { responsive: true, maintainAspectRatio: false }
  });

  const pingCtx = document.getElementById('pingChart').getContext('2d');
  new Chart(pingCtx, {
    type: 'line',
    data: {
      labels: data.timestamps,
      datasets: [{ label: 'Ping (ms)', data: data.ping, borderColor: '#ffc107', fill: false, tension: 0.3 }]
    },
    options: { responsive: true, maintainAspectRatio: false }
  });
}

fetchData();
</script>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
