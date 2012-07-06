<?

/**
 */
/**
 * Classe que implementa um calendário pop-up
 *
 * Classe que implementa um calendário pop-up
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see  WForm
 */
class WCalendar extends RDPagObj {
  var $wdate;

  function WCalendar($wdate) {
    $this->wdate = $wdate; 

    $this->requires("browserSniffer.js");
    $this->requires("calendar.js.php");
    $this->requires("dynCalendar.css","CSS");
  }

  function imprime() {
    $wd = $this->wdate;
    if(empty($wd->name)) {
      return 0;
    };

    $str = "";
    $str.= " function setDateFromCalendar_".$wd->name."(date,month,year) {";
    $str.= "    document.".$wd->formName.".".$wd->name."_day.value = date;"; 
    $str.= "    document.".$wd->formName.".".$wd->name."_month.value = month;"; 
    $str.= "    document.".$wd->formName.".".$wd->name."_year.value = year;";
    $str.= "};";

    $this->addScript($str);

    $this->addScript("calendar_".$wd->name." = new dynCalendar('calendar_".$wd->name."', 'setDateFromCalendar_".$wd->name."');");
    parent::imprime();
  }
}


?>
