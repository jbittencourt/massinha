<?

/** A classe RDRel implementa atraves do uso de RDObj a listagem de varios registros do bco de dados, tratando cada
 * um deles com um objeto RDObj. Ela pega as classes passadas como parametro e a partir de suas chaves primarias
 * gera os relacionamentos entre elas 
 *
 * @author Maicon Brauwers <maicon@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package Rooda_Devel
 * @see RDObj,RDLista
 */

class RDRel extends RDCursor {
  /**
   * @var string $tabelas Nomes das tabelas
   * @var array $campos Campos das tabelas
   * @var string $objMainClass O nome da classe do objeto principal 
   * @var array $objClasses Classes que farao parte dos relacionamentos
   * @var array $records Conjunto dos objetos retornados pela consulta
   * @var array $foreignKeys Chaves estrangeiras
   * @var array $pkFields Campos chaves. $pkFields[$campoChave] = tabelas onde campo eh chave
   * @var string $tipoJoin Tipo de Join que sera utilizado na consulta
   * @var array $renomeacao : Guarda as renomeacoes das tabelas
   */
  var $pkFields;
  
  /** Funcao construtora
   *
   * @access public
   * @param string mainClass Nome da classe principal do objeto
   * @param array $classes Nomes das classes que serao relacionadas 
   * @param array $chaves Chaves para serem utilizadas na consulta
   * @param string $ordem Ordenacao dos registros ( no formato sql sem ORDER BY)
   * @param RDParam $param : Parametros para montar a query
   */
  //  function RDRel($mainClass,$classes,$chaves="",$ordem="",$tipoJoin="",$distinct=0,$tabelas_nao_mostrar_campos="") {

  function RDRel($mainClass,$classes,$chaves="",$ordem="",$param="",$operacao=1,$paginada=0) {
    $this->RDCursor($mainClass);
    
    if (!in_array($mainClass,$classes)) {
      $classes = array_merge(array($mainClass),$classes);
    }
    
    $this->setObjClasses($classes);
    
    //cria um objeto RDParam
    if (empty($param)) {
      $this->param = new RDParam();
    }
    else {
      $this->param = $param;
    }

    //ordem
    if (!empty($ordem) && !$this->param->isDefined("ordem"))
      $this->param->setOrdem($ordem);
    
    //seta o tipo de projecao
    if (!$this->param->isDefined("flagTipoProjecao")) {
      //se o tipo de campos de projecao nao estiver definido entao usar o tipo default
      $this->param->setTipoProjecao(RDCURSOR_PROJ_DEFAULT);
    }
    
    $obj = new $mainClass;
    
    //pega as chaves estrangeiras da classe principal
    $obj = new $mainClass;
    $this->foreingKeys = $obj->getForeignKeys(); 
    $this->tabelas = $obj->getTables();
    
    //seta os campos de projecao de acordo com o tipo de projecao
    switch($this->param->getTipoProjecao()) {
    case RDCURSOR_PROJ_TODOS_CAMPOS:
      //pega todos os campos do objeto e coloca na projecao
      $campos = $obj->getFullFieldNamesOfDB();
      break;
    case RDCURSOR_PROJ_CAMPOS_LISTA:
      //pega apenas os campos marcados na definicao do objeto como devendo aparecer na listagem(DEFAULT)
      $campos = $obj->getCamposLista();
      break;
    case RDCURSOR_PROJ_NENHUM_CAMPO:
      $campos = array();
      break;
    }
    
    foreach($this->getObjClasses()  as $classe) {
      if ($classe!=$mainClass) {
	$obj = new $classe();
	$tabelas = $obj->getTables();
	if(!is_array($tabelas)) $tabelas = array($tabelas);
	
	$this->tabelas = array_merge($this->tabelas,$tabelas);
	
	//adiciona os campos chave das tabelas desta classe para os campo chave do rdrel
	foreach ($tabelas as $tabela) {
	  $camposChave = $obj->getKeyFieldsOfTable($tabela);
	  foreach ($camposChave as $campo) {
	    $this->pkFields[$campo][] = $tabela;
	  }
	}
	
	//seta os campos de projecao de acordo com o tipo de projecao de cada classe pertencente ao rdrel
	switch($this->param->getTipoProjecao()) {
	case RDCURSOR_PROJ_TODOS_CAMPOS:
	  //pega todos os campos do objeto e coloca na projecao
	  $campos = array_merge($campos,$obj->getFullFieldNamesOfDB());
	  break;
	case RDCURSOR_PROJ_CAMPOS_LISTA:
	  //pega apenas os campos marcados na definicao do objeto como devendo aparecer na listagem(DEFAULT)
	  $campos = array_merge($campos,$obj->getCamposLista());
	  break;
	}
	
	//adiciona os nomes dos campos
	//$campos = $obj->getFullFieldNamesOfDB();
	//$this->campos = array_merge($this->campos,$campos);
	
      }
    }

    if ($this->param->isDefined("camposProjecao")) {
      //se foi passado exlicitamente algum campo como devendo fazer parte da projecao entao fazer um merge dos campos
      $campos = array_merge($campos,$this->param->getCamposProjecao());
    }    
    $this->param->setCamposProjecao($campos);
    
    if ($this->param->getFlagSetForeignKeys())
      $this->setForeignKeys();

    if (!empty($chaves))
      $chaves = array_merge($this->foreignKeys,$chaves);
    //      $chaves = array_merge($chaves,$this->foreignKeys);
    else  
      $chaves = $this->foreignKeys;

    $this->param->setChaves($chaves);
    
    if ($operacao==1)
      $this->lista();
    
  }

