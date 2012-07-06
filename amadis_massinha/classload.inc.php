<?

$inc_path = get_include_path();
$new = $_CMAPP['path'] . "/templates:" . $_CMAPP['path'] . "/templates/boxes:" . $_CMAPP['path'] . "/lib";
set_include_path("$inc_path:$new");

function __autoload($class_name) {
  //extracts the prefix of the class
    $prefix =   strtolower(substr($class_name, 0, 2));

    switch($prefix) {
        case "ae":
        case "wa":
        case "am":
		    $filename = strtolower($class_name).'.inc.php';
            include($filename);
            break;
        case "rd":
        default:
            __rdautoload($class_name);
            break;
            
    }
}


?>