<?php

class AMContato extends RDObj 
{

    function AMContato($key="") {
        $pkFields = "codContato";
        $fgKFields = "";
        $fields_def = array();
        $fields_def[codContato] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[codOwner] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[codUser] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[nomPessoa] = array("type" => "varchar","size" => "60","bNull" => "0");
        $fields_def[strEMail] = array("type" => "varchar","size" => "100","bNull" => "0");
        $fields_def[tempo] = array("type" => "int","size" => "11","bNull" => "0");
        $this->RDObj($this->getTables(),$this->getFields(),$pkFields,$key,$fields_def,$fgKFields);
    }

    function getTables() {
        return "contatos";
    }

    function getFields() {
        return  array("codContato","codOwner","codUser","nomPessoa","strEMail","tempo");
    }

}

