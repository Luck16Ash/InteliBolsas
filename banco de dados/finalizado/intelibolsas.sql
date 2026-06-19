-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27/10/2025 às 17:53
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `intelibolsas`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `Atualizar_dados_aluno` (IN `p_id` INT, IN `p_nome` VARCHAR(100), IN `p_email` VARCHAR(100), IN `p_telefone` VARCHAR(20), IN `p_area_interesse` VARCHAR(100), IN `p_endereco` VARCHAR(255))   BEGIN
  UPDATE alunos
  SET nome = p_nome, email = p_email, telefone = p_telefone,
      area_interesse = p_area_interesse, endereco = p_endereco, updated_at = NOW()
  WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Atualizar_informacoes_instituicao` (IN `p_id` INT, IN `p_nome` VARCHAR(100), IN `p_email` VARCHAR(100), IN `p_telefone` VARCHAR(20), IN `p_endereco` VARCHAR(255))   BEGIN
  UPDATE instituicoes
  SET nome = p_nome, email = p_email, telefone = p_telefone,
      endereco = p_endereco, updated_at = NOW()
  WHERE id = p_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Cadastrar_bolsa` (IN `p_titulo` VARCHAR(100), IN `p_percentual` DECIMAL(5,2), IN `p_requisitos` TEXT, IN `p_vagas` INT, IN `p_validade` DATE, IN `p_id_curso` INT)   BEGIN
  INSERT INTO bolsas (titulo, percentual_desconto, requisitos, vagas_disponiveis, validade, id_curso)
  VALUES (p_titulo, p_percentual, p_requisitos, p_vagas, p_validade, p_id_curso);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Cadastrar_curso` (IN `p_id_instituicao` INT, IN `p_titulo` VARCHAR(100), IN `p_area` VARCHAR(100), IN `p_descricao` TEXT, IN `p_vagas` INT, IN `p_bolsa` INT, IN `p_link_externo` VARCHAR(255), IN `p_imagem` VARCHAR(255))   BEGIN
  INSERT INTO cursos (id_instituicao, titulo, area, descricao, vagas, bolsa, link_externo, imagem)
  VALUES (p_id_instituicao, p_titulo, p_area, p_descricao, p_vagas, p_bolsa, p_link_externo, p_imagem);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Cadastro_aluno` (IN `p_nome` VARCHAR(100), IN `p_email` VARCHAR(100), IN `p_usuario` VARCHAR(50), IN `p_senha_hash` VARCHAR(255), IN `p_telefone` VARCHAR(20), IN `p_area_interesse` VARCHAR(100))   BEGIN
  DECLARE v_novo_id_usuario INT;

  -- 1. Insere na tabela central de autenticação
  INSERT INTO usuarios (usuario, senha, tipo)
  VALUES (p_usuario, p_senha_hash, 'aluno');

  -- Pega o ID gerado (LAST_INSERT_ID)
  SET v_novo_id_usuario = LAST_INSERT_ID();

  -- 2. Insere na tabela de perfil, usando o ID_USUARIO
  INSERT INTO alunos (id_usuario, nome, email, telefone, area_interesse)
  VALUES (v_novo_id_usuario, p_nome, p_email, p_telefone, p_area_interesse);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Cadastro_instituicao` (IN `p_nome` VARCHAR(100), IN `p_email` VARCHAR(100), IN `p_usuario` VARCHAR(50), IN `p_senha_hash` VARCHAR(255), IN `p_telefone` VARCHAR(20), IN `p_endereco` VARCHAR(255))   BEGIN
  DECLARE v_novo_id_usuario INT;

  -- 1. Insere na tabela central de autenticação
  INSERT INTO usuarios (usuario, senha, tipo)
  VALUES (p_usuario, p_senha_hash, 'instituicao');

  -- Pega o ID gerado (LAST_INSERT_ID)
  SET v_novo_id_usuario = LAST_INSERT_ID();

  -- 2. Insere na tabela de perfil, usando o ID_USUARIO
  INSERT INTO instituicoes (id_usuario, nome, email, telefone, endereco)
  VALUES (v_novo_id_usuario, p_nome, p_email, p_telefone, p_endereco);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Favoritar_curso` (IN `p_aluno_id` INT, IN `p_curso_id` INT)   BEGIN
  -- evita duplicata: insere só quando não existir
  IF NOT EXISTS (SELECT 1 FROM favoritos WHERE aluno_id = p_aluno_id AND curso_id = p_curso_id) THEN
    INSERT INTO favoritos (aluno_id, curso_id) VALUES (p_aluno_id, p_curso_id);
  END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Reativar_instituicao` (IN `p_instituicao_id` INT)   BEGIN
  UPDATE instituicoes SET ativo = 1, updated_at = NOW() WHERE id = p_instituicao_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Reativar_usuario` (IN `p_usuario_id` INT)   BEGIN
  UPDATE usuarios SET ativo = 1, updated_at = NOW() WHERE id = p_usuario_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Registrar_inscricao` (IN `p_aluno_id` INT, IN `p_curso_id` INT)   BEGIN
  INSERT INTO inscricoes (aluno_id, curso_id, status)
  VALUES (p_aluno_id, p_curso_id, 'Inscrito');
  -- opcional: incrementar contador de acessos no curso
  UPDATE cursos SET acessos = IFNULL(acessos,0) + 1 WHERE id = p_curso_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Remover_favorito` (IN `p_aluno_id` INT, IN `p_curso_id` INT)   BEGIN
  DELETE FROM favoritos WHERE aluno_id = p_aluno_id AND curso_id = p_curso_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Suspender_instituicao` (IN `p_instituicao_id` INT)   BEGIN
  UPDATE instituicoes SET ativo = 0, updated_at = NOW() WHERE id = p_instituicao_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Suspender_usuario` (IN `p_usuario_id` INT)   BEGIN
  UPDATE usuarios SET ativo = 0, updated_at = NOW() WHERE id = p_usuario_id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `administradores`
