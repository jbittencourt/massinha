<?

include_once("$rdpath/base/rduser.inc.php");
include_once("$rdpath/base/rdsessaoambiente.inc.php");

/**
 * Classe principal que possui os dados do ambiente sendo executado
 *
 * O RDAmbiente é uma das classes mais importantes do rddevel pois comtém
 * as funções necessárias para inicialização, e gerenciamento do de uma plataforma
 * para EAD. Toda a vez que um usuário acessa um site contruído com o rd->devel, o script
 * config.inc.php se encarrega de criar uma nova instância do RDAmbiente e coloca-la na
 * variável $_SESSION[ambiente]. As funções mais comumente utilizadas são as de autenticação, e suporte
 * a várioas linguagens.
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage base
 * @see RDObj, RDUser, RDCurso
 */
class RDAmbiente {


  /**
   * @var int $logSession Informa ao ambiente se este deve manter o log de acessos dos usuários.
   */
  var $logSession;

  var $logado,$browserNome,$browserVersao,$language;
  var $fileLanguage, $sessao, $userClass, $userClassBib;
  
  /**
   * Construtor do objeto.
   *
   * @param string $login Nome do usuário que está se logando no sistema.
   * @param string $senha Senha do usuário
   * @param string $url_go_to_erro A url para onde a plataforma deve ser redirecionada em caso de falha no logon.
   */
  function RDAmbiente($login="",$senha="",$url_go_to_erro="",$logsession=1) {
    global $PHPSESSID;
    global $config_file;

    $this->detectaBrowser();
    $this->logSession = $logsession;
        
    $this->logado = 0;
    
    if (!empty($login)) {			  		//autenticando pela primeira vez
      $result = $this->login($login,$senha);   //faz o login
      if ($result==1) {//usuario ou senha invalidas
	
	if (strpos($url_go_to_erro,"?") === false)
	  $loc = $url_go_to_erro."?frm_error=auth_error";
	else
	  $loc = $url_go_to_erro."&frm_error=auth_error";
	
	Header("Location: ".$loc);

	exit;
	return;
      }
      elseif ($result==2 ) {   				//acesso negado 
	Header("Location:"."./denied.php");  
        return;
      };
    };


  }

  /**
   * Altera a classe a ser utilizada em $_SESSION[usuario]
   *
   * Altera a classe a ser utilizada em $_SESSION[usuario]. Essa classe deve
   * obritatoriamente ser subclasse de rduser.
   *
   * @param string $classe Nome da classe.
   * @param string $biblio Biblioteca em que a classe está definida.
   * @see rduser
   */
  function setUserClass($classe,$biblio) {
    $this->userClass = $classe;
    $this->userClassBib = $biblio;
  }

  /**
   * Realiza o logon do usuário no sistema.
   *
   * Realiza o logon do usuário no sistema. Em caso de sucesso retorna 1, e
   * seta a váriavel $_SESSION[usuario], carregando a intância do objeto RDUser. É possível
   * setar uma nova classe para ser instânciada no lugar de RDUser. Para tal o nome da classe
   * deve ser setada em $rdambiente->setUserClass. Além disse essa classe deve obrigatóriamente ser 
   * subclasse de rduser.
   *
   * @param string $login Nome do usuário a ser autênticado.
   * @param string $senha Senha do usuário em um string não encriptado.
   */
  function login($login,$senha) {
    global $pathuserlib,$config_ini;
    
    $chave[] = opVal("nomUser",$login);
    $chave[] = opVal("desSenha",md5($senha));
    
    //cria um usuário do sistema
    //O usuário default é RDUser, mas se ouver algo definido em
    // userClass então utiliza outra classe
    // no entanto essa classe deve estender RDUser
    //

    if(empty($this->userClass)) {
      $_SESSION[usuario] = new RDUser($chave);				//le usuario  
    }
    else {
      include_once("$this->userClassBib");
      $_SESSION[usuario] = eval("return  new ".$this->userClass."(\$chave);");
    };
    
    $erro = 0;

        if ((!$_SESSION[usuario]->novo))  {       				//usuario e senha conferem
      $_SESSION[sessId]   = session_id(); 		
      $_SESSION[remoteIP] = $_SERVER[REMOTE_ADDR];
    
      $sucesso = $this->autentica();              //autentica usuario,cria as variaveis de sessao   
      if (!$sucesso) {  		
	unset($_SESSION[usuario]);
	unset($_SESSION[sessId]);		
	unset($_SESSION[remoteIP]);
	unset($_SESSION[ambiente]);
      }
      else {
	$this->logado=1;
      };
     
    }
    else {
      $erro = 1;					//usuario inexistente    
      unset($_SESSION[usuario]);
    }
    

    return $erro;					  
  }	

