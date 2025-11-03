<?php
session_start();

// Configurações de conexão com o banco
$servername = "localhost:3307";
$username = "root";
$password = "";
$dbname = "rooms";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Remove everything else - connection handling should be in processar_adicao_sala.php