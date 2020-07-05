/*=============================================================================
Variables globales.
/*===========================================================================*/

var mainMenuOpen=true; // Pour savoir si le menu principal est ouvert.
var menuAnimation;     // Objet gerant l'animation du menu.
var menuHeight;
var comlinkAnimation;
var menuHide;
var advancedHide;
var mutexBulle = false;// Permet de savoir si la bulle est bloquee ou non.
var shootForm;         // Formulaire de tir.
var comlinkDims={top:0,left:0,width:0,height:0};
var comlinkShown=false;
var inBulle = false;
var bulleFade;
var eventsFade;
/*=============================================================================
Fonctions lancees pour initialiser la page.
/*===========================================================================*/

//************************************************************************ init

function init(){
  // Centrage sur le perso.
  centerJeu();
  new selectList($("persoId"),"fixed");
  $("persoId").addEvent("change",function(){$("changePerso").submit();});
  var i=0;
  var current=$("menuClose");
  // onclick sur le bouton de fermeture du menu.
  if($defined(current)){
    current.addEvent("click",toggleMenu);
    current.removeAttribute("href");
    current.setStyle("cursor","pointer");
  }

  // onclick sur les liens du plateau de jeu.
  var links = $("vue").getElementsByTagName("a");
  for(i = 0; i < links.length; i++){
    if(links[i].target == "framelink"){
      $(links[i]).addEvent("click",showComLink);
    }
  }

  // onclick sur les liens des bulles d'infos.
  var links = $$("div.infos a");
  for(i = 0; i < links.length; i++){
    $(links[i]).addEvent("click",showComLink);
  }

  // onsubmit sur les formulaires des bulles d'infos qui s'ouvrent dans le
  // comlink
  var links = $$("div.infos form");
  for(i = 0; i < links.length; i++){
    if(links[i].target == "framelink"){
      $(links[i]).addEvent("submit",showComLink);
    }
  }

  current=$("menuContent");
  // Animation du menu.
  if($defined(current)){
    menuAnimation = new Fx.Styles(current,
				  {duration:600,
				   transition: Fx.Transitions.quadOut,
				   onComplete:affectMenuTitle});
    menuHeight=current.getSize().size.y;
    var links = current.getElementsByTagName("a");
    for(i = 0; i < links.length; i++){
      if(links[i].target == "framelink"){
	$(links[i]).addEvent("click",showComLink);
      }
    }
  }

  // Si le menu a comme classe closed, cela veut dire qu'il est rendu invisible
  // par la page histoire de pouvoir chopper sa taille. On le rend donc visible
  // et on passe sa hauteur a 0.
  if($("menuClose").hasClass("closed")){
    current.setStyles({height:0,
		       visibility:"visible"});
    mainMenuOpen=false;
  }

  // Initialisation du CoM Link
  initComLink();

  // Preparation des bulles d'info.
  createBulle();

  // Ajout des evenements sur les cases du plateau de jeu.
  var TDs=document.getElementById("vue").getElementsByTagName("td");
  for(i=0;i<TDs.length;i++){
    if(TDs[i].id){
      $(TDs[i]);
      TDs[i].addEvent("mouseenter",showBulle);
      TDs[i].addEvent("mouseleave",hideBulle);
      TDs[i].addEvent("click",fixBulle);
    }
  }

  // Preparation du formulaire de tir.
  initShootOptions();

  eventFade=new Fx.Style($("events"),"opacity",{duration:200,
						transition: Fx.Transitions.quadOut});
  current=$("closeEvents");
  current.addEvent("click",function(){eventFade.start(1,0);});
  current.removeAttribute("href");
  current.setStyle("cursor","pointer");
}

function showEvents(){
  eventFade.stop();
  eventFade.start($("events").getStyle("opacity"),1);
}

function destroyClass(nom){
  var forms=$$("form."+nom+", div."+nom);
  for(var i = 0; i<forms.length; i++){
    forms[i].remove();
  }
}
//******************************************************************* centerJeu

function centerJeu(){
  // var center=$("0_0");
  // console.log(center);
  // var left=center.getLeft()-(Window.getWidth()-center.getStyle("width").toInt())/2;
  // var top=center.getTop()-(Window.getHeight()-center.getStyle("height").toInt())/2;
  // window.scrollTo(left,top);
}

//************************************************************ initShootOptions

function initShootOptions(){
  if(typeof Cibles != "undefined"){
    // On a des cibles donc on cree le formulaire de tir.
    shootForm=new Element("form");
    shootForm.setProperty("method","post");
    shootForm.setProperty("action","jeuAjax.php");
    shootForm.setProperty("target","action");
    shootForm.setStyle("display","none");
    document.getElementsByTagName("body")[0].appendChild(shootForm);
  }
}

