<?php
include 'db_connect.php';

$id_aula = $_GET['id'];

// Exclui a ligação do professor com a aula
$sql_delete_diaria = "DELETE FROM Diaria WHERE id_aula='$id_aula'";
$conn->query($sql_delete_diaria);

// Exclui a aula
$sql_delete_aula = "DELETE FROM Aula WHERE id_aula='$id_aula'";
$conn->query($sql_delete_aula);

header("Location: index.php");
$conn->close();
?>
