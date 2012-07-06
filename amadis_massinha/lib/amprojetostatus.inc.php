<?php
class AMProjetoStatus extends RDObj 
{
    function AMProjetoStatus($key="") {
        $table = "projetoStatus";
        $fields = array("codStatus","desStatus");

        $pkFields = "codStatus";

        $fields_def = array();
        $fields_def[codStatus] = array("type" => "tinyint","size" => "4","bNull" => "0");
        $fields_def[desStatus] = array("type" => "varchar","size" => "40","bNull" => "0");
        $this->RDObj($table,$fields,$pkFields,$key,$fields_def);

    }
}