  /**
   * Autentica um usuário no ambiente.
   *
   * Esse função é chamada toda a vez em que um usuário logado acessa uma página no ambiente. Os
   * objetivos são dois. Em primeiro lugar garantir que o usuário do objeto seja o mesmo que se encontra
   * descrito no banco de dados. Isso é realizado conferindo o nome de usuário e a senha contra aquelas
   * registradas no BD. Em segundo lugar, atualizar o registro da sessão do usuário dentro do ambiente,
   * com o objetivo de saber a hora exata da último acesso do usuário no sistema, e se este ainda 
   * encontra-se logado.
   *
   * @see rdsessaoambiente
   */
  function autentica() {
    global $pathuserlib; 

    if (!isset($_SESSION[usuario])) { // || !isset($_SESSION[sessId]) || !isset($_SESSION[remoteIP])) {
      return 0;
    };
    /*    elseif ($_SESSION[sessId] != session_id() || $_SESSION[remoteIP] != $_SERVER[REMOTE_ADDR]) {
     
      return 0;
    } */

    if ($this->logSession) {

      if(!empty($_SESSION[sessao])) {
	$_SESSION[sessao]->atualiza();
      } 
      else {
	$_SESSION[sessao] = new RDSessaoAmbiente();
	$_SESSION[sessao]->setFromAmbiente();
      };

    }
    return 1;
  }
  
  /**
   * Desloga o usuário do ambiente.
   */
  function logout($urlRedir="") {
    global $url;

    if (empty($urlRedir))
      $urlRedir = $url;
    
    if ($this->logSession) {
      if(!empty($_SESSION[sessao])) {
	$_SESSION[sessao]->termina();
      };
    }

    session_unset();
    setcookie (session_name(), '', (time () - 2592000), '/', '', 0);
    session_destroy();


    header("Location: $urlRedir");
    
  }
  
  /**
   * Listas todos os usuário cadastrados no sistema.
   *
   * @return object Retorna um RDLista com todos os usuários cadastrados no sistema.
   * @see rdlista,rduser
   * @todo criar um configuração em que só os usuários ativos apareçam
   */
  function listaUsuarios() {
    $lista = new RDLista("RDUser");  
    return $lista;    
  }

  /**
   * Listas todos os cursos cadastrados no sistema.
   *
   * @return object Retorna um RDLista com todos os usuários cadastrados no sistema.
   * @see rdlista,rdcurso
   */
  function listaCursos() {
    global $rdpath;
    include_once("$rdpath/base/rdcurso.inc.php");


    $lista = new RDLista("RDCurso");
    return $lista;  
  }

  function listaCursosAtivos() {
    global $tabelaCurso,$camposCurso;
    $chaves[datFim] = opVal("<",time());
    $lista = new RDLista("RDCurso",$chaves);
    return $lista;  
  }
    
  /**
   * Retorna um array com as definições de tema do ambiente.
   *
   * @return array Retorna um array com as definições de imagens do ambiente.
   * @decrepted
   */
  function getTema() {
    global $config_ini;
     
    $paths = $config_ini[Diretorios];
    $urls = $config_ini[Internet];
    $temas = $config_ini[Temas];
     
    $tema = $temas["default"];
    $def = $temas[$tema];
    $tema_file = parse_ini_file("$paths[pathtemas]/$tema/$def",TRUE);
     
    $tema_file[Info][url] = "$urls[urltemas]$tema/";
     
    return $tema_file;
  }
  

  /**
   * Detecta qual o browser sendo utilizado.
   *
   * Essa função detecta qual o browser que está sendo utilizado no sistema decompondo as i
   * informações contidas na variável $_SERVER[HTTP_USER_AGENT]. O resultados são colocados nas
   * variáveis rdambiente->browserNome e rdambiente->browserversao.
   *
   * @todo Obter maiores informações sobre o browser, como plugins do flash, etc.
   */
  function detectaBrowser() {
    
    $partes = split(" ",$_SERVER[HTTP_USER_AGENT]);
    $temp =  split("/",$partes[0]);
    $this->browserNome = $temp[0];
    $this->browserVersao = $temp[1];      
    $this->setLanguage();
    
    if(($this->browserNome=="Mozilla")&&($this->browserVersao=="4.0")) {
      $this->browserNome = "MSIE"; $this->browserVersao = 5.5; 
    };
    
  }


