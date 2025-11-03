<?php
require_once 'conexao.php';

// Garantir que $sala existe mesmo se nenhuma sala for selecionada
$sala = null;

// Get sala_id from URL
$sala_id = isset($_GET['sala']) ? (int)$_GET['sala'] : null;

// Verify if sala exists
if ($sala_id) {
    $stmt = $conn->prepare("SELECT NOME_SALA FROM sala WHERE ID_SALA = ?");
    $stmt->bind_param("i", $sala_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $sala = $result->fetch_assoc();
    if (!$sala) {
        die("Sala não encontrada!");
    }
}
function getTarefasbyProf($conn,$prof_nome,$prof_id) {
    $stmt = $conn->prepare("SELECT t.*, s.NOME_SALA 
        FROM tarefa t 
        INNER JOIN sala s ON t.ID_SALA = s.ID_SALA 
        WHERE t.PROF_NOME = ? AND t.PROF_ID = ?
        ORDER BY t.PRIORIDADE DESC
    ");
    $stmt->bind_param("si", $prof_nome, $prof_id);
}

function getTarefasBySala($conn, $sala_id) {
    $stmt = $conn->prepare("
        SELECT t.*, s.NOME_SALA 
        FROM tarefa t 
        INNER JOIN sala s ON t.ID_SALA = s.ID_SALA 
        WHERE t.ID_SALA = ? AND t.NOME_SALA = s.NOME_SALA
        ORDER BY t.PRIORIDADE DESC
    ");
    
    $stmt->bind_param("i", $sala_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Retorna uma tarefa específica daquela sala (filtro WHERE)
function getTarefaEspecifica($conn, $sala_id, $tarefa_id) {
    $stmt = $conn->prepare("
        SELECT t.*, s.NOME_SALA
        FROM tarefa t
        INNER JOIN sala s ON t.ID_SALA = s.ID_SALA
        WHERE t.ID_SALA = ? AND t.ID_TAREFA = ?
        LIMIT 1
    ");
    $stmt->bind_param("ii", $sala_id, $tarefa_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();
    return $row;
}

// Retorna a última tarefa criada naquela sala (filtro WHERE + ORDER/LIMIT)
function getUltimaTarefaPorSala($conn, $sala_id) {
    $stmt = $conn->prepare("
        SELECT t.*, s.NOME_SALA
        FROM tarefa t
        INNER JOIN sala s ON t.ID_SALA = s.ID_SALA
        WHERE t.ID_SALA = ?
        ORDER BY t.DATA_CRIACAO DESC
        LIMIT 1
    ");
    $stmt->bind_param("i", $sala_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();
    return $row;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        // Debug POST data
        echo "<pre>POST Data:\n";
        print_r($_POST);
        echo "</pre>";

        $tarefa = trim($_POST["NOME_TAREFA"] ?? '');
        $prioridade = $_POST["PRIORIDADE"] ?? '';
        $sala_id = $_POST["ID_SALA"] ?? null;

        // Debug variables
        echo "<pre>Processed Variables:\n";
        echo "Tarefa: " . var_export($tarefa, true) . "\n";
        echo "Prioridade: " . var_export($prioridade, true) . "\n";
        echo "Sala ID: " . var_export($sala_id, true) . "\n";
        echo "</pre>";

        // Verify database connection
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        // Prepare statement with error check
        $stmt = $conn->prepare("
            INSERT INTO tarefa (NOME_TAREFA, PRIORIDADE, ID_SALA, NOME_SALA) 
            SELECT ?, ?, ?, s.NOME_SALA 
            FROM sala s 
            WHERE s.ID_SALA = ?
        ");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        // Bind parameters with error check
        if (!$stmt->bind_param("ssis", $tarefa, $prioridade, $sala_id, $sala_id)) {
            throw new Exception("Binding parameters failed: " . $stmt->error);
        }

        // Execute with error check
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        echo "<pre>Insert successful! Affected rows: " . $stmt->affected_rows . "</pre>";

        $stmt->close();
    } catch (Exception $e) {
        echo "<pre>ERROR: " . $e->getMessage() . "\n";
        echo "Stack trace:\n" . $e->getTraceAsString() . "</pre>";
    }
}

$view_sala_id = null;
if (isset($_GET['sala'])) {
    $view_sala_id = (int) $_GET['sala'];
} elseif (isset($_POST['ID_SALA'])) {
    $view_sala_id = (int) $_POST['ID_SALA'];
}

// Uso na exibição: se receber ?tarefa=ID mostra essa tarefa, senão mostra a última
if ($sala_id) {
    if (isset($_GET['tarefa'])) {
        $tarefa_selecionada = getTarefaEspecifica($conn, $sala_id, (int)$_GET['tarefa']);
    } else {
        $tarefa_selecionada = getUltimaTarefaPorSala($conn, $sala_id);
    }

    if ($tarefa_selecionada) {
        echo "<h3>Pedido de Suporte Selecionado</h3>";
        echo "<p>Sala: " . htmlspecialchars($tarefa_selecionada['NOME_SALA']) . "</p>";
        echo "<p>Tarefa: " . htmlspecialchars($tarefa_selecionada['NOME_TAREFA']) . "</p>";
        echo "<p>Prioridade: " . htmlspecialchars($tarefa_selecionada['PRIORIDADE']) . "</p>";
        echo "<p>Data: " . htmlspecialchars($tarefa_selecionada['DATA_CRIACAO'] ?? '') . "</p>";
    } else {
        echo "<p>Nenhum pedido de suporte encontrado para esta sala.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suporte - <?php echo $sala ? htmlspecialchars($sala['NOME_SALA']) : 'Todas as Salas'; ?></title>
</head>
<body>
    <h1>Cadastro de Suporte - <?php echo $sala ? htmlspecialchars($sala['NOME_SALA']) : 'Todas as Salas'; ?></h1>
    <form method="POST" action="">
        <!-- Hidden field for sala_id when pre-selected -->
        <?php if ($sala_id): ?>
            <input type="hidden" name="ID_SALA" value="<?php echo htmlspecialchars($sala_id); ?>">
            <p><strong>Sala:</strong> <?php echo htmlspecialchars($sala['NOME_SALA']); ?></p>
        <?php else: ?>
        <table>
            <tr>
                <th>Sala</th>
                <td>
                    <select name="ID_SALA" id="ID_SALA" required>
                        <option value="">Selecione uma sala</option>
                        <?php
                        $sql = "SELECT ID_SALA, NOME_SALA FROM sala ORDER BY NOME_SALA";
                        $result = $conn->query($sql);
                        
                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($row['ID_SALA']) . '">' 
                                    . htmlspecialchars($row['NOME_SALA']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
        <?php endif; ?>
            <tr>
                <th>Razão de Suporte</th>
                <td><input type="text" id="NOME_TAREFA" name="NOME_TAREFA" required></td>
            </tr>
            <tr>
                <th>Prioridade do Suporte</th>
                <td>
                    <label><input type="radio" name="PRIORIDADE" value="Urgente" required> Urgente</label>
                    <label><input type="radio" name="PRIORIDADE" value="Alta"> Alta</label>
                    <label><input type="radio" name="PRIORIDADE" value="Baixa"> Baixa</label>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center;">
                    <button type="submit">Enviar</button>
                    <a href="index.php"><button type="button">Voltar</button></a>
                </td>
            </tr>
        </table>
    </form>

    <!-- Display tasks only for the selected sala -->
    <?php if ($sala_id): 
        $current_sala = $sala['NOME_SALA'];
    ?>
        <h2>Suportes Cadastrados - <?php echo htmlspecialchars($current_sala); ?></h2>
        <?php
        $tasks = getTarefasBySala($conn, $sala_id);
        if ($tasks && $tasks->num_rows > 0) {
            echo "<table border='1'>
                    <tr>
                        <th>Tarefa</th>
                        <th>Prioridade</th>
                        <th>Data</th>
                        <th>Sala</th>
                    </tr>";
            while ($row = $tasks->fetch_assoc()) {
                // Só mostra se o NOME_SALA corresponder à sala atual
                if ($row['NOME_SALA'] === $current_sala) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['NOME_TAREFA']) . "</td>
                        <td>" . htmlspecialchars($row['PRIORIDADE']) . "</td>
                        <td>" . date('d/m/Y H:i', strtotime($row['DATA_CRIACAO'] ?? 'now')) . "</td>
                        <td>" . htmlspecialchars($row['NOME_SALA']) . "</td>
                      </tr>";
                }
            }
            echo "</table>";
        } else {
            echo "<p>Nenhum suporte cadastrado para esta sala.</p>";
        }
        ?>
    <?php endif; ?>
    <button onclick="window.location.href='index.php'" class="button-home">Home</button>
    <b
</body>
</html>