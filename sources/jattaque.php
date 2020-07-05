<?php
if(!isset($_POST['targetCase']) || empty($perso['map'])){
  add_message(4,'Donn&eacute;es insuffisantes.');
}
else{
  if(!($perso['tirs_restants_arme'.$slot]>=1 && 
       ($perso['munars_arme'.$slot]>=1 ||
	!$perso['cadence_arme'.$slot]))){
    add_message(4,'Vous ne pouvez pas tirer');
  }
  else{
    $perte_concentration=0;
    //***********************************************************************
    // On verifie la concentration postee.
    //***********************************************************************
    if(!empty($_POST['concentration']) && is_numeric($_POST['concentration']) && $_POST['concentration'] > 0){
      // La concentratino doit etre un multiple de 5.
      $_POST['concentration']=floor($_POST['concentration']/5)*5;
      // On verifie qu'on a assez de PM
      if($perso['PM']>=($_POST['concentration']*80/(($perso['precision_max_arme'.$slot]-$perso['precision_min_arme'.$slot])*$perso['nbr_tirs_arme'.$slot]))){
	// On verifie que ça ne depasse pas precision max.
	if($perso['precision_min_arme'.$slot]+$_POST['concentration']>$perso['precision_max_arme'.$slot]){
	  // Faut pas abuser non plus hein.
	  $_POST['concentration']=floor(($perso['precision_max_arme'.$slot]-$perso['precision_min_arme'.$slot])/5)*5;
	}
	$perte_concentration=($_POST['concentration']*80/(($perso['precision_max_arme'.$slot]-$perso['precision_min_arme'.$slot])*$perso['nbr_tirs_arme'.$slot]));
	$perso['precision_min_arme'.$slot]+=$_POST['concentration'];
      }
    }
    //***********************************************************************
    // On verifie ce qui a ete poste comme augmentation du critique.
    //***********************************************************************
    $perte_critique=0;
    if(!empty($_POST['critique']) && is_numeric($_POST['critique']) && $_POST['critique'] > 0){
      $_POST['critique']=min($_POST['critique'],floor(($perso['PM']-$perte_concentration)/$perso['pm_critique_arme'.$slot]));
      $perte_critique=$_POST['critique']*$perso['pm_critique_arme'.$slot];
    }
    else{
      $_POST['critique']=0;
    }
    //***********************************************************************
    // Recuperation des cibles.
    //***********************************************************************
    $cible[0]=0;
    $where='';
    $dispersion=0;
    $pifometre=0;
    if($perso['type_arme'.$slot]==3){
      // Lance flammes. Va falloir recuperer les cibles potentielles.
      if(isset($_POST['targetMat']) && $_POST['targetMat']){
	$erreur=0;
	switch($_POST['targetMat']){
	case 'NO':
	  $x=-1;
	  $y=1;
	  break;
	case 'N':
	  $x=0;
	  $y=1;
	  break;
	case 'NE':
	  $x=1;
	  $y=1;
	  break;
	case 'O':
	  $x=-1;
	  $y=0;
	  break;
	case 'E':
	  $x=1;
	  $y=0;
	  break;
	case 'SO':
	  $x=-1;
	  $y=-1;
	  break;
	case 'S':
	  $x=0;
	  $y=-1;
	  break;
	case 'SE':
	  $x=1;
	  $y=-1;
	  break;
	default:
	  $erreur=1;
	  break;
	}
	if(!$erreur){
	  for($i=1;$i<=$perso['portee_arme'.$slot];$i++){
	    if($i>1){
	      $where.='OR';
	    }
	    $where.=' (persos.X=\''.($perso['X']+$i*$x).'\' AND persos.Y=\''.($perso['Y']+$i*$y).'\') ';
	  }
	}
      }
    }
    else if($perso['type_arme'.$slot]==4){
      if(empty($GLOBALS['com_map'])){
	// On charge les donnees de la carte.
	include('../sources/map.php');
	$GLOBALS['com_map'] = getMap($perso['map'],
				     $perso['X'],
				     $perso['Y'],
				     $perso['vision'],
				     $perso['type_armure'],
				     $perso['armee']);
      }
      // Lance roquettes. Beaucoup moins drole a gerer.
      $coords=explode('_',$_POST['targetCase']);
      if(is_numeric($coords[0])&&is_numeric($coords[1]) &&
	 isset($GLOBALS['com_map'][$coords[1]][$coords[0]]['cout_portee_total']) &&
	 $GLOBALS['com_map'][$coords[1]][$coords[0]]['cout_portee_total']<=$perso['portee_arme'.$slot]){
	// Bon, la case est bien a portee... voyons si on a bien vise la cible.
	// Tout d'abord, on recup' les infos de la cible.
	$bonus_precision=0;
	$lacible=my_fetch_array('SELECT persos.camouflage,
armures.bonus_precision
FROM persos
 INNER JOIN armures ON persos.armure=armures.ID
 WHERE persos.X='.($perso['X']+$coords[0]).'
 AND persos.Y='.($perso['Y']+$coords[1]).'
AND persos.map='.$perso['map']);
	if($lacible[0]){
	  if(!($lacible[1]['camouflage']>0 &&
	       $lacible[1]['camouflage']<6 &&
	       ceil($perso['vision']*comp('ecl',$lacible[1]['camouflage']-1))<=$GLOBALS['com_map'][$coords[1]][$coords[0]]['cout_vision_total'])){
	    $pifometre=0;
	    $bonus_precision=$lacible[1]['bonus_precision'];
	  }
	  else{
	    $pifometre=1;
	  }
	}
	else{
	  $pifometre=1;
	}
	$rand=mt_rand(1,100);
	$precision=$perso['precision_min_arme'.$slot]+$bonus_precision;
	  //($pifometre?$perso['touche_arme'.$slot]:$perso['precision_min_arme'.$slot])+$bonus_precision;
	if($rand==100 || $rand>$precision*$perso['malus_precision']){
	  $dispersion=ceil(($rand-$precision*$perso['malus_precision'])/20);
	  $pifometre=1;
	}

	$cases=array();
	for($x=(-$dispersion);$x<=$dispersion;$x++){
	  if(abs($x)==$dispersion){
	    for($y=(-$dispersion);$y<=$dispersion;$y++){
	      // La case doit à portée de l'arme.
	      if(isset($GLOBALS['com_map'][$coords[1]+$y][$coords[0]+$x]['cout_portee_total']) &&
		 $GLOBALS['com_map'][$coords[1]+$y][$coords[0]+$x]['cout_portee_total']<=$perso['portee_arme'.$slot])
		$cases[]=array('X'=>$coords[0]+$x,
			       'Y'=>$coords[1]+$y);
	    }
	  }
	  else{
	    // La case doit à portée de l'arme.
	    if($GLOBALS['com_map'][$coords[1]+$dispersion][$coords[0]+$x]['cout_portee_total']<=$perso['portee_arme'.$slot])
	      $cases[]=array('X'=>$coords[0]+$x,
			     'Y'=>$coords[1]+$dispersion);
	    if($GLOBALS['com_map'][$coords[1]-$dispersion][$coords[0]+$x]['cout_portee_total']<=$perso['portee_arme'.$slot])
	      $cases[]=array('X'=>$coords[0]+$x,
			     'Y'=>$coords[1]-$dispersion);
	  }
	}
	$lacase=mt_rand(0,count($cases)-1);
	add_message(4,'Vous avez touch&eacute; la case : '.($cases[$lacase]['X']+($perso['coordonnees']?$perso['X']:0)).'/'.(($perso['coordonnees']?$perso['Y']:0)+$cases[$lacase]['Y']).'<br />');
	$coords[0]=$cases[$lacase]['X'];
	$coords[1]=$cases[$lacase]['Y'];
	for($i=-$perso['rayon_arme'.$slot];$i<=$perso['rayon_arme'.$slot];$i++)
	  for($j=-$perso['rayon_arme'.$slot];$j<=$perso['rayon_arme'.$slot];$j++){
	    if($i>-$perso['rayon_arme'.$slot]||$j>-$perso['rayon_arme'.$slot])
	      $where.='OR';
	    $where.=' (persos.X='.($perso['X']+$cases[$lacase]['X']+$j).' AND persos.Y='.($perso['Y']+$cases[$lacase]['Y']+$i).') ';
	  }
    }
  }
  else if(isset($_POST['targetMat']) && is_numeric($_POST['targetMat'])){
    // Arme "normale".
    $where=' persos.ID='.$_POST['targetMat'];
  }
  if($where)
    {
      // Et maintenant, on récupère la liste des persos pouvant être touchés.
      $cible=my_fetch_array('SELECT persos.ID,
persos.nom,
persos.map, 
persos.X+'.(-$perso['X']).' AS X,
persos.X AS vrai_X,
persos.Y+'.(-$perso['Y']).' AS Y,
persos.Y AS vrai_Y,
persos.PA,
persos.PV, 
(persos.PV_max+5*persos.imp_PV) AS PV_max,
persos.peine_hopital,
persos.grade_reel,
persos.VS,
persos.confiance,
persos.camouflage,
persos.date_last_regen,
persos.date_lost_PV,
persos.date_last_mouv, 
persos.date_last_update,
persos.armee,
persos.imp_regen,
persos.imp_endu,
persos.peine_TRT,
persos.peine_hopital,
persos.peine_debutTRT,
persos.peine_fin,
/* Matos du perso */ 
arme1.ID AS ID_arme1,
arme1.dropable AS dropable_arme1,
arme1.type_munitions AS type_munars_arme1,
persos.munitions_1 AS munars_arme1,

arme2.ID AS ID_arme2,
arme2.dropable AS dropable_arme2,
arme2.type_munitions AS type_munars_arme2,
persos.munitions_2 AS munars_arme2,
 
armures.PA AS PA_max,
armures.malus_critique,
armures.bonus_precision,
terrains.prix_1,
terrains.prix_2,
terrains.prix_4,
terrains.malus_regen,
terrains.debut_perte
FROM persos
   INNER JOIN carte_'.$perso['map'].'
      ON persos.X=carte_'.$perso['map'].'.X
     AND persos.Y=carte_'.$perso['map'].'.Y
   INNER JOIN terrains
      ON carte_'.$perso['map'].'.terrain=terrains.ID
   INNER JOIN armures
      ON persos.armure=armures.ID
   INNER JOIN armes AS arme1
      ON persos.matos_1=arme1.ID
   INNER JOIN armes AS arme2
      ON persos.matos_2=arme2.ID
 WHERE persos.map='.$perso['map'].' AND ('.$where.')');
	if(!$cible[0] && $perso['type_arme'.$slot]!=4 && $perso['type_arme'.$slot]!=3){
	  add_message(4,'Cible introuvable.');
	}
	else{
	  //***************************************************************
	  // On applique les dégats aux cibles.
	  // Puis on retire les munars, PMs et tirs utilisés.
	  //***************************************************************
	  // Sauvegarde des valeurs de camouflage.
	  $ancien_camou=$perso['camouflage'];
	  $update_temp='';
	  // On "applique" le tir en ce qui concerne le camouflage.
	  $malus=$perso['malus_camou_arme'.$slot];
	  if((($time-$perso['date_last_tir'])>$GLOBALS['tour']/2) || !$perso['malus_camou_tir'])
	    $malus_tir=0;
	  else
	    $malus_tir=round($perso['malus_camou_tir']-($time-$perso['date_last_tir'])*$perso['malus_camou_tir']/($GLOBALS['tour']/2));
	  $malus+=$malus_tir;
	  if($perso['camouflage']){
	    $perso['camouflage']=reussite_camou($malus);
	    if((!$perso['camouflage'] || $perso['camouflage']==6)){
	      if($ancien_camou!=6){
		$update_temp.=', date_decamou=\''.(!$perso['camouflage']?0:$time).'\'';
	      }
	    }
	    else{
	      $up=monte_comp('camou',1/$perso['nbr_tirs_arme'.$slot]*0.6);
	      $update_temp.=$up[0];
	    }
	  }
	  $VS=degats($cible,$dispersion,$pifometre);
	  $crit=$VS[2];
	  $update='';
	  if(!$VS[0]){
	    // En fin de compte il n'y a pas eu tir... on retourne aux anciennes valeurs de camou.
	    $perso['camouflage']=$ancien_camou;
	  }
	  else{
	    if(!$cible[0]){
	      if($perso['type_arme'.$slot]==4){
		add_message(4,'Boum.');
	      }
	      else if($perso['type_arme'.$slot]==3){
		add_message(4,'Froush.');
	      }
	    }
	    $VS=update_VS($perso,$VS[1]);
	    $update.=$VS[3].$update_temp;
	    // Si on a perdu notre camouflage, on l'affiche.
	    if($ancien_camou && $ancien_camou!=6 && 
	       (!$perso['camouflage'] || $perso['camouflage']==6)){
	      add_message(4,'Vous avez perdu votre camouflage.<br />');
	    }

	    //***********************************************************
	    // On calcule les augmentations de competences.
	    //***********************************************************
	    $up=monte_comp($_SESSION['com_terrain']['competence'],1/$perso['nbr_tirs_arme'.$slot]*0.2);
	    $update.=$up[0];
	    $up=monte_comp(bdd_arme($perso['type_arme'.$slot]),1/$perso['nbr_tirs_arme'.$slot]);
	    $update.=$up[0];
	    request('UPDATE `persos`
                                   SET `PM`=`PM`-'.($perte_concentration+($crit?$perte_critique:0)).',
                                       `tir_restants`=`tir_restants`-'.round(100/$perso['nbr_tirs_arme'.$slot]).',
                                       `munitions_'.$slot.'`=IF(`munitions_'.$slot.'`<'.$perso['cadence_arme'.$slot].',0,`munitions_'.$slot.'`-'.$perso['cadence_arme'.$slot].'),
                                       `date_last_tir`='.$time.',
                                       `VS`='.$VS[0].',
                                       `confiance`='.$VS[1].',
                                       `grade_reel`='.$VS[2].',
                                       `camouflage`='.$perso['camouflage'].',
                                       `malus_camou_tir`='.$malus.$update.'
                                   WHERE persos.ID='.$_SESSION['com_perso']);
	  }
	}
      }
  }
}

function gainVS($camp,$grade,$reussi,$frag)
{
  global $time,$perso,$slot;
  if(!$perso['mouchard'])
    return 0;
  if($frag)
    $rand=mt_rand(90,110)/100;
  else if($reussi)
    $rand=mt_rand(66,100)/100;
  else
    $rand=mt_rand(66,90)/100;
  if(($camp!=$perso['armee'] &&
      $perso['type_arme'.$slot]!=5 &&
      $perso['type_arme'.$slot]!=8) ||
     ($camp==$perso['armee']&&
      ($perso['type_arme'.$slot]==5 ||
       $perso['type_arme'.$slot]==8))) // Tir sur un ennemi ou soignage d'un pote.
    {
      $VS=($rand/$perso['nbr_tirs_arme'.$slot]*(1500+($grade-$perso['grade_reel'])*50))/($perso['grade_reel']+1);
      // Voyons si le perso vient de transfuger ou est TRT.
      if($perso['transfuge_VS']>1 && $perso['fin_effet']>$time)
	$VS=$VS/$perso['transfuge_VS'];
      if($perso['peine_VS'])
	{
	  if($perso['peine_VS']>=1)
	    $VS*=$perso['peine_VS'];
	  else
	    $VS=0;
	}
      // Perso en position strategique?
      if($perso['defense'])
	{
	  $VS*=0.3;
	}
      return $VS;
    }
  else
    {
      $VS=($rand/$perso['nbr_tirs_arme'.$slot]*(1000-($perso['grade_reel']-$grade)*50));
      return -$VS;
    }
}

//*****************************************************************************
// Degats : fonction qui inflige les degats aux cible et calcule les VS gagnes.
//*****************************************************************************
function degats($cible,$dispersion,$pifometre)
{
  global $perso,$slot,$time,$coords;
  if(empty($GLOBALS['com_map'])){
    // On charge les donnees de la carte.
    include('../sources/map.php');
    $GLOBALS['com_map'] = getMap($perso['map'],
				 $perso['X'],
				 $perso['Y'],
				 $perso['vision'],
				 $perso['type_armure'],
				 $perso['armee']);
  }
  $VS=0;
  $crit=0;
  $fait=0;
  if($perso['type_arme'.$slot]==4 || $perso['type_arme'.$slot]==3){
    $fait=1;
  }

  for($i=1;$i<=$cible[0];$i++){
    $Y=$cible[$i]['Y'];
    $X=$cible[$i]['X'];

    //*************************************************************************
    // On fait regen la cible.
    //*************************************************************************
    $cible['PV']=regen_PV($cible[$i],$cible[$i]['malus_regen'],$cible[$i]['debut_perte']);
    $toAddInUpdate=$cible['PV'][0];
    $cible['PV']=$cible['PV'][2];
    if($cible['PV']==-1){
      add_message(4,'Votre cible est d&eacute;j&agrave; morte.');
      continue;
    }

    //*************************************************************************
    // On verifie si la cible est visible.
    //*************************************************************************
    $visible=1;
    if($cible[$i]['camouflage'] && $cible[$i]['camouflage']<=5){
      $distance=ceil($perso['vision']*comp('ecl',$cible[$i]['camouflage']-1));
      if(!($GLOBALS['com_map'][$Y][$X]['cout_vision_total']<=$distance)){
	$visible=0;
      }
    }
    if(!($GLOBALS['com_map'][$Y][$X]['cout_vision_total']<=$perso['vision'])){
      $visible=0;
    }

    //*************************************************************************
    // Pouvait on augmenter nos chances de critique sur la cible ?
    //*************************************************************************
    if(($perso['type_arme'.$slot]!=4 &&
	$perso['type_arme'.$slot]!=3 ||
	($perso['type_arme'.$slot]==4 &&
	 !$dispersion &&
	 !$pifometre &&
	 $X-$coords[0]==0 &&
	 $Y-$coords[1]==0) ||
	($perso['type_arme'.$slot]==3 &&
	 max(abs($X),abs($Y))==1)) &&
       (round($perso['precision_min_arme'.$slot]+$cible[$i]['bonus_precision']-$GLOBALS['com_map'][$Y][$X]['perte_precision_total'])*$perso['malus_precision'])>=$perso['seuil_critique_arme'.$slot]){
      $crit=1;
    }
      
    //*************************************************************************
    // On verifie si la cible est a portee.
    //*************************************************************************
    $portee=1;
    if($GLOBALS['com_map'][$Y][$X]['cout_portee_total']>$perso['portee_arme'.$slot]){
      $portee=0;
    }

    //***********************************************************************
    // On calcule quelques valeurs qui different entre chaque cible.
    //***********************************************************************
    // On calcule les critiques.
    $critique=max(1,min(99,$perso['critique_arme'.$slot]+($crit?$_POST['critique']:0)-$cible[$i]['malus_critique']));
    // Puis la precision
    // Bonus de precision qui varie en fonction de la cible
    if($perso['type_arme'.$slot]!= 5 &&
       $perso['type_arme'.$slot]!= 8){
      $cible[$i]['bonus_precision']=$cible[$i]['bonus_precision']*max(0,ceil($cible[$i]['PA']*4/$cible[$i]['PA_max']))/4;
    }
    if($perso['type_arme'.$slot]!=4 &&
       $perso['type_arme'.$slot]!=3 ||
       $perso['type_arme'.$slot]==4 &&
       !($dispersion ||
	 $pifometre ||
	 ($X-$coords[0])!=0 ||
	 ($Y-$coords[1])!=0) ||
       $perso['type_arme'.$slot]==3 &&
       abs($X<=1) &&
       abs($Y<=1)){
      $precision=max(1,min(99,($perso['precision_min_arme'.$slot]
			       +$cible[$i]['bonus_precision'])
			   *$perso['malus_precision']
			   -$GLOBALS['com_map'][$Y][$X]['perte_precision_total']));
    }
    else if($perso['type_arme'.$slot]==4){
      // LR qui a devie
      $precision=max(1,min(99,($perso['touche_arme'.$slot]
			       -sqrt(pow($X-$coords[0],2)+pow($Y-$coords[1],2))
			       *$perso['dimit_arme'.$slot]
			       +$cible[$i]['bonus_precision'])));
    }
    else if($perso['type_arme'.$slot]==3){
      // Case suivant la premiere du LF
      $precision=max(1,min(99,($perso['touche_arme'.$slot]
			       -sqrt(pow($X,2)+pow($Y,2))
			       *$perso['dimit_arme'.$slot]
			       +$cible[$i]['bonus_precision'])));
    }
    
    // Les degats maximum.
    $multi_deg=1;
    if($perso['type_arme'.$slot]==4){
      // C'est un LR : plus on est loin de la case touchee,
      // moins on prend cher.
      $multi_deg=1-sqrt(pow($X-$coords[0],2)+pow($Y-$coords[1],2))*$perso['perte_degats_arme'.$slot]/100;
    }
    else if($perso['type_arme'.$slot]==3){
      // C'est un LF : plus on est loin de la premiere case,
      // moins on prend cher.
      $multi_deg=1-(sqrt(pow($X,2)+pow($Y,2))-1)*$perso['perte_degats_arme'.$slot]/100;
    }

    if($perso['munars_arme'.$slot]<$perso['cadence_arme'.$slot]){
      // La rafale est pas complete (manque de munars) donc degats reduits.
      $multi_deg*=$perso['munars_arme'.$slot]/$perso['cadence_arme'.$slot];
    }
    $degats=max(0,$perso['degats_arme'.$slot]*$multi_deg);
    $degats_vie_max=max(0,$perso['degats_vie_arme'.$slot]*$multi_deg);


    //*************************************************************************
    // On verifie si c'est un traitre.
    //*************************************************************************
    if($cible[$i]['armee']==$perso['armee'] &&
       $cible[$i]['peine_TRT'] &&
       $cible[$i]['peine_debutTRT']<=$time &&
       $cible[$i]['peine_fin']<=$time){
      $cible[$i]['armee']++;
    }
      
    //***********************************************************************
    // On applique le tir. (Mecano)
    //***********************************************************************
    if($perso['type_arme'.$slot]==5){
      // Jouons au mecanicien.
      if(!$visible){
	add_message(4,'Vous ne pouvez voir votre cible.');
      }
      else if(!$portee){
	add_message(4,'Votre cible n\'est pas &agrave; port&eacute;e.');
      }
      else if($cible[$i]['PA']>=$cible[$i]['PA_max']){
	// Pas moyen de reparer.
	add_message(4,'L\'amure de votre cible est en parfait &eacute;tat, vous n\'avez rien &agrave; r&eacute;parer.');
      }
      else{
	$degats=mt_rand($precision,100)/100*$degats;
	if($degats+$cible[$i]['PA']>=$cible[$i]['PA_max']){
	  // Armure totalement reparee.
	  add_message(4,floor($cible[$i]['PA_max']-$cible[$i]['PA']).' PAs r&eacute;par&eacute;s. L\'armure de votre client est comme neuve.<br />');
	  request('INSERT INTO `events` (`date`,`tireur`,`cible`,`type`,`PA`,`camoufle`,`mouchard`)VALUES('.$time.','.$_SESSION['com_perso'].', '.$cible[$i]['ID'].',3,'.floor($cible[$i]['PA_max']-$cible[$i]['PA']).','.(($perso['camouflage']>=1 && $perso['camouflage']<=5?1:0)).','.$perso['mouchard'].')');
	  request('UPDATE `persos` SET `PA`='.$cible[$i]['PA_max'].$toAddInUpdate.' WHERE `ID`='.$cible[$i]['ID'].' LIMIT 1');
	}
	else{
	  // Armure partiellement réparée.
	  add_message(4,floor($degats).' PAs r&eacute;par&eacute;s.<br />');
	  request('INSERT INTO `events` (`date`,`tireur`,`cible`,`type`,`PA`,`camoufle`,`mouchard`)VALUES('.$time.','.$_SESSION['com_perso'].','.$cible[$i]['ID'].',3,'.floor($degats).','.($perso['camouflage']>=1 && $perso['camouflage']<=5?1:0).','.$perso['mouchard'].')');
	  request('UPDATE `persos` SET `PA`=`PA`+'.$degats.$toAddInUpdate.' WHERE `ID`='.$cible[$i]['ID'].' LIMIT 1');
	}
	$VS+= gainVS($cible[$i]['armee'],$cible[$i]['grade_reel'],1,0)/$cible[0];
	$fait=1;
      }
    }

    //***********************************************************************
    // Medoc.
    //***********************************************************************
    else if($perso['type_arme'.$slot]==8){
      // On souhaite soigner.
      if(!$visible){
	add_message(4,'Vous ne pouvez voir votre cible.');
      }
      else if(!$portee){
	add_message(4,'Votre cible n\'est pas &agrave; port&eacute;e.');
      }
      else if($cible[$i]['PV']>=$cible[$i]['PV_max']){
	// Cible ful pv
	add_message(4,'Votre cible est en parfaite sant&eacute;.');
      }
      else{
	// Soignons.
	$degats=mt_rand($precision,100)/100*$degats;
	if($degats+$cible[$i]['PV']>=$cible[$i]['PV_max']){
	   // Perso soigne.
	  add_message(4,round($cible[$i]['PV_max']-$cible[$i]['PV']).' PVs soign&eacute;s.<br />');
	  request('INSERT INTO `events`
(`date`,`tireur`,`cible`,`type`,`PV`,`camoufle`,`mouchard`) 
VALUES('.$time.','.$_SESSION['com_perso'].','.$cible[$i]['ID'].',4,'.round($cible[$i]['PV_max']-$cible[$i]['PV']).','.($perso['camouflage']>=1 && $perso['camouflage']<=5?1:0).','.$perso['mouchard'].')');	      
	  request('UPDATE `persos` SET `PV`='.$cible[$i]['PV_max'].', `date_lost_PV`=0,date_last_mouv=0 WHERE `ID`='.$cible[$i]['ID'].' LIMIT 1');
	}
	else{
	  // Perso encore amoche.
	  add_message(4,round($degats).' PVs soign&eacute;s.<br />');
	  request('INSERT INTO `events` (`date`,`tireur`,`cible`,`type`,`PV`,`camoufle`,`mouchard`)VALUES('.$time.','.$_SESSION['com_perso'].','.$cible[$i]['ID'].',4,'.round($degats).','.($perso['camouflage']>=1 && $perso['camouflage']<=5?1:0).','.$perso['mouchard'].')');
	  request('UPDATE `persos` SET PV=PV'.$toAddInUpdate.',`PV`=`PV`+'.$degats.' WHERE `ID`='.$cible[$i]['ID'].' LIMIT 1');
	}
	$VS+=gainVS($cible[$i]['armee'],$cible[$i]['grade_reel'],1,0)/$cible[0];
	$fait=1;
      }
    }

    //***********************************************************************
    // Arme.
    //***********************************************************************
    else{
      if($perso['type_arme'.$slot]!=4&&!$portee){
	add_message(4,'Cible trop &eacute;loign&eacute;e.');
      }
      else{
	$fait=1;
	$rand=mt_rand(1,100);
	if($rand>round($precision)){
	  //***********************************************************
	  // tir loupe.
	  //***********************************************************
	  if(($perso['type_arme'.$slot]==4 || $perso['type_arme'.$slot]==3) &&
	     !$visible){
	    // LR ou LF sur un mec pas visible
	    request('INSERT INTO `events` 
(`date`,`tireur`,`cible`,`type`,`camoufle`,`mouchard`,`X`)
VALUES('.$time.','.$_SESSION['com_perso'].','.$cible[$i]['ID'].',2,'.($perso['camouflage']>=1 && $perso['camouflage']<=5?1:0).','.$perso['mouchard'].',0)');
	    $VS+=gainVS($cible[$i]['armee'],$cible[$i]['grade_reel'],0,0)/$cible[0];
	  }
	  else if($visible){
	    add_message(4,'Tir sur '.bdd2html($cible[$i]['nom']).' : '.$rand.'/'.round($precision).'. Tir loup&eacute;.<br />');
	    request('INSERT INTO `events` 
(`date`,`tireur`,`cible`,`type`,`camoufle`,`mouchard`,`X`)
VALUES('.$time.','.$_SESSION['com_perso'].','.$cible[$i]['ID'].',2,'.($perso['camouflage']>=1 && $perso['camouflage']<=5?1:0).','.$perso['mouchard'].',1)');
	    $VS+=gainVS($cible[$i]['armee'],$cible[$i]['grade_reel'],0,0)/$cible[0];
	  }
	}
	else{
	  //***********************************************************
	  // tir reussi.
	  //***********************************************************
	  add_message(4,'Tir sur '.bdd2html($cible[$i]['nom']).' : '.$rand.'/'.floor($precision).'.<br />');
	  $degats=mt_rand(66,100)/100*$degats;
	  // Calcul des critiques.
	  $degats_vie=0;
	  if($visible){
	    add_message(4,'Critiques :<br />');
	  }
	  for($j=1;$j<=$degats_vie_max;$j++){
	    $polop=mt_rand(1,100);
	    add_message(4,$polop.' / '.$critique);
	    if($polop<=$critique){
	      $degats_vie++;
	      if($visible){
		add_message(4,' : 1 d&eacute;g&agrave;t');
	      }
	    }
	    if($visible)
	      add_message(4,'.<br />');
	  }
	  // Ajout de la difference entre degats et PA restant aux degats 
	  // dans les PV.
	  $diff=max(0,$degats-$cible[$i]['PA']);
	  $degats_vie+=$diff;
	  $degats_PA=max(0,$degats-$diff);
	      
	  //***********************************************************
	  // On applique les degats.
	  //***********************************************************
	  if($cible[$i]['PV']<=$degats_vie){
	    // Cible morte, on va pas se faire chier.
	    mort($cible[$i]);
	    request('INSERT INTO `events` 
(`date`,`tireur`,`cible`,`type`,`PV`,`PA`,`mort`,`camoufle`,`mouchard`)
VALUES('.$time.','.$_SESSION['com_perso'].','.$cible[$i]['ID'].',1,'.floor($cible[$i]['PV']).','.floor($cible[$i]['PA']).',1,'.($perso['camouflage']>=1 && $perso['camouflage']<=5?1:0).','.$perso['mouchard'].')');
	    add_message(4,'Touch&eacute; :'.($cible[$i]['PA']>0?' '.floor($cible[$i]['PA']).' PA':'').' '.floor($cible[$i]['PV']).' PV. Il en est mort.<br />');
	    $VS+=gainVS($cible[$i]['armee'],$cible[$i]['grade_reel'],1,1)/$cible[0];
	  }
	  else{
	    $ajout='';
	    if($degats_vie>0){
	      if(!$cible[$i]['date_lost_PV'])
		$ajout=', `date_lost_PV`='.$time;
	    }
	    request('UPDATE persos
SET PV=PV'.$toAddInUpdate.',`PV`=`PV`-'.$degats_vie.',
                                       `PA`=`PA`-'.$degats_PA.',
                                       `date_last_shot`='.$time.$ajout.'
                                   WHERE `ID`='.$cible[$i]['ID'].'
                                   LIMIT 1');
	    if(($perso['type_arme'.$slot]==4 || 
		$perso['type_arme'.$slot]==3) &&
	       !$visible){
	      // LR ou LF sur un mec pas visible
	      request('INSERT INTO `events` 
(`date`,`tireur`,`cible`,`type`,`PV`,`PA`,`mort`,`camoufle`,`mouchard`,`X`)
VALUES('.$time.','.$_SESSION['com_perso'].','.$cible[$i]['ID'].',1,'.floor($degats_vie).','.floor($degats_PA).',0,'.($perso['camouflage']>=1 && $perso['camouflage']<=5?1:0).','.$perso['mouchard'].',0)');
	    }
	    else{
	      request('INSERT INTO `events` 
(`date`,`tireur`,`cible`,`type`,`PV`,`PA`,`mort`,`camoufle`,`mouchard`,`X`)
VALUES('.$time.','.$_SESSION['com_perso'].','.$cible[$i]['ID'].',1,'.floor($degats_vie).','.floor($degats_PA).',0,'.($perso['camouflage']>=1 && $perso['camouflage']<=5?1:0).','.$perso['mouchard'].',1)');
	      add_message(4,'Touch&eacute; :'.max(0,floor($degats_PA)).' PA '.floor($degats_vie).' PV.<br />');
	    }
	    $VS+=gainVS($cible[$i]['armee'],$cible[$i]['grade_reel'],1,0)/$cible[0];
	  }
	}
      }
    }
  }
  return array($fait,$VS,$crit);
} 
?>