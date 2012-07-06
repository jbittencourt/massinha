<?

class RDChatConnection extends RDObj {

  function RDChatConnection($key="") {
    $pkFields = "codConexao";
    $fgKFields = array("codUser","codSala");
    $fields_def = array();
    $fields_def[codConexao] = array("type" => "bigint","size" => "20","bNull" => "0");
    $fields_def[codSala] = array("type" => "int","size" => "11","bNull" => "0");
    $fields_def[codUser] = array("type" => "int","size" => "11","bNull" => "0");
    $fields_def[datEntrou] = array("type" => "bigint","size" => "20","bNull" => "0");
    $fields_def[datSaiu] = array("type" => "bigint","size" => "20","bNull" => "0");
    $fields_def[flaOnline] = array("type" => "char","size" => "1","bNull" => "0");
    $this->RDObj($this->getTables(),$this->getFields(),$pkFields,$key,$fields_def,$fgKFields);
  }

  function getTables() {
     return "chat_sala_conectados";
  }

  function getFields() {
     return  array("codConexao","codSala","codUser","datEntrou","datSaiu","flaOnline");
  }

}


?>