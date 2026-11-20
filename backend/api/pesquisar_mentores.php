<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/MentorController.php';

try {
    $especialidade = $_GET['especialidade'] ?? '';

    if (trim($especialidade) === '') {
        http_response_code(400);

        echo json_encode([
            'sucesso' => false,
            'mensagem' => 'Informe uma especialidade para pesquisar.'
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }

    $controller = new MentorController($pdo);

    $resultado = $controller->buscar($especialidade);

    http_response_code($resultado['sucesso'] ? 200 : 404);

    echo json_encode(
        $resultado,
        JSON_UNESCAPED_UNICODE
    );

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro interno ao pesquisar mentores.'
    ], JSON_UNESCAPED_UNICODE);
}