var prefixe='mod_';

function LoadXML(page,id,where){
  prefixe=where+'_';
  var xmlhttp=new getHTTPObject();
  // Preparation d'une requete de type POST
  xmlhttp.open("POST", "xml/"+page+".php",true);
  // Faire la requete et envoyer les donnees.
  xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
  xmlhttp.send("id="+id);
}

function getHTTPObject(){
  var xmlhttp = false;
  /* on essaie de creer l'objet si ce n'est pas deja fait */
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined'){
    try{
      xmlhttp = new XMLHttpRequest();
    }
    catch (e){
      xmlhttp = false;
    }
  }
  xmlhttp.onreadystatechange=function(){
    if(xmlhttp.readyState==4){
      if (xmlhttp.status == 200){
	/* 200 : code HTTP pour OK */
	var page=xmlhttp.responseText;
	if(page==false || page=="Erreur"){
	  alert("Erreur lors du chargement de l'objet.");
	}
	else{
	  var Elements=page.split("\n");
	  for(var i=0;i<Elements.length;i++){
	    // Recuperation des donnees du lot.
	    str = "<dt>(.*)</dt><dd>(.*)</dd>";
	    regmatch = new RegExp(str,"i");
	    if(Elements[i].match(regmatch)){
	      champ=RegExp.$1;
	      valeur=RegExp.$2;
	      if(document.getElementById(prefixe+champ))
		document.getElementById(prefixe+champ).value=valeur;
	    }
	    else{
	      // Ce n'est pas une value a changer.
	      str = "<dt class=\"check\">(.*)</dt><dd>(.*)</dd>";
	      regmatch = new RegExp(str,"i");
	      if(Elements[i].match(regmatch)){
		champ=RegExp.$1;
		valeur=RegExp.$2;
		if(document.getElementById(prefixe+champ)){
		  if(valeur!=0){
		    document.getElementById(prefixe+champ).checked="checked";
		  }
		  else
		    document.getElementById(prefixe+champ).checked="";
		}
	      }
	      else{
		// Ni une checkbox.
		str = "<dt class=\"innerHTML\">(.*)</dt><dd>(.*)</dd>";
		regmatch = new RegExp(str,"i");
		if(Elements[i].match(regmatch)){
		  champ=RegExp.$1;
		  valeur=RegExp.$2;
		  if(document.getElementById(prefixe+champ))
		    document.getElementById(prefixe+champ).innerHTML=valeur;
		}
	      }
	    }
	  }
	}
      }
    }
  }
  return xmlhttp;
}

function loadImage(quoi,ou){
  if(!document.getElementById)
    return;
  document.getElementById(ou).src="file://" + quoi.value;
}

function new_PMs(){
  if(!document.getElementById)
    return;
  document.getElementById("new_arme_PMs").value=600/((document.getElementById("new_arme_maxp").value-document.getElementById("new_arme_minp").value)*document.getElementById("new_arme_tirs").value);
}

function mod_PMs(){
  if(!document.getElementById)
    return;
  document.getElementById("mod_arme_PMs").value=600/((document.getElementById("mod_arme_maxp").value-document.getElementById("mod_arme_minp").value)*document.getElementById("mod_arme_tirs").value);
}

function new_autonomie(){
  if(!document.getElementById)
    return;
  document.getElementById("new_arme_autonomie").value=document.getElementById("new_arme_maxi").value/(document.getElementById("new_arme_mun").value*document.getElementById("new_arme_tirs").value);
}

function mod_autonomie(){
  if(!document.getElementById)
    return;
  document.getElementById("mod_arme_autonomie").value=document.getElementById("mod_arme_maxi").value/(document.getElementById("mod_arme_mun").value*document.getElementById("mod_arme_tirs").value);
}

function colorize(laquelle,dou){
  if(!document.getElementById)
    return
  if(!document.getElementsByTagName)
    return
  var couleur="rgb("+document.getElementById(dou + "_camp_R").value+","+document.getElementById(dou + "_camp_V").value+","+document.getElementById(dou + "_camp_B").value+")";
  var spans=document.getElementById(laquelle).getElementsByTagName("span");
  for( var i=0;i<spans.length;i++)
     spans[i].style.background=couleur;
}
