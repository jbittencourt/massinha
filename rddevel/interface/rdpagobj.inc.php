<?php
/**
 * RDPagObj � o principal arquivo de interfaces do ROODA Devel. Ele � a classe de base para construir arquivos HTML
 *
 * RDPagObj  � o principal arquivo de interfaces do ROODA Devel. Ele � a classe de base para construir arquivos HTML
 * pois permite que as subclasses adicionem linhas atrav�s do comando add() e depois gerem o c�digo fonte atrav�s do
 * comando imprime(). Atrav�s de add() podem ser adicionados outros objetos descendentes de RDPagObj, pois o comando
 * imprime() reconhece o objeto e chama recursivamente o imprime() deste objeo.
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage interface
 */

class RDPagObj {
  /**
   * @var array $body Cont�m os itens adicionados atrav�s do comando add
   */
    var $nome, $body;
    var $force_newline;


  /**
   * Inicialize the object propreties with the default value.
   */
    function RDPagObj() {
        $this->force_newline = 1;
    }


  /**
   * Adiciona um string ou um RDPagObj ao c�digo html atual
   *
   * @param mixed $line Um string representando um c�digo HTML ou outro RDPagObj
   * @access public
   */
    function add($line) {
        if(is_string($line)) $line .= "\n";
        $this->body[]=$line;
    }


  /**
   * Adiciona um comando Javascript a um RDPagObj
   *
   * @param mixed $jscommadns Um string representando um c�digo Javascript ou outro RDPagObj que gere um javascript
   * @access public
   */
    function addScript($jscommands,$file=0) {
        if($file) {
            $srcfile = " src=\"$jscommands\" ";
        }
        $line = "<script language=\"JavaScript1.2\" type=\"text/javascript\" $srcfile>";
        if(!$file) $line.=$jscommands;
        $line.="</script>";
        $this->body[] = $line;
    }

     
  /**
   * Retorna como string a saida do comando imprime()
   *
   * Esse comando utiliza os comandos de redirecionamento ob_start() e ob_get_contents para capturar
   * a saida da fun��o imprime() que normalmente seria direcionado para o cliente(browser) e retona
   * ele como um string.
   *
   * @access public
   * @return string Retorna o conte�do da fun��o imprime()
   */
    function toString() {
        ob_start();
        $this->imprime();
        $ret_str = ob_get_contents();
        ob_end_clean();

        return $ret_str;
    }



    function requires($file,$type="JS") {
        global $RD_DEVEL_GLOBAL;
        $RD_DEVEL_GLOBAL[pag_requires][$file] = array("file"=>$file,
						  "type"=>$type);
    }


    function preLoadImage($imgurl) {
        global $RD_DEVEL_GLOBAL;

        if(empty($RD_DEVEL_GLOBAL[preloadimages])) {
            $this->requires("load_swap.js");
        }

        $RD_DEVEL_GLOBAL[preloadimages][$imgurl] = $imgurl;

    }


  /**
   * Envia para o cliente(browser) o HTML referente ao objeto
   *
   * A fun��o imprime � o principal comando de RDPagObj na medida em que ela � respons�vel por percorrer
   * todos os itens adicionados atrav�s da fun��o add(), identificar quais s�o strings e quais s�o subclasses
   * de RDPagObj, e dar tratamento a eles. No caso dos strings, ela os imprime diretamente para  o browser, se
   * for um objeto instanciado de uma subclasse de RDPagObj, ele chama a fun��o imprime desse pr�prio objeto.
   * Uma procedimento normal na constru��o de subclasses de RDPagObj � re-implementar a classe imprime(), fazendo
   * as impress�es e configura��es necess�rias e depois chamando a fun��o  imprime() de RDPagObj atrav�s do comando
   * parent::imprime(). Um exemplo de uso dessa t�cnica � a classe RDPagina.
   *
   * @see RDPagina
   * @access public
   */
    function imprime()  {
         
        if(!empty($this->body)) {
            reset($this->body);
            foreach($this->body as $item) {
                if(is_string($item)) {
                    //$item = utf8_encode($item);
                    if($this->force_newline) {
                        printf("%s\n",stripslashes($item));
                    }
                    else {
                        printf("%s",stripslashes(trim($item)));
                    }
                }
                else {
                     
                    if((is_subclass_of($item,"rdpagobj")) || (strtolower(get_class($item))=="rdpagobj")) {
                        $item->force_newline = $this->force_newline;
	  
                        print ("\n");

                        $item->imprime();
                    }
                     
                };
            };
     
        };
    }

}

