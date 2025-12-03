<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="bottom-nav">
  <a href="dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
    <img src="../assets/images/inicio_png.png" alt="Início" class="icon-img"
      style="width:24px;height:24px;margin-bottom:4px;">
    <span>Início</span>
  </a>

  <a href="avisos.php" class="<?php echo $current_page == 'avisos.php' ? 'active' : ''; ?>">
    <i class="ri-notification-3-line" style="font-size:24px; margin-bottom:4px;"></i>
    <span>Avisos</span>
  </a>

  <a href="rotas.php" class="<?php echo $current_page == 'rotas.php' ? 'active' : ''; ?>">
    <img src="../assets/images/rotas_icone.png" alt="Rotas" class="icon-img"
      style="width:24px;height:24px;margin-bottom:4px;">
    <span>Rotas</span>
  </a>



  <a href="funcionarios.php" class="<?php echo $current_page == 'funcionarios.php' ? 'active' : ''; ?>">
    <img src="../assets/images/icones_funcionarios.png" alt="Funcionários" class="icon-img"
      style="width:24px;height:24px;margin-bottom:4px;">
    <span>Funcionários</span>
  </a>

  <a href="meu_perfil.php" class="<?php echo $current_page == 'meu_perfil.php' ? 'active' : ''; ?>">
    <img src="../assets/images/icone_adm.png" alt="Perfil" class="icon-img"
      style="width:24px;height:24px;margin-bottom:4px;">
    <span>Perfil</span>
  </a>
</div>