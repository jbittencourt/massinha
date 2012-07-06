<?php

class AMProjetoMatricula extends RDObj {
    function AMProjetoMatricula($key="") {
        $pkFields = "codMatricula";
        $fgKFields = "";
        $fields_def = array();
        $fields_def[codMatricula] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[codProjeto] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[codUser] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[tempo] = array("type" => "int","size" => "11","bNull" => "0");
        $this->RDObj($this->getTables(),$this->getFields(),$pkFields,$key,$fields_def,$fgKFields);
    }
    function getTables() {
        return "projetoMatricula";
    }
    function getFields() {
        return  array("codMatricula","codProjeto","codUser","tempo");
    }
}

