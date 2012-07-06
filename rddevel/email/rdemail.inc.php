<?
/**
*/
/**
 * Classe que define um sistema de emails interno
 * Classe que define um sistema de emails internos
 * @author Maicon Browers
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage email
 * @see RDObj
 */

class RDEmailMen extends RDObj {
  function RDEmailMen($key="") {
    $table = "email_mensagens";
    $fields = array("codMensagem","codUser","nomPessoaEnviou","assunto","mensagem","tempo");
    $pkFields = "codMensagem";
    $fields_def = array();
    $fields_def[codMensagem] = array("type" => "int","size" => "11","bNull" => "0");
    $fields_def[codUser] = array("type" => "int","size" => "11","bNull" => "0");
    $fields_def[nomPessoaEnviou] = array("type" => "varchar","size" => "60","bNull" => "0");
    $fields_def[assunto] = array("type" => "varchar","size" => "100","bNull" => "0");
    $fields_def[mensagem] = array("type" => "mediumtext","size" => "","bNull" => "0");
    $fields_def[tempo] = array("type" => "int","size" => "11","bNull" => "0");
    $this->RDObj($table,$fields,$pkFields,$key,$fields_def);
  }

  function listaUsuariosDestino() {
    $chave = array();
    $chave[] = opVal("email_mensagens.codMensagem",$this->codMensagem);
    $chave[] = opVal("flaCopia",0);
    $usuarios = new RDLista("RDEmailUserDestino",$chave);
    return $usuarios;
  }

}

class RDEmailUserDestino extends RDEmailMen {
  function RDEmailUserDestino($codMsg="",$codUser="",$flaCopia=0) {
    $table = "email_users_destino";
    $fields = array("codMensagem","codUserDestino","flaCopia","flaLida");
    $pkFields = array("codMensagem","codUserDestino","flaCopia","flaLida");
    $fields_def = array();
    $fields_def[codMensagem] = array("type" => "int","size" => "11","bNull" => "0");
    $fields_def[codUserDestino] = array("type" => "int","size" => "11","bNull" => "0");
    $fields_def[flaCopia] = array("type" => "char","size" => "1","bNull" => "0");
    $fields_def[flaLida] = array("type" => "char", "size" => "1", "bNull" => "0");
    if (!empty($codMsg)) {
      $keys = array();
      $keys[] = opVal("email_mensagens.codMensagem",$codMsg);
      $keys[] = opVal("codUserDestino",$codUser);
      if (empty($flaCopia))
	$flaCopia = 0;
      $keys[] = opVal("flaCopia",$flaCopia);
    }

    $this->RDEmailMen();
    $this->RDObj($table,$fields,$pkFields,$keys,$fields_def);
  }


}



?>