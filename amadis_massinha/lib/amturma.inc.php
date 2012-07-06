<?php

class AMTurma extends RDObj
{

    function AMTurma($key="") {
        
        
        $pkFields = "codTurma";
        $fgKFields = "";
        $fields_def = array();
        $fields_def[codTurma] = array("type" => "tinyint","size" => "4","bNull" => "0");
        $fields_def[nomTurma] = array("type" => "varchar","size" => "8","bNull" => "0");
        $fields_def[codCiclo] = array("type" => "tinyint","size" => "4","bNull" => "0");
        $fields_def[codEscola] = array("type" => "tinyint","size" => "4","bNull" => "0");
        $this->RDObj($this->getTables(),$this->getFields(),$pkFields,$key,$fields_def,$fgKFields);
    }


    function getTables() {
        return "turma";

    }

    function getFields() {
        return  array("codTurma","nomTurma","codCiclo","codEscola");
    }

    function listaUsers() {
        global $config_ini, $pathuserlib;
        include_once("$pathuserlib/amuserturma.inc.php");

        $chave[] = opVal("codTurma", $this->codTurma,AMUserTurma::getTables());
        $chave[] = opMVal(AMUser::getTables(),"codUser",AMUserTurma::getTables());

        if ($config_ini[Ambiente][plataforma_master] == "0") {
            $chave[] = opVal("codPlataforma",$config_ini[Ambiente][plataforma_cod],AMUser::getTables());
        }

        $param = new RDParam();
        $user = AMUser::getTables();
        $turma = AMUserTurma::getTables();
        $param->setCamposProjecao(array("$user.codUser","$user.nomUser","$user.nomPessoa","$turma.codTurma"));

        $lst = new RDLista("AMUser",$chave,"$user.nomPessoa",$param);
   
        return $lst;

    }


}

