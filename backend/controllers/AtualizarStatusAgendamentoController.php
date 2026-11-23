
<?php

require_once __DIR__ . '/../models/AtualizarStatusAgendamento.php';

class AtualizarStatusAgendamentoController
{
    private AtualizarStatusAgendamento $model;

    public function __construct(PDO $pdo)
    {
        $this->model = new AtualizarStatusAgendamento($pdo);
    }

    public function atualizar(array $dados, int $usuarioId, string $tipoUsuario): array
    {
        $agendamentoId = filter_var(
            $dados['agendamento_id'] ?? null,
            FILTER_VALIDATE_INT
        );

        $novoStatus = strtoupper(trim($dados['status'] ?? ''));

        if (!$agendamentoId || $agendamentoId <= 0) {
            return [
                'sucesso' => false,
                'mensagem' => 'ID do agendamento inválido.'
            ];
        }

        if ($usuarioId <= 0) {
            return [
                'sucesso' => false,
                'mensagem' => 'Usuário inválido.'
            ];
        }

        $resultado = $this->model->atualizar(
            $agendamentoId,
            $usuarioId,
            $tipoUsuario,
            $novoStatus
        );

        switch ($resultado) {
            case 'SUCESSO':
                return [
                    'sucesso' => true,
                    'mensagem' => 'Status do agendamento atualizado com sucesso!'
                ];

            case 'NAO_ENCONTRADO':
                return [
                    'sucesso' => false,
                    'mensagem' => 'Agendamento não encontrado.'
                ];

            case 'SEM_PERMISSAO':
                return [
                    'sucesso' => false,
                    'mensagem' => 'Você não tem permissão para alterar este agendamento.'
                ];

            case 'STATUS_INVALIDO':
                return [
                    'sucesso' => false,
                    'mensagem' => 'Status não permitido para seu tipo de usuário.'
                ];

            case 'STATUS_NAO_PERMITE':
                return [
                    'sucesso' => false,
                    'mensagem' => 'Este agendamento não pode mais ter o status alterado.'
                ];

            default:
                return [
                    'sucesso' => false,
                    'mensagem' => 'Não foi possível atualizar o agendamento.'
                ];
        }
    }
}
