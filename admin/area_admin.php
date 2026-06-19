<?php
// ======= INÍCIO DO PHP =======
session_start();
include '..\publico\conexao.php';

// ======= VALIDAÇÃO DE LOGIN ADMIN =======
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] != 'admin') {
    header("Location: ..\publico\login.php");
    exit;
}

// ======= CONSULTAS PRINCIPAIS =======
$sqlInscricoes = "SELECT COUNT(*) AS total_inscricoes FROM inscricoes";
$totalInscricoes = $conn->query($sqlInscricoes)->fetch_assoc()['total_inscricoes'];

$sqlUsuarios = "SELECT * FROM usuarios WHERE tipo='aluno'";
$usuariosResult = $conn->query($sqlUsuarios);
$totalUsuarios = $usuariosResult->num_rows;

$sqlInstituicoes = "SELECT * FROM instituicoes";
$instituicoesResult = $conn->query($sqlInstituicoes);
$totalInstituicoes = $instituicoesResult->num_rows;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Área do Admin - InteliBolsas</title>
<link rel="stylesheet" href="..\publico\css/css_global.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="image/jpg" href="..\publico\imagens/logos/inteliBolsas4.jpg">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg bg-light luminaria">
  <div class="container">
    <a class="navbar-brand" href="..\publico\home.php">
      <img src="..\publico\imagens/logos/InteliBolsas1.jpg" class="logo-navbar" alt="Logo InteliBolsas">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
      data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" 
      aria-expanded="false" aria-label="Alternar navegação">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav ms-auto">
        <a class="nav-link NavTexto" href="..\publico\home.php">Home</a>
        <a class="nav-link NavTexto" href="area_admin.php">Admin</a>
      </div>
      <form class="d-flex ms-3" action="../publico/logout.php" method="post">
        <button type="submit" class="btn btn-danger">Logout</button>
      </form>
    </div>
  </div>
</nav>

<!-- HEADER -->
<header class="fundo text-center text-white py-5 luminaria">
  <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario']) ?>!</h1>
  <p class="fs-5">Painel Administrativo do InteliBolsas</p>
</header>

<!-- PAINEL ADMIN -->
<main class="container my-5">
  <div class="row g-4">
    <!-- Card: Total de Inscrições -->
    <div class="col-md-4">
      <div class="Caixa text-center luminaria">
        <h3>Total de Inscrições</h3>
        <p class="display-6 text-primary"><?= $totalInscricoes ?></p>
      </div>
    </div>
    <!-- Card: Total de Usuários -->
    <div class="col-md-4">
      <div class="Caixa text-center luminaria">
        <h3>Total de Usuários</h3>
        <p class="display-6 text-primary"><?= $totalUsuarios ?></p>
      </div>
    </div>
    <!-- Card: Total de Instituições -->
    <div class="col-md-4">
      <div class="Caixa text-center luminaria">
        <h3>Total de Instituições</h3>
        <p class="display-6 text-primary"><?= $totalInstituicoes ?></p>
      </div>
    </div>
  </div>

  <hr>

  <!-- LISTA DE USUÁRIOS (ALUNOS) -->
  <h3 class="mt-5">Gerenciar Usuários</h3>
  <div class="row">
    <?php while($usuario = $usuariosResult->fetch_assoc()): ?>
      <div class="col-md-4 mb-3">
        <div class="Caixa luminaria p-3">
          <h5><?= htmlspecialchars($usuario['usuario']) ?></h5>
          <p>Tipo: <?= htmlspecialchars($usuario['tipo']) ?></p>
          <div class="d-flex justify-content-center gap-2">
            <a href="editar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
            <a href="suspender_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-warning btn-sm">Suspender</a>
            <a href="deletar_usuario.php?id=<?= $usuario['id'] ?>" class="btn btn-danger btn-sm">Deletar</a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

  <hr>

  <!-- LISTA DE INSTITUIÇÕES -->
  <h3 class="mt-5">Gerenciar Instituições</h3>
  <div class="row">
    <?php while($inst = $instituicoesResult->fetch_assoc()): ?>
      <div class="col-md-4 mb-3">
        <div class="Caixa luminaria p-3">
          <h5><?= htmlspecialchars($inst['nome']) ?></h5>
          <p>Email: <?= htmlspecialchars($inst['email']) ?></p>
          <div class="d-flex justify-content-center gap-2">
            <a href="../instituicao/editar_instituicao.php?id=<?= $inst['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
            <a href="../instituicao/suspender_instituicao.php?id=<?= $inst['id'] ?>" class="btn btn-warning btn-sm">Suspender</a>
            <a href="../instituicao/deletar_instituicao.php?id=<?= $inst['id'] ?>" class="btn btn-danger btn-sm">Deletar</a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>

</main>

<!-- FOOTER -->
<footer class="Contatos text-white py-3 text-center">
  <p>&copy; 2025 InteliBolsas | AGJLM Corporation</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/js_global.js"></script>
</body>
</html>
