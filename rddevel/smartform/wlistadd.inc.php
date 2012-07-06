<?

include_once("$rdpath/smartform/wselect.inc.php");

/**
 * Classe que implementa duas listas, uma com os objetos e outra que receberá os objetos da primeira
 *
 * Classe que implementa duas listas, uma com os objetos e outra que receberá os objetos da primeira
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see  WFormEl, WSelect
 */

class WListAdd extends WFormEl {
  var $options,$slist,$dlist,$groupingList,$groupingField,$groupin;

  function WListAdd($name,$source_list,$dest_list="",$fieldValue="",$fieldLabel="") {
    global $smartform;

    $this->source_list = $source_list;
    $this->dest_list = $dest_list;
    $this->fieldValue = $fieldValue;
    $this->fieldLabel= $fieldLabel;
    $this->setName($name);
    $this->name = $name;
    
    $this->requires("addlist.js");
        
  }

  function imprime() {
    global $smartform,$host;

    $this->slist = new WSelect($this->name."_source");
    $this->slist->parseOptions($this->source_list,$this->fieldValue,$this->fieldLabel);
    $this->slist->prop[multiple]="";
    $this->slist->prop[size]= 10; 


    $this->dlist = new WSelect($this->name."[]");
    $this->dlist->parseOptions($this->dest_list,$this->fieldValue,$this->fieldLabel);
    $this->dlist->prop[multiple]="";
    $this->dlist->prop[size]= 10;

    $smartform[$this->formName][submit_actions][] = "addListSend(document.\formName['".$this->name."[]'])";

    if (!empty($this->label)) {
      parent::add($this->label);
    }
    
    parent::add("<table border=0>");


    parent::add("<tr><td rowspan=2>");

    parent::add($this->slist);

    $b1 = new WButton("send",">>","button");
    $b1->setOnClick("javascript:move(this.form['".$this->slist->nome."'],this.form['".$this->dlist->nome."'])");

    $b2 = new WButton("del","<<","button");
    $b2->setOnClick("javascript:move(this.form['".$this->dlist->nome."'],this.form['".$this->slist->nome."'])");

    parent::add("<td>");
    parent::add($b1);
    parent::add("</td><td rowspan=2>");

    parent::add($this->dlist);

    parent::add("</td><tr><td>");
    parent::add($b2);

    parent::add("</table>");

    parent::imprime();

  }

}

?>