<?

/** Classe usada para fornecer parametros para objetos do tipo RDCursor, como RDLista e RDRel
 * @author Maicon Brauwers <maicon@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage base
 * @see RDLista,RDRel
 *
 */
class RDParam {

  /**
   *  @var array private $paramDefined : Os parametros que foram definidos
   *  @var string private $ordem :  A ordem em que os registros devem ser consultados ( ORDER BY)
   *  @var string private $tipoJoin : O tipo de juncao 
   *  @var int private $startRow : A linha inicial a ser retornada numa limitQuery
   *  @var int private $numOfRows : O numero de linhas que deve ser retornada numa limitQuery
   *  @var int private $flagTipoProjecao : O tipo de projecao usado
   *  @var array private $tabelasNaoMostrarCampos : Nomes das campos das tabelas que nao devem aparecer na projecao do select. (DEPRECATED)
   *  @var int private $distinct : Se o select eh distinct ou nao
   */
  
  var $paramDefined;
  var $ordem;
  var $tipoJoin;
  var $startRow;
  var $numOfRows;
  var $flagTipoProjecao;
  var $camposProjecao;
  var $tabelasNaoMostrarCampos;
  var $distinct;
  var $flagSetForeignKeys;
  var $chaves;
  var $sqlWhere; //sql : uma sql qualquer que devera ser acrescentada a sql que for gerada pelo cursor
  
  function RDParam() {
    $this->paramDefined = array();
    $this->flagSetForeignKeys(1);
  }
  
  /** Uma sql (
   *
   */
  function setSqlWhere($sql) {
    $this->sqlWhere = $sql;
    if (!empty($this->paramDefined)) {
      if (!in_array("sqlWhere",$this->paramDefined))
	$this->paramDefined[] = "sqlWhere";
    }
    else
      $this->paramDefined = array("sqlWhere");
  }
  
  function getSqlWhere() {
    return $this->sqlWhere;
  }
  
  function setChaves($chaves="") {
    $this->chaves = $chaves;
    if (!empty($this->paramDefined)) {
      if (!in_array("chaves",$this->paramDefined))
	$this->paramDefined[] = "chaves";
    }
    else
      $this->paramDefined = array("chaves");
  }

  function getChaves() {
    return $this->chaves;
  }

  /** Retorna se o parametro esta definido
   *  @param string $param : Nome do parametro
   *  @return int 
   */
  function isDefined($param) {
    if (!empty($this->paramDefined))
      return in_array($param,$this->paramDefined);
    else
      return 0;
  }
 
  /** Seta se o rdcursor(rdrel) devera gerar automaticamente as chaves estrangeiras
   *
   */
  function flagSetForeignKeys($flag) {
    $this->flagSetForeignKeys = $flag;
    
    if (!empty($this->paramDefined)) {
      if (!in_array("flagSetForeignKeys",$this->paramDefined))
	$this->paramDefined[] = "flagSetForeignKeys";
    }
    else
      $this->paramDefined = array("flagSetForeignKeys");
  }
  
  function getFlagSetForeignKeys() {
    return $this->flagSetForeignKeys;
  }
  
  /** Seta a ordem 
   *  @param string $ordem : Ordem da consulta
   */
  function setOrdem($ordem) {
    $this->ordem = $ordem;
    if (!empty($this->paramDefined)) {
      if (!in_array("ordem",$this->paramDefined))
	$this->paramDefined[] = "ordem";
    }
    else
      $this->paramDefined = array("ordem");
  }

  /** Retorna a ordem 
   *
   */
  function getOrdem() {
    return $this->ordem;
  }

  /** Seta o tipo de juncao (INNER JOIN,LEFT OUTER JOIN,...)
   *
   */
  function setTipoJoin($tipoJoin) {
    $this->tipoJoin = $tipoJoin;
    if (!empty($this->paramDefined)) {
      if (!in_array("tipoJoin",$this->paramDefined))
	$this->paramDefined[] = "tipoJoin";
    }
    else
      $this->paramDefined = array("tipoJoin");
  }
  
  /** Retorna o tipo de juncao
   *
   */
  function getTipoJoin() {
    return $this->tipoJoin;
  }
  
  /** Seta a linha inicial de uma limitQuery
   *
   */
  function setStartRow($startRow) {
    $this->startRow = $startRow;
    if (!empty($this->paramDefined)) {
      if (!in_array("startRow",$this->paramDefined))
	$this->paramDefined[] = "startRow";
    }
    else
      $this->paramDefined = array("startRow");
  }

