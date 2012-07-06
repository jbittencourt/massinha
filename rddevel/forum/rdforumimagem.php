<?php
class RDForumImagem extends RDObj {

    function RDForumImagem($chave="") {
        $tabelaTexto = "forumImagem";
        $camposTexto = array("codArquivo","codMensagem");
        $this->RDObj($tabelaTexto,$camposTexto,"",$chave);
    }

}
?>