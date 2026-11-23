<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/UsuarioController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido. Utilize POST.'
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

    $controller = new UsuarioController($pdo);

    $resultado = $controller->cadastrar($dados);

    http_response_code($resultado['sucesso'] ? 201 : 400);

    echo json_encode($resultado, JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro interno ao cadastrar usuário.'
    ], JSON_UNESCAPED_UNICODE);
}