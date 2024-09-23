<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "crud_aula";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Ações de CREATE e UPDATE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $professor_id = $_POST['professor'];
    $sala = $_POST['sala'];
    $hora_aula = $_POST['hora_aula'];
    $id_aula = isset($_POST['id_aula']) ? $_POST['id_aula'] : '';

    if ($id_aula) {
        // Atualizar aula existente (UPDATE)
        $sql_update_aula = "UPDATE Aula SET sala='$sala' WHERE id_aula='$id_aula'";
        $conn->query($sql_update_aula);

        $sql_update_diaria = "UPDATE Diaria SET id_professor='$professor_id', hora_aula='$hora_aula' WHERE id_aula='$id_aula'";
        $conn->query($sql_update_diaria);
    } else {
        // Adicionar nova aula (CREATE)
        $sql_aula = "INSERT INTO Aula (sala) VALUES ('$sala')";
        if ($conn->query($sql_aula) === TRUE) {
            $last_id = $conn->insert_id;
            $sql_diaria = "INSERT INTO Diaria (id_professor, id_aula, hora_aula) VALUES ('$professor_id', '$last_id', '$hora_aula')";
            $conn->query($sql_diaria);
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
}

// Ação de DELETE
if (isset($_GET['delete'])) {
    $id_aula = $_GET['delete'];
    $conn->query("DELETE FROM Diaria WHERE id_aula='$id_aula'");
    $conn->query("DELETE FROM Aula WHERE id_aula='$id_aula'");
    header("Location: " . $_SERVER['PHP_SELF']);
}

// Consultar professores para popular o formulário
$sql_professores = "SELECT * FROM Professor";
$result_professores = $conn->query($sql_professores);

// Consultar aulas para listar
$sql = "SELECT a.id_aula, p.nome AS nome_professor, a.sala, d.hora_aula 
        FROM Aula a
        JOIN Diaria d ON a.id_aula = d.id_aula
        JOIN Professor p ON d.id_professor = p.id_professor";
$result = $conn->query($sql);

// Se estiver no modo de edição
$id_aula_edit = '';
$sala_edit = '';
$hora_aula_edit = '';
$professor_edit = '';

if (isset($_GET['edit'])) {
    $id_aula_edit = $_GET['edit'];
    $sql_edit = "SELECT a.id_aula, p.id_professor, a.sala, d.hora_aula 
                 FROM Aula a
                 JOIN Diaria d ON a.id_aula = d.id_aula
                 JOIN Professor p ON d.id_professor = p.id_professor
                 WHERE a.id_aula='$id_aula_edit'";
    $result_edit = $conn->query($sql_edit);
    if ($result_edit->num_rows > 0) {
        $row_edit = $result_edit->fetch_assoc();
        $sala_edit = $row_edit['sala'];
        $hora_aula_edit = $row_edit['hora_aula'];
        $professor_edit = $row_edit['id_professor'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gerenciamento de Aulas</title>
</head>
<body>
    <h1><?php echo isset($_GET['edit']) ? "Editar Aula" : "Adicionar Aula"; ?></h1>

    <!-- Formulário de Adição/Atualização de Aula -->
    <form method="post" action="">
        <label>Professor:</label>
        <select name="professor" required>
            <?php while($row = $result_professores->fetch_assoc()) { ?>
                <option value="<?php echo $row['id_professor']; ?>" 
                        <?php echo ($row['id_professor'] == $professor_edit) ? 'selected' : ''; ?>>
                    <?php echo $row['nome']; ?>
                </option>
            <?php } ?>
        </select>
        <br>
        <label>Sala:</label>
        <input type="text" name="sala" value="<?php echo $sala_edit; ?>" required>
        <br>
        <label>Hora da Aula:</label>
        <input type="time" name="hora_aula" value="<?php echo $hora_aula_edit; ?>" required>
        <br>
        <?php if ($id_aula_edit): ?>
            <input type="hidden" name="id_aula" value="<?php echo $id_aula_edit; ?>">
        <?php endif; ?>
        <input type="submit" value="<?php echo isset($_GET['edit']) ? "Atualizar" : "Adicionar"; ?>">
    </form>

    <h1>Lista de Aulas</h1>
    <!-- Tabela de Aulas -->
    <table border="1">
        <tr>
            <th>ID da Aula</th>
            <th>Professor</th>
            <th>Sala</th>
            <th>Hora da Aula</th>
            <th>Ações</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_aula']; ?></td>
                    <td><?php echo $row['nome_professor']; ?></td>
                    <td><?php echo $row['sala']; ?></td>
                    <td><?php echo $row['hora_aula']; ?></td>
                    <td>
                        <a href="?edit=<?php echo $row['id_aula']; ?>">Editar</a> |
                        <a href="?delete=<?php echo $row['id_aula']; ?>" onclick="return confirm('Tem certeza que deseja excluir?');">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Nenhuma aula cadastrada.</td>
            </tr>
        <?php endif; ?>
    </table>

</body>
</html>

<?php
$conn->close();
?>
