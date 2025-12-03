<?php
require_once('../assets/config/auth.php');
require_once('../assets/config/db.php');

// PROCESSAR FORMULÁRIO DE AVISOS
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  $id = $_POST['id'] ?? '';
  $title = trim($_POST['title'] ?? '');
  $body = trim($_POST['body'] ?? '');
  $tag = $_POST['tag'] ?? 'Sistema';

  if ($action === 'update' && $id) {
    $stmt = $mysqli->prepare("UPDATE notices SET title=?, body=?, tag=? WHERE id=?");
    $stmt->bind_param('sssi', $title, $body, $tag, $id);
    $stmt->execute();
  } elseif ($action === 'delete' && $id) {
    $stmt = $mysqli->prepare("DELETE FROM notices WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
  }

  header('Location: dashboard.php');
  exit;
}

// DELETE VIA GET (Opcional, mas mantendo padrão)
if (isset($_GET['delete_notice'])) {
  $id = (int) $_GET['delete_notice'];
  $mysqli->query("DELETE FROM notices WHERE id=$id");
  header('Location: dashboard.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - PagTrem</title>

  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
  <link href="../assets/css/styles.css" rel="stylesheet">

</head>

<body>

  <div class="layout-wrapper">
    <?php include '_partials/sidebar_admin.php'; ?>

    <div class="main-content">
      <!-- HEADER -->
      <div class="top-header">
        <h1><i class="ri-dashboard-line"></i> Dashboard</h1>
      </div>

      <div class="container">

        <?php
        $routesAtivas = $mysqli->query("SELECT COUNT(*) AS total FROM routes WHERE status='ativa'")->fetch_assoc()['total'];
        $notices_count = $mysqli->query("SELECT COUNT(*) AS total FROM notices")->fetch_assoc()['total'];
        $employees = $mysqli->query("SELECT COUNT(*) AS total FROM employees")->fetch_assoc()['total'];
        ?>

        <!-- STATS -->
        <div class="stats-grid">
          <div class="stat-card">
            <i class="ri-route-line"></i>
            <div class="stat-value"><?php echo $routesAtivas; ?></div>
            <div class="stat-label">Rotas Ativas</div>
          </div>

          <div class="stat-card">
            <i class="ri-group-line"></i>
            <div class="stat-value"><?php echo $employees; ?></div>
            <div class="stat-label">Funcionários</div>
          </div>

          <div class="stat-card">
            <i class="ri-notification-3-line"></i>
            <div class="stat-value"><?php echo $notices_count; ?></div>
            <div class="stat-label">Avisos</div>
          </div>
        </div>

        <!-- AVISOS RECENTES -->
        <div class="recent-section">
          <h2>Avisos Recentes</h2>
          <?php
          $res = $mysqli->query("SELECT * FROM notices ORDER BY id DESC LIMIT 5");
          if ($res->num_rows > 0) {
            while ($n = $res->fetch_assoc()) {
              $badge = match ($n['tag']) {
                'Manutenção' => '<span class="badge red">Manutenção</span>',
                'Novidades' => '<span class="badge blue">Novidades</span>',
                default => '<span class="badge">Sistema</span>',
              };

              // Prepara dados para o JS
              $jsonData = htmlspecialchars(json_encode($n), ENT_QUOTES, 'UTF-8');

              echo "
              <div class='notice-card'>
                <div class='notice-top'>
                  <div class='notice-title'>" . htmlspecialchars($n['title']) . "</div>
                  $badge
                </div>
                <div class='notice-body'>" . nl2br(htmlspecialchars($n['body'])) . "</div>
                <div class='notice-date'>" . date('d/m/Y H:i', strtotime($n['created_at'])) . "</div>
                
                <div style='position:absolute; top:20px; right:20px; cursor:pointer;' onclick='editNotice($jsonData)'>
                  <i class='ri-pencil-line' style='color:var(--muted); font-size:20px;'></i>
                </div>
              </div>";
            }
          } else {
            echo "<p class='text-muted'>Nenhum aviso recente.</p>";
          }
          ?>
        </div>

        <!-- ATIVIDADES RECENTES -->
        <div class="recent-section">
          <h2>Atividades Recentes</h2>

          <div class="recent-item">
            <i class="ri-train-line" style="font-size:24px; color:var(--brand);"></i>
            <div>
              <span style="font-weight:600; display:block;">Trem #4321</span>
              <span class="text-muted" style="font-size:0.9rem;">Partiu para Curitiba às 09:10</span>
            </div>
          </div>

          <div class="recent-item">
            <i class="ri-user-add-line" style="font-size:24px; color:var(--success);"></i>
            <div>
              <span style="font-weight:600; display:block;">Novo funcionário</span>
              <span class="text-muted" style="font-size:0.9rem;">Cadastrado em Operações às 09:00</span>
            </div>
          </div>

          <div class="recent-item">
            <i class="ri-notification-3-line" style="font-size:24px; color:var(--warning);"></i>
            <div>
              <span style="font-weight:600; display:block;">Câmera #7</span>
              <span class="text-muted" style="font-size:0.9rem;">Voltou ao status Online às 08:52</span>
            </div>
          </div>

          <div class="recent-item">
            <i class="ri-tools-line" style="font-size:24px; color:var(--danger);"></i>
            <div>
              <span style="font-weight:600; display:block;">Manutenção Agendada</span>
              <span class="text-muted" style="font-size:0.9rem;">Rota SP → Campinas às 08:47</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- MODAL AVISOS -->
  <div class="modal-bg" id="noticeModal">
    <div class="modal" onclick="event.stopPropagation()">
      <h2 id="modalTitle" style="margin-bottom: 24px;">Editar Aviso</h2>
      <form method="post">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" id="noticeId">

        <label>Título</label>
        <input class="input" name="title" id="noticeTitle" required>

        <label>Tag</label>
        <select class="select" name="tag" id="noticeTag">
          <option value="Sistema">Sistema</option>
          <option value="Manutenção">Manutenção</option>
          <option value="Novidades">Novidades</option>
        </select>

        <label>Mensagem</label>
        <textarea class="textarea" name="body" id="noticeBody" rows="4" required></textarea>

        <div style="display:flex; gap:12px; margin-top:24px;">
          <button type="button" class="btn secondary" style="flex:1;" onclick="closeNoticeModal()">Cancelar</button>
          <button type="submit" class="btn" style="flex:1;">Salvar</button>
        </div>

        <div style="margin-top:16px; text-align:center;">
          <a href="#" id="deleteNoticeLink" class="btn secondary"
            style="color:var(--danger); border-color:var(--danger-bg); width:100%;">Excluir Aviso</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    const noticeModal = document.getElementById("noticeModal");
    const noticeId = document.getElementById("noticeId");
    const noticeTitle = document.getElementById("noticeTitle");
    const noticeTag = document.getElementById("noticeTag");
    const noticeBody = document.getElementById("noticeBody");
    const deleteNoticeLink = document.getElementById("deleteNoticeLink");

    function editNotice(data) {
      noticeId.value = data.id;
      noticeTitle.value = data.title;
      noticeTag.value = data.tag;
      noticeBody.value = data.body;

      deleteNoticeLink.href = "?delete_notice=" + data.id;
      deleteNoticeLink.onclick = function (e) {
        if (!confirm('Tem certeza que deseja excluir este aviso?')) {
          e.preventDefault();
        }
      };

      noticeModal.style.display = "flex";
    }

    function closeNoticeModal() {
      noticeModal.style.display = "none";
    }

    window.addEventListener("click", function (e) {
      if (e.target === noticeModal) {
        closeNoticeModal();
      }
    });
  </script>

</body>

</html>