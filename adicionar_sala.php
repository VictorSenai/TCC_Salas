<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Adicionar Sala</h1>
    <form action="processar_adicao_sala.php" method="POST">
        <label for="NOME_SALA">Nome da Sala:</label>
        <input type="text" id="NOME_SALA" name="NOME_SALA" required $nome  >
        <br><br>
        <label for="DESCRICAO">Descrição:</label>
        <input type="text" id="DESCRICAO" name="DESCRICAO" required>
        <br><br>
        <input type="submit" value="Adicionar Sala" action="processar_adicao_sala.php" method="POST">
    </form>
</body>
</html>