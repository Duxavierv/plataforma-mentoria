<?php

class CadastroMentor
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function cadastrar(
        int $usuarioId,
        string $especialidade,
        ?string $descricao,
        int $experienciaAnos,
        ?float $valorHora
    ): bool {

        $sql = "INSERT INTO mentores
                (usuario_id, especialidade, descricao, experiencia_anos, valor_hora)
                SELECT id, :especialidade, :descricao, :experiencia, :valor
                FROM usuarios
                WHERE id = :usuario_id
                  AND tipo_usuario = 'MENTOR'";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':especialidade' => $especialidade,
            ':descricao' => $descricao,
            ':experiencia' => $experienciaAnos,
            ':valor' => $valorHora
        ]);

        return $stmt->rowCount() > 0;
    }
}