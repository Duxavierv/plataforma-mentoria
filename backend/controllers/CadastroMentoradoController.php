<?php

require_once __DIR__ . '/../models/CadastroMentorado.php';

class CadastroMentoradoController
{
    private CadastroMentorado $mentorado;

    public function __construct(PDO $pdo)
    {
        $this->mentorado = new CadastroMentorado($pdo);
    }

    public function cadastrar(array $dados): array
    {
        $usuarioId = filter_var(
            $dados['usuario_id'] ?? null,
            FILTER_VALIDATE_INT
        );

        $objetivo = trim($dados['objetivo'] ?? '');
        $areaInteresse = trim($dados['area_interesse'] ?? '');

        if (!$usuarioId) {
            return [
                'sucesso' => false,
                'mensagem' => 'Informe um ID de usuário válido.'
            ];
        }

        try {
            $cadastrado = $this->mentorado->cadastrar(
                $usuarioId,
                $objetivo !== '' ? $objetivo : null,
                $areaInteresse !== '' ? $areaInteresse : null
            );

            if (!$cadastrado) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Usuário não encontrado ou não está cadastrado como mentorado.'
                ];
            }

            return [
                'sucesso' => true,
                'mensagem' => 'Perfil de mentorado cadastrado com sucesso!'
            ];

        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return [
                    'sucesso' => false,
                    'mensagem' => 'Este usuário já possui um perfil de mentorado.'
                ];
            }

            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao cadastrar perfil de mentorado.'
            ];
        }
    }
}