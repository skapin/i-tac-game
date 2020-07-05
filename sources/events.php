<?php
  // Tir reussi.
$listEvents[1]=array(0=>array('defaut'=>'Il a tir&eacute;$nbr$ sur $cible$',
			      'defautf'=>'Elle a tir&eacute;$nbr$ sur $cible$',
			      'mortdefaut'=>' qui en est mort',
			      'mortdefautf'=>' qui en est morte',
			      'camou'=>'Vous avez tir&eacute;$nbr$ sur $cible$. Vous lui avez fait perdre $PA$ PA et $PV$ PV.',
			      'mortcamou'=>' Il en est mort.',
			      'mortcamouf'=>' Elle en est morte.'),
		     1=>array('defaut'=>'$cible$ lui a tir&eacute;$nbr$ dessus.',
			      'defautf'=>'$cible$ lui a tir&eacute;e$nbr$ dessus.',
			      'mortdefaut'=>' Il en est mort.',
			      'mortdefautf'=>' Elle en est morte.',
			      'camou'=>'$cible$ vous a tir&eacute;$nbr$ dessus. Vous avez perdu $PA$ PA et $PV$ PV.',
			      'camou'=>'$cible$ vous a tir&eacute;$nbr$ dessus. Vous avez perdu $PA$ PA et $PV$ PV.',
			      'mortcamou'=>' Vous en &ecirc;tes mort.',
			      'mortcamouf'=>' Vous en &ecirc;tes morte.'),
		     'buffer'=>1);
// Tir rate.
$listEvents[2]=array(0=>array('defaut'=>'Il a rat&eacute;$nbr$ son tir sur $cible$.',
			      'defautf'=>'Elle a rat&eacute;$nbr$ son tir sur $cible$.',
			      'camou'=>'Vous avez rat&eacute;$nbr$ votre tir sur $cible$.'),
		     1=>array('defaut'=>'$cible$ a rat&eacute;$nbr$ son tir sur lui.',
			      'defautf'=>'$cible$ a rat&eacute;$nbr$ son tir sur elle.',
			      'camou'=>'$cible$ vous a rat&eacute;$nbr$.',
			      'camouf'=>'$cible$ vous a rat&eacute;$nbr$.'),
		     'buffer'=>1);
// Reparations.
$listEvents[3]=array(0=>array('defaut'=>'Il a r&eacute;par&eacute;$nbr$ $cible$.',
			      'defautf'=>'Elle a r&eacute;par&eacute;$nbr$ $cible$.',
			      'camou'=>'Vous avez r&eacute;par&eacute;$nbr$ $cible$. Vous lui avez fait gagner $PA$ PA.'),
		     1=>array('defaut'=>'$cible$ l\'a r&eacute;par&eacute;$nbr$.',
			      'defautf'=>'$cible$ l\'a r&eacute;par&eacute;e$nbr$.',
			      'camou'=>'$cible$ vous a r&eacute;par&eacute;$nbr$. Vous avez gagn&eacute; $PA$ PA.',
			      'camouf'=>'$cible$ vous a r&eacute;par&eacute;$nbr$. Vous avez gagn&eacute; $PA$ PA.'),
		     'buffer'=>3);
// Soins.
$listEvents[4]=array(0=>array('defaut'=>'Il a soign&eacute;$nbr$ $cible$.',
			      'defautf'=>'Elle a soign&eacute;$nbr$ $cible$.',
			      'camou'=>'Vous avez soign&eacute;$nbr$ $cible$. Vous lui avez fait r&eacute;cup&eacute;rer $PV$ PV.'),
		     1=>array('defaut'=>'$cible$ l\'a soign&eacute;$nbr$.',
			      'defautf'=>'$cible$ l\'a soign&eacute;$nbr$.',
			      'camou'=>'$cible$ vous a soign&eacute;$nbr$. Vous avez r&eacute;cup&eacute; $PV$ PV.'),
		     'buffer'=>4);

// Marche sur une mine.
$listEvents[5]=array(0=>array('defaut'=>'Il a march&eacute; sur une mine.',
			      'defautf'=>'Elle a march&eacute; sur une mine.',
			      'mortdefaut'=>' Il en est mort.',
			      'mortdefautf'=>' Elle en est morte.',
			      'camou'=>'Vous avez march&eacute; sur une mine. Vous avez perdu $PA$ PA et $PV$ PV.',
			      'mortcamou'=>' Vous en &ecirc;tes mort.',
			      'mortcamouf'=>' Vous en &ecirc;tes morte.'),
		     1=>array('defaut'=>'',
			      'camou'=>''));
