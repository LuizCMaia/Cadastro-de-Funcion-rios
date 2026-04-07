<?php
require_once 'auth.php';
require_once 'config.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $pdo  = getConnection();
    $stmt = $pdo->prepare("DELETE FROM funcionarios WHERE id = :id");
    $stmt->execute([':id' => $id]);
    header('Location: listagem.php?msg=Funcionário+excluído+com+sucesso&tipo=sucesso');
} else {
    header('Location: listagem.php?msg=ID+inválido&tipo=erro');
}
exit;
?>
