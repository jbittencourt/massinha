<?

include_once("$rdpath/interface/rdhtmlformat.inc.php");

class RDPagLista extends RDPagina {
  var $lista,$camposAusentes,$camposPresentes,$ordemCampos;
  var $dataHeader,$dataHeaderStyle,$dataStyle;
  var $dateTimeFields,$formatoData;
  var $acoes;    //acoes do usuario padrao inserir,alterar,excluir
  var $acoesGlobais; //acoes que aparecerao no comeco da listagem, para serem usadas com as check boxes 
  var $footer;
  var $htmlFormat;
  var $startIndex,$endIndex;
  var $drawCheckBoxes;
  
  //variaveis referente a paginacao
  var $numPaginas,$pagAtual,$linkPagina;
  //$linkPagina eh o link que sera usado nos links para trocar de pagina
  
  function RDPagLista($lista,$camposAusentes="") {
    $this->lista = $lista;
    if (!empty($camposAusentes)) {
      $this->setCamposAusentes($camposAusentes);
    }
    $this->formatoData = "d/m/Y";
    
    //seta as acoes default
    $this->setAcoes(array("inserir","alterar","excluir"));
    
    $formatacao = new RDHtmlFormat(); //pega a formatacao default
    $this->setHtmlFormat($formatacao);
    
  }

  /** Se deve ou nao desenhar as checkboxes
   *
   */
  function drawCheckBoxes($draw=1) {
    $this->drawCheckBoxes = $draw;
  }

  function setPaginacao($paginacao,$linkPagina="") {
    $this->numPaginas = $paginacao->getNumPaginas();
    $this->pagAtual = $paginacao->getPagAtual();
    $this->linkPagina = $linkPagina;
  }

  function setNumPaginas($numPaginas) {
    $this->numPaginas = $numPaginas;
  }
  
  function setLinkPagina($linkPagina) {
    $this->linkPagina = $linkPagina;
  }

  /** Seta os indices onde deve comecar e terminar a listagem
   *  Usado quanto esta se paginando sem usar o objeto RDPaginacao
   */
  function setIndexes($startIndex,$endIndex) {
    $this->startIndex = $startIndex;
    $this->endIndex = $endIndex;
  }
  
  /** Seta a formatacao html para ser usada
   *
   */
  function setHtmlFormat($format) {
    $this->htmlFormat = $format;
  }
  
  function getHtmlFormat() {
    return $this->htmlFormat;
  }
  
  /**    
   *  Seta as acoes que estarao presentes mo objeto
   *  @param array $acoes : Array com os nomes das acoes : "inserir,alterar,deletar"
   */
  function setAcoes($acoes) {
    $this->acoes = $acoes;
  }
  
  function getAcoes() {
    return $this->acoes;
  }
  
  /* Adiciona uma Acao */
  function addAcao($texto,$link,$parametrosAdicionaisLink="") {
    $this->acoes[] = array("texto"=>$texto,"link"=>$link,"paramAdicionais"=>$parametrosAdicionaisLink); 
  }
  
  /** Adiciona uma acao global
   *  Exemplo : addAcaoGlobal("Deletar usuario","usuario.php","A_exclui")
   */
  function addAcaoGlobal($texto,$scriptTarget,$actionTarget="") {
    $this->acoesGlobais[] = array("texto"=>$texto,"scriptTarget"=>$scriptTarget,"actionTarget"=>$actionTarget);
  }

  
  /**
   *  Seta os campos que nao farao parte da listagem
   *  @param array $campos_ausentes
   */
  function setCamposAusentes($campos_ausentes="") {
    if (is_array($campos_ausentes)) {
      $this->camposAusentes = $campos_ausentes;
    }
    else {
      $this->camposAusentes[] = $campos_ausentes;
    }
  }
  
  /** Seta os campos presentes
   *  @param array $campos_presentes : Array dos campos presentes
   *  @param boolean $ordem : Se estiver setado para 1 entao o mesmo array de campos presentes eh usado para setar a ordem 
   *                            em que os campos serao mostrados
   */

  function setCamposPresentes($campos_presentes,$ordem=0) {
    if (is_a($this->lista,"RDCursor")) {
      $campos = $this->lista->getCamposProjecaoSemNomesTabela();
      $campos_ausentes = array();
      if (!empty($campos)) {
	foreach ($campos as $campo) {
	  if (!in_array($campo,$campos_presentes)) {
	    $campos_ausentes[] = $campo;
	  }
	}
      }
    }
    
    $this->camposPresentes = $campos_presentes;
    
    $this->setCamposAusentes($campos_ausentes);
    if ($ordem) {
      $this->setOrdemCampos($campos_presentes);
    }
    
  }
  
