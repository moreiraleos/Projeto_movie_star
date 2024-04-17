-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 27-Fev-2023 às 02:31
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `movie_star`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `movies`
--

CREATE TABLE `movies` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `trailer` varchar(150) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `length` varchar(50) DEFAULT NULL,
  `users_id` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `movies`
--

INSERT INTO `movies` (`id`, `title`, `description`, `image`, `trailer`, `category`, `length`, `users_id`) VALUES
(1, 'Hercules', 'Filme não convence no terceiro ato', 'bf5e81e08bc6bde6cfeb8fdd3596876d3e99e45857f8bf9a44decbf079195a91551084e6ea469114c05b89674c143866a586742c337edd42b3ff507a.jpg', 'https://www.youtube.com/embed/OwlynHlZEc4', 'Ação', '2h40m', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) UNSIGNED NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `users_id` int(11) UNSIGNED DEFAULT NULL,
  `movies_id` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `token` varchar(200) DEFAULT NULL,
  `bio` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`id`, `name`, `lastname`, `email`, `password`, `image`, `token`, `bio`) VALUES
(1, 'Leonardo', 'Moreira', 'leo.mor@hotmail.com', '$2y$10$1/L4LyhsINovS9QFnUU70.l7sRKosEwT4KXDvL/.cVTfNBZnwUCDa', '5df86570feccff9bc4171f57bac4d5131e25a44d7ac20637712978f92bdef155da51f60a22f52b53c3cf0c906a19c87d2bbbe4fe8fed14fcc3ea271a.jpg', 'd16356cef07d3dbfc713d100bd9512b7955586d2d9fec8e16051ed462fc37c940b5b36baf8063c9b8a45a818c5ba5512b582', 'Sou programador e gosto de ler. Adoro filmes'),
(2, 'dfdfdf', 'Moreira', 'gisahiphop@hotmail.com', '$2y$10$kChwAdzyLlwnzhii9dAMZ.KCQtv/EE4gDsIV0dYDST0Qa/3hNOkm6', NULL, '6ef100a6615c98e3fd00934936930d8ec5df6ad46cb3751b048bff88ac8d5263c77aabfc5a39688cf8a59e4044b1fc9ca84c', NULL),
(5, 'Leonardo', 'Moreira', 'leo.mor2@hotmail.com', '$2y$10$2YuqzdE4.NTp2iKVa9wr4.IfppoCPf8SdeFk1XmIZ6udnhecgp7p.', NULL, '3bd4c0323751f908eecf54fe98d4b5d6f37c752465d8680816e0eb7bd0ac72b42e5efdb7128877f5092abd9b64fa9b7bf92d', NULL),
(6, 'dfdfdf', 'Moreira', 'informatica@hidroluz.com.br', '$2y$10$Uecv6f2TXO30/Ka7pOT0yeogLUJa.JLhu5KysEwQgzZtkcBE0eqjq', NULL, '08cd077631d5b7fe2d8936704e0958d73cc6150d7daea5e3ebddc4d236b59848f26b7f45ded2fe2093c4c1b6a4e937223415', NULL),
(7, 'Gisele', 'Moreira', 'santos.limagisele@gmail.com', '$2y$10$sObLvJSdDEkhuSvQdk1vVO6J5FOug/Us9Slyqnxuq6tTKvbol.kwu', NULL, 'a4e462919f0c5f84f93487139516565c917bb6043d9476fa5400678255667d8f5fac482f47ad2322a11e980de467cffe3498', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`);

--
-- Índices para tabela `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`),
  ADD KEY `movies_id` (`movies_id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `movies_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Limitadores para a tabela `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`movies_id`) REFERENCES `movies` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
