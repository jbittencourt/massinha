<?php

class AMProjeto extends RDObj
{
    var $imagens;

    function AMProjeto($key="") {

        $table = "projeto";

        $pkFields = "codProjeto";

        $fields_def = array();
        $fields_def[codProjeto] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[desTitulo] = array("type" => "varchar","size" => "60","bNull" => "0");
        $fields_def[codOwner] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[desProjeto] = array("type" => "text","size" => "","bNull" => "0");
        $fields_def[flaEstado] = array("type" => "varchar","size" => "10","bNull" => "0");
        $fields_def[codOrientador] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[codEscola] = array("type" => "tinyint","size" => "4","bNull" => "0");
        $fields_def[codPlataforma] = array("type" => "tinyint","size" => "4","bNull" => "0");
        $fields_def[hits] = array("type" => "bigint","size" => "20","bNull" => "0");
        $fields_def[tempo] = array("type" => "int","size" => "11","bNull" => "0");


        $this->RDObj($this->getTables(),$this->getFields(),$pkFields,$key,$fields_def);

    }


    function getTables() {
        return "projeto";
    }


    function getFields() {
        return array("codProjeto","desTitulo","codOwner","desProjeto","flaEstado","codOrientador","codEscola","codPlataforma","hits","tempo");
    }
    
   /** Reimplementa a função de salvar
   *
   * Reimplementa a função de salvar para possibilitar a crição do diretório do projeto
   */
    function salva() {
        global $pathpaginas,$path;
        $novo = $this->novo;

        parent::salva();

        if($novo>0) {
            if(!empty($_SESSION[ambiente]) && !empty($pathpaginas)) {
                $dir = $pathpaginas."/project_".$this->codProjeto;

                $empty = $pathpaginas . "/projeto_empty.html";
                $index = $dir . "/index.html";
                
				mkdir($dir,0755);
				@copy($empty, $index); //create an default index
            }
        }
    }


    function listaMatriculas() {

        $chave[] = opVal("codProjeto", $this->codProjeto);

        $temp = new RDLista("AMProjetoMatricula", $chave);
        return ($temp);
    }
    
    function listaNovidades() {
        $chave[] = opVal("codProjeto", $this->codProjeto);
        $temp = new RDLista("AMNovidade", $chave, "tempo desc");
        return ($temp);
    }

    function listaComentariosProjeto() {
        $chave[] = opVal("codProjeto", $this->codProjeto);
        $temp = new RDLista("AMComentarioProjeto", $chave, "tempo desc");

        if(!empty($temp->records)) {
            foreach($temp->records as $k=>$rec) {
                if(!empty($rec->codUser)) {
                    $user = new AMUser($rec->codUser);
                    $temp->records[$k]->desNome = $user->nomUser;
                }
            }
        }

        return ($temp);
    }

    function addComentario($comentario,$nome="") {
        $com = new AMComentarioProjeto();
        $com->codProjeto = $this->codProjeto;
        $com->desNome = $nome;

        $com->desComentario = $comentario;
        $com->tempo = time();
        $com->salva();

    }

    function listaStatus() {
        $temp = new RDLista("AMProjetoStatus", "");
        return ($temp);
    }

    function listaEscola() {
        $chave[] = opVal("codEscola", $this->codEscola);
        $temp = new RDLista("AMEscola", $chave);
        return ($temp);
    }

    function listaAreas() {
        $chaves[] = opVal("codProjeto",$this->codProjeto);
        $temp = new RDLista("AMProjetoAreas", $chaves);
        return ($temp);
    }

    function listaDiario() {
        $chaves[] = opVal("codPai",$this->codProjeto);
        $chaves[] = opVal("tipoPai", "P");
        $temp = new RDLista("AMDiario", $chaves, "tempo desc");
        return ($temp);
    }

    function listaForuns() {
        $chaves[] = opVal("codPai",$this->codProjeto);
        $chaves[] = opVal("tipoPai", "P");
        $temp = new RDLista("AMForumAmadis", $chaves, "tempo desc");
        return ($temp);
    }

    function listaChats() {
        $chaves[] = opVal("codPai",$this->codProjeto);
        $chaves[] = opVal("tipoPai", "P");
        $temp = new RDLista("AMChatSala", $chaves, "tempo desc");
        return ($temp);
    }

  /** Matricula um novo usuário no projeto
   *
   * Matricula un novo usuário no projeto
   *
   * @param integer $user Código do usuário a ser matriculo
   */
    function matricula($user) {

        $mat = new AMProjetoMatricula();
        $mat->codProjeto = $this->codProjeto;
        $mat->codUser = $user;
        $mat->tempo = time();
        $mat->salva();
    }


    function addArea($codArea) {
        $area = new AMProjetoAreas();
        $area->codArea = $codArea;
        $area->codProjeto = $this->codProjeto;
        $area->salva();
    }

    function eMembro($codUser) {
        $chaves[] = opVal("codProjeto",$this->codProjeto);
        $chaves[] = opVal("codUser",$codUser);

        $mat = new AMProjetoMatricula($chaves);
  
        return ($mat->novo==0);

    }

    function eOrientador($codUser) {
        return ($this->codOrientador==$codUser);
    }

  /** Lista  os membros da equipe mas sem o orientador e o dono do projeto
   */

    function listaEquipe() {
        $chaves[] = opVal("codProjeto",$this->codProjeto,AMProjetoMatricula::getTables());
        $chaves[] = opMVal(AMUser::getTables(),"codUser",AMProjetoMatricula::getTables());

        $param = new RDParam();
        $tab = AMUser::getTables();
        $param->setCamposProjecao(array("$tab.codUser","$tab.nomPessoa"));
        $lst = new RDLista("AMUser", $chaves,'',$param);

        return ($lst);
    }


  /** Lista todos os membros da equipe, incluindo o orientador e o dono do projeto
   */
    function listaEquipeCompleta() {
        $chaves[] = opVal("codProjeto",$this->codProjeto);

        $lst = new RDLista("AMProjetoMatricula", $chaves,'');

        $chaves = " codUser='".$this->codOwner."' ";
        if($lst->numRecs>0) {
            foreach ($lst->records as $matr) {
                $chaves.=" OR codUser='".$matr->codUser."' ";
            }
        }

        if(!empty($this->codOrientador)) {
            $chaves.=" OR codUser='".$this->codOrientador."' ";
        }

        $param = new RDParam();
        $tab = AMUser::getTables();
        $param->setCamposProjecao(array("$tab.codUser","$tab.nomUser","$tab.nomPessoa"));
        $param->setSqlWhere($chaves);

        $lst = new RDLista("AMUser", '','',$param);

        return ($lst);

    }
    
    function listaMatriculasEquipe() {
        $chaves[] = opVal("codProjeto",$this->codProjeto);
    
        $lst = new RDLista("AMProjetoMatricula",$chaves);

        return ($lst);
    }

}








