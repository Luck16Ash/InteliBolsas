<?php
session_start();
include '../publico/conexao.php';

// ======= VALIDAÇÃO DE LOGIN ADMIN =======
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] != 'admin') {
    header("Location: ../publico/login.php");
    exit;
}

// ======= PEGAR DADOS DA INSTITUIÇÃO =======
if(isset($_GET['id'])){
    $id = (int)$_GET['id'];
    $sql = $conn->prepare("SELECT * FROM instituicoes WHERE id = ?");
    $sql->bind_param("i", $id);
    $sql->execute();
    $result = $sql->get_result();
    $inst = $result->fetch_assoc();
}

// ======= ATUALIZAR DADOS =======
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    $stmt = $conn->prepare("UPDATE instituicoes SET nome=?, email=?, telefone=?, endereco=? WHERE id=?");
    $stmt->bind_param("ssssi", $nome, $email, $telefone, $endereco, $id);
    $stmt->execute();

    header("Location: ../admin/area_admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Instituição - Admin</title>
<link rel="stylesheet" href="../publico/css/css_global.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h2>Editar Instituição: <?= htmlspecialchars($inst['nome']) ?></h2>
    <form method="post" class="mt-4">
        <div class="mb-3">
            <label>Nome:</label>
            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($inst['nome']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($inst['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Telefone:</label>
            <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($inst['telefone']) ?>">
        </div>
        <div class="mb-3">
            <label>Endereço:</label>
            <input type="text" name="endereco" class="form-control" value="<?= htmlspecialchars($inst['endereco']) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Atualizar Instituição</button>
        <a href="area_admin.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
