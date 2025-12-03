<?php
require_once('../assets/config/auth.php');
require_once('../assets/config/db.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Relatórios - PagTrem</title>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
  <link href="../assets/css/styles.css" rel="stylesheet">
</head>

<body>

  <div class="layout-wrapper">
    <?php include '_partials/sidebar_admin.php'; ?>

    <div class="main-content">
      <!-- HEADER -->
      <div class="top-header">
        <h1><i class="ri-bar-chart-2-line"></i> Relatórios</h1>
      </div>

      <div class="container" style="padding-bottom: 100px;">
        <!-- KPI GRID -->
        <div class="stats-grid">
          <div class="stat-card">
            <i class="ri-train-line"></i>
            <div class="stat-value">185</div>
            <div class="stat-label">Viagens/Mês</div>
          </div>
          <div class="stat-card">
            <i class="ri-time-line"></i>
            <div class="stat-value">94%</div>
            <div class="stat-label">Pontualidade</div>
          </div>
          <div class="stat-card">
            <i class="ri-emotion-happy-line"></i>
            <div class="stat-value">4.7</div>
            <div class="stat-label">Satisfação</div>
          </div>
        </div>

        <!-- CHARTS -->
        <div class="chart-section" style="display: flex; flex-direction: column; gap: 24px; margin-top: 32px;">

          <div class="card">
            <div style="margin-bottom: 16px;">
              <strong style="font-size: 1.1rem; display: block;">Passageiros e Viagens por Mês</strong>
              <span class="text-muted" style="font-size: 0.9rem;">Últimos 6 meses</span>
            </div>
            <canvas id="chartPassageiros"></canvas>
          </div>

          <div class="card">
            <div style="margin-bottom: 16px;">
              <strong style="font-size: 1.1rem; display: block;">Distribuição por Rota</strong>
              <span class="text-muted" style="font-size: 0.9rem;">Percentual de uso</span>
            </div>
            <div style="max-width: 400px; margin: 0 auto;">
              <canvas id="chartRotas"></canvas>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Chart 1: Passageiros e Viagens
    const ctx1 = document.getElementById('chartPassageiros').getContext('2d');
    new Chart(ctx1, {
      type: 'line',
      data: {
        labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
        datasets: [{
          label: 'Passageiros (k)',
          data: [8.5, 9.2, 8.8, 9.5, 10.1, 10.2],
          borderColor: '#DC2626',
          backgroundColor: 'rgba(220, 38, 38, 0.1)',
          tension: 0.4,
          fill: true
        }, {
          label: 'Viagens',
          data: [150, 165, 160, 175, 180, 185],
          borderColor: '#10b981',
          backgroundColor: 'rgba(16, 185, 129, 0.1)',
          tension: 0.4,
          fill: true,
          yAxisID: 'y1'
        }]
      },
      options: {
        responsive: true,
        interaction: {
          mode: 'index',
          intersect: false,
        },
        scales: {
          y: {
            type: 'linear',
            display: true,
            position: 'left',
          },
          y1: {
            type: 'linear',
            display: true,
            position: 'right',
            grid: {
              drawOnChartArea: false,
            },
          },
        }
      }
    });

    // Chart 2: Distribuição por Rota
    const ctx2 = document.getElementById('chartRotas').getContext('2d');
    new Chart(ctx2, {
      type: 'doughnut',
      data: {
        labels: ['Linha 1 - Azul', 'Linha 2 - Verde', 'Linha 3 - Vermelha', 'Linha 4 - Amarela'],
        datasets: [{
          data: [35, 25, 20, 20],
          backgroundColor: [
            '#DC2626',
            '#10b981',
            '#2563eb',
            '#f59e0b'
          ],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom',
          }
        }
      }
    });
  </script>

</body>

</html>