<?php


class AMEscola extends RDObj
{
    function AMEscola($key="") {
        $table = "escola";
        $fields = array("codEscola","nomEscola","desEndereco","desBairro","desTelefone","codCidade");
        $pkFields = "codEscola";
        $fields_def = array();

        $fields_def[codEscola] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[nomEscola] = array("type" => "varchar","size" => "60","bNull" => "0");
        $fields_def[desEndereco] = array("type" => "varchar","size" => "150","bNull" => "0");
        $fields_def[codCidade] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[desBairro] = array("type" => "varchar","size" => "80","bNull" => "0");
        $fields_def[desTelefone] = array("type" => "varchar","size" => "20","bNull" => "0");

        $this->RDObj($table,$fields,$pkFields,$key,$fields_def);
    }

    function listaTurmas() {
        $chave[] = opVal ("codEscola", $this->codEscola);
        return (new RDLista("AMTurma", $chave, "nomTurma asc"));
    }


}

