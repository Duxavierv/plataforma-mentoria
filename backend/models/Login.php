
<?php

class Login
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function autenticar(string $email, string $senha): ?array
    {
        $sql = "SELECT id, nome, email, senha, tipo_usuario
                FROM usuarios
                WHERE email = :email
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':email' => $email
        ]);

        $usuario = $stmt->fetch();

        // Verifica se o usuário existe e se a senha está correta
        if (!$usuario || !password_verify($senha, $usuario['senha'])) {
            return null;
        }

        // Não retorna o hash da senha
        unset($usuario['senha']);

        return $usuario;
    }
}
