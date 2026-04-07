<?php
session_start();

// Se já estiver logado, redireciona para listagem
if (isset($_SESSION['usuario_id'])) {
    header('Location: listagem.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'config.php';

    $username = trim($_POST['username'] ?? '');
    $senha    = trim($_POST['senha'] ?? '');

    if ($username === '' || $senha === '') {
        $erro = 'Preencha todos os campos.';
    } else {
        $pdo  = getConnection();
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE username = :u AND senha = MD5(:s)");
        $stmt->execute([':u' => $username, ':s' => $senha]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            $_SESSION['usuario_id']   = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            header('Location: listagem.php');
            exit;
        } else {
            $erro = 'Usuário ou senha inválidos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Cadastro de Funcionários</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">

<div class="login-container">
    <div class="login-header">
        <div class="login-icon">&#128100;</div>
        <h1>Cadastro de Funcionários</h1>
    </div>

    <?php if ($erro): ?>
        <div class="alert alert-erro"><?php echo htmlspecialchars($erro); ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php">
        <div class="input-group">
            <span class="input-icon">&#128100;</span>
            <input type="text" name="username" placeholder="Usuário"
                   value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                   required autofocus>
        </div>

        <div class="input-group">
            <span class="input-icon">&#128274;</span>
            <input type="password" name="senha" placeholder="Senha" required>
        </div>

        <button type="submit" class="btn-entrar">Entrar</button>
    </form>

    <hr class="login-divider">
    <a href="esqueci-senha.php" class="link-esqueci">Esqueci minha senha</a>
</div>

</body>
</html>
