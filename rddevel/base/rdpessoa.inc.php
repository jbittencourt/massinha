<?

/**
 * Classe que define os campos de uma pessoa
 *
 * @author Maicon Browers
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage base
 * @see RDObj
 */
class RDPessoa extends RDUser {
  function RDPessoa($chave="") {
    $tabelaPessoa = "pessoa";
    $camposPessoa = array("codUser","desEmail");
    $chavesPPessoa = "codUser";
    if (!empty($chave)) {
      if (!is_array($chave))	{
	$keyvalue = $chave;
	$chave = array();
	$chave[] = opVal("codUser",$keyvalue,"user");	
      }
    }	 
    $this->RDUser();
    $this->RDObj($tabelaPessoa,$camposPessoa,$chavesPPessoa,$chave);
  }
}


?>