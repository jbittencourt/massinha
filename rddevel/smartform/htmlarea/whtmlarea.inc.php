<?

include_once("$rdpath/smartform/wtextarea.inc.php");

/**
 * Classe que implementa um form tipo Textarea
 *
 * Classe que implementa um form tipo Textarea
 *
 * @author Maicon Browers <maicon@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see  WForm
 */
class WHTMLArea extends WTextArea {
  var $content;

  function WHTMLArea($name,$h,$w,$value="") {
    $this->wtextArea($name,"20","0",$value);

    $this->prop[style] ="height: $h; width: $w;";

    $_SESSION[rddevel][smartform][whtmlarea][name] = $name;

    $this->requires("mediawrapper.php?type=js&frm_class=whtmlarea&file=inicialize","MEDIA_JS");
    $this->requires("mediawrapper.php?type=js&frm_class=whtmlarea&file=htmlarea.js","MEDIA_JS");
    $this->requires("mediawrapper.php?type=js&frm_class=whtmlarea&file=configure","MEDIA_JS");

  }

  function setFullPagetEdit() {
    //$_SESSION[smartform][whtmlarea][full] = 1;
    $this->fullpage = 1;
  }

  
  function getInitScript() {
    global $config_ini;


    $url = $config_ini[Internet][urlmedia];
    $urlimagens = $config_ini[Internet][urlimagens];

    $js = "_editor_url_js = '$url/mediawrapper.php?type=js&frm_class=whtmlarea&file=';";
    $js.= "_editor_url_plugins = ''; ";
    $js.= "_editor_url_images = '$urlimagens/htmlarea/';";
    $js.= "_editor_url_css = '$url/mediawrapper.php?type=css&frm_class=whtmlarea&file=';";
    $js.= "_editor_url_popups = '$url/mediawrapper.php?type=html&frm_class=whtmlarea&file=';";

    if(!empty($_SESSION[ambiente])) {
      $js.= "_editor_lang = '".$_SESSION[ambiente]->language."';";
    }


    return $js;
  }


  function getConfigurationScript() {
    $js[] = "HTMLArea.loadPlugin(\"FullPage\");";
    $js[] = "function initDocument() {";
    $js[] = "  var editor = new HTMLArea(\"".$_SESSION[rddevel][smartform][whtmlarea][name]."\");";
    $js[] = "  editor.registerPlugin(FullPage);";
    $js[] = "  editor.generate();";
    $js[] = "}";

    return implode($js);
  }



  function getHTMLFile($file) {
    global $rdpath,$config_ini;

    $url = $config_ini[Internet][urlmedia];

    $file = implode(file("$rdpath/smartform/htmlarea/popups/$file"));

    $file = @ereg_replace("{URLJS}","$url/mediawrapper.php?type=js&frm_class=whtmlarea&file=",$file);

    return $file;
  }




  function getCSS($file) {
    global $rdpath;
    $script = implode(file("$rdpath/smartform/htmlarea/css/htmlarea.css"));
    return $script;
  }

  function getScripts($file) {
    global $rdpath;

    switch($file) {
    case "inicialize":
      $script = WHTMLArea::getInitScript();
      break;
    case "configure":
      $script = WHTMLArea::getConfigurationScript();
      break;
    case "htmlarea.js":
      $script = implode(file("$rdpath/smartform/htmlarea/js/htmlarea.js"));
      break;
    case "dialog.js":
      $script = implode(file("$rdpath/smartform/htmlarea/js/dialog.js"));
      break;
    case "popupwin.js":
      $script = implode(file("$rdpath/smartform/htmlarea/js/popupwin.js"));
      break;
    case "popup.js":
      $script = implode(file("$rdpath/smartform/htmlarea/js/popup.js"));
      break;
    case "full-page.js":
      $script = implode(file("$rdpath/smartform/htmlarea/js/full-page.js"));
      break;
      //languages
    case "en.js":
      $script = implode(file("$rdpath/smartform/htmlarea/js/lang/en.js"));
      break;
    case "pt-br.js":
      $script = implode(file("$rdpath/smartform/htmlarea/js/lang/pt-br.js"));
      break;

    case "full-page.pt_br.js":
      $script = implode(file("$rdpath/smartform/htmlarea/js/lang/full-page.pt-br.js"));
      break;


    }

    return $script;
  }



}


?>