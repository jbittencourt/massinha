<?

class RDPaginacao {
  var $cursor,$numRecords,$recsPorPagina,$paginaAtual,$numPaginas;
  
  function RDPaginacao($cursor,$recsPorPagina=20) {
    $this->cursor = $cursor;
    $this->numRecords = $cursor->getCount();
    $this->recsPorPagina = $recsPorPagina;
    $this->paginaAtual = 1;
    $this->numPaginas = ceil($this->numRecords / $recsPorPagina);
  }

  /** Dado um certo numero de pagina constroi a limit query no cursor correspondendo a pagina atual 
   *  Faz a query no cursor
   */
  function atualiza($numPagina) {
    if ($numPagina <= $this->numPaginas) {
      $this->paginaAtual = $numPagina;
      $ini = $this->calcIniRecord($numPagina);
      $this->cursor->param->setLimitQuery($ini,$this->recsPorPagina);
      $this->cursor->lista();
    }
  }

  /** Calcula o numero do registro inicial da pagina
   *
   */
  function calcIniRecord($numPagina) {
    return (($numPagina-1) * $this->recsPorPagina);
  }

  /** Retorna o cursor
   *
   */
  function getCursor() {
    return $this->cursor;
  }
  
  /** Retorna o numero da pagina atual
   *
   */
  function getPagAtual() {
    return $this->paginaAtual;
  }

  /** Retorna o numero de paginas
   *
   */
  function getNumPaginas() {
    return $this->numPaginas;
  }

}

?>