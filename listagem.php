<?php
require_once 'auth.php';
require_once 'config.php';

$pdo = getConnection();

// Busca e paginação
$busca    = trim($_GET['busca'] ?? '');
$pagina   = max(1, (int)($_GET['pagina'] ?? 1));
$porPagina = 5;
$offset   = ($pagina - 1) * $porPagina;

// Contar total
if ($busca !== '') {
    $stmtCount = $pdo->prepare(
        "SELECT COUNT(*) FROM funcionarios
         WHERE nome ILIKE :b OR cargo ILIKE :b OR email ILIKE :b"
    );
    $stmtCount->execute([':b' => "%$busca%"]);
} else {
    $stmtCount = $pdo->query("SELECT COUNT(*) FROM funcionarios");
}
$total      = (int)$stmtCount->fetchColumn();
$totalPaginas = max(1, (int)ceil($total / $porPagina));

// Buscar registros
if ($busca !== '') {
    $stmt = $pdo->prepare(
        "SELECT * FROM funcionarios
         WHERE nome ILIKE :b OR cargo ILIKE :b OR email ILIKE :b
         ORDER BY id LIMIT :lim OFFSET :off"
    );
    $stmt->bindValue(':b',   "%$busca%");
    $stmt->bindValue(':lim', $porPagina, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset,    PDO::PARAM_INT);
    $stmt->execute();
} else {
    $stmt = $pdo->prepare(
        "SELECT * FROM funcionarios ORDER BY id LIMIT :lim OFFSET :off"
    );
    $stmt->bindValue(':lim', $porPagina, PDO::PARAM_INT);
    $stmt->bindValue(':off', $offset,    PDO::PARAM_INT);
    $stmt->execute();
}
$funcionarios = $stmt->fetchAll();

// Mensagem de sucesso/erro vinda de outras páginas
$msg     = $_GET['msg']  ?? '';
$msgTipo = $_GET['tipo'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem – Cadastro de Funcionários</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="app-page">

<!-- NAV -->
<nav class="navbar">
    <div class="nav-brand">
        <span class="nav-icon">&#127760;</span>
        <span>Cadastro de Funcionários</span>
    </div>
    <div class="nav-links">
        <a href="listagem.php" class="nav-link">Início</a>
        <a href="listagem.php" class="nav-link active">Listagem</a>
    </div>
    <div class="nav-user">
        <span>Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?> &#9660;</span>
        <div class="dropdown">
            <a href="logout.php">Sair</a>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="page-title">Listagem de Funcionários</h2>

    <?php if ($msg): ?>
        <div class="alert alert-<?php echo $msgTipo === 'sucesso' ? 'sucesso' : 'erro'; ?>">
            <?php echo htmlspecialchars($msg); ?>
        </div>
    <?php endif; ?>

    <!-- Barra de busca -->
    <form method="GET" action="listagem.php" class="search-bar">
        <input type="text" name="busca" placeholder="&#128269; Buscar funcionário..."
               value="<?php echo htmlspecialchars($busca); ?>">
        <button type="submit" class="btn btn-primary">Pesquisar</button>
        <a href="cadastro.php" class="btn btn-success">Novo Funcionário</a>
    </form>

    <!-- Tabela -->
    <table class="tabela">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Cargo</th>
                <th>E-mail</th>
                <th>Situação</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($funcionarios) === 0): ?>
            <tr>
                <td colspan="6" class="sem-resultado">Nenhum funcionário encontrado.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($funcionarios as $i => $f): ?>
            <tr>
                <td><?php echo $offset + $i + 1; ?>.</td>
                <td><?php echo htmlspecialchars($f['nome']); ?></td>
                <td><?php echo htmlspecialchars($f['cargo']); ?></td>
                <td><em><?php echo htmlspecialchars($f['email']); ?></em></td>
                <td>
                    <span class="badge <?php echo $f['situacao'] === 'Ativo' ? 'badge-ativo' : 'badge-inativo'; ?>">
                        <?php echo $f['situacao']; ?>
                    </span>
                </td>
                <td class="acoes">
                    <a href="cadastro.php?id=<?php echo $f['id']; ?>" class="btn-acao btn-editar" title="Editar">&#9998;</a>
                    <a href="enviar-email.php?id=<?php echo $f['id']; ?>" class="btn-acao btn-email" title="E-mail">&#9993;</a>
                    <a href="excluir.php?id=<?php echo $f['id']; ?>"
                       class="btn-acao btn-excluir" title="Excluir"
                       onclick="return confirm('Confirma exclusão de <?php echo htmlspecialchars(addslashes($f['nome'])); ?>?')">&#128465;</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- Paginação -->
    <?php if ($totalPaginas > 1): ?>
    <div class="paginacao">
        <?php for ($p = 1; $p <= $totalPaginas; $p++): ?>
            <a href="listagem.php?pagina=<?php echo $p; ?>&busca=<?php echo urlencode($busca); ?>"
               class="pag-btn <?php echo $p === $pagina ? 'pag-ativo' : ''; ?>">
                <?php echo $p; ?>
            </a>
        <?php endfor; ?>
        <?php if ($pagina < $totalPaginas): ?>
            <a href="listagem.php?pagina=<?php echo $pagina + 1; ?>&busca=<?php echo urlencode($busca); ?>"
               class="pag-btn">Próximo &raquo;</a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

</body>
</html>
