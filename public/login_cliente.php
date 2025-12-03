<?php
require_once('../assets/config/db.php');
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $pass = $_POST['password'] ?? '';

  if ($email && $pass) {
    $stmt = $mysqli->prepare("SELECT id, name, email, password, role, avatar FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
      if (password_verify($pass, $row['password']) && $row['role'] === 'user') {
        $_SESSION['user'] = $row;
        header('Location: cliente_home.php');
        exit;
      } else {
        $error = 'E-mail ou senha incorretos, ou conta não é de cliente.';
      }
    } else
      $error = 'Usuário não encontrado.';
  } else
    $error = 'Preencha todos os campos.';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login do Cliente - PagTrem</title>

  <link href="../assets/css/styles.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">

</head>

<body>

  <div class="auth-card">

    <div class="brand-icon">
      <i class="ri-user-smile-line" style="font-size: 40px;"></i>
    </div>

    <h2>Login do Cliente</h2>
    <p class="text-muted" style="margin-bottom: 24px;">Acesse sua área de rotas</p>

    <?php if ($error): ?>
      <div class="badge red" style="margin-bottom: 16px; width: 100%; justify-content: center; padding: 8px;">
        <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="post">
      <label>Email</label>
      <input class="input" type="email" name="email" placeholder="cliente@pagtrem.com" required>

      <label>Senha</label>
      <input class="input" type="password" name="password" placeholder="Senha" required>
      <div style="text-align:right; margin-top:8px; font-size:0.875rem;">
        <a href="esqueci_senha.php" style="color: var(--text-light);">Esqueci minha senha</a>
      </div>

      <button class="btn" type="submit" style="width: 100%; margin-top: 24px;">Entrar</button>
    </form>

    <div class="back-links" style="margin-top: 24px; text-align: center; font-size: 0.875rem;">
      <p>Não tem conta? <a href="registrar_se.php">Registrar-se</a></p>
      <a href="login_admin.php" style="color: var(--text-light); margin-top: 12px; display: inline-block;">
        Entrar como administrador
      </a>
    </div>

  </div>

</body>

</html>