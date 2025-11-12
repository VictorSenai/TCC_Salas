<?php
require_once 'config.php';

// Verifica se há um token de acesso na sessão
if (isset($_SESSION['access_token'])) {
    // Revoga o token de acesso no Google (boa prática de segurança)
    try {
        $client->revokeToken($_SESSION['access_token']);
    } catch (Exception $e) {
        // Ignora erros de revogação, pois o token pode já ter expirado
    }
}

// Limpa todas as variáveis de sessão
session_unset();

// Destrói a sessão
session_destroy();

// Redireciona para a página de login
header('Location: index.php');
exit();
?>