<?

include_once("$rdpath/smartform/wformel.inc.php");
include_once("$rdpath/smartform/wtext.inc.php");
include_once("$rdpath/smartform/wcalendar.inc.php");


/**
 * Classe que implementa um form tipo Data
 *
 * Classe que implementa um form tipo Data
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see  WForm, WSmartform, WText
 */
class WData extends WFormEl {
  var $formato, $calendarOn;

  function WData($name,$value,$formato) {
    $this->nome = $name;
    $this->name = $name;
    $this->value = $value;
    $this->formato = $formato;
    $this->requires("data.js.php");

  }

  function setCalendarOn() {
    $this->calendarOn = 1;
    $this->calendar = new WCalendar(&$this);
  }

 
  function imprime() {
    global $smartform,$lang;

    if($this->design != WFORMEL_DESIGN_STRING_DEFINED) $str = $this->label;
    if($this->design == WFORMEL_DESIGN_OVER) $str.= "<br>";

    $formato = $this->formato;
    $n = strlen($formato);

    if(!empty($this->prop[value])) {
      $date = getdate($this->prop[value]);
    };

   
    $form[day] = 0;
    $form[month] = 0;
    $form[year] = 0;
    $form[hours] = 0;
    $form[minutes] = 0;
    $form[seconds] = 0;

    $last ="";
    for($i=0;$i<$n;$i++) {
      $simb = $formato[$i];
      if($last!='\\') {

	switch($simb) {
	case "d":
	  $input = new WText($this->name."_day",$date[mday],2,2);
	  $str .= $input->toString();
	  $form[day] = "document.\formName['".$this->name."_day'].value";
	  break;
	case "m":
	  $input = new WText($this->name."_month",$date[mon],2,2);
	  $str .= $input->toString();
	  $form[month] = "document.\formName['".$this->name."_month'].value";
	  break;
	case "F":
	  $meses = array(1=>$lang[january],
			 2=>$lang[february],
			 3=>$lang[march],
			 4=>$lang[april],
			 5=>$lang[may],
			 6=>$lang[june],
			 7=>$lang[july],
			 8=>$lang[august],
			 9=>$lang[september],
			 10=>$lang[october],
			 11=>$lang[november],
			 12=>$lang[december]);
	  $input = new WSelect($this->name."_month");
	  $input->parseOptions($meses);
	  $input->setValue($date[mon]);
	  $str .= $input->toString();
	  $form[month] = "document.\formName['".$this->name."_month'].value";
	  break;
	case "y":
	  $input = new WText($this->name."_year",$date[year],2,2);
	  $str .= $input->toString();
	  $form[year] = "document.\formName['".$this->name."_year'].value";
	  break;
	case "Y":
	  $input = new WText($this->name."_year",$date[year],4,4);
	  $str .= $input->toString();
	  $form[year] = "document.\formName['".$this->name."_year'].value";
	  break;
	case "h":
	  $input = new WText($this->name."_hour",$date[hours],2,2);
	  $input->prop[onBlur] = "return validateHour(this);";
	  $str .= $input->toString();
	  $form[hours] = "document.\formName['".$this->name."_hour'].value";
	  break;
	case "i":
	  $input = new WText($this->name."_minutes",$date[minutes],2,2);
	  $input->prop[onBlur] = "return validateMinutes(this);";
	  $str .= $input->toString();
	  $form[minutes] = "document.\formName['".$this->name."_minutes'].value";
	  break;
	case "s":
	  $input = new WText($this->name."_seconds",$date[seconds],2,2);
	  $input->prop[onBlur] = "return validateSeconds(this);";
	  $str .= $input->toString();
	  $form[seconds] = "document.\formName['".$this->name."_seconds'].value";
	  break;

	default:
	  if($simb!="\\") 
	    $str .= $simb;
	}
      }
      else {
	$str .= $simb;
      }
      $last = $simb;
    }
    $this->add($str);

    if($this->calendarOn) {
      $this->add($this->calendar);
    }

    if(!empty($this->formName)) {
      $smartform[$this->formName][submit_actions][] = "makeUnixDate(document.\formName['".$this->name."'],date2timestamp($form[hours],$form[minutes],$form[seconds],$form[month],$form[day],$form[year]))";
    };

    $hid = new WHidden($this->name,$this->value);
    $this->add($hid);
    
    parent::imprime();
  }

}


?>
