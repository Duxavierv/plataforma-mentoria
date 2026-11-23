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
        'mensagem' => 'Apenas usuários mentorados podem cadastrar este perfil.'
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/CadastroMentoradoController.php';

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

    $dados['usuario_id'] = $_SESSION['usuario_id'];

    $controller = new CadastroMentoradoController($pdo);
    $resultado = $controller->cadastrar($dados);

    http_response_code($resultado['sucesso'] ? 201 : 400);

    echo json_encode($resultado, JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro interno ao cadastrar perfil de mentorado.'
    ], JSON_UNESCAPED_UNICODE);
}