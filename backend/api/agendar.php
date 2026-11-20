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

if ($_SESSION['tipo_usuario'] !== 'MENTORADO') {
    http_response_code(403);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Apenas mentorados podem solicitar agendamentos.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AgendamentoController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Utilize o método POST.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

try {
    $dados = json_decode(file_get_contents('php://input'), true);

    if (!is_array($dados)) {
        http_response_code(400);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Dados JSON inválidos.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $dados['mentorado_id'] = null;

    $usuarioId = $_SESSION['usuario_id'];

    $stmt = $pdo->prepare(
        "SELECT id FROM mentorados WHERE usuario_id = :usuario_id"
    );

    $stmt->execute([
        ':usuario_id' => $usuarioId
    ]);

    $perfil = $stmt->fetch();

    if (!$perfil) {
        http_response_code(400);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Cadastre seu perfil de mentorado antes de solicitar uma sessão.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $dados['mentorado_id'] = $perfil['id'];

    $controller = new AgendamentoController($pdo);

    $resultado = $controller->solicitar($dados);

    http_response_code($resultado['sucesso'] ? 201 : 400);

    echo json_encode($resultado, JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro interno ao solicitar agendamento.'
    ], JSON_UNESCAPED_UNICODE);
}