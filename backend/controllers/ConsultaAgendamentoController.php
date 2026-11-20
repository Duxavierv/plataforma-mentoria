<?php

require_once __DIR__ . '/../models/ConsultaAgendamento.php';

class ConsultaAgendamentoController
{
    private ConsultaAgendamento $consulta;

    public function __construct(PDO $pdo)
    {
        $this->consulta = new ConsultaAgendamento($pdo);
    }

    public function listarTodos(): array
    {
        try {
            return [
                'sucesso' => true,
                'agendamentos' => $this->consulta->listarTodos()
            ];

        } catch (PDOException $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao consultar os agendamentos.'
            ];
        }
    }

    public function listarPorMentor(int $mentorId): array
    {
        if ($mentorId <= 0) {
            return [
                'sucesso' => false,
                'mensagem' => 'ID do mentor inválido.'
            ];
        }

        try {
            return [
                'sucesso' => true,
                'agendamentos' => $this->consulta->listarPorMentor($mentorId)
            ];

        } catch (PDOException $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao consultar os agendamentos do mentor.'
            ];
        }
    }

    public function listarPorMentorado(int $mentoradoId): array
    {
        if ($mentoradoId <= 0) {
            return [
                'sucesso' => false,
                'mensagem' => 'ID do mentorado inválido.'
            ];
        }

        try {
            return [
                'sucesso' => true,
                'agendamentos' => $this->consulta->listarPorMentorado($mentoradoId)
            ];

        } catch (PDOException $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao consultar os agendamentos do mentorado.'
            ];
        }
    }
}