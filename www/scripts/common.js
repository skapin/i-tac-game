var activeItemWidth=110;
var inactiveItemWidth=90;
var normalItemWidth=100;
var pulsars;
var phase=true;
var newTabs=new Object;

function initTabs(){
  var menuHaut=$("menuHaut");
  if(!menuHaut){
    return;
  }
  var frames=$$('.framed');
  for(var i=0; i<frames.length; i++){
    frames[i].setStyle('display','none');
  }
  var tabs = menuHaut.getElementsByTagName("a");
  if(tabs.length>0){
    Nifty("#moiperso,#invEquiped,#forumIndex","left");
    Nifty("#moiimplant,#invSol,#forumMembers","right");
  }

  if(menuHaut.hasClass("lite") &&
     parent.$("comlink")){
    var polop=parent.Window.getWidth()-parent.$("menu").getSize().size.x;
    inactiveItemWidth=(polop-140)/tabs.length;
  }
  else{
    inactiveItemWidth=(menuHaut.getSize().size.x-115)/tabs.length;
  }
  activeItemWidth=100 + inactiveItemWidth;
  var j=0;
  // Mise en place des tabs et des frames.
  for(i=0; i< tabs.length; i++){
    if(tabs[i].hasClass('active')){
      tabs[i].setStyle('width',activeItemWidth+'px');
      if($(tabs[i].id+"Frame")){
	$(tabs[i].id+"Frame").setStyle('display','block');
      }
    }else{
      tabs[i].setStyle('width',inactiveItemWidth+'px');
    }

    if(!menuHaut.hasClass("forum")){
      tabs[i].addEvent('click',showFrame);
      tabs[i].removeAttribute("href");
      tabs[i].setStyle('cursor','pointer');
    }
    if(tabs[i].hasClass('new')){
      tabs[i].addEvent('click',function(){pulsar.stop();this.removeClass('new');this.setProperty("opacity",0.7);pulse();});
      $(tabs[i]).setStyle('opacity',0.7);
      newTabs[j]=tabs[i];
      j++;
    }
  }
  newTabs.length=j;
  var elt=$$("#menuHaut a.new");
  if(elt.length>0){
    pulsar=new Fx.Elements(elt,
			   {duration:1000,
			    transition:Fx.Transitions.Sine.easeInOut});
    pulse();
  }
}

window.addEvent('domready', initTabs);


function pulse(){
  var nbrTabs = 0;
  var to=phase?1:0.3;
  var obj={};
  for(var i=0; i<newTabs.length; i++){
    if(newTabs[i].hasClass("new")){
      obj[i]={'opacity':[newTabs[i].getStyle("opacity"),to]};
      nbrTabs++;
    }
  }
  phase=!phase;
  if(nbrTabs > 0){
    pulsar.start(obj).chain(function(){pulse();});
  }
}

function highlightTab(e){
  e = new Event(e).stop();
  var obj = {};
  var tabs = $$('#menuHaut a');
  var w;
  for(var i=0; i<tabs.length; i++){
    w = tabs[i].getSize().size.x;
    if(tabs[i] == e.target){
      obj[i]={'width': [w, activeItemWidth]};
    }else if( tabs[i] != e.target){
      obj[i]={'width': [w, inactiveItemWidth]};
    }
  }
  fx.start(obj);
}

function showFrame(e){
  e = new Event(e).stop();
  var active=$$('#menuHaut .active');
  for(var i=0; i<active.length;i++){
    if($(active[i].id+"Frame")){
      $(active[i].id+"Frame").setStyle('display','none');
    }
    active[i].removeClass("active");
    active[i].setStyle('width',inactiveItemWidth+'px');
    Cookie.remove(active[i].id);
    if($(active[i]).getStyle("opacity")!=0.3){
      active[i].setStyle("opacity",0.3);
    }
  }

  e.target.addClass("active");
  if($(e.target.id+"Frame")){
    $(e.target.id+"Frame").setStyle('display','block');
    Cookie.set(e.target.id,"1");
  }
  e.target.setStyle('width',activeItemWidth+'px');
  if($(e.target).getStyle("opacity")!=0.7){
    e.target.setStyle("opacity",0.7);
  }
}

function backToNormal(){
  var obj = {};
  var tabs = $$('#menuHaut a');
  var w;
  for(var i=0; i<tabs.length; i++){
    w = tabs[i].getSize().size.x;
    if(tabs[i].hasClass('active')){
      obj[i]={'width':[w, activeItemWidth]};
    }else if(!tabs[i].hasClass('active')){
      obj[i]={'width': [w, inactiveItemWidth]};
    }
  }
  fx.start(obj);
}

