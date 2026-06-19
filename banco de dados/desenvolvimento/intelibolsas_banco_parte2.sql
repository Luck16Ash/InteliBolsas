-- =========================
-- PARTE 2: PROCEDURES / ALTERS / VIEWS (VERSÃO CORRIGIDA)

USE intelibolsas;

-- =========================
-- 1) ALTER TABLE: adiciona colunas utilitárias (ativo, updated_at)
-- =========================

ALTER TABLE usuarios
  ADD COLUMN ativo TINYINT(1) NOT NULL DEFAULT 1,
  ADD COLUMN updated_at DATETIME DEFAULT NULL;

ALTER TABLE alunos
  ADD COLUMN ativo TINYINT(1) NOT NULL DEFAULT 1,
  ADD COLUMN updated_at DATETIME DEFAULT NULL;

ALTER TABLE instituicoes
  ADD COLUMN ativo TINYINT(1) NOT NULL DEFAULT 1,
  ADD COLUMN updated_at DATETIME DEFAULT NULL;

ALTER TABLE cursos
  ADD COLUMN updated_at DATETIME DEFAULT NULL;

-- =========================
-- 2) PROCEDURES: cadastros e operações básicas
-- =========================
DELIMITER $$

CREATE PROCEDURE Cadastro_aluno (
  IN p_nome VARCHAR(100),
  IN p_email VARCHAR(100),
  IN p_usuario VARCHAR(50),
  IN p_senha VARCHAR(255),
  IN p_telefone VARCHAR(20),
  IN p_area_interesse VARCHAR(100)
)
BEGIN
  INSERT INTO alunos (nome, email, usuario, senha, telefone, area_interesse)
  VALUES (p_nome, p_email, p_usuario, p_senha, p_telefone, p_area_interesse);
END $$


CREATE PROCEDURE Cadastro_instituicao (
  IN p_nome VARCHAR(100),
  IN p_email VARCHAR(100),
  IN p_senha VARCHAR(255),
  IN p_telefone VARCHAR(20),
  IN p_endereco VARCHAR(255)
)
BEGIN
  INSERT INTO instituicoes (nome, email, senha, telefone, endereco)
  VALUES (p_nome, p_email, p_senha, p_telefone, p_endereco);
END $$


CREATE PROCEDURE Cadastrar_curso (
  IN p_id_instituicao INT,
  IN p_titulo VARCHAR(100),
  IN p_area VARCHAR(100),
  IN p_descricao TEXT,
  IN p_vagas INT,
  IN p_bolsa INT,
  IN p_link_externo VARCHAR(255),
  IN p_imagem VARCHAR(255)
)
BEGIN
  INSERT INTO cursos (id_instituicao, titulo, area, descricao, vagas, bolsa, link_externo, imagem)
  VALUES (p_id_instituicao, p_titulo, p_area, p_descricao, p_vagas, p_bolsa, p_link_externo, p_imagem);
END $$


CREATE PROCEDURE Cadastrar_bolsa (
  IN p_titulo VARCHAR(100),
  IN p_percentual DECIMAL(5,2),
  IN p_requisitos TEXT,
  IN p_vagas INT,
  IN p_validade DATE,
  IN p_id_curso INT
)
BEGIN
  INSERT INTO bolsas (titulo, percentual_desconto, requisitos, vagas_disponiveis, validade, id_curso)
  VALUES (p_titulo, p_percentual, p_requisitos, p_vagas, p_validade, p_id_curso);
END $$


CREATE PROCEDURE Registrar_inscricao (
  IN p_aluno_id INT,
  IN p_curso_id INT
)
BEGIN
  INSERT INTO inscricoes (aluno_id, curso_id, status)
  VALUES (p_aluno_id, p_curso_id, 'Inscrito');
  -- opcional: incrementar contador de acessos no curso
  UPDATE cursos SET acessos = IFNULL(acessos,0) + 1 WHERE id = p_curso_id;
END $$


CREATE PROCEDURE Favoritar_curso (
  IN p_aluno_id INT,
  IN p_curso_id INT
)
BEGIN
  -- evita duplicata: insere só quando não existir
  IF NOT EXISTS (SELECT 1 FROM favoritos WHERE aluno_id = p_aluno_id AND curso_id = p_curso_id) THEN
    INSERT INTO favoritos (aluno_id, curso_id) VALUES (p_aluno_id, p_curso_id);
  END IF;
END $$


CREATE PROCEDURE Remover_favorito (
  IN p_aluno_id INT,
  IN p_curso_id INT
)
BEGIN
  DELETE FROM favoritos WHERE aluno_id = p_aluno_id AND curso_id = p_curso_id;
END $$

-- =========================
-- 3) Admin: suspender / reativar usuários e instituições
-- =========================

CREATE PROCEDURE Suspender_usuario (
  IN p_usuario_id INT
)
BEGIN
  UPDATE usuarios SET ativo = 0, updated_at = NOW() WHERE id = p_usuario_id;
END $$

CREATE PROCEDURE Reativar_usuario (
  IN p_usuario_id INT
)
BEGIN
  UPDATE usuarios SET ativo = 1, updated_at = NOW() WHERE id = p_usuario_id;
END $$

CREATE PROCEDURE Suspender_instituicao (
  IN p_instituicao_id INT
)
BEGIN
  UPDATE instituicoes SET ativo = 0, updated_at = NOW() WHERE id = p_instituicao_id;
END $$

CREATE PROCEDURE Reativar_instituicao (
  IN p_instituicao_id INT
)
BEGIN
  UPDATE instituicoes SET ativo = 1, updated_at = NOW() WHERE id = p_instituicao_id;
END $$

-- =========================
-- 4) Atualizações simples
-- =========================

CREATE PROCEDURE Atualizar_dados_aluno (
  IN p_id INT,
  IN p_nome VARCHAR(100),
  IN p_email VARCHAR(100),
  IN p_telefone VARCHAR(20),
  IN p_area_interesse VARCHAR(100),
  IN p_endereco VARCHAR(255)
)
BEGIN
  UPDATE alunos
  SET nome = p_nome, email = p_email, telefone = p_telefone,
      area_interesse = p_area_interesse, endereco = p_endereco, updated_at = NOW()
  WHERE id = p_id;
END $$


CREATE PROCEDURE Atualizar_informacoes_instituicao (
  IN p_id INT,
  IN p_nome VARCHAR(100),
  IN p_email VARCHAR(100),
  IN p_telefone VARCHAR(20),
  IN p_endereco VARCHAR(255)
)
BEGIN
  UPDATE instituicoes
  SET nome = p_nome, email = p_email, telefone = p_telefone,
      endereco = p_endereco, updated_at = NOW()
  WHERE id = p_id;
END $$

-- =========================
-- 5) VIEW: Relatórios de inscrições
-- =========================

CREATE OR REPLACE VIEW view_inscricoes_por_curso AS
SELECT c.id AS curso_id, c.titulo, COUNT(i.id) AS total_inscricoes
FROM cursos c
LEFT JOIN inscricoes i ON i.curso_id = c.id
GROUP BY c.id, c.titulo;

DELIMITER ;