  /**
   *  Seta a ordem dos campos
   *  @param array $ordem
   */
  function setOrdemCampos($ordem) {
    $this->ordemCampos = $ordem;
  }

  /**
  *  Seta o cabecalho dos campos
  *  eh um array no formato array[nome_do_campo] = label
  *  se nao for setado nada vai usar o nome dos campos
  *  @param array $head
  *
  */
  function setDataHeader($head) {
    $this->dataHeader = $head;
  }

  /**
   *  Retorna os campos que estarao presentes na listagem (desconta os campos ausentes)
   *  @return array
   *
   */
  function getCamposPresentes() {
    //$campos  = $this->lista->getCamposProjecao();
    if (is_a($this->lista,"RDCursor")) {
      $campos = $this->lista->getCamposProjecaoSemNomesTabela();
      $campos_na_listagem = array();
      if (!empty($campos)) {
	foreach ($campos as $campo) {
	  if (strpos($campo," ") !== FALSE)
	    list(,$campo) = explode(" ",$campo);
	  if (is_array($this->camposAusentes)) {
	    if (!in_array($campo,$this->camposAusentes)) {
	      $campos_na_listagem[] = $campo;
	    }
	  }
	  else {
	    $campos_na_listagem[] = $campo;
	  }
	}
      }
      return $campos_na_listagem;
    }
    else {
      return $this->camposPresentes;
    }
  }

  /**
   *Retorna os campos que serao usados em ordem
   *@param int $flagNomeTabela Se for igual a 0 entao tira o nome da tabela caso o nome do campos esteja no formato
   *  nomeTabela.nomeCampo  
   *@return array
  */
 
  function getCamposOrdenados($flagNomeTabela=0) {    
    $campos = $this->getCamposPresentes();
    $campos_em_ordem = array();
    if (is_array($this->ordemCampos)) {
      $campos_em_ordem = $this->ordemCampos;
      foreach ($campos as $campo) {
        if (!in_array($campo,$campos_em_ordem)) {
          $campos_em_ordem[] = $campo;
        }
      }
    }
    else {
      $campos_em_ordem = $campos;
    } 
    
    if (!$flagNomeTabela) {
      $campos_em_ordem_sem_nomes_tabela = array();
      foreach ($campos_em_ordem as $campo) {
	$pos = strpos($campo,"."); //acha a posicaoo do ponto
	if ($pos === FALSE)
	  $campos_em_ordem_sem_nomes_tabela[] = $campo;
	else 
	  $campos_em_ordem_sem_nomes_tabela[] = substr($campo,$pos+1); //tira o nome da tabela e o ponto
      }
      return $campos_em_ordem_sem_nomes_tabela;
    }
    else
      return $campos_em_ordem;
  }
 
  /** 
   *  Adiciona um campo que eh do tipo date time (na impressao ira formatar a data automaticamente)
   *  @param string $campo : Nome do campo
   */
  function addDateTimeField($campo) {
    $this->dateTimeFields[] = $campo;
  }

  /**
   * Seta o nome de todos os campos date time
   * @param array $campos : Nomes dos campos
   */
  function setDateTimeFields($campos) {
    $this->dateTimeFields = $campos;
  }
 
  /**
   *  Funcao booleana que retorna se um determinado campo eh do tipo date time
   *  @param string $campo : Nome do campo
   *  @return boolean 
   */
  function isDateTimeField($campo) {
    if (is_array($this->dateTimeFields)) {
      return in_array($campo,$this->dateTimeFields);
    }
    else {
      return 0;
    }
  }
  /**
   * Seta o formato das datas ( para ser usados na impressao de campos do tipo date time)
   * @param string $formato : Formato da data, no mesmo padrao que o php usa
   */

  function setDateFormat($formato) {
    $this->formatoData = $formato;
  }

  /**
   *  Retorna o formato data
   *  @return string
   */
  function getDateFormat() {
    return $this->formatoData;
  }
  
  /**
   *  Retorna o label do campo
   *  @param string $campo
   *  @return string
   */  
  function getFieldLabel($campo) {
    return $this->dataHeader[$campo];
  }
  
  /**
  *  Seta o conteudo 
  *  Eh um objeto RDPagObj,RDPagina ou eh html texto
  *  @param mixed $footer
  */
  function setFooter($footer) {
    $this->footer = $footer;
  }
  
  function getFooter() {
    return $this->footer;
  }

