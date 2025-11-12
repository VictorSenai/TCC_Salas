j<?php
require_once 'config.php';
require_once 'db_config.php'; // NOVO

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) { // MUDANÇA
    header('Location: index.php');
    exit();
}

// Busca as informações do usuário no banco de dados
$stmt = $pdo->prepare("SELECT name, email, picture_url FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // Se o usuário não for encontrado no DB, encerra a sessão e redireciona
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}

// Extrai as informações necessárias do banco de dados
$name = $user['name'];
$email = $user['email'];
$picture = $user['picture_url'];

// Link para deslogar
$logoutUrl = 'logout.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Usuário</title>
    <style>
        /* ... (CSS permanece o mesmo) ... */
    </style>
</head>
<body>
    <div class="profile-box">
        <img src="<?php echo htmlspecialchars($picture); ?>" alt="Foto de Perfil" class="profile-picture">
        <h1>Bem-vindo(a), <?php echo htmlspecialchars($name); ?>!</h1>
        <p>Seu e-mail: <strong><?php echo htmlspecialchars($email); ?></strong></p>
        <p>Você está logado com sucesso usando o Google OAuth 2.0.</p>
        <a href="<?php echo $logoutUrl; ?>" class="logout-btn">Sair (Logout)</a>
    </div>
</body>
</html>
