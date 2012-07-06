<?php
class AMBibliotecaDoc extends RDObj {

    function AMBibliotecaDoc($key="") {

        $table = "biblioteca_doc";
        $fields = array("codDoc","desTitulo","desTipoMime","codUser","codArquivo","codOficina","flaRestrito","flaAceito","tempo");
        $pkFields = "codDoc";
        $fields_def = array();

        $fields_def[codDoc] = array("type" => "bigint","size" => "20","bNull" => "0");
        $fields_def[desTitulo] = array("type" => "varchar","size" => "100","bNull" => "0");
        $fields_def[codUser] = array("type" => "mediumint","size" => "9","bNull" => "0");
        $fields_def[codArquivo] = array("type" => "bigint","size" => "20","bNull" => "0");
        $fields_def[codOficina] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[flaRestrito] = array("type" => "char","size" => "1","bNull" => "0");
        $fields_def[flaAceito] = array("type" => "char","size" => "1","bNull" => "0");
        $fields_def[tempo] = array("type" => "mediumint","size" => "9","bNull" => "0");

        $this->RDObj($table,$fields,$pkFields,$key,$fields_def);

    }


    function salva() {

        if(!empty($this->file)) {
            $arq = new RDDBUpload();
            $arq->setData($this->file);
            $arq->salva();

            if(!$arq->novo) {
                $this->codArquivo = $arq->codArquivo;
                $this->desTipoMime = $arq->desTipoMime;
                parent::salva();
            }
        }

    }

}

