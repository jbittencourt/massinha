
var color_selected = "darkgray";
var color_not_selected = "transparent";

selecionados = new Array();

var is_ie = document.all?1:0; 
var open_menu = null;

function getElement(objName) {
  
  if (document.all) {
    var el = document.all(objName);
  }
  else {
    var el = document.getElementById(objName);
  }
  
  return el;
}

function initSelectable(objName) {
  var el = getElement(objName);
  var evname = ["click"];

  if (is_ie) {
    el.attachEvent("on" + evname, handleMouse);
  } else {
    el.addEventListener(evname, handleMouse, true);
  }

}


function initMenu(objName) {

  var el = getElement(objName);
  var evname = ["contextmenu"];

  if (is_ie) {
    el.attachEvent("on" + evname, popUpMenu);
  } else {
    el.addEventListener(evname, popUpMenu, true);
  }


}


function addItem(item) {
    size = selecionados.length;
    selecionados[size] = item;
    
}

function delItem(item) {
  for (var i=0; i<=selecionados.length;i++) {
    if (selecionados[i]==item) {
      selecionados[i] = "";
    }
  }
}


      
function isSelected(item) {
  for (var i=0; i<=selecionados.length;i++) {
    if (selecionados[i]==item) { 
      return 1;
    }
  }
  return 0;
}



function seleciona(el) {
  if (isSelected(el.id)) {
    el.style.background = color_not_selected;
    delItem(el.id); }
  else {    
    el.style.background = color_selected;
    addItem(el.id);
  } 
}

function force_select(id) {
 
  if(!isSelected(id)) {
     el = getElement(id);
     el.style.background = color_selected;
     addItem(id);
  }
}

function handleMouse(e) {
  var rightclick;


  if (e.currentTarget) { 
    var obj = e.currentTarget;
  }
  else {
    var obj = e.target;
  }
  
  seleciona(obj);
  
}


function  popUpMenu(e) {
  var posx, posy;

  if (e.currentTarget) { 
    var obj = e.currentTarget;
  }
  else {
    var obj = e.target;
  }

  if (e.pageX || e.pageY) {
    posx = e.pageX;
    posy = e.pageY;
  }
  else if (e.clientX || e.clientY) {
    posx = e.clientX + document.body.scrollLeft;
    posy = e.clientY + document.body.scrollTop;
  }

  

  obj = getElement("menu_"+obj.id);
  obj.style.display = "";
  obj.style.top = posy-10;
  obj.style.left= posx-10;
  open_menu = obj;

  evname = "mousedown";
  if (is_ie) {
    document.attachEvent("on" + evname, documentClick);
  } else {
    document.addEventListener(evname,  documentClick, true);
  }
}


function  hideMenu(e) {
  if(open_menu==null) return 0;

  open_menu.style.display = "none";
  open_menu = null;
}


function documentClick(ev) {
  ev || (ev = window.event);
  var el = is_ie ? ev.srcElement : ev.target;
  for (; el != null && el != open_menu; el = el.parentNode);
  if (el == null)
    hideMenu();

}