/*=============================================================================
Fonction de gestion de la bulle d'infos.
/*===========================================================================*/

//******************************************************************* showBulle

function showBulle(e){
  if(mutexBulle){
    return;
  }
  e = new Event(e);
  var target = e.target;

  while(target.getTag() != "td" && target.getTag() != "body"){
    target=target.getParent();
  }

  if(!target.id || target.getTag() == "body"){
    return;
  }
  var toCopy= $("infos"+target.id);
  if(!toCopy){
    return;
  }

  if(inBulle){
    inBulle.toggleClass("infos");
    document.getElementsByTagName("body").item(0).adopt(inBulle);
  }

  $("innerInfoBulle").adopt(toCopy);
  toCopy.toggleClass("infos");
  inBulle=toCopy;
  $("bulle").setStyle("display","block");

  var leftBulle = (Math.ceil(e.page.x
			     / target.getSize().size.x+0.5)
		   * target.getSize().size.x)+"px";
  var topBulle = (target.getTop()
		  - $("innerInfoBulle").getSize().size.y/2)+"px";

  bulleFade.stop();
  $("bulle").setStyles({top:topBulle,
			left:leftBulle});
  bulleFade.start($("bulle").getStyle("opacity"),0.8);
}

//******************************************************************* hideBulle

function hideBulle(){
  if(!mutexBulle){
    bulleFade.stop();
    if(typeof shootForm != "undefined"){
      $(shootForm).setStyle("display","none");
    }
    
    bulleFade.start($("bulle").getStyle("opacity"),0);
  }
}

//******************************************************************** fixBulle

function fixBulle(e){
  e = new Event(e);
  var target=e.target;
  if(target.getTag() == "input"){
    return;
  }

  if(mutexBulle){
    mutexBulle=false;
    hideBulle();
  }else{
    showBulle(e);
    mutexBulle=true;
    bulleFade.stop();
    bulleFade.start($("bulle").getStyle("opacity"),1);
    forceBulleInScreen();
    
    // Puis on recupere le titre.
    if(target.getTag() != "td"){
      target=target.parentNode;
    }
    if(!target.id){
      return;
    }
    if(typeof Cibles != "undefined" && 
       typeof Cibles[target.id] != "undefined"){
      showShootForm(target.id);
    }
  }
}

//*************************************************************** showShootForm

function showShootForm(cible){
  $("innerInfoBulle").adopt(shootForm);
  shootForm.setStyle("display","block");
  var matricule = Cibles[cible]["mat"];
  if(type_arme == 2){
    matricule = cible;
  }
  // Formulaire deja cree, on change juste son contenu.
  if(document.getElementById("shootFormDiv")){
    createConcOptions();
    $("targetCase").value=cible;
    $("targetMat").value=matricule;
    $("basePrecision").setHTML(Math.round(Cibles[cible]["precision"]));
    calculPrecision();
    shootForm.setStyle("display","block");
    return;
  }
  // Premiere fois qu'on affiche le formulaire de tir.
  // Creation des elements dans le formulaire.
  var Div=new Element("div");
  Div.setProperty("id","shootFormDiv");
  shootForm.adopt(Div);

  // Titre et infos sur le camouflage.
  var title=new Element("h3");
  title.appendText("Interaction");
  Div.adopt(title);

  if(camouflage > 0){
    var infos=new Element("p");
    infos.appendText("Camouflage : "+camouflage+"%");
    Div.adopt(infos);
  }

  // Concentration.
  var label = new Element("label");
  label.setProperty("for","precision");
  label.setHTML("Pr&eacute;cision : ");
  Div.adopt(label);
  var span = new Element("span");
  span.setProperty("id","basePrecision");
  span.appendText(Math.round(Cibles[cible]["precision"]));

  label.adopt(span);
  label.appendText("% + ");


  var select = new Element("select");
  select.setProperty("id","concentration");
  select.addEvent("change",calculPrecision);
  select.setProperty("name","concentration");
  label.adopt(select);

  label.appendText("=>");
  
  // Affichage du recapitulatif de precision.
  span = new Element("span");
  span.setProperty("id","displayPrecision");
  span.appendText(Math.round(Cibles[cible]["precision"]*malus_precision));

  label.adopt(span);
  label.appendText("%");

  // Les chances de critique
  var label = new Element("label");
  label.setProperty("for","critique");
  label.setProperty("id","critLabel");
  label.appendText("Critique : ");
  Div.adopt(label);
  var span = new Element("span");
  span.setProperty("id","baseCritique");
  span.appendText(Math.round(Cibles[cible]["critique"]));

  label.adopt(span);
  label.appendText("% + ");

  var select = new Element("select");
  select.setProperty("id","critique");
  select.addEvent("change",calculPrecision);
  select.setProperty("name","critique");
  label.adopt(select);

  label.appendText("=>");

  // Affichage du recapitulatif de critique.
  span = new Element("span");
  span.setProperty("id","displayCritique");
  span.appendText(Math.max(1,Math.min(99,Math.round(Cibles[cible]["critique"]))));

  label.adopt(span);
  label.appendText("%");

  // Recapitulatif du cout en PM.
  var displayPM = new Element("p");
  displayPM.innerHTML="Co&ucirc;t en PT : <span id=\"displayPM\">0</span> / "+Math.round(PM);
  Div.adopt(displayPM);

  // Bouton de confirmation du tir.
  var button = new Element("input");
  button.setProperty("type","submit");
  button.setProperty("value",tirName);
//  button.value=tirName;
  displayPM.adopt(button);

  // Inputs de choix de cible.
  var target = new Element("input");
  target.setProperty("type","hidden");
  target.setProperty("id","targetCase");
  target.value=cible;
  Div.adopt(target);
  target.setProperty("name","targetCase");
  
  target = new Element("input");
  target.setProperty("type","hidden");
  target.setProperty("id","targetMat");
  target.setProperty("value",matricule);
  Div.adopt(target);
  target.setProperty("name","targetMat");

  // Enfin, on ajoute les options
  createConcOptions();
}

