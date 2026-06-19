<?php
session_start();
require_once "conexao.php";

// ============================
// Processa login
// ============================
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['usuario'], $_POST['senha'])) {
    $login = trim($_POST['usuario']);
    $senha = $_POST['senha'];
    $dadosUsuario = null;

    // ============================
    // Verifica em usuarios (admin/instituicao)
    // ============================
    // Verifica em usuarios (admin)
  $sql = "SELECT id, usuario, senha, tipo FROM usuarios WHERE usuario = ?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $login);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows > 0) {
      $dadosUsuario = $result->fetch_assoc();
  } else {
      // Verifica em instituicoes
      $sqlInst = "SELECT id, nome, email, senha FROM instituicoes WHERE email = ?";
      $stmtInst = $conn->prepare($sqlInst);
      $stmtInst->bind_param("s", $login);
      $stmtInst->execute();
      $resultInst = $stmtInst->get_result();

      if ($resultInst && $resultInst->num_rows > 0) {
          $inst = $resultInst->fetch_assoc();
          $dadosUsuario = [
              'id'       => $inst['id'],
              'name'     => $inst['nome'],
              'usuario'  => $inst['email'],
              'senha'    => $inst['senha'],
              'tipo'     => 'instituicao'
          ];
      } else {
          // Verifica em alunos
          $sqlAluno = "SELECT id, nome, email, senha FROM alunos WHERE email = ?";
          $stmtAluno = $conn->prepare($sqlAluno);
          $stmtAluno->bind_param("s", $login);
          $stmtAluno->execute();
          $resultAluno = $stmtAluno->get_result();

          if ($resultAluno && $resultAluno->num_rows > 0) {
              $aluno = $resultAluno->fetch_assoc();
              $dadosUsuario = [
                  'id'      => $aluno['id'],
                  'name'    => $aluno['nome'],
                  'usuario' => $aluno['email'],
                  'senha'   => $aluno['senha'],
                  'tipo'    => 'aluno'
              ];
          }
      }
  
}

    // ============================
    // Confere senha
    // ============================
    echo "<pre>";
    var_dump($dadosUsuario);
    echo "</pre>";
    if ($dadosUsuario && ($senha === $dadosUsuario['senha'] || password_verify($senha, $dadosUsuario['senha']))) { 

        // ============================
        // Armazena sessão de acordo com o tipo de usuário
        // ============================
        switch ($dadosUsuario['tipo']) {
            case "admin":
                $_SESSION['id_usuario'] = $dadosUsuario['id'];
                $_SESSION['usuario']    = $dadosUsuario['usuario'];
                $_SESSION['nome']       = $dadosUsuario['name'];
                $_SESSION['tipo']       = "admin";
                header("Location: ..\admin\area_admin.php");
                exit;

            case "instituicao":
                $_SESSION['id_instituicao'] = $dadosUsuario['id'];
                $_SESSION['usuario']    = $dadosUsuario['usuario'];
                $_SESSION['nome']       = $dadosUsuario['name'];
                $_SESSION['tipo']       = $dadosUsuario['tipo'];
                header("Location: ..\instituicao\area_instituicao.php");
                exit;

            case "aluno":
                $_SESSION['id_aluno'] = $dadosUsuario['id']; // CORREÇÃO: id_aluno
                $_SESSION['usuario']  = $dadosUsuario['usuario'];
                $_SESSION['nome']     = $dadosUsuario['name'];
                $_SESSION['tipo']     = "aluno";
                header("Location: ..\aluno\area_aluno.php");
                exit;

            default:
                $_SESSION['erro_login'] = "Tipo de usuário inválido.";
                header("Location: login.php");
                exit;
        }

    } else {
        $_SESSION['erro_login'] = "Usuário ou senha incorretos!";
        header("Location: login.php");
        exit;
    }
}
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>InteliBolsas - Login e Cadastro</title>
  <link rel="stylesheet" href="css/css_global.css">
  <link rel="icon" type="image/png" href="imagens/logos/inteliBolsas4.jpg">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- ============================ -->
<!-- NAVBAR -->
<!-- ============================ -->
<nav class="navbar navbar-expand-lg bg-light">
  <div class="container">
    <a class="navbar-brand" href="home.php#sobre">
      <img src="imagens/logos/InteliBolsas1.jpg" class="logo-navbar" alt="Logo InteliBolsas">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
      data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" 
      aria-expanded="false" aria-label="Alternar navegação">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav ms-auto">
        <a class="nav-link NavTexto" href="home.php">Home</a>
      </div>
    </div>
  </div>
</nav>

<!-- ============================ -->
<!-- HEADER -->
<!-- ============================ -->
<header class="fundo d-flex align-items-center text-center text-white" style="height: 40vh;">
  <div class="container">
    <h1 class="TextoPrincipal">Acesse sua Conta</h1>
    <p class="SubTexto">Entre com seu login (usuário ou e-mail) ou cadastre-se gratuitamente.</p>
  </div>
</header>

<!-- ============================ -->
<!-- CONTEÚDO PRINCIPAL -->
<!-- ============================ -->
<main class="container my-5">
  <div class="row">

    <!-- Login -->
    <div class="col-md-6 mb-4">
      <div class="Caixa">
        <h2>Login</h2>
        <form method="post" action="login.php">
          <input type="text" name="usuario" class="form-control mb-3" placeholder="E-mail" required>
          <input type="password" name="senha" class="form-control mb-3" placeholder="Senha" required>
          <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>

        <!-- Mensagem de erro -->
        <?php
        if (isset($_SESSION['erro_login'])) {
            echo "<p style='color:red;'>{$_SESSION['erro_login']}</p>";
            unset($_SESSION['erro_login']);
        }
        ?>
      </div>
    </div>

    <!-- Cadastro -->
    <div class="col-md-6 mb-4">
      <div class="Caixa">
        <h2>Criar Conta</h2>
        <ul class="nav nav-tabs" id="cadastroTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="aluno-tab" data-bs-toggle="tab" data-bs-target="#aluno" type="button" role="tab">Aluno</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="instituicao-tab" data-bs-toggle="tab" data-bs-target="#instituicao" type="button" role="tab">Instituição</button>
          </li>
        </ul>

        <div class="tab-content p-3 border border-top-0 rounded-bottom">
          <!-- Cadastro Aluno -->
          <div class="tab-pane fade show active" id="aluno" role="tabpanel">
            <form method="post" action="cadastro.php">
              <input type="text" name="nome" class="form-control mb-3" placeholder="Nome completo" required>
              <input type="email" name="email" class="form-control mb-3" placeholder="E-mail" required>
              <input type="password" name="senha" class="form-control mb-3" placeholder="Senha" required>
              <input type="hidden" name="tipo" value="aluno">
              <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
            </form>
          </div>

          <!-- Cadastro Instituição -->
          <div class="tab-pane fade" id="instituicao" role="tabpanel">
            <form method="post" action="cadastro.php">
              <input type="text" name="nome" class="form-control mb-3" placeholder="Nome da Instituição" required>
              <input type="email" name="email" class="form-control mb-3" placeholder="E-mail institucional" required>
              <input type="password" name="senha" class="form-control mb-3" placeholder="Senha" required>
              <input type="hidden" name="tipo" value="instituicao">
              <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
            </form>
          </div>
        </div>

      </div>
    </div>

  </div>
</main>

<!-- ============================ -->
<!-- RODAPÉ -->
<!-- ============================ -->
<footer id="contato" class="Contatos text-white py-3">
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
<script src="js/js_global.js"></script>
</body>
</html>
