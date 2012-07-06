<?php

class menuLateral extends RDPagObj 
{
    var $no_wrap,$itens,$jsitem;

    function setNoWrap() {
        $this->no_wrap= 1;
    }
    
    function menuLateral($itens=array()) {
        $this->itens = $itens;
    }

    function setJSMenuLateral($item) {
        $this->jsitem[$item] = 1;
    }

    function add($item,$link="") {
        $this->itens[$item] = $link;
    }

    function imprime()  {
        global $urltema, $urlimagens;

        $itens = &$this->itens;
        if($this->no_wrap) $wrap = " nowrap ";

        parent::add ("<img src=\"".$urltema."space.gif\" height=1 width=10 align=\"middle\">");

        $cont=0;

        foreach ($itens as $item=>$link)  {

            if ($cont != "0") {
                parent::add ("|&nbsp;&nbsp;&nbsp;");
            }
            
            if(!empty($link)) {
                 
    //testa para ver se o link n�o � um js assim imprimindo de forma diferente utilizando onClick
                if(isset($this->jsitem[$cont])) {
                    parent::add ("<a href=\"#\" onClick=\"$link\" class=\"regular\"><font size=\"2\">$item</font></a>");
                }
                else {
                    parent::add ("<font size=\"2\"><a href=\"$link\" target=\"_top\" class=\"regular\">$item</a>");
                };
            }
            else {
                parent::add ("<a href=\"$link\" target=\"_top\" class=\"regular\"><font size=2>$item</font></a>");
            }

            $cont++;
            parent::add ("<img src=\"".$urltema."space.gif\" height=1 width=10 align=\"middle\">");
        }


        parent::add ("</td><TD background=\"".$urltema."pattern14.gif\" width=10 height=25><img src=\"".$urltema."space.gif\" height=13 width=10></TD>");

        /*
        parent::add ("</TR><TR>");
        parent::add ("<td width=\"100%\"></td>");
        parent::add ("<td background=\"$urlimagens/space.gif\" width=30><img src=\"$urlimagens/space.gif\" width=1 height=1></TD>");
        parent::add ("<td background=\"$urlimagens/blackline.gif\"><img src=\"$urlimagens/blackline.gif\" width=1 height=1></TD>");
        parent::add ("<td background=\"$urlimagens/blackline.gif\" width=10><img src=\"$urlimagens/blackline.gif\" width=1 height=1></TD>");
        parent::add ("</TR></TABLE>");
        */
        parent::add ("</TR><TR>");
        parent::add ("<td width=\"100%\"></td>");
        parent::add ("<td background=\"$urlimagens/space.gif\" width=30><img src=\"$urlimagens/space.gif\" width=1 height=1></TD>");
        parent::add ("<td background=\"$urlimagens/blackline.gif\"><img src=\"$urlimagens/blackline.gif\" width=1 height=1></TD>");
        parent::add ("<td background=\"$urlimagens/blackline.gif\" width=10><img src=\"$urlimagens/blackline.gif\" width=1 height=1></TD>");
        parent::add ("</TR></TABLE>");

        parent::add ("<TABLE border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">");
        parent::add ("<TR bgcolor=\"ffffff\">");
        parent::add ("<TD width=\"3%\" valign=\"top\" align=\"center\" bgcolor=\"ffffff\"></TD>");
        parent::add ("<TD width=\"94%\" valign=\"top\" align=\"center\" bgcolor=\"ffffff\">");

        parent::imprime();
    }
}

