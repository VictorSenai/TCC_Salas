<?php
require_once 'conexao.php'; // deve definir $conn

// Pegar o ID da sala da URL
$sala_id = isset($_GET['sala']) ? intval($_GET['sala']) : 0;
$message = '';

// Deletar pedido (se solicitado) - usa GET delete=id ou pode mudar para POST conforme desejar
if (isset($_GET['delete'])) {
    $delete_id = (int) $_GET['delete'];
    if ($delete_id > 0) {
        $delStmt = $conn->prepare("DELETE FROM tarefa WHERE ID_TAREFA = ?");
        $delStmt->bind_param("i", $delete_id);
        if ($delStmt->execute()) {
            $message = "Pedido removido com sucesso.";
        } else {
            $message = "Erro ao remover: " . $delStmt->error;
        }
        $delStmt->close();
    }
}

// Buscar nome da sala (se fornecida)
$nome_sala = 'Todas as Salas';
if ($sala_id > 0) {
    $stmt = $conn->prepare("SELECT NOME_SALA FROM sala WHERE ID_SALA = ?");
    $stmt->bind_param("i", $sala_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $nome_sala = $row['NOME_SALA'];
    } else {
        $nome_sala = 'Sala não encontrada';
    }
    $stmt->close();
}

// Buscar pedidos apenas da sala (se sala_id = 0 busca todos)
if ($sala_id > 0) {
    $q = $conn->prepare("SELECT ID_TAREFA, NOME_TAREFA, PRIORIDADE, STATUS, DATA_CRIACAO FROM tarefa WHERE ID_SALA = ? ORDER BY DATA_CRIACAO DESC");
    $q->bind_param("i", $sala_id);
} else {
    $q = $conn->prepare("SELECT ID_TAREFA, NOME_TAREFA, PRIORIDADE, STATUS, DATA_CRIACAO FROM tarefa ORDER BY DATA_CRIACAO DESC");
}
$q->execute();
$result = $q->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pedidos de Suporte - <?php echo htmlspecialchars($nome_sala); ?></title>
</head>
<body>
    <style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    padding: 20px;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
}

header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

h1 {
    color: #333;
    font-size: 2rem;
}

.btn-primary {
    background: #4CAF50;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.3s;
}

.btn-primary:hover {
    background: #45a049;
}

.btn-secondary {
    background: #6c757d;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.btn-secondary:hover {
    background: #5a6268;
}

.kanban-board {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 20px;
}

.column {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    min-height: 600px;
}

.column-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #eee;
}

.column-header h2 {
    color: #333;
    font-size: 1.3rem;
}

.task-count {
    background: #e9ecef;
    color: #495057;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.9rem;
}

.tasks {
    min-height: 500px;
    transition: background-color 0.3s;
}

.tasks.dragover {
    background-color: #f8f9fa;
    border: 2px dashed #007bff;
}

.task {
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    cursor: move;
    transition: all 0.3s;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.task:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.task.dragging {
    opacity: 0.5;
    transform: rotate(5deg);
}

.task-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
}

.task-title {
    font-weight: bold;
    color: #333;
    font-size: 1.1rem;
    margin-bottom: 5px;
}

.task-description {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.4;
    margin-bottom: 10px;
}

.task-priority {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: bold;
}

.priority-high {
    background: #ffebee;
    color: #c62828;
}

.priority-medium {
    background: #fff3e0;
    color: #ef6c00;
}

.priority-low {
    background: #e8f5e8;
    color: #2e7d32;
}

.task-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 10px;
}

.delete-btn {
    background: #dc3545;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 3px;
    cursor: pointer;
    font-size: 0.8rem;
}

.delete-btn:hover {
    background: #c82333;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 30px;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    position: relative;
}

.close {
    position: absolute;
    right: 20px;
    top: 15px;
    font-size: 28px;
    cursor: pointer;
    color: #aaa;
}

.close:hover {
    color: #333;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #333;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
}

.form-group textarea {
    resize: vertical;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .kanban-board {
        grid-template-columns: 1fr;
    }
    
    header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
}
    </style>
    <h1>Pedidos de Suporte - <?php echo htmlspecialchars($nome_sala); ?></h1>

    <?php if ($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Pedido</th>
                <th>Prioridade</th>
                <th>Status</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['NOME_TAREFA']); ?></td>
                        <td><?php echo htmlspecialchars($row['PRIORIDADE']); ?></td>
                        <td><?php echo htmlspecialchars($row['STATUS'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['DATA_CRIACAO'] ?? ''); ?></td>
                        <td>
                            <!-- link para visualizar/editar -->
                            <a href="suporte.php?sala=<?php echo htmlspecialchars($sala_id); ?>&tarefa=<?php echo $row['ID_TAREFA']; ?>">Ver</a>
                            <!-- link para apagar (confirme no cliente antes de usar) -->
                            <a href="visualizar_pedido_sup.php?sala=<?php echo htmlspecialchars($sala_id); ?>&delete=<?php echo $row['ID_TAREFA']; ?>" onclick="return confirm('Confirmar exclusão?')">Apagar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">Nenhum pedido encontrado</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <p>
        <button type="button" onclick="window.location.href='index.php'">Home</button>
        <button type="button" onclick="window.location.href='suporte.php?sala=<?php echo htmlspecialchars($sala_id); ?>'">Voltar</button>
    </p>

    
    <div class="container">
        <header>
            <h1>Meu Quadro Kanban</h1>
            <button id="addTaskBtn" class="btn-primary">+ Nova Tarefa</button>
        </header>

        <div class="kanban-board">
            <!-- Coluna A Fazer -->
            <div class="column" id="todo">
                <div class="column-header">
                    <h2>A Fazer</h2>
                    <span class="task-count">0</span>
                </div>
                <div class="tasks" ondrop="drop(event)" ondragover="allowDrop(event)">
                    <!-- Tarefas serão adicionadas aqui -->
                </div>
            </div>

            <!-- Coluna Em Progresso -->
            <div class="column" id="progress">
                <div class="column-header">
                    <h2>Em Progresso</h2>
                    <span class="task-count">0</span>
                </div>
                <div class="tasks" ondrop="drop(event)" ondragover="allowDrop(event)">
                    <!-- Tarefas serão adicionadas aqui -->
                </div>
            </div>

            <!-- Coluna Concluído -->
            <div class="column" id="done">
                <div class="column-header">
                    <h2>Concluído</h2>
                    <span class="task-count">0</span>
                </div>
                <div class="tasks" ondrop="drop(event)" ondragover="allowDrop(event)">
                    <!-- Tarefas serão adicionadas aqui -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para adicionar tarefa -->
    <div id="taskModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Nova Tarefa</h2>
            <form id="taskForm">
                <div class="form-group">
                    <label for="taskTitle">Título:</label>
                    <input type="text" id="taskTitle" required>
                </div>
                <div class="form-group">
                    <label for="taskDescription">Descrição:</label>
                    <textarea id="taskDescription" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="taskPriority">Prioridade:</label>
                    <select id="taskPriority">
                        <option value="low">Baixa</option>
                        <option value="medium">Média</option>
                        <option value="high">Alta</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="button" id="cancelBtn" class="btn-secondary">Cancelar</button>
                    <button type="submit" class="btn-primary">Adicionar Tarefa</button>
                </div>
            </form>
        </div>
    </div>

    <script src="script.js"></script>


</body>
</html>
<?php
// liberar recursos e fechar conexão apenas aqui

$q->close();
$conn->close();
?>