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
    }

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Titulo"], $_POST["Descricao"], $_POST["Vagas"], $_POST["Link"])){
        $titulo = $_POST["Titulo"];
        $descricao = $_POST["Descricao"];
        $vagas = $_POST["Vagas"];
        $link = $_POST["Link"];
        $id = $_POST["id"];
        $comando = "UPDATE cursos SET titulo = ?, descricao = ?, vagas = ?, link_externo = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $comando);
        $stmt->bind_param("ssisi", $titulo, $descricao, $vagas, $link, $id);
        $stmt->execute();

        $sucesso = true;
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
    
    <div class="container my-5">
    <h2>Editar Curso</h2>
        <form method="post" class="mt-4">
             <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <div class="mb-3">
                <label>Título:</label>
                <input type="text" name="Titulo" class="form-control" value="<?= htmlspecialchars($resultado['titulo']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Descrição:</label>
                <input type="text" name="Descricao" class="form-control" value="<?= htmlspecialchars($resultado['descricao']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Vagas:</label>
                <input type="number" name="Vagas" class="form-control" value="<?= htmlspecialchars($resultado['vagas']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Link:</label>
                <input type="text" name="Link" class="form-control" value="<?= htmlspecialchars($resultado['link_externo']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Atualizar Curso</button>
            <a href="area_instituicao.php#Cursos" class="btn btn-secondary">Cancelar</a>
            <?php

            if(isset($sucesso)){
                if($sucesso == true){
                echo "<br>";
                echo "<p> Editado com Sucesso</p>";
                $sucesso= false;
                }
            }
            ?>
        </form>
    </div>

    
        
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JS global -->
    <script src="../publico/js/js_global.js"></script>
</body>
</html>