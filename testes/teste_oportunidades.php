<?php
include("../publico/conexao.php");

$sql = "SELECT * FROM oportunidades";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo "<p>{$row['titulo']} - {$row['descricao']}</p>";
  }
} else {
  echo "Nenhuma oportunidade encontrada.";
}
?>
