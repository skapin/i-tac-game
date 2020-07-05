<?php
$slot=1;
if(!empty($_POST['mine2'])){
  $slot=2;
}
if(!empty($_POST['mine3'])){
  $slot=3;
}
if(!($perso['mines_gad'.$slot] > 0 &&
     $perso['munitions_restantes_gad'.$slot] > 0)){
  add_message(4,'Vous n\'avez plus de mine disponible.');
}
else if($perso['PM']<10){
  add_message(4,'Pas assez de mouvement pour poser la mine.');
}
else{
  $mined=false;
  // Chances que ca explose :
  $exp=max(1,min(99,$perso['instabilite_gad'.$slot]-4*$perso['mine']-$perso['bonus_precision']));
  $rand=mt_rand(1,100);
  add_message(4,'&Eacute;chec : '.$rand.'/'.$exp.'<br />');
  if($rand == 1 || $rand <=$exp){
    $mort=degats_mines($perso['degats_mines_gad'.$slot],
		       $perso['pourcent_gad'.$slot],
		       1);
    $mined=true;
  }else{
    // Posage de la mine.
    request('INSERT INTO mines
 (X,Y,map,poseur,degats,pourcent_PV,discretion,instabilite,minage,camouflage,terrain,armee)
VALUES('.$perso['X'].',
'.$perso['Y'].',
'.$perso['map'].',
'.$_SESSION['com_perso'].',
'.$perso['degats_mines_gad'.$slot].',
'.$perso['pourcent_gad'.$slot].',
'.$perso['instabilite_gad'.$slot].',
'.$perso['discretion_gad'.$slot].',
'.$perso['mine'].',
'.$perso['camou'].',
'.$perso[$_SESSION['com_terrain']['competence']].',
'.$perso['armee'].')');
    $id=last_id();
    if(!empty($_POST['pos'.$slot]) && $id){
      request('INSERT INTO minesmarquees
(ID,camp)
VALUES('.$id.','.$perso['armee'].')');
    }
    if($id){
      add_message(4,'Mine pos&eacute;e');
      if(empty($_POST['pos'.$slot])){
	$perte_VS=update_VS($perso,$GLOBALS['perte_grade']/10);
	request('UPDATE persos SET VS='.$perte_VS[0].',
confiance='.$perte_VS[1].',
grade_reel='.$perte_VS[2].'
WHERE ID='.$_SESSION['com_perso']);
      }
      $mined=true;
    }
  }
  $upd='';
  if($mined){
    $str=monte_comp('mine',0.25);
    $upd.=$str[0];
  }
  if($mined && !$mort){
    $perso['munitions_restantes_gad'.$slot]--;
    $upd.=', `mun_g'.$slot.'`=`mun_g'.$slot.'`-1,used_'.$slot.'='.time();
    if($perso['munitions_restantes_gad'.$slot]<=0){
      add_message(5,'parent.destroyClass("formMine'.$slot.'");');
    }
  }
  request('UPDATE persos SET PM=PM-10'.$upd.' WHERE ID='.$_SESSION['com_perso']);
}
?>