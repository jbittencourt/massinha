<?


/**
 * Classe que implementa uma mensagem de um f�rum
 *
 * Classe que implementa uma mensagem de um f�rum
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage forum
 * @see  RDForum,RDObj
 */
class RDMensagemForum extends RDObj {
    var $imagens;

    function RDMensagemForum($key="") {
        $pkFields = "codMensagem";
        $fields_def = array();
        $fields_def[codMensagem] = array("type" => "bigint","size" => "20","bNull" => "0");
        $fields_def[codForum] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[codAutor] = array("type" => "bigint","size" => "20","bNull" => "0");
        $fields_def[strTitulo] = array("type" => "tinytext","size" => "","bNull" => "0");
        $fields_def[desCorpo] = array("type" => "text","size" => "","bNull" => "0");
        $fields_def[codMensagemPai] = array("type" => "bigint","size" => "20","bNull" => "0");
        $fields_def[tempo] = array("type" => "bigint","size" => "20","bNull" => "0");
        $fields_def[relacao] = array("type" => "tinytext","size" => "","bNull" => "0");

        $this->RDObj($this->getTables(),$this->getFields(),$pkFields,$key,$fields_def);
    }
    
    function getTables() {
        return "Mensagem";
    }

    function getFields() {
        return array("codMensagem","codForum","codAutor","strTitulo","desCorpo","codMensagemPai","tempo","relacao");
    }


    function listaImagens() {
        $chave[] = opVal("codMensagem",$this->codMensagem);
        $temp = new RDLista("RDForumImagem",$chave,"");

        return $temp;
    }
    
    function listaFilhas() {
        $chave[] = opVal("codMensagemPai", $this->codMensagem);
        $temp = new RDLista("RDMensagemForum", $chave, "tempo asc");
        return ($temp);
    }
    //uma imagem s� pode ser adicionada antes que a mensagem foi salva
    function addImagem($img) {
        if(strtolower(get_class($img)) == "rdimagem") {
            $this->imagens[] = $img;
        }

    }

    function salva() {
        $novo = $this->novo;
        parent::salva();   //chama o salva de rdobj
        

        //salva as imagens se for uma nova mensagem
        if(($novo!=0) && (!empty($this->imagens))){
            foreach($this->imagens as $k=>$img) {
                $img = $this->imagens[$k];   //n�o sei pq, mas funcina
                $img->tempo = time();
                $img->salva();

                $dado = new RDForumImagem();
                $dado->codMensagem = $this->codMensagem;
                $dado->codArquivo = $img->codArquivo;
                $dado->salva();
            }
        }
    }
}


?>