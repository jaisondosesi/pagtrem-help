<?php
require_once('../assets/config/auth.php');
require_once('../assets/config/db.php');

$feedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $name = trim($_POST['name'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $department = trim($_POST['department'] ?? '');
  $job = trim($_POST['job_title'] ?? '');

  // Avatar logic removed
  // Update
  $stmt = $mysqli->prepare("UPDATE users SET name=?, phone=?, department=?, job_title=? WHERE id=?");
  $stmt->bind_param('ssssi', $name, $phone, $department, $job, $user['id']);

  if ($stmt->execute()) {
    $_SESSION['user']['name'] = $name;
    $feedback = 'Perfil atualizado com sucesso!';
  }
}

$res = $mysqli->query("SELECT * FROM users WHERE id=" . $user['id']);
$me = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Meu Perfil - PagTrem</title>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
  <link href="../assets/css/styles.css" rel="stylesheet">
</head>

<body>

  <div class="layout-wrapper">
    <?php include '_partials/sidebar_admin.php'; ?>

    <div class="main-content">
      <!-- HEADER -->
      <div class="top-header">
        <h1><i class="ri-user-settings-line"></i> Meu Perfil</h1>
      </div>

      <div class="container" style="padding-bottom: 100px;">

        <?php if ($feedback): ?>
          <div class="badge success" style="margin-bottom: 24px; width: 100%; justify-content: center; padding: 12px;">
            <?php echo $feedback; ?>
          </div>
        <?php endif; ?>

        <div class="card">
          <form method="post">

            <div style="text-align:center; margin-bottom:24px;">
              <div
                style="width:100px; height:100px; background:var(--brand-bg); color:var(--brand); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:40px; border:1px solid var(--brand-light); margin: 0 auto;">
                <i class="ri-user-line"></i>
              </div>
            </div>

            <label>Nome Completo</label>
            <input class="input" name="name" value="<?php echo htmlspecialchars($me['name']); ?>"
              placeholder="Nome completo">

            <label>E-mail</label>
            <input class="input" type="email" disabled value="<?php echo htmlspecialchars($me['email']); ?>"
              style="background: var(--bg);">

            <label>Telefone</label>
            <input class="input" name="phone" value="<?php echo htmlspecialchars($me['phone']); ?>"
              placeholder="Telefone">

            <label>Departamento</label>
            <input class="input" name="department" value="<?php echo htmlspecialchars($me['department']); ?>"
              placeholder="Departamento">

            <label>Cargo</label>
            <input class="input" name="job_title" value="<?php echo htmlspecialchars($me['job_title']); ?>"
              placeholder="Cargo">

            <button class="btn" style="width: 100%; margin-top: 24px;">Salvar Alterações</button>

          </form>
        </div>
      </div>
    </div>
  </div>

</body>

</html>