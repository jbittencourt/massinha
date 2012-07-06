<?php

class AMDiario extends RDObj 
{

    function AMDiario($key="") {

    //tipoPai = U quando eh diario de um usuario e P quando eh diario de projeto

        
        $pkFields = "codTexto";
        $fields_def = array();
        $fields_def[codTexto] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[codPai] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[tipoPai] = array("type" => "char","size" => "1","bNull" => "0");
        $fields_def[desTexto] = array("type" => "text","size" => "","bNull" => "0");
        $fields_def[tempo] = array("type" => "int","size" => "11","bNull" => "0");

        $this->RDObj($this->getTables(),$this->getFields(),$pkFields,$key,$fields_def);

    }

    function getTables() {
        return "diario";
    }


    function getFields() {
        return array("codTexto","codPai","tipoPai","desTexto","tempo");
    }

    function listaComentarios() {
        $chave[] = opVal ("codTexto", $this->codTexto);
        $pesq = new RDLista ("AMDiarioComent", $chave, "tempo desc");
        return ($pesq);
    }

}

