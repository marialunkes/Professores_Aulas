<?php
include 'db_connect.php';

$id_aula = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $professor_id = $_POST['professor'];
    $sala = $_POST['sala'];
    $hora_aula = $_POST['hora_aula'];

    // Atualiza a aula
    $sql_update_aula = "UPDATE Aula SET sala='$sala' WHERE id_aula='$id_aula'";
    $conn->query($sql_update_aula);

    // Atualiza a ligação do professor com a aula
    $sql_update_diaria = "UPDATE Diaria SET id_professor='$professor_id', hora_aula='$hora_aula' WHERE id_aula='$id_aula'";
    $conn->query($sql_update_diaria);

    header("Location: index.php");
}

$sql_aula = "SELECT * FROM Aula WHERE id_aula='$id_aula'";
$result_aula = $conn->query($sql_aula);
$aula = $result_aula->fetch_assoc();

$sql_professores = "SELECT * FROM Professor";
$result_professores = $conn->query($sql_professores);

$sql_diaria = "SELECT * FROM Diaria WHERE id_aula='$id_aula'";
$result_diaria = $conn->query($sql_diaria);
$diaria = $result_diaria->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Aula</title>
</head>
<body>
    <h1>Editar Aula</h1>
    <form method="post" action="">
        <label>Professor:</label>
        <select name="professor">
            <?php while($row = $result_professores->fetch_assoc()) { ?>
                <option value="<?php echo $row['id_professor']; ?>" <?php echo ($row['id_professor'] == $diaria['id_professor']) ? 'selected' : ''; ?>><?php echo $row['nome']; ?></option>
            <?php } ?>
        </select>
        <br>
        <label>Sala:</label>
        <input type="text" name="sala" value="<?php echo $aula['sala']; ?>" required>
        <br>
        <label>Hora da Aula:</label>
        <input type="time" name="hora_aula" value="<?php echo $diaria['hora_aula']; ?>" required>
        <br>
        <input type="submit" value="Atualizar">
    </form>
</body>
</html>

<?php
$conn->close();
?>
