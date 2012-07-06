<?php

class AEUserInfo extends RDPagObj
{
    function AMUserInfo($codUser) {
        $user = new AMUser($codUser);
        $dados = $user->nomPessoa."&nbsp;(".$user->nomUser.")$nbsp;";
        $tip = new WTip("$teste");

    }

    function imprime() {
        parent::add ($dados.$tip->toString());
        parent::imprime();
    }

}
