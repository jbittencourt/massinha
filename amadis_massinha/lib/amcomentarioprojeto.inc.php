<?php

class AMComentarioProjeto extends RDObj 
{
    function AMComentarioProjeto($key="") {
        $table = "comentarios";
        $fields = array("codComentario","codProjeto","desNome","desComentario","tempo");
        $pkFields = "codComentatio";
        $fields_def = array();
        $fields_def[codComentario] = array("type" => "smallint","size" => "6","bNull" => "0");
        $fields_def[codProjeto] = array("type" => "smallint","size" => "6","bNull" => "0");
        $fields_def[desNome] = array("type" => "varchar","size" => "50","bNull" => "0");
        $fields_def[desComentario] = array("type" => "text","size" => "","bNull" => "0");
        $fields_def[tempo] = array("type" => "int","size" => "11","bNull" => "0");
        $this->RDObj($table,$fields,$pkFields,$key,$fields_def);
    }

}

