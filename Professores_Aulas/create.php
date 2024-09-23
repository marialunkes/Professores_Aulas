<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $professor_id = $_POST['professor'];
    $sala = $_POST['sala'];
    $hora_aula = $_POST['hora_aula'];

    // Insere a aula
    $sql_aula = "INSERT INTO Aula (sala) VALUES ('$sala')";
    if ($conn->query($sql_aula) === TRUE) {
        $last_id = $conn->insert_id;
        // Insere a ligação do professor com a aula
        $sql_diaria = "INSERT INTO Diaria (id_professor, id_aula, hora_aula) VALUES ('$professor_id', '$last_id', '$hora_aula')";
        $conn->query($sql_diaria);
        header("Location: index.php");
    } else {
        echo "Erro: " . $sql_aula . "<br>" . $conn->error;
    }
}

$sql_professores = "SELECT * FROM Professor";
$result_professores = $conn->query($sql_professores);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adicionar Aula</title>
</head>
<body>
    <h1>Adicionar Aula</h1>
    <form method="post" action="">
        <label>Professor:</label>
        <select name="professor">
            <?php while($row = $result_professores->fetch_assoc()) { ?>
                <option value="<?php echo $row['id_professor']; ?>"><?php echo $row['nome']; ?></option>
            <?php } ?>
        </select>
        <br>
        <label>Sala:</label>
        <input type="text" name="sala" required>
        <br>
        <label>Hora da Aula:</label>
        <input type="time" name="hora_aula" required>
        <br>
        <input type="submit" value="Adicionar">
    </form>
</body>
</html>

<?php
$conn->close();
?>
