
<?php

class Agendamento
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function solicitar(
        int $mentorId,
        int $mentoradoId,
        string $dataHoraInicio,
        string $dataHoraFim,
        ?string $observacoes
    ): string {

        try {

            // Inicia uma transação.
            $this->pdo->beginTransaction();

            // Bloqueia o registro do mentor.
            // Outras solicitações para esse mentor precisarão aguardar.
            $sqlMentor = "SELECT id
                          FROM mentores
                          WHERE id = :mentor_id
                          FOR UPDATE";

            $stmtMentor = $this->pdo->prepare($sqlMentor);
            $stmtMentor->execute([
                ':mentor_id' => $mentorId
            ]);

            $mentor = $stmtMentor->fetch();

            if (!$mentor) {
                $this->pdo->rollBack();
                return 'PERFIL_NAO_ENCONTRADO';
            }

            // Bloqueia o registro do mentorado.
            $sqlMentorado = "SELECT id
                             FROM mentorados
                             WHERE id = :mentorado_id
                             FOR UPDATE";

            $stmtMentorado = $this->pdo->prepare($sqlMentorado);
            $stmtMentorado->execute([
                ':mentorado_id' => $mentoradoId
            ]);

            $mentorado = $stmtMentorado->fetch();

            if (!$mentorado) {
                $this->pdo->rollBack();
                return 'PERFIL_NAO_ENCONTRADO';
            }

            // Verifica conflitos de horário.
            $sqlConflito = "SELECT COUNT(*)
                            FROM agendamentos
                            WHERE status IN ('PENDENTE', 'CONFIRMADO')
                              AND (
                                  mentor_id = :mentor_id
                                  OR mentorado_id = :mentorado_id
                              )
                              AND data_hora_inicio < :fim
                              AND data_hora_fim > :inicio";

            $stmtConflito = $this->pdo->prepare($sqlConflito);

            $stmtConflito->execute([
                ':mentor_id' => $mentorId,
                ':mentorado_id' => $mentoradoId,
                ':inicio' => $dataHoraInicio,
                ':fim' => $dataHoraFim
            ]);

            if ($stmtConflito->fetchColumn() > 0) {
                $this->pdo->rollBack();
                return 'CONFLITO_HORARIO';
            }

            // Insere o novo agendamento.
            $sql = "INSERT INTO agendamentos
                    (
                        mentor_id,
                        mentorado_id,
                        data_hora_inicio,
                        data_hora_fim,
                        status,
                        observacoes
                    )
                    VALUES (
                        :mentor_id,
                        :mentorado_id,
                        :inicio,
                        :fim,
                        'PENDENTE',
                        :observacoes
                    )";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                ':mentor_id' => $mentorId,
                ':mentorado_id' => $mentoradoId,
                ':inicio' => $dataHoraInicio,
                ':fim' => $dataHoraFim,
                ':observacoes' => $observacoes
            ]);

            // Confirma a transação.
            $this->pdo->commit();

            return 'SUCESSO';

        } catch (Throwable $e) {

            // Desfaz a operação se ocorrer algum erro.
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }
}
