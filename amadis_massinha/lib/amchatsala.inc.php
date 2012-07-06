<?php

class AMChatSala extends RDChatRoom {//RDChatRoom j'a sabe qual a tabela que vai ser consultada: chat_sala.

    function AMChatSala($chave="") {//esta fun'c~ao retorna o regiastro da tabela chat_sala com o id ($chave) recebido)

        $this->RDChatRoom();//func'c~ao da classe pai que inicializa ela mesma

        $this->addfield("tipoPai",RDChatRoom::getTables(),"char","1","1");
        $this->addfield("codPai",RDChatRoom::getTables(),"int","11","0");

        if(!empty($chave)) {

            if(!is_array($chave)) {
                $chaves[] = opVal("codSala",$chave,"chat_sala");
                $chaves[] = opVal("codProjeto",$chave,"projeto");
                $var = new RDLista("AMChatSala",$chaves);
                $var->records;

            }
            else { $chaves = $chave; };

            $this->le($chave);
        }
    }


}
