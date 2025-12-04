<?php
require_once('../assets/config/auth.php');
require_once('../assets/config/db.php');

$user = $_SESSION['user'] ?? null;

// Helper para formatar duração
function formatDuration($minutes)
{
  if (!$minutes)
    return '0min';
  $hours = floor($minutes / 60);
  $mins = $minutes % 60;
  if ($hours > 0) {
    return "{$hours}h {$mins}min";
  }
  return "{$mins}min";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <title>Área do Cliente - PagTrem</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../assets/css/styles.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">

</head>

<body>

  <div class="layout-wrapper">
    <?php include '_partials/sidebar_user.php'; ?>

    <div class="main-content">
      <div class="top-header">
        <h1><i class="ri-user-smile-line"></i> Olá, <?php echo htmlspecialchars($user['name']); ?>!</h1>
      </div>

      <div class="container" style="padding-bottom: 100px;">

        <div class="card" style="margin-bottom: 24px; text-align: center; padding: 32px 24px;">
          <i class="ri-train-line"
            style="font-size: 48px; color: var(--brand); margin-bottom: 16px; display: block;"></i>
          <h2 style="margin-bottom: 8px;">Bem-vindo ao PagTrem</h2>
          <p class="text-muted">Acompanhe suas rotas e viagens em tempo real.</p>
        </div>

        <h3 style="margin-bottom: 16px; padding-left: 4px;">Rotas Disponíveis</h3>

        <div class="route-list">
          <?php
          $res = $mysqli->query("SELECT * FROM routes ORDER BY id DESC");
          if ($res->num_rows > 0) {
            while ($r = $res->fetch_assoc()) {
              $badgeClass = ($r['status'] === 'manutencao') ? 'red' : 'blue';
              $badgeText = ($r['status'] === 'manutencao') ? 'Manutenção' : 'Ativa';
              $durationFmt = formatDuration($r['duration_minutes']);

              echo "
              <div class='route-card'>
                <div class='route-title'>
                  <span>" . htmlspecialchars($r['name']) . "</span>
                  <span class='badge $badgeClass'>$badgeText</span>
                </div>
                <div class='details'>
                  <div style='display:flex; align-items:center; gap:8px;'>
                    <i class='ri-map-pin-2-line' style='color:var(--brand);'></i>
                    <span>Paradas: " . htmlspecialchars($r['stops'] ?? 'Direto') . "</span>
                  </div>
                  <div style='display:flex; align-items:center; gap:8px;'>
                    <i class='ri-time-line' style='color:var(--brand);'></i>
                    <span>$durationFmt</span>
                  </div>
                  <div style='display:flex; align-items:center; gap:8px;'>
                    <i class='ri-calendar-line' style='color:var(--brand);'></i>
                    <span>Opera diariamente</span>
                  </div>
                </div>";

              if (!empty($r['extra_info'])) {
                echo "
                <div class='live-info'>
                  <i class='ri-notification-3-line' style='font-size:18px; color:var(--text-light);'></i>
                  <span>" . htmlspecialchars($r['extra_info']) . "</span>
                </div>";
              }

              echo "</div>";
            }
          } else {
            echo "<p class='text-muted' style='grid-column: 1/-1; text-align: center;'>Nenhuma rota disponível no momento.</p>";
          }
          ?>
        </div>
      </div>
    </div>
  </div>

</body>

</html>