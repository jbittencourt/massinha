
function onChangeProcurar() {	
	if (document.enviar.frm_imagem.value != "") {
		attach();
	}
}


function attach() {
	if (document.enviar.frm_imagem.value != "")
	{
	
		document.enviar.acao.value = "atachar";
		document.enviar.submit();
	} 
        else {
           alert('Você deve primeiro escolher uma imagem. Cliquer em "Procurar" ou "Browse".');
        }

}

function remove() {
	document.enviar.acao.value = "desatachar";
	document.enviar.submit();
}

function enviarMen() {
	if (document.enviar.frm_imagem.value == "") {
		if (document.enviar.frm_titulo.value == "" || document.enviar.frm_texto.value == "") {
			alert("Os campos título e mensagem devem estar preenchidos");
		}
		else {		
			document.enviar.acao.value = "preview";
			document.enviar.submit();
		}
	}
	else {
		alert("Você ainda não atachou o seu arquivo. Por favor clique em atachar e depois em enviar novamente!");
	}
}


function cancelaPreview() {
	document.enviar.acao.value = "cancelaPreview";
	document.enviar.submit();	
}
