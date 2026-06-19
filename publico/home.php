<?php
  // Inicia a sessão
  session_start();

  // conexão com o banco de dados (se precisar em outras partes)
  include("conexao.php");
  $sql_destaque = "SELECT c.id, c.titulo, c.vagas, c.bolsa, c.descricao, c.imagem, i.nome AS instituicao_nome
                      FROM cursos c
                      JOIN instituicoes i ON c.id_instituicao = i.id
                      ORDER BY c.vagas DESC, c.bolsa DESC, c.acessos ASC LIMIT 6";
  $cursos_em_destaque = mysqli_query($conn,$sql_destaque);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <title>InteliBolsas - Oportunidades que transformam</title>
  
  <!-- CSS externo específico da home -->
  <link rel="stylesheet" href="css/home.css">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="imagens/logos/inteliBolsas4.png">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- ======= NAVBAR ======= -->
<nav class="navbar navbar-expand-lg bg-light">
  <div class="container">
    <!-- Logo -->
    <a class="navbar-brand" href="#sobre">
      <img src="imagens/logos/InteliBolsas1.jpg" class="logo-navbar" alt="Logo InteliBolsas">
    </a>

    <!-- Botão mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
      data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" 
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Links -->
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav ms-auto">
        <a class="nav-link NavTexto" href="#sobre">Quem Somos</a>
        <a class="nav-link NavTexto" href="#destaques">Oportunidades</a>
        <!--Verifica se há um sessão de tipo, e se a sessão é do tipo aluno, admin ou instituição -->
        <?php if (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'aluno'): ?>
          <a class="nav-link NavTexto" href="..\instituicao\cursos.php">Cursos</a>
          <a class="nav-link NavTexto" href="..\aluno\area_aluno.php">Área Aluno</a>
          <a href="logout.php" class="btn btn-danger">Sair</a>  
        <?php elseif (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin'): ?>
          <a class="nav-link NavTexto" href="..\admin\area_admin.php">Área Admin</a>
          <a href="logout.php" class="btn btn-danger">Sair</a>  
        <?php elseif (isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'instituicao'): ?>
          <a class="nav-link NavTexto" href="../instituicao/area_instituicao.php#Cursos">Meus Cursos</a>
          <a class="nav-link NavTexto" href="../instituicao/area_instituicao.php">Área Inst.</a>
          <a href="logout.php" class="btn btn-danger">Sair</a>  
        <?php else: ?>
          <a href="login.php" class="btn btn-success" id="btnLogin">Login</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<!-- ======= APRESENTAÇÃO ======= -->
<header class="fundo d-flex align-items-center text-center text-white" style="height:100vh;">
  <div class="container">
    <h1 class="TextoPrincipal">Transforme sua vida através do conhecimento</h1>
    <p class="SubTexto">Encontre bolsas, cursos e oportunidades que vão transformar sua jornada acadêmica e profissional.</p>
    <a href="#destaques" class="btn btn-primary btn-lg mt-3">Explorar Cursos</a>
    <div class="mt-4">
      <img src="imagens/mascote/mascote.png" alt="Mascote InteliBolsas" class="mascote">
    </div>
  </div>
</header>

<!-- ======= PESQUISA ======= -->
<section class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <form class="d-flex shadow-sm" id="formBusca">
        <input class="form-control me-2" type="search" placeholder="Digite o curso ou bolsa dos seus sonhos..." aria-label="Search" id="inputBusca">
        <button class="btn btn-primary" type="submit">Buscar</button>
      </form>
    </div>
  </div>
</section>

<!-- ======= DESTAQUES ======= -->
<section id="destaques" class="container my-5">
  <h2 class="text-center mb-4">Oportunidades em Destaque</h2>
  <div class="row" id="cardsOportunidades">
    <?php
      if (mysqli_num_rows($cursos_em_destaque) > 0):
        while ($curso = mysqli_fetch_assoc($cursos_em_destaque)):
    ?>
      <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($curso['titulo']); ?></h5>
            <p class="card-text">
              <!--<strong>Instituição:</strong> <?php echo htmlspecialchars($curso['instituicao_nome']); ?><br>-->
              <strong></strong> <?php echo htmlspecialchars($curso['descricao']); ?><br>
              <strong>Vagas:</strong> <?php echo htmlspecialchars($curso['vagas']); ?><br>
              <strong>Bolsa:</strong> <?php echo htmlspecialchars($curso['bolsa']); ?>%
            </p>
            <a href="cursos.php" class="btn btn-outline-primary">Quero Saber Mais</a>
          </div>
        </div>
      </div>
    <?php
        endwhile;
      else:
    ?>
      <div class="col-12 text-center">
        <p class="alert alert-info">Nenhuma oportunidade em destaque encontrada no momento.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- ======= QUEM SOMOS ======= -->
<section id="sobre" class="container Caixa my-5">
  <div class="row justify-content-center">
    <div class="col-md-8 text-center">
      <h2>Sobre a InteliBolsas</h2>
      <p>
        A <strong>InteliBolsas</strong> é um projeto acadêmico da <strong>FATEC</strong>, desenvolvido por alunos do 2º semestre do curso superior em <strong>Desenvolvimento de Software Multiplataforma</strong>.  
        Nosso objetivo é <strong>aproximar estudantes de oportunidades educacionais</strong>, conectando bolsas e cursos de qualidade ao futuro de cada aluno.
      </p>
      <img src="imagens/logos/Logo AGJLM.webp" alt="Logo AGJLM" class="logo-secundario">
    </div>
  </div>
</section>

<!-- ======= RODAPÉ ======= -->
<footer id="contato" class="Contatos text-white py-3">
  <div class="container">
    <div class="row text-center">
      <div class="col-md-4">
        <span>📞 Entre em Contato</span>
      </div>
      <div class="col-md-4">
        <span>© 2025 InteliBolsas | AGJLM Corporation</span>
      </div>
      <div class="col-md-4">
        <span>📍 Nosso Endereço</span>
      </div>
    </div>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<!-- JS externo específico da home -->
<script src="js/home.js"></script>

</body>
</html>
