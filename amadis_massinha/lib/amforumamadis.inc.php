<?php

class AMForumAmadis extends RDForum {

    function AMForumAmadis($chave="") {

	// tipo pai eh: C (urso), S (eminario) ou O (ficina)

        $this->RDForum();

      //adiciona os novos campos da tabela
        $this->addField("tipoPai","Forum","char","1","0");
        $this->addField("codPai","Forum","int","11","0");
        $this->addField("flaAllowView","Forum","char","1","0");
        $this->addField("flaAllowPost","Forum","char","1","0");

        if(!empty($chave)) {
            $this->le($chave);
        };

    }


}
