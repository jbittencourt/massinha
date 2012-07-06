<?php
class AMPlataforma extends RDObj
{
    function AMPlataforma($key="") {
        $pkFields = "codPlataforma";
        $fgKFields = "";
        $fields_def = array();
        $fields_def[codPlataforma] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[strIDPlataforma] = array("type" => "varchar","size" => "20","bNull" => "0");
        $fields_def[flaMaster] = array("type" => "char","size" => "1","bNull" => "0");
        $fields_def[descrPlataforma] = array("type" => "varchar","size" => "100","bNull" => "0");
        $fields_def[tempo] = array("type" => "bigint","size" => "20","bNull" => "0");
        $this->RDObj($this->getTables(),$this->getFields(),$pkFields,$key,$fields_def,$fgKFields);
    }
    function getTables() {
        return "plataforma";
    }
    function getFields() {
        return  array("codPlataforma","strIDPlataforma","flaMaster","descrPlataforma","tempo");
    }
}

