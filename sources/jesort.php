<?php
if($time>$perso['relocalisation'])
{
  request('UPDATE persos
           SET persos.X=0,
               persos.Y=0,
               persos.map=0,
               persos.mission=0,
               persos.camouflage=0 
           WHERE persos.ID='.$_SESSION['com_perso']);
  if(!affected_rows())
    add_message(3,'Erreur lors du départ de la mission. Veuillez réessayer ou contacter un admin.');
}
?>