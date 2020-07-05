var body=$(document.getElementsByTagName("body")[0]);
var RPLoader=false;
var eventFade;

function init()
{
	if($("persoId"))
	{
		$("persoId").addEvent("change",function(){$("changePerso").submit();});
		new selectList($("persoId"),"absolute");
	}
	Nifty("#menu a,#menu strong,#menu form","right small transparent");
	Nifty("#anim,#perso,#lire,#accountAff","left");
	Nifty("#event,#console,#mod,#accountTransfuge","right");
	Nifty("#middle h2,div.weapon h2,div.gadget h2","top");
	showMenuLeft();
	if($("matosFrame")){
		initMatos();
	}
	if($("missionFrame")){
		initMissions();
	}
	if($("rp_id")){
		$("rp_id").addEvent("change",loadRP);
	}
	if($("inscription")){
		initInsc();
	}
	eventFade=new Fx.Style($("events"),"opacity",{duration:200,transition: Fx.Transitions.quadOut});
	current=$("closeEvents");
	if(current){
		current.addEvent("click",function(){eventFade.start(1,0);});
		current.removeAttribute("href");
		current.setStyle("cursor","pointer");
	}
}

window.addEvent('domready', init);

function showMenuLeft(){
	if(Cookie.get("showMenuLeft") == "1"){
		return;
	}
	var timer = 0;
	var menu = $$('#menu li');
	var slidefxs = [];
	var colorfxs = [];

	for(var i=0; i<menu.length;i++){
		menu[i].setStyle('margin-left', '-150px');
		timer += 150;
		slidefxs[i] = new Fx.Style(menu[i], 'margin-left', {
			duration: 400,
			transition: Fx.Transitions.backOut,
			wait: false
		});
		slidefxs[i].start.delay(timer, slidefxs[i], 0);
	}
	Cookie.set("showMenuLeft", "1", {duration: 0.1});
}

/*=============================================================================
Gestion du matos.
=============================================================================*/

var currentList="";
var currentArmorType="ALE";
var currentWeaponType=0;
var currentWeaponType2=7;
var chosenArmorType="NOPE";
var chosenWeaponType1=-1;
var chosenWeaponType2=-1;
var chosenGadgetPos1=-1;
var chosenGadgetPos2=-1;
var chosenGadgetPos3=-1;
var currentArmor=0;
var currentWeapon1=0;
var currentWeapon2=0;
var currentGad1=0;
var currentGad2=0;
var currentGad3=0;

var optionGad1=null;
var optionGad2=null;
var optionGad3=null;
// ITAC - LD - 2010-01-24
// ITAC - LD - BEGIN
// http://dandoy.fr/mantis/view.php?id=9
var maxPoids=0;
var poidsArme1=0;
var poidsArme2=0;
var curPoids=0;
// ITAC - LD - END

