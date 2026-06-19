<?php
session_start();
include 'conexao.php';

// Verifica se é admin
if (!isset($_SESSION['usuario']) || $_SESSION['tipo'] != 'admin') {
    header("Location: login.php");
    exit;
}

if(isset($_GET['id'])){
    $id = (int)$_GET['id'];
    $sql = "UPDATE alunos SET status='suspenso' WHERE id=$id";
    $conn->query($sql);
}

header("Location: area_admin.php");
exit;
?>
