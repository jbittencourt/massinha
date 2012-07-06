<?php

include_once("../../config.inc.php");
include_once("forumBox.inc.php");

$ui = new RDui("forum", "");
$lang = $_SESSION[ambiente]->getLangUI($ui);

$showRel = $_REQUEST[showRel];

if ($_REQUEST[voltar] == "cafe") {
    $pag = new templateFerramentasAmadis();
    $destino = "cafe.php";
}

else{
    $pag = new AEForum();
    $destino = "mensagens.php";
}


if( empty($_REQUEST[forum]) ) {
    echo "acesso não permitido";
    die();
}

$forum = new AMForumAmadis($_REQUEST[forum]);

if ($_REQUEST[jaLida] == "0") {
    $temp = new RDMensagensLidas();
    $temp->codMensagem = $_REQUEST[mensagem];
    $temp->codUser = $_SESSION[usuario]->codUser;
    $temp->salva();
}

$pag->addJSFile($config_ini[Internet][urljs]."/forum.js");

switch($_REQUEST[acao])  {
    case "atachar":
        if(!empty($_FILES[frm_imagem])) {

            if(is_uploaded_file($_FILES[frm_imagem][tmp_name])) {
                $tmp_file = basename($_FILES[frm_imagem][tmp_name].".att");
                copy($_FILES[frm_imagem][tmp_name],$config_ini[Diretorios][pathtemp]."/$tmp_file");
      
                $elem = $_FILES[frm_imagem];
                $elem[tmp_name] = $config_ini[Diretorios][pathtemp]."/$tmp_file";

        
                $_SESSION[imagem_at][] = $elem;
       // note($_SESSION[imagem_at]);
            }
        }
        break;

    case "desatachar":
        if(!empty($_REQUEST[frm_del_attach])) {
            foreach($_REQUEST[frm_del_attach] as $k=>$img_key) {
                $img = $_SESSION[imagem_at][$img_key];
                if(!empty($img)) {
                    $tmp_file = $img[tmp_name];
                    unlink($tmp_file);
                    unset($_SESSION[imagem_at][$img_key]);
                }
            }
        }
        break;

    case "preview":
         
        $men = new RDMensagemForum();
        $men->codForum = $_REQUEST[forum];
        $men->codAutor = $_SESSION[usuario]->codUser;
        $men->strTitulo = $_REQUEST[frm_titulo];
        $men->desCorpo = $_REQUEST[frm_texto];
        $men->codMensagemPai = $_REQUEST[mensagem];
        $men->tempo = time();
        $men->relacao = $_REQUEST[relacao];

  
        if(!empty($_SESSION[imagem_at])) {
            foreach($_SESSION[imagem_at] as $k=>$imagem_tmp) {
                $img = new RDImagem();
                $img->setData($imagem_tmp);
                $imglist[] = $img;
            }
            
            $_SESSION[tmpimagens] = serialize($imglist);
        }

        $resposta = $men;

        $_SESSION[tempmen] = serialize($men);   //converte para uma string que possa ser reconstruida depois

        $nomeForum = new AMForumAmadis($resposta->codForum);

        $numProjeto = $_REQUEST[projeto];

        $mensagem = new mensagemBox();
        $mensagem->acao("preview");
   //$mensagem->setTitle("img_tit_meus_projetos.gif");
        
        $mensagem->addLI("<b>".$lang[forum].":</b>&nbsp;".$nomeForum->nomForum);
        $mensagem->addLI("<b>".$lang[data_mensagem].":</b>&nbsp;".date("j/n/Y G:i",$resposta->tempo));

        /* Desativei o spellcheck at� poder lidar com os tags html
        * $spell = new WSpellcheck($resposta->desCorpo);
        * $mensagem->add($spell->toString());
        */
        $mensagem->add($resposta->desCorpo);
        $mensagem->add("");

        $mensagem->setPreviewTitulo($_REQUEST[frm_titulo]);
        $mensagem->setPreviewTexto($_REQUEST[frm_texto]);
   
        $chave[] = opVal("codMensagem",$resposta->codMensagem);

        $mensagem->codigos($_REQUEST[forum], $_REQUEST[mensagem], $_REQUEST[modo], $_REQUEST[showRel], $_REQUEST[relacao]);

        if(!empty($imglist)) {
            foreach ($imglist as $k=>$imagem) {
                $mensagem->addImagem ($k);
            }
        }

        $pag->add ("<br>");
        $pag->add ($mensagem);
        $pag->add ("<BR>");
        $pag->imprime();
   
        die();
        break;

   
    case "cancelaPreview":
        unset($_SESSION[tempmen]);
        unset($_SESSION[tmpimagens]);
        break;

    case "enviar":

        $men = unserialize($_SESSION[tempmen]);
        
        $imagens = unserialize($_SESSION['tmpimagens']);
        if($imagens==false) $imagens = $_SESSION['tmpimagens'];

        if(!empty($imagens)) {
            foreach($imagens as $k=>$img) {
                $men->addImagem($img);
            };
        };

        if(empty($men)) {
            die("Erro fatal ao enviar mensagem");
        };
        $men->salva();

        if(!empty($_SESSION[imagem_at])) {
            foreach($_SESSION[imagem_at] as $k=>$img) {
                if(!empty($img)) {
                    $tmp_file = $img[tmp_name];
                    unlink($tmp_file);
                    unset($_SESSION[imagem_at][$k]);
                }
            }
        }

        unset($_SESSION[tempmen]);
        unset($_SESSION[imagem_at]);


    case "confirmarEnvio":
         
        Header("Location: ".$destino."?forum=$_REQUEST[forum]&acao=A_mensagem_enviada&modo=".$_REQUEST[modo]."&enviada=".$men->codMensagem);
    				
        die();
        break;

    default:
        unset($_SESSION[tempmen]);
        unset($_SESSION[tmpimagens]);
        unset($_SESSION[imagem_at]);

   //note ($_SESSION);    
}

