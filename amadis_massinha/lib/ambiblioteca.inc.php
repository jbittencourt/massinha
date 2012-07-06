<?php

class AMBiblioteca {


    function listaDocumentos($codOficina="") {
        $chaves = array();

        if(!empty($codOficina)) {
            $chaves[codOficina] = opVal("=","codOficina");
        }

        $lst = new RDLista("AMBibliotecaDoc",$chaves,"tempo");

        return $lst;
    }


}