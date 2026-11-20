<?php

require_once __DIR__ . '/../models/CadastroMentor.php';

class CadastroMentorController
{
    private CadastroMentor $mentor;

    public function __construct(PDO $pdo)
    {
        $this->mentor = new CadastroMentor($pdo);
    }

    public function cadastrar(array $dados): array
    {
        $usuarioId = filter_var(
            $dados['usuario_id'] ?? null,
            FILTER_VALIDATE_INT
        );

        $especialidade = trim($dados['especialidade'] ?? '');
        $descricao = trim($dados['descricao'] ?? '');

        $experiencia = filter_var(
            $dados['experiencia_anos'] ?? null,
            FILTER_VALIDATE_INT
        );

        $valorHora = trim($dados['valor_hora'] ?? '');

        if (!$usuarioId || $especialidade === '') {
            return [
                'sucesso' => false,
                'mensagem' => 'Informe o usuário e a especialidade.'
            ];
        }

        if ($experiencia === false || $experiencia === null || $experiencia < 0) {
            return [
                'sucesso' => false,
                'mensagem' => 'Informe uma quantidade válida de anos de experiência.'
            ];
        }

        if ($valorHora !== '' && (!is_numeric($valorHora) || (float)$valorHora < 0)) {
            return [
                'sucesso' => false,
                'mensagem' => 'Informe um valor por hora válido.'
            ];
        }

        try {
            $cadastrado = $this->mentor->cadastrar(
                $usuarioId,
                $especialidade,
                $descricao !== '' ? $descricao : null,
                $experiencia,
                $valorHora !== '' ? (float)$valorHora : null
            );

            if (!$cadastrado) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Usuário não encontrado ou não está cadastrado como mentor.'
                ];
            }

            return [
                'sucesso' => true,
                'mensagem' => 'Perfil de mentor cadastrado com sucesso!'
            ];

        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Este usuário já possui um perfil de mentor.'
                ];
            }

            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao cadastrar perfil de mentor.'
            ];
        }
    }
}