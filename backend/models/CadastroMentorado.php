<?php

class CadastroMentorado
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function cadastrar(
        int $usuarioId,
        ?string $objetivo,
        ?string $areaInteresse
    ): bool {

        $sql = "INSERT INTO mentorados
                (usuario_id, objetivo, area_interesse)
                SELECT id, :objetivo, :area_interesse
                FROM usuarios
                WHERE id = :usuario_id
                  AND tipo_usuario = 'MENTORADO'";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':objetivo' => $objetivo,
            ':area_interesse' => $areaInteresse
        ]);

        return $stmt->rowCount() > 0;
    }
}