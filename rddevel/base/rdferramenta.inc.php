<?
/**
 */
/**
 * Classe que define uma ferramenta
 *
 * @author Maicon Browers <maicon@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage base
 * @see RDObj
 */
class RDFerramenta {	
  var $nomFerramenta,$str;
	
  //se $chave nao for array assume que eh um valor de strFerramenta
  function RDFerramenta($nome="",$str="") {
    $this->setNome($nome);
    $this->setStr($str);
  }
  
  function getNome() {
    return $this->nomFerramenta;	
  }	
  
  function setNome($nome) {
    $this->nomFerramenta = $nome;
  }	
  
  function getStr() {
    return $this->str;	
  }	
  
  function setStr($str) {
    $this->str = $str;
  }	
  
}  //fim classe

?>