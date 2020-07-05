<?php
if(isset($perso,$_POST['qg_dep'],$_POST['qg_dest']) &&
   is_numeric($_POST['qg_dep']) &&
   is_numeric($_POST['qg_dest'])&&
   !$perso['peine_tubes'])
{
  $qg1=exist_in_db('SELECT ID
                    FROM qgs
                    WHERE SQRT(POW(X-'.$perso['X'].',2)
                              +POW(Y-'.$perso['Y'].',2))<=utilisation
                      AND carte='.$perso['map'].'
                      AND ID='.$_POST['qg_dep'].'
                    LIMIT 1');
  if($qg1 && !is_bloque($_POST['qg_dep']))
    {
      // Le qg de départ est ok.
      $tube=my_fetch_array('SELECT QG_2,prix
                            FROM tubes
                            WHERE QG_1='.$_POST['qg_dep'].'
                              AND ID='.$_POST['qg_dest'].'
                            LIMIT 1');
      if($tube[0])
	{
	  // Le tube existe.
	  if($tube[1]['prix']>$perso['PM'])
	    {
	      add_message(3,'Vous n\'avez pas assez de mouvement pour utiliser ce tube.');
	    }
	  else
	    {
	      $qg2=my_fetch_array('SELECT ID,carte,malus_camou
                                   FROM qgs
                                   WHERE (qgs.camp='.$perso['armee'].' or qgs.type=1)
                                     AND qgs.ID='.$tube[1]['QG_2'].'
                                   LIMIT 1');
	      if($qg2[0]&& !is_bloque($qg2[1]['ID']))
		{
		  // Le qg d'arrivée est ok, reste plus qu'à essayer de faire spawner.
		  $coords=spawn($qg2[1]['carte'],$qg2[1]['ID']);
		  if(!$coords)
		    add_message(3,'Toutes les cases du QG de destination sont occupées.');
		  else
		    {
		      request('UPDATE persos
                               SET `X`='.$coords['X'].',
                                   `Y`='.$coords['Y'].',
                                   `map`='.$qg2[1]['carte'].',
                                   `PM`=`PM`-'.$tube[1]['prix'].',
date_last_mouv='.$time.', 
                                   `malus_camou_tir`='.$qg2[1]['malus_camou'].'
                               WHERE ID='.$_SESSION['com_perso']);
		      if(!affected_rows())
			add_message(3,'Erreur SQL.');
		    }
		}
	      else
		add_message(3,'Le QG de destination n\'est pas disponible.');
	    }
	}
      else
	add_message(3,'Pas de tubes existants entre les QGs de départ et de destination.');
    }
  else
    add_message(3,'Le QG de départ est bloqué.');
} 
else
  add_message(3,'Erreur de parametres');
?>