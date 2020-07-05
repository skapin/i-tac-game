<?php
// Le perso est inscrit sur une mission, si celle ci est en route,
// on le fait spawn.
$mission=my_fetch_array('SELECT *
                         FROM missions
                            INNER JOIN missions_camps
                               ON missions.ID=missions_camps.mission
                         WHERE missions_camps.camp='.$perso['armee'].'
                           AND missions.ouverte=1
                           AND missions.active=1
                           AND missions.ID='.$perso['ID_mission'].'
                         LIMIT 1');
if($mission[0])
{
    // Il a bien le droit d'entrer sur cette mission.
  $cartes=my_fetch_array('SELECT ID
                          FROM cartes
                          WHERE mission='.$mission[1]['ID'].'
                          ORDER BY RAND()');
  if(!$cartes[0])
    erreur(1,'Pas de carte trouvée pour la mission à laquelle vous vous êtes inscrit.');
  else
    {
      $ok=0;
      $i=0;
      while(!$ok && $i<$cartes[0])
	{
	  $i++;
	  $ok=spawn($cartes[$i]['ID'],0);
	}
      if($ok)
	{
	  request('UPDATE persos
                   SET X='.$ok['X'].',
                       Y='.$ok['Y'].',
                       cloned=0,
                       date_last_mouv='.$time.', 
                       map='.$cartes[$i]['ID'].',
                       relocalisation='.($time+$mission[1]['relocalisation']*3600).' 
                   WHERE ID='.$_SESSION['com_perso']);
	  if(!affected_rows())
	    add_message(3,'Impossible de vous faire apparaître sur la carte à cause d\'une erreur SQL.');
	}
      else
	add_message(3,'Aucune case libre et utilisable pour apparaître sur cette mission.');
    }
}
?>