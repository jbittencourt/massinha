<?php

class AMambiente extends RDAmbiente {


    function AMambiente($login="",$senha="",$url_erro="") {
        
        $this->setUserClass("AMUser","amuser.inc.php");
        $this->rdambiente($login,$senha,$url_erro);

        $_SESSION[biblioteca] = new AMBiblioteca();
    }


    function listaAreas() {
        $temp = new RDLista("AMArea");
        return ($temp);
    }

    function listaPlataformas() {
        $temp = new RDLista("AMPlataforma");
        return ($temp);
    }

    function listaForunsAmadis($tipo="", $cod="") {
        if ($tipo) {
            $chave[] = opVal("tipoPai", $tipo);
            if ($cod) {
                $chave[] = opVal("codPai", $cod);
            }
        }
        $temp = new RDLista("AMForumAmadis", $chave, "tempo desc");
        return ($temp);
    }

    function listaProjetos() {
        global $config_ini;

        $param = new RDParam();

        if($config_ini[Ambiente][plataforma_master] != 1){
            $chave[] = opVal("codPlataforma", $config_ini[Ambiente][plataforma_cod]);
            $param->setCamposProjecao(array("codProjeto","desTitulo","desProjeto","tempo"));
        }else {
            
            $chave[] = opmval(AMProjeto::getTables(),"codPlataforma",AMPlataforma::getTables());

   
            $f = AMProjeto::getFields();
            $fields = array();
            foreach($f as $item)
            $fields[] = AMProjeto::getTables().".".$item;

            $fields[] = AMPlataforma::getTables().".descrPlataforma";

            $param->setCamposProjecao($fields);
  
        }
        
        $proj = new RDLista("AMProjeto",$chave,"tempo asc", $param);
        return $proj;

    }

    function listaProjetosEscola($codEscola){
        global $config_ini;

        $param = new RDParam();

        if($config_ini[Ambiente][plataforma_master] != 1){

            $chave[] = opVal("codPlataforma", $config_ini[Ambiente][plataforma_cod]);
            $chave[] = opval("codEscola",$codEscola);
            $param->setCamposProjecao(array("codProjeto","desTitulo","tempo"));

        } else {

            $chave[] = opval("codEscola",$codEscola);
            $chave[] = opmval(AMProjeto::getTables(),"codPlataforma",AMPlataforma::getTables());


            $tp = AMProjeto::getTables();
            $fields = array($tp.".codProjeto",$tp.".desTitulo","$tp.tempo");

            $fields[] = AMPlataforma::getTables().".descrPlataforma";

            $param->setCamposProjecao($fields);

        }
        $proj = new RDLista("AMProjeto",$chave,"tempo asc", $param);

        return $proj;

    }


    function listaTodosProjetos(){
        $proj = new RDLista("AMProjeto","","tempo asc");
        return $proj;

    }


    function listaTopProjetos($num=5,$master="") {
        global $config_ini;

        $param = new RDParam();

        if($config_ini[Ambiente][plataforma_master] != 1){
            
            $chave[] = opVal("codPlataforma", $config_ini[Ambiente][plataforma_cod],AMProjeto::getTables());
            $chave[] = opmval(AMProjeto::getTables(),"codPlataforma",AMPlataforma::getTables());
    
            $f = AMProjeto::getFields();
            $tb = AMProjeto::getTables();

            $fields = array($tb.".codProjeto",$tb.".desTitulo",$tb.".hits");


            $param->setCamposProjecao($fields);

        } else {
            
            $chave[] = opmval(AMProjeto::getTables(),"codPlataforma",AMPlataforma::getTables());

   
            $f = AMProjeto::getFields();
            $tb = AMProjeto::getTables();

            $fields = array($tb.".codProjeto",$tb.".desTitulo",$tb.".hits");
            $fields[] = AMPlataforma::getTables().".descrPlataforma";

            $param->setCamposProjecao($fields);
        }
        $tableP = AMProjeto::getTables();
        $tablePl = AMPlataforma::getTables();
        $proj = new RDLista("AMProjeto",$chave,"hits desc", $param);
        $i=0;
        $ret = array();
        if(!empty($proj->records)){
            foreach($proj->records as $item){
                if($i<$num){
                    $ret[] = $proj->records[$i];
                }
                $i++;
            }
        }
        return $ret;
    }


