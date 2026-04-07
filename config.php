<?php
// ==============================================
// CONFIGURAÇÃO DO BANCO DE DADOS
// Altere as credenciais conforme seu ambiente
// ==============================================

define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'cadastro_funcionarios');
define('DB_USER', 'postgres');
define('DB_PASS', '123456');

/**
 * Retorna uma conexão PDO com o PostgreSQL
 */
function getConnection() {
    $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";options='--client_encoding=UTF8'";
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die(json_encode(['erro' => 'Erro de conexão: ' . $e->getMessage()]));
    }
}
?>