//unset($_SESSION[tempmen]);
//unset($_SESSION[tmpimagens]);

$men = new RDMensagemForum($_REQUEST[mensagem]);
$autor = new AMUser($men->codAutor);

if ($forum->tipoPai == "P") {
    $voltar = "projeto=".$forum->codPai;
}
if ($forum->tipoPai == "S" or $forum->tipoPai == "O") {
    $voltar = "oficina=".$forum->codPai;
}
if ($forum->tipoPai == "C") {
    $voltar = "forum=".$forum->codPai;
}


if($_REQUEST[acao] != "compor" and $showRel != "1") {
    if ($forum->tipoPai != "C") {
        $itens[$lang[voltar_lista_mensagens]] = "mensagens.php?forum=".$_REQUEST[forum]."&modo=".$_REQUEST[modo];
    }
    else {
        $itens[$lang[voltar]] = "cafe.php?modo=".$_REQUEST[modo];
    }

    if($_SESSION[forum_perm][$forum->codForum][can_post]) {
        $itens[$lang[responder_mensagem]]  = "manipula_men.php?acao=leMens&mensagem=$_REQUEST[mensagem]&modo=$_REQUEST[modo]&forum=$_REQUEST[forum]&voltar=".$_REQUEST[voltar]."#responder";
    }
    if ($forum->tipoPai != "C") {
        $itens[$lang[outro_forum]]  = "forum.php?".$voltar;
    }
}
else {
    if ($forum->tipoPai != "C") {
        $itens[$lang[voltar_lista_mensagens]] = "mensagens.php?forum=".$_REQUEST[forum]."&modo=".$_REQUEST[modo];
        $itens[$lang[outro_forum]] = "forum.php?".$voltar;
    }
    else {
        $itens[$lang[voltar]] = "cafe.php?modo=".$_REQUEST[modo];
    }
}

//menu auxiliar
$menu = " ";
foreach ($itens as $k=>$item) {
    $menu .= "&nbsp;|&nbsp;<a class=\"comum\" href=\"$item\">$k</a>";
}
$pag->add ("<br>");
$pag->add ("<p align=right class=\"comum\">".substr($menu, 14, strlen($menu))."</p>");

$pag->add ("<br>");

