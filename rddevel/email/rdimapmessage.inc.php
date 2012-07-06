<?

include_once("Mail/mime.php");



define("RD_MSG_COMPLEX",1);
define("RD_MSG_SIMPLE",2);


class RDImapMessage {

  var $from;
  var $fromaddress;
  var $subject;
  var $reply;
  var $body;
  var $to;
  var $unseen;
  var $id;
  var $uid;

  var $type;


  function RDImapMessage() {
    $this->mail = new Mail_mime();
  }


  function attachFile($file,$realname,$mime,$filename=true) {
    echo "$file,$realname,$mime";
    $ret = $this->mail->addAttachment($file, $mime, $realname,$filename);
    if(get_class($ret)=="pear_error")
      note($ret->message);
  }

  function getMessage() {
    global $config_ini;

   if(empty($this->from)) {
      $user = $_SESSION[usuario]->nomPessoa;
      $email = $_SESSION[usuario]->strEMail;
      $this->from = "$user <$email>";
      $this->fromaddress = $email;
    }


    if(!empty($this->to)) {
      $tmp = $this->to;
      $addresses = imap_rfc822_parse_adrlist($tmp, $config_ini[email][domain]);
      foreach($addresses as $address) {
	$temp[]= $address->mailbox."@".$address->host;
      }
      $this->realto = implode(", ",$temp);
    } else {
      die("Voce precisa setar um destinatario");
    }

    $this->mail->setHTMLBody($this->body);

    $headers["From"] = $this->from;
    $headers["Subject"] = $this->subject;
    $headers["Date"] = date("r");
    $headers["Message-id"] = '<'.uniqid(time().'.').'@'.$config_ini[email][domain]. '>';
    $headers["To"] = $this->to;

    $param[html_encoding] = "8bit";
    $this->message = $this->mail->get($param);
    $this->hdrs = $this->mail->headers($headers);

  }
  

  function toStr() {
    include_once("Mail.php");
    
    $this->getMessage();
    $text_headers = "";
    foreach($this->hdrs as $k=>$d) 
      $text_headers.= "$k: $d\r\n";
      
    return ("$text_headers\r\n"."$this->message\r\n");
  }
  
  function send() {
    global $config_ini;
    include_once("Mail.php");

    $this->getMessage();

    if(empty($config_ini[email][method])) {
      $config_ini[email][method]= 'mail';
    }

    $mail = &Mail::factory($config_ini[email][method]);
    $mail->send($this->realto, $this->hdrs, $this->message);

  }
  
}




?>
