
<?php

require_once __DIR__ . '/../models/Agendamento.php';

class AgendamentoController
{
    private Agendamento $agendamento;

    public function __construct(PDO $pdo)
    {
        $this->agendamento = new Agendamento($pdo);
    }

    public function solicitar(array $dados): array
    {
        $mentorId = filter_var(
            $dados['mentor_id'] ?? null,
            FILTER_VALIDATE_INT
        );

        $mentoradoId = filter_var(
            $dados['mentorado_id'] ?? null,
            FILTER_VALIDATE_INT
        );

        $inicio = trim($dados['data_hora_inicio'] ?? '');
        $fim = trim($dados['data_hora_fim'] ?? '');
        $observacoes = trim($dados['observacoes'] ?? '');

        if (!$mentorId || !$mentoradoId) {
            return [
                'sucesso' => false,
                'mensagem' => 'Informe um mentor e um mentorado válidos.'
            ];
        }

        $dataInicio = DateTime::createFromFormat('Y-m-d\TH:i', $inicio);
        $dataFim = DateTime::createFromFormat('Y-m-d\TH:i', $fim);

        if (
            !$dataInicio ||
            $dataInicio->format('Y-m-d\TH:i') !== $inicio ||
            !$dataFim ||
            $dataFim->format('Y-m-d\TH:i') !== $fim
        ) {
            return [
                'sucesso' => false,
                'mensagem' => 'Informe datas e horários válidos.'
            ];
        }

        if ($dataInicio <= new DateTime()) {
            return [
                'sucesso' => false,
                'mensagem' => 'O agendamento deve ser para uma data futura.'
            ];
        }

        if ($dataFim <= $dataInicio) {
            return [
                'sucesso' => false,
                'mensagem' => 'O horário de término deve ser posterior ao início.'
            ];
        }

        try {
            $resultado = $this->agendamento->solicitar(
                $mentorId,
                $mentoradoId,
                $dataInicio->format('Y-m-d H:i:s'),
                $dataFim->format('Y-m-d H:i:s'),
                $observacoes !== '' ? $observacoes : null
            );

            if ($resultado === 'PERFIL_NAO_ENCONTRADO') {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Mentor ou mentorado não encontrado.'
                ];
            }

            if ($resultado === 'CONFLITO_HORARIO') {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Este horário já está ocupado. Escolha outro horário.'
                ];
            }

            if ($resultado === 'SUCESSO') {
                return [
                    'sucesso' => true,
                    'mensagem' => 'Solicitação de agendamento realizada com sucesso!'
                ];
            }

            return [
                'sucesso' => false,
                'mensagem' => 'Não foi possível realizar o agendamento.'
            ];

        } catch (PDOException $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao solicitar agendamento.'
            ];
        }
    }
}
