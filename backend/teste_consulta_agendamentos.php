<?php

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/ConsultaAgendamentoController.php';

$controller = new ConsultaAgendamentoController($pdo);

$resultado = $controller->listarTodos();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Consulta de Agendamentos</title>
</head>
<body>

    <h2>Agendamentos de Mentoria</h2>

    <?php if (!$resultado['sucesso']): ?>

        <p><?= htmlspecialchars($resultado['mensagem']) ?></p>

    <?php elseif (empty($resultado['agendamentos'])): ?>

        <p>Nenhum agendamento encontrado.</p>

    <?php else: ?>

        <table border="1" cellpadding="8">

            <tr>
                <th>ID</th>
                <th>Mentor</th>
                <th>Mentorado</th>
                <th>Especialidade</th>
                <th>Início</th>
                <th>Término</th>
                <th>Status</th>
                <th>Observações</th>
            </tr>

            <?php foreach ($resultado['agendamentos'] as $agendamento): ?>

                <tr>
                    <td><?= htmlspecialchars((string)$agendamento['id']) ?></td>
                    <td><?= htmlspecialchars($agendamento['nome_mentor']) ?></td>
                    <td><?= htmlspecialchars($agendamento['nome_mentorado']) ?></td>
                    <td><?= htmlspecialchars($agendamento['especialidade']) ?></td>
                    <td><?= htmlspecialchars($agendamento['data_hora_inicio']) ?></td>
                    <td><?= htmlspecialchars($agendamento['data_hora_fim']) ?></td>
                    <td><?= htmlspecialchars($agendamento['status']) ?></td>
                    <td><?= htmlspecialchars($agendamento['observacoes'] ?? '') ?></td>
                </tr>

            <?php endforeach; ?>

        </table>

    <?php endif; ?>

</body>
</html>