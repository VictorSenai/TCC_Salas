
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Salas</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .support-button {
            background-color: #4CAF50;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 2px;
        }
        .support-button:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Lista de Salas</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome da Sala</th>
                <th>Descrição</th>
                <th>Suporte</th>
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

            $sql = "SELECT * FROM sala";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Saída de dados de cada linha
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["ID_SALA"] . "</td>";
                    echo "<td>" . $row["NOME_SALA"] . "</td>";
                    echo "<td>" . $row["DESCRICAO"] . "</td>";
                    echo "<td>";
                    echo "<button class='support-button' onclick='window.location.href=\"visualizar_pedido_sup.php?sala=" . $row["ID_SALA"] . "\"'>";
                    echo "Ver Pedidos";
                    echo "</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>Nenhuma sala encontrada</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
        <button onclick="window.location.href='index.php'" class="button-home">Home</button>
</body>
</html>