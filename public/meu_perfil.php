<?php
require_once('../assets/config/auth.php');
require_once('../assets/config/db.php');

$feedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $name = trim($_POST['name'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $department = trim($_POST['department'] ?? '');
  $job = trim($_POST['job_title'] ?? '');

  // Avatar
  $avatarPath = $user['avatar'] ?? null;

  // 1. Upload
  if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
    $fname = 'u' . $user['id'] . '_' . time() . '.' . $ext;
    $dest = '../assets/uploads/profile_photos/' . $fname;

    // Ensure directory exists
    if (!is_dir(dirname($dest))) {
      mkdir(dirname($dest), 0777, true);
    }

    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) {
      $avatarPath = $fname;
    }
  }
  // 2. Galeria
  elseif (!empty($_POST['selected_photo'])) {
    $avatarPath = $_POST['selected_photo'];
  }

  // Update
  $stmt = $mysqli->prepare("UPDATE users SET name=?, phone=?, department=?, job_title=?, avatar=? WHERE id=?");
  $stmt->bind_param('sssssi', $name, $phone, $department, $job, $avatarPath, $user['id']);

  if ($stmt->execute()) {
    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['avatar'] = $avatarPath;
    $feedback = 'Perfil atualizado com sucesso!';
  }
}

$res = $mysqli->query("SELECT * FROM users WHERE id=" . $user['id']);
$me = $res->fetch_assoc();

// Determine current avatar URL for display
$currentAvatar = $me['avatar'];
if ($currentAvatar && strpos($currentAvatar, 'assets/') === 0) {
  $displayAvatar = '../' . $currentAvatar;
} elseif ($currentAvatar) {
  $displayAvatar = '../assets/uploads/profile_photos/' . $currentAvatar;
} else {
  $displayAvatar = '../assets/uploads/profile_photos/avatar-default.png';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Meu Perfil - PagTrem</title>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
  <link href="../assets/css/styles.css" rel="stylesheet">

  <style>
    .gallery-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 12px;
      margin-top: 12px;
      margin-bottom: 24px;
    }

    .gallery-item {
      cursor: pointer;
      border: 2px solid transparent;
      border-radius: 50%;
      padding: 2px;
      transition: all 0.2s;
    }

    .gallery-item:hover {
      transform: scale(1.1);
    }

    .gallery-item.selected {
      border-color: var(--brand);
      transform: scale(1.1);
    }

    .gallery-item img {
      width: 100%;
      height: auto;
      border-radius: 50%;
      display: block;
    }
  </style>
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
          <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="selected_photo" id="selectedPhoto">

            <div style="text-align:center; margin-bottom:24px;">
              <img id="preview" src="<?php echo $displayAvatar; ?>"
                style="width:100px; height:100px; border-radius:50%; object-fit:cover; border:4px solid var(--surface); box-shadow: var(--shadow);">
              <br>
              <label for="photoInput" class="btn secondary"
                style="display:inline-block; width:auto; padding:8px 16px; font-size:0.875rem; margin-top:16px;">Alterar
                Foto</label>
              <input type="file" name="avatar" id="photoInput" class="file-input" accept="image/*" style="display:none;"
                onchange="handleFileUpload(this)">
            </div>

            <!-- Opções de Foto (Galeria) -->
            <div style="margin-bottom:24px; text-align:center;">
              <span style="font-size:0.875rem; color:var(--text-light); display:block; margin-bottom:8px;">Ou escolha um
                avatar:</span>
              <div class="gallery-grid">
                <div class="gallery-item" onclick="selectGalleryPhoto('assets/images/funcionario1.png', this)">
                  <img src="../assets/images/funcionario1.png">
                </div>
                <div class="gallery-item" onclick="selectGalleryPhoto('assets/images/funcionario2.png', this)">
                  <img src="../assets/images/funcionario2.png">
                </div>
                <div class="gallery-item" onclick="selectGalleryPhoto('assets/images/funcionario3.png', this)">
                  <img src="../assets/images/funcionario3.png">
                </div>
                <div class="gallery-item" onclick="selectGalleryPhoto('assets/images/funcionario4.png', this)">
                  <img src="../assets/images/funcionario4.png">
                </div>
                <div class="gallery-item" onclick="selectGalleryPhoto('assets/images/funcionario5.png', this)">
                  <img src="../assets/images/funcionario5.png">
                </div>
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

  <script>
    const preview = document.getElementById("preview");
    const selectedPhotoInput = document.getElementById("selectedPhoto");
    const photoInput = document.getElementById("photoInput");

    function selectGalleryPhoto(path, element) {
      // Atualiza preview
      preview.src = "../" + path;
      // Define valor no input hidden
      selectedPhotoInput.value = path;
      // Limpa input file
      photoInput.value = "";

      // Visual selection
      document.querySelectorAll('.gallery-item').forEach(el => el.classList.remove('selected'));
      element.classList.add('selected');
    }

    function handleFileUpload(input) {
      if (input.files && input.files[0]) {
        preview.src = window.URL.createObjectURL(input.files[0]);
        // Limpa seleção da galeria
        selectedPhotoInput.value = "";
        document.querySelectorAll('.gallery-item').forEach(el => el.classList.remove('selected'));
      }
    }
  </script>

</body>

</html>