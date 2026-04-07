# Cadastro de Funcionários

Sistema web para cadastro de funcionários desenvolvido em **PHP + PostgreSQL**, sem uso de frameworks.

## Tecnologias

- HTML5 + CSS3 (puro, sem frameworks)
- PHP 5+ / PHP 7+
- PostgreSQL
- PDO (conexão com banco)

## Estrutura de Arquivos

```
cadastro-funcionarios/
├── index.php          # Tela de login
├── auth.php           # Proteção de sessão
├── config.php         # Configuração do banco
├── cadastro.php       # Formulário de cadastro/edição
├── listagem.php       # Listagem com busca e paginação
├── excluir.php        # Excluir funcionário
├── logout.php         # Encerrar sessão
├── esqueci-senha.php  # Recuperação de senha
├── database.sql       # Script SQL para criar tabelas
└── assets/
    └── css/
        └── style.css  # Estilo principal
```

## Como rodar

### 1. Pré-requisitos
- PHP 5.6+ (ou 7+/8+)
- PostgreSQL instalado
- Extensão `pdo_pgsql` habilitada no PHP

### 2. Criar o banco de dados

```bash
psql -U postgres
```

```sql
CREATE DATABASE cadastro_funcionarios;
\c cadastro_funcionarios
\i database.sql
```

### 3. Configurar conexão

Edite o arquivo `config.php` com suas credenciais:

```php
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'cadastro_funcionarios');
define('DB_USER', 'postgres');
define('DB_PASS', 'sua_senha_aqui');  // <- altere aqui
```

### 4. Iniciar o servidor PHP (desenvolvimento)

```bash
cd cadastro-funcionarios
php -S localhost:8000
```

Acesse: [http://localhost:8000](http://localhost:8000)

### 5. Login padrão

| Campo  | Valor    |
|--------|----------|
| Usuário | `admin` |
| Senha   | `admin123` |

## Funcionalidades

- ✅ Autenticação com sessão PHP
- ✅ Cadastro de funcionários (nome, cargo, e-mail, telefone, situação)
- ✅ Listagem com busca por nome, cargo ou e-mail
- ✅ Paginação (5 registros por página)
- ✅ Edição de funcionários
- ✅ Exclusão com confirmação
- ✅ Controle de situação: Ativo / Inativo
- ✅ Design responsivo fiel ao protótipo

## Publicar no GitHub

```bash
git init
git add .
git commit -m "Primeiro commit - Cadastro de Funcionários"
git remote add origin https://github.com/SEU_USUARIO/cadastro-funcionarios.git
git push -u origin main
```
