
<?php

header('Content-Type: application/json; charset=utf-8');

session_start();

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'É necessário realizar login.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/ConsultaAgendamentoController.php';

try {
    $usuarioId = $_SESSION['usuario_id'];
    $tipoUsuario = $_SESSION['tipo_usuario'];

    $controller = new ConsultaAgendamentoController($pdo);

    if ($tipoUsuario === 'MENTOR') {

        $stmt = $pdo->prepare(
            "SELECT id FROM mentores WHERE usuario_id = :usuario_id"
        );

        $stmt->execute([
            ':usuario_id' => $usuarioId
        ]);

        $perfil = $stmt->fetch();

        if (!$perfil) {
            http_response_code(404);

            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Perfil de mentor não encontrado.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        $resultado = $controller->listarPorMentor((int) $perfil['id']);

    } elseif ($tipoUsuario === 'MENTORADO') {

        $stmt = $pdo->prepare(
            "SELECT id FROM mentorados WHERE usuario_id = :usuario_id"
        );

        $stmt->execute([
            ':usuario_id' => $usuarioId
        ]);

        $perfil = $stmt->fetch();

        if (!$perfil) {
            http_response_code(404);

            echo json_encode([
                'sucesso' => false,
                'mensagem' => 'Perfil de mentorado não encontrado.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        $resultado = $controller->listarPorMentorado((int) $perfil['id']);

    } else {
        http_response_code(403);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Tipo de usuário inválido.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    http_response_code($resultado['sucesso'] ? 200 : 500);

    echo json_encode($resultado, JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro interno ao consultar agendamentos.'
    ], JSON_UNESCAPED_UNICODE);
}
