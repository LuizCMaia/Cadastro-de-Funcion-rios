-- ==============================================
-- CADASTRO DE FUNCIONÁRIOS - Banco de Dados
-- PostgreSQL
-- ==============================================

-- Criar banco de dados (executar separadamente se necessário)
-- CREATE DATABASE cadastro_funcionarios;

-- Tabela de usuários do sistema (login)
CREATE TABLE IF NOT EXISTS usuarios (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nome VARCHAR(100) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de funcionários
CREATE TABLE IF NOT EXISTS funcionarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cargo VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefone VARCHAR(20),
    situacao VARCHAR(10) NOT NULL DEFAULT 'Ativo' CHECK (situacao IN ('Ativo', 'Inativo')),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Inserir usuário admin padrão (senha: admin123)
INSERT INTO usuarios (username, senha, nome)
VALUES ('admin', MD5('admin123'), 'Administrador')
ON CONFLICT (username) DO NOTHING;

-- Inserir funcionários de exemplo
INSERT INTO funcionarios (nome, cargo, email, telefone, situacao) VALUES
('João Silva',    'Administrador', 'joao@ensx.com',   '(11) 99999-0001', 'Ativo'),
('Ana Mendes',    'Gerente',       'ana@ensx.com',    '(11) 99999-0002', 'Ativo'),
('Pedro Souza',   'Assistente',    'pedro@ensx.com',  '(11) 99999-0003', 'Ativo'),
('Carla Oliveira','Administrador', 'carla@ensx.com',  '(11) 99999-0004', 'Ativo'),
('Lucas Martins', 'Assistente',    'lucas@ensx.com',  '(11) 99999-0005', 'Inativo')
ON CONFLICT DO NOTHING;
