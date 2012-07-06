<?php

class AETree extends WTreeNode {
    
    function AETree($caption) {
        global $urlimagens;

        $this->wtreenode($caption);
        $this->setBullets("$urlimagens/img_seta.gif","$urlimagens/img_seta_baixo.gif");
        $this->setClasses("fontgray2","");
    }

}

