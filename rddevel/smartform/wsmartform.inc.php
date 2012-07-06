<?

include_once("$rdpath/smartform/wform.inc.php");
include_once("$rdpath/smartform/wtip.inc.php");


$RD_DEVEL_GLOBAL[lang][required][] = "smartform";

/**
 * Classe cujo objetivo e automatizar o processo de construcao de formularios
 *
 * Classe cujo objetivo e automatizar o processo de construcao de formularios
 *
 * @author Maicon Brauwers <maicon@edu.ufrgs.br>
 * @access public
 * @version 0.5
 * @package rddevel
 * @subpackage smartform
 * @see  WForm
 */
class WSmartForm extends WForm {
    var $objClass,$componentes;
    var $rows = array();
    var $design;
    var $name;
    var $urlOnCancel;

    function WSmartForm($objClass,$name,$action,$campos_ausentes="",$campos_hidden="",$method="POST",$enctype="") {
        global $lang;

        $this->spacing = 10;
        $this->name = $name;
        $this->WForm($name,$action,$method,$enctype);
        $this->componentes = array();

        if(empty($objClass)) { return  0; }

        $obj = new $objClass;
        $this->objClass = $objClass;

        if(is_string($campos_ausentes)) $campos_ausentes = array($campos_ausentes);
        if(is_string($campos_hidden)) $campos_hidden = array($campos_hidden);

        foreach ($obj->defCampos as $campo=>$campo_def) {
            if (is_array($campos_hidden)) {
                if (in_array($campo,$campos_hidden)) {
                    $widget = new WHidden($campo,"");
                    $widget->setName("frm_$campo");
                    $this->addComponent($campo,$widget);
                    continue;
                };
            }

            if (!in_array($campo,$campos_ausentes)) {

                $widget = $this->getWidgetOfDBField($campo,$campo_def);
                $widget->setName("frm_$campo");

   
                if($campo_def[bNull]==0) {
                    $this->required_forms[] = $campo;
                };

                $this->addComponent($campo,$widget);
            }
        }
        
        $this->requires("smartform.js.php");
        $this->requires("browserSniffer.js");

  
    }


    function forceNotCheckField($remove) {
        foreach($this->required_forms as $k=>$field) {
            if($field==$remove) {
                unset($this->required_forms[$k]);
                return 1;
            }
        }
        
        return 0;
    }

    function forceCheckField($name) {
        $this->required_forms[] = $name;
    }

    
  /** Adiciona os componentes de outro formulario para este smartform 
   *
   */
    function addForm($form) {
        if (!empty($form->componentes)) {
            foreach ($form->componentes as $nome=>$widget) {
                $this->addComponent($nome,$widget);
            }
        }
    }

  /** Muda o design do formlario
   *
   * Muda o modo como sao impressos os labels em relacao com os
   * elementos. A principicio o label pode ser posicionado 
   * na mesma linha (SIDE) ou na linha superiro (OVER)
   *
   * @access public
   * @param int $design pode ser 
   */
    function setDesign($design) {
        $this->design = $design;
    }
    

  /**
   * Retorna o widget de formulario conforme o tipo e tamanho do campo da tabela do bco de dados   
   *
   * @access private
   *
  */
    function getWidgetOfDBField($field,$field_def) {
        $type = $this->getFieldType($field_def);

        switch ($type) {

            case "varchar":
                if($field_def[size]>1) {
                    if ($field_def[size]  < WTEXT_SIZE)
                    $size = $field_def[size];
                    else
                    $size = WTEXT_SIZE;
                    $widget = new WText($field,"",$size,$field_def[size]);
                }
                else {
                    $widget = new WCheckbox($field,"1");
                }
                break;

            case "text":
                $widget = new WTextArea($field,WTEXTAREA_ROWS,WTEXTAREA_COLS);
                break;

            case "int":
                $widget = new WText($field,"",$field_def[size],$field_def[size]);
                break;

            case "float":
                $widget = new WText($field,"",WFLOATSIZE,WFLOATSIZE);
                break;

            case "blob":
                $widget = new WFile($field);
                break;


        }

        return $widget;

    }

  /**
   * Ordena o array de widgets conforme definido pelo usuario
   * $ordem eh um array dos nomes dos campos em ordem que deverao ser exibidos seus widgets
  */
    function setWidgetOrder($ordem) {
        $comps = array();

        foreach ($ordem as $campo) {
            $comps[$campo] = $this->componentes[$campo];
        }

        foreach($this->componentes as $campo=>$comp) {
            if(get_class($comp)=="whidden")  $comps[$campo] = &$comp;
        }

        $this->componentes = &$comps;
    }


