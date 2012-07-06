<?php


class AMOficina extends RDObj {

    function AMOficina($key="") {
        $table = "oficina";
        $fields = array("codOficina","nomOficina","desOficina","flaInscrAutomatica","datInicio","datFim","datInscrInicio","datInscrFim","flaSeminario","tempo");

        $pkFields = "codOficina";
        $fields_def = array();

        $fields_def[codOficina] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[nomOficina] = array("type" => "varchar","size" => "59","bNull" => "0");
        $fields_def[desOficina] = array("type" => "text","size" => "","bNull" => "0");
        $fields_def[flaInscrAutomatica] = array("type" => "char","size" => "1","bNull" => "0");
        $fields_def[datInicio] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[datFim] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[datInscrInicio] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[datInscrFim] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[flaSeminario] = array("type" => "char","size" => "1","bNull" => "0");
        $fields_def[tempo] = array("type" => "int","size" => "11","bNull" => "0");

        $this->RDObj($table,$fields,$pkFields,$key,$fields_def);
    }

    function listaForuns() {
        global $pathuserlib;
        include_once ("$pathuserlib/amforum.inc.php");

        $chaves[] = opVal("codPai",$this->codOficina);
        $chaves[] = opVal("tipoPai","O");

        $ret = new RDLista("AMForumAmadis",$chaves,"tempo asc");
        return ($ret);
    }

    function listaChats() {
        $chaves[] = opVal("codPai",$this->codOficina);
        $chaves[] = opVal("tipoPai","O");

        $ret = new RDLista("AMChatSala",$chaves,"tempo asc");
        return ($ret);
    }

    function listaMatriculas() {
        $chaves[] = opVal("codOficina",$this->codOficina);
        $chaves[] = opVal("flaAutorizado","1");
        $matriculas = new RDLista("AMOficinaMatricula",$chaves,"tempo asc");
        return ($matriculas);
    }
    
    function listaTodasMatriculas() {
        $chaves[] = opVal("codOficina",$this->codOficina);
        $matriculas = new RDLista("AMOficinaMatricula",$chaves,"tempo asc");
        return ($matriculas);
    }

    function listaMatriculasNaoJulgadas() {
        $chaves[] = opVal("codOficina",$this->codOficina);
        $chaves[] = opVal("flaAutorizado","0");
        $matriculas = new RDLista("AMOficinaMatricula",$chaves,"tempo asc");
        return ($matriculas);
    }

    function listaMatriculasNegadas() {
        $chaves[] = opVal("codOficina",$this->codOficina);
        $chaves[] = opVal("flaAutorizado","2");
        $matriculas = new RDLista("AMOficinaMatricula",$chaves,"tempo asc");
        return ($matriculas);
    }

    function getMatricula($user) {
        $chave[] = opVal("codUser", $user);
        $chave[] = opVal("codOficina", $this->codOficina);

        return (new AMOficinaMatricula($chave));
    }

    function listaCoordenadores() {
        $chaves[] = opVal("codOficina",$this->codOficina,"oficinaCoordenador");
        $chaves[] = opMVal("user","coduser","oficinaCoordenador");

        $lst = new RDLista(array("AMUser","AMOficinaCoordenador"),$chaves);

        return ($lst);
    }


    function removeCoordenador($codUser) {
        $chaves[] = opVal("codUser",$codUser);
        $chaves[] = opVal("codOficina",$this->codOficina);
        $new = new AMOficinaCoordenador($chaves);
        $new->deleta();
    }


    function eCoordenador($codUser) {
        $chaves[] = opVal("codOficina",$this->codOficina);
        $chaves[] = opVal("codUser",$codUser);

        $lst = new RDLista("AMOficinaCoordenador",$chaves);
        if(empty($lst->records)) {
            return 0;
        }

        return 1;
    }

    function addCoordenador($codUser) {
        $cood = new AMOficinaCoordenador();
        $cood->codOficina = $this->codOficina;
        $cood->codUser = $codUser;
        return $cood->salva();
    }

   /** Reimplementa a função de salvar
   *
   * Reimplementa a função de salvar para possibilitar a crição do diretório da oficina
   */
    function salva() {
        global $pathpaginas, $TAB_lastquery;
        $novo = $this->novo;

        parent::salva();

        if($novo>0) {
            if(!empty($_SESSION[ambiente]) && !empty($pathpaginas)) {
                $dir = "$pathpaginas/oficina_".$this->codOficina;

                $_SESSION[ambiente]->execAsRoot("criadir.php",$dir);
                $_SESSION[ambiente]->execAsRoot("copia_arquivo.php","cp ".$path."amadis/paginas/oficina_empty.html $dir/index.html 0");
            }
        }
    }


}
