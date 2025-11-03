<?php
require_once 'conexao.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $nome = $_POST["NOME_PROFESSOR"] ?? '';
        $email = $_POST["EMAIL"] ?? '';
        $senha = $_POST["SENHA"] ?? '';

        // Hash da senha antes de salvar
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO professor (NOME_PROFESSOR, EMAIL, SENHA) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $senha_hash);

        if ($stmt->execute()) {
            echo "<script>alert('Professor adicionado com sucesso!'); window.location.href='index.php';</script>";
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
    <title>Cadastro de Professor</title>
</head>
<body>
    <h1>Cadastro de Professor</h1>    
    <form action="cadastro_prof.php" method="POST">
        <label for="NOME_PROFESSOR">Nome</label>
        <input type="text" id="NOME_PROFESSOR" name="NOME_PROFESSOR" required>
        <br><br>
        
        <label for="EMAIL">Email</label>
        <input type="email" id="EMAIL" name="EMAIL" required>
        <br><br>
        
        <label for="SENHA">Senha</label>
        <input type="password" id="SENHA" name="SENHA" required>
        <br><br>
        
        <button type="submit">Adicionar Professor</button>
        <button type="button" onclick="window.location.href='index.php'">Voltar</button>
    </form>
</body>
</html>