  /** Retorna os objetos que foram selecionados na listagem anterior e foi selecionada uma acao sobre eles 
      Este metodo pode ser chamado estaticamente
   */
  function getObjetosSelecionados($objClass) {
    $obj = new $objClass();
    $camposChavePrimaria = $obj->getKeyFieldsOfTable($obj->getTableOfHigherClass());
    
    $param = new RDParam();
    
    $sql = "";
    foreach($_REQUEST as $nomeCampoRequest=>$valor) {
      $valores = explode("_",$nomeCampoRequest);
      if ($valores[0] == "check") {
	if (!empty($sql)) $sql.= " OR ";
	$sql.= " ( ";
	$i = 1;
	$s = "";
	foreach($camposChavePrimaria as $campoChave) {
	  if (!empty($s)) $s.= " AND ";
	  $s.= " ".$campoChave."=".$valores[$i];
	  $i++;
	}
	$sql.= $s . " ) ";
      }
    }
    
    $param->setSqlWhere($sql);
    $objetos = new RDLista($objClass,$chaves,"",$param);
    return $objetos;
  }

  /**
   * Funcao que seta o estilo para o cabecalhos dos dados
   * @param string $estilo : Nome da classe de estilo ou uma string contendo os estilos css propriamente ditos
   * @param boolean $isClassName : Se igual a 1 entao $estilo eh um nome de uma classe de estilo, nao a propria definicao do estilo
   */  
  function setDataHeaderStyle($estilo,$isClassName=1) {
    $this->dataHeaderStyle[style] = $estilo;
    if ($isClassName)
      $this->dataHeaderStyle[isClassName] = 1;
    else
      $this->dataHeaderStyle[isClassName] = 0;
  }
  
  /**
   * Funcao que seta o estilo para o cabecalhos dos dados
   * @param string $linha_par : Nome da classe de estilo ou uma string contendo os estilos css propriamente ditos das linhas pares
   * @param string $linha_impar : Nome da classe de estilo ou uma string contendo os estilos css propriamente ditos das linhas impares
   * @param boolean $isClassName : Se igual a 1 entao $estilo eh um nome de uma classe de estilo, nao a propria definicao do estilo
   */
  function setDataStyle($linha_par="",$linha_impar="",$isClassName=1) {
    if (!empty($linha_par)) {
      $this->dataStyle[par] = $linha_par;
      $this->dataStyle[impar] = $linha_impar;
      if ($isClassName)
	$this->dataStyle[isClassName] = 1;
      else 
	$this->dataStyle[isClassName] = 0;
    }

  }
  
  /**
   *  Imprime o cabecalho dos dados
   *  parametro $campos => campos do relatorio ordenados
   *  @param array $campos 
   */
  function printHeader($campos) {    
    $format = $this->getHtmlFormat();
    $html = "<" . $format->getIniLinTitulo();
    if (!empty($this->dataHeaderStyle)) {
      if($this->dataHeaderStyle[isClassName])
	$html.= " class=\"".$this->dataHeaderStyle[style]."\"";
      else {
	$this->addClassStyle(".RDListaHeader",$this->dataHeaderStyle[style]); //adiciona a classe referente ao estilo
	$html.= " class=\"RDListaHeader\"";
      }   
    }
    $html.= ">";
    
    $this->add($html);

    if ($this->drawCheckBoxes)
      $this->add($format->getIniColTituloTag() ."&nbsp;".$format->getFimColTituloTag());

    //imprime o cabecalho dos dados
    if (!empty($campos)) {
      foreach ($campos as $campo) {
        $label = $this->getFieldLabel($campo);   //retorna o label  do campo
	if (empty($label)) {
          $label = $campo;    //se label estiver em branco entao usar o nome do campo
        }
        $this->add("<" . $format->getIniColTitulo() . ">" .  $label . "<" . $format->getFimColTitulo() . ">");
      }
    }
    $this->add("<" . $format->getFimLinTitulo() . ">");
  }