  /**  Funcao que lista os registros baseados nas chaves. Se as chaves nao forem passadas como parametro le todos registros
   *
   *    @access public
   *    @param array $chaves Chaves para consulta
   */
  function lista() {
    
    $result = listaRegTabela($this->tabelas,$this->param);

    if ($result!=0) {
      $this->records = array();       //array dos objetos pertencentes a lista
      $indice = 0;
      foreach ($result as $reg) {	
        //instancia o objeto de acordo com a classe que foi passada como parametro na construcao
	
      	$obj = new $this->objClass();			    
	$obj->parseFieldsFromArray($reg,0);         //seta as propriedades do objeto (somente na mainClass)
		
	//seta a mao os campos das outras tabelas no objeto
       	foreach ($this->getCamposProjecaoSemNomesTabela() as $campo) {
	  if (!isset($obj->$campo)) {
	    $obj->$campo = $reg[$campo];
	  }
	}
	
        $obj->novo = 0;				  //o registro nao eh novo
	$obj->notifyDelete = &$this->records;     //"ponteiro" para a lista
	$obj->indiceArray = $indice;	      	  //indice do array
	$this->records[] = $obj;		  //coloca no array
	$indice++;
      }
    }
    else $this->records = 0;
    
    $this->setNumRecords(); //seta o numero de registros
    
}

  /**  Funcao que seta as chaves estrangeiras, setando os relacionamentos entre as classes
   *
   *   @access private
   */

  function setForeignKeys() {

    $campos_iguais = array();

    $tabs_join_ok = array();
    $chaves = array();
    
    if (!empty($this->pkFields)) {
      foreach ($this->pkFields as $field=>$tabelas) {
	//se ha mais de uma tabela com mesmo campo entao fazer chaves estrangeiras
	if (count($tabelas) > 1) {
	  $tab_main = array_shift($tabelas);	
	  $this->setKeysOfField($field,$tab_main,$tabelas,$tabs_join_ok,$chaves);
	}
      }
    }
    
    $this->foreignKeys = array_merge($this->foreignKeys,$chaves);
    
  }
  
  /** Funcao que seta todas as chaves estrangeiras para os relacionamentos das classes de determinado campo
   *
   *  @access private
   *  @param string $field Nome do campo
   *  @param string $tab_main Nome da tabela principal
   *  @param array $tables Tabelas que deverao ser relacionadas a tabela principal
   *  @param array $tabs_join_ok Tabelas em que ja foi feito join
   *  @param array $chaves Chaves ja geradas
   */

  function setKeysOfField($field,$tab_main,$tables,&$tabs_join_ok,&$chaves) {

    if (count($tables) > 0) {
      
      $tab2 = array_shift($tables);
        
      if (!in_array($tab2,$tabs_join_ok)) {
	//faz join da tabela principal com a tab2
	$chaves[] = opMVal($tab_main,$field,$tab2);
	$tabs_join_ok[] = $tab_main;
	$tabs_join_ok[] = $tab2;

      }
      else {
	//ja existe um join com a tab2, coloca na clausula where
	$chaves[] = opMVal($tab_main,$field,$tab2,$field,0);
      }

      //faz clausula where com os outras tabelas
      if (count($tables) > 0) {
	foreach ($tables as $table) {
	  $chaves[] = opMVal($tab2,$field,$table,$field,0);
	}
      }
      
      $this->setKeysOfField($field,$tab_main,$tables,$tabs_join_ok,$chaves);

    }
  }

  /** Procura no array de objetos se existe um objeto cujo campo tenha um valor específico
   *
   *  @access public
   *  @param string $field Nome do campo a ser procurado.
   *  @param string $value Valor a ser procurado.     
   */
  function searchObjs($field,$value) {

    if(!empty($this->records)) {
      foreach($this->records as $k=>$obj) {
	$tvalue = $obj->$field;
	if($tvalue==$value) {
	  return $k;
	}
      }
    }

    return -1;
  }

}

/* RENOMEACAO
 $this->objClasses = array();
 foreach ($classes as $classe) {
 if (strpos($classe," ")===FALSE) {
 //foi passado apenas o nome da classe
 $this->objClasses[] = $classe;
 }
 else {
 //foi passao o nome da classe mais o nome de uma renomeacao para esta tabela
	//isto permite uma tabela poder participar mais de uma vez num rdrel
	//tambem os nomes das propriedades do objeto que tem uma tabela renomeada terao seus nomes
	//comecando pelo nome da renomeacao, permitindo assim que campos com mesmos nomes em 
	//tabelas diferentes possam ser usados como campos distintos
	list($nomeClasse,$nomeRenomeado) = explode(" ",$classe);
	$this->objClasses[] = $nomeClasse;
	if (empty($this->renomeacao))
	$this->renomeacao = array();
	$this->renomeacao[$nomeClasse] = $nomeRenomeado;
	}
	}
*/    

?>