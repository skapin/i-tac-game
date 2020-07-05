<?php
if(isset($perso['ID_mission'])&&$perso['ID_mission']==$_POST['mission'])
  {
    // On se désinscrit d'une mission.
    request('UPDATE persos SET mission=0 WHERE ID='.$_SESSION['com_perso'].' LIMIT 1');
  }
else if(is_numeric($_POST['mission']))
  {
    // On s'inscrit à une mission.
    $erreur=0;
    $missions=my_fetch_array('SELECT *
                                  FROM missions
                                     INNER JOIN missions_camps
                                        ON missions.ID=missions_camps.mission
                                  WHERE missions_camps.camp='.$perso['armee'].'
                                    AND missions.ouverte=1
                                    AND missions.ID='.$_POST['mission'].'
                                  LIMIT 1');
    if(!$missions[0])
      {
	add_message(2,'Vous n\'avez pas accés à cette mission.');
	$erreur=1;
      }
    $inscrits=my_fetch_array('SELECT COUNT(persos.ID)
                                  FROM persos
                                  WHERE mission='.$missions[1]['ID'].'
                                    AND armee='.$perso['armee']);
    if($inscrits[1][0]>=$missions[1]['nbr_maxi'])
      {
	$erreur=1;
	add_message(2,'Mission déjà pleine.');
      }
    if($perso['ID_mission']!=$missions[1]['ID'])
      if($perso['map'])
	{
	  $erreur=1;
	  add_message(2,'Vous êtes déjà inscrit dans une autre mission.');
	}
    if($time<$perso['relocalisation'])
      {
	$erreur=1;
	add_message(2,'Vous avez quitté une mission depuis trop peu de temps pour pouvoir en rejoindre une autre.');
      }    
    if(!$erreur)
      {
	// On enregistre le fait qu'on entre dans la mission.
	request('UPDATE persos
                     SET mission='.$_POST['mission'].'
                     WHERE ID='.$_SESSION['com_perso'].'
                     LIMIT 1');
	if(affected_rows()) // On supprime les demandes de transfuge
	  {
	    request('DELETE FROM demandes WHERE `type`=1
 AND demandeur='.$_SESSION['com_ID']);
	    if(affected_rows())
	      request('OPTIMIZE TABLE demandes');
	  }
	$perso['mission']=$_POST['mission'];
	if(($inscrits[1][0]+1)==$missions[1]['nbr_mini'] && !$missions[1]['active'])
	  {
	    // Pour notre camp, la mission devrait pouvoir commencer,
	    // mais y a t'il le nombre minimum de persos pour les autres
	    // camps ?
	    $ok=1;
	    $missions=my_fetch_array('SELECT `nbr_mini`,`camp`
                                          FROM missions_camps
                                          WHERE missions_camps.mission='.$_POST['mission']);
	    for($i=1;($i<=$missions[0]&&$ok);$i++)
	      {
		$inscrits=my_fetch_array('SELECT COUNT(persos.ID)
                                              FROM persos
                                              WHERE mission='.$_POST['mission'].'
                                                AND armee='.$missions[$i]['camp']);
		if($inscrits[1][0]<$missions[$i]['nbr_mini'])
		  $ok=0;
	      }
	    if($ok)
	      {
		if($missions[1]['duree'])
		  $debut=$time;
		else
		  $debut=0;
		request('UPDATE missions
                             SET active=1'.($debut?', debut='.$debut:'').'
                             WHERE ID='.$missions[1]['ID']);
	      }
	  }
      }
    unset($missions);
  }
?>