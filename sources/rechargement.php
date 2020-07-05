<?php
if(isset($_POST['repare_ok']) &&
   isset($_POST['qg_id']) &&
   is_numeric($_POST['qg_id']) &&
   $_POST['qg_id']){
  // On souhaite reparer notre armure.
  if($perso['PA']<$perso['PA_max'] && 
     !$perso['peine_reparations']){
    $qg=my_fetch_array('SELECT utilisation,
                                 X,
                                 Y,
                                 carte,
                                 reparation,
                                 camp,
                                 `type`
                          FROM qgs WHERE ID='.$_POST['qg_id'].' LIMIT 1');
    if($qg[0] &&
       $qg[1]['reparation'] &&
       sqrt(pow($perso['Y']-$qg[1]['Y'],2)+pow($perso['X']-$qg[1]['X'],2))<=$qg[1]['utilisation'] &&
       ($qg[1]['camp']==$perso['armee']||$qg[1]['type']==1) &&
       $qg[1]['carte']==$perso['map'] &&
       !is_bloque($_POST['qg_id'])){
      // On calcule le gain max possible.
      $PA=min($perso['PA_max']-$perso['PA'],
	      (($time-max($perso['date_last_reparation'],
			  $perso['date_last_shot'],
			  $perso['date_last_bouge']))/$GLOBALS['tour'])*
	      $qg[1]['reparation']);
      if(round($PA)>=1){
	request('UPDATE `persos` SET `PA`=`PA`+'.$PA.', date_last_reparation='.$time.' WHERE persos.ID='.$_SESSION['com_perso']);
	add_message(4,'Armure r&eacute;par&eacute;e de '.(floor($perso['PA']+$PA)-floor($perso['PA'])).' PA.');
      }
    }
  }
  else
    add_message(4,'Vous ne pouvez pas r&eacute;parer votre armure.');
}
else if(isset($_POST['recharge_arme_ok']) &&
	isset($_POST['qg_id']) &&
	is_numeric($_POST['qg_id']) &&
	$_POST['qg_id'] &&
	!$perso['peine_munars']){
  // On refait le plein de bastos.
  if($perso['munars_arme'.$slot]<$perso['munars_max_arme'.$slot] ||
     $perso['munars_arme'.$slot2]<$perso['munars_max_arme'.$slot2]){
    $qg=my_fetch_array('SELECT utilisation,
X,
Y,
carte,
munitions,
camp,
`type`
FROM qgs
WHERE ID='.$_POST['qg_id'].' LIMIT 1');
    if($qg[0] &&
       $qg[1]['munitions'] &&
       sqrt(pow($perso['Y']-$qg[1]['Y'],2)+pow($perso['X']-$qg[1]['X'],2))<=$qg[1]['utilisation'] &&
       ($qg[1]['camp']==$perso['armee']||$qg[1]['type']==1) &&
       $qg[1]['carte']==$perso['map']){
      request('UPDATE `persos` SET `munitions_'.$slot.'`='.$perso['munars_max_arme'.$slot].',`munitions_'.$slot2.'`='.$perso['munars_max_arme'.$slot2].'  WHERE `ID`='.$_SESSION['com_perso']);
      add_message(4,'Armes recharg&eacute;es.');
    }
  }
  else
    add_message(4,'Vous n\'avez pas besoin de recharger.');
}
else if(isset($_POST['recharge_gad_ok']) &&
	isset($_POST['qg_id']) &&
	is_numeric($_POST['qg_id']) &&
	$_POST['qg_id'] &&
	!$perso['peine_munars']){
  // rechargement de gagdets.
  if($perso['munitions_restantes_gad1']<$perso['mines_gad1'] &&
     $perso['date_last_used_gad1']+$GLOBALS['tour']<$time ||
     $perso['munitions_restantes_gad2']<$perso['mines_gad2'] &&
     $perso['date_last_used_gad2']+$GLOBALS['tour']<$time ||
     $perso['munitions_restantes_gad3']<$perso['mines_gad3'] &&
     $perso['date_last_used_gad3']+$GLOBALS['tour']<$time){
    $qg=my_fetch_array('SELECT utilisation,X,Y,carte,munitions,camp,`type` FROM qgs WHERE ID='.$_POST['qg_id'].' LIMIT 1');
    if($qg[0] &&
       $qg[1]['munitions'] &&
       sqrt(pow($perso['Y']-$qg[1]['Y'],2)+pow($perso['X']-$qg[1]['X'],2))<=$qg[1]['utilisation'] &&
       ($qg[1]['camp']==$perso['armee']||$qg[1]['type']==1) &&
       $qg[1]['carte']==$perso['map']){
      $update='';
      $j=0;
      for($i=1;$i<=3;$i++)
	if($perso['munitions_restantes_gad'.$i]<$perso['mines_gad'.$i] &&
	   $perso['date_last_used_gad'.$i]+$GLOBALS['tour']<$time){
	  if($j)
	    $update.=',';
	  else
	    $j++;
	  $update.='`mun_g'.$i.'`=\''.$perso['mines_gad'.$i].'\'';
	}
      if($update){
	request('UPDATE `persos` SET '.$update.' WHERE `ID`='.$_SESSION['com_perso']);
	add_message(4,'Gadgets recharg&eacute;s.');
      }
    }
  }
}
?>