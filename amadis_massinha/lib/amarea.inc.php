<?

class AMArea extends RDObj {
    var $imagens;

    function AMArea($key="") {
        $table = "areas";
        $fields = array("codArea","nomArea","codPai","intGeracao");
        $pkFields = "codArea";
        $fields_def = array();
        $fields_def[codArea] = array("type" => "tinyint","size" => "4","bNull" => "0");
        $fields_def[nomArea] = array("type" => "varchar","size" => "50","bNull" => "0");
        $fields_def[codPai] = array("type" => "tinyint","size" => "4","bNull" => "0");
        $fields_def[intGeracao] = array("type" => "char","size" => "1","bNull" => "0");
        $this->RDObj($table,$fields,$pkFields,$key,$fields_def);
    }


    function listaProjetos() {
        global $config_ini;

        $chaves[] = opVal("codArea",$this->codArea,"projetoAreas");

        if($config_ini[Ambiente][plataforma_master] != 1){

            $chaves[] = opVal("codPlataforma",$config_ini[Ambiente][plataforma_cod],"projeto");
            $chaves[] = opmVal("projetoAreas","codProjeto","projeto");
            $param = new RDParam();
            $param->setCamposProjecao(array('projetoAreas.codProjeto','projetoAreas.codArea'));

        } else {
             
            $chaves[] = opmVal("projeto","codPlataforma","plataforma");
            $chaves[] = opmVal("projetoAreas","codProjeto","projeto");
    
            $param = new RDParam();
            $param->setCamposProjecao(array('projetoAreas.codProjeto','projeto.desTitulo','projeto.desProjeto','projetoAreas.codArea','plataforma.descrPlataforma'));
   
        }
        
        $lst = new RDLista("AMProjetoAreas",$chaves,'',$param);
     //noteLastquery();
        return $lst;
    }

    function listaTodosProjetoArea(){
        $lst = new RDLista("AMProjetoAreas","");
        return $lst;
    }

    function listaFilhas() {
        $chaves[] = opVal("codPai",$this->codArea);
        $lst = new RDLista("AMArea",$chaves);
        return $lst;
    }

    
}

