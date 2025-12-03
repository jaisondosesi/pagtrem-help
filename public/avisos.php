<?php
require_once('../assets/config/auth.php');
require_once('../assets/config/db.php');

$success_msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $body = trim($_POST['body'] ?? '');
  $tag = $_POST['tag'] ?? 'Sistema';

  if ($title && $body) {
    $stmt = $mysqli->prepare("INSERT INTO notices(title, body, tag) VALUES (?, ?, ?)");
    $stmt->bind_param('sss', $title, $body, $tag);
    if ($stmt->execute()) {
      $success_msg = "Aviso criado com sucesso!";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Avisos - PagTrem</title>

  <link href="../assets/css/styles.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">

</head>

<body>

  <div class="layout-wrapper">
    <?php include '_partials/sidebar_admin.php'; ?>

    <div class="main-content">
      <div class="top-header">
        <h1><i class="ri-notification-3-line"></i> Avisos</h1>
      </div>

      <div class="container">

        <?php if ($success_msg): ?>
          <div class="badge success" style="margin-bottom: 24px; width: 100%; justify-content: center; padding: 12px;">
            <?php echo $success_msg; ?>
          </div>
        <?php endif; ?>

        <div class="card" style="margin-bottom: 32px;">
          <h2 style="margin-bottom: 16px;">Criar Novo Aviso</h2>
          <form method="post">
            <label>Título</label>
            <input class="input" name="title" placeholder="Título do aviso" required>

            <label>Categoria</label>
            <select class="select" name="tag">
              <option>Manutenção</option>
              <option>Novidades</option>
              <option selected>Sistema</option>
            </select>

            <label>Mensagem</label>
            <textarea class="textarea" name="body" rows="4" placeholder="Escreva o aviso..." required></textarea>

            <button class="btn" style="margin-top:24px; width:100%;">Publicar Aviso</button>
          </form>
        </div>

        <h3 style="margin-bottom: 16px; padding-left: 4px;">Histórico de Avisos</h3>

        <div class="recent-section">
          <?php
          $res = $mysqli->query("SELECT * FROM notices ORDER BY id DESC");
          if ($res->num_rows > 0) {
            while ($n = $res->fetch_assoc()) {
              $badge = match ($n['tag']) {
                'Manutenção' => '<span class="badge red">Manutenção</span>',
                'Novidades' => '<span class="badge blue">Novidades</span>',
                default => '<span class="badge">Sistema</span>',
              };

              echo "
              <div class='notice-card'>
                <div class='notice-top'>
                  <div class='notice-title'>" . htmlspecialchars($n['title']) . "</div>
                  $badge
                </div>
                <div class='notice-body'>" . nl2br(htmlspecialchars($n['body'])) . "</div>
                <div class='notice-date'>" . date('d/m/Y H:i', strtotime($n['created_at'])) . "</div>
              </div>";
            }
          } else {
            echo "<p class='text-muted' style='padding-left: 4px;'>Nenhum aviso registrado.</p>";
          }
          ?>
        </div>

      </div>
    </div>
  </div>

</body>

</html>