  /**
   * Pega a linguagem corrente
   *
   * A partir da linguagem definida pelo browser que está em RDAmbiente->language,
   * retorna o nome da língua.
   *
   * @return string Nome da língua.
   */
  function getLingua() {
    $partes = split("-",$this->language);    
    return $partes[0];
  }
  

  /** 
   * Pega a localizacao da língua, se houver
   *
   * A partir da linguagem definida pelo browser que está em RDAmbiente->language, retorna a localizacao.
   *
   * @return string Localizacao da língua.
   */
  function getLocalizacao() {
    $partes = split("-",$this->language);    
    return $partes[1];
  }


  /**
   * Inicializa o suporte a várias línguas
   *
   * Procura o primeiro arquivo de linguagem da lista de linguagem do usuario
   * se nao achar entao usa o default em config.ini. Se não houver default
   * utiliza pt-br
   *
   * @return integer Retorna 1 para sucesso 0 para falha e seta $rd_errors.
   */
  function setLanguage() {
    global $config_ini;
    $achou = 0;

    //deteccao da linguagem configurada pelo browser    
    $partes_langs    = split(";",$_SERVER[HTTP_ACCEPT_LANGUAGE]);
    $languages = split(", ",$partes_langs[0]);      

    
    if (!empty($languages)) {
      foreach ($languages as $lang) {
	$filename = $config_ini[Diretorios][pathlang].strtolower($lang).".lang";
	if (file_exists($filename)) {
	  $this->language = $lang;
	  $this->fileLanguage = $filename;
	  $achou = 1;
	  return 1;
	}
      }      
      
      if (!$achou) {
	$this->language = $config_ini[Linguagem]["default"];
	if(empty($this->language)) $this->language = "pt-br";
	$this->fileLanguage = $config_ini[Diretorios][pathlang]."/$this->language.lang";
	return 0;
      }
    }
  }


  /**
   * Retorna o array associativo com as variaveis de linguagem dessa unidade de interface
   *
   * Cada unidade de interface normalmente esta inserido dentro de uma arvore de altura 4
   * onde cada unidades tem como pai uma unidade virtual chamada geral, depois uma subdivisão
   * por grande unidades que tem filhos. Nesses filhos existem acoes que definem mais mensagens.
   * Essa funcao exime o programador de montar essa hieraquia a mão e faz o trabalho por ele.
   *
   * @param class $ui Classe RDUI que define o objeto de interface
   * @return array Array associativo com as mensagens da árvore da UI.
   */
  function getLangUI($ui) {
    global $language,$RD_DEVEL_GLOBAL;

    $temp = array();

    if(!empty($language[geral])) $temp = $language[geral];
    if(!empty($language[localization])) $temp = array_merge($temp,$language[localization]);

    $req_lang = $RD_DEVEL_GLOBAL[lang][required];
    if(!empty($req_lang)) {
      foreach($req_lang as $req) {
	if(!empty($language[$req])) $temp = array_merge($temp,$language[$req]);
      }
    }

    $nome = $ui->getGroupName();

    if(!empty($language[$nome]))  $temp = array_merge($temp,$language[$nome]);
    if(!empty($language[$ui->nome])) $temp = array_merge($temp,$language[$ui->nome]);
 
    return $this->normalizeLang($temp);
  }


  /**
   * Normaliza as mensagens da linguagem para o padrão de acentos do HTML
   *
   * Normaliza as mensagens da linguagem para o padrão de acentos do HTML
   *
   * @access private
   * @param array $lang Array das mensagens
   * @return array Array Array normalizado para o formato de caracteres internacionais do HTML
   */
  function normalizeLang($lang) {

  
    //letras minusculas
    $convert_chars["ç"] = "&ccedil;";
    $convert_chars["ñ"] = "&ntilde;";
    $convert_chars["ã"] = "&atilde;";
    $convert_chars["õ"] = "&otilde;";
    $convert_chars["á"] = "&aacute;";
    $convert_chars["é"] = "&eacute;";
    $convert_chars["í"] = "&iacute;";
    $convert_chars["ó"] = "&oacute;";
    $convert_chars["ê"] = "&ecirc;";
    $convert_chars["ô"] = "&ocirc;";
      
    //maiusculas
    $convert_chars["Ç"] = "&Ccedil;";
    $convert_chars["Ñ"] = "&Ntilde;";
    $convert_chars["Ã"] = "&Atilde;";
    $convert_chars["Õ"] = "&Otilde;";
    $convert_chars["Á"] = "&Aacute;";
    $convert_chars["É"] = "&Eacute;";
    $convert_chars["Í"] = "&Iacute;";
    $convert_chars["Ó"] = "&Oacute;";
    $convert_chars["Ê"] = "&Ecirc;";
    $convert_chars["Ô"] = "&Ocirc;";
      
    //simbolos
//    $convert_chars["<"] = "&lt;";
//:    $convert_chars[">"] = "&gt;";
  
       
    foreach($lang as $chave=>$l) {
      $lang[$chave] = strtr($l,$convert_chars);
    };

    return $lang;
  }


  

