<?php

class AMOficinaCoordenador extends RDObj
{

    function AMOficinaCoordenador($key="") {
        $table = "oficinaCoordenador";
        $fields = array("codOficina","codUser");
        $pkFields = "";
        $fields_def = array();
        $fields_def[codOficina] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[codUser] = array("type" => "int","size" => "11","bNull" => "0");
        $this->RDObj($table,$fields,$pkFields,$key,$fields_def);
    }

}

