<?php

class AMUser extends RDUser
{


    function AMUser($chave="") {

        $this->RDUser();

        $this->addfield("datNascimento","user","bigint","20","0");
        $this->addfield("strEMail","user","varchar","50","0");
        $this->addfield("strEMailAlt","user","varchar","50","0");
        $this->addfield("strMaildir","user","varchar","40","0");
        $this->addfield("desUrl","user","varchar","50","1");
        $this->addfield("desEndereco","user","varchar","150","0");
        $this->addfield("codCidade","user","tinyint","4","0");
        $this->addfield("desCEP","user","varchar","9","0");
        $this->addfield("desTelefone","user","varchar","15","0");
        $this->addfield("desFax","user","varchar","15","1");
        $this->addfield("codEscola","user","tinyint","4","0");
        $this->addfield("desCargo","user","varchar","20","1");
        $this->addfield("desHistorico","user","text","","1");
        $this->addfield("flaAprovado","user","char","1","0");
        $this->addfield("flaAtivo","user","char","1","0");
        $this->addfield("flaHomedir","char","1","0");

        if(!empty($chave)) {

            if(!is_array($chave)) {
                $chaves[] = opVal("codUser",$chave);
            }
            else { $chaves = $chave; };

            $this->le($chaves);
        }
    }

    function getFields(){

        $fields = parent::getFields();
        $fields2 = array("datNascimento","strEmail","strEmaildir","desUrl","desEndereco","codCidade","desCEP","desTelefone","desFax","codEscola","desCargo","desHistorico","flaAprovado","flaAtivo","flaHomedir");
        return array_merge($fields, $fields2);
    }

    function listaProjetos() {
        $chave[] =opVal("codUser", $this->codUser);
        $q1 = new RDLista("AMProjetoMatricula", $chave);

        $tab = AMProjeto::getTables();
        $chave = "($tab.codOwner='".$this->codUser."')";
        $chave.= " OR ($tab.codOrientador='". $this->codUser."')";

        if($q1->numRecords()>0) {
            foreach($q1->records as $item) {
                $chave .= " OR ($tab.codProjeto=".$item->codProjeto.") ";
            }
        }

        $param = new RDParam();
        $param->setSqlWhere($chave);
        
        $q2 = new RDLista("AMProjeto",'','',$param);

        return $q2;
    }


    function listaForunsParticipou() {
        global $pathuserlib,$rdpath;

        $chave[] = opVal("codAutor", $this->codUser,RDMensagemForum::getTables());
        $chave[] = opMVal(AMForumAmadis::getTables(),"codForum",RDMensagemForum::getTables());


        $param=new RDParam();
        $tab = AMForumAmadis::getTables();
        $param->setCamposProjecao(array("$tab.codForum","$tab.nomForum"));
        $param->setDistinct();

        $retorno =  new RDLista("AMForumAmadis",$chave,"",$param);

        return $retorno;
    }


    
    function listaOficinas($force_listagem_todas="") {
        global $pathuserlib;

        $chave = array();
        $chave[] = opVal("codUser", $this->codUser);
        $chave[] = opVal("flaAutorizado", "1");

        $param=new RDParam();
        $param->setCamposProjecao(array("codOficina"));

        $matr = new RDLista("AMOficinaMatricula", $chave, "", $param);
        $coord = $this->listaOficinasCoordenador();

        $query = "";
        if (!empty($matr->records)) {
            foreach ($matr->records as $rec) {
                if (!empty($query)) $query .= " OR ";
                $query .= "codOficina=".$rec->codOficina;
            }
        }

        if (!empty($coord->records)) {
            foreach ($coord->records as $rec) {
                if (!empty($query)) $query .= " OR ";
                $query .= "codOficina=".$rec->codOficina;
            }
        }

        $chaves = array();
        $chaves[] = $query;

        if(!$force_listagem_todas) {
            $chaves[] = opVal("datFim", time(),"oficina",">");
        }

        $param=new RDParam();
        $param->setCamposProjecao(array("codOficina","nomOficina"));

        $retorno = new RDLista("AMOficina",$chaves,"",$param);
        return $retorno;
    }


    function listaOficinasCoordenador($force_listagem_todas=0) {
        global $pathuserlib;

        $chave[] = opVal("codUser", $this->codUser,"oficinaCoordenador");
        $chave[] = opMVal("oficina","codOficina","oficinaCoordenador");

        if(!$force_listagem_todas) {
            $chave[] = opVal("datFim", time(),"oficina",">");
        }


        $param=new RDParam();
        $param->setCamposProjecao(array("oficina.codOficina","oficina.nomOficina"));

        $retorno =  new RDLista(array("AMOficina","AMOficinaCoordenador"),$chave,"",$param);


        return $retorno;
    }



    function listaNoticias() {
        global $pathuserlib;


        $chave[] = opVal("codUser", $this->codUser);
        $retorno =  new RDLista("AMNoticia",$chave, "tempo desc");

        return ($retorno);
    }


    function listaDiario() {
        global $pathuserlib;


        $chave[] = opVal("codPai", $this->codUser);
        $chave[] = opVal("tipoPai", "U");
        $retorno =  new RDLista("AMDiario",$chave,"tempo desc");

        return ($retorno);
    }


    function listaEmails($novos=0) {

        if(!$novos) {
            $chavemail[] = opVal("flaLida", 0);
        }

        $chavemail[] = opVal("codUserDestino", $_SESSION[usuario]->codUser);
        $chavemail[] = opVal("flaCopia", "0");

        $mail = new RDLista ("RDEmailUserDestino", $chavemail, "");


    }


    function listaContatos() {
        global $pathuserlib;

        $chaves[]  = opVal("codOwner",$this->codUser,AMContato::getTables());

        $lst = new RDLista("AMContato",$chaves,$order);

        return $lst;
    }


    function addContato($user) {
        global $pathuserlib,$usercontatos;

        if(empty($usercontatos)) {
            $temp = $this->listaContatos();
            $usercontatos = array();
            if(!empty($temp->records)) {
                foreach($temp->records as $cont) {
                    $usercontatos[$cont->codUser] = 1;
                }
            }
        }



        if(empty($usercontatos[$user])) {
            $contato = new AMContato();
            $contato->codOwner = $_SESSION[usuario]->codUser;
            $contato->codUser = $user;
            $contato->tempo = time();
            $contato->salva();

            return 0;
        }
        else {
            return 1;
        }


    }



    function salva() {

        $this->flaHomedir = 1;
        $novo = $this->novo;
        parent::salva();

        if($novo > 0) {
            $from = "amadis@escola.psico.ufrgs.br";
            $subject = "Bem-vindo ao AMADIS!";
            $body = "<html><body>Seja bem-bindo ao nosso novo ambiente.</body></html>";

            $headers="Content-Type:text/html;CHARSET=iso-8859-8-i\r\n";
            $headers.="From:".$from."\r\n";

            mail($this->strEMail, $subject, $body, $headers);
        }
    }


}
