<?

include_once("$rdpath/upload/rdupload.inc.php");
include_once("$rdpath/smartform/wicon.inc.php");
include_once("$rdpath/smartform/wform.inc.php");

/**
 * Implemente uma classe de ferramenta para o upload
 * @author Maicon Brauwers <maicon@edu.ufrgs.br>
 * @access public
 * @abstract
 * @version 0.5
 * @package rddevel
 * @subpackage upload
 * @see RDPagObj
 */
class RDFUpload extends RDFerramenta {
    var $upload;
    var $baseurl;

    function RDFUpload($basePath,$dir="") {
        global $rdpath, $rootpath;

		if(empty($basePath)) {
		    die("Fatal Error: No base path informed!");
		}
        
        $this->upload = new RDUpload($basePath);
        if (!empty($dir)) {
            $this->upload->cd($dir);
        }
        
    //configura o tema
        $this->tema = parse_ini_file("$rootpath/etc/tema_upload.ini");


        $this->extencoes['gif'] = "image/gif";
        $this->extencoes['html'] = "text/html";
        $this->extencoes['htm'] = "text/html";
        $this->extencoes['jpg'] = "image/jpeg";
        $this->extencoes['jpeg'] = "image/jpeg";
        $this->extencoes['png'] = "image/png";
        $this->extencoes['psd'] = "image/psd";
        $this->extencoes['zip'] = "application/x-zip";
        $this->extencoes['tar'] = "application/x-bzip";
        $this->extencoes['tgz'] = "application/x-gzip";
        $this->extencoes['gz'] = "application/x-gzip";
        $this->extencoes['Z'] = "application/x-compress";
        $this->extencoes['doc'] = "doc";
        $this->extencoes['ppt'] = "application/vnd.ms-powerpoint";
        $this->extencoes['php'] = "application/x-php";
        $this->extencoes['php3'] = "application/x-php";
        $this->extencoes['txt'] = "text/plain";
        $this->extencoes['xml'] = "text/xml";
        $this->extencoes['xls'] = "application/vnd.ms-excel";
        $this->extencoes['rtf'] = "application/rtf";
        $this->extencoes['sdw'] = "application/vnd.sun.xml.writer";
        $this->extencoes['sdx'] = "application/vnd.sun.xml.impress";
        $this->extencoes['bak'] = "application/x-backup";
        $this->extencoes['bz2'] = "application/x-bzip";
        $this->extencoes['class'] = "application/x-java-byte-code";



    }
    
    function makeFormEnvio($url_action,$num_of_input=1) {
        $form = new WForm("form_upload",$url_action,"","multipart/form-data");
        $form->add("<TABLE>");

        for ($i=1; $i<=$num_of_input; $i++) {
            $w_file = new WFile("arquivo".$i);
            if (($i % 2) == 1) {
                $form->add("<TR>");
            }
            $form->add("<TD>");
            $form->add($w_file);
            $form->add("</TD>");
        }

        $botaoSubmit = new WButton("submit1","Enviar");
        $botaoCancelar = new WButton("cancel","Cancelar","button");
        $botaoCancelar->setOnClick("window.location.href = '".$_SERVER[PHP_SELF]."?acao=A_diretorio&diretorio=".$_SESSION[diretorio_atual]."'");

        $form->add("<TR><TD>");
        $form->add($botaoSubmit);
        $form->add($botaoCancelar);
        $form->add("</TD></TR>");


        $form->add("</TABLE>");
        return $form;

    }
    

    function listTree() {
        global $config_ini,$url;

        $upload = &$this->upload;

        $pag = new RDPagObj();

        $pag->add("<SCRIPT language=\"JavaScript\" type=\"text/javascript\" src=\"".$config_ini[Internet][urljs]."/divs.js\">");
        $pag->add("</SCRIPT>");

    //funcao que atualiza o frame da direita(conteudo do diretorio) quando o usuario clica num diretorio da arvore
        $script = "<SCRIPT language=\"JavaScript\">";
        $script.= "function refreshDir(dir) {\n ";
        $script.= "  parent.fr_diretorio.location.href = \"".$_SERVER[PHP_SELF]."?acao=A_diretorio&diretorio=\" + dir;";
        $script.= "}";
        $script.= "</SCRIPT>";

        $pag->add($script);

    //estilo usado para ir desenhando os diretorios hierarquicamente
        $pag->add("<STYLE type=\"text/css\">");
        $pag->add(".posDireita { position: relative; left: 12px }");
        $pag->add("</STYLE>");
        $diretorios = array();
        $indiceDiretorios = 0;

        $diretorios = $upload->treeArray();

        $this->listDirRecursivo($pag,$diretorios,$indiceDiretorios);

    /**
    */

        return $pag;

    }
    

