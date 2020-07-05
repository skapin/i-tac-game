<?php
//***************************************************************************
// Accepter un postulant.
//***************************************************************************
if(isset($_POST['compa_postule_confirm'],$_POST['compa_postule_ok'],$_POST['compa_postule_id']) &&
   is_numeric($_POST['compa_postule_id']) &&
   $perso['ID_compa']>1)
{
  $postulant=my_fetch_array('SELECT persos.compte
                             FROM persos
                               INNER JOIN demande_compagnie
                                  ON demande_compagnie.ID=persos.ID
                               LEFT OUTER JOIN grades
                                  ON persos.grade=grades.ID
                             WHERE persos.ID='.$_POST['compa_postule_id'].'
                               AND creation=0
                               AND demande_compagnie.compa='.$perso['ID_compa'].'
                               AND (grades.ID IS NULL OR grades.niveau!=1)
                               AND persos.armee='.$perso['armee']);
  if($postulant[0])
    {
      request('UPDATE persos
               SET colo_HRP=0,
                   colo_RP=0,
                   colo_colo=0,
                   colo_criteres=0,
                   colo_droits=0,
                   colo_sigle=0,
                   colo_valider=0,
                   colo_ordres=0,
                   colo_virer=0,
                   colo_grades=0,
                   niveau_compa=0,
                   compagnie='.$perso['ID_compa'].',
                   ordrescompa=1,
forum_compa=1,
forum_mcompa=0 
               WHERE persos.ID='.$_POST['compa_postule_id']);
      if(affected_rows())
	{
	  // On update le perso sur le forum.
	  update_droits_forum($_POST['compa_postule_id']);
	  // On supprime la demande.
	  request('DELETE
               FROM demande_compagnie
               WHERE ID='.$_POST['compa_postule_id'].'
                 AND creation=0
               LIMIT 1');
	  request('OPTIMIZE TABLE demande_compagnie');
	  echo'<p>postulation acceptée.</p>
';
	}
    }
  else
    add_message(3,'Impossible de valider cette postulation.');
}
//***************************************************************************
// Refuser un postulant.
//***************************************************************************
if(isset($_POST['compa_postule_confirmno'],$_POST['compa_postule_no'],$_POST['compa_postule_id']) &&
   is_numeric($_POST['compa_postule_id']))
{
  $postulant=my_fetch_array('SELECT persos.ID
                             FROM persos
                               INNER JOIN demande_compagnie
                                  ON demande_compagnie.ID=persos.ID
                             WHERE persos.ID='.$_POST['compa_postule_id'].'
                               AND creation=0
                               AND compa='.$perso['ID_compa'].'
                               AND persos.armee='.$perso['armee']);
  if($postulant[0])
    {
      request('DELETE
               FROM demande_compagnie
               WHERE ID='.$_POST['compa_postule_id'].'
                 AND creation=0
               LIMIT 1');
      if(affected_rows())
	{
	  request('INSERT
                   INTO events (`tireur`,
                                `cible`,
                                `date`,
                                `type`,
                                `raison`)
                         VALUES('.$_SESSION['com_perso'].',
                                '.$_POST['compa_postule_id'].',
                                '.time().',
                                11,
                                "'.post2bdd($_POST['compa_postule_refus']).'")');
	  echo'<p>Refus enregistré.</p>';
	}
      request('OPTIMIZE TABLE demande_compagnie');
    }
  else
    add_message(3,'Impossible de refuser cette postulation.');
}
//***************************************************************************
// Afficher le formulaire.
//***************************************************************************
$postulants=my_fetch_array('SELECT persos.ID,
                                   persos.nom
                             FROM persos
                               INNER JOIN demande_compagnie
                                  ON demande_compagnie.ID=persos.ID
                             WHERE creation=0
AND persos.compagnie=1
                               AND compa='.$perso['ID_compa'].'
                               AND persos.armee='.$perso['armee']);
echo'<form method="post" action="compagnie.php?colo_valider">
<p>
',form_select('Postulants : ','compa_postule_id',$postulants,''),'<br />
',form_check('Accepter','compa_postule_confirm'),'
',form_submit('compa_postule_ok','Ok'),'<br />
',form_check('Refuser','compa_postule_confirmno'),'
',form_submit('compa_postule_no','Ok'),'
',form_textarea('Motif du refus (le cas échéant) :','compa_postule_refus',15,70),'
</p>
</form>
';
?>