  /**
   * Normaliza os diversos nomes de tipos de campos em alguns tipos basicos
   * 
   * @param string $field_def Definicao do tipo do campo obtida do banco de dados
   * @return string Tipo do campo Normalizado
  */
    function getFieldType($field_def) {
        $tipo_campo = strtolower($field_def[type]);

        switch ($tipo_campo) {

            case "small":
            case "mediumint":
            case "int":
            case "bigint":
                return "int";
                break;

            case "float":
            case "double":
                return "float";
                break;

            case "tinytext":
            case "mediumtext":
            case "longtext":
            case "text":
                return "text";
                break;

            case "blob":
            case "tinyblob":
            case "mediumblob":
            case "longblob":
                return "blob";
                break;

            case "varchar":
            case "char":
                return "varchar";
                break;


            default:
                return "varchar";
    
        }


    }


  /**
   * Define a estrutura de apresentacao do formulario
   *
   * Definie a estrutura de apresentacao do formulario
   * Cols eh um array em que cada elemento do array eh o numero de colunas para aquela linha da tabela
   *
   * @param integer $cols Numero de colunas nas qual o objeto deve ser apresentado 
  */
    function setStructure($cols) {
        $this->rows = array();
        if(is_array($cols)) {
            foreach ($cols as $col) {
                $this->rows[] = $col;
            }
        }
        else {
            $this->rows[] = $cols;
        }
    }

  /**
   * Define a estrutura de apresentacao do formulario
   *
   * Definie a estrutura de apresentacao do formulario
   * Cols eh um array em que cada elemento do array eh o numero de colunas para aquela linha da tabela
   *
   * @param integer $cols Numero de colunas nas qual o objeto deve ser apresentado 
  */
    function loadDataFromObject($obj) {

        $el = $obj->toArray();

        foreach($el as $campo=>$item) {
            if(!empty($this->componentes[$campo])) {
                $this->componentes[$campo]->setValue($item);
            }
        };

    }


  /**
   *Adiciona um componente
  */
    
    function addComponent($nome,$widget) {
        global $lang;

        $this->componentes[$nome] = $widget;
        $this->componentes[$nome]->formName = $this->name;
        $this->componentes[$nome]->nome = "frm_$nome";

        $w = &$this->componentes[$nome];

        if(isset($lang["frm_$nome"])) {
            $w->setLabel($lang["frm_$nome"]);
        };

        $str = "frm_".$nome."_desc";
        if(!empty($lang[$str])) {
            $tip = new WTip($lang["frm_".$nome."_desc"]);
            $w->tip = $tip;
        }


    }


  /**
   *  Transforma um campo num hidden.
   *
   * @param string $field  Nome do campo a ser alterado
   * @param string $value  Valor da variavel hidden
  */  
    function setHidden($field,$value) {
        $data = new WHidden("frm_$field",$value);
        $data->formName = $this->name;

        $this->componentes[$field] = $data;
    }

  /**
   *  Transforma um campo numa wdate.
   *
   * @param string $field  Nome do campo a ser alterado
   * @param string $formato Formato de como os campos devem serem exibidos. Segue o padrao do comando date() do PHP.
  */  
    function setDate($field,$formato,$calendar=0) {
        $data = new WData("frm_$field","",$formato);
        $data->formName = $this->name;
        if($calendar) $data->setCalendarOn();
        $label = $this->componentes[$field]->label;
        $value = $this->componentes[$field]->prop[value];
        $this->componentes[$field] = $data;
        $this->componentes[$field]->addLabel($label);
        $this->componentes[$field]->setValue($value);
    }


  /**
   *  Transforma um campo num select, onde as opcoes podem ser passadas como parametro ou o objeto lista no qual ira
tirar os dados
   *
   * @param @mixed $options Array do tipo $options[][value][label]
  */  
    function setSelect($field,$options,$index="",$list="") {
        $name = $this->componentes[$field]->nome;
        $label = $this->componentes[$field]->label;
        $value = $this->componentes[$field]->prop[value];

        $this->componentes[$field] = new WSelect($field);
   
        if(is_subclass_of($options,"rdcursor") || is_a($options,"rdcursor")) {
             
            if(!empty($options->records)) {
                foreach($options->records as $op) {
	  //$op_array = $op->toArray();
	  //$this->componentes[$field]->addOption($op_array[$index],$op_array[$list]);
                    $this->componentes[$field]->addOption($op->$index,$op->$list);
                }
            }
        }
        else {
            foreach ($options as $value=>$rotulo) {
                $this->componentes[$field]->addOption($value,$rotulo);
            };
        };

        $this->componentes[$field]->setName($name);
        $this->componentes[$field]->prop[value] = $value;
        $this->componentes[$field]->label =  $label;
    }
    

