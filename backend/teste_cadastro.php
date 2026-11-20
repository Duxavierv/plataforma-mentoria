
<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/UsuarioController.php';

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new UsuarioController($pdo);
    $resultado = $controller->cadastrar($_POST);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Teste de Cadastro</title>
</head>
<body>

    <h2>Cadastro de Usuário - Teste Backend</h2>

    <?php if ($resultado): ?>
        <p>
            <?= htmlspecialchars($resultado['mensagem']) ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <label>Nome:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>E-mail:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Senha:</label><br>
        <input type="password" name="senha" required><br><br>

        <label>Telefone:</label><br>
        <input type="text" name="telefone"><br><br>

        <label>Tipo de usuário:</label><br>
        <select name="tipo_usuario" required>
            <option value="">Selecione</option>
            <option value="MENTOR">Mentor</option>
            <option value="MENTORADO">Mentorado</option>
        </select><br><br>

        <button type="submit">Cadastrar</button>
    </form>

</body>
</html>
