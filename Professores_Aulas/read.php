<?php
include 'db_connect.php';

// Busca os dados das aulas e dos professores
$sql = "SELECT a.id_aula, p.nome AS nome_professor, a.sala FROM Aula a
        JOIN Diaria d ON a.id_aula = d.id_aula
        JOIN Professor p ON d.id_professor = p.id_professor";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aulas</title>
</head>
<body>
    <h1>Lista de Aulas</h1>
    <table border="1">
        <tr>
            <th>ID da Aula</th>
            <th>Nome do Professor</th>
            <th>Sala</th>
            <th>Ações</th>
        </tr>
        <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id_aula']; ?></td>
            <td><?php echo $row['nome_professor']; ?></td>
            <td><?php echo $row['sala']; ?></td>
            <td>
                <a href="edit.php?id=<?php echo $row['id_aula']; ?>">Editar</a> |
                <a href="delete.php?id=<?php echo $row['id_aula']; ?>" onclick="return confirm('Deseja realmente excluir?');">Excluir</a>
            </td>
        </tr>
        <?php } ?>
    </table>
    <a href="create.php">Adicionar Aula</a>
</body>
</html>

<?php
$conn->close();
?>
