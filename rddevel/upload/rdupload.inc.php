<?
/**
 */
/**
 * Classe que abstrai um sistema de gerenciamento de diret�rios
 *
 * Essa classe tenta criar uma camada de abstra��o entre a interface
 * de uma ferramenta de upload, e as opera��es que essa realiza sobre o sistema de 
 * arquivos. Al�m disse ela tenta manter a segun�a do sistema ao tentar implementar
 * uma forma rudimentar de chroot l�dico sobre os diret�rios do sistema.
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @abstract
 * @version 0.5
 * @package rddevel
 * @subpackage upload
 * @see RDPagObj
 */
class RDUpload {
    var $pathChroot, $pathAtual;
    var $arvore,$nodoatual;


    function RDUpload($pathini) {
        $pathini = realpath($pathini);
        $this->pathChroot = $pathini;
        $this->pathAtual = $pathini;
        $this->arvore = $this->listaDir($pathini);
        $this->nodoAtual = $this->arvore;
    }
    
    function eDirValido($path) {
         
        $dir = trim($path);
        $dir = realpath($dir);

        $dir.= "/";
        $chroot = substr($dir,0,strlen($this->pathChroot));
        if($chroot!=$this->pathChroot) return 0;
   
        return 1;
    }

    function cd($relativedir) {

        $dir = $this->resolvename($relativedir);
        if(!($this->eDirValido($dir)) ) return 0;


        $this->pathAtual = $dir;
        $this->nodoAtual = $this->listaDir($dir);

    }

    function cdFromRoot($relativedir) {
        $dir = $this->pathChroot.$relativedir;
        $dir = realpath($dir);
        $this->pathAtual = $dir;
        $this->nodoAtual = $this->listaDir($dir);
    }
    
    function refresh() {
        $this->nodoAtual = $this->listaDir($this->pathAtual);
    }
    
    function cdup() {
        
        if(!$this->eRoot()) {
            $this->cd("..");
        };
    }
    
    function eRoot() {
        return ($this->pathAtual==$this->pathChroot);
    }
    
    function listFiles() {
        $dir = $this->pathAtual;

        if(!($dp = @opendir($dir))) return $lang[upload_denied];

        $retdir = array();

        while($arq = readdir($dp)) {
            if($arq=='.' || $arq=='..') continue;
            $fullname = "$dir/$arq";
      //if(!is_dir($fullname)) {
    	//$retdir[$arq] = tipoArquivo($fullname);
      //};
            
        };

        closedir($dp);

    }
    
    function listaDir($dir="") {
        global $lang;

        if(empty($dir)) $dir = $this->pathAtual;
        if(!($dp = @opendir($dir))) return $lang[upload_denied];

        $retdir = array();

        while($arq = readdir($dp)) {
            if($arq=='.' || $arq=='..') continue;
            $fullname = "$dir/$arq";

            if(is_dir($fullname)) {
                $tam  = strlen($this->pathChroot);
                $path = substr($fullname,$tam,strlen($fullname)-$tam+1);

                $retdir[$arq][nome] = $arq;
                $retdir[$arq][rel_nome] = $path;
                $retdir[$arq][tipo] = "dir";
                $retdir[$arq][filhos] = $this->listadir($fullname);
            }
            else {
	//$retdir[$arq][tipo] = tipoArquivo($fullname);
                $retdir[$arq][nome] = $arq;
                $retdir[$arq][tamanho] = filesize($fullname);
            };

        };

        closedir($dp);

        return $retdir;
    }


    function leArquivo($nome) {
        global $pathbin,$PHP_SELF;
        $dir = $this->pathAtual;
        $file = @file("$dir/$nome");
        if(is_array($file))
        $file = implode("",$file);

        return $file;
    }
    
