<?php
// ============================
// Área da Instituição - InteliBolsas
// ============================

// Inicia a sessão
session_start();

// Importa a conexão com o banco MariaDB
require_once "../publico/conexao.php";

// ============================
// VERIFICA SE A INSTITUIÇÃO ESTÁ LOGADA
// ============================
if (!isset($_SESSION['id_instituicao'])) {
    // Redireciona para login caso não esteja logado
    header("Location: ../publico/login.php");
    exit;
}

// Captura o ID da instituição logada
$idInstituicao = $_SESSION['id_instituicao'];

// ============================
// CONSULTA DADOS DA INSTITUIÇÃO
// ============================
$sql = "SELECT id, nome, email, telefone, endereco 
        FROM instituicoes 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idInstituicao);
$stmt->execute();
$result = $stmt->get_result();
$dadosInstituicao = $result->fetch_assoc();

// ============================
// CONSULTA CURSOS DA INSTITUIÇÃO
// ============================
$sqlCursos = "SELECT id, titulo, descricao, acessos 
              FROM cursos 
              WHERE id_instituicao = ?";
$stmtCursos = $conn->prepare($sqlCursos);
$stmtCursos->bind_param("i", $idInstituicao);
$stmtCursos->execute();
$resultCursos = $stmtCursos->get_result();

// Contador de cursos
$totalCursos = $resultCursos->num_rows;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Área da Instituição - InteliBolsas</title>

  <!-- CSS global -->
  <link rel="stylesheet" href="../publico/css/css_global.css">

  <!-- Favicon -->
  <link rel="icon" type="image/jpg" href="../publico/imagens/logos/inteliBolsas4.jpg">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- ============================ -->
<!-- NAVBAR -->
<!-- ============================ -->
<nav class="navbar navbar-expand-lg bg-light">
  <div class="container">
    <a class="navbar-brand" href="home.php#sobre">
      <img src="../publico/imagens/logos/InteliBolsas1.jpg" class="logo-navbar" alt="Logo InteliBolsas">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup"
            aria-expanded="false" aria-label="Alternar navegação">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav ms-auto">
        <a class="nav-link NavTexto" href="../publico/home.php">Home</a>
        <a class="nav-link NavTexto" href="#Cursos">Meus Cursos</a>
      </div>
      <!-- Botão logout -->
      <form class="d-flex ms-3" action="../publico/logout.php" method="post">
        <button type="submit" class="btn btn-danger" id="btnLogout">Logout</button>
      </form>
    </div>
  </div>
</nav>

<!-- ============================ -->
<!-- HEADER -->
<!-- ============================ -->
<header class="fundo d-flex align-items-center justify-content-center text-white" style="height: 40vh;">
  <div class="container text-center">
    <h1 class="TextoPrincipal">Bem-vindo, <?= htmlspecialchars($dadosInstituicao['nome']) ?></h1>
    <p class="SubTexto">
      Você possui <strong><?= $totalCursos ?></strong> cursos cadastrados. Gerencie suas ofertas e dados institucionais.
    </p>
  </div>
</header>

<!-- ============================ -->
<!-- SEÇÃO DADOS DA INSTITUIÇÃO -->
<!-- ============================ -->
<section class="container my-5">
  <div class="Caixa">
    <h2>Meus Dados</h2>
    <form id="formDadosInstituicao" method="post" action="salvar_dados_instituicao.php">
      <div class="mb-3">
        <label for="nomeInstituicao" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nomeInstituicao" name="nome"
               value="<?= htmlspecialchars($dadosInstituicao['nome']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="emailInstituicao" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="emailInstituicao" name="email"
               value="<?= htmlspecialchars($dadosInstituicao['email']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="telefoneInstituicao" class="form-label">Telefone</label>
        <input type="tel" class="form-control" id="telefoneInstituicao" name="telefone"
               value="<?= htmlspecialchars($dadosInstituicao['telefone']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="enderecoInstituicao" class="form-label">Endereço</label>
        <input type="text" class="form-control" id="enderecoInstituicao" name="endereco"
               value="<?= htmlspecialchars($dadosInstituicao['endereco']) ?>" required>
      </div>
      <button type="submit" class="btn btn-primary">Salvar Alterações</button>
      <?php 
      if(isset($_SESSION['alterado'])){
        if($_SESSION['alterado'] == true){
          echo "<p> Dados Salvos com Sucesso</p>";
          $_SESSION['alterado'] = false;
        }
      }
      ?>
    </form>
  </div>
</section>

<!-- ============================ -->
<!-- SEÇÃO DE CURSOS CADASTRADOS -->
<!-- ============================ -->
<section class="container my-5">
  <div class="Caixa" id="Cursos">
    <h2>Meus Cursos Cadastrados (<?= $totalCursos ?>)</h2>
    <a href="cadastrar_curso.php" class="btn btn-success mb-3">Adicionar Novo Curso</a>
    <div class="row row-cols-1 row-cols-md-2 g-4">
      <?php while ($curso = $resultCursos->fetch_assoc()): ?>
        <div class="col">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($curso['titulo']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($curso['descricao']) ?></p>
              <p class="card-text"><small>Acessos: <?= $curso['acessos'] ?></small></p>
              <a href="editar_curso.php?id=<?= $curso['id'] ?>" class="btn btn-outline-primary me-2">Editar</a>
              <a href="remover_curso.php?id=<?= $curso['id'] ?>" class="btn btn-outline-danger">Remover</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<!-- ============================ -->
<!-- RODAPÉ -->
<!-- ============================ -->
<footer class="Contatos text-white py-3">
  <div class="container">
    <div class="row text-center">
      <div class="col-md-4">📞 Entre em Contato</div>
      <div class="col-md-4">© 2025 InteliBolsas | AGJLM Corporation</div>
      <div class="col-md-4">📍 Nosso Endereço</div>
    </div>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<!-- JS global -->
<script src="../publico/js/js_global.js"></script>

</body>
</html>
