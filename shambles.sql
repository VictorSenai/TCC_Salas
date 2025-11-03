-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3305
-- Tempo de geração: 03/11/2025 às 17:10
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
-- Banco de dados: `shambles`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `administrador`
--

CREATE TABLE `administrador` (
  `ID_ADMINISTRADOR` int(11) NOT NULL,
  `NOME_ADMINISTRADOR` varchar(100) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `SENHA` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `professor`
--

CREATE TABLE `professor` (
  `ID_PROFESSOR` int(11) NOT NULL,
  `NOME_PROFESSOR` varchar(100) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `SENHA` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `professor`
--

INSERT INTO `professor` (`ID_PROFESSOR`, `NOME_PROFESSOR`, `EMAIL`, `SENHA`) VALUES
(1, 'Aislan', 'aislan@gmail.com', ''),
(2, 'Aislan', 'aislan@gmail.com', '$2y$10$1mtPHA.o5.u8ARII/CqlQ.lXpTDjWQ.nX3WarNjjous9BCFy3dJja');

-- --------------------------------------------------------

--
-- Estrutura para tabela `sala`
--

CREATE TABLE `sala` (
  `ID_SALA` int(11) NOT NULL,
  `NOME_SALA` varchar(100) NOT NULL,
  `DESCRICAO` varchar(255) NOT NULL,
  `ID_TAREFA` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `sala`
--

INSERT INTO `sala` (`ID_SALA`, `NOME_SALA`, `DESCRICAO`, `ID_TAREFA`) VALUES
(1, 'Sala01', '', NULL),
(2, 'Sala02', '', NULL),
(3, 'Sala03', '', NULL),
(4, 'Sala04', '', NULL),
(5, 'Sala05', '', NULL),
(6, 'Sala06', '', NULL),
(7, 'Sala07', '', NULL),
(8, 'Sala08', '', NULL),
(9, 'Sala09', '', NULL),
(10, 'Sala10', '', NULL),
(11, 'Sala11', '', NULL),
(12, 'Sala12', '', NULL),
(13, 'Sala13', '', NULL),
(14, 'Sala14', '', NULL),
(15, 'Sala15', '', NULL),
(16, 'LAB_DEV_SISTEMAS', '', NULL),
(17, 'LAB_INFORMATICA_I', '', NULL),
(18, 'LAB_INFORMATICA_II', '', NULL),
(19, 'LAB_ACIONAMENTOS', '', NULL),
(20, 'LAB_AUTOMACAO', '', NULL),
(21, 'LAB_MAKER', '', NULL),
(22, 'LAB_PNEUMATICA', '', NULL),
(23, 'LAB_QUIMICA', '', NULL),
(24, 'LAB_QUIMICA', '', NULL),
(25, 'LAB_LOGISTICA', '', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `suporte`
--

CREATE TABLE `suporte` (
  `ID_SUPORTE` int(11) NOT NULL,
  `NOME_SUPORTE` varchar(100) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `SENHA` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tarefa`
--

CREATE TABLE `tarefa` (
  `ID_TAREFA` int(11) NOT NULL,
  `ID_SALA` int(11) DEFAULT NULL,
  `NOME_TAREFA` varchar(100) NOT NULL,
  `DESCRICAO` varchar(255) NOT NULL,
  `PRIORIDADE` varchar(50) NOT NULL,
  `STATUS` int(11) NOT NULL,
  `DATA_CRIACAO` datetime NOT NULL DEFAULT current_timestamp(),
  `NOME_SALA` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tarefa`
--

INSERT INTO `tarefa` (`ID_TAREFA`, `ID_SALA`, `NOME_TAREFA`, `DESCRICAO`, `PRIORIDADE`, `STATUS`, `DATA_CRIACAO`, `NOME_SALA`) VALUES
(16, 2, 'porobleas', '', 'Baixa', 0, '2025-11-01 09:10:57', 'Sala02');

-- --------------------------------------------------------

--
-- Estrutura para tabela `turma`
--

CREATE TABLE `turma` (
  `ID_TURMA` int(11) NOT NULL,
  `NOME_TURMA` varchar(100) NOT NULL,
  `DESCRICAO` varchar(255) NOT NULL,
  `ID_PROFESSOR` int(11) DEFAULT NULL,
  `ID_SUPORTE` int(11) DEFAULT NULL,
  `ID_ADMINISTRADOR` int(11) DEFAULT NULL,
  `ID_SALA` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`ID_ADMINISTRADOR`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`,`SENHA`);

--
-- Índices de tabela `professor`
--
ALTER TABLE `professor`
  ADD PRIMARY KEY (`ID_PROFESSOR`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`,`SENHA`);

--
-- Índices de tabela `sala`
--
ALTER TABLE `sala`
  ADD PRIMARY KEY (`ID_SALA`),
  ADD KEY `ID_TAREFA` (`ID_TAREFA`);

--
-- Índices de tabela `suporte`
--
ALTER TABLE `suporte`
  ADD PRIMARY KEY (`ID_SUPORTE`),
  ADD UNIQUE KEY `EMAIL` (`EMAIL`,`SENHA`);

--
-- Índices de tabela `tarefa`
--
ALTER TABLE `tarefa`
  ADD PRIMARY KEY (`ID_TAREFA`),
  ADD KEY `idx_tarefa_id_sala` (`ID_SALA`);

--
-- Índices de tabela `turma`
--
ALTER TABLE `turma`
  ADD PRIMARY KEY (`ID_TURMA`),
  ADD KEY `ID_PROFESSOR` (`ID_PROFESSOR`),
  ADD KEY `ID_SUPORTE` (`ID_SUPORTE`),
  ADD KEY `ID_ADMINISTRADOR` (`ID_ADMINISTRADOR`),
  ADD KEY `ID_SALA` (`ID_SALA`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `professor`
--
ALTER TABLE `professor`
  MODIFY `ID_PROFESSOR` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `sala`
--
ALTER TABLE `sala`
  MODIFY `ID_SALA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `tarefa`
--
ALTER TABLE `tarefa`
  MODIFY `ID_TAREFA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `sala`
--
ALTER TABLE `sala`
  ADD CONSTRAINT `sala_ibfk_1` FOREIGN KEY (`ID_TAREFA`) REFERENCES `tarefa` (`ID_TAREFA`);

--
-- Restrições para tabelas `tarefa`
--
ALTER TABLE `tarefa`
  ADD CONSTRAINT `fk_tarefa_sala` FOREIGN KEY (`ID_SALA`) REFERENCES `sala` (`ID_SALA`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `turma`
--
ALTER TABLE `turma`
  ADD CONSTRAINT `turma_ibfk_1` FOREIGN KEY (`ID_PROFESSOR`) REFERENCES `professor` (`ID_PROFESSOR`),
  ADD CONSTRAINT `turma_ibfk_2` FOREIGN KEY (`ID_SUPORTE`) REFERENCES `suporte` (`ID_SUPORTE`),
  ADD CONSTRAINT `turma_ibfk_3` FOREIGN KEY (`ID_ADMINISTRADOR`) REFERENCES `administrador` (`ID_ADMINISTRADOR`),
  ADD CONSTRAINT `turma_ibfk_4` FOREIGN KEY (`ID_SALA`) REFERENCES `sala` (`ID_SALA`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
