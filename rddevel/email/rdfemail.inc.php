<?
/**
 */
/**
 * Classe da ferramenta de Email
 *
 * Classe da ferramenta de Email
 *
 * @var $codUser -> codigo do usuario que esta usando a ferramenta
 * @author Maicon Browers
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage email
 * @see RDObj
 */

class RDFEmail extends RDFerramenta {
  var $codUser;
  var $imaplink;
  
  function RDF_Email($codUser="") {
    global $config_ini;

    $this->RDFerramenta("Email","email");
    $this->codUser = $codUser;

  }
  
  /**

  Lista Mensagens Recebidas
  @access public
  @param $codUser -> codigo do usuario cujas mensagens devem ser listadas
  @param $ordem -> ordenacao das mensagens onde :
     $ordem = data -> ordenacao por data 
     $ordem = assunto -> ordenacao por assunto
     $ordem = remetente -> ordenacao por nome da pessoa que enviou
  @param $tipoOrdem -> se a ordenacao eh crescente ou decrescente
     $tipoOrdem = asc -> ascendete
     $tipoOrdem = desc -> descendente
 
  */

  function listaMsgRecebidas($ordem="data",$tipoOrdem="asc",$codUser="") {
    
    $chave = array();
    if (empty($codUser))
      $chave[] = opVal("codUserDestino",$this->codUser);    
    else
      $chave[] = opVal("codUserDestino",$codUser);       

    //flaCopia identifica se a mensagem eh uma copia (pasta enviadas)
    $chave[] = opVal("flaCopia",0);

    switch($ordem) {

    case "data":
      $order = "tempo ";
      break;
      
    case "assunto":
      $order = "assunto ";
      break;
      
    case "remetente":
      $order = "nomPessoaEnviou ";
      break;
      
    default: 
      $order = "tempo ";
      $tipoOrdem = "asc";

    }

    $order.= $tipoOrdem;

    $mens = new RDLista("RDEmailUserDestino",$chave,$order);        
    return $mens->records;
                          
  }
    
  
  /**  Lista Mensagens Enviadas
   *  @access public
   *  @param int $codUser -> codigo do usuario cujas mensagens devem ser listadas
   *  @param string $ordem -> ordenacao das mensagens onde :
   *  $ordem = data -> ordenacao por data 
   *  $ordem = assunto -> ordenacao por assunto
   *  $ordem = remetente -> ordenacao por nome da pessoa que enviou
   *  @param string $tipoOrdem -> se a ordenacao eh crescente ou decrescente
   *  $tipoOrdem = asc -> ascendete
   *  $tipoOrdem = desc -> descendente
   * 
  */

  function listaMsgEnviadas($ordem="data",$tipoOrdem="asc") {
    
    $chave = array();  
    
    $chave[] = opVal("codUser",$this->codUser);     
    $chave[] = opVal("codUserDestino",$this->codUser); 
    $chave[] = opVal("flaCopia",1);

    switch($ordem) {

    case "data":
      $order = "tempo ";
      break;
      
    case "assunto":
      $order = "assunto ";
      break;
      
    case "remetente":
      $order = "nomPessoaEnviou ";
      break;
      
    default: 
      $order = "tempo ";
      $tipoOrdem = "asc";

    }

    $order.= $tipoOrdem;

    $mens = new RDLista("RDEmailUserDestino",$chave,$order);
    
    return $mens->records;
                          
  }


}



?>