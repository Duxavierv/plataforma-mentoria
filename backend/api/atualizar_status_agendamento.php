
<?php

session_start();

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AtualizarStatusAgendamentoController.php';

// Verifica se o usuário está autenticado.
if (!isset($_SESSION['usuario_id'], $_SESSION['tipo_usuario'])) {
    http_response_code(401);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Você precisa estar autenticado para realizar esta ação.'
    ]);

    exit;
}

// Aceita somente requisições POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Método não permitido. Utilize POST.'
    ]);

    exit;
}

// Recebe os dados em JSON.
$dados = json_decode(file_get_contents('php://input'), true);

if (!is_array($dados)) {
    http_response_code(400);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Dados inválidos. Envie um JSON válido.'
    ]);

    exit;
}

try {
    $controller = new AtualizarStatusAgendamentoController($pdo);

    $resultado = $controller->atualizar(
        $dados,
        (int) $_SESSION['usuario_id'],
        $_SESSION['tipo_usuario']
    );

    if (!$resultado['sucesso']) {
        $mensagem = $resultado['mensagem'];

        if ($mensagem === 'Agendamento não encontrado.') {
            http_response_code(404);
        } elseif (
            $mensagem === 'Você não tem permissão para alterar este agendamento.'
        ) {
            http_response_code(403);
        } elseif (
            $mensagem === 'Não foi possível atualizar o agendamento.'
        ) {
            http_response_code(500);
        } else {
            http_response_code(400);
        }
    }

    echo json_encode($resultado);

} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro interno ao atualizar o agendamento.'
    ]);
}
