<?

function tipoArquivo($nome) {    

  $nome = strtolower($nome);

  $extencoes['gif'] = "gif";
  $extencoes['html'] = "html";
  $extencoes['htm'] = "html";
  $extencoes['jpg'] = "jpg";
  $extencoes['zip'] = "zip";
  $extencoes['tar'] = "tar";
  $extencoes['tgz'] = "tgz";
  $extencoes['gz'] = "gz";
  $extencoes['doc'] = "doc";
  $extencoes['ppt'] = "ppt";
  $extencoes['php'] = "php";
  $extencoes['php3'] = "php";
  $extencoes['txt'] = "txt";

  $partes = explode(".",$nome);

  $ultimo = count($partes)-1;

  $retorno = "outro";
  while( list($ext,$tipo)=each($extencoes))
    { if($partes[$ultimo]==$ext) 
      { $retorno = $tipo; };
    };

  return $retorno;
}


function eTexto($tipo)
{
  $imagem[] = "html";
  $imagem[] = "txt";
  $imagem[] = "htm";
  $imagem[] = "php";
  $imagem[] = "php3";
  $imagem[] = "ini";
  $imagem[] = "asp";
  $imagem[] = "c";
  $imagem[] = "cpp";
  $imagem[] = "java"; 

  //   $tipo = tipoArquivo($nome);
  foreach($imagem as $k=>$item)
    if($item==$tipo)
      return 1;

  return 0;
}


function eImagem($nome)
{
  $imagem= array(0=>"gif",1=>"jpg",2=>"bmp");

  $ret = 0;
  $tipo = tipoArquivo($nome);
  while(list(,$item)=each($imagem))
    { if($item==$tipo)
      { $ret = 1; };
    };

  return $ret;
}



/**
 * Implementa a entrada de um arquivo no banco de dados
 * @author Maicon Brauwers <maicon@edu.ufrgs.br>
 * @access public
 * @abstract
 * @version 0.5
 * @package rddevel
 * @subpackage upload
 * @see RDPagObj
 */
class RDDbUpload extends RDObj {
    
  //opcionalmente podem ser passados o nome da tabela, campos e campos chave caso
  //nao seja os dados padrao
  function RDDbUpload($key="") {
    $pkFields = "codArquivo";
    $fgKFields = "";
    $fields_def = array();
    $fields_def[codArquivo] = array("type" => "int","size" => "11","bNull" => "0");
    $fields_def[desDados] = array("type" => "blob","size" => "","bNull" => "0");
    $fields_def[desTipoMime] = array("type" => "varchar","size" => "20","bNull" => "0");
    $fields_def[desTamanho] = array("type" => "int","size" => "11","bNull" => "0");
    $fields_def[desNome] = array("type" => "varchar","size" => "30","bNull" => "0");
    $fields_def[tempo] = array("type" => "int","size" => "11","bNull" => "0");
    $this->RDObj($this->getTables(),$this->getFields(),$pkFields,$key,$fields_def,$fgKFields);
  }

  function getTables() {
    return "arquivos";
  }

  function getFields() {
    return  array("codArquivo","desDados","desTipoMime","desTamanho","desNome","tempo");
  }

  function setData($arq) {

    if (is_array($arq)) {
      if($arq[erro]) return 0;

      $stream = fopen($arq[tmp_name],"r");
      $dados  = addSlashes(fread($stream,$arq[size]));
      $this->desDados = $dados;    
      $this->setName($arq);
      $this->setSize($arq);
      $this->setMimeType($arq);

      return 1;
    }    
    else return 0;
  }    

  function getData() {
    return $this->desDados;    
  }    

  //seta o nome do arquivo
  function setName($arq) {
    if (is_array($arq)) {
      $this->desNome = $arq[name];     
    }                
    else
      $this->desNome = $arq;                        
  }    

  function getName() {
    return $this->desNome;    
  }    


  //seta o tamanho do arquivo
  function setSize($arq) {
    if (is_array($arq)) {
      $this->desTamanho = $arq[size];     
    }                
    else
      $this->desTamanho = $arq;        
  }    

  function getSize() {
    return $this->desTamanho;    
  }    


  //seta o tipo mime, se $arq for um array procurar pela chave type, senao simplesmente setar o tipo
  function setMimeType($arq) {
    if (is_array($arq)) {
      if ($arq[type]=="image/pjpeg")
	$this->desTipoMime  = "image/jpeg";
      else
	$this->desTipoMime = $arq[type];
    }                
    else
      $this->desTipoMime = $arq;
  }    

  function getMimeType() {    
    return $this->desTipoMime;    
  }        

  //retorna true se o arquivo eh imagem
  function eImagem() {

    switch ($this->getMimeType()) {

    case "image/gif":
      return 1;  break;
      case "image/jpeg";
      return 1;  break;
    case "image/pjpeg":
      return 1;  break;        
    case "image/png":
      return 1;  break;        
    case "image/bmp":
      return 1;  break;                          
    default:
      return 0;
    }            
  }

}    


?>
