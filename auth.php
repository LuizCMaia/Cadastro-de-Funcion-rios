<?php
// auth.php — incluir no topo de páginas protegidas
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: index.php');
    exit;
}
?>
