function addStr(str) {
   input = window.opener.document.envia.nomUserDestino;
   
   if(!(input.value=='')) 
      input.value += ", ";
   input.value += str;


}