function initMatos(){
	var j;
	$("armeP").addEvent('click',focus);
	$("armure").addEvent('click',focus);
	$("armeS").addEvent('click',focus);
	$("gad1").addEvent('click',focus);
	$("gad2").addEvent('click',focus);
	$("gad3").addEvent('click',focus);

	$$(".weapon h2,#middle h2,.gadget h2").addEvent('click',focusNext);
	/* Choix d'armure */
	$("ALE").setStyle("display","none");
	$("AMO").setStyle("display","none");
	$("ALO").setStyle("display","none");
	$(currentArmorType).setStyle("display","block");
	$("selectArmure").addEvent('change',selectArmureType);

	var options =  $$("#ALE option, #AMO option, #ALO option");
	options.addEvent("mouseover",toggleArmor);
	options.addEvent("mouseout",toggleArmor); 
	options.addEvent("click",chooseArmor); 
	if(currentArmor != 0){
		showChosenArmor(currentArmor);
	}

	/* Choix armes */
	$("selectArmeP").addEvent('change',selectArmeType);
	$("selectArmeS").addEvent('change',selectArmeType);

	for(var i=0; i<=9; i++){
		if(i != currentWeaponType && i != currentWeaponType2){
			$("voir_armes_"+i).setStyle("display","none");
		}
		options=$$("#voir_armes_"+i+" option");
		options.addEvent("mouseover",toggleWeapon);
		options.addEvent("mouseout",toggleWeapon);
		if(i < 7){
			options.addEvent("click",chooseWeapon1);
		}
		else{
			options.addEvent("click",chooseWeapon2);
		}
	}

	if(currentWeapon1 != 0){
		showChosenWeapon1(currentWeapon1);
	}
	if(currentWeapon2 != 0){
		showChosenWeapon2(currentWeapon2);
	}

	/* Choix gadget */
	for(i = 1; i <= 3; i++){
		options=$$("#gad" + i + "Liste option");
		options.addEvent("mouseover",toggleGadget);
		options.addEvent("mouseout",toggleGadget);
		if(i == 1){
			options.addEvent("click",chooseGadget1);
			if(currentGad1 != 0){
				for(j = 0; j < options.length; j ++){
					if(options[j].getProperty("value") == currentGad1){
						optionGad1 = options[j];
					}
				}
			}
		}
		if(i == 2){
			options.addEvent("click",chooseGadget2);
			if(currentGad2 != 0){
				for(j = 0; j < options.length; j ++){
					if(options[j].getProperty("value") == currentGad2){
						optionGad2 = options[j];
					}
				}
			}
		}
		if(i == 3){
			options.addEvent("click",chooseGadget3);
			if(currentGad3 != 0){
				for(j = 0; j < options.length; j ++){
					if(options[j].getProperty("value") == currentGad3){
						optionGad3 = options[j];
					}
				}
			}
		}
	}
	if(currentGad1 != 0){
		showGad1(currentGad1);
	}
	if(currentGad2 != 0){
		showGad2(currentGad2);
	}
	if(currentGad3 != 0){
		showGad3(currentGad3);
	}

	$("equipement_reset").addEvent("click",razMatos);
	verifDispoArmes();
}

function chooseArmor(e){
	e = new Event(e);
	showChosenArmor(e.target.getProperty("value"));
}

function showChosen(id,into){
	if(!$(id) || !$(id).innerHTML){
		return;
	}
	var html = $(id).innerHTML.replace(/<p class="titleBar">/gi,"<h3>").replace(/<\/p>/gi,"</h3>");
	$(into).setHTML(html);
}
function showChosenArmor(armorId){
	var id = "infos_A" + armorId;
	showChosen(id,"armure");
	chosenArmorType = currentArmorType;
	$("armor").value = armorId;
	// ITAC - LD - 2010-01-24
	// ITAC - LD - BEGIN
	// http://dandoy.fr/mantis/view.php?id=9
	if(this.document.getElementById(id))
	{
		var poids = /<dd>([0-9]+)\s*kg<\/dd>/ig.exec(this.document.getElementById(id).innerHTML);
		if (poids)
		{
			maxPoids=Number(poids[1]);
		}
	}
	// ITAC - LD - END
	verifDispoArmes();
	// ITAC - LD - 2010-01-24
	// ITAC - LD - BEGIN
	// http://dandoy.fr/mantis/view.php?id=9
	this.document.getElementById("poidsTransportable").innerHTML="Poids: " + curPoids + "Kg";
	// ITAC - LD - END
}

function chooseWeapon1(e){
	e = new Event(e);
	var html;
	if(e.target.getProperty("disabled")){
		return;
	}
	if(currentWeapon1 == e.target.getProperty("value")){
		return;
	}
	showChosenWeapon1(e.target.getProperty("value"));
}

function showChosenWeapon1(weaponId){
	var id = "infos_arme_" + weaponId;
	showChosen(id,"armeP");
	currentWeapon1 = weaponId;
	chosenWeaponType1 = currentWeaponType;
	$("arme1").value = weaponId;
	// ITAC - LD - 2010-01-24
	// ITAC - LD - BEGIN
	// http://dandoy.fr/mantis/view.php?id=9
	if(this.document.getElementById(id))
	{
		var tpoidsArme1 = /<dd class="poidsarme1">([0-9]+)\s*<\/dd>/ig.exec(this.document.getElementById(id).innerHTML);
		if (tpoidsArme1)
		{
			poidsArme1 = tpoidsArme1[1];
			curPoids=Number(poidsArme1) + Number(poidsArme2?poidsArme2:0);
			this.document.getElementById("poidsTransportable").innerHTML="Poids: " + curPoids + "Kg";
			if(curPoids > maxPoids)
			{
				this.document.getElementById("poidsTransportable").style.backgroundColor = 'red';
			}
			else
			{
				this.document.getElementById("poidsTransportable").style.backgroundColor = 'inherit';
			}
		}
	}
	// ITAC - LD - END
	verifDispoGadgets();
}

