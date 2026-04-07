<?php
session_start();
require_once 'config.php'; // Adicionamos a conexão com o banco

$msg = '';
$tipo = 'sucesso'; // Define a cor do alerta (sucesso ou erro)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');

    if ($username === '') {
        $msg = 'Por favor, informe o usuário.';
        $tipo = 'erro';
    } else {
        $pdo = getConnection();
        
        // Verifica se o usuário existe no banco
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = :u");
        $stmt->execute([':u' => $username]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            // Gera uma nova senha temporária aleatória (ex: admin456, admin789)
            $novaSenha = 'admin' . rand(100, 999);

            // Atualiza no banco usando MD5 (padrão do sistema)
            $update = $pdo->prepare("UPDATE usuarios SET senha = MD5(:s) WHERE id = :id");
            $update->execute([':s' => $novaSenha, ':id' => $usuario['id']]);

            $msg = "Senha redefinida! Sua nova senha temporária é: <br><strong style='font-size: 1.2rem; color: #35609c;'>$novaSenha</strong>";
            $tipo = 'sucesso';
        } else {
            $msg = 'Usuário não encontrado no sistema.';
            $tipo = 'erro';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background-color: #eaedf2; display: flex; justify-content: center; align-items: center; height: 100vh;">

    <div class="app-container" style="max-width: 400px; width: 100%; padding: 30px; text-align: center;">
        
        <h2 class="page-title" style="margin-bottom: 20px;">
            <i class="fas fa-lock"></i> Recuperar Senha
        </h2>

        <?php if ($msg): ?>
            <!-- Exibe a mensagem de erro ou a nova senha -->
            <div style="padding: 15px; margin-bottom: 20px; border-radius: 4px; background-color: <?php echo $tipo === 'erro' ? '#f8d7da' : '#d4edda'; ?>; color: <?php echo $tipo === 'erro' ? '#721c24' : '#155724'; ?>; border: 1px solid <?php echo $tipo === 'erro' ? '#f5c6cb' : '#c3e6cb'; ?>;">
                <?php echo $msg; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div style="margin-bottom: 20px; text-align: left;">
                <label style="font-weight: bold; color: #333; font-size: 0.9rem; display: block; margin-bottom: 5px;">Usuário</label>
                <input type="text" name="username" placeholder="Digite seu usuário (ex: admin)" required autofocus
                       style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>
            
            <button type="submit" class="btn-blue" style="width: 100%; padding: 12px; font-size: 1rem; font-weight: bold;">
                Gerar Nova Senha
            </button>
        </form>

        <div style="margin-top: 25px;">
            <a href="index.php" style="color: #4a7bbd; text-decoration: none; font-weight: bold;">
                <i class="fas fa-arrow-left"></i> Voltar ao login
            </a>
        </div>
        
    </div>

</body>
</html>