    function listDirRecursivo(&$pag,&$diretorios,&$indiceDiretorios,$diretorio="") {
        global $urlimagens;

        $upload = &$this->upload;
   
        $dir = $upload->listaDir($diretorio);

    //se empty($diretorio) entao eh o diretorio raiz
        
        if (empty($diretorio)) {
            $link = "javascript:toggle('div_0')";

            $onclick = "if (document.imagem_0.src=='$urlimagens/upload/increment.gif') ";
            $onclick.= "  document.imagem_0.src = '$urlimagens/upload/decrement.gif'; else ";
            $onclick.= "  document.imagem_0.src = '$urlimagens/upload/increment.gif' ";

            $pag->add("<BR><A href=\"".$link."\" onclick=\"".$onclick."\" ><IMG name=\"imagem_0\" src=\"$urlimagens/upload/decrement.gif\"></A>");
            $pag->add("<IMG src=\"$urlimagens/".$this->tema[folder_small]."\" width=\"20\" height=\"20\" align=\"center\">&nbsp;");
            $onclick = "refreshDir('/')";

            $pag->add("<A href=\"javascript:refreshDir('/')\" onclick=\"".$onclick."\">Raiz</A>");
  
            $indice = 0;
        }
        else {
            $indice = "";
            foreach ($diretorios as $key=>$diret) {
                if ($diret==$diretorio) {
                    $indice = $key;
                    break;
                }
            }
        }
        
        $nome_div = "div_".$indice;

        if ($nome_div=="div_0") {
            $pag->add("<DIV name=\"".$nome_div."\" id=\"".$nome_div."\" class=\"posDireita\">");
        }
        else {
            $pag->add("<DIV name=\"".$nome_div."\" id=\"".$nome_div."\" class=\"posDireita\" style=\"display: none\">");
        }

        if (!empty($dir)) {

            foreach ($dir as $filho) {
                
                if ($filho[tipo]=="dir") {
                    $indiceDiretorios++;

                    $nome_dir_filho = $upload->pathChroot."/".$filho[rel_nome];
                    $diretorios[$indiceDiretorios] = $nome_dir_filho;
                    $nome_div_filho = "div_".$indiceDiretorios;
	 
                    $link = "javascript:toggle('".$nome_div_filho."')";
	 
                    $onclick = "if (document.imagem_".$indiceDiretorios.".src=='$urlimagens/".$this->tema[increment]."') ";
                    $onclick.= "  document.imagem_".$indiceDiretorios.".src = '$urlimagens/".$this->tema[decrement]."'; else ";
                    $onclick.= "  document.imagem_".$indiceDiretorios.".src = '$urlimagens/".$this->tema[increment]."' ";

                    $pag->add("<BR><A href=\"".$link."\" onclick=\"".$onclick."\"><IMG name=\"imagem_".$indiceDiretorios."\" src=\"$urlimagens/upload/increment.gif\"></A>");
                    $pag->add("<IMG src=\"$urlimagens/".$this->tema[folder_small]."\" width=\"20\" height=\"20\" align=\"center\">&nbsp;");

                    $onclick = "refreshDir('".$filho[rel_nome]."')";

                    $pag->add("<A href=\"javascript:$onclick\">". $filho[nome]."</A>");
	 
                    $this->listDirRecursivo($pag,$diretorios,$indiceDiretorios,$nome_dir_filho);
	 
                }
                else {
	  //$pag->add("<BR>".$filho[nome]);
                }
            }
            
        }
        $pag->add("</DIV>");

    
        /*    if ($nome_div != "div_0") {
        $pag->add("<SCRIPT language=\"JavaScript\">");
        $pag->add("set_invisible(\"".$nome_div."\");");
        $pag->add("</SCRIPT>");
    }*/
        
    }


    function getScript() {
        global $rdpath;

        $file = @implode(file("$rdpath/smartform/js/context_menu.js"));
		$file.= @implode(file("$rdpath/upload/js/upload.js"));
        
        return $file;
    }



    function getMime($filename) {
        $partes = explode(".",$filename);
        $ultimo = count($partes)-1;
        $icon = $url.$mime["unknown"];
        foreach($this->extencoes as $ext=>$tmime) {
            if($partes[$ultimo]==$ext) {
                return $tmime;
            };
        };

        return 0;
    }

