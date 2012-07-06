<?

define("RDCURSOR_PROJ_TODOS_CAMPOS",1);
define("RDCURSOR_PROJ_CAMPOS_LISTA",2);
define("RDCURSOR_PROJ_NENHUM_CAMPO",3);
define("RDCURSOR_PROJ_DEFAULT",RDCURSOR_PROJ_TODOS_CAMPOS);

class RDCursor {
  
  var $tabelas;
  var $campos;
  var $objClass;
  var $objClasses;
  var $records;
  var $numRecs;
  var $regAtual;
  var $tipoJoin;
  var $foreignKeys;
  var $param;
  
  function RDCursor($objClass) {
    $this->records = 0;
    $this->regAtual = 0;
    $this->objClass = $objClass;
  }

  /** Executa a query, retornando apenas o numero de linhas 
   *
   */
  function getCount() {
    
    $tipoProjecao = $this->param->getTipoProjecao();
    $camposProjecao = $this->getCamposProjecao();
    $this->setCamposProjecao(array("COUNT(*) numeroLinhas")); 
    $this->lista();
    $count = $this->records[0]->numeroLinhas;
    
    //restaura as configuracoes de projecao anteriores
    $this->param->setTipoProjecao($tipoProjecao);
    if (!empty($camposProjecao))
      $this->setCamposProjecao($camposProjecao);
    
    return $count;
  }
  
  function getCamposProjecao() {
    return $this->param->getCamposProjecao();
  }

  function setCamposProjecao($campos) {
    $this->param->setCamposProjecao($campos);
  }

    
  /** Retorna os campos de projecao sem os nomes da tabela
   *  
   */
  function getCamposProjecaoSemNomesTabela() {
    return $this->param->getCamposProjecaoSemNomesTabela();
  }

  function setObjClasses($classes) {
    $this->objClasses = $classes;
  }

  function getObjClasses() {
    return $this->objClasses;
  }

  function setObjClass($objClass) {
    $this->objClass = $objClass;
  }

  function getObjClass() {
    return $this->objClass;
  }

  function setForeignKeys($foreignKeys) {
    $this->foreignKeys = $foreignKeys;
  }
  
  function getForeignKeys() {
    return $this->foreignKeys;
  }
  
  function getTabelas() {
    return $this->tabelas;
  }

  function setTabelas($tabelas) {
    $this->tabelas = $tabelas;
  }

  function getCampos() {
    return $this->campos;
  }

  function setCampos($campos) {
    $this->campos = $campos;
  }
  
  function setNumRecords() {
    if (empty($this->records))
      $this->numRecs = 0;
    else
      $this->numRecs = count($this->records);
  }
  
  function numRecords() {
    return $this->numRecs;
  }

  function setTipoJoin($tipoJoin) {
    $this->tipoJoin = $tipoJoin;
  }

  function getTipoJoin() {
    return $this->tipoJoin;
  }

  /** Retorna o objeto(registro) atual
   *
   */
  function objAtual() {
    return $this->records[$this->regAtual];
  }
  
  /** Avanca o ponteiro de registro atual e retorna o proximo registro
   *
   */
  function next() {
    $this->regAtual = $this->regAtual + 1;
    if ($regAtual < $this->numRecords()) {
      return $this->objAtual();
    }
    else {
      $this->regAtual = $this->regAtual - 1; 
      return null;
    }
  }

  /** Posiciona o registro atual no primeiro elemento e retorna ele
   *
   */
  function first() {
    $this->regAtual = 0;
    return $this->objAtual();
  }
  
  /** Posiciona o registro atual no ultimo elemento e retorna ele
   *
   */
  function last() {
    $this->regAtual = $this->numRecords() - 1;
    return $this->objAtual();
  }

  /** Posiciona o registro atual em $pos e retorna este registro
   *
   */
  function seek($pos) {
    if ($pos <= $this->numRecords()) {
      $this->regAtual = $pos-1;
      return $this->objAtual();
    }
    else
      return null;
  }

  /** Adiciona um elemento ao cursor
   *
   */
  function add($object) {
    if (!is_array($this->records))
      $this->records = array();
    $this->records[] = $object;
    $this->numRecs++;
  }
  
}

?>