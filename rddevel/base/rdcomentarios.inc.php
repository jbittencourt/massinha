<?


/**
 * Classe para utilização de comentarios
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage base
 * @see RDObj
 */
class RDComentario extends RDObj {

	//se apenas $cod eh passado como parametro entao cod eh codComentario
	//se nao $cod eh codFerramenta e entao eh necessario $codTag
	//se codTag nao for suficiente para identificar um unico registro,
	//passar os outros campos no 3o parametro.
	//$params eh um array de chaves no formato opVal ou opMval:

	function RDComentario($cod="",$codTag="",$params="") {
      $tabelaCom = "comentarios";
      $camposCom = array("codComentario","strFerramenta","codTag",
  					   "codUser","desComentario","params","tempo");  	
  	$prKey     = "codComentario";
	  if (!empty($cod)) {
	    if (empty($codTag))
	      $chave[] = opVal("codComentario",$cod);
		else {
		  $chave[] = opVal("codFerramenta",$cod);
		  $chave[] = opVal("codTag",$codTag);	
		}
	  };	
	  if (!empty($params)) {
	    if (is_array($params))
		  $chave = array_merge($chave,$params);
	  };
      $this->RDObj($tabelaCom,$camposCom,$prKey,$chave);
	}	

   function getUserAutor() {
     $user = new RDPessoa($this->codUser);
     return $user;	
   }	


  }  

  class RDFComentario extends RDFerramenta {
    function RDFComentario() {
	  global $ferramentas;
	  $this->RDFerramenta($ferramentas[Comentarios][nome],$ferramentas[Comentarios][str]);
    }
  }


?>
