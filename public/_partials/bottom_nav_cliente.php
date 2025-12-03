<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="bottom-nav">
  <a href="cliente_home.php" class="<?php echo $current_page == 'cliente_home.php' ? 'active' : ''; ?>">
    <i class="ri-map-pin-line"></i>
    <span>Rotas</span>
  </a>

  <a href="perfil_cliente.php" class="<?php echo $current_page == 'perfil_cliente.php' ? 'active' : ''; ?>">
    <i class="ri-user-smile-line"></i>
    <span>Perfil</span>
  </a>

  <a href="logout.php">
    <i class="ri-logout-box-r-line"></i>
    <span>Sair</span>
  </a>
</div>