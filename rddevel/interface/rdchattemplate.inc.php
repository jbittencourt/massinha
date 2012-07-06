<?
/**
 */
/**
 * Template para a interface de um chat.
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @abstract
 * @version 0.5
 * @package rddevel
 * @subpackage interface
 * @see RDObj
 */
class RDChatTemplate extends RDPagObj {
  var $campoDest,$campoSender, $campoTempo, $tempoSleep, $CHAT_cod_user;


  function RDChatTemplate() {
    $this->tempoSleep = 2;  //seta epera para 2 segundos
  }

  //essa funcao é um template e deve ser reimplementada para o chat de cada ambiente
  function drawMessage() {
  }

  //essa funcao é um template e deve ser reimplementada para o chat de cada ambiente
  function getNewMessages($time) {
  }
 
  function setSleepTime($tempo) {
    $this->tempoSleep = $tempo;
  }



  //essa funcao foi retirada do site do php como uma alternativa 
  // ao connection_aborted, que atulmente (11-2-2003) encontra-se
  // quebrada. Ela provavlemente tem efeitos de performace no servidor
  // mas é melhor que nada. Quando a função do php voltar a funcionar
  // espero que ela seja retirada
  function alt_connection_aborted() {
    return 0;
    /*  $ip = str_replace(".", "\\.", getenv("REMOTE_ADDR"));
    $port = getenv("REMOTE_PORT");
    
    return (preg_match("/^tcp +[0-9]+ +[0-9]+ +[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}:[0-9]+ +$ip:$port +.*?$/m", `netstat -n --tcp`))?false:true; */
  }




  function mainLoop($tempoLastMessage="") {
    
    ignore_user_abort(TRUE);
    set_time_limit(0);

    if(empty($tempoLastMessage)) $tempoLastMessage = time();
    session_write_close();    //fecha a seção para escrita evitando sessões concorrentes, o que gera um bug no sistema
    $this->scrollScript();

    //imprime os cabecalhos da pagina
    
    echo "<html><head></head>";
    echo "<body bgcolor=\"#FFFFFF\">";
    
    while ((!$this->alt_connection_aborted()) && (!$onlyShow) )   { 
      
      $onlyShow = $this->onlyShow;
      
      $mensagens = $this->getNewMessages($tempoLastMessage);      //pega as novas mensagens

      //sempre manda um espaço para forçcar uma shutdown_function a ser chamada;
      echo " \n";flush();
     

      if (!empty($mensagens)) {

	foreach($mensagens as $mensagem) {
	  if ($mensagem[$this->campoDestino]==0 || $mensagem[$this->campoDestino]==$this->CHAT_cod_user || $mensagem[$this->campoSender]==$this->CHAT_cod_user) {
	    $numLinhas = $this->drawMessage($mensagem); 
	  };
	  $tempoLastMessage = $mensagem[$this->campoTempo];                  
	  flush();
      
	  //este é o script que chama o scroll da tela
	  $scroll= "\n<SCRIPT language=\"JavaScript\" type=\"text/javascript\">";
	  $scroll.= " scrollTela(".$numLinhas.") ";
	  $scroll.= "</SCRIPT>\n";  
	  echo $scroll;
	  
	  flush();
            
            
	};

          
      };
     
      flush();
      sleep($this->tempoSleep);
    } ;

  
  }

  function scrollScript() {
    $script= "<SCRIPT language=\"JavaScript\" type=\"text/javascript\">";
    $script.= "function doScroll(numLinhas) {"; 
    $script.= "window.scrollBy(0,1);";
    $script.= "if (browser==\"opera\") {";
    $script.= "     window.scrollBy(0,40);";
    $script.= "     for(i=1;i<=numLinhas;i++) {";
    $script.= "     window.scrollBy(0,10); }";   
    $script.= "}";    
    $script.= "else if (isNaN(window.pageYOffset))";
    $script.= " { window.scrollTo(0,document.body.scrollHeight);}";
    $script.= "else ";
    $script.= " { if (document.layers) {";
    $script.= "     window.scrollBy(0,47);";
    $script.= "     for(i=1;i<=numLinhas;i++) {";
    $script.= "     window.scrollBy(0,14); }";   
    $script.= "   }";
    $script.= "   else {";
    $script.= "     window.scrollTo(0,document.body.offsetHeight);}";   //By0,42
    $script.= "}";
    $script.= "}";

    $script.= "function scrollTela(numLinhas) {";
    $script.= " if (browser==\"opera\") {";
    $script.= " if (parent.finder_envia.document.envia.frm_scroll.checked==true)";    
    $script.= "   { doScroll(numLinhas); }";
    $script.= "}";
    $script.= "if (browser!=\"opera\") {";
    $script.= "if (!isNaN(window.pageYOffset)) {";
    $script.= "  if (window.pageYOffset<posicaoY) {";
    $script.= "    parent.finder_envia.document.envia.frm_scroll.checked = false; }";    
    $script.= "  posicaoY = window.pageYOffset; } ";                   
    $script.= "else if (!isNaN(document.body.scrollTop)) {";
    $script.= "  if (document.body.scrollTop<posicaoY) {";
    $script.= "    parent.finder_envia.document.envia.frm_scroll.checked = false; }";    
                     
    $script.= "}";
    $script.= "}";
    $script.= "if (browser!=\"opera\") {";
    $script.= "if (parent.finder_envia.document.envia.frm_scroll.checked==true) {";
    $script.= "   doScroll(numLinhas); }";    
    $script.= "else if (!isNaN(window.pageYOffset)) {" ;
    $script.= "   if ((document.body.offsetHeight-window.pageYOffset)<(window.innerHeight+70))";    //70
    $script.= "     {  doScroll(numLinhas);} ";    
    $script.= " }";  
    $script.= "else if (!isNaN(document.body.scrollTop)) {";
    $script.= "   if ((document.body.scrollTop+document.body.clientHeight)>=(document.body.scrollHeight-90))"; //90
    $script.= "     {  doScroll(numLinhas);}";
    $script.= "}";      
    $script.= " if (isNaN(window.pageYOffset)) {";
    $script.= "    posicaoY = document.body.scrollTop; }";                        
    $script.= " else {"; 
    $script.= "    posicaoY = window.pageYOffset; }";    
    $script.= "}";
    $script.= "}";
    $script.= "posicaoY=0;";
    $script.= "detect = navigator.userAgent.toLowerCase();";
    $script.= "if (detect.indexOf('opera')!=-1)";    
    $script.= "  { browser=\"opera\"; }";    
    $script.= "else ";
    $script.= "  { browser = \"outro\"; }";
    $script.= "</SCRIPT>";  
    echo $script;
    flush();
  }

}

?>
