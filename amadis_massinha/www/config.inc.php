<?

/**
 * Discover the root of the application
 **/
$parts = explode('/',dirname(__FILE__));
array_pop($parts);
$_CMAPP['path'] = implode('/',$parts);

//preencha com a url certa para o ambiente
$pathetc = $_CMAPP['path'] . "/etc";
$file = $pathetc . "/config.ini";
$fileferramentas = $pathetc . "/ferramentas.ini";	


$config_ini = parse_ini_file($file,TRUE);

$rdpath = $config_ini[RDDevel][rdpath];

$rootpath = $config_ini[Diretorios][rootpath];
$pathuserlib = $config_ini[Diretorios][pathuserlib];
$pathpaginas = $config_ini[Diretorios][pathpaginas];
$pathtemplates = $config_ini[Diretorios][pathtemplates];
$pathbin = $config_ini[Diretorios][pathbin];

$url = $config_ini[Internet][url];
$urlferramentas = $config_ini[Internet][urlferramentas];
$urlimagens = $config_ini[Internet][urlimagens];
$urlpaginas = $config_ini[Internet][urlpaginas];
$urltema = $urlimagens."/temas/".$config_ini[Ambiente][tema]."/";
  
$ferramentas = parse_ini_file($fileferramentas,TRUE); 

include_once("$rdpath/rddevel.inc.php");

//include the class loader function
include($_CMAPP['path'].'/classload.inc.php');


include_once("$pathuserlib/amambiente.inc.php");


// var global de bibliotecas a serem incluidas  
if (!empty($r_bibliotecas)) {  
  foreach ($r_bibliotecas as $k=>$bib)  {
    
    $lib = eval("\$bib = \"$bib\"; return \$bib;");
    include_once($lib);	

  }
}		

//conecta com o bco de dados
$connDB = conectaDB();

//testa para ver se existe um nome definido para a sessão deste programa
//caso não utitliza o nome padrao do RODDA_DEVEL_PROGRAM
$temp = $config_ini[Ambiente][session_name];
if(empty($temp)) {
  $temp = "RDDEVEL";
}

session_name($temp);
session_start();


if(!$sem_login) {

  if(!empty($_REQUEST[frm_login])) {
    $_SESSION[ambiente] = new AMambiente($_REQUEST[frm_login],$_REQUEST[frm_pwd],"$url/index.php");
  }
  else {
    $ret = 0;
    if(!empty($_SESSION[ambiente])) {
      $ret = $_SESSION[ambiente]->autentica();
    };

    if(!$ret) {
      //a variavel abaixo é definida no caso de não se desejar redirecionar o logon no case
      // de falha da autenticacao. Ela serve por exemplo para ferramentas como o finder
      // fecharem o janela no caso de falha do logon. No entanto cabe ao script do usuário
      // saber o que fazer nesse caso.
      if(!$no_redirect_on_logon_failure) {
	Header("Location: $url/index.php");
      };
    };
  };

}
else {
 
  if(empty($_SESSION[ambiente])) {
    $_SESSION[ambiente] = new AMAmbiente();
  }
};


  
//carrega o arquivo de linguagem
if($config_ini[Linguagem][ativa] == 1) {
  $language = parse_ini_file($_SESSION[ambiente]->fileLanguage,TRUE);
};


//set uma url para as imagens relativas a sua linguagem. � importante 
//para suportar imagens em varias linguas
$urlimlang = "$urlimagens/".$_SESSION[ambiente]->language;


if(!empty($_SESSION[usuario])) {
  if(empty($_REQUEST[go]))
    $_REQUEST[go] = $_SESSION[usuario]->flaSuper;
}

if(($config_ini[Ambiente][manutencao] || (!$connDB)) && empty($_REQUEST[go])) {

  include_once("$pathtemplates/aemanutencao.inc.php");
  $ui = new RDui("manutencao");
  $lang = $_SESSION[ambiente]->getLangUI($ui);


  if($config_ini[Ambiente][manutencao]){ 
    $motivo= AE_MANUT_ADMIN;
  }

  if(!$connDB) $motivo= AE_MANUT_DB_OUT;

  $pag = new AEManutencao($motivo);
  $pag->imprime();
  die();
}

?>
