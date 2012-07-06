<?
/**
 */

include_once("$rdpath/rdcursor.inc.php");

/** A classe RDLista implementa atraves do uso de RDObj a listagem de varios registros do bco de dados, tratando cada
  * um deles com um objeto RDObj
  * @author Maicon Brauwers <maicon@edu.ufrgs.br>
  * @access public
  * @version 0.5
  * @package rddevel
  * @subpackage base
  * @see RDObj
*/
class RDLista extends RDCursor {
  /**
   * @var string $tabelas Nome das tabelas
   * @var array campos Campos da tabela
   * @var string $objClasss O nome da classe de objeto sobre a qual sera feita a consulta 
   * @var array records Conjunto dos objetos retornados pela consulta
   * @var integer $numRecs Retorna o numero de registros retornados
   * @var RDCursorParam Parametros a serem passados para a query
   */
  //  var $tabela,$campos,$objClass,$records,$numRecs;
  //  var $param;
    var $records = array();

  /** Funcao construtora
   * @access public
   * @param string $class Nome da classe de objeto
   * @param array $chaves Chaves para serem utilizadas na consulta
   * @param string $ordem Ordenacao dos registros ( no formato sql sem ORDER BY)
   * @param RDParam Parametros a serem passados na query. 
   */
    
    function RDLista($classes,$chaves="",$ordem="",$param="",$operacao=1,$keyValueIsRecordIndex=0) {
        
        if(!is_array($classes)) {
            $classes = array($classes);
        }
        $class = $classes[0];

        $this->RDCursor($class);  //construtor da classe pai
        $obj               = new $class();
    //seta as chaves estrangeiras, que sao as chaves estrangeiras do objeto
        $this->foreignKeys = $obj->getForeignKeys();
		if(!is_array($this->foreignKeys)) {
		    $this->foreignKeys = array();
		}
        
    //cria um objeto RDParam
        if (empty($param))
        $this->param = new RDParam();
        else
        $this->param = $param;

        if (!$this->param->isDefined("flagTipoProjecao")) {
      //se o tipo de campos de projecao nao estiver definido entao usar o tipo default
            $this->param->setTipoProjecao(RDCURSOR_PROJ_DEFAULT);
        }
        
        $campos = array();
    //pega as tabelas de cada objeto
        foreach($classes as $class) {
            $obj               = new $class();

            foreach($obj->tabelas as $item) {
                $this->tabelas[] = $item;
            }

        }
        
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
        
        if ($this->param->isDefined("camposProjecao")) {
      //se foi passado exlicitamente algum campo como devendo fazer parte da projecao entao fazer um merge dos campos
            $campos = array_merge($campos,$this->param->getCamposProjecao());
        }
        $this->param->setCamposProjecao($campos);

        if (!empty($ordem) && !$this->param->isDefined("ordem"))
        $this->param->setOrdem($ordem);

        
        if (!empty($chaves)) {
            $chaves = array_merge($chaves,$this->foreignKeys);
        }
        else {
            $chaves = $this->foreignKeys;
        }
		
        $this->param->setChaves($chaves);

        if ($operacao==1) {
            if ($chaves!=-1)
            $this->lista($keyValueIsRecordIndex);
        }

    }

  /**  Funcao que lista os registros baseados nas chaves. Se as chaves nao forem passadas como parametro le todos registros
   *    @access public
   *    @param int $keyValueIsRecordIndex Se true entao o indice do array de registros eh o valor da (unica) chave do objeto
   */
    function lista($keyValueIsRecordIndex=0) {

        $result = listaRegTabela($this->tabelas,$this->param);

        $obj = new $this->objClass();

        if ($keyValueIsRecordIndex) {
            $camposChaves = $obj->getKeyFieldsNames();
            if (count($camposChaves) == 1) {
                $campoChave = $camposChaves[0];
            }
            else {
                $keyValueIsRecordIndex = 0;
            }
        }

        if ($result!=0) {
            $this->records = array();       //array dos objetos pertencentes a lista
            $indice = 0;
            foreach ($result as $reg) {
        //instancia o objeto de acordo com a classe que foi passada como parametro na construcao
                
                $obj = new $this->objClass();
                $obj->parseFieldsFromArray($reg,0);         //seta todas propriedades do objeto
                $obj->novo = 0;				  //o registro nao eh novo
                $obj->notifyDelete = &$this->records;     //"ponteiro" para a lista
                
                if ($keyValueIsRecordIndex) {
                    $valorCampoChave = $obj->$campoChave;
                    $obj->indiceArray = $valorCampoChave;
                    $this->records[$valorCampoChave] = $obj;
                }
                else {
	  //coloca no array
                    $obj->indiceArray = $indice;	      	  //indice do array
                    $this->records[] = $obj;
                    $indice++;
                }
                
            }
            
            $this->numRecs = count($this->records);

        }
        else {
            $this->records = array();
            $this->numRecs = 0;
        }
    }
    

  /** Funcao que faz um update num conjunto inteiro de registros
   *
   *  @access public
   *  @param array $valores Valores a serem atualizados
   *  @param array $chaves Chaves para indentificar quais registros serao atualizados      
   */
    function update($valores,$chaves,$quotes=1) {
        $tabelas = $this->tabelas;
        $tabela = array_pop($tabelas);
        atualizaRegTabela($tabela,$valores,$chaves,$quotes);
    }

  /** Funcao que deleta update num conjunto inteiro de registros
   *
   *  @access public
   *  @param array $valores Valores a serem atualizados
   *  @param array $chaves Chaves para indentificar quais registros serao atualizados      
   */
    function delete($chaves="") {
        $tabelas = $this->tabelas;
        $tabela = array_pop($tabelas);

        if(empty($chaves)) {
            $chaves = $this->param->getChaves();
        }
        delRegTabela($tabela,$chaves);
    }



    function indexBy($field) {
        $this->indexes[$field] = array();


        if(!empty($this->records)) {
            foreach($this->records as $k=>$obj) {
                $this->indexes[$field][$obj->$field] = $k;
            }
        }

        return 0;
    }


  /** Procura no array de objetos se existe um objeto cujo campo tenha um valor específico
   *
   *  @access public
   *  @param string $field Nome do campo a ser procurado.
   *  @param string $value Valor a ser procurado.     
   */
    function searchObjs($field,$value) {

        if(!empty($this->indexes[$field])) {
            if(!empty($this->indexes[$field][$value])) {
                return $this->indexes[$field][$value];
            }

            return -1;
        }

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

?>