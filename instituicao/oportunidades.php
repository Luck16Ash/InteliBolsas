<?php
header('Content-Type: application/json');
include("../publico/conexao.php");

$result = $conn->query("SELECT * FROM oportunidades");

$oportunidades = [];
while($row = $result->fetch_assoc()) {
    $oportunidades[] = [
        'titulo' => $row['titulo'],
        'descricao' => $row['descricao'],
        'imagem' => $row['imagem'],   
        'url_inscricao' => $row['url_inscricao'] ?? ''
    ];
}

echo json_encode($oportunidades);
?>