  /**
   *  Transforma um campo em um WRadioGroup
   *
   * @param @mixed $options Array do tipo $options[][value][label] ou um RDLista
  */  
    function setRadioGroup($field,$options,$index="",$list="") {

        $name = $this->componentes[$field]->nome;
        $label = $this->componentes[$field]->label;

        $value = $this->componentes[$field]->prop[value];

        $this->componentes[$field] = new WRadioGroup($name);


        if(is_a($options,"RDLista")) {
            if(!empty($options->records)) {
                foreach($options->records as $op) {
                    $op_array = $op->toArray();
                    $this->componentes[$field]->addOption($op_array[$index],$op_array[$list]);
                }
            }
        }
        else {
            foreach ($options as $value=>$labelOp) {
                $this->componentes[$field]->addOption($value,$labelOp);
            };
        };

        $this->componentes[$field]->setName($name);
        $this->componentes[$field]->prop[value] = $value;
        $this->componentes[$field]->label =  $label;

    }

  /** Carrega os labels a partir de um array
   *  
   *  @param array $labels : Array de labels no formato array[nomeDoCampo] = $label
   */ 

    function loadLabels($labels) {
        foreach ($this->componentes as $field=>$w) {
            $this->componentes[$field]->addLabel($labels[$field]);
        }
    }

  /** Não imprime o botão de cancela no form
   *
   * O botão de envia e cancela são por padrão 
   * adicionados ao form. Quando envocada essa
   * funão suprime o botão de cancela.
   */
    function setCancelOff() {
        $this->cancelButtonOff = 1;
    }


  /** Força um determinado campo a ser tornar um wtext
   *
   * Essa função é útil principalmente quando o smartform identifica
   * um campo como wtextarea, mas devido a falta de espaço deseja-se
   * utiliza um input text.
   *
   * @param string $field Nome do campo que deseja-se forçar
   * @param int $size Tamanho do Input Text
   * @param int $maxSize Nűmero máximo de caracteres permitidos dentro do form
   * @see WText
  */
    function forceToText($field,$size,$maxsize) {
        $old_w = $this->componentes[$field];

        if(empty($old_w)) return 0;

        $widget = new WText($old_w->nome,$old_w->value,$size,$maxsize);
        $widget->label =$old_w->label;
        $this->componentes[$field] = $widget;
    }

  /**  Seta a url que sera carregada se o usario clicar em cancel
   *
   *  @param string $url
   *
   */

    function setCancelUrl($url) {
        $this->urlOnCancel = $url;
    }


  /** Configura o tamanho do espa�amento entre as c�lulas da tabela do smartform
   *
   * @param integer $spc Par�metro a ser passados para a tabela.
   */
    function setSpacing($spc) {
        $this->spacing = $spc;
    }


  /**
   * Altera a classe css padr�o para ser utilizada no label
   *
   * @param string $class Nome da classe CSS.
   */
    function setLabelClass($class) {
        $this->labelclass = $class;
    }


    function setDesignString($str,$no_iterative=0) {
        $this->design_string=$str;
        $this->design_string_iterative=!$no_iterative;
    }