// Blesse en posant une mine.
$listEvents[6]=array(0=>array('defaut'=>'',
			      'camou'=>''),
		     1=>array('defaut'=>'Il a march&eacute; sur une mine.',
			      'defautf'=>'Elle a march&eacute; sur une mine.',
			      'mortdefaut'=>' Il en est mort.',
			      'mortdefautf'=>' Elle en est morte.',
			      'camou'=>'Vous avez fait exploser une mine en essayant de la poser. Vous avez perdu $PA$ PA et $PV$ PV.',
			      'mortcamou'=>' Vous en &ecirc;tes mort.',
			      'mortcamouf'=>' Vous en &ecirc;tes morte.'));
// Gain de grade.
$listEvents[9]=array(0=>array('defaut'=>'',
			      'camou'=>''),
		     1=>array('defaut'=>'Il a &eacute;t&eacute; promu $grade$.',
			      'defautf'=>'Elle a &eacute;t&eacute; promue $grade$.',
			      'camou'=>'Vous avez &eacute;t&eacute; promu $grade$.',
			      'camouf'=>'Vous avez &eacute;t&eacute; promue $grade$.'));
// Perte de grade.
$listEvents[10]=array(0=>array('defaut'=>'',
			      'camou'=>''),
		     1=>array('defaut'=>'Il a &eacute;t&eacute; d&eacute;grad&eacute; au rang de $grade$.',
			      'defautf'=>'Elle a &eacute;t&eacute; d&eacute;grad&eacute;e au rang de $grade$.',
			      'camou'=>'Vous avez &eacute;t&eacute; d&eacute;grad&eacute; au rang de $grade$.'));
// Postulation refusee.
$listEvents[11]=array(0=>array('defaut'=>'',
			      'camou'=>''),
		      1=>array('defaut'=>'',
			       'camou'=>'$cible$ a refus&eacute; votre postulation. Motif :<br />$raison$.'));
// Creation de compagnie refusee.
$listEvents[12]=array(0=>array('defaut'=>'',
			      'camou'=>''),
		      1=>array('defaut'=>'',
			       'camou'=>'$cible$ a refus&eacute; la cr&eacute;ation de votre compagnie. Motif :<br />$raison$.'));
// Nomination comme general en chef.
$listEvents[13]=array(0=>array('defaut'=>'',
			      'camou'=>''),
		      1=>array('defaut'=>'',
			       'camou'=>'$cible$ vous a nomm&eacute; $grade3$.'));
// Nomination comme general
$listEvents[14]=array(0=>array('defaut'=>'',
			      'camou'=>''),
		      1=>array('defaut'=>'',
			       'camou'=>'$cible$ vous a nomm&eacute; $grade2$.'));
// Destitution du grade de general
$listEvents[15]=array(0=>array('defaut'=>'',
			      'camou'=>''),
		      1=>array('defaut'=>'',
			       'camou'=>'$cible$ vous a enlev&eacute; votre grade.'));
// Cours martiale
$listEvents[16]=array(0=>array('defaut'=>'',
			      'camou'=>''),
		      1=>array('defaut'=>'',
			       'camou'=>'$cible$ vous a fait passer en cours martiale.<br />$raison$'));
// ITAC - LD - 2009-12-29
// ITAC - LD - BEGIN
// http://www.dandoy.fr/mantis/view.php?id=7
// message lie a la mort via regen negative incorrect
$listEvents[17]=array(0=>array('defaut'=>'',
			      'camou'=>''),
		      1=>array(	'defaut'=>'Il est mort des suites de ses blessures.',
						'defautf' => 'Elle est morte des suites de ses blessures.',
						'camou'=>'Vous &ecirc;tes mort des suites de vos blessures.'));
