# phpMyAdmin MySQL-Dump
# version 2.3.0-rc2
# http://phpwizard.net/phpMyAdmin/
# http://www.phpmyadmin.net/ (download page)
#
# Servidor: tolkien
# Tempo de Generação: Nov 15, 2002 at 12:18 AM
# Versão do Servidor: 3.23.41
# Versão do PHP: 4.2.3
# Banco de Dados : `etc`
# --------------------------------------------------------

#
# Estrutura da tabela `arquivos`
#

CREATE TABLE arquivos (
  codArquivo int(11) NOT NULL auto_increment,
  desDados blob NOT NULL,
  desTipoMime varchar(20) NOT NULL default '',
  desTamanho int(11) NOT NULL default '0',
  desNome varchar(30) NOT NULL default '',
  tempo int(11) NOT NULL default '0',
  PRIMARY KEY  (codArquivo)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Estrutura da tabela `comentarios`
#

CREATE TABLE comentarios (
  codComentario int(11) NOT NULL auto_increment,
  strFerramenta varchar(15) NOT NULL default '',
  codTag int(11) NOT NULL default '0',
  codUser int(11) NOT NULL default '0',
  desComentario text NOT NULL,
  params blob NOT NULL,
  tempo int(11) NOT NULL default '0',
  PRIMARY KEY  (codComentario)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Estrutura da tabela `curso`
#

CREATE TABLE curso (
  codCurso int(11) NOT NULL auto_increment,
  nomCurso varchar(80) NOT NULL default '0',
  codAdm int(11) NOT NULL default '0',
  desCurso text NOT NULL,
  desUrl varchar(80) NOT NULL default '',
  tempo int(11) NOT NULL default '0',
  PRIMARY KEY  (codCurso,nomCurso)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Estrutura da tabela `diario_bordo`
#

CREATE TABLE diario_bordo (
  codMensagem int(11) NOT NULL default '0',
  codUser int(11) NOT NULL default '0',
  codProjeto int(11) NOT NULL default '0',
  desMensagem int(11) NOT NULL default '0',
  tempo int(11) NOT NULL default '0',
  PRIMARY KEY  (codMensagem)
) TYPE=MyISAM COMMENT='se codProjeto=o entao diario de bordo do usuario senao diari';
# --------------------------------------------------------

#
# Estrutura da tabela `ferramentas`
#

CREATE TABLE ferramentas (
  codFerramenta int(11) NOT NULL auto_increment,
  nomFerramenta varchar(80) NOT NULL default '',
  strFerramenta varchar(20) NOT NULL default '',
  PRIMARY KEY  (codFerramenta)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Estrutura da tabela `pessoa`
#

CREATE TABLE pessoa (
  codUser int(11) NOT NULL default '0',
  foto int(11) default NULL,
  endereco varchar(50) default NULL,
  cidade varchar(30) default NULL,
  telefone varchar(20) default NULL,
  desEmail varchar(50) NOT NULL default '',
  descricao text,
  tempo int(11) NOT NULL default '0',
  PRIMARY KEY  (codUser)
) TYPE=ISAM PACK_KEYS=1;
# --------------------------------------------------------

#
# Estrutura da tabela `turma_matriculado_users`
#

CREATE TABLE turma_matriculado_users (
  turma int(11) NOT NULL default '0',
  user int(11) NOT NULL default '0',
  tempo int(11) NOT NULL default '0',
  PRIMARY KEY  (turma,user)
) TYPE=ISAM PACK_KEYS=1;
# --------------------------------------------------------

#
# Estrutura da tabela `turma_rooda`
#

CREATE TABLE turma_rooda (
  id int(11) NOT NULL auto_increment,
  turma varchar(50) NOT NULL default '0',
  disciplina int(11) NOT NULL default '0',
  tempo_criacao int(11) NOT NULL default '0',
  tempo_inicio int(11) NOT NULL default '0',
  tempo_fim int(11) NOT NULL default '0',
  encerrada char(1) NOT NULL default 'N',
  url varchar(50) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=ISAM PACK_KEYS=1;
# --------------------------------------------------------

#
# Estrutura da tabela `user`
#

CREATE TABLE user (
  codUser int(11) NOT NULL auto_increment,
  nomUser varchar(40) NOT NULL default '',
  desSenha varchar(60) NOT NULL default '',
  flaSuper char(1) NOT NULL default 'N',
  nomPessoa varchar(50) NOT NULL default '',
  tempo int(11) NOT NULL default '0',
  PRIMARY KEY  (codUser),
  UNIQUE KEY user (nomUser)
) TYPE=ISAM PACK_KEYS=1;

    

