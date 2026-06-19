DROP DATABASE IF EXISTS intelibolsas;
CREATE DATABASE intelibolsas CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE intelibolsas;

-- ========================
-- TABELAS PRINCIPAIS
-- ========================

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(50) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  tipo ENUM('aluno','instituicao','admin') NOT NULL DEFAULT 'aluno'
);

CREATE TABLE alunos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  telefone VARCHAR(20),
  area_interesse VARCHAR(100),
  usuario VARCHAR(50) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  idade INT,
  sexo VARCHAR(20),
  endereco VARCHAR(255)
);

CREATE TABLE instituicoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  senha VARCHAR(255),
  telefone VARCHAR(20),
  endereco VARCHAR(255)
);

CREATE TABLE administradores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100),
  area_manutencao VARCHAR(100),
  nivel_acesso ENUM('baixo','medio','alto') DEFAULT 'alto'
);

-- ========================
-- CURSOS E BOLSAS
-- ========================

CREATE TABLE cursos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_instituicao INT NOT NULL,
  titulo VARCHAR(100) NOT NULL,
  area VARCHAR(100),
  instituicao_origem VARCHAR(100),
  link_externo VARCHAR(255),
  descricao TEXT,
  vagas INT DEFAULT 0,
  bolsa INT DEFAULT 0,
  data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
  imagem VARCHAR(255),
  FOREIGN KEY (id_instituicao) REFERENCES instituicoes(id) ON DELETE CASCADE
);

CREATE TABLE bolsas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(100) NOT NULL,
  percentual_desconto DECIMAL(5,2),
  requisitos TEXT,
  vagas_disponiveis INT DEFAULT 0,
  validade DATE,
  id_curso INT,
  FOREIGN KEY (id_curso) REFERENCES cursos(id) ON DELETE CASCADE
);

-- ========================
-- INTERAÇÕES DO ALUNO
-- ========================

CREATE TABLE favoritos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  aluno_id INT NOT NULL,
  curso_id INT NOT NULL,
  FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
  FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE
);

CREATE TABLE inscricoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  aluno_id INT NOT NULL,
  curso_id INT NOT NULL,
  data DATETIME DEFAULT CURRENT_TIMESTAMP,
  status VARCHAR(50) DEFAULT 'Inscrito',
  FOREIGN KEY (aluno_id) REFERENCES alunos(id) ON DELETE CASCADE,
  FOREIGN KEY (curso_id) REFERENCES cursos(id) ON DELETE CASCADE
);

-- ========================
-- ADMIN E LOGS
-- ========================

CREATE TABLE logs_acesso (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(50),
  tipo VARCHAR(50),
  acao VARCHAR(100),
  data DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Trigger para registrar logins
DELIMITER $$
CREATE TRIGGER trg_login AFTER INSERT ON usuarios
FOR EACH ROW
BEGIN
  INSERT INTO logs_acesso(usuario, tipo, acao)
  VALUES (NEW.usuario, NEW.tipo, 'Novo usuário registrado');
END $$
DELIMITER ;

-- ========================
-- ADMIN PADRÃO
-- ========================

INSERT INTO usuarios (usuario, senha, tipo)
VALUES ('admin', '$2y$10$.HnzErlEzc7HgZynXRnG3OLjdiinh4hjLDVSciH9UgTU6t3Q1r5je', 'admin');

COMMIT;
