<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/MentorController.php';

try {
    $controller = new MentorController($pdo);

    $resultado = $controller->listar();

    http_response_code($resultado['sucesso'] ? 200 : 500);

    echo json_encode(
        $resultado,
        JSON_UNESCAPED_UNICODE
    );

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro interno ao listar mentores.'
    ], JSON_UNESCAPED_UNICODE);
}