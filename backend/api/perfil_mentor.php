<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/MentorController.php';

try {

    $id = filter_var(
        $_GET['id'] ?? null,
        FILTER_VALIDATE_INT
    );

    if (!$id || $id <= 0) {
        http_response_code(400);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Informe um ID de mentor válido.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $controller = new MentorController($pdo);

    $resultado = $controller->buscarPorId($id);

    http_response_code($resultado['sucesso'] ? 200 : 404);

    echo json_encode(
        $resultado,
        JSON_UNESCAPED_UNICODE
    );

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro interno ao consultar perfil do mentor.'
    ], JSON_UNESCAPED_UNICODE);
}