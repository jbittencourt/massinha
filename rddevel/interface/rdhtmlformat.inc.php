<?

/** Classe para gerenciar as formatacoes de html
 *
 */
class RDHtmlFormat {
  
  var $iniTabela,$fimTabela;
  var $iniLinha,$fimLinha;
  var $iniColuna,$fimColuna;
  var $iniLinTitulo,$fimLinTitulo;
  var $iniColTitulo,$fimColTitulo;
  
  function RDHtmlFormat() {
    $this->defaultFormat();
  }
  
  /** Seta a formatacao default
   *
   */  
  function defaultFormat() {
    $this->iniTabela="table bgcolor=black><tr><td><table cellspacing=1><tr";
    $this->fimTabela="/table></td></tr></table";
    $this->iniLinha="tr";
    $this->fimLinha="/tr";
    $this->iniColuna="td bgcolor=white";
    $this->fimColuna="/td";
    //Titulo
    $this->iniLinTitulo="tr bgcolor=black style=\"color: white\"";
    $this->fimLinTitulo="/font></tr";
    $this->iniColTitulo="td";
    $this->fimColTitulo="/td";
  }
  /*
   
  function defaultFormat() {
    $this->iniTabela = "TABLE"; 
    $this->fimTabela = "/TABLE";
    $this->iniLinha = "TR";
    $this->fimLinha = "/TR";
    $this->iniColuna = "TD";
    $this->fimColuna = "/TD";
    $this->iniLinTitulo = "TR";
    $this->fimLinTitulo = "/TR";
    $this->iniColTitulo = "TD";
    $this->fimColTitulo = "/TD";
    }*/

  function getIniTabela() {    
    return $this->iniTabela;
  }
  
  function getFimTabela() {
    return $this->fimTabela;
  }
  
  function getIniLinha() {
    return $this->iniLinha;
  }

  function getFimLinha() {
    return $this->fimLinha;
  }
  
  function getIniColuna() {
    return $this->iniColuna;
  }
  
  function getFimColuna() {
    return $this->fimColuna;
  }
  
  function getIniLinTitulo() {
    return $this->iniLinTitulo;
  }

  function getFimLinTitulo() {
    return $this->fimLinTitulo;
  }
  
  function getIniColTitulo() {
    return $this->iniColTitulo;
  }
  
  function getFimColTitulo() {
    return $this->fimColTitulo;
  }
  
  function getIniTabelaTag() {    
    return "<".$this->iniTabela.">";
  }
  
  function getFimTabelaTag() {
    return "<".$this->fimTabela.">";
  }
  
  function getIniLinhaTag() {
    return "<".$this->iniLinha.">";
  }

  function getFimLinhaTag() {
    return "<".$this->fimLinha.">";
  }
  
  function getIniColunaTag() {
    return "<".$this->iniColuna.">";
  }
  
  function getFimColunaTag() {
    return "<".$this->fimColuna.">";
  }
  
  function getIniLinTituloTag() {
    return "<".$this->iniLinTitulo.">";
  }

  function getFimLinTituloTag() {
    return "<".$this->fimLinTitulo.">";
  }
  
  function getIniColTituloTag() {
    return "<".$this->iniColTitulo.">";
  }
  
  function getFimColTituloTag() {
    return "<".$this->fimColTitulo.">";
  }

  function setTabela($iniTabela,$fimTabela) {
    $this->iniTabela = $iniTabela;
    $this->fimTabela = $fimTabela;
  }
  
  function setLinha($iniLinha,$fimLinha) {
    $this->iniLinha = $iniLinha;
    $this->fimLinha = $fimLinha;
  }
  
  function setColuna($iniColuna,$fimColuna) {
    $this->iniColuna = $iniColuna;
    $this->fimColuna = $fimColuna;
  }
  
  function setLinhaTitulo($iniLinTitulo,$fimLinTitulo) {
    $this->iniLinTitulo = $iniLinTitulo;
    $this->fimLinTitulo = $fimLinTitulo;
  }
  
  function setColunaTitulo($iniColTitulo,$fimColTitulo) {
    $this->iniColTitulo = $iniColTitulo;
    $this->fimColTitulo = $fimColTitulo;
  }
  
}


?>