  /** Retorna a linha inicial de uma limitQuery
   *
   */
  function getStartRow() {
    return $this->startRow;
  }
  
  /** Seta o numero de linhas de uma limitQuery
   *
   */
  function setNumOfRows($numOfRows) {
    $this->numOfRows = $numOfRows;
    if (!empty($this->paramDefined)) {
      if (!in_array("numOfRows",$this->paramDefined))
	$this->paramDefined[] = "numOfRows";
    }
    else
      $this->paramDefined = array("numOfRows");
  }

  /** Retorna o numero de linhas de uma limitQuery
   *
   */
  function getNumOfRows() {
    return $this->numOfRows;
  }

  /**Configura a query como sendo uma limitQuery
   * 
   * @param int $startRow : Linha inicial
   * @param int $numOfRows : Numero de linhas
   *
  */
  function setLimitQuery($startRow,$numOfRows) {
    $this->setStartRow($startRow);
    $this->setNumOfRows($numOfRows);
  }

  /** Seta o tipo de projecao
   *  $param int $flagTipoProjecao : O tipo de projecao
   *    O tipo de projecao pode ser o seguinte : 
   *      RDCURSOR_PROJ_TODOS_CAMPOS -> todos os campos de todas as tabelas do rdlista ou rdrel
   *      RDCURSOR_PROJ_CAMPOS_LISTA -> apenas os campos que foram definidos no RDObj como devendo aparecer em listagens
   *      RDCURSOR_PROJ_NENHUM_CAMPO -> nenhum campo devera aparecer. util quando se deseja colocar na mao apenas um ou dois
   *                                     campos na projecao.
   *
   */
  function setTipoProjecao($flagTipoProjecao) {
    $this->flagTipoProjecao = $flagTipoProjecao;
    if (!empty($this->paramDefined)) {
      if (!in_array("flagTipoProjecao",$this->paramDefined))
	$this->paramDefined[] = "flagTipoProjecao";
    }
    else
      $this->paramDefined = array("flagTipoProjecao");
  }
  
  /** Retorna o tipo de projecoa
   *
   */
  function getTipoProjecao() {
    return $this->flagTipoProjecao;
  }

  /** Seta os campos que deverao aparecer na projecao ( alem daqueles que ja aparecerao dependendo do tipo de projecao usado
   *
   */
  function setCamposProjecao($camposProjecao,$tipoProjecao=RDCURSOR_PROJ_NENHUM_CAMPO) {
    $this->setTipoProjecao($tipoProjecao);
    $this->camposProjecao = $camposProjecao;
    if (!empty($this->paramDefined)) {
      if (!in_array("camposProjecao",$this->paramDefined))
	$this->paramDefined[] = "camposProjecao";
    }
    else
      $this->paramDefined = array("camposProjecao");
  }

  /** Retorna os campos projecao setados explicitamente
   *
   */
  function getCamposProjecao() {
    return $this->camposProjecao;
  }
  
  /** Retorna os nomes dos campos sem os nomes das tabelas  
   */
  function getCamposProjecaoSemNomesTabela() {
    $campos = $this->getCamposProjecao();
    $camposSemNomeTabela = array();
    foreach ($campos as $campo) {
      $pos = strpos($campo,".");
      if ($pos===FALSE)
	$camposSemNomeTabela[] = $campo;
      else
	$camposSemNomeTabela[] = substr($campo,$pos+1);
    }
    return $camposSemNomeTabela;
  } 
  
  function setTabelasNaoMostrarCampos($tabelasNaoMostrarCampos) {
    $this->tabelasNaoMostrarCampos = $tabelasNaoMostrarCampos;
    if (!empty($this->paramDefined)) {
      if (!in_array("tabelasNaoMostrarCampos",$this->paramDefined))
	$this->paramDefined[] = "tabelasNaoMostrarCampos";
    }
    else
      $this->paramDefined = array("tabelasNaoMostrarCampos");
  }

  function getTabelasNaoMostrarCampos() {
    return $this->tabelasNaoMostrarCampos;
  }

  /** Seta a select como sendo distinct
   *
   */
  function setDistinct($distinct=1) {
    $this->distinct = $distinct;
    if (!empty($this->paramDefined)) {
      if (!in_array("distinct",$this->paramDefined))
	$this->paramDefined[] = "distinct";
    }
    else
      $this->paramDefined = array("distinct");
  }

  /** Retorna se eh distict ou nao
   *
   */
  function getDistinct() {
    return $this->distinct;
  }

   
}

?>