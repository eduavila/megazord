SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "-04:00";

--
-- Estrutura da tabela `basicas`
--

CREATE TABLE IF NOT EXISTS `basicas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tabela` varchar(30) CHARACTER SET latin1 NOT NULL,
  `valor` varchar(254) CHARACTER SET latin1 NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `log_sessao`
--

CREATE TABLE IF NOT EXISTS `log_sessao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session` varchar(32) NOT NULL,
  `browser` varchar(254) NOT NULL,
  `data` datetime NOT NULL,
  `user` int(11) NOT NULL,
  `remote_ip` varchar(15) NOT NULL,
  `data_encer` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `perfil` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `log_sistema`
--

CREATE TABLE IF NOT EXISTS `log_sistema` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` datetime DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `acao` varchar(255) DEFAULT NULL,
  `usuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) DEFAULT NULL,
  `modulo` varchar(50) DEFAULT NULL,
  `pag` varchar(50) DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `define` varchar(50) DEFAULT NULL,
  `target` varchar(30) DEFAULT NULL,
  `ordem` int(11) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `parametro`
--

CREATE TABLE IF NOT EXISTS `parametro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `campo` varchar(40) NOT NULL,
  `valor` text NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Extraindo dados da tabela `parametro`
--

INSERT INTO `parametro` (`id`, `campo`, `valor`, `status`) VALUES
(1, 'admFrontTitle', 'Gestão de Pessoas', 1),
(5, 'admDescription', 'Departamento de Tecnologia da Informação', 1),
(6, 'miniTitleSite', 'GP', 1),
(7, 'siteTitle', 'Gestão de Pessoal', 1),
(8, 'siteIcon', 'images/favicon2.ico', 1),
(9, 'siteLogoFooter', 'images/brasao_white.png', 0),
(10, 'siteCopyright', 'Copyright © 2016 Todos os Direitos Reservados | Departamento de Tecnologia da Informação', 1),
(11, 'siteBannerPrincipal', 'images/pref.jpg', 1),
(12, 'siteLogo', 'images/brasao.png', 0),
(13, 'siteInfoFooterHorario', 'Horário de Atendimento 09h às 11h | 13h às 15h - segunda a sexta feira', 1),
(14, 'siteInfoFooterEndereco', 'Av. América do Sul, 2500-S - Parque dos Buritis - (65) 3549-8300', 1),
(15, 'siteLogo', 'images/logo_gestao2.png', 1),
(16, 'siteLogoFooter', 'images/logo_gestao_footer2.png', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `perfil`
--

CREATE TABLE IF NOT EXISTS `perfil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `perfil`
--

INSERT INTO `perfil` (`id`, `nome`) VALUES
(1, 'Administrador');

-- --------------------------------------------------------

--
-- Estrutura da tabela `perfil_menu`
--

CREATE TABLE IF NOT EXISTS `perfil_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_perfil` int(11) DEFAULT NULL,
  `id_menu` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `status`
--

CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `codigo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `status`
--

INSERT INTO `status` (`id`, `nome`, `codigo`) VALUES
(1, '<span class=''glyphicon glyphicon-stop'' style=''color:green; font-size:20px;'' ></span>', 1),
(2, '<span class=''glyphicon glyphicon-stop'' style=''color:red; font-size:20px;'' ></span>', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) NOT NULL,
  `login` varchar(30) NOT NULL,
  `senha` varchar(32) NOT NULL,
  `perfil` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `alt_senha` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=56 ;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `login`, `senha`, `perfil`, `status`, `alt_senha`) VALUES
(1, 'Administrador', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 0, 0),
(2, 'Natan Bassani', 'natan.bassani', '643fcab89ad0cf85f8d72e5e43db5c15', 1, 1, 0),
(3, 'Giovane Spengler', 'giovanesp', 'e85d935cd0061377cc868cbcc041a5bf', 1, 1, 0),
(4, 'Lucian Schirmer', 'lucian.schirmer', '84714b0575c98295607ff0be68f384cc', 1, 1, 0);