$dadosForum = new mensagemBox;
if(($_REQUEST[acao]!="compor") && ($_REQUEST[mensagem]!=0)) {

  //$dadosForum->setTitle("img_tit_meus_projetos.gif");
    $dadosForum->addLI("<b>".$lang[autor].":</b>&nbsp;".$autor->nomPessoa);
    $dadosForum->addLI("<b>".$lang[forum].":</b>&nbsp;".$forum->nomForum);
    $dadosForum->addLI("<b>".$lang[data_abertura].":</b>&nbsp;".date("j/n/Y G:i",$forum->tempo));
    $dadosForum->addLI("<b>".$lang[data_mensagem].":</b>&nbsp;".date("j/n/Y G:i",$men->tempo));

    $dadosForum->add($men->desCorpo);
    $dadosForum->acao("compor");

    $chave[] = opVal("codMensagem",$men->codMensagem);
    $listaImg = new RDLista("RDForumImagem",$chave);
   
    if ($listaImg->records != "0") {
        foreach ($listaImg->records as $imagem) {
            if($imagem->codMensagem == $men->codMensagem) {
                $dadosForum->add("<p align=\"center\"><img src=\"".$url."/imagem.php?imagem=".$imagem->codArquivo."\" alt=\"Imagem\"></p>");
            }
        }
    }
    
    $chaveFilhas[] = opVal("codMensagemPai",$_REQUEST[mensagem]);
    $filhas = new RDLista("RDMensagemForum",$chaveFilhas,"tempo asc");

    if ($filhas->records != "0" ) {
        foreach($filhas->records as $k=>$filha) {
            $dadosForum->addResposta ("<a class=\"comum\" href=\"manipula_men.php?modo=".$_REQUEST[modo]."&mensagem=".$filha->codMensagem."&voltar=".$_REQUEST[voltar]."&forum=".$_REQUEST[forum]."\">".$filha->strTitulo."</a><br>");
        }
    }
    
    $pag->add($dadosForum);
    $pag->add ("<br>");
}

$pag->add ("<a name=\"responder\">");

$resposta = new AEBox();

if ($_REQUEST[acao] == "compor" or ($_REQUEST[acao] == "cancelaPreview" and $_REQUEST[showRel] == "1")) {
  //$resposta->setTitle("img_tit_meus_projetos.gif");
}
else {
  //$resposta->setTitle("img_tit_meus_projetos.gif");
}

$form = "<table bgcolor=\"#E6E8EF\">";

$form .= "<form name=\"enviar\" action=\"manipula_men.php?#responder\" enctype=\"multipart/form-data\" method=\"POST\">";
if ($forum->tipoPai == "C") {
    $voltar = "cafe";
    $form .= "<input type=\"hidden\" name=\"voltar\" value=\"".$_REQUEST[voltar]."\">";
}
$form .= "<input type=\"hidden\" name=\"forum\" value=\"".$_REQUEST[forum]."\">";
$form .= "<input type=\"hidden\" name=\"modo\" value=\"".$_REQUEST[modo]."\">";

if($_REQUEST[acao] == "compor")  {
    $form .= "<input type=\"hidden\" name=\"mensagem\" value=\"0\">";
}
else {
    $form .= "<input type=\"hidden\" name=\"mensagem\" value=\"".$men->codMensagem."\">";
}

$form .= "<TR><td>&nbsp;&nbsp;</td><td>";
$form .= "<input type=\"hidden\" name = \"strTituloOriginal\" value=\"".$men->strTitulo."\">";

if($_REQUEST[acao] != "compor" and $showRel != "1") {
    $form .= "<TD class=\"comum\" valign=\"top\" width=\"60%\">";
    $tamanho = "45";
}
else {
    $form .= "<TD class=\"comum\" valign=\"top\" width=\"100%\">";
    $tamanho = "70";
}

$form .= "<b>".$lang[titulo].":</b><br>";

if($_REQUEST[acao] == "atachar" or $_REQUEST[acao] == "desatachar" or $_REQUEST[acao] == "cancelaPreview") {
    $form .= "<input type=\"text\" name=\"frm_titulo\" value=\"".$_REQUEST[frm_titulo]."\" size=\"$tamanho\"><br><br>";
    $form .= "<b>".$lang[resposta].":</b><br>";
}
elseif($_REQUEST[acao] != "compor") {
    $form .= "<input type=\"text\" name=\"frm_titulo\" value=\"re: ".$men->strTitulo."\" size=\"$tamanho\"><br><br>";
    $form .= "<b>".$lang[resposta].":</b><br>";
}
else {
    $form .= "<input type=\"text\" name=\"frm_titulo\" size=\"$tamanho\"><br><br>";
    $form .= "<b>".$lang[mensagem].":</b><br>";
    $form.= "<input type=hidden name=\"oldacao\" value=\"compor\">";
}

