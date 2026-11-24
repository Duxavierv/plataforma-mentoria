
-- Projeto: Plataforma de Mentoria
-- Banco de dados: plataforma_mentoria
-- SGBD: MySQL 8.0+
-- Como executar:
-- 1. Abra este arquivo no MySQL Workbench.
-- 2. Execute o script completo.


CREATE DATABASE IF NOT EXISTS plataforma_mentoria
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE plataforma_mentoria;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20) NULL,
    tipo_usuario ENUM('MENTOR', 'MENTORADO') NOT NULL,
    data_cadastro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT pk_usuarios PRIMARY KEY (id),
    CONSTRAINT uq_usuarios_email UNIQUE (email)
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS mentores (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    usuario_id INT UNSIGNED NOT NULL,
    especialidade VARCHAR(150) NOT NULL,
    descricao TEXT NULL,
    experiencia_anos INT UNSIGNED NOT NULL DEFAULT 0,
    valor_hora DECIMAL(10,2) NULL,

    CONSTRAINT pk_mentores PRIMARY KEY (id),
    CONSTRAINT uq_mentores_usuario UNIQUE (usuario_id),
    CONSTRAINT fk_mentores_usuarios
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;


CREATE TABLE IF NOT EXISTS mentorados (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    usuario_id INT UNSIGNED NOT NULL,
    objetivo TEXT NULL,
    area_interesse VARCHAR(150) NULL,

    CONSTRAINT pk_mentorados PRIMARY KEY (id),
    CONSTRAINT uq_mentorados_usuario UNIQUE (usuario_id),
    CONSTRAINT fk_mentorados_usuarios
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS agendamentos (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    mentor_id INT UNSIGNED NOT NULL,
    mentorado_id INT UNSIGNED NOT NULL,
    data_hora_inicio DATETIME NOT NULL,
    data_hora_fim DATETIME NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'PENDENTE',
    observacoes TEXT NULL,
    data_criacao TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT pk_agendamentos PRIMARY KEY (id),
    CONSTRAINT fk_agendamentos_mentores
        FOREIGN KEY (mentor_id)
        REFERENCES mentores(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT fk_agendamentos_mentorados
        FOREIGN KEY (mentorado_id)
        REFERENCES mentorados(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    INDEX idx_agendamentos_mentor_data (mentor_id, data_hora_inicio),
    INDEX idx_agendamentos_mentorado_data (mentorado_id, data_hora_inicio),
    INDEX idx_agendamentos_status (status)
) ENGINE=InnoDB;