  /**
   * Chamada recursiva que imprime o resultado da pagina no objeto de mais alto nÃ­vel
   *
   */
    function imprime() {
        global $smartform,$lang;

        $this->add("<!- Inicio do SmartForm >");

        $this->add("<!- Inicio dos campos hidden>");
        foreach ($this->componentes as $k=>$form_el) {
            if(strtolower(get_class($form_el))=="whidden") {
                $this->add($form_el);
            };
        };
        $this->add("<!- Fim dos campos hidden>");

        $this->add("<TABLE CELLPADDING=\"$this->spacing\" CELLSPACING=\"$this->spacing\"><TR>");
        $row = 0;    //contador das linhas
        $col = 1;    //contador das colunas
        if(empty($this->submit_label))
        $this->submit_label = "Envia";


        $group = new WFormElGroup();
        $group->nome = "submit_buttons";
        $env = new WButton("submit",$this->submit_label);

        if(!empty($this->required_forms)) {
            $env = new WButton("submit2",$this->submit_label,"button");
            $str = "";
            $str2 = "";
            foreach($this->required_forms as $field) {
                $w = $this->componentes[$field];
                if(!empty($w)) {
                    if(!empty($str)) $str.=",";
                    if(!empty($str2)) $str2.=",";
                    $str.="'$w->nome'";
                    $str2.="'$w->label'";
                };
            }

            if(!empty($str)) {
                $env->setOnClick("if(formCheck(this.form,Array($str),Array($str2))) { if(document.$this->name.onsubmit!='undefined') document.$this->name.onsubmit();document.$this->name.submit(); }");

            };
        };


        $group->add($env);

        if(!$this->cancelButtonOff)  {
            $cancel_button = new WButton("cancelar","Cancelar","button");

      //se nao tiver sido setada a url que devera ir caso cancelar
      //entao vai para $_SERVER[PHP_SELF]

            if (empty($this->urlOnCancel)) {
                $cancel_button->setOnClick("window.location.href = '".$_SERVER[PHP_SELF]."'");
            }
            else {
                $cancel_button->setOnClick("window.location.href = '".$this->urlOnCancel."'");
            }
            $group->add($cancel_button);

        }

        if(!empty($this->submitgroup_class))
        $group->setClass($this->submitgroup_class);

        if(!empty($this->submitgroup_align))
        $group->setAlign($this->submitgroup_align);

        $this->componentes[submit_group] = $group;

        $taborder=0;
        foreach ($this->componentes as $form_el) {
            
            if(strtolower(get_class($form_el))=="whidden") {
                continue;
            }

            if(get_class($form_el)=="wfile") {
                $this->enctype = "multipart/form-data";
            }


            if($this->design!=WFORMEL_DESIGN_STRING_DEFINED) {

                if ($col > $this->rows[$row]) {
                    $col = 1;
                    $row++;
                    $this->add("</TD></TR><TR>");
                }
                if ($col!= 1)
                $this->add("</TD>");

                $this->add("<TD class=\"$this->labelclass\">");

                $form_el->design = $this->design;
                $this->add($form_el);

                if(!empty($form_el->tip)) {
                    $this->add("&nbsp;");
                    $this->add($form_el->tip);
                }

                
            } else {

                $form_el->design = $this->design;

                if($this->design_string_iterative) {
                    $el= @ereg_replace("{LABEL}",$form_el->label,$this->design_string);
                    $el= @ereg_replace("{FORM_EL}",$form_el->toString(),$el);
                    if(!empty($form_el->tip)) {
                        $el= @ereg_replace("{TIP}",$form_el->tip->toString(),$el);
                    }
                }
                else {
                    $name = strtoupper($form_el->nome);
                    $this->design_string = @ereg_replace("{LABEL_$name}",$form_el->label,$this->design_string);
                    $this->design_string = ereg_replace("{FORM_EL_$name}",$form_el->toString(),$this->design_string);
                    if(!empty($form_el->tip)) {
                        $this->design_string = @ereg_replace("{TIP_$name}",$form_el->tip->toString(),$this->design_string);
                    }


                }

                parent::add($el);
            }

            $col++;
        }

        if($this->design!=WFORMEL_DESIGN_STRING_DEFINED) {
            $this->add("</TD></TR>");
        } else {
            if($this->design_string_iterative==0)
            parent::add($this->design_string);
        }
        $this->add("</TABLE>");
        $this->add("<!- Fim do SmartForm >");


    //faz um parse dos campos que sÃ£o marcados como not null
    //e inscreve eles na funÃ§Ã£o javascript que sÃ³ vai permitir o envio
    // de forms completos
         
        if(!empty($this->required_forms)) {
            $str = "";
            $str2 = "";
            foreach($this->required_forms as $field) {
                $w = $this->componentes[$field];
                if(!empty($w)) {
                    if(!empty($str)) $str.=",";
                    if(!empty($str2)) $str2.=",";
                    $str.="'$w->nome'";
                    $str2.="'$w->label'";
                };
            }

            if(!empty($str)) {
                $smartform[$this->name][submit_actions][] = "if(formCheck(this,Array($str),Array($str2))==false) return false";
            };
        };

        parent::imprime();

    }



}




?>
