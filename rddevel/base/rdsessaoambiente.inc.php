<?

//include_once("$rdpath/rderro.inc.php");

$RD_Errors[] = "RD_SESSION_EMPTY";   //nenhuma sessao definida
$RD_Errors[] = "RD_SESSION_USER_EMPTY";  //nenhum usuario cadastrado na sessao aberta

/* Classe que guarda e pesquisa as sessões abertas pelo usuários quando se conecta
 *
 * Classe que guarda e pesquisa as sessões abertas pelo usuários quando se conecta.
 * Ela é importante para que se possa recuperar os usuários atualmente conectados dentro
 * de um  ambiente.  Toda a vez que o usuário acessar um página, realizar um refresh dentro
 * dentro do ambiente, que por sua vez atualiza o objeto de sessão corrente marcando o datFim com 
 * o tempo corrente.
 * Quando se cria uma nova seção ela verifica as sessões que já estão abertas, se existe um
 * cujo datFim mais antigo que um limiar(configurável), essa sessão é morta.
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
   * Primeiro verifica se não se trata de uma sessão a ser pesquisa através de campos. Se campos estiver vazio
   * então é uma nova sessão, e nesse caso grava com os dados obtidos atraves do ambiente.
   *
   * @param mixed $chave Chave que define o valor da chave primária para carregar o objeto. Caso vazio é uma nova instância.
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


  /* Atualiza a sessão no banco de dados
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

  /* Termina a sessão atual
   */
  function termina() {
    $this->datFim = time();
    $this->flaEncerrada = 1;
    $this->salva();
  }

  /* Acaba com as sessões zumbies que restaram de pessoas que não deram logou(a maioria delas faz isso)
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