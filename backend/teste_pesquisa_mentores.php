
<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/MentorController.php';

$controller = new MentorController($pdo);

$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['especialidade'])) {
    $resultado = $controller->buscar($_GET['especialidade']);
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pesquisar Mentores</title>
</head>
<body>

    <h1>Pesquisar Mentores</h1>

    <form method="GET">

        <label>Especialidade:</label><br>

        <input
            type="text"
            name="especialidade"
            placeholder="Ex: Java, Banco de Dados..."
            required
        >

        <button type="submit">Pesquisar</button>

    </form>

    <hr>

    <?php if ($resultado !== null): ?>

        <?php if (!$resultado['sucesso']): ?>

            <p>
                <?= htmlspecialchars($resultado['mensagem']) ?>
            </p>

        <?php elseif (empty($resultado['mentores'])): ?>

            <p>Nenhum mentor encontrado para essa especialidade.</p>

        <?php else: ?>

            <?php foreach ($resultado['mentores'] as $mentor): ?>

                <h2><?= htmlspecialchars($mentor['nome']) ?></h2>

                <p>
                    <strong>Especialidade:</strong>
                    <?= htmlspecialchars($mentor['especialidade']) ?>
                </p>

                <p>
                    <strong>Experiência:</strong>
                    <?= (int) $mentor['experiencia_anos'] ?> anos
                </p>

                <hr>

            <?php endforeach; ?>

        <?php endif; ?>

    <?php endif; ?>

</body>
</html>
