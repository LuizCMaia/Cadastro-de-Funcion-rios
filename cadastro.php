<?php
require_once 'auth.php';
require_once 'config.php';

$pdo  = getConnection();
$erro = '';
$suc  = '';

// Carregar funcionário para edição
$id          = (int)($_GET['id'] ?? 0);
$funcionario = null;

if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM funcionarios WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $funcionario = $stmt->fetch();
    if (!$funcionario) {
        header('Location: listagem.php?msg=Funcionário+não+encontrado&tipo=erro');
        exit;
    }
}

// Salvar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['nome']     ?? '');
    $cargo    = trim($_POST['cargo']    ?? '');
    $email    = trim($_POST['email']    ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $situacao = $_POST['situacao'] === 'Inativo' ? 'Inativo' : 'Ativo';
    $editId   = (int)($_POST['id'] ?? 0);

    if ($nome === '' || $cargo === '' || $email === '') {
        $erro = 'Preencha os campos obrigatórios: Nome, Cargo e E-mail.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'E-mail inválido.';
    } else {
        if ($editId > 0) {
            $stmt = $pdo->prepare(
                "UPDATE funcionarios
                 SET nome=:n, cargo=:c, email=:e, telefone=:t,
                     situacao=:s, atualizado_em=NOW()
                 WHERE id=:id"
            );
            $stmt->execute([
                ':n'  => $nome, ':c' => $cargo, ':e' => $email,
                ':t'  => $telefone, ':s' => $situacao, ':id' => $editId,
            ]);
            header('Location: listagem.php?msg=Funcionário+atualizado+com+sucesso&tipo=sucesso');
            exit;
        } else {
            $stmt = $pdo->prepare(
                "INSERT INTO funcionarios (nome, cargo, email, telefone, situacao)
                 VALUES (:n, :c, :e, :t, :s)"
            );
            $stmt->execute([
                ':n' => $nome, ':c' => $cargo, ':e' => $email,
                ':t' => $telefone, ':s' => $situacao,
            ]);
            header('Location: listagem.php?msg=Funcionário+cadastrado+com+sucesso&tipo=sucesso');
            exit;
        }
    }
}

$cargos = ['Administrador', 'Gerente', 'Assistente', 'Analista', 'Supervisor', 'Coordenador'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $funcionario ? 'Editar' : 'Novo'; ?> Funcionário</title>
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
    <h2 class="page-title">Cadastro de Funcionários</h2>

    <?php if ($erro): ?>
        <div class="alert alert-erro"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>

    <div class="card-form">
        <div class="card-form-header">
            <span>&#128100;</span> Cadastro de Funcionários
        </div>

        <form method="POST" action="cadastro.php">
            <input type="hidden" name="id" value="<?php echo $funcionario['id'] ?? 0; ?>">

            <div class="form-grid">
                <!-- Linha 1 -->
                <div class="form-group">
                    <label>ID: <span class="label-auto">Automático</span></label>
                    <input type="text" name="nome" placeholder="Nome"
                           value="<?php echo htmlspecialchars($funcionario['nome'] ?? $_POST['nome'] ?? ''); ?>"
                           required>
                </div>

                <div class="form-group">
                    <label>Nome</label>
                    <select name="cargo" required>
                        <option value="">Cargo</option>
                        <?php foreach ($cargos as $c): ?>
                            <option value="<?php echo $c; ?>"
                                <?php echo (($funcionario['cargo'] ?? $_POST['cargo'] ?? '') === $c) ? 'selected' : ''; ?>>
                                <?php echo $c; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Linha 2 -->
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email" placeholder="E-mail"
                           value="<?php echo htmlspecialchars($funcionario['email'] ?? $_POST['email'] ?? ''); ?>"
                           required>
                </div>

                <div class="form-group">
                    <label>E-mail</label>
                    <input type="text" placeholder="joao@dsebrosc.com" disabled
                           value="<?php echo htmlspecialchars($funcionario['email'] ?? ''); ?>">
                </div>

                <!-- Linha 3 -->
                <div class="form-group">
                    <label>Telefone</label>
                    <input type="text" name="telefone" placeholder="Telefone"
                           value="<?php echo htmlspecialchars($funcionario['telefone'] ?? $_POST['telefone'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Situação</label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="situacao" value="Ativo"
                                <?php echo (($funcionario['situacao'] ?? 'Ativo') === 'Ativo') ? 'checked' : ''; ?>>
                            Ativo
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="situacao" value="Inativo"
                                <?php echo (($funcionario['situacao'] ?? '') === 'Inativo') ? 'checked' : ''; ?>>
                            Inativo
                        </label>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <button type="reset"  class="btn btn-secondary">Limpar</button>
                <a href="listagem.php" class="btn btn-secondary">Voltar</a>
                <button type="button" class="btn btn-secondary"
                        onclick="window.close()">Fechar</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