function chooseWeapon2(e){
	e = new Event(e);
	var html;
	if(e.target.getProperty("disabled")){
		return;
	}
	if(currentWeapon2 == e.target.getProperty("value")){
		return;
	}
	showChosenWeapon2(e.target.getProperty("value"));
}

function showChosenWeapon2(weaponId){
	var id = "infos_arme_" + weaponId;
	showChosen(id,"armeS");
	currentWeapon2 = weaponId;
	chosenWeaponType2 = currentWeaponType;
	$("arme2").value = weaponId;
	// ITAC - LD - 2010-01-24
	// ITAC - LD - BEGIN
	// http://dandoy.fr/mantis/view.php?id=9
	var tpoidsArme2 = /<dd class="poidsarme1">([0-9]+)\s*<\/dd>/ig.exec(this.document.getElementById(id).innerHTML);
	if (tpoidsArme2)
	{
		poidsArme2 = tpoidsArme2[1];
		curPoids=Number(poidsArme1?poidsArme1:0) + Number(poidsArme2);
		this.document.getElementById("poidsTransportable").innerHTML="Poids: " + curPoids + "Kg";
		if(curPoids > maxPoids)
		{
			this.document.getElementById("poidsTransportable").style.backgroundColor = 'red';
		}
		else
		{
			this.document.getElementById("poidsTransportable").style.backgroundColor = 'inherit';
		}
	}
	// ITAC - LD - END
	verifDispoGadgets();
}

function chooseGadget1(e){
	e = new Event(e);
	var html;
	if(e.target.getProperty("disabled")){
		return;
	}
	chosenGadgetPos1=e.target.getProperty("label");
	optionGad1 = e.target;
	showGad1(e.target.getProperty("value"));
}

function showGad1(gadId){
	var id = "infos_C" + gadId;
	showChosen(id,"gad1");
	chosenGad1=gadId;
	$("gadget1").value = gadId;
	verifDispoGadgets();
}

function chooseGadget2(e){
	e = new Event(e);
	var html;
	if(e.target.getProperty("disabled")){
		return;
	}
	chosenGadgetPos2=e.target.getProperty("label");
	optionGad2 = e.target;
	showGad2(e.target.getProperty("value"));
}

function showGad2(gadId){
	var id = "infos_C" + gadId;
	showChosen(id,"gad2");
	chosenGad2=gadId;
	$("gadget2").value = gadId;
	verifDispoGadgets();
}

function chooseGadget3(e){
	e = new Event(e);
	var html;
	if(e.target.getProperty("disabled")){
		return;
	}
	chosenGadgetPos3=e.target.getProperty("label");
	optionGad3 = e.target;
	showGad3(e.target.getProperty("value"));
}

function showGad3(gadId){
	var id = "infos_C" + gadId;
	showChosen(id,"gad3");
	chosenGad3=gadId;
	$("gadget3").value = gadId;
	verifDispoGadgets();
}

function toggleWeapon(e){
	e = new Event(e);
	var id = "infos_arme_" + e.target.getProperty("value");
	var y = $("listeMatos").getTop();
	var x = $("listeMatos").getLeft()+200;
	toggleBulle(x,y,id);
}

function toggleArmor(e){
	e = new Event(e);
	var id = "infos_A" + e.target.getProperty("value");
	var y = $("listeMatos").getTop();
	var x = $("listeMatos").getLeft()+200;
	toggleBulle(x,y,id);
}

function toggleGadget(e){
	e = new Event(e);
	var id = "infos_C" + e.target.getProperty("value");
	var y = $("listeMatos").getTop();
	var x = $("listeMatos").getLeft()+200;
	toggleBulle(x,y,id);
}

