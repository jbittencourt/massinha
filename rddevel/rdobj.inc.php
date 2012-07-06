<?
/**
 */
/**
 * RDObj eh o objeto de acesso e manipulacao do bco de dados do ROODA DEVEL
 *
 * Atraves dele que se obtem acesso ao banco de dados, funcionando como um wrapper com o bd, tratando as informacos como
 * um objeto. Na chamado do metodo construtor ( RDObj() ) eh passada a tabela, os campos e os campos chaves do 
 * banco de dados, e uma possivel chave. No metodo le() ele recebe uma chave e faz a leitura no banco de dados, 
 * retornando os campos da(s) tabela(s) pesquisada(s) como propriedades do objeto. O metodo salva() faz ou um insert 
 * se o registro eh novo ou um update se o registro ja existe. Nao eh necessario nenhuma linha de sql para todas
 * estas manipulações.
 *
 * Usualmente o RDObj não utiliza diretamente no código, mas sim na definição de outros objetos que o extendem. Estes por
 * sua vez representam tabela dentro do bando de dados. O exemplo abaixo demostra a construção de um objeto que manipula
 * diretamente uma tabela.
 *
 * example rdobj1.php
 *
 * @author Maicon Brauwers <maicon@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage base 
*/

class RDObj {

  /**
   * @var array $primaryKeys guarda os campos que sao chaves primarias da tabela, no seguinte formato :
   *    primaryKeys[nomeTabela] = array de campos;      
   *   @var array $tabelas fila de tabelas, em nome de menos hierarquica para mais hierarquica    
   *   @var ptr $notifyDelete : ponteiro para o objeto de lista que o objeto pertence, se pertencer;
   *   @var array $campos : array de campos, no formato campos[nomeTabela] = array dos campos
   *   @var array $chaves : array com os campos chaves e seus valores
   *   @var int $novo : flag que controla se o obj eh novo ou nao, ou seja, se existe o registro no bd ou nao
   *   @var array $defCampos : campos e sua definicao de tipo e tamanho
  */
  
  var $primaryKeys = array();	  
  var $tabelas = array();		   
  var $notifyDelete = 0; 
  var $campos = array();	           
  var $chaves;				   
  var $novo;
  var $defCampos; 
  
  /**
   * Funcao construtora. Seta as propriedades para poder manipular o objeto.
   *
   *  @access public
   *  @param string $tabela : nome da tabela do bco de dados do objeto
   *  @param array  $campos : array com os nomes dos campos da tabela
   *  @param mixed $camposChavePrimaria : nome ou nomes dos campos que sao chave primaria
   *  @param mixed $chave : chave para ser usada na leitura(select) no bco de dados
   *  @param array $campos_def : array no formato array[campo][tipo]
   *                                                          [tamanho]
   */ 
  function RDObj($tabela,$campos,$camposChavePrimaria,$chave="",$campos_def="") {	
    
    $this->tabelas[] = $tabela;			   
    $this->campos[$tabela] = $campos;		 //array dos campos indexado pelo nome da tabela
    if (empty($this->defCampos)) {
      $this->defCampos = $campos_def;
    }
    else {
      $this->defCampos = array_merge($this->defCampos,$campos_def);
    }

    if (is_array($camposChavePrimaria)) {
      foreach ($camposChavePrimaria as $campo)
	$this->primaryKeys[$tabela][] = $campo;
    }
    else
      $this->primaryKeys[$tabela][] = $camposChavePrimaria;
    
    
    if (!empty($chave)) {
      if(!$this->le($chave)) 
      	$this->novo = 2;
    }
    else
      $this->novo = 2;	//os registros de todas as classes sao novas
    
  }

