<?

include_once("$rdpath/email/rdimapmessage.inc.php");


class RDImapMail extends RDFerramenta {
  var $mbox;
  var $type;

  function RDImapMail() {
    global $config_ini;

    if($config_ini[email][imap_email]) {
      if(empty($config_ini[email][imap_str])) {
	die("Voce não forceu um string sql");
      }

      $this->mailbox = $config_ini[email][imap_str]."INBOX";
      $this->mbox = imap_open($this->mailbox,
			       $_SESSION[usuario]->nomUser,
			       $_SESSION[usuario]->desSenha);

    }

  }


  function getNumRecentes() {
    $check = @imap_check ($this->mbox);
    
    return $check->Recent;
  }

  function listMessages() {
    $headers = @imap_headers($this->mbox);
    $numEmails = sizeof($headers); 

    for($i=0; $i<$numEmails; $i++) {
      $mailHeader = @imap_headerinfo($this->mbox, $i+1);
      
      $mail[id] = $mailHeader->message_id;
      $mail[uid] = imap_uid($this->mbox,$i+1);
      $mail[from] = $mailHeader->fromaddress;
      $mail[subject] = strip_tags($mailHeader->subject);
      $mail[date] = $mailHeader->date;
      $mail[unseen] = ($mailHeader->Unseen == 'U') || ($mailHeader->Recent == 'N');
      
      $lista[] = $mail;
    }
   return $lista;
    
  }

  function setMailbox($mailbox="") {
    if(!empty($mailbox)) $mailbox = ".".$mailbox;
    imap_reopen ($this->mbox,$this->mailbox.$mailbox);
  }

  function getNewMessage() {
    return new RDImapMessage();
  }
  

  function saveMessage($message) {

    if(get_class($message!="rdimapmessage")) {
       die("O tipo de mensagem deve ser um objeto RDImapMessage");
    }

    //ve se o mailbox de sent existe.
    $status = @imap_status($this->mbox,$this->mailbox.".sent",SA_ALL);

    if(empty($status)) {
      @imap_createmailbox($this->mbox,imap_utf7_encode($this->mailbox.".sent"));
    }

    $men = $message->toStr();
    @imap_append($this->mbox,$this->mailbox.".sent",$men);
  }

  function deleteMessage($message_id) {
    //move para a pasta .Trash.
    $msg_num = imap_msgno($this->mbox,$message_id);
    @imap_mail_copy($this->mbox,$msg_num,$this->mailbox.".Trash");
    echo "E:".imap_last_error();
    @imap_delete($this->mbox,$msg_num);
    @imap_expunge();
  }



  function getMensagem($message_id) {

    $msg_num = imap_msgno($this->mbox,$message_id);

    $mailHeader = imap_headerinfo($this->mbox, $msg_num);
 
    if(empty($mailHeader)) return 0;
            
    $message = new RDImapMessage();
      
    $message->id = $mailHeader->message_id;
    $message->uid = $message_id;
    $message->from_email = $mailHeader->fromaddress;
    $message->from = $mailHeader->from[0]->personal; 
    $message->subject = strip_tags($mailHeader->subject);
    $message->date = $mailHeader->date;
    $message->unseen = ($mailHeader->Unseen == 'U') || ($mailHeader->Recent == 'N');
    $message->to = $mailHeader->toaddress; 
    
    $struct = imap_fetchstructure($this->mbox,$msg_num);
    
    //$ret->subtype = strtolower($ret->subtype);

    if($struct->type==TYPETEXT) {
      $subtype = strtolower($struct->subtype);
      switch($subtype) {
      case "plain":
	$message->type = "text/plain";
	$message->body = imap_fetchbody($this->mbox,$msg_num,1);
	break;
      case "html":
	$message->type = "text/html";
	$message->body = imap_fetchbody($this->mbox,$msg_num,1);
	break;
      }

    }


    return $message;
  }

}


?>
