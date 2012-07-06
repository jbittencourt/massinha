<?php

class AMProjetoAreas extends RDObj {

    function AMProjetoAreas($key="") {
        $table = "projetoAreas";
        $fields = array("codProjeto","codArea");
        $pkFields = array("codProjeto","codArea");
        $fields_def = array();
        $fields_def[codProjeto] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[codArea] = array("type" => "mediumint","size" => "9","bNull" => "0");

        $this->RDObj($table,$fields,$pkFields,$key,$fields_def);
    }
}