  /* adicona um novo campo a definição do objeto
   *
   * Essa função é útil principalmente para definir
   * novos objetos que estendem os anteriores.
   *
   * @access public
   * @param string $field Nome o novo campo
   * @param string $table Nome da tabela do novo campo
   * @param string $type Tipo do campo (INT, BIGINT, VARCHAR, CHAR, BLOB,...)
   * @param int $size Número de caracteres ocupado pelo campo
   * @param int $null Se o campo pode ser nulo ou não.
   * @param int $showOnList Se o campo devera aparecer num RDLista ou RDRel, o que significa se ele estara ou nao na projecao do select 
   */
  function addField($field,$table,$type="",$size="",$null="0",$showOnList=1,$autoInc=0) {
    $this->campos[$table][] = $field;
    $this->defCampos[$field] = array("type" => $type,"size" => $size,"bNull" => $null,"showOnList"=>$showOnList,"autoInc"=>$autoInc);
  }

  /** Retorna os nomes dos campos que deverao aparecer num RDLista ou RDRel
   *
   */
  function getCamposLista() {
    $camposLista = array();
    foreach ($this->defCampos as $campo=>$prop) {
      if ($prop[showOnList]) {
	foreach ($this->tabelas as $tabela) {
	  if (in_array($campo,$this->campos[$tabela]))
	    $camposLista[] = "$tabela.$campo";
	}
      }
    }
    return $camposLista;
  }

  /* Le os dados para o objeto a partir do dados de request
   *
   * Para facilitar o processo de carregar os dados para um objeto
   * enviados a partir de um form. Ela é principalmente últil em
   * conjunto com o smartform na medida que o smartform padroniza
   * os nomes do formulario a partir dos nomes dos campos do objeto.
   *
   * @access public
   * @param string prefix  Tenta adivinhar o nome que o campo recebeu no formulario adicionando o prefixo
   * @see WSmartForm
   */
  function loadDataFromRequest($prefix="frm_") {
    foreach($this->tabelas as $table) {
      foreach($this->campos[$table] as $campo) {
	$nome = $prefix.$campo;
	if(isset($_REQUEST[$nome])) {
	  $this->$campo = $_REQUEST[$nome];
	}
      }
    }
  }

  /**
   * Retorna o nome da tabela da classe mais hierarquica
   *
   * @access private
   * @return string Retorna o nome da classe mais hierarquica
  */
  function getTableOfHigherClass() {
    $tabela = end($this->tabelas);	
    reset($this->tabelas);
    return $tabela;
  }	
  
  /** Retorna um array com os nomes da tabelas
   *  @return array
   */
  function getTables() {
    return $this->tabelas;
  }


  /**
   * Retorna o nome de todos os campos de todas as tabelas
   *
   * @access private
   * @return array : Retorna o nome de todos os campos
   */
  function getFieldNamesOfDB() {
    $campos = array();
    foreach($this->tabelas as $tabela) {
      $camposTabela = $this->getFieldsOfTable($tabela);
      $campos = array_merge($campos,$camposTabela);
    }	  	
    return array_unique($campos);		//tira campos duplicados
  }	


  /**
   * Retorna o nome de todos os campos de todas as tabelas com o nome do campo no formato nomeTabela.nomeCampo
   *
   * @access private
   * @return array : Retorna o nome de todos os campos
   */
  function getFullFieldNamesOfDB() {
    $campos = array();
    foreach($this->tabelas as $tabela) {
      $camposTabela = $this->getFieldsOfTable($tabela);
      foreach ($camposTabela as $campo) {
	$campos[] = "$tabela.$campo";
      }
    }	  	
    return $campos;		//tira campos duplicados
  }	


  /**
   * Retorna o nome dos campos de uma determinada tabela 	
   *
   *  @access private
   *  @param string $tabela Nome da tabela
   *  @return array Nome dos campos da tabela
   */
  function getFieldsOfTable($tabela) {
    return $this->campos[$tabela];
  }

  /**
   * Pega todos as propriedades do objeto(campos) vindas de uma determinada tabela e retorna um array no tipo array[campo] = valor
   *
   * @access private
   * @param $tabela Nome da tabela
   * @return array Retorna os campos e seus valores num array associativo array[campo] = valor      
  */
  function getArrayFromFields($tabela,$camposNaoRetornar="") {
    $valores = array();
    $campos = $this->getFieldsOfTable($tabela);
    foreach ($campos as $campo) {
      if (isset($this->$campo)) {
        if (!is_array($camposNaoRetornar) || !in_array($campo,$camposNaoRetornar))
	  $valores[$campo] = $this->$campo;
      }
    }  
    return $valores;
  }

