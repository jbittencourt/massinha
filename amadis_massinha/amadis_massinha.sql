-- phpMyAdmin SQL Dump
-- version 2.6.0-rc2
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost
-- Tempo de Generação: Jul 16, 2006 at 01:38 AM
-- Versão do Servidor: 5.0.22
-- Versão do PHP: 5.1.2

SET AUTOCOMMIT=0;
START TRANSACTION;

-- 
-- Banco de Dados: `ecsic_amadis2004`
-- 

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Ambiente_Sessoes`
-- 

CREATE TABLE `Ambiente_Sessoes` (
  `codSessionId` varchar(50) NOT NULL default '',
  `codUser` mediumint(9) NOT NULL default '0',
  `codPlataforma` tinyint(4) NOT NULL default '0',
  `datInicio` bigint(9) NOT NULL default '0',
  `datFim` bigint(9) NOT NULL default '0',
  `desIP` varchar(15) NOT NULL default '',
  `flaVisibilidade` tinyint(4) NOT NULL default '0',
  `flaEncerrada` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`codSessionId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Finder_Chat`
-- 

CREATE TABLE `Finder_Chat` (
  `codFinderChat` bigint(9) NOT NULL auto_increment,
  `codIniciador` mediumint(9) NOT NULL default '0',
  `codRequisitado` mediumint(9) NOT NULL default '0',
  `datInicio` bigint(9) NOT NULL default '0',
  `datFim` bigint(9) default NULL,
  PRIMARY KEY  (`codFinderChat`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Finder_Mensagens`
-- 

