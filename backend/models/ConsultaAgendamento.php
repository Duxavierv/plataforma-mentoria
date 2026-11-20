<?php

class ConsultaAgendamento
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listarTodos(): array
    {
        $sql = "SELECT
                    a.id,
                    um.nome AS nome_mentor,
                    ud.nome AS nome_mentorado,
                    m.especialidade,
                    a.data_hora_inicio,
                    a.data_hora_fim,
                    a.status,
                    a.observacoes
                FROM agendamentos a
                INNER JOIN mentores m
                    ON a.mentor_id = m.id
                INNER JOIN usuarios um
                    ON m.usuario_id = um.id
                INNER JOIN mentorados md
                    ON a.mentorado_id = md.id
                INNER JOIN usuarios ud
                    ON md.usuario_id = ud.id
                ORDER BY a.data_hora_inicio DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function listarPorMentor(int $mentorId): array
    {
        $sql = "SELECT
                    a.id,
                    um.nome AS nome_mentor,
                    ud.nome AS nome_mentorado,
                    m.especialidade,
                    a.data_hora_inicio,
                    a.data_hora_fim,
                    a.status,
                    a.observacoes
                FROM agendamentos a
                INNER JOIN mentores m
                    ON a.mentor_id = m.id
                INNER JOIN usuarios um
                    ON m.usuario_id = um.id
                INNER JOIN mentorados md
                    ON a.mentorado_id = md.id
                INNER JOIN usuarios ud
                    ON md.usuario_id = ud.id
                WHERE a.mentor_id = :mentor_id
                ORDER BY a.data_hora_inicio DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':mentor_id' => $mentorId
        ]);

        return $stmt->fetchAll();
    }

    public function listarPorMentorado(int $mentoradoId): array
    {
        $sql = "SELECT
                    a.id,
                    um.nome AS nome_mentor,
                    ud.nome AS nome_mentorado,
                    m.especialidade,
                    a.data_hora_inicio,
                    a.data_hora_fim,
                    a.status,
                    a.observacoes
                FROM agendamentos a
                INNER JOIN mentores m
                    ON a.mentor_id = m.id
                INNER JOIN usuarios um
                    ON m.usuario_id = um.id
                INNER JOIN mentorados md
                    ON a.mentorado_id = md.id
                INNER JOIN usuarios ud
                    ON md.usuario_id = ud.id
                WHERE a.mentorado_id = :mentorado_id
                ORDER BY a.data_hora_inicio DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':mentorado_id' => $mentoradoId
        ]);

        return $stmt->fetchAll();
    }
}