function verifDispoArmes(){
	var options;
	var j;
	for(var i=0; i<=9; i++){
		options=$$("#voir_armes_"+i+" option");
		for(j=0;j<options.length;j++){
			if(options[j].hasClass(chosenArmorType)){
				enableOption(options[j]);
			}
			else{
				if(options[j].getProperty("value") == currentWeapon1){
					currentWeapon1 = 0;
					chosenWeaponType1=-1;
					clear("armeP");
					options[j].removeAttribute("selected");
					$("arme1").value = 0;
					// ITAC - LD - 2010-01-24
					// ITAC - LD - BEGIN
					// http://dandoy.fr/mantis/view.php?id=9
					curPoids = curPoids - poidsArme1;
					// ITAC - LD - END
				}
				else if(options[j].getProperty("value") == currentWeapon2){
					currentWeapon2 = 0;
					chosenWeaponType2=-1;
					clear("armeS");
					options[j].removeAttribute("selected");
					$("arme2").value = 0;
					// ITAC - LD - 2010-01-24
					// ITAC - LD - BEGIN
					// http://dandoy.fr/mantis/view.php?id=9
					curPoids = curPoids - poidsArme2;
					// ITAC - LD - END
				}
				disableOption(options[j]);
			}
		}
	}
	verifDispoGadgets();
}

function verifDispoGadgets(){
	var options;
	var j;

	/* On verifie d'abord les gadgets deja selectionnes */

	if(optionGad1 &&
		!(optionGad1.hasClass(chosenArmorType) &&
		optionGad1.hasClass("armes_" + chosenWeaponType1) &&
		optionGad1.hasClass("armes_" + chosenWeaponType2))){
			chosenGad1 = 0;
			chosenGadgetPos1 = -1;
			clear("gad1");
			optionGad1.removeAttribute("selected");
			optionGad1=null;
			$("gadget1").value = 0;
		}
		if(optionGad2 &&
			!(optionGad2.hasClass(chosenArmorType) &&
			optionGad2.hasClass("armes_" + chosenWeaponType1) &&
			optionGad2.hasClass("armes_" + chosenWeaponType2))){
				chosenGad2 = 0;
				chosenGadgetPos2 = -1;
				clear("gad2");
				optionGad2.removeAttribute("selected");
				optionGad2=null;
				$("gadget2").value = 0;
			}
			if(optionGad3 &&
				!(optionGad3.hasClass(chosenArmorType) &&
				optionGad3.hasClass("armes_" + chosenWeaponType1) &&
				optionGad3.hasClass("armes_" + chosenWeaponType2))){
					chosenGad3 = 0;
					chosenGadgetPos3 = -1;
					clear("gad3");
					optionGad3.removeAttribute("selected");
					optionGad3=null;
					$("gadget3").value = 0;
				}
				for(var i = 1; i <= 3; i++){
					options=$$("#gad" + i + "Liste option");
					for(j=0;j<options.length;j++){
						if(options[j].hasClass(chosenArmorType) &&
						options[j].hasClass("armes_" + chosenWeaponType1) &&
						options[j].hasClass("armes_" + chosenWeaponType2) &&
						(i == 1 &&
							chosenGadgetPos2 != options[j].getProperty("label") &&
							chosenGadgetPos3 != options[j].getProperty("label") ||
							i == 2 &&
							chosenGadgetPos1 != options[j].getProperty("label") &&
							chosenGadgetPos3 != options[j].getProperty("label") ||
							i == 3 &&
							chosenGadgetPos1 != options[j].getProperty("label") &&
							chosenGadgetPos2 != options[j].getProperty("label"))){
								options[j].removeAttribute("disabled");
								enableOption(options[j]);
							}
							else{
								disableOption(options[j]);
							}
						}
					}
					verifForm();
				}

				function disableOption(elt){
					elt.setProperty("disabled","disabled");
					elt.setStyle("opacity",0.3);
				}
				function enableOption(elt){
					elt.removeProperty("disabled");
					elt.setStyle("opacity",1);
				}

				function verifForm(){
					if($("armor").getValue() != 0 &&
					$("arme1").getValue() != 0 &&
					$("arme2").getValue() != 0 &&
					$("gadget1").getValue() != 0 &&
					$("gadget2").getValue() != 0 &&
					$("gadget3").getValue() != 0){
						$("equipement_ok").setStyle("display","inline");
					}
					else{
						$("equipement_ok").setStyle("display","none");
					}
				}

				function toggleBulle(x,y,id){
					if($(id).getStyle("display") == "block"){
						$(id).setStyle("display","none");
					}
					else{
						$(id).setStyles({display:"block",
						position:"absolute",
						top:y+"px",
						left:x+"px",
						opacity:0.8});
					}
				}

				function selectArmureType(e){
					e = new Event(e);

					var elt=$(e.target.getValue());
					if(elt){
						$(currentArmorType).setStyle("display","none");
						elt.setStyle("display","block");
						currentArmorType=e.target.getValue();
					}
				}

				function selectArmeType(e){
					e = new Event(e);

					var elt=$("voir_armes_"+e.target.getValue());
					if(elt){
						if(e.target.getValue()<7){
							$("voir_armes_"+currentWeaponType).setStyle("display","none");
							currentWeaponType=e.target.getValue();
						}
						else{
							$("voir_armes_"+currentWeaponType2).setStyle("display","none");
							currentWeaponType2=e.target.getValue();
						}
						elt.setStyle("display","block");
					}
				}

				function focusNext(e){
					e = new Event(e);
					showFocus(e.target.getNext());
				}

				function focus(e){
					e = new Event(e);
					var elt;
					elt = e.target;
					while(elt.getTag() != "div" && elt.getTag != "body"){
						elt=elt.getParent();
					}
					if(elt.getTag() == "body"){
						return;
					}
					showFocus(elt);
				}

				function showFocus(elt){
					var i;
					if(!elt.hasClass("active")){
						if(currentList !=""){
							$(currentList).removeClass("active");
							$(currentList).getParent().removeClass("active");
							$(currentList + "Liste").setStyle("display","none");
						}

						elt.addClass("active");
						elt.getParent().addClass("active");
						currentList = elt.id;
						$(currentList + "Liste").setStyle("display","block");
					}
				}


				function clear(what){
					$(what).setHTML("");
				}

				function razMatos(){
					$("armor").value=0;
					$("arme1").value=0;
					$("arme2").value=0;
					$("gadget1").value=0;
					$("gadget2").value=0;
					$("gadget3").value=0;
					clear("armure");
					$(currentArmorType).setStyle("display","none");
					currentArmorType="ALE";
					chosenArmorType="NOPE";
					currentArmor=0;
					curPoids=0;
					verifDispoArmes();
				}

