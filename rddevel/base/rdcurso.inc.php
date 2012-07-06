<?

/**
 * Classe que define um curso
 *
 * @author Juliano Bittencourt <juliano@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage base
 * @see RDObj
 */
class RDCurso extends RDObj {
  function RDCurso($chave="") {

    $tabelaCurso = "curso";
    $camposCurso = array("codCurso","nomCurso","codAdm","desCurso","desUrl","tempo");
    $chavesPCurso = "codCurso";

    $this->RDObj($tabelaCurso,$camposCurso,$chavesPCurso);  
	$this->le($chave);
  }

  function listaUsuariosCurso() {

  
  }
}


class RDCursoMatricula extends RDObj {
  function RDCursoMatricula($chave="") {

    $tabelaMatricula = "cursoMatricula";
    $camposMatricula = array("codCurso","codUser","tempo");
    $chavesPMatricula = "codUser";
 

    $this->RDObj($tabelaMatricula,$camposMatricula,$chavesPMatricula);  
    $this->le($chave);
  }
  
}



?>