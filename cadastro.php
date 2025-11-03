<?php
require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $nome_sala = $_POST["NOME_SALA"] ?? '';
        $descricao = $_POST["DESCRICAO"] ?? '';

        // Corrected prepared statement - using ? placeholders
        $stmt = $conn->prepare("INSERT INTO sala (NOME_SALA, DESCRICAO) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome_sala, $descricao);

        if ($stmt->execute()) {
            echo "<script>alert('Sala adicionada com sucesso!');</script>";
        } else {
            throw new Exception("Erro ao adicionar sala: " . $stmt->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        echo "<script>alert('Erro: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Sala</title>
</head>
<body>
    <h1>Adicionar Sala</h1>
    <form action="processar_adicao_sala.php" method="POST">
        <label for="NOME_SALA">Nome da Sala:</label>
        <input type="text" id="NOME_SALA" name="NOME_SALA" required>
        <br><br>
        <label for="DESCRICAO">Descrição:</label>
        <input type="text" id="DESCRICAO" name="DESCRICAO" required>
        <br><br>
        <input type="submit" value="Adicionar Sala">
    </form>
</body>
</html>
