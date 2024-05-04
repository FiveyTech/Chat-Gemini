-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 04/05/2024 às 03:06
-- Versão do servidor: 8.0.27-18
-- Versão do PHP: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `chatgpphp`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `accounts`
--

CREATE TABLE `accounts` (
  `id` int NOT NULL,
  `nome` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `senha` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nivel` varchar(300) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '3'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico`
--

CREATE TABLE `historico` (
  `id` int NOT NULL,
  `byid` int NOT NULL,
  `mainid` int DEFAULT NULL,
  `nome` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `historico` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(300) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `historico`
--
ALTER TABLE `historico`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `historico`
--
ALTER TABLE `historico`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