    function listaUsuarios($campos="") {
        global $config_ini;

        $chave[] = opVal("flaAtivo", "1");
        $chave[] = opVal("flaAprovado", "1");

        if ($config_ini[Ambiente][plataforma_master] != "1") {
            $chave[] = opVal("codPlataforma", $config_ini[Ambiente][plataforma_cod]);
        }

        if(!empty($campos)) {
            $param = new RDParam();
            $param->setTipoProjecao(RDCURSOR_PROJ_NENHUM_CAMPO);
            $param->setCamposProjecao($campos);
        }

        $users = new RDLista("AMUser",$chave,"nomPessoa asc",$param);
        return ($users);
    }

    function listaTodosUsuarios($order="nomPessoa asc") {
        $users = new RDLista("AMUser",$chave,$order);
        return ($users);
    }

    function listaSuperUsuarios() {
        $chave[] = opVal("flaAtivo", "1");
        $chave[] = opVal("flaAprovado", "1");
        $chave[] = opVal("flaSuper", "1");
        $users = new RDLista("AMUser",$chave,"nomPessoa asc");
        return ($users);
    }

    function listaUsuariosInativos() {
        $chave[] = opVal("flaAtivo", "0");
        $users = new RDLista("AMUser",$chave,"nomPessoa asc");
        return ($users);
    }

    function listaUsuariosNaoAprovados() {
        $chave[] = opVal("flaAprovado", "0");
        $users = new RDLista("AMUser",$chave,"nomPessoa asc");
        return ($users);
    }

    function listaUsuariosRejeitados() {
        $chave[] = opVal("flaAprovado", "2");
        $users = new RDLista("AMUser",$chave,"nomPessoa asc");
        return ($users);
    }



    function listaUsuariosPlataforma($plat,$chaves=array(),$campos="") {
        $chave[] = opVal("codPlataforma", $plat);
        $param = new RDParam();
        if(empty($campos))
        $campos = array("codUser","nomPessoa");
        $param->setCamposProjecao($campos);

        if(!empty($chaves)) {
            $chave = $chaves;
        }

        $users = new RDLista("AMUser",$chave,"nomPessoa asc",$param);
        return $users;
    }


  /** Lista um sumarios dos usuarios com os numero de forums, diarios, chats
   *
   *  Lista um sumarios dos usuarios com os numero de forums, diarios, chats
   */
    function listaSumarioUsuarios($chaves=array(),$campos="") {
        global $pathuserlib,$rdpath,$config_ini;

        if ($config_ini[Ambiente][plataforma_master] != "1") {
            $chaves[] = opVal("codPlataforma", $config_ini[Ambiente][plataforma_cod],AMUser::getTables());
        };

  

        $param = new RDParam();
        $tabUser = AMUser::getTables();
        if(empty($campos)) {
            $campos = array("codUser","nomPessoa","nomUser");
        }
        $param->setCamposProjecao($campos);

        $users = new RDLista("AMUser",$chaves,"nomPessoa asc",$param);

        return $users;

    }



    function listaUsuariosEscola($escola, $plat=0) {
        global $config_ini;

        $chave[] = opVal("codEscola", $escola);

        if (!empty($plat)) {
            $chave[] = opVal("codPlataforma", $plat);
        }
        else {
            if ($config_ini[Ambiente][plataforma_master] != "1") {
                $chave[] = opVal("codPlataforma", $config_ini[Ambiente][plataforma_cod]);
            }
        }

        $param = new RDParam();
        $param->setCamposProjecao(array("codUser","nomUser","nomPessoa","codEscola"));

        $users = new RDLista("AMUser",$chave,"nomPessoa asc",$param);
        return $users;
    }



    function listaOficinas() {
        $oficinas = new RDLista("AMOficina","","tempo asc");
        return ($oficinas);
    }

    function listaEscolas() {
        $param = new RDParam();
        $param->setCamposProjecao(array("codEscola","nomEscola"));
        $lst = new RDLista("AMEscola","","nomEscola asc",$param);
        return $lst;
    }

    function listaCidades() {
        $lst = new RDLista("AMCidade","","nomCidade");
        return $lst;
    }

    function listaSalasChatTodosTipos(){
        global $pathuserlib, $config_ini;

        if(!$todas)
        $chaves[] = opVal("datFim",time()-$config_ini[Chat][room_timeout],"",">");

        if(!$multiplataformas)
        $chaves[] = opVal("codPlataforma",$config_ini[Ambiente][plataforma_cod]);

  //  $chaves[] = opVal("tipoPai","G");


        $order = "tempo DESC";
        $lst = new RDLista("AMChatSala",$chaves,$order);

        return $lst;

    }

