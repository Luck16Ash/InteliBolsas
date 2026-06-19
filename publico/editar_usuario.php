<?php
session_start();
include 'conexao.php';
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] != 'admin') {
    header("Location: login.php");
    exit;
}

if(isset($_GET['id'])){
    $id = (int)$_GET['id'];
    $usuario = $conn->query("SELECT * FROM alunos WHERE id=$id")->fetch_assoc();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $area_interesse = $_POST['area_interesse'];
    
    $conn->query("UPDATE alunos SET nome='$nome', email='$email', telefone='$telefone', area_interesse='$area_interesse' WHERE id=$id");
    header("Location: area_admin.php");
    exit;
}
?>

<form method="post">
    Nome: <input type="text" name="nome" value="<?= htmlspecialchars($usuario['nome']) ?>"><br>
    Email: <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>"><br>
    Telefone: <input type="text" name="telefone" value="<?= htmlspecialchars($usuario['telefone']) ?>"><br>
    Área de Interesse: <input type="text" name="area_interesse" value="<?= htmlspecialchars($usuario['area_interesse']) ?>"><br>
    <button type="submit">Atualizar</button>
</form>
