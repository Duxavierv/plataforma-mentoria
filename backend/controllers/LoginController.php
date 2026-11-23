
<?php

require_once __DIR__ . '/../models/Login.php';

class LoginController
{
    private Login $login;

    public function __construct(PDO $pdo)
    {
        $this->login = new Login($pdo);
    }

    public function autenticar(array $dados): array
    {
        $email = trim($dados['email'] ?? '');
        $senha = $dados['senha'] ?? '';

        if ($email === '' || $senha === '') {
            return [
                'sucesso' => false,
                'mensagem' => 'Informe o e-mail e a senha.'
            ];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'sucesso' => false,
                'mensagem' => 'E-mail inválido.'
            ];
        }

        try {
            $usuario = $this->login->autenticar($email, $senha);

            if (!$usuario) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'E-mail ou senha incorretos.'
                ];
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            session_regenerate_id(true);

            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

            return [
                'sucesso' => true,
                'mensagem' => 'Login realizado com sucesso!',
                'usuario' => $usuario
            ];

        } catch (PDOException $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao realizar login.'
            ];
        }
    }
}