var selectList = new Class({
  initialize:function(elt,pos){
    this.replaced=$(elt);
    this.input=null;
    this.boxDims={"initialized":false,
		  "shown":0,
		  "height":0,
		  "top":0,
		  "left":0};
    if($defined(this.replaced) &&
       this.replaced.getTag() == "select"){
      // Element qui affichera la valeur choisie de la liste
      this.input=new Element("span").
      setProperties({"class":elt.getProperty("class"),
		     "id":elt.getProperty("id")+"Input"}).
      addClass("select");
      this.replaced.getParent().adopt(this.input);

      // Remplacement de la liste des options
      this.liste=new Element("ul").
      setProperties({"class":"dropDown",
		     "id":elt.getProperty("id")+"dropDown"});
      $(document.getElementsByTagName("body")[0]).adopt(this.liste);
      var children=elt.getChildren();
      for(var i=0; i<children.length;i++){
	if(children[i].getTag() == "optgroup"){
	  this.replaceOptGroup(children[i]);
	}
	else if(children[i].getTag() == "option"){
	  this.replaceOption(children[i]);
	}
      }
      // Disparition de l'ancien select
      this.replaced.setStyle("display","none");
      var coords=this.input.getCoordinates();
      this.liste.setStyles({position:pos,
			    top:coords.top+coords.height,
			    left:coords.left});


      // Ajout d'evenements pour copier le comportement d'un select
      this.input.addEvent("click",this.toggleDropDown.bindWithEvent(this));
    }
    return this.input;
  },
  replaceOptGroup:function(opt){
    var rep = new Element("li");
    rep.addClass("optgroup");
    rep.appendText(opt.getProperty("label"));
    this.liste.adopt(rep);

    var children=opt.getChildren();
    for(var i=0; i<children.length;i++){
      if(children[i].getTag() == "option"){
	this.replaceOption(children[i]);
      }
    }
  },
  replaceOption:function(option){
    var rep = new Element("li");
    rep.addClass("option");
    if(option.getProperty("selected")){
      rep.addClass("selected");
      rep.addClass("current");
      this.input.appendText(option.getText());
      this.current=rep;
      this.selected=rep;
    }
    rep.setProperty("value",option.getProperty("value"));
    rep.appendText(option.getText());
    this.liste.adopt(rep);
    rep.addEvent("mouseenter",this.updateListState.bindWithEvent(this));
    rep.addEvent("click",this.selectListState.bindWithEvent(this));
  },
  toggleDropDown:function(){
    this.liste.toggleClass("shown");
    if(this.boxDims['initialized']){
      var coords = this.boxDims;
    }
    else{
      var coords=this.liste.getCoordinates();
      this.boxDims['initialized']=true;
      this.boxDims['top']=coords["top"];
      this.boxDims['left']=coords["left"];
      this.boxDims['height']=coords["height"];
    }
    // On verifie que rien ne depasse.
    var left=0;
    var top=0;
    if(this.liste.getStyle("position") != "fixed"){
      // Si la bulle est trop a gauche, on la balance a droite.
      left=Math.max(coords['left'],Window.getScrollLeft());
      // Si elle est trop haut, on la rabaisse.
      top=Math.max(coords['top'],Window.getScrollTop());

      // Maintenant, si cela amene la bulle trop a droite, on va la faire
      // revenir a gauche.
      if(Window.getHeight()<coords['height']){
	  coords['height']=Window.getHeight();
      }
      left=Math.min(left,Window.getScrollLeft()+Window.getWidth()-coords['width']);
      top=Math.min(top,Window.getScrollTop()+Window.getHeight()-coords['height']);
    }
    else{
      left=Math.max(coords['left'],0);
      top=Math.max(coords['top'],0);
      if(Window.getHeight()<coords['height']){
	  coords['height']=Window.getHeight();
      }
      left=Math.min(left,Window.getWidth()-coords['width']);
      top=Math.min(top,Window.getHeight()-coords['height']);
    }
    this.liste.setStyle("top",top+"px");
    this.liste.setStyle("left",left+"px");
    this.liste.setStyle("height",coords['height']+"px");
  },
  updateListState:function(e){
    var e=new Event(e);
    if(e.target != this.current){
      this.current.toggleClass("selected");
      e.target.toggleClass("selected");
    }
    this.current=e.target;
  },
  selectListState:function(e){
    var e=new Event(e);
    if(e.target != this.selected){
      this.input.setText(e.target.getText());
      this.replaced.value=e.target.value;
      this.replaced.fireEvent("change");
      this.selected.toggleClass("current");
      e.target.toggleClass("current");
    }
    this.selected=e.target;
    this.liste.removeClass("shown");
  }
});
