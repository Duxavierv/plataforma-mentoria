
<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/MentorController.php';

$controller = new MentorController($pdo);

$resultado = null;

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($id === false) {
        $resultado = [
            'sucesso' => false,
            'mensagem' => 'Identificador inválido.'
        ];
    } else {
        $resultado = $controller->buscarPorId($id);
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Mentor</title>
</head>
<body>

    <h1>Consultar Perfil do Mentor</h1>

    <form method="GET">
        <label>ID do mentor:</label><br>

        <input
            type="number"
            name="id"
            min="1"
            required
        >

        <button type="submit">Consultar</button>
    </form>

    <hr>

    <?php if ($resultado !== null): ?>

        <?php if (!$resultado['sucesso']): ?>

            <p>
                <?= htmlspecialchars($resultado['mensagem']) ?>
            </p>

        <?php else: ?>

            <?php $mentor = $resultado['mentor']; ?>

            <h2><?= htmlspecialchars($mentor['nome']) ?></h2>

            <p>
                <strong>E-mail:</strong>
                <?= htmlspecialchars($mentor['email']) ?>
            </p>

            <p>
                <strong>Telefone:</strong>
                <?= htmlspecialchars($mentor['telefone'] ?? 'Não informado') ?>
            </p>

            <p>
                <strong>Especialidade:</strong>
                <?= htmlspecialchars($mentor['especialidade']) ?>
            </p>

            <p>
                <strong>Descrição:</strong>
                <?= htmlspecialchars($mentor['descricao'] ?? 'Não informada') ?>
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

        <?php endif; ?>

    <?php endif; ?>

</body>
</html>
