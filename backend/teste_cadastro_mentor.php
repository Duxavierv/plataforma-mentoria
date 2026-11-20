<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/CadastroMentorController.php';

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new CadastroMentorController($pdo);
    $resultado = $controller->cadastrar($_POST);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Perfil de Mentor</title>
</head>
<body>

    <h2>Cadastro de Perfil de Mentor</h2>

    <?php if ($resultado): ?>
        <p>
            <?= htmlspecialchars($resultado['mensagem']) ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>ID do usuário:</label><br>
        <input type="number" name="usuario_id" required><br><br>

        <label>Especialidade:</label><br>
        <input type="text" name="especialidade" required><br><br>

        <label>Descrição:</label><br>
        <textarea name="descricao"></textarea><br><br>

        <label>Anos de experiência:</label><br>
        <input type="number" name="experiencia_anos" min="0" value="0" required><br><br>

        <label>Valor por hora:</label><br>
        <input type="number" name="valor_hora" min="0" step="0.01"><br><br>

        <button type="submit">Cadastrar perfil</button>

    </form>

</body>
</html>