    function salvaArq($arq,$conteudo) {
        global $pathbin,$config_file,$config_ini;

        $dir = $this->pathAtual;

        $tmp = $config_file[paths][pathtemp];
        if (empty($tmp)) $tmp = $config_ini[Diretorios][pathtemp];
        if(empty($tmp)) {
            logger("upload.inc.php","O caminho para o diretorio temporario nao esta configurado");
            return 0;
        };


        $len = strlen($conteudo);
        $fd = fopen($arq,"w");
        $tam = fwrite($fd,$conteudo);
        fclose($fd);

        $this->refresh();

        return $tam;
    }
    
    
    function upload_file($nome,$tmp_file,$dir="") {
        global $pathbin,$PHP_SELF;

        if (empty($dir)) {
            $dir = $this->pathAtual;
        }

        chmod($tmp_file,0755);
    //$ret = trim(exec($pathbin."/wrapper copia_arquivo.php cp \"$tmp_file\" \"$dir/$nome\"",$linhas));
        @move_uploaded_file($tmp_file, "$dir/$nome");
        $this->refresh();
        return $ret;
    }
    
    function change_perms($nome,$perm) {
        global $pathbin;
        $dir = $this->pathAtual;
        chmod($nome, $perm);
//        $ret = trim(exec($pathbin."/wrapper change_perm.php \"$dir/$nome\" \"$perm\"",$linhas));
        $this->refresh();
        return $ret;
    }

    function pwd() {
        
        $tam =strlen($this->pathChroot);
        $path = substr($this->pathAtual,$tam,strlen($this->pathAtual)-$tam);
        if(empty($path)) $path="/";
        return $path;
    }
    
    
    function copia($arq,$dest,$modo="cp",$nome="") {
        global $pathbin;
        if(empty($arq)) return 0;

        $tmp_file = $this->pathAtual."/$arq";

        if(!$this->eDirValido($this->pathChroot."/$dest")) return 0;

        $dir = trim($this->pathChroot."/$dest");
        $dir = realpath($dir);

        if(copy($arq,$dest)) {
            if($modo=="mv") {
                unlink($arq);
            }
        }
        
        $this->refresh();
        return true;
    }

  /**
   * esta funcao eh diferente da anterior em dois aspectos :
   *  - apenas tem a funcao de copiar
   *  - os path dos arquivos source e destino sao relativos ao pathChroot, nao ao pathAtual
   */

    function copia_from_chroot($dir_src,$dir_dst,$nome) {
        global $pathbin;

        $file_src = $this->pathChroot.$dir_src."/".$nome;

        $dir = trim($this->pathChroot."/$dir_dst");
	
        $this->copia($file_src,$dir);
        return true;
    }


  /**
      Copia diretorio recursivamente
      dir_src e dir_dst relativos
  */

    function copia_dir_recursivo($dir_src="",$dir_dst="") {
        global $pathbin;

        $dir_src = $this->pathChroot.$dir_src;
        $dir_src = trim($dir_src);
        $dir_src = realpath($dir_src);

        $dir_dst = $this->pathChroot.$dir_dst;
        $dir_dst = trim($dir_dst);
        $dir_dst = realpath($dir_dst);

        if ($this->eDirValido($dir_src)) {
            if ($this->eDirValido($dir_dst)) {

                $linhas = array();
                $this->copy($dir_src, $dir_src);

                return 1;
            }
            else
            return 0;
        }
        else
        return 0;
    }
    
    function existeArq($arq) {
        return file_exists($this->pathAtual."/$arq");
    }
    
    function criaDir($novodir,$diretorio="") {
        global $pathbin;

        if (empty($diretorio)) {
            $dir = trim($this->pathAtual);
        }
        else {
            $dir = $this->pathChroot.$diretorio;
        }

		mkdir("$dir/$novodir");

        $this->refresh();
      
        return 1;

    }

  /** Funcao estatica para criacao de diretorios. Pode ser chamada sem ter algum objeto instanciado. 
   *
   */
    function staticCriaDir($dir) {
        global $pathbin;
        mkdir($dir);
    }
    
    function apaga($arq,$diretorio="") {
        global $pathbin;
   
        if(empty($arq)) return 0;

    //if(!$this->eDirValido($this->pathAtual."/$arq")) return 0;

        if (empty($diretorio)) {
            $dir = trim($this->pathAtual."/$arq");
        }
        else {
            $dir = trim($this->pathChroot.$diretorio."/$arq");
        }
        $dir = realpath($dir);

        $ret = unlink($dir);
        $this->refresh();
      
        return $ret;
    }
     
