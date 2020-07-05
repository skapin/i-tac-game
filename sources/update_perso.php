<?php
if(isset($perso)){
  $update=regen_PV($perso,($_SESSION['com_terrain']['malus_regen']?$_SESSION['com_terrain']['malus_regen']*comp($_SESSION['com_terrain']['competence'],'regen'):0),$_SESSION['com_terrain']['debut_perte']);
  if($update[2]!=-1){
    if($update[1])
      add_message(2,$update[1]);
    $update=$update[0];
    $gain_tirs=$perso['tir_restants']+100*($time-$perso['date_last_recuptir'])/$GLOBALS['tour'];
    if($gain_tirs>=100){
      // Le perso a tout ses tirs, on va eviter qu'il puisse zerker tout en 
      // lui evitant de perdre du temps.
      $timer=$time-min($GLOBALS['offset'],
		       ($gain_tirs-100)/100*$GLOBALS['tour'],
		       $GLOBALS['tour']/(max($perso['nbr_tirs_arme1'],
					     $perso['nbr_tirs_arme2'])+1));
      $gain_tirs=100;
    }
    else
      $timer=$time;
    $update.=', `tir_restants`='.$gain_tirs.', date_last_recuptir='.$timer;
    if(floor($gain_tirs/100*$perso['nbr_tirs_arme'.$slot])>$perso['tirs_restants_arme'.$slot])
      add_message(2,min($perso['nbr_tirs_arme'.$slot],
			floor($gain_tirs/100*$perso['nbr_tirs_arme'.$slot])
			-$perso['tirs_restants_arme'.$slot]).' tirs récupérés.<br />
 ');
    $pms=($time-$perso['date_last_PM'])*100/$GLOBALS['tour'];
    if($perso['sprints']){
      $sprints=$pms/3*(1+($perso['type_armure']==1?0.05*$perso['imp_endu']:0));
      if($sprints<=$perso['sprints']){
	$update.=', `sprints`=`sprints`-'.$sprints;
	if(floor($perso['sprints'])-floor($perso['sprints']-$sprints))
	  add_message(2,(floor($perso['sprints'])-floor($perso['sprints']-$sprints)).' sprints récupérés.<br />
 ');
	$gains_PM=$perso['PM']+$pms/3;
      }
      else
	{
	  add_message(2,'Sprints totalement récupérés.<br />
');
	  $update.=', `sprints`=0';
	  $gains_PM=$perso['PM']+$pms/3+($sprints-$perso['sprints'])/(1+($perso['type_armure']==1?0.05*$perso['imp_endu']:0))*3;
	}
    }
    else
      $gains_PM=$perso['PM']+$pms;
    if($gains_PM>100){
      // Le perso a tout ses tirs, on va éviter qu'il puisse zerker tout en lui évitant de perdre du temps.
      $timer=$time-min($GLOBALS['offset'],
		       ($gains_PM-100)/100*$GLOBALS['tour']);
      $gains_PM=100;
    }
    else
      $timer=$time;
    $update.=', `PM`='.$gains_PM.', date_last_PM='.$timer;
    if(floor($gains_PM)-floor($perso['PM']))
      add_message(2,(floor($gains_PM)-floor($perso['PM'])).' points de temps régénérés.<br />
 ');
  }
  else
    {
      if($perso['date_last_PM']<($time-$GLOBALS['offset']))
	$update.=', date_last_PM='.($time-$GLOBALS['offset']);
    }
  // Régénération des compétences aprés refonte d'implants.
  foreach($GLOBALS['competences'] as $key=>$value)
    {
      if($perso[$key.'_max']>0)
	{
	  if($perso[$key.'_max']<=$perso[$key.'_reel'])
	    $update.=', '.$key.'_max=0
 ';
	  else
	    {
	      $gain=min($perso[$key.'_max']-$perso[$key.'_reel'],0.05*$perso[$key.'_max']*($time-$perso['date_last_update'])/$GLOBALS['tour']);
	      if($gain==$perso[$key.'_max']-$perso[$key.'_reel'])
		$update.=', '.$key.'_max=0
 ';
	      $update.=', '.$key.'='.$key.'+'.$gain.'
 ';
	    }
	}
    }
    if($perso['peine_fin']&&$perso['peine_fin']<$time)
      {
	// Fin de la peine TRT
	$update.=', peine_assaut=\'0\'
 , peine_pompe=\'0\'
 , peine_snipe=\'0\'
 , peine_mitrailleuse=\'0\'
 , peine_lourde=\'0\'
 , peine_LR=\'0\'
 , peine_lekarz=\'0\'
 , peine_cac=\'0\'
 , peine_biotech=\'0\'
 , peine_pistolet=\'0\'
 , peine_tubes=\'0\'
 , peine_armes=\'0\'
 , peine_munars=\'0\'
 , peine_armures=\'0\'
 , peine_hopital=\'0\'
 , peine_grade=\'0\'
 , peine_reparations=\'0\'
 , peine_TRT=\'0\'
 , peine_forum=\'0\'
 , peine_debutTRT=\'0\'
 , peine_fin=\'0\'';
	add_message(2,'Votre peine est terminée.<br />');
      }
    request('UPDATE persos SET `date_last_update`='.$time.$update.' WHERE ID='.$_SESSION['com_perso']);
  }
?>