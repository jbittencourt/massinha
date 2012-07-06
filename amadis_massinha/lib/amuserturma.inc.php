<?php

class AMUserTurma extends RDObj
{
    function AMUserTurma($key="") {
        $pkFields = "codUser";
        $fgKFields = "";
        $fields_def = array();
        $fields_def[codUser] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[codTurma] = array("type" => "tinyint","size" => "4","bNull" => "0");
        $this->RDObj($this->getTables(),$this->getFields(),$pkFields,$key,$fields_def,$fgKFields);
    }


    function getTables() {
        return "userTurma";
    }


    function getFields() {
        return array("codUser","codTurma");
    }

}