    function apaga_recursivo($diretorio="") {
        global $pathbin;

        $dir = trim($this->pathChroot.$diretorio);
        $dir = realpath($dir);
        if ($this->eDirValido($dir)) {
            $ret = trim(exec($pathbin."/wrapper apaga_arquivo.php \"$dir\" \"1\"",$linhas));
            $this->refresh();
            return $ret;
        }
        else {
            return 0;
        }
         
    }

  /** Apaga um arquivo temporario
   *  
   *  @param $nomeArq : Nome do arquivo temporario
   */
    function apagaTmpFile($nomeArq) {
        global $pathbin;

        $dir = "/tmp/";
        if (!empty($nomeArq)) {
            if (strpos($nomeArq,"../") === false) { //confere se o caminho eh correto
                $arq = "/tmp/".$nomeArq;
                unlink($arq);

                return $ret;
            }
        }
        else
        return 0;
    }

  /** Retorna se o arquivo eh um arquivo zip
   *
   *  Apenas checa pelo nome do arquivo
   *  @param $nomeArq : nome do arquivo
   */
    function isZip($nomeArq) {
        $temp = explode('.',$nomeArq);
        $tam = count($temp);
        $ext = strtolower($temp[$tam-1]);

        if($ext=="zip") {
            return 1;
        }
        else
        return 0;
    }
    
    function toZip($arquivos,$dir_atual="") {
        global $pathbin;

        if (empty($dir_atual)) {
            $dirbase = $this->pathAtual;
        }
        else {
            $dirbase = realpath($this->pathChroot.$dir_atual);
        }

        
        $cmd = $pathbin."/wrapper zip.php \"$dirbase\" \"".$_SESSION[sessId]."\"";
        foreach ($arquivos as $arq) {
            if($arq[0]=="/") {
                $temp = explode("/",$arq);
                $arq = "/".$temp[count($temp)-1];
            }
            $cmd .= " \"$arq\" ";
        }

        $ret = trim(exec($cmd,$linhas));

        return $ret;

    }


    function unZip($arquivo="",$dir_atual="") {
        global $pathbin;

        if (empty($dir_atual)) {
            $dirbase = $this->pathAtual;
        }
        else {
            $dirbase = $this->pathChroot.$dir_atual;
        }
        
        $cmd = $pathbin."/wrapper zip.php \"$dirbase\" \"".$_SESSION[sessId]."\" \"unzip\" \"".$arquivo."\"";

        $ret = trim(exec($cmd,$linhas));
        return $ret;

    }

  /** Compacta um arquivo em formato tar.gz
   *
   *
   */
    function toTarGz($arquivos,$dir_atual="") {
        global $pathbin;

        if(!empty($dir_atual)) {
            $dirbase = $this->pathChroot.$dir_atual;
        } else {
            $dirbase = $this->pathChroot.$this->pwd();
        }

        
        $dirbase = realpath($dirbase);

        $cmd = $pathbin."/wrapper tar.php \"$dirbase\" \"".$_SESSION[sessId]."\"";
        foreach ($arquivos as $arq) {
            if($arq[0]=="/") {
                $temp = explode("/",$arq);
                $arq = "/".$temp[count($temp)-1];
            }
            $cmd .= " \"$arq\" ";
        }
        $ret = trim(exec($cmd,$linhas));
        return $ret;

    }
    
    function treeArray($ar="") {

         
        $ret = array();

        if(empty($ar)) { $ar = &$this->arvore; $ret[] = "/"; };

        if (!empty($ar)) {
            
            foreach($ar as $item) {
                if($item[tipo]=="dir") {
                    $ret[] = $item[rel_nome];
                    if(!empty($item[filhos])) $temp = $this->treeArray($item[filhos]);
                    $ret = array_merge($ret,$temp);
                };
            };
        }
         
        return $ret;

    }

  /**
     Vai para raiz
  */
    function cdRoot() {
        $this->pathAtual = $this->pathChRoot;
        $this->refresh();
    }

    function isDir($arq) {
        $arq = $this->resolveName($arq);
        return is_dir($arq);
    }
    
    function resolveName($path) {
        if($path[0]=='/') {
            $path = trim($this->pathChroot.$path);
        }
        else {
            $path = trim($this->pathAtual."/".$path);
        }
         
        $path = realpath($path);


        return $path;
    }

    
};


?>
