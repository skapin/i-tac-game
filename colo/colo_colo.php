<?php
if(isset($_POST['nomme_ok'],$_POST['nomme_colo_confirm'],$_POST['nomme_colo']) && is_numeric($_POST['nomme_colo']))
{
  $nommer=my_fetch_array('SELECT grades.niveau,
persos.compte 
                         FROM persos
                           LEFT OUTER JOIN grades
                           ON grades.ID=persos.grade
                         WHERE persos.ID='.$_POST['nomme_colo'].'
                           AND persos.compagnie='.$perso['ID_compa'].'
                           AND (grades.ID IS NULL OR grades.niveau=0)
                           AND persos.armee='.$perso['armee']);
  if($nommer[0]) // Le perso existe et n'est ni géné en chef, ni colonel, ni général.
    {
      request('UPDATE persos
               SET persos.grade=1,
                   colo_ordres=1,
                   colo_valider=1,
                   colo_criteres=1,
                   colo_virer=1,
                   colo_droits=1,
                   colo_colo=1,
                   colo_HRP=1,
                   colo_RP=1,
                   colo_sigle=1,
                   colo_grades=1,
                   niveau_compa=100,
                   niveau_gene=50, 
                   ordrescompa=1,
                   ordresconfi=1,
                   forum_em=1,
                   forum_compa=1,
                   forum_mcompa=1
               WHERE persos.ID='.$_POST['nomme_colo']);
      if(affected_rows())
	{
	  update_droits_forum($_POST['nomme_colo']);
	  // On se vire le grade de colonel maintenant.
	  request('UPDATE persos
                   SET persos.grade=0,
                       colo_ordres=0,
                       colo_valider=0,
                       colo_criteres=0,
                       colo_virer=0,
                       colo_droits=0,
                       colo_colo=0,
                       colo_HRP=0,
                       colo_RP=0,
                       colo_sigle=0,
                       colo_grades=0,
                       niveau_compa=0,
                       niveau_gene=0, 
                       ordresconfi=0,
                       forum_em=0,
forum_mcompa=0
                   WHERE persos.ID='.$_SESSION['com_perso']);
	  update_droits_forum($_SESSION['com_perso']);
	}
    }
  else
    add_message(3,'Vous ne pouvez pas nommer ce soldat colonel de compagnie.');
}
$perso=recup_droits('colo');
if(autorisation('colo'))
  {
    $persos=my_fetch_array('SELECT persos.ID,
                               persos.nom
                        FROM persos
                          LEFT OUTER JOIN grades
                            ON persos.grade=grades.ID
                        WHERE persos.armee='.$perso['armee'].'
                          AND persos.compagnie='.$perso['ID_compa'].'
                          AND (grades.ID IS NULL OR grades.niveau=0)
                        ORDER BY persos.ID ASC');
    $a_grader[0]=0;
    for($i=1;$i<=$persos[0];$i++)
      {
	$a_grader[]=array($persos[$i]['ID'],$persos[$i]['ID'].' ('.$persos[$i]['nom'].')');
	$a_grader[0]++;
      }
    echo'<form method="post" action="compagnie.php?colo_colo">
<p>
',form_select('Nommer comme colonel : ','nomme_colo',$a_grader,''),'<br />
',form_check('Confirmation : ','nomme_colo_confirm'),'
',form_submit('nomme_ok','Ok'),'</p>
<p>
</form>
';
  }
?>