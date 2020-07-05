<?php
if(!empty($_POST['mineID']) &&
   is_numeric($_POST['mineID'])){
  // Recuperation des infos de la mine.
  $mines=my_fetch_array('SELECT mines.X,
mines.Y,
mines.map,
mines.degats,
mines.pourcent_PV,
mines.discretion,
mines.instabilite,
mines.minage,
mines.terrain,
mines.poseur,
mines.armee,
minesmarquees.camp,
minesmarquees.perso
FROM mines
  LEFT JOIN minesmarquees
    ON mines.ID=minesmarquees.ID
WHERE (mines.poseur='.$_SESSION['com_perso'].'
  OR minesmarquees.perso='.$_SESSION['com_perso'].'
  OR minesmarquees.camp='.$perso['armee'].')
  AND mines.ID='.$_POST['mineID']);
  if(!$mines[0]){
    add_message(4,'Mine inexistante.');
  }
  else if($mines[1]['X']!=$perso['X']||
	  $mines[1]['Y']!=$perso['Y']||
	  $mines[1]['map']!=$perso['map']){
    add_message(4,'Mine non pr&eacute;sente.');
  }
  else{
    $perte=false;
    $explosion=false;
    for($i=2;$i<=$mines[0];$i++){
      if($mines[$i]['camp']==$perso['armee']){
	$mines[1]['camp']=$mines[$i]['camp'];
      }
    }
    if(!empty($_POST['demine'])){
      if($perso['PM']>=20){
	if($perso['type_armure']==1){
	  $obj=min(99,max(1,10*($perso['demine']+1)-$mines[1]['instabilite']));
	  $rand=mt_rand(1,100);
	  add_message(4,'Tentative de d&eacute;minage : '.$rand.'/'.$obj.'.<br />');
	  if($rand <= $obj || $rand == 1){
	    add_message(4,'D&eacute;minage r&eacute;ussi.<br />');
	    request('DELETE FROM mines WHERE ID='.$_POST['mineID']);
	    request('DELETE FROM minesmarquees WHERE ID='.$_POST['mineID']);
	    add_message(5,'parent.destroyClass("formDemine'.$_POST['mineID'].'");');
	    if($mines[1]['camp']==$perso['armee']){
	      $perte=true;
	    }
	  }
	  else{
	    add_message(4,'D&eacute;minage &eacute;chou&eacute;.<br />');
	    if($rand >= $obj+(100-$obj)/2 || $rand == 100){
	      $explosion=true;
	      if($mines[1]['camp']==$perso['armee']){
		$perte=true;
	      }
	    }
	    else if($perso['PM']<60){
	      add_message(5,'parent.destroyClass("formDeminage'.$_POST['mineID'].'");');
	    }
	  }
	  $up=monte_comp('demine',max(1,min(0.05,1-$obj/100)));
	  $update=$up[0];
	  request('UPDATE persos SET PM=PM-20'.$update.' WHERE ID='.$_SESSION['com_perso']);
	  $perso['PM']-=20;
	}
	else{
	  add_message(4,'Mauvais type d\'armure pour d&eacute;miner.');
	  add_message(5,'parent.destroyClass("formDeminage'.$_POST['mineID'].'");');
	}
      }
      else{
	add_message(4,'Pas assez de PM pour d&eacute;miner.');
	add_message(5,'parent.destroyClass("formDeminage'.$_POST['mineID'].'");');
      }
    }
    else if(!empty($_POST['declenchemine'])){
      if($mines[1]['camp']==$perso['armee']){
	$perte=true;
      }
      $explosion=true;
    }
    else if(!empty($_POST['mark'])){
      if(empty($mines[1]['camp'])){
	if(empty($mines[1]['perso'])){
	  request('INSERT INTO minesmarquees (ID,camp)
VALUES('.$_POST['mineID'].','.$perso['armee'].')');
	}
	else{
	  request('UPDATE minesmarquees SET camp='.$perso['armee'].' WHERE ID='.$_POST['mineID'].' AND perso='.$mines[1]['perso']);
	}
	add_message(4,'Mine marqu&eacute;e.');
	add_message(5,'parent.destroyClass("formMark'.$_POST['mineID'].'");');
      }
      else{
	add_message(4,'Mine d&eacute;j&agrave; marqu&eacute;e.');
	add_message(5,'parent.destroyClass("formMark'.$_POST['mineID'].'");');
      }
    }
    if($explosion){
      degats_mines($mines[1]['degats'],$mines[1]['pourcent_PV']);
      request('DELETE FROM mines WHERE ID='.$_POST['mineID']);
      request('DELETE FROM minesmarquees WHERE ID='.$_POST['mineID']);
      add_message(5,'parent.destroyClass("formDemine'.$_POST['mineID'].'");');
    }
    if($perte){
      $perte_VS=update_VS($perso,-($GLOBALS['gain_grade'])/10);
      request('UPDATE persos SET VS='.$perte_VS[0].',
confiance='.$perte_VS[1].',
grade_reel='.$perte_VS[2].'
WHERE ID='.$_SESSION['com_perso']);
    }
  }
}
?>