CREATE TABLE `Finder_Mensagens` (
  `codMensagem` bigint(20) NOT NULL auto_increment,
  `codFinderChat` mediumint(9) NOT NULL default '0',
  `codRemetente` int(11) NOT NULL default '0',
  `codDestinatario` int(11) NOT NULL default '0',
  `tempo` int(11) NOT NULL default '0',
  `desMensagem` text NOT NULL,
  `flaLida` char(1) NOT NULL default '0',
  PRIMARY KEY  (`codMensagem`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Forum`
-- 

CREATE TABLE `Forum` (
  `codForum` int(11) NOT NULL auto_increment,
  `nomForum` varchar(60) NOT NULL default '',
  `tipoPai` char(1) NOT NULL default '',
  `codPai` int(11) NOT NULL default '0',
  `flaAllowView` char(1) NOT NULL default '0',
  `flaAllowPost` char(1) NOT NULL default '0',
  `tempo` int(11) NOT NULL default '0',
  KEY `Codigo` (`codForum`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `Mensagem`
-- 

CREATE TABLE `Mensagem` (
  `codMensagem` bigint(20) NOT NULL auto_increment,
  `codForum` int(11) NOT NULL default '0',
  `codAutor` bigint(20) NOT NULL default '0',
  `strTitulo` tinytext NOT NULL,
  `desCorpo` text NOT NULL,
  `codMensagemPai` bigint(5) NOT NULL default '0',
  `tempo` int(11) NOT NULL default '0',
  `relacao` tinytext NOT NULL,
  PRIMARY KEY  (`codMensagem`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `admin`
-- 

CREATE TABLE `admin` (
  `codUser` bigint(20) NOT NULL default '0',
  `codEscola` bigint(20) NOT NULL default '0',
  `can_manage_turmas` char(1) NOT NULL default '',
  `can_manage_users` char(1) NOT NULL default '',
  `can_manage_interactions` char(1) NOT NULL default '',
  PRIMARY KEY  (`codUser`,`codEscola`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `anuncios`
-- 

CREATE TABLE `anuncios` (
  `codAnuncio` int(11) NOT NULL auto_increment,
  `codProjeto` int(11) NOT NULL default '0',
  `desTituloAnuncio` varchar(100) NOT NULL default '',
  `desAnuncio` text NOT NULL,
  `tempo` int(11) NOT NULL default '0',
  KEY `codAnuncio` (`codAnuncio`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `areas`
-- 

CREATE TABLE `areas` (
  `codArea` tinyint(4) NOT NULL auto_increment,
  `nomArea` varchar(50) NOT NULL default '',
  `codPai` tinyint(4) NOT NULL default '0',
  `intGeracao` char(1) NOT NULL default '1',
  KEY `codArea` (`codArea`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `arquivos`
-- 

CREATE TABLE `arquivos` (
  `codArquivo` int(11) NOT NULL auto_increment,
  `desDados` longblob NOT NULL,
  `desTipoMime` varchar(20) NOT NULL default '',
  `desTamanho` int(11) NOT NULL default '0',
  `desNome` varchar(30) NOT NULL default '',
  `tempo` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codArquivo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `biblioteca_doc`
-- 

CREATE TABLE `biblioteca_doc` (
  `codDoc` bigint(20) NOT NULL auto_increment,
  `desTitulo` varchar(100) NOT NULL default '',
  `desTipoMime` varchar(50) NOT NULL default '',
  `codUser` mediumint(9) NOT NULL default '0',
  `codArquivo` bigint(20) NOT NULL default '0',
  `codOficina` int(11) NOT NULL default '0',
  `flaRestrito` char(1) NOT NULL default '',
  `flaAceito` char(1) NOT NULL default '0',
  `tempo` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`codDoc`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `categoria`
-- 

CREATE TABLE `categoria` (
  `codCategoria` int(11) NOT NULL auto_increment,
  `nomCategoria` varchar(30) NOT NULL default '',
  `flaPublica` char(1) NOT NULL default '',
  PRIMARY KEY  (`codCategoria`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `chat_mensagens`
-- 

CREATE TABLE `chat_mensagens` (
  `codMensagem` bigint(20) NOT NULL auto_increment,
  `codSalaChat` mediumint(9) NOT NULL default '0',
  `codRemetente` int(11) NOT NULL default '0',
  `codDestinatario` int(11) NOT NULL default '0',
  `tempo` int(11) NOT NULL default '0',
  `desMensagem` text NOT NULL,
  `desTag` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`codMensagem`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `chat_sala`
-- 

CREATE TABLE `chat_sala` (
  `codSala` bigint(20) NOT NULL auto_increment,
  `nomSala` varchar(30) NOT NULL default '',
  `desSala` varchar(60) NOT NULL default '',
  `tipoPai` char(1) NOT NULL default '',
  `codPai` int(11) NOT NULL default '0',
  `codPlataforma` tinyint(4) NOT NULL default '0',
  `flaPermanente` char(1) NOT NULL default '',
  `datInicio` bigint(20) NOT NULL default '0',
  `datFim` bigint(20) NOT NULL default '0',
  `tempo` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codSala`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `chat_sala_conectados`
-- 

CREATE TABLE `chat_sala_conectados` (
  `codConexao` bigint(20) NOT NULL auto_increment,
  `codSala` int(11) NOT NULL default '0',
  `codUser` int(11) NOT NULL default '0',
  `datEntrou` bigint(20) NOT NULL default '0',
  `datSaiu` bigint(20) NOT NULL default '0',
  `flaOnline` char(1) NOT NULL default '0',
  PRIMARY KEY  (`codConexao`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `ciclo`
-- 

CREATE TABLE `ciclo` (
  `codCiclo` tinyint(4) NOT NULL auto_increment,
  `nomCiclo` varchar(8) NOT NULL default '',
  PRIMARY KEY  (`codCiclo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `cidade`
-- 

CREATE TABLE `cidade` (
  `codCidade` int(11) NOT NULL auto_increment,
  `nomCidade` varchar(100) NOT NULL default '',
  `codEstado` int(11) NOT NULL default '0',
  `tempo` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codCidade`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `classetabela`
-- 

CREATE TABLE `classetabela` (
  `nomeClasse` varchar(50) NOT NULL default '',
  `nomeTabela` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`nomeTabela`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `comentarios`
-- 

CREATE TABLE `comentarios` (
  `codComentario` smallint(6) NOT NULL auto_increment,
  `codProjeto` smallint(6) NOT NULL default '0',
  `desNome` varchar(50) NOT NULL default '',
  `codUser` int(11) default NULL,
  `desComentario` text NOT NULL,
  `tempo` int(11) NOT NULL default '0',
  KEY `codComentario` (`codComentario`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `compromisso`
-- 

CREATE TABLE `compromisso` (
  `codCompromisso` int(11) NOT NULL auto_increment,
  `codUser` int(11) NOT NULL default '0',
  `nomCompromisso` varchar(40) NOT NULL default '',
  `desCompromisso` tinytext,
  `codProjeto` int(11) default '0',
  `codOficina` int(11) default '0',
  `timeDATA` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codCompromisso`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `config`
-- 

CREATE TABLE `config` (
  `codPlataforma` int(11) NOT NULL default '0',
  `desGrupo` varchar(20) NOT NULL default '',
  `desCampo` varchar(100) NOT NULL default '',
  `desValor` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`desCampo`,`desGrupo`,`codPlataforma`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `contatos`
-- 

CREATE TABLE `contatos` (
  `codContato` int(11) NOT NULL auto_increment,
  `codOwner` int(11) NOT NULL default '0',
  `codUser` int(11) NOT NULL default '0',
  `nomPessoa` varchar(60) NOT NULL default '',
  `strEmail` varchar(100) NOT NULL default '',
  `tempo` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codContato`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `diario`
-- 

CREATE TABLE `diario` (
  `codTexto` int(11) NOT NULL auto_increment,
  `tipoPai` char(1) NOT NULL default '',
  `codPai` int(11) NOT NULL default '0',
  `desTexto` text NOT NULL,
  `tempo` int(11) NOT NULL default '0',
  KEY `codTexto` (`codTexto`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `diarioComent`
-- 

CREATE TABLE `diarioComent` (
  `codComent` int(11) NOT NULL auto_increment,
  `codTexto` int(11) NOT NULL default '0',
  `codUser` int(11) NOT NULL default '0',
  `desTexto` text NOT NULL,
  `tempo` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codComent`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `email_mensagens`
-- 

CREATE TABLE `email_mensagens` (
  `codMensagem` int(11) NOT NULL auto_increment,
  `codUser` int(11) NOT NULL default '0',
  `nomPessoaEnviou` varchar(60) NOT NULL default '',
  `assunto` varchar(100) NOT NULL default '',
  `mensagem` mediumtext NOT NULL,
  `tempo` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codMensagem`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `email_users_destino`
-- 

CREATE TABLE `email_users_destino` (
  `codMensagem` int(11) NOT NULL default '0',
  `codUserDestino` int(11) NOT NULL default '0',
  `flaCopia` char(1) NOT NULL default '0',
  `flaLida` char(1) NOT NULL default '0',
  PRIMARY KEY  (`codMensagem`,`codUserDestino`,`flaCopia`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `escola`
-- 

CREATE TABLE `escola` (
  `codEscola` int(11) NOT NULL auto_increment,
  `nomEscola` varchar(60) NOT NULL default '',
  `codCidade` int(11) NOT NULL default '0',
  `desEndereco` varchar(150) NOT NULL default '',
  `desBairro` varchar(80) NOT NULL default '',
  `desTelefone` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`codEscola`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `estado`
-- 

CREATE TABLE `estado` (
  `codEstado` int(11) NOT NULL auto_increment,
  `nomEstado` varchar(20) NOT NULL default '',
  `desPais` varchar(20) NOT NULL default '',
  `desSigla` char(3) NOT NULL default '',
  PRIMARY KEY  (`codEstado`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `forumImagem`
-- 

CREATE TABLE `forumImagem` (
  `codMensagem` int(11) NOT NULL default '0',
  `codArquivo` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codArquivo`,`codMensagem`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `mensagensLidas`
-- 

CREATE TABLE `mensagensLidas` (
  `codUser` int(11) NOT NULL default '0',
  `codMensagem` int(11) NOT NULL default '0',
  `flaLida` char(1) NOT NULL default '0',
  PRIMARY KEY  (`codMensagem`,`codUser`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `noticias`
-- 

CREATE TABLE `noticias` (
  `codNoticia` bigint(20) NOT NULL auto_increment,
  `codUser` int(11) NOT NULL default '0',
  `flaLida` bigint(20) NOT NULL default '0',
  `desNoticia` tinytext NOT NULL,
  `tempo` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codNoticia`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `novidades`
-- 

CREATE TABLE `novidades` (
  `codNovidade` int(4) NOT NULL auto_increment,
  `codProjeto` int(4) NOT NULL default '0',
  `desNovidade` text NOT NULL,
  `tempo` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codNovidade`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `oficina`
-- 

CREATE TABLE `oficina` (
  `codOficina` int(11) NOT NULL auto_increment,
  `nomOficina` varchar(59) NOT NULL default '',
  `flaInscrAutomatica` char(1) NOT NULL default '0',
  `desOficina` text NOT NULL,
  `datInicio` bigint(20) NOT NULL default '0',
  `datFim` bigint(20) NOT NULL default '0',
  `datInscrInicio` bigint(20) NOT NULL default '0',
  `datInscrFim` bigint(20) NOT NULL default '0',
  `flaSeminario` char(1) NOT NULL default '0',
  `tempo` int(11) NOT NULL default '0',
  KEY `codOficina` (`codOficina`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `oficinaCoordenador`
-- 

CREATE TABLE `oficinaCoordenador` (
  `codOficina` int(11) NOT NULL default '0',
  `codUser` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codOficina`,`codUser`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `oficinaMatricula`
-- 

CREATE TABLE `oficinaMatricula` (
  `codOficina` int(11) NOT NULL default '0',
  `codUser` int(11) NOT NULL default '0',
  `flaAutorizado` char(1) NOT NULL default '0',
  `tempo` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codOficina`,`codUser`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `plataforma`
-- 

CREATE TABLE `plataforma` (
  `codPlataforma` int(11) NOT NULL auto_increment,
  `strIDPlataforma` varchar(20) NOT NULL default '',
  `flaMaster` char(1) NOT NULL default '1',
  `codMaster` int(11) NOT NULL default '0',
  `descrPlataforma` varchar(100) NOT NULL default '',
  `tempo` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codPlataforma`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `professorTurma`
-- 

CREATE TABLE `professorTurma` (
  `codUser` bigint(20) NOT NULL default '0',
  `codTurma` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codUser`,`codTurma`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `projeto`
-- 

CREATE TABLE `projeto` (
  `codProjeto` int(11) NOT NULL auto_increment,
  `desTitulo` varchar(100) NOT NULL default '',
  `codOwner` int(11) NOT NULL default '0',
  `desProjeto` text NOT NULL,
  `flaEstado` tinyint(4) NOT NULL default '0',
  `codOrientador` int(11) NOT NULL default '0',
  `codEscola` tinyint(4) NOT NULL default '0',
  `codPlataforma` tinyint(4) NOT NULL default '0',
  `hits` bigint(20) NOT NULL default '0',
  `tempo` int(11) NOT NULL default '0',
  KEY `codProjeto` (`codProjeto`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `projetoAreas`
-- 

CREATE TABLE `projetoAreas` (
  `codProjeto` int(11) NOT NULL default '0',
  `codArea` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`codProjeto`,`codArea`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `projetoMatricula`
-- 

CREATE TABLE `projetoMatricula` (
  `codMatricula` int(11) NOT NULL auto_increment,
  `codProjeto` int(11) NOT NULL default '0',
  `codUser` int(11) NOT NULL default '0',
  `tempo` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codMatricula`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 PACK_KEYS=0;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `projetoStatus`
-- 

CREATE TABLE `projetoStatus` (
  `codStatus` tinyint(4) NOT NULL auto_increment,
  `desStatus` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`codStatus`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `tipo_cursos`
-- 

CREATE TABLE `tipo_cursos` (
  `codTipoCurso` int(11) NOT NULL auto_increment,
  `nomTipoCurso` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`codTipoCurso`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `turma`
-- 

CREATE TABLE `turma` (
  `codTurma` int(11) NOT NULL auto_increment,
  `nomTurma` varchar(15) NOT NULL default '',
  `codCiclo` int(11) NOT NULL default '0',
  `codEscola` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codTurma`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `user`
-- 

CREATE TABLE `user` (
  `codUser` bigint(20) NOT NULL auto_increment,
  `nomUser` varchar(40) NOT NULL default '',
  `desSenha` varchar(60) NOT NULL default '',
  `desSenhaPlain` varchar(30) NOT NULL default '',
  `strMaildir` varchar(100) NOT NULL default '',
  `codPlataforma` tinyint(4) NOT NULL default '0',
  `flaSuper` char(1) NOT NULL default '',
  `flaAprovado` char(1) NOT NULL default '1',
  `flaAtivo` char(1) NOT NULL default '1',
  `flaHomedir` char(1) NOT NULL default '0',
  `nomPessoa` varchar(50) NOT NULL default '',
  `tempo` bigint(20) NOT NULL default '0',
  `strEMail` varchar(100) NOT NULL default '',
  `strEMailAlt` varchar(100) NOT NULL default '',
  `desEndereco` varchar(150) NOT NULL default '',
  `codCidade` int(11) NOT NULL default '0',
  `desCEP` varchar(9) NOT NULL default '0',
  `desTelefone` varchar(15) NOT NULL default '',
  `desFax` varchar(15) default '0',
  `desCargo` varchar(20) default NULL,
  `desHistorico` text,
  `desUrl` varchar(50) default NULL,
  `codEscola` tinyint(4) NOT NULL default '0',
  `datNascimento` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`codUser`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `userClasse`
-- 

CREATE TABLE `userClasse` (
  `codUser` int(11) NOT NULL default '0',
  `codClasse` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`codUser`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `userTurma`
-- 

CREATE TABLE `userTurma` (
  `codUser` int(11) NOT NULL default '0',
  `codTurma` int(11) NOT NULL default '0',
  PRIMARY KEY  (`codUser`,`codTurma`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Estrutura da tabela `usuario_categoria`
-- 

CREATE TABLE `usuario_categoria` (
  `codUser` bigint(20) NOT NULL default '0',
  `codCategoria` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

COMMIT;
