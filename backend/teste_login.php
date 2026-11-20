
<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/LoginController.php';

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new LoginController($pdo);
    $resultado = $controller->autenticar($_POST);
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Teste de Login</title>
</head>
<body>

    <h2>Login - Plataforma de Mentoria</h2>

    <?php if ($resultado): ?>
        <p>
            <?= htmlspecialchars($resultado['mensagem']) ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>E-mail:</label><br>
        <input type="email" name="email" required>
        <br><br>

        <label>Senha:</label><br>
        <input type="password" name="senha" required>
        <br><br>

        <button type="submit">Entrar</button>
/*
Dados do usuário de teste:

Login: teste.mentoria01@email.com
Senha: Teste@123
*/
    </form>

</body>
</html>
