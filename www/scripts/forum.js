function init(){
  if($("persoId")){
    $("persoId").addEvent("change",function(){$("changePerso").submit();});
    new selectList($("persoId"),"absolute");
  }
  Nifty("#menu a,#menu strong,#menu form","right small transparent");
  Nifty("#anim,#perso,#lire,#accountAff","left");
  Nifty("#event,#console,#mod,#accountTransfuge","right");
  Nifty("#middle h2,div.weapon h2,div.gadget h2","top");
  showMenuLeft();
}

window.addEvent('domready', init);