  /**
   *  Imprime os dados
   *  parametro $campos => campos do relatorio ja ordenados
   *  @param array $campos
   *
   */  
  function printData($campos) {

    $format = $this->getHtmlFormat();
    
    if(!$this->dataStyle[isClassName]) {
      //adiciona as classes de estilo
      if (!empty($this->dataStyle[par]))   
	$this->addClassStyle(".RDLinhaPar",$this->dataStyle[par]);
      if (!empty($this->dataStyle[impar]))
	$this->addClassStyle(".RDLinhaImpar",$this->dataStyle[impar]);
    }
    
    //eh uma lista, imprimir todos os registros
    if (!empty($this->lista->records)) {
      $obj = &$this->lista->records[0]; 
      $camposChavePrimaria = $obj->getKeyFieldsOfTable($obj->getTableOfHigherClass()); //pega os campos que sao chabe primaria
      $acoes = $this->getAcoes();  //acoes que serao disponiveis na listagem
      $num_linha = 1;

      if (!isset($this->startIndex)) {
	$this->startIndex = 0;
	$this->endIndex = $this->lista->numRecords() -1;
      }
	
      for ($i=$this->startIndex; $i<= $this->endIndex; $i++) {
	$obj = &$this->lista->records[$i];
	
	$html = "<" . $format->getIniLinha();
	
	//estilo das linhas do relatorio
	if (!empty($this->dataStyle)) {
	  
	  $estilo = "";
	  if ($num_linha==1) {	    
	    if ($this->dataStyle[isClassName])
	      $nomeClasseEstilo = $this->dataStyle[impar];
	    else
	      $nomeClasseEstilo = "RDLinhaImpar";  
	    $estilo.= " class=\"".$nomeClasseEstilo."\"";
	    $num_linha = 0;
	  }
	  else {
	    if ($this->dataStyle[isClassName])
	      $nomeClasseEstilo = $this->dataStyle[par];
	    else
	      $nomeClasseEstilo = "RDLinhaPar"; 
	    $estilo.= " class=\"".$nomeClasseEstilo."\"";
	    $num_linha = 1;
	  }
	  $html.= $estilo;
	}
	
	$html.= ">";
	$this->add($html);

	if ($this->drawCheckBoxes) {
	  $this->add($format->getIniColunaTag());
	  //desenha a checkbox
	  //no nome da checkbox vao os valores dos campos chave do objeto
	  $nomeCheckBox = "check";
	  foreach($camposChavePrimaria as $campo) {
	    $nomeCheckBox.= "_".$obj->$campo;
	  }
	  $check = new WCheckBox($nomeCheckBox,1);
	  $this->add($check);
	  $this->add($format->getFimColunaTag());
	}

	foreach ($campos as $campo) {
	  $this->add("<" . $format->getIniColuna() . ">");
	  if ($this->isDateTimeField($campo)) {
	    //campo eh do tipo data
	    $data_formatada = date($this->getDateFormat(),$obj->$campo);
	    $this->add($data_formatada);
	  }
	  else {
	    //campo eh um campo normal, apenas mostrar seu valor
	    //$valor = $obj->$campo;
	    $this->add($obj->$campo);
	  }
	  $this->add("<". $format->getFimColuna() . ">");
	  
	}
	
	//identificador
	$id = "";
	foreach ($camposChavePrimaria as $campoChavePrimaria) {
	  $id.= "&frm_".$campoChavePrimaria."=".$obj->$campoChavePrimaria;
	}
	
	if (is_array($acoes)) {
	  if (in_array("alterar",$acoes)) {
	    $link = $_SERVER[PHP_SELF]."?acao=A_altera";
	    foreach ($camposChavePrimaria as $campoChavePrimaria) {
	      $link.= "&frm_".$campoChavePrimaria."=".$obj->$campoChavePrimaria;
	    }
	    $this->add("<". $format->getIniColuna() . "><A href=\"".$link."\">Alterar</A><" . $format->getFimColuna() . ">");
	  }
	  if (in_array("excluir",$acoes)) {
	    $link = $_SERVER[PHP_SELF]."?acao=A_exclui";
	    foreach ($camposChavePrimaria as $campoChavePrimaria) {
	      $link.= "&frm_".$campoChavePrimaria."=".$obj->$campoChavePrimaria;
	    }
	    $this->add("<". $format->getIniColuna() . "><A href=\"".$link."\">Excluir</A><". $format->getFimColuna() . ">");
	  }
	}
	
	if (!empty($acoes)) {
	  foreach ($acoes as $acao) {
	    //acoes definidas pelo usuario
	    if (is_array($acao)) {
	      eval("\$link = \"$acao[link]\";");
	      $link .= $id;
	      $this->add("<". $format->getIniColuna() . "><A href=\"".$link."\" ".$acao[paramAdicionais].">".$acao[texto]."</A><" . $format->getFimColuna() . ">");
	    }
	  }
	}
	
	$this->add("<" . $format->getFimLinha() . ">");
      }
    }
    else {
      $this->add("<". $format->getIniLinha() . "><". $format->getIniColuna() . "> Nenhum item <".$format->getFimColuna()."><".$format->getFimLinha().">");
      //      $this->add("<TR><TD>&nbsp;</TD><TD colspan=\"2\">Nenhum item</TD></TR>");
    }
  }

