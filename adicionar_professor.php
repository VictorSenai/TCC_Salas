<?php
require_once 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $nome_professor = $_POST["NOME_PROFESSOR"] ?? '';
        $email = $_POST["EMAIL"] ?? '';

        // Corrected prepared statement - using ? placeholders
        $stmt = $conn->prepare("INSERT INTO professor (NOME_PROFESSOR, EMAIL) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome_professor, $email);

        if ($stmt->execute()) {
            echo "<script>alert('Professor adicionado com sucesso!');</script>";
        } else {
            throw new Exception("Erro ao adicionar professor: " . $stmt->error);
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
    <h1>Adicionar Professor</h1>
    <form action="adicionar_professor.php" method="POST">
        <label for="NOME_PROFESSOR">Nome:</label>
        <input type="text" id="NOME_PROFESSOR" name="NOME_PROFESSOR" required>
        <br><br>
        <label for="EMAIL">Email:</label>
        <input type="email" id="EMAIL" name="EMAIL" required>
        <br><br>
        <input type="submit" value="Adicionar Professor">
        <button onclick="window.location.href='index.php'">Voltar</button>
    </form>
</body>
</html>