    function listaSalasChatGerais($todas=0,$multiplataforma=0) {
        global $pathuserlib, $rdpath,  $config_ini;

        if(!$todas)
        $chaves[] = opVal("datFim",time()-$config_ini[Chat][room_timeout],"",">");

        if(!$multiplataformas)
        $chaves[] = opVal("codPlataforma",$config_ini[Ambiente][plataforma_cod]);

        $chaves[] = opVal("tipoPai","G");


        $order = "tempo DESC";
        $lst = new RDLista("AMChatSala",$chaves,$order);

        return $lst;
    }

    function listaSalasChatOficinas($todas=0) {
        global $pathuserlib, $rdpath, $config_ini;

        if(!$todas)
        $chaves[] = opVal("datFim",time()-$config_ini[Chat][room_timeout],"",">");

        $coduser = $_SESSION[usuario]->codUser;

        $chaves[] = opVal("codUser",$coduser,AMOficinaMatricula::getTables());
        $chaves[] = opVal("tipoPai","O",AMChatSala::getTables());
        $chaves[] = opMVal(AMChatSala::getTables(),"codPai",AMOficinaMatricula::getTables(),"codOficina");
        $tab = AMChatSala::getTables();
        $param = new RDParam();
        $param->setCamposProjecao(array("$tab.codSala","$tab.nomSala","$tab.datInicio",AMOficinaMatricula::getTables().".codOficina"));

        $lista = new RDlista(array("AMChatSala","AMOficinaMatricula"),$chaves,"",$param);

        return $lista;


    }

    function listaSalasChatProjetos($todas=0) {
        global $pathuserlib, $rdpath, $config_ini;

        if(!$todas)
        $chaves[] = opVal("datFim",time()-$config_ini[Chat][room_timeout],"",">");

        $coduser = $_SESSION[usuario]->codUser;

        $chaves[] = opVal("codUser",$coduser,AMProjetoMatricula::getTables());
        $chaves[] = opVal("tipoPai","P",AMChatSala::getTables());
        $chaves[] = opMVal(AMChatSala::getTables(),"codPai",AMProjetoMatricula::getTables(),"codProjeto");

        $tab = AMChatSala::getTables();
        $param = new RDParam();

        $param->setCamposProjecao(array("$tab.codSala","$tab.nomSala","$tab.datInicio",AMProjetoMatricula::getTables().".codProjeto"));
        $lista = new RDlista(array("AMChatSala","AMProjetoMatricula"),$chaves,"",$param);

        return $lista;


    }

    function user_by_username($username) {
        $chave[] = opVal("nomUser", $username);
        $user = new AMUser($chave);
        return ($user);
    }

    function user_by_name($username) {
        $chave[] = opVal("nomPessoa", $username);
        $user = new AMUser($chave);
        return ($user);
    }


    function listaAnuncios($key="") {
        global $pathuserlib;

        $f = AMAnuncio::getFields();
        $fields = array();
        foreach($f as $item)
        $fields[] = AMAnuncio::getTables().".".$item;

        $fields[] = AMProjeto::getTables().".desTitulo";

        $param = new RDParam();
        $param->setCamposProjecao($fields);

        if($key=="") {
            
            $chaves[] = opMVal(AMAnuncio::getTables(),"codProjeto",AMProjeto::getTables());


        }else{
            
            $chaves[] = opval("codAnuncio",$key);
            $chaves[] = opMVal(AMAnuncio::getTables(),"codProjeto",AMProjeto::getTables());

        }
        
        $temp = new RDLista("AMAnuncio",$chaves,"tempo desc",$param);

        return ($temp);
    }

    function procuraUser($username="",$turma="",$escola=0) {
        global $pathuserlib;


        if(!empty($username)) {
            $tab = AMUser::getTables();
            $chaves[] = "(nomUser LIKE \"%$username%\" OR $tab.nomPessoa LIKE \"%$username%\")";
        }

        if(!empty($turma)) {

            $tab = AMTurma::getTables();
            $chaves[] = opVal("nomTurma","%$turma%",$tab,"LIKE");
            $chaves[] = opMVal(AMUser::getTables(),"codUser", AMUserTurma::getTables());
            $chaves[] = opMVal(AMUserTurma::getTables(),"codTurma", AMTurma::getTables());
        }


        if(!empty($escola)) {
            $chaves[] = opVal("codEscola",$escola,AMUser::getTables());
        }

        $lst = new RDLista("AMUser",$chaves,"nomPessoa");

        return $lst;

    }

}



?>