    function getMimeIcon($nome) {
        global $urlimagens;

    //$this->extencoes[''] = "";


        $mime["application/rtf"] = "gnome-application-rtf.png";
        $mime["application/vnd.ms-excel"] = "gnome-application-vnd.ms-excel.png";
        $mime["application/vnd.sun.xml.writer"] = "gnome-application-vnd.sun.xml.writer.png";
        $mime["application/vnd.sun.xml.impress"] = "gnome-application-vnd.sun.xml.impress.png";
        $mime["application/x-backup"] = "gnome-application-x-backup.png";
        $mime["application/x-bzip"] = "gnome-application-x-bzip.png";
        $mime["application/x-compress"] = "gnome-application-x-compress.png";
        $mime["application/x-gzip"] = "gnome-application-x-gzip.png";
        $mime["application/x-java-byte-code"] = "gnome-application-x-java-byte-code.png";    $mime["application/x-php"] = "gnome-application-x-php.png";
        $mime["image/gif"] = "gnome-image-gif.png";
        $mime["image/jpeg"] = "gnome-image-jpeg.png";
        $mime["image/png"] = "gnome-image-png.png";
        $mime["image/psd"] = "gnome-image-psd.png";
        $mime["text/plain"] = "gnome-textfile.png";
        $mime["text/html"] = "gnome-http-url.png";
        $mime["application/x-zip"] = "gnome-compressed.png";
        $mime["application/vnd.ms-powerpoint"] = "gnome-application-vnd.ms-powerpoint.png";
        $mime["text/xml"] = "gnome-text-xml.png";
        $mime["unknown"] = "gnome-textfile.png";

        $url = $urlimagens."/upload";
        $tmime = $this->getMime($nome);

        if(isset($mime[$tmime])) {
            return "$url/".$mime[$tmime];
        }

        return "$url/".$mime["unknown"];
    }


    function setMimeAction($mime_type,$action_array) {
        $this->mime[$mime_type] = $action_array;
    }


    function getMimeAction($filename,$url,$id) {
        global $rdpath;

        $this->mime["text/html"] = array('OPENWINDOW','EDIT');
        $this->mime["image/gif"] = array('OPENWINDOW');
        $this->mime["image/jpeg"] = array('OPENWINDOW');
        $this->mime["image/png"] = array('OPENWINDOW');


        $fmime = $this->getMime($filename);

        if(!empty($this->mime[$fmime])) {
            $actions = array();
		   
            foreach($this->mime[$fmime] as $action) {
                switch($action) {
                    case 'OPENWINDOW':
                        include_once("$rdpath/interface/rdjswindow.inc.php");
                        $win = new RDJSWindow($url,"",400,400);
                        $actions[$action] = $win->getScript();
                        break;
                    case 'EDIT':
                        $actions[$action] = "window.location = '$_SERVER[PHP_SELF]?acao=A_edit&frm_filename=$filename';";
                }
            }

            $actions["SEPARATOR"] = "";
            $actions["COPY"] = "force_select('$id'); go_to_copia();";


            return $actions;
        }
        
        
        return 0;
    }



    function getCSS($file) {
        global $rdpath;
        return implode(@file("$rdpath/upload/css/$file"));
    }

    function configurePag($pag) {
        global $config_ini;

        $pag->requires("mediawrapper.php?type=js&frm_class=rdfupload&file=upload.js","MEDIA_JS");
        $pag->requires("mediawrapper.php?type=css&frm_class=rdfupload&file=contextmenu.css","MEDIA_CSS");

        return $pag;
    }

