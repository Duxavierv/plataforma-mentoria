
<?php

class Usuario
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function cadastrar(
        string $nome,
        string $email,
        string $senha,
        ?string $telefone,
        string $tipoUsuario
    ): bool {

        // Criptografa a senha antes de salvar
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios
                (nome, email, senha, telefone, tipo_usuario)
                VALUES
                (:nome, :email, :senha, :telefone, :tipo_usuario)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $senhaHash,
            ':telefone' => $telefone,
            ':tipo_usuario' => $tipoUsuario
        ]);
    }
}