function createConcOptions(){
  var elt=$("concentration");
  if(!elt){
    return;
  }
  curConcentration=elt.getValue();
  if(!$defined(curConcentration)){
    curConcentration=0;
  }
  else{
    curConcentration=curConcentration.toInt();
  }
  var actPrecision = Math.max(1,Math.min(99,Math.round((curConcentration + Cibles[$("targetCase").getValue()]["precision"])*malus_precision)));
  
  // Nettoyage de tous les children.
  var toDel = elt.getChildren();
  for(var i = 0; i < toDel.length; i++){
    $(toDel[i]).remove();
  }

  // Recuperation de la valeur de critique choisie.
  var crit=$("critique");
  var curCritique=crit.getValue();
  if(!$defined(curCritique) || 
     actPrecision < seuilCrit){
    curCritique=0;
  }
  else{
    curCritique=curCritique.toInt();
  }

  // Creation de toutes les options dans le menu de concentration.
  var concentration = 0;
  var option;

  while(PM-curCritique*prixCrit >= concentration*80/((preciMax-preciMin)*nbTirs) 
	&& preciMin+concentration <= preciMax){
    option = new Element("option");
    option.setProperty("value",concentration);
    option.appendText(concentration+"%");
    elt.adopt(option);
    if(concentration == curConcentration){
      option.setProperty("selected","selected");
    }
    concentration+=5;
  };


  if((actPrecision >= seuilCrit) && type_arme != 4){
    crit.removeProperty("disabled");
    $("critLabel").removeClass("disabled");
  }
  else{
    crit.setProperty("disabled","disabled");
    $("critLabel").addClass("disabled");
  }
  // Nettoyage de tous les children.
  toDel = crit.getChildren();
  for(var i = 0; i < toDel.length; i++){
    $(toDel[i]).remove();
  }
  // Creation de toutes les options dans le menu de concentration.
  var critique=0;
  while(PM-curConcentration*80/((preciMax-preciMin)*nbTirs)>=critique*prixCrit){
    option = new Element("option");
    option.setProperty("value",critique);
    option.appendText(critique+"%");
    $("critique").adopt(option);
    if(critique == curCritique){
      option.setProperty("selected","selected");
    }
    critique++;
  }
}

function forceBulleInScreen(){
  var coords=$("bulle").getCoordinates();
  // Si la bulle est trop a gauche, on la balance a droite.
  var left=Math.max(coords['left'],Window.getScrollLeft());
  // Si elle est trop haut, on la rabaisse.
  var top=Math.max(coords['top'],Window.getScrollTop());
  // Maintenant, si cela amene la bulle trop a droite, on va la faire
  // revenir a gauche.
  left=Math.min(left,Window.getScrollLeft()+Window.getWidth()-coords['width']);
  top=Math.min(top,Window.getScrollTop()+Window.getHeight()-coords['height']);
  $("bulle").setStyle("top",top+"px");
  $("bulle").setStyle("left",left+"px");
}

//***************************************************************** createBulle

