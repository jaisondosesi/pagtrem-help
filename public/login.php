<?php
require_once('../assets/config/db.php');
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';
  $login_type = $_POST['login_type'] ?? 'cliente';

  if ($email && $pass) {
    $stmt = $mysqli->prepare("SELECT id, name, email, password, role, avatar FROM users WHERE email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {

      if (password_verify($pass, $row['password'])) {

        if (($login_type === 'cliente' && $row['role'] !== 'user')) {
          $error = 'Essa conta é de administrador. Use a tela de login de usuário.';
        } elseif (($login_type === 'usuario' && $row['role'] !== 'admin')) {
          $error = 'Essa conta é de cliente. Use o login de cliente.';
        } else {

          $_SESSION['user'] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'role' => $row['role'],
            'avatar' => $row['avatar']
          ];

          if ($row['role'] === 'admin') {
            header('Location: dashboard.php');
          } else {
            header('Location: cliente_home.php');
          }
          exit;
        }

      } else {
        $error = 'Senha incorreta.';
      }

    } else {
      $error = 'Usuário não encontrado.';
    }

  } else {
    $error = 'Preencha todos os campos.';
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - PagTrem</title>

  <link href="../assets/css/styles.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body>

  <div class="auth-card">

    <div class="brand-icon">
      <i class="ri-train-line" style="font-size: 40px;"></i>
    </div>

    <h2>PagTrem</h2>
    <p class="text-muted" style="margin-bottom: 24px;">Acesso ao sistema</p>

    <?php if ($error): ?>
      <div class="badge red" style="margin-bottom: 16px; width: 100%; justify-content: center; padding: 8px;">
        <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="tab-switch"
      style="display: flex; gap: 8px; margin-bottom: 24px; background: var(--bg); padding: 4px; border-radius: var(--radius-sm);">
      <button type="button" id="tab-cliente" class="btn secondary" style="flex: 1; border: none; box-shadow: none;"
        onclick="showTab('cliente')">Cliente</button>
      <a href="login_admin.php" id="tab-usuario" class="btn secondary"
        style="flex: 1; border: none; box-shadow: none; text-decoration: none; justify-content: center;">Admin</a>
    </div>

    <!-- Form CLIENTE -->
    <form id="form-cliente" method="post">
      <input type="hidden" name="login_type" value="cliente">

      <label>Email</label>
      <input class="input" type="email" name="email" placeholder="cliente@pagtrem.com" required>

      <label>Senha</label>
      <input class="input" type="password" name="password" placeholder="Sua senha" required>
      <div style="text-align:right; margin-top:8px; font-size:0.875rem;">
        <a href="esqueci_senha.php" style="color: var(--text-light);">Esqueci minha senha</a>
      </div>

      <button class="btn" type="submit" style="width: 100%; margin-top: 24px;">Entrar</button>

      <p class="auth-note" style="margin-top: 24px; font-size: 0.875rem;">
        Não tem conta? <a href="registrar_se.php">Registrar-se</a>
      </p>
    </form>

  </div>

  <script>
    function showTab(type) {
      document.getElementById('form-cliente').style.display = 'block';
      document.getElementById('tab-cliente').classList.add('active');
      document.getElementById('tab-usuario').classList.remove('active');
    }
  </script>

</body>

</html>