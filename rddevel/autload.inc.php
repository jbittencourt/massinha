<?php


$rdClassDeclaration['rdagenda'] = 'base/rdagenda.inc.php';
$rdClassDeclaration['rdcomentarios'] = 'base/rdcomentarios.inc.php';
$rdClassDeclaration['rdcurso'] = 'base/rdcurso.inc.php';
$rdClassDeclaration['rdferramenta'] = 'base/rdferramenta.inc.php';
$rdClassDeclaration['rdparam'] = 'base/rdparam.inc.php';
$rdClassDeclaration['rdpessoa'] = 'base/rdpessoa.inc.php';
$rdClassDeclaration['rdsessaoambiente'] = 'base/rdsessaoambiente.inc.php';
$rdClassDeclaration['rduser'] = 'base/rduser.inc.php';
$rdClassDeclaration['rdzip'] = 'base/rdzip.inc.php';
$rdClassDeclaration['rdchatsala'] = 'chat/chat_sala.inc.php';
$rdClassDeclaration['rdchatconnection'] = 'chat/rdchatconnection.inc.php';
$rdClassDeclaration['rdchatmensagem'] = 'chat/rdchatmensagem.inc.php';
$rdClassDeclaration['rdchatroom'] = 'chat/rdchatroom.inc.php';
$rdClassDeclaration['rdemail'] = 'email/rdemail.inc.php';
$rdClassDeclaration['rdfemail'] = 'email/rdfemail.inc.php';
$rdClassDeclaration['rdimapmail'] = 'email/rdimapmail.inc.php';
$rdClassDeclaration['rdimapmessage'] = 'email/rdimapmessage.inc.php';
$rdClassDeclaration['rdfinder'] = 'finder/rdfinder.inc.php';
$rdClassDeclaration['rdfinderchat'] = 'finder/rdfinderchat.inc.php';
$rdClassDeclaration['rdfindermensagem'] = 'finder/rdfindermensagem.inc.php';
$rdClassDeclaration['rdforum'] = 'forum/rdforum.inc.php';
$rdClassDeclaration['rdforumimagem'] = 'forum/rdforumimagem.php';
$rdClassDeclaration['rdmensagemforum'] = 'forum/rdmensagemforum.inc.php';
$rdClassDeclaration['rdchattemplate'] = 'interface/rdchattemplate.inc.php';
$rdClassDeclaration['rdflash'] = 'interface/rdflash.inc.php';
$rdClassDeclaration['rdjswindow'] = 'interface/rdjswindow.inc.php';
$rdClassDeclaration['rdpagina'] = 'interface/rdpagina.inc.php';
$rdClassDeclaration['rdpaglista'] = 'interface/rdpaglista.inc.php';
$rdClassDeclaration['rdpagobj'] = 'interface/rdpagobj.inc.php';
$rdClassDeclaration['rdspellcheck'] = 'interface/rdspellcheck.inc.php';
$rdClassDeclaration['rdui'] = 'interface/rdui.inc.php';
$rdClassDeclaration['wimage'] = 'interface/wimage.inc.php';
$rdClassDeclaration['wmime'] = 'interface/wmime.inc.php';
$rdClassDeclaration['wslidinmenu'] = 'interface/wslideinmenu.inc.php';
$rdClassDeclaration['wswapimage'] = 'interface/wswapimage.inc.php';
$rdClassDeclaration['wtemplate'] = 'interface/wtemplate.inc.php';
$rdClassDeclaration['wtreenode'] = 'interface/wtreenode.inc.php';
$rdClassDeclaration['rdhtmlformat'] = 'interface/rdhtmlformat.inc.php';
$rdClassDeclaration['rdambiente'] = 'rdambiente.inc.php';
$rdClassDeclaration['rdcursor'] = 'rdcursor.inc.php';
$rdClassDeclaration['rdlista'] = 'rdlista.inc.php';
$rdClassDeclaration['rdobj'] = 'rdobj.inc.php';
$rdClassDeclaration['rdpaginacao'] = 'rdpaginacao.inc.php';
$rdClassDeclaration['rdrel'] = 'rdrel.inc.php';
$rdClassDeclaration['whtmlarea'] = 'smartform/htmlarea/whtmlarea.inc.php';
$rdClassDeclaration['wbutton'] = 'smartform/wbutton.inc.php';
$rdClassDeclaration['wcalendar'] = 'smartform/wcalendar.inc.php';
$rdClassDeclaration['wcheckbox'] = 'smartform/wcheckbox.inc.php';
$rdClassDeclaration['wdata'] = 'smartform/wdata.inc.php';
$rdClassDeclaration['wfile'] = 'smartform/wfile.inc.php';
$rdClassDeclaration['wform'] = 'smartform/wform.inc.php';
$rdClassDeclaration['wformel'] = 'smartform/wformel.inc.php';
$rdClassDeclaration['wformelgroup'] = 'smartform/wformelgroup.inc.php';
$rdClassDeclaration['whidden'] = 'smartform/whidden.inc.php';
$rdClassDeclaration['wicon'] = 'smartform/wicon.inc.php';
$rdClassDeclaration['winputel'] = 'smartform/winputel.inc.php';
$rdClassDeclaration['wlistadd'] = 'smartform/wlistadd.inc.php';
$rdClassDeclaration['wradio'] = 'smartform/wradio.inc.php';
$rdClassDeclaration['wradiogroup'] = 'smartform/wradiogroup.inc.php';
$rdClassDeclaration['wselect'] = 'smartform/wselect.inc.php';
$rdClassDeclaration['wselectgroup'] = 'smartform/wselectgroup.inc.php';
$rdClassDeclaration['wtext'] = 'smartform/wtext.inc.php';
$rdClassDeclaration['wtextarea'] = 'smartform/wtextarea.inc.php';
$rdClassDeclaration['wtip'] = 'smartform/wtip.inc.php';
$rdClassDeclaration['wsmartform'] = 'smartform/wsmartform.inc.php';
$rdClassDeclaration['rddbupload'] = 'upload/rddbupload.inc.php';
$rdClassDeclaration['rdfupload'] = 'upload/rdfupload.inc.php';
$rdClassDeclaration['rdupload'] = 'upload/rdupload.inc.php';
$rdClassDeclaration['rdimagem'] = 'rdimagem.inc.php';

function __rdautoload($className) {
	global $rdpath, $rdClassDeclaration;

	$file = $rdClassDeclaration[strtolower($className)];
	include($rdpath . "/" . $file);
}

?>