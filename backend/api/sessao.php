<?php

header('Content-Type: application/json; charset=utf-8');

session_start();

if (isset($_SESSION['usuario_id'])) {
    http_response_code(200);

    echo json_encode([
        'sucesso' => true,
        'autenticado' => true,
        'usuario' => [
            'id' => $_SESSION['usuario_id'],
            'nome' => $_SESSION['usuario_nome'],
            'email' => $_SESSION['usuario_email'],
            'tipo_usuario' => $_SESSION['tipo_usuario']
        ]
    ], JSON_UNESCAPED_UNICODE);

} else {
    http_response_code(401);

    echo json_encode([
        'sucesso' => false,
        'autenticado' => false,
        'mensagem' => 'Nenhum usuário autenticado.'
    ], JSON_UNESCAPED_UNICODE);
}