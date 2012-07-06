<?
/**
 * Classes para serem usadas numa agenda ou algo do tipo
 *
 * Tres Funcionalidades basicas : Compromissos, Avisos e Anotacoes
 * @access public
 * @package rddevel
 * @subpackage base
 */
include_once("$rdpath/upload/rddbupload.inc.php");

class RDCompromisso extends RDObj {

  function RDCompromisso($key="") {

    $table = "compromisso";
    $fields = array("codCompromisso","codUser","nomCompromisso","desCompromisso","timeDATA");
    $pkFields = "codCompromisso";
    $fields_def = array();
    $fields_def[codCompromisso] = array("type" => "int","size" => "11","bNull" => "0");
    $fields_def[codUser] = array("type" => "int","size" => "11","bNull" => "0");
    $fields_def[nomCompromisso] = array("type" => "varchar","size" => "40","bNull" => "0");
    $fields_def[timeDATA] = array("type" => "int","size" => "11","bNull" => "0");
    $fields_def[desCompromisso] = array("type" => "tinytext","size" => "","bNull" => "1");
    $this->RDObj($table,$fields,$pkFields,$key,$fields_def);
  }

}

class RDAnotacao extends RDDbUpload {

  function RDAnotacao($chave="") {

    $tabela = "anotacao";
    $campos = array("codAnotacao","codArquivo","codUser","nomAnotacao","tempo");
    $campoChave = array("codAnotacao","codArquivo");
    $this->RDDbUpload();
    $this->RDObj($tabela,$campos,$campoChave,$chave);

  }

}

class RDAviso extends RDObj {

  function RDAviso($chave="") {
    /** se flaCurso = 1 entao aviso do curso
                        senao aviso pessoal*/         
    $tabela = "aviso";
    $campos = array("codAviso","codUser","flaCurso","desAviso");
    $campoChave = "codAviso";
    $this->RDObj($tabela,$campos,$campoChave,$chave);

  }

}


?>