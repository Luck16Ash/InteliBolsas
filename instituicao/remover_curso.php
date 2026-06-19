<?php
    session_start();
    require_once("../publico/conexao.php");

    if (!isset($_SESSION['id_instituicao'])) {
        // Redireciona para login caso não esteja logado
        header("Location: ../publico/login.php");
        exit;
    }
    $ID_Inst = $_SESSION['id_instituicao'];

    if(isset($_GET["id"])){
        $id = $_GET["id"];
        $comando = "SELECT * FROM cursos WHERE id = ?";
        $stmt = mysqli_prepare($conn, $comando);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $resultado = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!isset($resultado)){
            die( "O curso não existe.");
        }

        $comando = "DELETE FROM cursos WHERE id = ?";
        $stmt = mysqli_prepare($conn, $comando);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        mysqli_stmt_close($stmt);

        header("Location: area_instituicao.php#Cursos");
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar</title>

    <!-- CSS global -->
    <link rel="stylesheet" href="../publico/css/css_global.css">

    <!-- Favicon -->
    <link rel="icon" type="image/jpg" href="../publico/imagens/logos/inteliBolsas4.jpg">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
</body>
</html>