  //coonverte os campos em um array tipo array[campo] = valor
  function toArray() {
    $ret = array();
    foreach($this->tabelas as $tab) {
      $temp = $this->getArrayFromFields($tab);
      $ret = array_merge($ret,$temp);
    }
    
    return $ret;
  }
  
  /**
   * Retorna os nomes dos campos chaves de todas as tabelas
   *
   * @access private;
   * @return array Retorna os nomes de todos campos chaves
  */   
  function getKeyFieldsNames() {
    $camposChave = array();
    foreach ($this->tabelas as $tabela) {
      $chavesTab = $this->getKeyFieldsOfTable($tabela);
      $camposChave = array_merge($camposChave,$chavesTab); 
    };	    	
    return array_unique($camposChave);	
  }	

  /**
   *  Retorna se o campo eh chave primaria
   *
   */
  function isPKField($campo) {
    $camposChaves = $this->getKeyFieldsNames();
    return in_array($campo,$camposChaves);
  }

  /**
   * Retorna os nomes dos campos chave de uma determinada tabela
   *
   * @access private 
   * @param string $tabela Nome da tabela
   * @return array Nomes dos campos chaves   
  */
  function getKeyFieldsOfTable($tabela) {
    $camposChaveP = $this->primaryKeys[$tabela];
    return $camposChaveP;
  }

  /**
   * Retorna as chaves do objeto e seus respectivos valores no formato das funcoes de tabelas.inc.php
   *
   *  @access private
   *  @return array Retorna as chaves do objeto
  */
  function getKeys() {
    $chaves = array();
    foreach ($this->tabelas as $tabela) {
      $chaves[$tabela] = $this->getKeysOfTable($tabela);    
    };
    return $chaves;
  }

  /**
   * Retorna as chaves de uma determinada tabela e seus respectivos valores no formato das funcoes de tabelas.inc.php
   *
   *  @access private
   *  @param string $tabela Nome da tabela
   *  @return array Retorna as chaves do objeto
  */
  function getKeysOfTable($tabela) {
    $camposChave = $this->getKeyFieldsOfTable($tabela);
    if (!empty($camposChave)) {
      if (is_array($camposChave)) {
        foreach ($camposChave as $campo) {
	  if (isset($this->$campo))	
            $chave[$campo] = array("op"=>"=","valor"=>$this->$campo);
	};
      }
      else
	$chave[$camposChave] = array("op"=>"=","valor"=>$this->$camposChave);
      return $chave;
    }
    else return 0;
  }

  /**
   * Seta a propriedade chaves com base nos valores dos campos do objeto
   *
   *  @access private
  */
  function setKeys() {
    $chaves = $this->getKeys();
    if (is_array($this->tabelas)) {
      foreach ($this->tabelas as $tabela) {
        $this->chaves[$tabela] = $chaves[$tabela]; 
      };
    };
  }	

  
  /**
   * Seta as chaves de determinada tabela
   *
   * @acess private
  */

  function setKeysOfTable($tabela) {
    if (!empty($tabela)) {
      $chaves = $this->getKeysOfTable($tabela);
      if (is_array($chaves))
	$this->chaves[$tabela] = $chaves;
      else return 0;
    }
    else return 0;
  }    


  /**
   * Le campos e valores de um array e seta as propriedades do objeto referente a estes dados. O array eh do formato do retornato pela funcao funcao mysql_fetch_array 	
   *
   *
   * @acess public
   * @param $registro O array de dados
  */    
  function parseFieldsFromArray($registro) {
    if (is_array($registro)) {
      while (list($campo,$valor)=each($registro))      
	if (!isset($this->$campo))			//seta as propriedades do objeto 
          $this->$campo = $valor;			 //baseado no array
      $this->setKeys($chaves);	                        //atualiza as chaves 
      return 1;
    }
    else return 0;
  }	

