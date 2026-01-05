<?php /*
DROP TABLE IF EXISTS  `fotos`, `fotos_album`, `login`,  `paginas`, `slide`, `video`, `videos`, `usuarios`;
DROP TABLE IF EXISTS `album`, `categoria_fotos`, `menu`,`categoria_videos`; 

CREATE TABLE `album` (
  `id` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `nome` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ocultar` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria_fotos`
--

CREATE TABLE `categoria_fotos` (
  `id` int(11) NOT NULL,
  `imagem` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_cadastro` date NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria_videos`
--

CREATE TABLE `categoria_videos` (
  `id` int(11) NOT NULL,
  `imagem` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_cadastro` date NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fotos`
--

CREATE TABLE `fotos` (
  `id` int(11) NOT NULL,
  `id_categoria_fotos` int(11) NOT NULL,
  `foto` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fotos_album`
--

CREATE TABLE `fotos_album` (
  `id` int(11) NOT NULL,
  `id_album` int(11) NOT NULL,
  `nome` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ocultar` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `id_usuarios` int(11) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time DEFAULT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `menu`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `id_menu` int(11) ,
  `nome` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ocultar` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE menus ADD FOREIGN KEY (id_menu) REFERENCES menus (id);
-- --------------------------------------------------------

--
-- Estrutura para tabela `paginas`
--

CREATE TABLE `paginas` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conteudo` blob DEFAULT NULL,
  `data_inicio` datetime NOT NULL,
  `data_fim` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `slide`
--

CREATE TABLE `slide` (
  `id` int(11) NOT NULL,
  `imagem` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL primary key AUTO_INCREMENT,
  `code` blob ,
  `code_time` DATETIME,
  `tentativas` int(11)  NOT NULL DEFAULT '0',
  `Nome` varchar(50) NOT NULL,
  `e_mail` blob NOT NULL,
  `login` blob NOT NULL,
  `senha` blob NOT NULL
) ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `video`
--

CREATE TABLE `video` (
  `id` int(11) NOT NULL,
  `titulo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `video` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `videos`
--

CREATE TABLE `videos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `video` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_albums_menus` (`id_menu`);

--
-- Índices de tabela `categoria_fotos`
--
ALTER TABLE `categoria_fotos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `categoria_videos`
--
ALTER TABLE `categoria_videos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `fotos`
--
ALTER TABLE `fotos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_fotos_categoria_fotos` (`id_categoria_fotos`);

--
-- Índices de tabela `fotos_album`
--
ALTER TABLE `fotos_album`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_fotos_album_albums` (`id_album`);

--
-- Índices de tabela `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_login_usuarios` (`id_usuarios`);

--
-- Índices de tabela `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `paginas`
--
ALTER TABLE `paginas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `slide`
--
ALTER TABLE `slide`
  ADD PRIMARY KEY (`id`);



--
-- Índices de tabela `video`
--
ALTER TABLE `video`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `album`
--
ALTER TABLE `album`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `categoria_fotos`
--
ALTER TABLE `categoria_fotos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `categoria_videos`
--
ALTER TABLE `categoria_videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fotos`
--
ALTER TABLE `fotos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fotos_album`
--
ALTER TABLE `fotos_album`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `paginas`
--
ALTER TABLE `paginas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `slide`
--
ALTER TABLE `slide`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuario`


--
-- AUTO_INCREMENT de tabela `video`
--
ALTER TABLE `video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `videos`
--
ALTER TABLE `videos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `album`
--
ALTER TABLE `album`
  ADD CONSTRAINT `fk_albums_menus` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id`);

--
-- Restrições para tabelas `fotos`
--
ALTER TABLE `fotos`
  ADD CONSTRAINT `fk_fotos_categoria_fotos` FOREIGN KEY (`id_categoria_fotos`) REFERENCES `categoria_fotos` (`id`);

--
-- Restrições para tabelas `fotos_album`
--
ALTER TABLE `fotos_album`
  ADD CONSTRAINT `fk_fotos_album_albums` FOREIGN KEY (`id_album`) REFERENCES `album` (`id`);

--
-- Restrições para tabelas `login`
--
ALTER TABLE `login`
  ADD CONSTRAINT `fk_login_usuarios` FOREIGN KEY (`id_usuarios`) REFERENCES `usuarios` (`id`);
COMMIT;

INSERT INTO `usuarios` (`id`,`nome`, `login`, `senha`) VALUES (NULL,'Jair Tem Pass', 'jair', 'Qfwlrp6*JAqa5p!*6');
delete  from `usuarios` ;
INSERT INTO `usuarios` (`id`,`nome`, `login`, `senha`) VALUES (NULL,'Jair Tem Pass', 'a8cf61800bc0a9fde3cd2acd10ad88ab206a947966009234c3335bfd0889b98bc8029be2655436d32e8e4507d757ae66271e6a49d414b86a7d4872a24fd1819f', '66f38ab9a0a78271255fc9ff16c1bfc7d804625fb0174c3438cdad10aedb66b69f3de48104c7ecdf23aeca675db874ab41a13a1f82980c1e2201ce38ea9084c6');



*/?>