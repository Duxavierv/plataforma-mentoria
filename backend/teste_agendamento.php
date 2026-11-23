<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/AgendamentoController.php';

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new AgendamentoController($pdo);
    $resultado = $controller->solicitar($_POST);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Agendamento</title>
</head>
<body>

    <h2>Solicitar Mentoria</h2>

    <?php if ($resultado): ?>
        <p>
            <?= htmlspecialchars($resultado['mensagem']) ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <label>ID do mentor:</label><br>
        <input type="number" name="mentor_id" required><br><br>

        <label>ID do mentorado:</label><br>
        <input type="number" name="mentorado_id" required><br><br>

        <label>Data e hora de início:</label><br>
        <input type="datetime-local" name="data_hora_inicio" required><br><br>

        <label>Data e hora de término:</label><br>
        <input type="datetime-local" name="data_hora_fim" required><br><br>

        <label>Observações:</label><br>
        <textarea name="observacoes"></textarea><br><br>

        <button type="submit">Solicitar agendamento</button>

    </form>

</body>
</html>