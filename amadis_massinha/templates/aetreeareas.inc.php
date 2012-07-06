<?php

class AETreeAreas extends RDPagObj
{

    var $list, $linhas;

    function AETreeAreas($lst){
        $this->list = $lst;
    }


    function getTree($parent) {
        global $urlferramentas;

        $node = new AETree($parent->nomArea);
        $hits = 0;
        for($i=0;$i < count($this->list->records);$i++) {
            $area = $this->list->records[$i];
            if($area->codPai==$parent->codArea) {
                $node->add($this->getTree($area));
                $node->add("<br>");
                $hits++;
            }
        }

        if($hits==0) {
            $node = "<a href=\"$urlferramentas/projetos/projetoarea.php?frm_codArea=$parent->codArea\" class=\"mnlateral\">&raquo; $parent->nomArea</a>";
        }

        return $node;

    }


    function imprime(){
        
        foreach($this->list->records as $area) {
            if($area->codPai==0) {
                parent::add($this->getTree($area));
                parent::add("<br>");
            }
        }

        parent::imprime();
    }
    
    
}
