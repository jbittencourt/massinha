<?
/*
*  This program is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  This program is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU Library General Public License for more details.
*
*  You should have received a copy of the GNU General Public License
*  along with this program; if not, write to the Free Software
*  Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
*/
//include_once("$rdpath/base/rdagenda.inc.php");
//include_once("Date/Calc.php");

/**
 * Classe que define um usuarios
 *
 * @author Maicon Browers <maicon@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage base
 * @see RDObj
 */
class RDUser extends RDObj {

    var $desSenhaAnt;
    var $force_create_email;
    var $force_create_homedir;

    var $fields = array("codUser","nomUser","desSenha","desSenhaPlain","tempo","nomPessoa","codPlataforma");
    var $pkFields = "codUser";

    function RDUser($chave="") {

        $fields_def = array();
        $fields_def[codUser] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[nomUser] = array("type" => "varchar","size" => "40","bNull" => "0");
        $fields_def[desSenha] = array("type" => "varchar","size" => "60","bNull" => "0");
        $fields_def[desSenhaPlain] = array("type" => "varchar","size" => "30","bNull" => "0");
        $fields_def[nomPessoa] = array("type" => "varchar","size" => "50","bNull" => "0");
        $fields_def[tempo] = array("type" => "bigint","size" => "20","bNull" => "0");
        $fields_def[codPlataforma] = array("type" => "tinyint","size" => "4","bNull" => "0");

        $this->RDObj($this->getTables(),$this->fields,$this->pkFields,$key,$fields_def);

    //usado para controlar quando o usuario mudou da senha
    //e criptografala novamente
        $this->desSenhaAnt = $this->desSenha;
    }


    function getTables() {
        return "user";
    }

    function getFields() {
        return array("codUser","nomUser","desSenha","desSenhaPlain","flaSuper","nomPessoa","tempo","codPlataforma");
    }

  /**
   * Essa funcao e implementada devido a necessidade de registrar as mudancas de senha. Ela e utilizada pelo RDLista para povoar o objeto com os dados da tabela.
   */
    function parseFieldsfromArray($reg) {
        $ret = parent::parseFieldsFromArray($reg);
        $this->desSenhaAnt = $this->desSenha;

        return $ret;
    }
    
  //retorna se usuario eh super usuario  
    function eSuper() {
        if ($this->flaSuper==1 || $this->flaSuper=="S")
        return 1;
        else return 0;
    }

  //funcao que retorna se o usuario atual participa no grupo cujo codigo foi passado como parametro
    function participaGrupo($codGrupo) {
        if (!empty($codGrupo)) {
            $grupo = new TCGrupo($codGrupo);
            $equipe = $grupo->listaEquipe();
            $participa = 0;
            foreach ($equipe->records as $user) {
                if ($this->codUser == $user->codUser)
                $participa =1;
            }
            return $participa;
        }
        
        return 0;
    }

  //lista os compromissos do usuario em determinada data
    function listaCompromissos($dia="",$mes="",$ano="") {
        if (empty($dia)) {
            $data = getDate();
            $dia = $data[mday];
            $mes = $data[mon];
            $ano = $data[year];
        }

        $data_0_0 = strtotime("$mes/$dia/$ano 00:00");
        $data_23_59 = strtotime("$mes/$dia/$ano 23:59:59");
        $chaves = array();
        $chaves[] = opVal("timeData",$data_0_0,"",">=");
        $chaves[] = opVal("timeData",$data_23_59,"","<=");
        $chaves[] = opVal("codUser",$this->codUser);
        $ordem = "timeData desc";

        $compromissos = new RDLista("RDCompromisso",$chaves,$ordem);
        return $compromissos;
    }

  /**
   * Retorna um array contento o número de compromissos que os dias que o usuário tem compromisso naquele mês
   *
   * Esse funcão tem como principal objetivo possibilitar ao usuário construir calendários sabendo que dias 
   * que existem compromissos para cada usuário.
   *
   * @access public
   * @param integer mes Mes a ser pesquisado
   * @param integer ano Ano a ser pesquisado
   * @return array Retorna um array contento os dias como índice e o número de compromissos como valor;
   */
    function listaDiasDeCompromisso($mes="",$ano="") {


        $data_0_0 = strtotime("$mes/1/$ano 00:00");
        $data_23_59 = strtotime("$mes/".Date_Calc::daysinMonth($mes,$ano)."/$ano 23:59:59");


        $chaves = array();
        $chaves[] = opVal("timeData",$data_0_0,"",">=");
        $chaves[] = opVal("timeData",$data_23_59,"","<=");
        $chaves[] = opVal("codUser",$this->codUser);
        $ordem = "timeData desc";

        $compromissos = new RDLista("RDCompromisso",$chaves,$ordem);

        $dias = array();

        if(!empty($compromissos->records)) {
            foreach($compromissos->records as $comp) {
                $dia = date("d",$comp->timeDATA);
                $dias[$dia]++;
            };
        };


        return $dias;
    }


    function salva() {
        global $pathpaginas,$path,$config_ini;

        if(($this->desSenha!=$this->desSenhaAnt)) {
            if($config_ini[Ambiente][store_plain_password])
            $this->desSenhaPlain = $this->desSenha;

            if($config_ini[Ambiente][use_md5_password])
            $this->desSenha = md5($this->desSenha);
        }
        
        if($config_ini[email][imap_email]) {
            if(empty($this->strEMail) || $this->force_create_email) {
                $this->strEMail = "$this->nomUser@".$config_ini[email][domain];
                $this->strMaildir = $config_ini[email][mailbox_dir]."/$this->nomUser/";

                $_SESSION[ambiente]->execAsRoot("maildirmake.php ",$this->strMaildir);
            }
            
        };


        if(!empty($config_ini[Ambiente][plataforma_cod])) {
            $this->codPlataforma = $config_ini[Ambiente][plataforma_cod];
        }

        $novo = $this->novo;

        parent::salva();

        if(($novo>0) ||  ($this->force_create_homedir)) {
            if((!empty($_SESSION[ambiente])) && (!empty($pathpaginas)) && ($this->flaHomedir==1)) {
                $dir = $pathpaginas."/user_".$this->codUser;
                mkdir($dir);
            }
        }
    }
    
}

?>