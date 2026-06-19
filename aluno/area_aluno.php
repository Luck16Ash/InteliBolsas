<?php
// ============================
// Área do Aluno - InteliBolsas
// ============================

session_start();

// ============================
// IMPORTA CONEXÃO COM O BANCO
// ============================
// Corrigido o caminho relativo, pois "conexao.php" está na pasta principal
require_once __DIR__ . '/../publico/conexao.php';


// ============================
// VERIFICA SE O ALUNO ESTÁ LOGADO
// ============================
if (!isset($_SESSION['id_aluno'])) {
    header("Location: ../publico/login.php");
    exit;
}


$idAluno = $_SESSION['id_aluno'];

// ============================
// CONSULTA DADOS DO ALUNO
// ============================
$sql = "SELECT id, nome, email, telefone, area_interesse 
        FROM alunos 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idAluno);
$stmt->execute();
$result = $stmt->get_result();
$dadosAluno = $result->fetch_assoc();

// ============================
// CONSULTA CURSOS FAVORITOS
// ============================
$sqlFavoritos = "SELECT cursos.id, cursos.titulo, cursos.descricao
                 FROM cursos
                 INNER JOIN favoritos ON cursos.id = favoritos.curso_id
                 WHERE favoritos.aluno_id = ?";
$stmtFav = $conn->prepare($sqlFavoritos);
$stmtFav->bind_param("i", $idAluno);
$stmtFav->execute();
$resultFavoritos = $stmtFav->get_result();

// ============================
// CONSULTA INSCRIÇÕES
// ============================
$sqlInscricoes = "SELECT cursos.id AS curso_id, cursos.titulo, instituicoes.nome AS instituicao, inscricoes.data, inscricoes.status
                  FROM inscricoes
                  INNER JOIN cursos ON cursos.id = inscricoes.curso_id
                  INNER JOIN instituicoes ON instituicoes.id = cursos.id_instituicao
                  WHERE inscricoes.aluno_id = ?";
$stmtIns = $conn->prepare($sqlInscricoes);
$stmtIns->bind_param("i", $idAluno);
$stmtIns->execute();
$resultInscricoes = $stmtIns->get_result();

// ============================
// CONTADOR DE INSCRIÇÕES
// ============================
$totalInscricoes = $resultInscricoes->num_rows;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Área do Aluno - InteliBolsas</title>

  <!-- CSS global -->
  <link rel="stylesheet" href="../publico/css/css_global.css">

  <!-- Favicon -->
  <link rel="icon" type="image/jpg" href="../publico\imagens/logos/inteliBolsas4.jpg">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- ============================ -->
<!-- NAVBAR -->
<!-- ============================ -->
<nav class="navbar navbar-expand-lg bg-light">
  <div class="container">
    <a class="navbar-brand" href="../publico\home.php#sobre">
      <img src="../publico/imagens/logos/InteliBolsas1.jpg" class="logo-navbar" alt="Logo InteliBolsas">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup"
            aria-expanded="false" aria-label="Alternar navegação">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav ms-auto">
        <a class="nav-link NavTexto" href="../publico\home.php">Home</a>
        <a class="nav-link NavTexto" href="../instituicao\cursos.php">Cursos</a>
               
      </div>
      <form class="d-flex ms-3" action="../publico\logout.php" method="post">
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
    <h1 class="TextoPrincipal">Bem-vindo, <?= htmlspecialchars($dadosAluno['nome']) ?></h1>
    <p class="SubTexto">
      Você possui <strong><?= $totalInscricoes ?></strong> inscrições.
    </p>
  </div>
</header>

<!-- ============================ -->
<!-- SEÇÃO DADOS DO ALUNO -->
<!-- ============================ -->
<section class="container my-5">
  <div class="Caixa" id="Alter">
    <h2>Meus Dados</h2>
    <form id="formDadosAluno" method="post" action="../aluno/atualizar_dados_aluno.php">
      <div class="mb-3">
        <label for="nomeAluno" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nomeAluno" name="nome"
               value="<?= htmlspecialchars($dadosAluno['nome']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="emailAluno" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="emailAluno" name="email"
               value="<?= htmlspecialchars($dadosAluno['email']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="telefoneAluno" class="form-label">Telefone</label>
        <input type="tel" class="form-control" id="telefoneAluno" name="telefone"
               value="<?= htmlspecialchars($dadosAluno['telefone']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="areaInteresse" class="form-label">Área de Interesse</label>
        <input type="text" class="form-control" id="areaInteresse" name="area_interesse"
               value="<?= htmlspecialchars($dadosAluno['area_interesse']) ?>" required>
      </div>
      <button type="submit" class="btn btn-primary">Salvar Alterações</button>
      <?php 
      if(isset($_SESSION['alterado'])){
        if($_SESSION['alterado'] == true){
          echo "<p class='text-success mt-2'>Dados salvos com sucesso!</p>";
          $_SESSION['alterado'] = false;
        }
      }
      ?>
    </form>
  </div>
</section>

<!-- ============================ -->
<!-- SEÇÃO DE CURSOS FAVORITOS -->
<!-- ============================ -->
<section class="container my-5">
  <div class="Caixa">
    <h2>Meus Cursos Favoritos</h2>
    <div class="row row-cols-1 row-cols-md-2 g-4">
      <?php while ($curso = $resultFavoritos->fetch_assoc()): ?>
        <div class="col">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($curso['titulo']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($curso['descricao']) ?></p>
              <a href="../ver_curso.php?id=<?= $curso['id'] ?>" class="btn btn-outline-primary me-2">Ver Curso</a>
              <a href="../aluno/remover_favorito.php?id=<?= $curso['id'] ?>" class="btn btn-outline-danger">Remover Favorito</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<!-- ============================ -->
<!-- SEÇÃO DE INSCRIÇÕES -->
<!-- ============================ -->
<section class="container my-5">
  <div class="Caixa">
    <h2>Minhas Inscrições (<?= $totalInscricoes ?>)</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Curso</th>
          <th>Instituição</th>
          <th>Data de Inscrição</th>
          <th>Status</th>
          <th>Ação</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($insc = $resultInscricoes->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($insc['titulo']) ?></td>
          <td><?= htmlspecialchars($insc['instituicao']) ?></td>
          <td><?= date("d/m/Y", strtotime($insc['data'])) ?></td>
          <td><?= htmlspecialchars($insc['status']) ?></td>
          <td>
            <a href="../aluno/cancelar_inscricao.php?id=<?= $insc['curso_id'] ?>" class="btn btn-danger btn-sm">Cancelar</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</section>

<!-- ============================ -->
<!-- RODAPÉ -->
<!-- ============================ -->
<footer class="Contatos text-white py-3">
  <div class="container text-center">
    <p>© 2025 InteliBolsas | AGJLM Corporation</p>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<!-- JS global -->
<script src="../publico\js/js_global.js"></script>

</body>
</html>