--

CREATE TABLE `administradores` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `area_manutencao` varchar(100) DEFAULT NULL,
  `nivel_acesso` enum('baixo','medio','alto') DEFAULT 'alto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `alunos`
--

CREATE TABLE `alunos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `area_interesse` varchar(100) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `bolsas`
--

CREATE TABLE `bolsas` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `percentual_desconto` decimal(5,2) DEFAULT NULL,
  `requisitos` text DEFAULT NULL,
  `vagas_disponiveis` int(11) DEFAULT 0,
  `validade` date DEFAULT NULL,
  `id_curso` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cursos`
--

CREATE TABLE `cursos` (
  `id` int(11) NOT NULL,
  `id_instituicao` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `area` varchar(100) DEFAULT NULL,
  `instituicao_origem` varchar(100) DEFAULT NULL,
  `link_externo` varchar(255) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `vagas` int(11) DEFAULT 0,
  `bolsa` int(11) DEFAULT 0,
  `data_criacao` datetime DEFAULT current_timestamp(),
  `imagem` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `favoritos`
--

CREATE TABLE `favoritos` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `inscricoes`
--

CREATE TABLE `inscricoes` (
  `id` int(11) NOT NULL,
  `aluno_id` int(11) NOT NULL,
  `curso_id` int(11) NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Inscrito'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `instituicoes`
--

CREATE TABLE `instituicoes` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs_acesso`
--

CREATE TABLE `logs_acesso` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `acao` varchar(100) DEFAULT NULL,
  `data` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `logs_acesso`
--

INSERT INTO `logs_acesso` (`id`, `usuario`, `tipo`, `acao`, `data`) VALUES
(1, 'admin', 'admin', 'Novo usuário registrado', '2025-10-26 17:26:43');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('aluno','instituicao','admin') NOT NULL DEFAULT 'aluno',
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `senha`, `tipo`, `ativo`, `updated_at`) VALUES
(1, 'admin', '$2y$10$.HnzErlEzc7HgZynXRnG3OLjdiinh4hjLDVSciH9UgTU6t3Q1r5je', 'admin', 1, NULL);

--
-- Acionadores `usuarios`
--
DELIMITER $$
CREATE TRIGGER `trg_login` AFTER INSERT ON `usuarios` FOR EACH ROW BEGIN
  INSERT INTO logs_acesso(usuario, tipo, acao)
  VALUES (NEW.usuario, NEW.tipo, 'Novo usuário registrado');
END
$$
DELIMITER ;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `alunos`
--
ALTER TABLE `alunos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `bolsas`
--
ALTER TABLE `bolsas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Índices de tabela `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_instituicao` (`id_instituicao`);

--
-- Índices de tabela `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aluno_id` (`aluno_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Índices de tabela `inscricoes`
--
ALTER TABLE `inscricoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aluno_id` (`aluno_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Índices de tabela `instituicoes`
--
ALTER TABLE `instituicoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `logs_acesso`
--
ALTER TABLE `logs_acesso`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `alunos`
--
ALTER TABLE `alunos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `bolsas`
--
ALTER TABLE `bolsas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `inscricoes`
--
ALTER TABLE `inscricoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `instituicoes`
--
ALTER TABLE `instituicoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `logs_acesso`
--
ALTER TABLE `logs_acesso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `alunos`
--
ALTER TABLE `alunos`
  ADD CONSTRAINT `alunos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `bolsas`
--
ALTER TABLE `bolsas`
  ADD CONSTRAINT `bolsas_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `cursos`
--
ALTER TABLE `cursos`
  ADD CONSTRAINT `cursos_ibfk_1` FOREIGN KEY (`id_instituicao`) REFERENCES `instituicoes` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `alunos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `inscricoes`
--
ALTER TABLE `inscricoes`
  ADD CONSTRAINT `inscricoes_ibfk_1` FOREIGN KEY (`aluno_id`) REFERENCES `alunos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscricoes_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `instituicoes`
--
ALTER TABLE `instituicoes`
  ADD CONSTRAINT `instituicoes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
