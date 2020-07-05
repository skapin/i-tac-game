<?php
if($_GET['bouge']=='NO'){
  $pas_X=-1;
  $pas_Y=-1;
  $diag=true;
}
if($_GET['bouge']=='N'){
  $pas_X=0;
  $pas_Y=-1;
  $diag=false;
}
if($_GET['bouge']=='NE'){
  $pas_X=1;
  $pas_Y=-1;
  $diag=true;
}
if($_GET['bouge']=='O'){
  $pas_X=-1;
  $pas_Y=0;
  $diag=false;
}
if($_GET['bouge']=='E'){
  $pas_X=1;
  $pas_Y=0;
  $diag=false;
}
if($_GET['bouge']=='SO'){
  $pas_X=-1;
  $pas_Y=1;
  $diag=true;
}
if($_GET['bouge']=='S'){
  $pas_X=0;
  $pas_Y=1;
  $diag=false;
}
if($_GET['bouge']=='SE'){
  $pas_X=1;
  $pas_Y=1;
  $diag=true;
}

$X=$perso['X'];
$Y=$perso['Y'];

// Recuperation des trucs sur les trois cases dans la direction ou on veut
// aller.

$cases=my_fetch_array('SELECT carte_'.$perso['map'].'.X,
                               carte_'.$perso['map'].'.Y,
                               carte_'.$perso['map'].'.Z,
                               carte_'.$perso['map'].'.h,
                               carte_'.$perso['map'].'.terrain,
                               terrains.prix_'.$perso['type_armure'].' AS prix,
                               terrains.competence AS comp_terrain,
                               persos.ID,
                               qgs.ID as qg_ID,
                               qgs.camp,
                               qgs.prenable
                        FROM carte_'.$perso['map'].'
                           INNER JOIN terrains
                             ON terrains.ID=carte_'.$perso['map'].'.terrain
                           LEFT OUTER JOIN `persos`
                             ON carte_'.$perso['map'].'.X=persos.X
                            AND carte_'.$perso['map'].'.Y=persos.Y
                            AND persos.map='.$perso['map'].'
                           LEFT OUTER JOIN `qgs`
                             ON carte_'.$perso['map'].'.X=qgs.X
                            AND carte_'.$perso['map'].'.Y=qgs.Y
                            AND qgs.carte='.$perso['map'].'
                        WHERE (carte_'.$perso['map'].'.X='.($X).'
                           AND carte_'.$perso['map'].'.Y='.($Y).')
                           OR (carte_'.$perso['map'].'.X='.($X+$pas_X).'
                           AND carte_'.$perso['map'].'.Y='.($Y+$pas_Y).')
                           OR (carte_'.$perso['map'].'.X='.($X+2*$pas_X).'
                           AND carte_'.$perso['map'].'.Y='.($Y+2*$pas_Y).')
                           OR (carte_'.$perso['map'].'.X='.($X+3*$pas_X).'
                           AND carte_'.$perso['map'].'.Y='.($Y+3*$pas_Y).')');
$map=array();
for($i=1;$i<=$cases[0];$i++){
  if($cases[$i]['ID']){
    // C'est un perso
    $map[$cases[$i]['Y']][$cases[$i]['X']]=array('praticable'=>2,
						 'prix'=>$cases[$i]['prix'],
						 'comp'=>$cases[$i]['comp_terrain'],
						 'z_perso'=>$cases[$i]['Z']+0.5);
  }
  else if($cases[$i]['qg_ID']){
    // C'est un qg
    if($cases[$i]['prenable']&&$cases[$i]['camp']!=$perso['armee']){
      // Prenable
      $map[$cases[$i]['Y']][$cases[$i]['X']]=array('praticable'=>3,
		   'comp'=>$cases[$i]['comp_terrain'],
		   'prix'=>$cases[$i]['prix'],
		   'ID'=>$cases[$i]['qg_ID'],
		   'z_perso'=>$cases[$i]['Z']+0.5);
    }
    else{
      // Impossible a prendre
      $map[$cases[$i]['Y']][$cases[$i]['X']]=array('praticable'=>0);
    }
  }
  else{
    // Case vide
    $map[$cases[$i]['Y']][$cases[$i]['X']]=array('praticable'=>1,
						 'prix'=>$cases[$i]['prix'],
						 'comp'=>$cases[$i]['comp_terrain'],
						 'z_perso'=>$cases[$i]['Z']+0.5);
  }
}
$mapTriee=array();
for($i=0;$i<$cases[0];$i++){
  $mapTriee[]=$map[$i*$pas_Y+$perso['Y']][$i*$pas_X+$perso['X']];
}
$prix=calcMouv($mapTriee,$diag);

$bouge=0;
// Verification de la possibilite d'aller sur la case et du cout en PM
if($prix[0] == 0){
  // Probleme pour aller sur la case
  add_message(3,$prix[1]);
}
else if($prix[0]>$perso['PM']){
  // Pas assez de mouvement
  add_message(3,'Vous ne disposez pas d\'assez de PT pour aller sur cette case.');
}
else{
  $bouge=1;
  $camou=0;
  $mort=false;
  $update='';
  $aMonter=$prix[3];
  foreach($aMonter AS $key=>$value){
    $aMonter[$key]=$value*0.8/80;
  }
  if($prix[2] > 0){
    $aMonter['escalade']=$prix[2]/10;
  }
  // Si on doit s'infiltrer, on teste la reussite ou non
  if($prix[1]){
    $aMonter['infi']=$prix[1];
    $rand=mt_rand(1,100);
    $chances=$GLOBALS['competences']['infi'][$perso['infi']]['reussite'][$prix[1]];
    add_message(1,'Infiltration : '.$rand.'/'.$chances);
    if($rand >= $chances){
      $bouge=0;
    }
  }
  // Si on etait camou, on voit si on le reste
  if($perso['camouflage']!=0){
    $camou=reussite_camou(0);
    if(!$camou || $camou==6){
      if($perso['camouflage']!=6){
	add_message(2,'Vous avez perdu votre camouflage.<br />');
	$update.=', date_decamou=\''.$time.'\'';
      }
    }
    else{
      $aMonter['camou']=$prix[0]/80*0.4;
    }
  }
  if($mapTriee[$prix[1]+1]['praticable']==3){
    // On prend un QG
    request('UPDATE `qgs` 
SET `camp`='.$perso['armee'].' 
WHERE `ID`='.$mapTriee[$prix[1]+1]['ID'].' LIMIT 1');
    add_message(1,'Vous venez de prendre un QG.');
    $bouge=0;
  }
  else{
    // "Simple" mouvement
    // Verifions la presence ou non de mines sur le chemin
// ITAC - LD - 2009-12-30
// ITAC - LD - BEGIN
// http://dandoy.fr/mantis/view.php?id=8
	$mort=desamorceMines();
	// add_message(4,'Mourru? ' . $temp . '.<br />');
// ITAC - LD - END
    if($mort){
      // On est mort, donc on ne bougera pas
      $bouge=0;
    }
  }
  $upComps=monteComps($aMonter);
  $sql='UPDATE persos
SET `PM`=`PM`-'.$prix[0].$upComps;
  if($bouge){
    $sql.=', `X`=`X`+'.($pas_X*(1+$prix[1])).',
`Y`=`Y`+'.($pas_Y*(1+$prix[1])).'
';
  }
  if(!$mort){
    $sql.=',`camouflage`='.$camou.',
';
  }
  $sql.='date_last_bouge='.$time.'
WHERE ID='.$_SESSION['com_perso'];
  request($sql);
}

function desamorceMines(){
  global $perso,$mapTriee,$prix,$pas_X,$pas_Y,$aMonter;
  $mort=false;
  $mines=my_fetch_array('SELECT mines.ID,
mines.degats,
mines.pourcent_PV,
mines.discretion,
mines.instabilite,
mines.minage,
mines.terrain,
mines.poseur,
mines.armee,
mines.camouflage,
mines.X,
mines.Y
FROM mines
  LEFT JOIN minesmarquees
    ON mines.ID=minesmarquees.ID
WHERE   mines.map='.$perso['map'].'
  AND mines.X BETWEEN '.($perso['X']+$pas_X).'
              AND '.($perso['X']+$pas_X*(1+$prix[1])).'
  AND mines.Y BETWEEN '.($perso['Y']+$pas_Y).'
              AND '.($perso['Y']+$pas_Y*(1+$prix[1])).'
  AND mines.poseur!='.$_SESSION['com_perso'].'
  AND (minesmarquees.perso IS NULL
       OR
       minesmarquees.perso!='.$_SESSION['com_perso'].'
       AND minesmarquees.camp!='.$perso['armee'].')');
  if($mines[0]){
    $aMonter['demine']=0;
    for($i=1;$mort == false && $i<=$mines[0];$i++){
      // detection ?
      $detect=10*($perso['demine']+4)+
	5*$perso['ecl']+
	2*$perso[$mapTriee[max(abs($perso['X']-$mines[$i]['X']),
			       abs($perso['Y']-$mines[$i]['Y']))]['comp']]-
	(10*$mines[$i]['minage']+
	 5*$mines[$i]['camouflage']+
	 2*$mines[$i]['terrain']+
	 $mines[$i]['discretion']);
      $rand=mt_rand(1,100);
      if(($rand == 1 || $rand <= $detect) && $rand !=100){
	// Mine trouvee.
	request('INSERT INTO minesmarquees (ID,perso)
 VALUES('.$mines[$i]['ID'].','.$_SESSION['com_perso'].')');
	add_message(4,'Mine rep&eacute;r&eacute;e.<br />');
	$aMonter['demine'].=max(0.05,min(1,1-$detect/100))/2;
      }
      else{
	$exp=$mines[$i]['instabilite']+
	  $perso['bonus_precision']+
	  2*$mines[$i]['minage']+
	  2*$mines[$i]['terrain'];
	$rand=mt_rand(1,100);
	if(($rand == 1 || $rand <= $exp) && $rand !=100){
	  // La mine explose
	  $mort=degats_mines($mines[$i]['degats'],
			     $mines[$i]['pourcent_PV']);
	  request('DELETE FROM mines WHERE ID='.$mines[$i]['ID']);
	  request('DELETE FROM minesmarquees WHERE ID='.$mines[$i]['ID']);
	  $aMonter['demine'].=max(0.05,min(1,1-$detect/100))/2;
	}
      }
    }
  }
  return $mort;
}
?>
