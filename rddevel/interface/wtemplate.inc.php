<?

include_once($pathilib."pagina.inc.php");
  
/**
 * Classe para a criaчуo de pсgina a partir de templates
 * @author Maicon Browers
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage interface
 */
class WTemplate {
  var $nomearq, $pilha;
  var $ger_lang;
  var $loadarq;
  var $local;
  var $translate;
  
  
  function WTemplate($pag) {
    $this->nomearq = $pag;
    $this->ger_lang = 0;
  }
       
  function setLocal($local) {
    $this->local = $local;
  }
       
  function setLanguageOn() {
    $this->translate = 1;
  }
       
  function translate() {
    global $lang;
       	
    preg_match_all("({LANG_\w+})",$this->loadarq,$matchs);
    $matchs = $matchs[0];

    foreach($matchs as $k=>$item) {
      $litem = substr($item,6,strlen($item)-7);
      $item = substr($item,1,strlen($item)-2);  //tira as chaves
      $msg = $lang[strtolower($litem)];
      if(!empty($msg))
	$this->sub($item,$msg);
    };


  }
       
  function add($str,$local="") {
    if((!empty($this->local)) && empty($local)) {
      $local = $this->local; 
    }
    else {
      if(empty($local)) $local = "AREA";
    }
       	
    if(!empty($this->pilha[$local])) {
      $arr = &$this->pilha[$local];
    }
    else {
      $arr = array();
      $this->pilha[$local] = &$arr;
    };
           
    $arr[] = $str;
  }
       
  function assign($var,$str) {
    $this->pilha[$var] = $str;
  }
  function sub($chave,$str) {    
    if(empty($chave) || empty($this->loadarq)) return 0;  
    $chave = "\{".strtoupper($chave)."\}";
    $this->loadarq = @ereg_replace("$chave","$str",$this->loadarq);
  }       
       
  function toString() {	

  }
       
  function imprime() {
    global $config_ini;
          
         
    //le arquivo
    $conf = $config_ini[Diretorios];
    $this->loadarq = implode("\n",file($conf[pathtemplates].$this->nomearq));
          
    $this->sub("URL",$config_ini[Internet][url]);
    $this->sub("URLTEMPLATES",$config_ini[Internet][urltemplates]);

    if($this->translate) $this->translate();
		  		  
    if(!empty($this->pilha)) {
      foreach($this->pilha as $chave=>$p) {
    			             
	if(is_array($p)) {
	  //caso seja um array, transcreve tudo para um array auxiliar
	  //de modo a detectar se existe algum objeto intermediario
	  $temp = array();
	  foreach($p as $n=>$item) {
	    if(empty($item)) continue;
	    if(is_subclass_of($item,"pagObj")) {
	      $temp[] = $item->toString();
	    }						
	    else {
	      $temp[] = $item;
	    };
                       
	  };
	  $this->sub($chave,implode("\n",$temp)); 
	}
	else {
	  //caso seja um unico objeto repassdo por um assign
                  
	  if(is_subclass_of($p,"pagObj")) {
	    $str = $p->toString();
	  } 
	  elseif (is_subclass_of($p,"wtemplate")) {
	    $str  = $p->toString();	
	  }		
	  else {
	    $str = $p;
	  }
                    
	  $this->sub($chave,$str);
	};
             
      };
    };

    echo $this->loadarq;       
  }
  
}


?>