  /**
   * Atualiza as propriedade do objeto que sao campos da tabela passada como parametro fazendo uma leitura no db	
   *
   * @access private
   * @param string $tabela Nome da tabela
  */
  function updateFieldsFromTable($tabela) {
    $campos = $this->getFieldsOfTable($tabela);
    $reg = leRegTabela($tabela,$campos,$this->chaves[$tabela]);
    if (is_array($reg)) {
      while (list($campo,$valor)=each($reg)) {
	//	if (!isset($this->$campo))
	if($this->defCampos[$campo][type]!="blob")
	  $this->$campo = $valor;
      }
      $this->setKeysOfTable($tabela);  //atualiza as chaves
    }
  }
  
  /**
   * Funcao que determina se o campo eh campo chave de alguma tabela e retorna num array os nomes de todas as tabelas em que eh campo chave
   *
   *  @access private
   *  @param string $campo Nome do campo
   *  @return array Nome das tabelas onde o campo eh campo chave
  */
  function fieldIsKey($campo) {
    $tabs = array();
    foreach ($this->tabelas as $tabela) {	
      $camposChave = $this->getKeyFieldsOfTable($tabela);	
      if (is_array($camposChave)) {	
	if (in_array($campo,$camposChave)) {
	  $tabs[] = $tabela;    	
	};
      };
    };	
    if (is_array($tabs) && (count($tabs)>0)) return array_unique($tabs);
    else				     return 0;
  }  

  /**
   * Funcao booleana que determina se campo pertence a tabela
   *
    * @access private 
    * @param string $campo Nome do campo
    * @param string $tabela Nome da tabela
    * @return boolean Retorna se o campos esta ou nao na tabela
  */
  function fieldIsInTable($campo,$tabela) {
    $campos = $this->getFieldsOfTable($tabela);
    if (in_array($campo,$campos)) return 1;
    else			  return 0;
  }	


  /**
   *Checa se todos os campos chaves da classe mais hierarquica estao presentes nas chaves passadas como parametro, para serem usadas num leitura
   * @access private
   * @param string $tabela Nome da tabela
   * @param array $chaves Chaves que serao usadas para formar o select para fazer a leitura
   * @return boolean Retorna se todas os campos sao valorados ou nao
   */ 
  function checkKeysOfTable($tabela,$chaves) {
    $camposChaveParam = array(); //array que formara os campos chaves passado por parametro da respectiva tabela
    $camposChaveObj = $this->getKeyFieldsOfTable($tabela);    //campos chave do objeto
    foreach ($chaves as $chave) {
      if (in_array($chave[campo],$camposChaveObj))
        $camposChaveParam[] = $chave[campo];  	
    }		
    array_unique($camposChaveParam);
    sort($camposChaveParam);
    sort($camposChaveObj);
    //se os campos chaves nao forem totalmente iguais chaves insuficientes;
    if ($camposChaveParam==$camposChaveObj)
      return 1;
    else 
      return 0;
  }
  
  
  /** 
   * Gera chaves estrangeiras	
   *
   * @access private
   * @return array Retorna as chaves estrangeiras
  */  
  function getForeignKeys() {
    $tabelas = $this->tabelas;
    reset($tabelas);
    $foreignKeys = array();
    $campos = $this->getKeyFieldsNames();
    foreach ($campos as $campo) {
      $tabelasChave = $this->fieldIsKey($campo);
      if (is_array($tabelasChave)) {
	$tab1 = current($tabelasChave);	
	foreach ($tabelas as $tab2) {
          if ($this->fieldIsInTable($campo,$tab2)) {
    	    if ($tab1!=$tab2)
	      $foreignKeys[] = opMVal($tab1,$campo,$tab2);
	  };
        };
      };
    };        	
    return $foreignKeys;
  }

