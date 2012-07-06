<?php


class AMNoticia extends RDObj
{
    function AMNoticia($key="") {
        $table = "noticias";
        $fields = array("codNoticia","codUser","flaLida","desNoticia", "tempo");
        $pkFields = "codNoticia";
        $fields_def = array();
        $fields_def[codNoticia] = array("type" => "bigint","size" => "20","bNull" => "0");
        $fields_def[codUser] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[flaLida] = array("type" => "char","size" => "1","bNull" => "0");
        $fields_def[desNoticia] = array("type" => "tinytext","size" => "","bNull" => "0");
        $fields_def[tempo] = array("type" => "bigint","size" => "20","bNull" => "0");
        $this->RDObj($table,$fields,$pkFields,$key,$fields_def);
    }
}


