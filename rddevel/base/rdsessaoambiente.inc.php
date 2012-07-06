<?

//include_once("$rdpath/rderro.inc.php");

$RD_Errors[] = "RD_SESSION_EMPTY";   //nenhuma sessao definida
$RD_Errors[] = "RD_SESSION_USER_EMPTY";  //nenhum usuario cadastrado na sessao aberta

/* Classe que guarda e pesquisa as sess�es abertas pelo usu�rios quando se conecta
 *
 * Classe que guarda e pesquisa as sess�es abertas pelo usu�rios quando se conecta.
 * Ela � importante para que se possa recuperar os usu�rios atualmente conectados dentro
 * de um  ambiente.  Toda a vez que o usu�rio acessar um p�gina, realizar um refresh dentro
 * dentro do ambiente, que por sua vez atualiza o objeto de sess�o corrente marcando o datFim com 
 * o tempo corrente.
 * Quando se cria uma nova se��o ela verifica as sess�es que j� est�o abertas, se existe um
 * cujo datFim mais antigo que um limiar(configur�vel), essa sess�o � morta.
 * e aquelas que 
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage base
 * @see RDObj, RDUser, RDCurso
 */
class RDSessaoAmbiente extends RDObj {

    var $pkFields = "codSessionId";


  /* Construtor do Objeto
   *
   * Primeiro verifica se n�o se trata de uma sess�o a ser pesquisa atrav�s de campos. Se campos estiver vazio
   * ent�o � uma nova sess�o, e nesse caso grava com os dados obtidos atraves do ambiente.
   *
   * @param mixed $chave Chave que define o valor da chave prim�ria para carregar o objeto. Caso vazio � uma nova inst�ncia.
   * @return object RD_Error Retorna caso um erro tenho ocorrido
   * @see RD_Ambiente, RD_Error, RD_Obj
   */
  function RDSessaoAmbiente($chave="") {
    global $config_ini;

    
    $this->RDObj($this->getTables(),$this->getFields(), $this->pkFields,$chave);

    return 1;
  }

  function setFromAmbiente() {
    global $config_ini;
    
    
    $this->codSessionId = session_id();
    
    if(!empty($_SESSION[usuario])) {
      $this->codUser = $_SESSION[usuario]->codUser;
    }
    else {
      return new RDError("RD_SESSION_USER_EMPTY");
    };
    

    $this->datInicio = time();
    $this->datFim = time() + $config_ini[Finder][timeout];
    $this->tempo = time();
    $this->desIP = $_SERVER['REMOTE_ADDR'];
    $this->codPlataforma = $config_ini[Ambiente][plataforma_cod];
    $this->salva();


  }


  /* Atualiza a sess�o no banco de dados
   */
  function atualiza()  {
    global $config_ini;


    //evita que quando por algum acaso remova-se os cookies do browser crie um cadastro fantasma
    if(empty($this->codSessionId)) {
      return 0;
    };
 
    $this->datFim = time() + $config_ini[Finder][timeout];
    $this->flaEncerrada = 0;
    $this->salva();

    $this->terminaSessoesMortas();
  }

  /* Termina a sess�o atual
   */
  function termina() {
    $this->datFim = time();
    $this->flaEncerrada = 1;
    $this->salva();
  }

  /* Acaba com as sess�es zumbies que restaram de pessoas que n�o deram logou(a maioria delas faz isso)
   */

  function terminaSessoesMortas() {

    $chaves[] = opVal("datFim",time(),"","<");
    $chaves[] = opVal("flaEncerrada","0");

    $lista = new RDLista("RDSessaoAmbiente",$chaves,"");

    if(!empty($lista->records)) {
      foreach($lista->records as $key=>$item) {
	$lista->records[$key]->termina();   
      };
    };
  }

  function getTables() {
    return "Ambiente_Sessoes";
  }

  function getFields() {
    return array("codSessionId","codUser","codPlataforma","datInicio","datFim","desIP", "flaVisibilidade", "flaEncerrada");
  }


}




?>