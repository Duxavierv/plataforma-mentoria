
<?php

class AtualizarStatusAgendamento
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function atualizar(
        int $agendamentoId,
        int $usuarioId,
        string $tipoUsuario,
        string $novoStatus
    ): string {

        // Localiza o agendamento e verifica quem é o responsável.
        $sql = "SELECT
                    a.id,
                    a.status,
                    m.usuario_id AS usuario_mentor,
                    md.usuario_id AS usuario_mentorado
                FROM agendamentos a
                INNER JOIN mentores m ON m.id = a.mentor_id
                INNER JOIN mentorados md ON md.id = a.mentorado_id
                WHERE a.id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $agendamentoId]);

        $agendamento = $stmt->fetch();

        if (!$agendamento) {
            return 'NAO_ENCONTRADO';
        }

        // Verifica a permissão e os status permitidos.
        if ($tipoUsuario === 'MENTOR') {

            if ((int)$agendamento['usuario_mentor'] !== $usuarioId) {
                return 'SEM_PERMISSAO';
            }

            if (!in_array($novoStatus, ['CONFIRMADO', 'RECUSADO'], true)) {
                return 'STATUS_INVALIDO';
            }

            if ($agendamento['status'] !== 'PENDENTE') {
                return 'STATUS_NAO_PERMITE';
            }

        } elseif ($tipoUsuario === 'MENTORADO') {

            if ((int)$agendamento['usuario_mentorado'] !== $usuarioId) {
                return 'SEM_PERMISSAO';
            }

            if ($novoStatus !== 'CANCELADO') {
                return 'STATUS_INVALIDO';
            }

            if (!in_array($agendamento['status'], ['PENDENTE', 'CONFIRMADO'], true)) {
                return 'STATUS_NAO_PERMITE';
            }

        } else {
            return 'SEM_PERMISSAO';
        }

        // Atualiza somente se o status ainda for o mesmo consultado.
        $sqlUpdate = "UPDATE agendamentos
                      SET status = :novo_status
                      WHERE id = :id
                        AND status = :status_atual";

        $stmtUpdate = $this->pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([
            ':novo_status' => $novoStatus,
            ':id' => $agendamentoId,
            ':status_atual' => $agendamento['status']
        ]);

        return $stmtUpdate->rowCount() > 0
            ? 'SUCESSO'
            : 'STATUS_NAO_PERMITE';
    }
}