  function imprimePaginacao() {
    $this->add("<TABLE>");
    $this->add("<TR><TD>Pag</TD>");
    if (empty($this->linkPagina))
      $link = $_SERVER[PHP_SELF]."?";
    else
      $link = $this->linkPagina;
    
    for ($i=1; $i<=$this->numPaginas; $i++) {
      if ($i==$this->pagAtual)
	$this->add("<TD><A href=\"".$link."numPagina=".$i."\" target=\"_top\"><B>".$i."</B></A></TD>");
      else
	$this->add("<TD><A href=\"".$link."numPagina=".$i."\" target=\"_top\">".$i."</A></TD>");
    }
    $this->add("</TR></TABLE>");
  }
  
  /**  
   *   Funcao de impressao, chamada para imprimir a pagina (mandar para o browser)
   *   Redefina esta funcao se desejar, para imprimiir do jeito que voce deseja
   */
  function imprime() {
    
    $campos = $this->getCamposOrdenados();
    
    $formatacao = $this->getHtmlFormat();

    if ($this->drawCheckBoxes) {
      //script para selecao das checkboxes
      $this->add("<SCRIPT language=\"JavaScript\" type=\"text/javascript\">");
      $js = "function selecionaTodos() {\n";
      $js.= "  for (i=0; i< document.formPagLista.length; i++) {\n";
      $js.= "    document.formPagLista.elements[i].checked = true;";
      $js.= "  }\n";
      $js.= "}\n";
      $js.= "function selecionaNenhum() {\n";
      $js.= "  for (i=0; i< document.formPagLista.length; i++) {\n";
      $js.= "    document.formPagLista.elements[i].checked = false;";
      $js.= "  }\n";
      $js.= "}\n";
      //muda a acao do formulario
      $js.= "function submete(acaoForm,acaoScript) {\n";
      $js.= "  document.formPagLista.action = acaoForm;\n";
      $js.= "  document.formPagLista.acao.value = acaoScript;\n";
      //      $js.= "  alert(document.formPagLista.action);\n";
      $js.= "  document.formPagLista.submit();\n";
      $js.= "}\n";
      
      
      $this->add($js);
      $this->add("</SCRIPT>");
    }

    if ( (is_array($this->acoes) && in_array("inserir",$this->acoes)) || !empty($this->acoesGlobais)) {

      $this->add("<TABLE><TR>");
      
      //acao de inclui
      if (is_array($this->acoes)) {
	if (in_array("inserir",$this->acoes))
	  $this->add("<TD><A href=\"".$_SERVER[PHP_SELF]."?acao=A_inclui\">Incluir </A></TD>");
	$imprimeAcaoTopo = 1;
      }
      
      //acoes globais
      if (!empty($this->acoesGlobais)) {
	foreach($this->acoesGlobais as $key=>$acao) {
	  $this->add("<TD><A href=\"javascript: submete('".$acao["scriptTarget"]."','".$acao["actionTarget"]."')\">");
	  if ($key > 0) $this->add(" | &nbsp;");
	  $this->add($acao["texto"]."</A></TD>");
	}
	$imprimeAcaoTopo = 1;
      }
      
      //se desenha as check boxes links para selecionar todos ou nenhum
      if ($this->drawCheckBoxes) {
	$this->add("<TD> | <A href=\"javascript: selecionaTodos()\">Selecionar todos</A></TD>");
	$this->add("<TD> | <A href=\"javascript: selecionaNenhum()\">Selecionar nenhum</A></TD>");
	$imprimeAcaoTopo = 1;
      }
      
      $this->add("</TR></TABLE>");
      
    }

    //necessario para as checkboxes
    if ($this->drawCheckBoxes) {
      $this->add("<FORM name=\"formPagLista\" action=\"".$_SERVER[PHP_SELF]."\" methos=\"POST\">");
      $this->add("<INPUT type=\"hidden\" name=\"acao\">");
    }
    

    $this->add("<". $formatacao->getIniTabela() . ">"); 
    
    $this->printHeader($campos);
    $this->printData($campos);
    
    $this->add("<". $formatacao->getFimTabela() . ">");

    if ($drawCheckBoxes)
      $this->add("</FORM>");
    
    //imprime a paginacao(se houver)
    if (!empty($this->numPaginas)) {
      $this->imprimePaginacao();
    }
    
    $this->add($this->getFooter());
    parent::imprime();
    
  }
} 
  
?>