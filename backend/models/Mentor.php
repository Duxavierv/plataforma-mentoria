
<?php

class Mentor
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function listarTodos(): array
    {
        $sql = "SELECT
                    m.id,
                    u.nome,
                    u.email,
                    u.telefone,
                    m.especialidade,
                    m.descricao,
                    m.experiencia_anos,
                    m.valor_hora
                FROM mentores m
                INNER JOIN usuarios u
                    ON m.usuario_id = u.id
                WHERE u.tipo_usuario = 'MENTOR'
                ORDER BY u.nome ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }


public function buscarPorEspecialidade(string $especialidade): array
{
    $sql = "SELECT
                m.id,
                u.nome,
                u.email,
                u.telefone,
                m.especialidade,
                m.descricao,
                m.experiencia_anos,
                m.valor_hora
            FROM mentores m
            INNER JOIN usuarios u
                ON m.usuario_id = u.id
            WHERE u.tipo_usuario = 'MENTOR'
              AND m.especialidade LIKE :especialidade
            ORDER BY u.nome ASC";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ':especialidade' => '%' . $especialidade . '%'
    ]);

    return $stmt->fetchAll();
}


public function buscarPorId(int $id): ?array
{
    $sql = "SELECT
                m.id,
                u.nome,
                u.email,
                u.telefone,
                m.especialidade,
                m.descricao,
                m.experiencia_anos,
                m.valor_hora
            FROM mentores m
            INNER JOIN usuarios u
                ON m.usuario_id = u.id
            WHERE m.id = :id
              AND u.tipo_usuario = 'MENTOR'
            LIMIT 1";

    $stmt = $this->pdo->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    $mentor = $stmt->fetch();

    return $mentor ?: null;
}


}
