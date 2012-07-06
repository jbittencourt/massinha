<?php
include_once("$rdpath/forum/rdmensagemforum.inc.php");

/**
 * Classe que implementa as funcionalidades da ferramenta de f�rum
 *
 * Classe que implementa as funcionalidades da ferramenta de f�rum
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage forum
 * @see  RDForumMensagem, RDFerramenta
 */

class RDForum extends RDObj {
    var $pais=array(), $mensagens=array();

    function RDForum($chave="") {

        $pkFields = "codForum";

        $fields_def = array();
        $fields_def[codForum] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[nomForum] = array("type" => "varchar","size" => "60","bNull" => "0");
        $fields_def[tempo] = array("type" => "int","size" => "11","bNull" => "0");
        $this->RDObj($this->getTables(),$this->getFields(),$pkFields,$key,$fields_def);
    }

    function getTables() {
        return "Forum";
    }

    function getFields() {
        return array("codForum","nomForum","tempo");
    }

  //funcao que retorna um array com todas as mensagens e todos seus campos
    function listaMensagens() {
        $chave[] = opVal("codForum",$this->codForum);
        $chave[] = opMVal(RDMensagemForum::getTables(),"codAutor",RDUser::getTables(),"codUser");

        $param = new RDParam();
        $tab = RDMensagemForum::getTables();
        $fields = RDMensagemForum::getFields();
        $search_fields = array();
        foreach($fields as $field) {
            $search_fields[] = "$tab.$field";
        }
        $search_fields[] = RDUser::getTables().".nomPessoa";
        $param->setCamposProjecao($search_fields);

        $temp = new RDLista("RDMensagemForum",$chave,"tempo asc",$param);

     
        if ($temp->records != "0")   {
            foreach($temp->records as $k=>$men) {
                $mens[$men->codMensagem] = array("mensagem"=>$men,"filhos"=>array());
            }
        }
        if ($mens != "") {
            foreach($mens as $cod=>$men) {
                if($men[mensagem]->codMensagemPai!=0) {
                    $filhos = &$mens[$men[mensagem]->codMensagemPai][filhos];
                    $filhos[] = $cod;
                }
            }
        }
        return $mens;
    }
    
    function organizaMensagens($cod,$mens,$n=0) {

        $men = &$ret[];
        $men[mensagem] = $mens[$cod][mensagem];
        $men[geracao] = $n;

        $filhos = $mens[$cod][filhos];

        if(!empty($filhos)) {
            foreach($filhos as $k) {
                $temp = $this->organizaMensagens($k,$mens,$n+1);
                $ret = array_merge($ret, $temp);
            }
        }
        return ($ret);
    }

    function setFilhas($cod, $geracao) {
        $ret = array();
        if (!empty($this->pais[$cod])) {
            foreach ($this->pais[$cod] as $mens) {
                $ret[$mens->codMensagem] = $mens;
                $ret[$mens->codMensagem]->intGeracao = ($geracao + 1);
                if (!empty($this->pais[$mens->codMensagem]))
                $ret[$mens->codMensagem]->filhas = $this->setFilhas($mens->codMensagem,$ret[$mens->codMensagem]->intGeracao);
                else $ret[$mens->codMensagem]->filhas = array();
            }
        }
        return ($ret);
    }
}

//classe que define cada f�rum
class RDMensagensLidas extends RDObj {
    
    function RDMensagensLidas($chave="") {
        $tabelaTexto = "mensagensLidas";
        $camposTexto = array("codUser","codMensagem", "flaLida");

        $chaveP = array("codUser", "codMensagem");

        $def = array();
        $def[codUser] = array("type" => "int", "size" => "11", "bNull" => "0");
        $def[codMensagem] = array("type" => "int", "size" => "11", "bNull" => "0");
        $def[flaLida] = array("type" => "char", "size" => "1", "bNull" => "0");

        $this->RDObj($tabelaTexto,$camposTexto,$chaveP,$chave);
    }
}

?>