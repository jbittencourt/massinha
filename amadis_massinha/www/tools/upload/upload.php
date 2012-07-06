<?
define("TEMP_PREFIX",UPLOAD_TEMP);
define("TRASH_DIR",trash);

$r_bibliotecas[] = "\$rdpath/upload/rdfupload.inc.php";

include_once("../../config.inc.php");

//include_once($pathlib."widgets/wicon.inc.php");   
//include_once("$pathuserlib/templ_paginas.inc.php");

$ui = new RDUi("upload",$_REQUEST[acao]);
$lang = $_SESSION[ambiente]->getLangUI($ui);

switch($_REQUEST[acao]) {

    case "":
         
        if(empty($_REQUEST[codProjeto]) && empty($_REQUEST[codUser])) {
            die("sem projeto");
        }
        

        if(!empty($_REQUEST[codProjeto])) {
            $_SESSION[path_chroot] = $config_ini[Diretorios][pathpaginas]."/project_".$_REQUEST[codProjeto];
            $_SESSION[url_chroot] = $config_ini[Internet][urlpaginas]."/project_".$_REQUEST[codProjeto];
        } else {
            $_SESSION[path_chroot] = $config_ini[Diretorios][pathpaginas]."/user_".$_REQUEST[codUser];
            $_SESSION[url_chroot] = $config_ini[Internet][urlpaginas]."/user_".$_REQUEST[codUser];
        }

        if (!is_dir($_SESSION[path_chroot])) {
      //se o diretorio do projeto nao existir cria-lo
      //modificar isto depois : eh bastante inseguro
            RDUpload::staticCriaDir($_SESSION[path_chroot]);
        }
        
        $_SESSION[fer_upload] = new RDFUpload($_SESSION[path_chroot]);
        $_SESSION[fer_upload]->baseurl = $_SESSION[url_chroot];
  
        $_SESSION[dir_atual] = "";
  
    case "A_diretorio":
         
        $_SESSION[diretorio_atual] = $_REQUEST[diretorio];
  
        $pag = new AEUpload();
        $pag->addScript($_SESSION['fer_upload']->getScript());
        $pag->setMargin(10,0,10,0);

        $box = new AEUploadBox($_SESSION[diretorio_atual]);
   //lista o conteudo do diretorio
        $dir_content = $_SESSION[fer_upload]->listDir($_REQUEST[diretorio]);
        $box->add($dir_content);
        $pag->add($box);
        $pag->imprime();
  
        break;

    case "A_copia":
        if (!empty($_REQUEST[arquivos])) {
            $_SESSION[clipboard_dir] = $_SESSION[diretorio_atual];
            $_SESSION[clipboard] = $_REQUEST[arquivos];
        }

   //volta a mostrar o conteudo do diretorio atual
        Header("Location: ".$_SERVER[PHP_SELF]."?acao=A_diretorio&diretorio=".$_SESSION[diretorio_atual]);
  
        break;


    case "A_cola":
        
        $dir_dst = $_SESSION[diretorio_atual];

        if (!empty($_SESSION[clipboard])) {
            foreach ($_SESSION[clipboard] as $arq) {

                $file = $_SESSION[fer_upload]->upload->pathChroot.$arq;
                if (is_dir($file)) {
	 //se tiver barra no nome do arquivo entao o arquivo na verdade eh um diretorio
                    $dir_src = $arq;
                    $_SESSION[fer_upload]->upload->copia_dir_recursivo($dir_src,$dir_dst);
                }
                else {
                    $dir_src = $_SESSION[clipboard_dir];
                    if(empty($dir_src)) $dir_src = "/";
                    $_SESSION[fer_upload]->upload->copia_from_chroot($dir_src,$dir_dst,$arq);
                }
                 
            }
        }
         
        Header("Location: ".$_SERVER[PHP_SELF]."?acao=A_diretorio&diretorio=".$_SESSION[diretorio_atual]);

        break;

    case "A_move_to_trash":
   //se o diretorio da lixeira nao existir, cria-lo
        if (!is_dir($_SESSION[path_chroot]."/trash")) {
            criaDir(TRASH_DIR);
        }
         
        foreach ($_REQUEST[arquivos] as $arq) {
            $file = $_SESSION[path_chroot].$arq;
            if (is_dir($file)) {
                copia_dir_recursivo($file,TRASH_DIR."/$arq");
                $_SESSION[fer_upload]->upload->apaga_recursivo($arq);
            }
            else {
                copia_from_chroot($_SESSION[diretorio_atual],"/".TRASH_DIR,$arq);
                $_SESSION[fer_upload]->upload->apaga($arq,$_SESSION[diretorio_atual]);
            }
             
        }
         
        break;

    case "A_apaga":

        if (!empty($_REQUEST[arquivos])) {
            foreach ($_REQUEST[arquivos] as $arq) {
                $file = $_SESSION[fer_upload]->upload->pathChroot.$arq;
                $eDiretorio = is_dir($file);
                if ($eDiretorio) {
                    $_SESSION[fer_upload]->upload->apaga_recursivo($arq);
                }
                else {
                    $_SESSION[fer_upload]->upload->apaga($arq,$_SESSION[diretorio_atual]);
                }
            }
        }
         
        if ($eDiretorio)
        Header("Location: ".$_SERVER[PHP_SELF]."?acao=A_diretorio&diretorio=".$_SESSION[diretorio_atual]."&refreshTreeFrame=1");
        else
        Header("Location: ".$_SERVER[PHP_SELF]."?acao=A_diretorio&diretorio=".$_SESSION[diretorio_atual]);
        break;

    case "A_envia_arq":

        $pag = new AEUpload("envia");
        $form = $_SESSION[fer_upload]->makeFormEnvio($_SERVER[PHP_SELF]."?acao=A_envia_arq_make",8);
        $pag->add($form);
        $pag->imprime();
        exit;
  
  
        break;
  
    case "A_envia_arq_make":


        foreach ($_FILES as $arq) {
            if (!empty($arq[tmp_name]) && ($arq[tmp_name]!='none')) {
                $sucesso = $_SESSION[fer_upload]->uploadFile($arq[name],$arq[tmp_name],$_SESSION[diretorio_atual]);
                if (!$sucesso) {
                    if (empty($arquivos_existentes)) {
                        $arquivos_existentes = array();
                    }
                    $arquivos_existentes[] = $arq;
                }
            }
        }
         
   //se houver arquivo que ira sobrescrever pedir ao usuario se ele deseja sobrescrever
        if (is_array($arquivos_existentes)) {
             
            $pag = new AEUpload();
            $pag->add("Estes arquivos ja existem. Voce tem certeza que deseja sobrescreve-los ? ");
   
            $form = new WForm("form1",$_SERVER[PHP_SELF]."?acao=A_envia_arq_sobrescrever");
            $form->add("<TABLE><TR><TD>Sobrescrever</TD><TD>Nome do arquivo</TD></TR>");
   
            foreach ($arquivos_existentes as $arq) {
                $_SESSION[fer_upload]->uploadFile(TEMP_PREFIX.$arq[name],$arq[tmp_name],$_SESSION[diretorio_atual],1);
    
                $w_hidden = new WHidden("arqs[]",$arq[name]);
                $form->add($w_hidden);
    
                $form->add("<TR><TD>");
                $w_check = new WCheckBox($arq[name],1,"Sim");
                $form->add($w_check);
                $form->add("</TD><TD>".$arq[name]."</TD></TR>");
            }
            $botao = new WButton("submit","Enviar");
            $form->add("<TR><TD>");
            $form->add($botao);
            $form->add("</TD></TR>");
            $form->add("</TABLE>");
   
            $pag->add($form);
            $pag->imprime();
            exit;
        }
        else {
     //nenhum arquivo ja existe. fazer upload dos arquivos
            Header("Location: ".$_SERVER[PHP_SELF]."?acao=A_diretorio&diretorio=".$_SESSION[diretorio_atual]);
        }


        break;

    case "A_envia_arq_sobrescrever":

        foreach ($_REQUEST[arqs] as $arq) {
            $nome = strtr($arq,".","_");  //esta funcao substitui as ocorrencias de "." por "_" no nome do arquivo
     //se o checkbox relacionado ao arquivo foi ativado fazer upload (mover o arquivo temporario para seu nome original)
            $nome_temp = TEMP_PREFIX.$arq;
            if ($_REQUEST[$nome]==1) {
                $dest = $_SESSION[fer_upload]->upload->pwd()."/".$arq;
                $_SESSION[fer_upload]->upload->copia($nome_temp,$dest,"mv");
            }
            else {
       //apagar arquivo temporario
                $_SESSION[fer_upload]->upload->apaga($nome_temp);
            }
             
        }
        
        Header("Location: ".$_SERVER[PHP_SELF]."?acao=A_diretorio&diretorio=".$_SESSION[diretorio_atual]);
  
        break;

    case "A_cria_dir":
        $pag = new AEUpload("criaDir");
  
        $form = new WForm("form1",$_SERVER[PHP_SELF]."?acao=A_cria_dir_make");
        $w_nome_dir = new WText("namedir","");
        $w_nome_dir->addLabel("Digite um nome para a pasta: ");
        $w_nome_dir->design = WFORMEL_DESIGN_OVER;
  
        $w_but = new WButton("sub1","Enviar");
        $w_b_cancel = new WButton("but1","Cancelar","button");
        $w_b_cancel->setOnClick("window.location.href = '".$_SERVER[PHP_SELF]."?acao=A_diretorio&diretorio=".$_SESSION[diretorio_atual]."'");
  
        $form->add("<TABLE><TR><TD>");
        $form->add($w_nome_dir);
        $form->add($w_but);
        $form->add($w_b_cancel);
        $form->add("</TD></TR></TABLE>");

        $pag->add($form);

        $pag->imprime();

        break;

    case "A_cria_dir_make":
        if (!empty($_REQUEST[namedir])) {
            $_SESSION[fer_upload]->upload->criaDir($_REQUEST[namedir],$_SESSION[diretorio_atual]);
        }

        Header("Location: ".$_SERVER[PHP_SELF]."?acao=A_diretorio&diretorio=".$_SESSION[diretorio_atual]."&refreshTreeFrame=1");
        break;


    case "A_download":
        
   //o download sera na forma de uma arquivo zip caso
   //forem mais de um arquivo, o usuario marcou para download como zip ou for um diretorio
        
        $eDiretorio =  is_dir($_SESSION[fer_upload]->upload->pathChroot.$_REQUEST[arquivos][0]);

        if ($_REQUEST[flagTar]) {
     //download como tar
            $_SESSION[fer_upload]->upload->toTarGz($_REQUEST[arquivos],$_SESSION[diretorio_atual]);
            $nomearquivo = "download".session_id().".tar.gz";
            header("Content-type: application/tar-gzipped");
            if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE"))
            header("Content-Disposition: filename=download.tar.gz" . "%20"); // For IE
            else
            header("Content-Disposition: attachment; filename=download.tar.gz"); // For Other browsers
            $flagTar = 1;
            header("Content-Length: ".filesize("/tmp/".$nomearquivo));
            readfile("/tmp/".$nomearquivo);
        }
         
        else if (count($_REQUEST[arquivos]) > 1 || $_REQUEST[flagZip] || $eDiretorio) {
     //download como zip
            $_SESSION[fer_upload]->upload->toZip($_REQUEST[arquivos],$_SESSION[diretorio_atual]);
            $nomearquivo = "download".session_id().".zip";
            header("Content-type: application/zip-file");
            if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE"))
            header("Content-Disposition: filename=download.zip" . "%20"); // For IE
            else
            header("Content-Disposition: attachment; filename=download.zip"); // For Other browsers
            $flagZip = 1;
            header("Content-Length: ".filesize($nomearquivo));
            readfile("/tmp/".$nomearquivo);
        }

        else {
     //apenas um arquivo para download. baixar o arquivo sem compactar
            $nomearquivo = $_SESSION[fer_upload]->upload->pathChroot."/".$_SESSION[diretorio_atual].$_REQUEST[arquivos][0];
            header("Content-type: application/force-download");
            if (strstr($_SERVER["HTTP_USER_AGENT"], "MSIE"))
            header("Content-Disposition: filename=".$_REQUEST[arquivos][0]."%20"); // For IE
            else
            header("Content-Disposition: attachment; filename=".$_REQUEST[arquivos][0]); // For Other browsers
            header("Content-Length: ".filesize($nomearquivo));
            readfile($nomearquivo);
        }

        if ($flagZip || $flagTar) {
     //apaga arquivo temporario (o .zip)
            $_SESSION[fer_upload]->upload->apagaTmpFile($nomearquivo);
        }

        break;


}

?>