<?php
// Configurações do Banco de Dados MySQL (XAMPP padrão)
define('DB_HOST', 'localhost');
define('DB_NAME', 'google_login_db'); // Crie este banco de dados!
define('DB_USER', 'root');
define('DB_PASS', ''); // Senha padrão do XAMPP

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de Conexão com o Banco de Dados: " . $e->getMessage());
}

// SQL para criar a tabela de usuários (Execute este código no seu phpMyAdmin):
/*
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    google_id VARCHAR(255) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    picture_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
*/
?>
