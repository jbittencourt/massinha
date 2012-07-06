<?php

class AMOficinaMatricula extends RDObj
{

    function AMOficinaMatricula($chave="") {
        $campoTextoChaveP = array("codOficina", "codUser");
        $this->RDObj($this->getTables(),$this->getFields(),$campoTextoChaveP,$chave);
    }

    function getTables() {
        return "oficinaMatricula";
    }

    function getFields() {
        return array("codOficina","codUser","flaAutorizado","tempo");
    }

}

