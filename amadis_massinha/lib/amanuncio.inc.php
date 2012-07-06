<?

class AMAnuncio extends RDObj {
    function AMAnuncio($key="") {
        $pkFields = "";
        $fgKFields = "codAnuncio";
        $fields_def = array();
        $fields_def[codAnuncio] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[codProjeto] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[desTituloAnuncio] = array("type" => "varchar","size" => "100","bNull" => "0");
        $fields_def[desAnuncio] = array("type" => "text","size" => "","bNull" => "0");
        $fields_def[tempo] = array("type" => "int","size" => "11","bNull" => "0");
        $this->RDObj($this->getTables(),$this->getFields(),$pkFields,$key,$fields_def,$fgKFields);
    }

    function getTables() {
        return "anuncios";
    }

    function getFields() {
        return  array("codAnuncio","codProjeto","desTituloAnuncio","desAnuncio","tempo");
    }

}

