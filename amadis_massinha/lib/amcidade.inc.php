<?


$AMEstados = array( "RS" => "Rio Grande do Sul",
		    "SC" => "Santa Cataria",
		    "RJ" => "Rio de Janeiro",
		    "SP" => "São Paulo"
);


class AMCidade extends RDObj {

    function AMCidade($key="") {
        $table = "cidade";
        $fields = array("codCidade","nomCidade","nomEstado","tempo");
        $pkFields = "codCidade";

        $fields_def = array();
        $fields_def[codCidade] = array("type" => "int","size" => "11","bNull" => "0");
        $fields_def[nomCidade] = array("type" => "varchar","size" => "100","bNull" => "0");
        $fields_def[nomEstado] = array("type" => "char","size" => "2","bNull" => "0");
        $fields_def[tempo] = array("type" => "int","size" => "11","bNull" => "0");

        $this->RDObj($table,$fields,$pkFields,$key,$fields_def);

    }


}

