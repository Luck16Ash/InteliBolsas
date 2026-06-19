<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/css_global.css">

    <!-- Favicon -->
    <link rel="icon" type="image/jpg" href="imagens/logos/inteliBolsas4.jpg">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php
        require_once "conexao.php";

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
            $tipo = $_POST['tipo']; // "aluno" ou "instituicao"

            if($tipo === "aluno") {
                $sql = "CALL Cadastro_aluno(?, ?, ?, ?)";
            } else {
                //Também serve para cadastro de admin!
                $sql = "CALL Cadastro_instituicao(?, ?, ?)";
            }

            $stmt = $conn->prepare($sql);
            if($tipo === "aluno") {
                $stmt->bind_param("ssss", $nome, $email, $nome, $senha); // usuario = nome (exemplo)
            } else {
                $stmt->bind_param("sss", $nome, $email, $senha);
            }

            if($stmt->execute()) {
                echo "<header class='fundo d-flex align-items-center justify-content-center text-white' style='height: 40vh;'>";
                echo "<div class='container text-center'>";
                echo "<h1 class='TextoPrincipal'>Cadastro realizado com sucesso!</h1>";
                echo "</div>";
                echo "</header>";
                echo "</br>";
                echo "<div class='container text-center'>";
                echo "<button class=' btn btn-primary' style='background-color: blue;'> <a style='color:white; text-decoration: none;' href='login.php'> Retornar ao Login </a> </button> ";
                echo "</div>";
            } else {
                echo "Erro no cadastro: " . $stmt->error;
            }
        }
    ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JS global -->
    <script src="js/js_global.js"></script>
</body>
</html>