  /**
   * Executa uma funcão com privilegios diferentes do Apache
   *
   * Um dos grandes problemas em PHP é a execucao de arquivos com direitos de um usuario
   * diferente do Apache(usualmente apache ou nobody). O ROODA Devel utiliza um sistema
   * de wrapper baseado no do mailman. Um wrapper é um programa em C(no nosso caso) que
   * é compilado pelo root com o flag u+s do chmod. Esse flag permite que o programa mude
   * se usuário corrente que é o do Apache para qualquer outro que quiser, inclusive root.
   * Normalmente cria-se um usuário para o projeto e da-se direitos para ele escrever nos
   * diretorios desejados, assim assegurando-se que um código mal implementado comprometa
   * a seguranca do máquina. Esse usuário deve ser alterado no Makefile do wrapper que esta
   * em $pathbin/src.
   *
   * @param string $cmd Comando a ser executado. Deve estar dentro do diretório $pathbin e deve estar registrado dentro do código wrapper.c
   * @param string $args Argumentos a serem passados para o programa.
   * @return array Linhas que forma impressas pelo programa com uma linha por elementos do array.
   */
  function execAsRoot($cmd,$args) {
    global $config_ini,$rdpath;

    $path = $rdpath."/scriptsbin";
    if(!empty($cmd)) {
      $cmd = escapeshellcmd($cmd);
      $str_args = "";
      if(!empty($args)) {

	if(!is_string($args)) {
	  foreach($args as $arg) $str_args.= "\"$arg\" ";
	}
	else { $str_args = $args; };
      }
      exec($path."/wrapper $cmd $str_args",$ret);
      if($config_ini[rddevel][debug]) {
	note($ret);
      }
    };

    return $ret;
  }


  /**
   * Obtem os usuários que estão conectados no ambiente
   *
   * Toda a vez que um usuário autentica-se no RDAmbiente é gravado um informacão de tempo
   * referente a ele. Essa informacao é associada com o session_id da secao do browser. Depois de
   * um timeout definidio no arquivo de config.ini, se o usuario não retornar a navegar, sua sessao é
   * dada como encerrada e o flagEncerrado e setado para 1.
   * @return class Retorna uma RDLista com as informacoes de sessao de cada usuário. Note que não são retornados os RDUsers, esses devem serem construído pelos usuários a partir da propriedade RDSessaAmbiente->codUser.
   */
  function getOnlineUsers($camposProj="") {
    global $config_ini;
    

    $chaves[] = opVal("flaEncerrada","0", RDSessaoAmbiente::getTables());
    
    if(!empty($config_ini[Ambiente][plataforma_cod])) {
      $chaves[] = opVal("codPlataforma",$config_ini[Ambiente][plataforma_cod], RDSessaoAmbiente::getTables());
    }

    $chaves[] = opMVal(RDUser::getTables(),"codUser",RDSessaoAmbiente::getTables());
    $classes = array("RDUser","RDSessaoAmbiente");

    $tabuser = RDUser::getTables();
    $tabsess = RDSessaoAmbiente::getTables();

    $param = new RDParam();
    $param->setOrdem("$tabuser.nomUser");
    
    if(is_array($camposProj)) {
      $param->setCamposProjecao($camposProj);
    }
    else {
      $param->setCamposProjecao(array("$tabuser.codUser","$tabuser.nomUser","$tabsess.flaVisibilidade"));
    }

    $temp = new RDLista(array("RDUser","RDSessaoAmbiente"), $chaves,"",$param);
    
    return $temp;
  }


  function getNumOnlineUsers() {
    $lst = $this->getOnlineUsers();
    return $lst->numRecs;
  }
  
}

?>