$form .= "<textarea rows=\"13\" name=\"frm_texto\" cols=\"$tamanho\">".$_REQUEST[frm_texto]."</textarea><br><br>";
$form .= "<b>".$lang[anexar_imagem].":</b><BR>";
$form .= "<input type=\"file\" size=\"35\" name=\"frm_imagem\" onFocus=\"onChangeProcurar()\">&nbsp;";
$form .= "<input type=\"button\" onclick=\"attach()\" value=\"".$lang[anexar]."\"><br><br>";

$form .= "<input type=hidden name=acao value=\"preview\">";

if(!empty($_SESSION[imagem_at])) {
    $form .= "<table border=0>";
    $form .= "<tr><td class=\"comum\" colspan = \"3\">".$lang[lista_imagens_anexadas].":</td></tr>";

    if(!empty($_SESSION[imagem_at])) {
        foreach($_SESSION[imagem_at] as $k=>$imagem) {
            $im = "<img src=\"thumb.php?ni=$k\" border=0>";
            $form .= "<tr><td class=\"comum\" width=10><input type=checkbox name=\"frm_del_attach[]\" value=\"$k\"</td><td>$im</td><td class=\"comum\">$imagem[name]<br></td>";
        }
    }
     
    $form .= "<tr><td class=\"comum\" colspan=3><input type=button value=\"".$lang[excluir_imagens]."\" onClick=\"remove()\"></td>";
    $form .= "</table>";
};
	  
$form .= "</TD>";

if($_REQUEST[acao] != "compor" and $showRel != "1") {
    if (!empty($_REQUEST[relacao]))  {
        if ($_REQUEST[relacao] == "concordo") {
            $checkConcordo = "checked";
        }
        if ($_REQUEST[relacao] == "discordo") {
            $checkDiscordo = "checked";
        }
        if ($_REQUEST[relacao] == "duvida") {
            $checkDuvida = "checked";
        }
        if ($_REQUEST[relacao] == "ideia") {
            $checkIdeia = "checked";
        }
        if ($_REQUEST[relacao] == "nenhuma") {
            $checkNo = "checked";
        }
    }
    else {
        $checkNo = "checked";
    }

    $form .= "<TD class=\"comum\" valign=\"top\" width=\"40%\">";
    $form .= "<font size=\"2\"><b>".$lang[relacao_mensagem_original].":</b><br><br>";
    $form .= "<input type=\"radio\" value=\"concordo\" $checkConcordo name=\"relacao\">&nbsp;".$lang[concordo]."&nbsp;<img src=\"$urlimagens/ok.gif\" height=\"15\" width=\"15\"><br>";
    $form .= "<input type=\"radio\" value=\"discordo\" $checkDiscordo name=\"relacao\">&nbsp;".$lang[discordo]."&nbsp;<img src=\"$urlimagens/notok.gif\" height=\"15\" width=\"15\"><br>";
    $form .= "<input type=\"radio\" value=\"duvida\" $checkDuvida name=\"relacao\">&nbsp;".$lang[duvida]."&nbsp;<img src=\"$urlimagens/hein.gif\" height=\"15\" width=\"15\"><br>";
    $form .= "<input type=\"radio\" value=\"ideia\" $checkIdeia name=\"relacao\">&nbsp;".$lang[ideia]."&nbsp;<img src=\"$urlimagens/idea.gif\" height=\"15\" width=\"15\"><br>";
    $form .= "<input type=\"radio\" value=\"nenhuma\" $checkNo name=\"relacao\">&nbsp;".$lang[nenhuma]."<br><br><br>";
    $form .= "</TD>";
}
else {
    $form .= "<input type=hidden name=showRel value=\"1\">";
}

$form .= "</TR>";
$form .= "<TR>";
$form .= "<TD class=\"comum\" colspan=\"5\" width=\"100%\"><hr></td>";
$form .= "</TR>";
$form .= "<TR>";
$form .= "<TD class=\"comum\" colspan=\"5\" width=\"100%\">";
$form .= "<div align=\"center\"><input type=\"button\" value=\"".$lang[ver_preview]."\" name=\"Enviar\" onclick=\"enviarMen()\">&nbsp;<input type=\"reset\" value=\"".$lang[limpar]."\" ></div><br></TD>";
$form .= "</TR>";
$form .= "</font>";
$form .= "</form>";

$form .= "</table>";

$resposta->add($form);
$pag->add($resposta);


$pag->add ("<br>");

$pag->imprime();


?>
