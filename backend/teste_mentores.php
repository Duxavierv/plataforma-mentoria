
<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/MentorController.php';

$controller = new MentorController($pdo);
$resultado = $controller->listar();

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Lista de Mentores</title>
</head>
<body>

    <h1>Mentores disponíveis</h1>

    <?php if (!$resultado['sucesso']): ?>

        <p>
            <?= htmlspecialchars($resultado['mensagem']) ?>
        </p>

    <?php elseif (empty($resultado['mentores'])): ?>

        <p>Nenhum mentor cadastrado até o momento.</p>

    <?php else: ?>

        <?php foreach ($resultado['mentores'] as $mentor): ?>

            <div>
                <h2>
                    <?= htmlspecialchars($mentor['nome']) ?>
                </h2>

                <p>
                    <strong>Especialidade:</strong>
                    <?= htmlspecialchars($mentor['especialidade']) ?>
                </p>

                <p>
                    <strong>Descrição:</strong>
                    <?= htmlspecialchars($mentor['descricao'] ?? '') ?>
                </p>

                <p>
                    <strong>Experiência:</strong>
                    <?= (int) $mentor['experiencia_anos'] ?> anos
                </p>

                <p>
                    <strong>Valor por hora:</strong>
                    <?= $mentor['valor_hora'] !== null
                        ? 'R$ ' . number_format((float) $mentor['valor_hora'], 2, ',', '.')
                        : 'Não informado' ?>
                </p>

                <hr>
            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</body>
</html>
