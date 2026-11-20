
<?php

require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController
{
    private Usuario $usuario;

    public function __construct(PDO $pdo)
    {
        $this->usuario = new Usuario($pdo);
    }

    public function cadastrar(array $dados): array
    {
        // Verifica os campos obrigatórios
        $nome = trim($dados['nome'] ?? '');
        $email = trim($dados['email'] ?? '');
        $senha = $dados['senha'] ?? '';
        $telefone = trim($dados['telefone'] ?? '');
        $tipoUsuario = strtoupper(trim($dados['tipo_usuario'] ?? ''));

        if ($nome === '' || $email === '' || $senha === '') {
            return [
                'sucesso' => false,
                'mensagem' => 'Preencha nome, e-mail e senha.'
            ];
        }

        // Valida o formato do e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'sucesso' => false,
                'mensagem' => 'E-mail inválido.'
            ];
        }

        // Valida o tipo de usuário
        if (!in_array($tipoUsuario, ['MENTOR', 'MENTORADO'], true)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Tipo de usuário inválido.'
            ];
        }

        try {
            $this->usuario->cadastrar(
                $nome,
                $email,
                $senha,
                $telefone !== '' ? $telefone : null,
                $tipoUsuario
            );

            return [
                'sucesso' => true,
                'mensagem' => 'Usuário cadastrado com sucesso!'
            ];

        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Este e-mail já está cadastrado.'
                ];
            }

            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao cadastrar usuário.'
            ];
        }
    }
}
