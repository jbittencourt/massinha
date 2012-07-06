<?php

class AMNovidade extends RDObj 
{
    function AMNovidade($key="") {
        $table = "novidades";
        $fields = array("codNovidade","codProjeto","desNovidade","tempo");
        $pkFields = "codNovidade";
        $fields_def = array();
        $fields_def[codNovidade] = array("type" => "tinyint","size" => "4","bNull" => "0");
        $fields_def[codProjeto] = array("type" => "tinyint","size" => "4","bNull" => "0");
        $fields_def[desNovidade] = array("type" => "text","size" => "","bNull" => "0");
        $fields_def[tempo] = array("type" => "int","size" => "11","bNull" => "0");
        $this->RDObj($table,$fields,$pkFields,$key,$fields_def);
    }
}