function createBulle(){
  var Div=new Element("div");
  Div.setProperty("id","bulle");
  Div.setStyles({position:"absolute",top:0,left:0,zIndex:50,opacity:0});

  var title=new Element("p");
  title.addClass("titleBar");

  var close=new Element("a");
  close.addClass("close");
  close.innerHTML="Fermer";
  close.addEvent("click",function(){mutexBulle=false;hideBulle();});
  title.adopt(close);
  var span = new Element("span");
  span.innerHTML="Infos";
  title.adopt(span);

  Div.adopt(title);

  var innerDiv=new Element("div");
  innerDiv.setProperty("id","innerInfoBulle");
  Div.adopt(innerDiv);

  document.getElementsByTagName("body")[0].appendChild(Div);
  bulleFade = new Fx.Style(Div,'opacity',{duration:200});
}

//************************************************************* calculPrecision
function calculPrecision(){
  var precision = Math.max(1,Math.min(99,Math.round((parseInt($("concentration").getValue()) + Cibles[$("targetCase").getValue()]["precision"])*malus_precision)));
  var critique = 1;
  var coutPM = Math.round($("concentration").getValue()*80/((preciMax-preciMin)*nbTirs));
  if(precision >= seuilCrit){
    critique = Math.max(1,Math.min(99,$("critique").getValue().toInt() + Cibles[$("targetCase").getValue()]["critique"]));
    coutPM+=$("critique").getValue().toInt()*prixCrit;
  }

  $("displayPrecision").innerHTML = precision;
  $("displayCritique").innerHTML = critique;
  $("displayPM").innerHTML = coutPM;
  createConcOptions();
}

//***************************************************************** showMessage
function showMessage(message){
  $("innerEventWindow").innerHTML=message;
}

//****************************************************************** toggleMenu
function toggleMenu(){
  var menu=$("menu");
  if(typeof menu == "undefined"){
    return;
  }
  if(mainMenuOpen){
    // Fermeture du menu.
    menuAnimation.custom({"height":[menuHeight,0]});
  }else{
    // Ouverture.
    menuAnimation.custom({"height":[0,menuHeight]});
  }

  mainMenuOpen=!mainMenuOpen;
  Cookie.set("gameMenuOpen",mainMenuOpen);
}

//************************************************************* affectMenuTitle
function affectMenuTitle(){
  var button=$("menuClose");
  button.toggleClass("closed");
}

//***************************************************************** showComLink
function showComLink(e){
  e = new Event(e);
  var text="";
  var target="";
  if(e.target.getTag() == 'a'){
    text=e.target.innerHTML;
    target=e.target.href;
  }
  else if(e.target.getTag() == 'form'){
    text=e.target.title;
    target=e.target.action;
  }
  $("titleCL").setHTML(text);
  Cookie.set("hrefCL",target);
  Cookie.set("titleCL",text);
  if(!comlinkShown){
    comlinkAnimation.stop();
    comlinkAnimation.start({"width":[0,Window.getWidth()-$("menu").getSize().size.x],
			    "opacity":[$("comlink").getStyle("opacity"),1]});
    comlinkShown=true;
  }
}

//**************************************************************** closeComLink
function closeComLink(){
  comlinkAnimation.stop();
  comlinkAnimation.start({"opacity":[$("comlink").getStyle("opacity"),0]});
  comlinkShown=false;
  Cookie.set("widthCL",'closed');
}

//***************************************************************** initComLink
function initComLink(){
  var menuWidth = $("menu").getStyle("width").toInt();
  var maxWidth = Window.getWidth()-menuWidth;
  console.log($("menu"))
  console.log($("comlink"))
  if($("comlink").getSize().size.x != 0){
    comlinkShown=true;
  }
  $("closeCL").addEvent("click",closeComLink);
  $("closeCL").removeAttribute("href");
  $("closeCL").setStyle("cursor","pointer");
  $("resizeCL").removeAttribute("href");
  $("resizeCL").setStyle("cursor","pointer");

  // Dimensionnement du comlink
  $("comlink").setStyles({height:Window.getHeight()+"px"});
  
  // Ajout de proprietes a la frame du comlink.
  var drag = new Drag.Multi({
    handle:$("resizeCL"),
    onComplete:saveWindow});
  drag.add($("comlink"), {x: {style: "-width",
			      limit:[100,maxWidth]},
			  y: false});
  // Preparation de l'animation
  comlinkAnimation = new Fx.Styles($("comlink"),{duration:600});;
}

//****************************************************************** saveWindow
function saveWindow(){
  var dims = $("comlink").getCoordinates();
  Cookie.set("widthCL",dims["width"]);
  Cookie.set("heightCL",dims["height"]);
}

window.onload=init;
