<?php
session_start();
include '../publico/conexao.php';

if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] != 'admin') {
    header("Location: ../publico/login.php");
    exit;
}

if(isset($_GET['id'])){
    $id = (int)$_GET['id'];
    // Deleta da tabela instituicoes
    $conn->query("DELETE FROM instituicoes WHERE id=$id");
    // Opcional: deletar login correspondente na tabela usuarios
    $conn->query("DELETE FROM usuarios WHERE usuario=(SELECT usuario FROM instituicoes WHERE id=$id)");
}

header("Location: ../admin/area_admin.php");
exit;
?>