  /**
   *Funcao que gera as chaves estrangeiras se necessario e faz o select do bco de dados seta as proprieadas do objeto relativas aos campos das tabelas que retornaram da consulta
   *
   * @access private
   * @param mixed chaves Chaves que serao usadas pra formar o select  
   */
  function parseFieldsFromQuery($chaves) {
    global $r_pesquisas;		//"cache" de pesquisas ja realizadas
    $novo = 0;


    if (!empty($chaves)) {
      if (is_array($this->tabelas)) {
	$tabelas = $this->tabelas;
	reset($tabelas);
        $tabela = end($tabelas);
	
	if (!is_array($chaves)) {
	  $camposC = $this->getKeyFieldsOfTable($tabela);
	  if (count($camposC)==1) {
    	    $key = $chaves;
    	    $chaves = array();
    	    $chaves[] = opVal(current($camposC),$key,$tabela);
	  };
	};

	if (count($this->tabelas)>1) {	  
	  /*  		if (!$this->checkKeysOfTable($tabela,$chaves)) {        //registro eh novo
	   $tabela = array_pop($tabelas);						 //tira tabela mais hierarquica do select
	   $novo = 1;
	   $tabela.= "_novo";
	   }*/
	  if ((!is_array($r_pesquisas)) || (!array_key_exists($tabela,$r_pesquisas))) {
	    //cruzamentos entre as classses	
	    $foreignKeys = $this->getForeignKeys();
	    $r_pesquisas[$tabela] = $foreignKeys;
	  };
	}
	else {
	  $r_pesquisas[$tabela] = array();
	};  		

	foreach($r_pesquisas[$tabela] as $item) {
	  $chaves[] = $item;
	};

	$campos = $this->getFieldNamesOfDB();

	$reg = leRegTabela($tabelas,$campos,$chaves);
	
	$sucesso = $this->parseFieldsFromArray($reg);

	if ($sucesso) {
	  $this->novo = 0;
	  return 1;
	}
	else {
	  $this->novo = 2;		//registro novo
	  return 0;	
	}
      }
	  else return 0; 
    }
    else {
      $this->novo = 2;   //registros de todas as classes sao novos	
      return 0;
    }
  }

  /** Le um registro no banco de dados  baseado nas chaves passadas como parametro 
   *
    * @access public
    * @param mixed $chave Chave para ler registro
    * @return boolena Retorna se a operacao foi realizada 
    */
  function le($chave) {
    if (!empty($chave))
      $sucesso = $this->parseFieldsFromQuery($chave);
    else 
      $sucesso = 0;	
    return $sucesso;
  }

  /**Grava no banco de dados, fazendo um insert se o registro for novo ou um update se o registro ja existe
   * @param int $fazerSelectAposUpdate : Se deve-se fazer select apos insert 
   * @acess public
   */
 
  function salva($fazerSelectAposInsert=1,$camposNaoFazerUpdate="") {
    switch ($this->novo) {
    case 0:
      //registro ja existe, fazer update
      foreach ($this->tabelas as $tabela) {
      	$valores = $this->getArrayFromFields($tabela,$camposNaoFazerUpdate); //pega os valores
	
	atualizaRegTabela($tabela,$valores,$this->chaves[$tabela]);
	$this->setKeysOfTable($tabela);	 			   //atualiza as chaves
      }
      break;	
      
    case 1:
      //apenas classe mais hierarquica eh novo
      $tabelas = $this->tabelas;
      reset($tabelas);
      $tabela = array_pop($tabelas);
      $valores = $this->getArrayFromFields($tabela);		//pega os valores
      insereRegTabela($tabela,$valores);	        	//insere no bd
      $campos = $this->getFieldsOfTable($tabela);
      foreach ($campos as $campo) {
	if (isset($this->$campo))
	  $chaves[] = opVal($campo,$this->$campo,$tabela);
      }
      $this->chaves[$tabela] = $chaves;
      
      if ($fazerSelectAposInsert)
	$this->updateFieldsFromTable($tabela);			//atualiza os campos
      //faz update nas outras classes
      foreach ($tabelas as $tabela) {
	$valores = $this->getArrayFromFields($tabela);		//pega os valores
	atualizaRegTabela($tabela,$valores,$this->chaves[$tabela]);
	$this->setKeysOfTable($tabela);	 			   //atualiza as chaves
      }
    break;	
	  
    //todas as classes sao novas
    case 2:
            
      reset($this->tabelas);
      foreach ($this->tabelas as $tabela) {
	
	$valores = $this->getArrayFromFields($tabela);		//pega os valores
	
	insereRegTabela($tabela,$valores);	    	        //insere no bd
	$campos = $this->getFieldsOfTable($tabela);
	if (isset($chaves)) unset($chaves);
	foreach ($campos as $campo) {
	  if (isset($this->$campo)) {
	    $this->defCampos[$campo] = array();
	    if($this->defCampos[$campo]["type"]!="blob")
	      $chaves[] = opVal($campo,$this->$campo,$tabela);
	  }
	}
	$this->chaves[$tabela] = $chaves;
	if ($fazerSelectAposInsert)
	  $this->updateFieldsFromTable($tabela);	 	        //atualiza os campos
      }
      break;		
    }  

    $this->novo = 0;
  }