/*
*/

function initMissions(){
	var items = $$("#missionFrame ul li").addEvent('click',selectMission).setStyle("cursor","pointer");
	$$("#missionFrame ul li a").removeProperty("href");
	var div;
	for(var i = 0; i < items.length; i++){
		div = $("info"+items[i].id);
		if(div){
			if(!items[i].hasClass("active")){
				div.setStyle("display","none");
			}
		}
	}
}

function selectMission(e){
	e = new Event(e);
	var target=e.target;
	while(target.getTag()!="li"){
		target=target.getParent();
	}
	if(target.hasClass("active")){
		return;
	}
	var id = $$("#missionFrame ul li.active").removeClass("active");
	for(var i = 0; i < id.length; i++){
		$("info" + id[i].id).setStyle("display","none");
	}
	target.addClass("active");
	$("info" + target.id).setStyle("display","block");
}

function loadRP(e){
	e=new Event(e).stop();
	if(RPLoader){
		RPLoader.cancel();
	}
	var str = e.target.getValue();
	RPLoader = new Ajax('xml/texte.php',
	{/*update:$("debug"),*/
	data:"id="+str,
	evalScripts:true});
	RPLoader.request();
	var toDel=$("liste_perms").getChildren()
	for(var i=0; i< toDel.length; i++){
		toDel[i].remove();
	}
}

function initInsc(){
	if(!$defined(Compas)){
		return;
	}
	$("c").addEvent("change",showCompas);
}

function showCompas(e){
	var e=new Event(e);
	var camp = e.target.getValue();
	if($("l1")){
		$("l1").remove();
	}
	if($("l2")){
		$("l2").remove();
	}
	if($defined(Compas[camp]) && Compas[camp].length > 1){
		for(j=1;j<=2;j++){
			var label = new Element("label").setProperties({"id":"l"+j,"for":"c"+j});
			label.appendText("Groupe : ");
			label.injectAfter($("lg"+j),$("col"+j));
			var select = new Element("select").setProperties({"id":"c"+j,"name":"c"+j});
			label.adopt(select);
			for(i=0;i<Compas[camp].length;i++){
				option=new Element("option").setProperty("value",Compas[camp][i].id);
				option.appendText(Compas[camp][i].nom);
				select.adopt(option);
			}
		}
	}
}

window.addEvent('domready', init);
//window.onload=init();
