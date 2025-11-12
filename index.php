<?php
require_once 'config.php';

// Se o usuário já estiver logado, redireciona para a página de perfil
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
    header('Location: profile.php');
    exit();
}

// Gera a URL de autenticação do Google
$authUrl = $client->createAuthUrl();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login com Google</title>
    <style>
        body { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background-color: #f4f4f4; }
        .login-box { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); text-align: center; }
        h1 { color: #333; }
        .google-btn {
            display: inline-block;
            background-color: #4285F4;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .google-btn:hover {
            background-color: #357ae8;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>Faça Login</h1>
        <p>Use sua conta Google para acessar.</p>
        <a href="<?php echo htmlspecialchars($authUrl); ?>" class="google-btn">
            Login com Google
        </a>
    </div>
</body>
</html>