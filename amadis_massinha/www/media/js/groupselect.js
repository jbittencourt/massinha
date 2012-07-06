

function selectItem(boxname,value) {

   if(ns6) {
     box = document.getElementById(boxname);
   } else {
     box = eval('document.all.'+boxname);
   };

   for(var i=0; i<box.options.length-1;i++) {
     if(box.options[i].value == value) {
        box.selectedIndex = i;
        break;
     }    
   } 

}


function selectClean(boxname) {
   var tam, box;

   if(ns6) {
     box = document.getElementById(boxname);
   } else {
     box = eval('document.all.'+boxname);
   };

   for(var i=0; i<box.options.length-1;i++) {
     box.options[i] = null;
   }  
   box.options.length = 0;
   
}

function selectNewElement(boxname,svalue,slabel) {
   var novo, box;

   if(ns6) {
     box = document.getElementById(boxname);
   } else {
     box = eval('document.all.'+boxname);
   };

   novo = box.options.length;
   box.options[novo] = new Option(slabel,svalue);
   
   return 1;
}

function selectChangeGroup(svalue,iframe_name,urlaction,fieldvalue) {
    var cont,link,name,list,ifr;

    if(ns6) {
      ifr = document.getElementById(iframe_name);
    } else {
      ifr = eval('document.all.'+iframe_name);
    };

    ifr.src = urlaction+'frm_'+fieldvalue+'='+svalue;

    return 1;
}
