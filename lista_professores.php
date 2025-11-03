
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Lista de Professores</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome da Sala</th>
                <th>Descrição</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once 'conexao.php';
            // Criar conexão
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Verificar conexão
            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM professor";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Saída de dados de cada linha
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["EMAIL"] . "</td>";
                    echo "<td>" . $row["NOME_PROFESSOR"] . "</td>";

                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Nenhum professor encontrado</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
        <button onclick="window.location.href='index.php'" class="button-home">Home</button>
</body>
</html>