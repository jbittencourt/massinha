<?

function note($var) {
    echo "<pre>Men:\n"; print_r($var); echo "</pre>";
}


function noteAtrib($cursor,$atribs) {
    if (!is_array($atribs))
    $atribs = array($atribs);
    foreach($cursor->records as $obj) {
        echo "<pre>Men: \n";
        foreach($atribs as $atr)
        echo $atr." : ".$obj->$atr."\n";
    }
}

function noteLastquery() {
    global $TAB_lastquery;
    note($TAB_lastquery);
}

include($rdpath . "/autload.inc.php");
include("$rdpath/base/tabelas.inc.php");


?>