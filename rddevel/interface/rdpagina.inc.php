<?
/**
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
*
* @version 0.2
* @author Juliano <juliano@edu.ufrgs.br>
*/



/**
 * Classe que repredenta uma p�gina HTML
 *
 * O RDPagina � uma das classes mais import�ntes do RD->Devel. Ela � utilizada para criar p�ginas que posteriormente 
 * podem serem enviadas para a sa�da padr�o (browser) ou redirecinadas para um arquivo. A vantagem em utilizar o 
 * RDPagina ao inv�s de simplesmente imprimir o html na tela com o comando echo, � a de poder adicionar subclasses de
 * RDPagObj a p�gina. Como as p�gina s�o geradas apenas quando o m�todo imprime() � invocado, todas as altera��es ap�s
 * a adi��o s�o v�lidas. (s� � v�lido no PHP 5 em diante, no php 4, uma c�pia do objeto � adiciona a p�gina, e sendo 
 * assim todas as altera��es posteriores s�o perdidas.)
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage interface
 * @see RDPagObj
 */
class RDPagina extends RDPagObj
{
    var $frameset,  $title;
    var $bgcolor, $bgimage, $OnLoad, $refreshRate;
    var $m_left,$m_top,$m_width, $m_height;
    var $script, $JSfiles,$styleFiles;


    function sendheader() {
        header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Date in the past
        header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    // always modified
        header ("Cache-Control: no-cache, must-revalidate");  // HTTP/1.1
        header ("Pragma: no-cache");                          // HTTP/1.0
    }

    function setFrameset($texto)
    { $frameset = $texto; }
     
    function printFrames()
    { print($this->frameset); }

    function setTitle($texto) {
        $this->title = $texto;
    }
     
    function setRefreshRate($time) {
        $this->refreshRate = $time;
    }
     
    function setOnLoad($texto) {
        $this->OnLoad = $texto;
    }
     
    function setOnClose($texto) {
        $this->OnClose = $texto;
    }
    
    function setMargin($ml, $mt, $mw, $mh) {
        
        $this->m_left = $ml+1;
        $this->m_top = $mt+1;
        $this->m_width = $mw+1;
        $this->m_height = $mh+1;
    }

    function setBgColor($cor) {
        $this->bgcolor = $cor;
    }


    function setBgImage($img) {
        $this->bgimage = $img;
    }
    
    function addJSFile($line) {
        $this->JSfiles[]= $line;
    }

    function addStyleFile($sf) {
        $this->styleFiles[] = $sf;
    }

    function addStyle($line) {
        $this->style[]="\t$line\n";
    }
     
    function addClassStyle($class, $line) {
        $this->style[] = "\t$class { $line }\n";
    }

    function aplicaTema($tema) {
        if(!empty($tema->estilos)) {
            foreach($tema->estilos as $item)
            {  $this->style[] = "\t$item\n"; };
        };
    }


    function setIcon($url) {
        $this->favicon = $url;
    }

    function addEnd($item) {
        global $RD_DEVEL_GLOBAL;
        $RD_DEVEL_GLOBAL[pag_end][] = $item;
    }
    
    function imprime() {
        global $config_ini,  $RD_DEVEL_GLOBAL;

   		header('Content-Type: text/html; charset=UTF-8');
        print ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">");
        print ("<html>");

    //Head
        
        
        print("<head>");
        print('<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >');
        if(!empty($this->refreshRate)) print("<meta http-equiv=\"refresh\" content=".$this->refreshRate.">");
  
  
        if(!empty($this->title)) print("<title>".$this->title."</title>");


        $req = $RD_DEVEL_GLOBAL[pag_requires];

        if(!empty($req)) {
            foreach($req as $item) {
                switch($item[type]){
                    case "JS":
                        $this->JSfiles[] = $config_ini[Internet][urljs]."/".$item[file];
                        break;
                    case "MEDIA_JS":
                        $this->JSfiles[] = $config_ini[Internet][urlmedia]."/".$item[file];
                        break;
                    case "MEDIA_CSS":
                        $this->styleFiles[] = $config_ini[Internet][urlmedia]."/".$item[file];
                        break;
                    case "CSS":
                        $this->styleFiles[] = $config_ini[Internet][urlcss]."/".$item[file];
                        break;
                }
            }
        }

        if(!empty($this->styleFiles)) {
            foreach($this->styleFiles as $item) {
                echo "<link rel=\"stylesheet\" href=\"$item\">\n";
            };
        };

        if(!empty($this->JSfiles)) {
            foreach($this->JSfiles as $item) {
                print("\n\t<script language=\"JavaScript1.2\" type=\"text/javascript\"  src=\"$item\"></script>\n");
            };
        }

        
        if(!empty($this->style))
        {   print("<style type=\"text/css\">\n");
        reset($this->style);
        while(list(,$item)=each($this->style))
        { printf("%s\n",$item); };
        print("</style>\n");
        }
         
        

        if(!empty($this->favicon)) {
            print("<link rel=\"icon\" href=\"$this->favicon\" type=\"image/ico\">");
            print("<link rel=\"SHORTCUT ICON\" href=\"$this->favicon\">");
        }
        
        
        print("</head>");



        if(!empty($this->frameset))
        { $this->printFrames(); };

        print ("<body ");
        if(!empty($this->bgcolor)) print(' bgcolor='.$this->bgcolor);
        if(!empty($this->bgimage)) print(' background="'.$this->bgimage.'" ');
        if(!empty($this->m_left))  print(" leftmargin=\"". ($this->m_left-1) ."\" topmargin=\"". ($this->m_top-1) ."\" marginwidth=\"". ($this->m_width-1) ."\" marginheight=\"". ($this->m_height-1)."\"" );

        if(!empty($RD_DEVEL_GLOBAL[preloadimages])) {
            $preload = "RD_preloadImages(";
            foreach($RD_DEVEL_GLOBAL[preloadimages] as $img) {
                $preload.="'$img',";
            }

            $preload[strlen($preload)-1] = ")";
            $preload.=";";
            $this->OnLoad.=$preload;
        }


        if(!empty($this->OnLoad))  print(" onLoad=\"$this->OnLoad\" ");
        if(!empty($this->OnClose))  print(" onUnLoad=\"$this->OnClose\"");
        print(">");

        parent::imprime();

        if(!empty($RD_DEVEL_GLOBAL[pag_end])) {
            foreach($RD_DEVEL_GLOBAL[pag_end] as $item) {
                if(is_string($item)) {
                    print($item);
                }
                else {
                    if(is_subclass_of($item,"pagobj")) {
                        $item->imprime();
                    };
                };
            }
        }


        print("</body>");


        print ("</html>");



    }

}