  /** 
   * Deleta objeto tanto do db quanto o objeto em si se delCascata for setada para true entao deleta em cascata  senao (default) deleta apenas nivel mais hierarquico	
   *
   * @access public
   * @param boolean $delCascata Valor booleano se deve deletar em cascata ou nao
   */
  function deleta($delCascata=0) { 
    if (!$this->novo) {
      if ($delCascata) {								//deleta em cascata
	reset($this->tabelas);
	foreach ($this->tabelas as $tabela) {
          delRegTabela($tabela,$this->chaves[$tabela]);	  
	}	
      }
      else {
	$tabela = end($this->tabelas);				  //deleta apenas nivel mais hierarquico
        delRegTabela($tabela,$this->chaves[$tabela]);	  	    	
      }	
      if (!empty($this->notifyDelete))				  //se objeto pertence a uma lista,
        unset($this->notifyDelete[$this->indiceArray]);           //tira a referencia dele 
      //$this = NULL;
      return 1;
    }
    else return 0; 
  }

  /** Deleta os registros em outra tabela que tenham a mesma chave que este registro
   *  Emulacao de deletar em cascata
   *  @param $nomeTabela String : Nome da tabela onde vai deletar
   */
  function delCascata($nomeTabela) {
    if (!$this->novo) {
      $tabela = end($this->tabelas);
      $chaves = $this->chaves[$tabela]; //pega as chaves do objeto
      if (!empty($chaves))
	delRegTabela($nomeTabela,$chaves);
    }
    else 
      return 0;
  }

  function readByRequest($prefix="frm_") {
    if (!empty($_REQUEST)) {
      $chaves = array();
      foreach ($_REQUEST as $chave=>$valor) {
	if ($chave != "acao" && $chave != "PHPSESSID") {
	  if (!empty($prefix))
	    $nomeCampo = substr($chave,strlen($prefix));
	  else
	    $nomeCampo = $chave;
	  $chaves[] = opVal($nomeCampo,$valor);
	}
      }
      $this->le($chaves);
    }
  }
  
  /** Retorna uma lista de comentarios a respeito do objeto atual
   *
   * @acess public
   * @param string $strF String de identificacao da ferramenta
   * @param mixed $adParams Parametros adicionais para leitura dos comentarios
   */
  function getComentarios($strF,$adParams="") {
	$tabela = $this->getTableOfHigherClass();
	$camposChave = $this->getKeyFieldsOfTable($tabela);	
	$campoChave = reset($camposChave);
	$chave = array();
	$chave[] = opVal("strFerramenta",$strF);
	$chave[] = opVal("codTag",$this->$campoChave);
	$comentarios = new RDLista("RDComentario",$chave);
	return $comentarios;
  }

  function eNovo() {
    return $this->novo;
  }


}  //fim da classe




?>
