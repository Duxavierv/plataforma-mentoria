
<?php

require_once __DIR__ . '/../models/Mentor.php';

class MentorController
{
    private Mentor $mentor;

    public function __construct(PDO $pdo)
    {
        $this->mentor = new Mentor($pdo);
    }

    public function listar(): array
    {
        try {
            $mentores = $this->mentor->listarTodos();

            return [
                'sucesso' => true,
                'mentores' => $mentores
            ];

        } catch (PDOException $e) {
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao consultar os mentores.'
            ];
        }
    }

public function buscar(string $especialidade): array
{
    try {
        $especialidade = trim($especialidade);

        if ($especialidade === '') {
            return [
                'sucesso' => false,
                'mensagem' => 'Informe uma especialidade para pesquisar.'
            ];
        }

        $mentores = $this->mentor->buscarPorEspecialidade($especialidade);

        return [
            'sucesso' => true,
            'mentores' => $mentores
        ];

    } catch (PDOException $e) {
        return [
            'sucesso' => false,
            'mensagem' => 'Erro ao pesquisar mentores.'
        ];
    }
}


public function buscarPorId(int $id): array
{
    try {
        if ($id <= 0) {
            return [
                'sucesso' => false,
                'mensagem' => 'Identificador do mentor inválido.'
            ];
        }

        $mentor = $this->mentor->buscarPorId($id);

        if (!$mentor) {
            return [
                'sucesso' => false,
                'mensagem' => 'Mentor não encontrado.'
            ];
        }

        return [
            'sucesso' => true,
            'mentor' => $mentor
        ];

    } catch (PDOException $e) {
        return [
            'sucesso' => false,
            'mensagem' => 'Erro ao consultar o perfil do mentor.'
        ];
    }
}


}
