<?php
require_once 'config.php';
require_once 'db_config.php'; // NOVO

// Verifica se o Google retornou um código de autorização
if (isset($_GET['code'])) {
    try {
        // Troca o código de autorização por um token de acesso
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        
        // Armazena o token na sessão
        $_SESSION['access_token'] = $token;

        // --- SALVAR/ATUALIZAR DADOS NO BANCO DE DADOS ---
        
        // Define o token de acesso no cliente Google
        $client->setAccessToken($token);
        
        // Cria o serviço OAuth para obter informações do usuário
        $oauth2 = new Google\Service\Oauth2($client);
        $userInfo = $oauth2->userinfo->get();

        // Extrai as informações necessárias
        $google_id = $userInfo->id;
        $name = $userInfo->name;
        $email = $userInfo->email;
        $picture = $userInfo->picture;

        // 1. Verifica se o usuário já existe
        $stmt = $pdo->prepare("SELECT id FROM users WHERE google_id = ?");
        $stmt->execute([$google_id]);
        $user = $stmt->fetch();

        if ($user) {
            // 2. Se existe, atualiza os dados
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, picture_url = ? WHERE google_id = ?");
            $stmt->execute([$name, $email, $picture, $google_id]);
            $user_id = $user['id'];
        } else {
            // 3. Se não existe, insere um novo registro
            $stmt = $pdo->prepare("INSERT INTO users (google_id, name, email, picture_url) VALUES (?, ?, ?, ?)");
            $stmt->execute([$google_id, $name, $email, $picture]);
            $user_id = $pdo->lastInsertId();
        }
        
        // Armazena o ID do usuário no banco de dados na sessão
        $_SESSION['user_id'] = $user_id; // NOVO

        // Redireciona para a página de perfil
        header('Location: profile.php');
        exit();

    } catch (Exception $e) {
        // Em caso de erro na troca do token
        echo "Erro ao obter o token de acesso: " . $e->getMessage();
        // Opcional: Logar o erro para debug
        // error_log("Google Login Error: " . $e->getMessage());
        exit();
    }
} else if (isset($_GET['error'])) {
    // O usuário cancelou o login ou houve outro erro
    echo "Erro de autenticação: " . htmlspecialchars($_GET['error']);
    exit();
} else {
    // Acesso direto sem código, redireciona para o login
    header('Location: index.php');
    exit();
}
?>
