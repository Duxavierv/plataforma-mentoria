<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/CadastroMentoradoController.php';

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CadastroMentoradoController($pdo);
    $resultado = $controller->cadastrar($_POST);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Perfil de Mentorado</title>
</head>
<body>

    <h2>Cadastro de Perfil de Mentorado</h2>

    <?php if ($resultado): ?>
        <p><?= htmlspecialchars($resultado['mensagem']) ?></p>
    <?php endif; ?>

    <form method="POST">

        <label>ID do usuário:</label><br>
        <input type="number" name="usuario_id" required><br><br>

        <label>Objetivo profissional:</label><br>
        <textarea name="objetivo"></textarea><br><br>

        <label>Área de interesse:</label><br>
        <input type="text" name="area_interesse"><br><br>

        <button type="submit">Cadastrar perfil</button>

    </form>

</body>
</html>