<?php
require_once('../assets/config/auth.php');
$user = $_SESSION['user'] ?? null;
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

          <div class="route-card">
            <div class="route-title">
              <span>São Paulo → Rio de Janeiro</span>
              <span class="badge blue">Ativa</span>
            </div>
            <div class="details">
              <div style="display:flex; align-items:center; gap:8px;">
                <i class="ri-map-pin-2-line" style="color:var(--brand);"></i>
                <span>Estações: Central • Norte • Sul</span>
              </div>
              <div style="display:flex; align-items:center; gap:8px;">
                <i class="ri-time-line" style="color:var(--brand);"></i>
                <span>6h 30min</span>
              </div>
              <div style="display:flex; align-items:center; gap:8px;">
                <i class="ri-calendar-line" style="color:var(--brand);"></i>
                <span>Opera diariamente</span>
              </div>
            </div>
          </div>

          <div class="route-card">
            <div class="route-title">
              <span>Campinas → Santos</span>
              <span class="badge blue">Ativa</span>
            </div>
            <div class="details">
              <div style="display:flex; align-items:center; gap:8px;">
                <i class="ri-map-pin-2-line" style="color:var(--brand);"></i>
                <span>KM 45 • Ponte Rio Grande</span>
              </div>
              <div style="display:flex; align-items:center; gap:8px;">
                <i class="ri-time-line" style="color:var(--brand);"></i>
                <span>3h 45min</span>
              </div>
              <div style="display:flex; align-items:center; gap:8px;">
                <i class="ri-calendar-line" style="color:var(--brand);"></i>
                <span>Opera diariamente</span>
              </div>
            </div>
          </div>

          <div class="route-card">
            <div class="route-title">
              <span>Belo Horizonte → São Paulo</span>
              <span class="badge red">Manutenção</span>
            </div>
            <div class="details">
              <div style="display:flex; align-items:center; gap:8px;">
                <i class="ri-map-pin-2-line" style="color:var(--brand);"></i>
                <span>Estação Sul • Estação Central</span>
              </div>
              <div style="display:flex; align-items:center; gap:8px;">
                <i class="ri-time-line" style="color:var(--brand);"></i>
                <span>8h 15min</span>
              </div>
            </div>
            <div class="live-info">
              <i class="ri-notification-3-line" style="font-size:18px; color:var(--text-light);"></i>
              <span>Retorno previsto: 15/11</span>
            </div>
          </div>

          <div class="route-card">
            <div class="route-title">
              <span>Curitiba → Florianópolis</span>
              <span class="badge blue">Ativa</span>
            </div>
            <div class="details">
              <div style="display:flex; align-items:center; gap:8px;">
                <i class="ri-map-pin-2-line" style="color:var(--brand);"></i>
                <span>Estação Norte • Ponte Rio Grande</span>
              </div>
              <div style="display:flex; align-items:center; gap:8px;">
                <i class="ri-time-line" style="color:var(--brand);"></i>
                <span>5h 20min</span>
              </div>
              <div style="display:flex; align-items:center; gap:8px;">
                <i class="ri-calendar-line" style="color:var(--brand);"></i>
                <span>Opera diariamente</span>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

</body>

</html>