  //imprime o conteudo do diretorio
    function listDir($diretorio="") {
        global $rdpath, $config_ini,$urlimagens,$url,$lang;
        $upload = &$this->upload;

        $pag = new RDPagObj();

        $arquivos = array();

        $pag->add("<SCRIPT language=\"JavaScript\" type=\"text/javascript\" src=\"".$config_ini[Internet][urljs]."divs.js\">");
        $pag->add("</SCRIPT>");


        $action_icons["COPY"] = "ed_copy.gif";
        $action_icons["OPENWINDOW"] = "docprop.gif";


//     $script = "<SCRIPT language=\"JavaScript\">";
//     $script.= "</SCRIPT>";

//     $pag->add($script);

        if (!empty($diretorio)) {
            $pathChroot = substr($upload->pathChroot,0,strlen($upload->pathChroot));
            $fakedir = $diretorio;
            $diretorio = $pathChroot.$diretorio;
        }

        $dir = $upload->listaDir($diretorio);

        $i = 1;
        $pag->add("<TABLE cellspacing=\"20\"><TR><TD>");


        $item_por_linha = 3;
  
        if (!empty($dir)) {
            
            foreach ($dir as $filho) {
                $i++;
    
                if ($filho[tipo] == "dir") {
                    $pag->add("<A href=\"javascript:seleciona()\"></A>");

                    if ($filho[rel_nome][0]=="/") {
                        $nome_filho = $filho[rel_nome];
                    }
                    else {
                        $nome_filho = "/".$filho[rel_nome];
                    }
                    $id = $filho[rel_nome];
                    $dblClick = "window.location.href = '".$_SERVER[PHP_SELF]."?acao=A_diretorio&diretorio=".$nome_filho."';";
	 
                    $icon = new WIcon($filho[nome],$urlimagens."/".$this->tema[folder],"");
                    $icon->setNoLink();

                    $pag->add("<DIV style=\"float: left; padding: 10;cursor: pointer\" id=\"".$id."\" onDblClick=\"".$dblClick."\"  onClick=\"seleciona(this)\">");
                    $pag->add($icon);
                    $pag->add("</DIV>");


	  //	  $pag->add("</TR></TABLE></A></TD>");
                }
                else {
                    $id = $filho['nome'];
	 
                    $mime = $this->getMimeIcon($filho['nome']);
                    $icon = new WIcon($filho['nome'],$mime,"");
                    $icon->setNoLink();

                    $dbclick="";
                    if(!empty($this->baseurl)) {
                        if(!empty($fakedir)) {
                            $temp = $fakedir."/";
                        } else {
                            $temp = $this->upload->pwd()."/";
                        }
                         
                        $url = $this->baseurl."/$temp".$filho[nome];
                        $actions = $this->getMimeAction($filho[nome],$url,$id);
                        if(!empty($actions)) {
                            reset($actions);
                            list($k,$js) = each($actions);
                            $dbclick="onDblClick=\"".$js."\"";
                        }
                    };

                    $pag->add("<DIV style=\"cursor: pointer; float: left; padding: 10\" id=\"$id\"  $dbclick >");
                    $pag->add($icon);
                    $pag->add("</DIV>");
                    $pag->addScript("initSelectable('$id');");

                    /*if(!empty($actions)) {
                        if(count($actions)>0) {
                            $pag->add("<DIV id=\"menu_$id\" class=\"context-menu\" style=\"position: absolute; display: none\">");
                            $pag->add("<TABLE BGCOLOR=white BORDER=0>");
                            foreach($actions as $name=>$js) {
                                if($name=="SEPARATOR") {
                                    $pag->add("<TR class=\"separator\"><td class=\"icon\"><DIV></DIV><TD class=\"label\"><DIV></DIV>");
                                }
                                else {
                                    $icon = $action_icons[$name];
                                    if(!empty($icon)) {
                                        $urlimg = $config_ini[Internet][urlimagens];
                                        $icon = "<img src=\"$urlimg/$icon\">";

                                    }
                                    
                                    $pag->add("<TR class=\"item\"><td class=\"icon\">$icon<TD class=\"label\"><A HREF=\"#\" onClick=\"$js\">".$lang[strtolower($name)]."</A>");
                                }
                            }
                            $pag->add("</TABLE></DIV>");
                            $pag->addScript("initMenu('$id');");
                        }
                    }
*/


                }
                
	//array onde guarda os arquivos do diretorio atual, sendo que os nomes dos arquivos tambem sao os nomes
	//das divs onde seleciona os arquivos/diretorios
                $arquivos[] = $id;
    
            }
            
        }
        
        $pag->add("</TR>");
        $pag->add("</TABLE>");

        return $pag;


    }

    function uploadFile($arq,$tmp_name,$dir="",$force_rewrite=0) {

        if (!empty($dir))  $arq = $dir."/".$arq;

        if ($this->upload->existeArq($arq)) {
            if ($force_rewrite==0) {
                return 0;
            }
        }
    //se for um arquivo zip entao descompactalo
        if ($this->upload->isZip($arq)) {
            $this->upload->unZip($tmp_name,$dir);
        }
        else {
            $this->upload->upload_file($arq,$tmp_name);
        }
        return 1;
    }

}

?>