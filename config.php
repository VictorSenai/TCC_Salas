config.php
<?php
// Inicia a sessão para armazenar o estado de login e tokens
session_start();

// Inclui o autoloader do Composer
require_once 'vendor/autoload.php';

// --- CONFIGURAÇÕES DO GOOGLE ---

// 1. Substitua 'SEU_CLIENT_ID' pelo ID do Cliente da sua aplicação no Google Developer Console
define('CLIENT_ID', '');

// 2. Substitua 'SEU_CLIENT_SECRET' pelo Segredo do Cliente da sua aplicação
define('CLIENT_SECRET', '');

// 3. Substitua 'SUA_REDIRECT_URI' pela URL de redirecionamento que você configurou no Google Developer Console.
// Exemplo: http://localhost/google-login-php/callback.php
define('REDIRECT_URI', 'SUA_REDIRECT_URI');

// --- INICIALIZAÇÃO DO CLIENTE GOOGLE ---

$client = new Google\Client();
$client->setClientId(CLIENT_ID);
$client->setClientSecret(CLIENT_SECRET);
$client->setRedirectUri(REDIRECT_URI);

// Define as permissões (scopes) que você precisa.
// 'email' e 'profile' são o mínimo para login.
$client->addScope('email');
$client->addScope('profile');

// Opcional: Força o consentimento para obter um refresh token (útil para acesso offline)
// $client->setAccessType('offline');

?>