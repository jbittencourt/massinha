<?php

class AMDiarioComent extends RDObj
{
    function AMDiarioComent($key="") {
        $table = "diarioComent";
        $fields = array("codComent","codTexto","codUser","desTexto","tempo");
        $pkFields = "codComent";
        $fields_def = array();
        $fields_def[codComent] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[codTexto] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[desTexto] = array("type" => "text","size" => "","bNull" => "0");
        $fields_def[tempo] = array("type" => "bigint","size" => "20","bNull" => "0");
        $fields_def[codUser] = array("type" => "int","size" => "11","bNull" => "0");
        $this->RDObj($table,$fields,$pkFields,$key,$fields_def);
    }
}