// ITAC - LD - END
function showEvents($start,$end,$id){
  global $listEvents;
  $events=my_fetch_array('SELECT events.*,
perso1.nom AS tireur_nom,
perso1.ID AS tireur_ID,
perso1.armee AS tireur_armee,
perso1.rp_genre AS tireur_genre,
compagnie1.initiales AS tireur_compa,
perso2.nom AS cible_nom,
perso2.ID AS cible_ID,
perso2.armee AS cible_armee,
perso2.rp_genre AS cible_genre,
compagnie2.initiales AS cible_compa
FROM events
  LEFT OUTER JOIN persos AS perso1
    ON perso1.ID=events.tireur
  LEFT OUTER JOIN persos AS perso2
    ON perso2.ID=events.cible
  LEFT OUTER JOIN compagnies AS compagnie1
    ON perso1.compagnie=compagnie1.ID
  LEFT OUTER JOIN compagnies AS compagnie2
    ON perso2.compagnie=compagnie2.ID
WHERE events.date BETWEEN '.$start.' AND '.$end.'
  AND (perso1.ID='.$id.'
   OR  perso2.ID='.$id.')
ORDER BY events.ID DESC');
  $camou='defaut';
  if(isset($_SESSION['com_perso'])&&$_SESSION['com_perso']==$id){
    $camou='camou';
  }
  echo'<ul>
';
  for($i=1;$i<=$events[0];$i++){
    $buffer=array();
    if(!empty($listEvents[$events[$i]['type']]['buffer'])){
      $j=$i;
      while(!empty($listEvents[$events[$j+1]['type']]['buffer']) &&
	    $listEvents[$events[$j]['type']]['buffer'] == 
	    $listEvents[$events[$j+1]['type']]['buffer'] &&
	    $events[$j]['date']-$events[$j+1]['date']<60 &&
	    $events[$j]['tireur_ID'] == $events[$j+1]['tireur_ID'] &&
	    $events[$j]['cible_ID'] == $events[$j+1]['cible_ID']){
	if(empty($buffer)){
	  $buffer['end']=$events[$j]['date'];
	  $buffer['PA']=0;
	  $buffer['PV']=0;
	  $buffer['coups']=1;
	}
	switch($listEvents[$events[$j]['type']]['buffer']){
	default:
	  $buffer['PA']+=$events[$j]['PA'];
	  $buffer['PV']+=$events[$j]['PV'];
	  $buffer['coups']++;
	  break;
	}
	$j++;
      }
      if($j>$i){
	$events[$j]['mort'] = $events[$i]['mort'];
	$i=$j;
	$events[$i]['type']=$listEvents[$events[$i]['type']]['buffer'];
      }
    }
    echo showEvent($events[$i],$id,$camou,$buffer);
  }
  echo'</ul>
';
}

function showEvent($event,$id,$camou,$buffer){
  global $listEvents;
  $str='';
  $date=date(" \L\e d/m/Y à G\hi\m\\ns\s.",$event['date']);
  if(!empty($buffer)){
    $date=' Entre '.date("G\hi\m\\ns\s",$event['date']).
      ' et '.date("G\hi\m\\ns\s \l\e d/m/Y",$buffer['end']).'.';
    $event['PA']+=$buffer['PA'];
    $event['PV']+=$buffer['PV'];
  }
  else{
    $date=date(" \L\e d/m/Y à G\hi\m\\ns\s.",$event['date']);
  }
  $is_target=1;
  if($event['tireur_ID']==$id){
    $is_target=0;
  }
  if(!empty($listEvents[$event['type']][$is_target][$camou])){
    $plus='';
    if(!empty($listEvents[$event['type']][$is_target][$camou.'f']) &&
       (!$is_target && $event['tireur_genre']==1 ||
	$is_target && $event['cible_genre']==1)){
      $plus='f';
    }
    if(!$is_target && 
       $event['cible_ID']){
      $cible='<a href="evenements.php?id='.$event['cible_ID'].(isset($_GET['lite'])?'&amp;lite=1':'').'">'.bdd2html($event['cible_nom']).' ('.camp_initiale($event['cible_armee']).'-'.bdd2html($event['cible_compa']).'-'.$event['cible_ID'].')</a>';
    }
    else if($is_target &&
	    !$event['camoufle'] && 
	    $event['tireur_armee']){
      $cible='<a href="evenements.php?id='.$event['tireur_ID'].(isset($_GET['lite'])?'&amp;lite=1':'').'">'.bdd2html($event['tireur_nom']).' ('.camp_initiale($event['tireur_armee']).'-'.bdd2html($event['tireur_compa']).'-'.$event['tireur_ID'].')</a>';
    }
    else if($is_target &&
	    $event['camoufle']){
      $cible="Quelqu'un";
    }
    else{
      $cible='';
    }
    $nbr='';
    if(!empty($buffer)){
      $nbr=' '.$buffer['coups'].' fois';
    }
    $str.= '<li>'.
      str_replace(array('$PV$',
			'$PA$',
			'$cible$',
			'$raison$',
			'$grade$',
			'$grade2$',
			'$grade3$',
			'$nbr$'),
		  array($event['PV'],
			$event['PA'],
			$cible,
			bdd2html($event['raison']),
			numero_camp_grade($event['cible_armee'],
					  $event['PV']),
			grade_spec_autre($event['cible_armee'],2,''),
			grade_spec_autre($event['cible_armee'],3,''),
			$nbr),
		  $listEvents[$event['type']][$is_target][$camou.$plus]);
    if($event['mort'] &&
       !empty($listEvents[$event['type']][$is_target]['mort'.$camou.$plus])){
      $str.=$listEvents[$event['type']][$is_target]['mort'.$camou.$plus];
    }
    $str.=$date.
      '</li>
';
  }
  return $str;
}

?>
