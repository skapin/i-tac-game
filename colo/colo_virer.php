<?php
if(isset($_POST['virer_ok'],$_POST['virer_conf'],$_POST['virer_id'])&&is_numeric($_POST['virer_id']))
  {
    // Tentative de virage d'un perso.
    $virer=my_fetch_array('SELECT persos.ID,
persos.compte,
grades.compa AS grade 
FROM persos
LEFT OUTER JOIN grades
  ON grades.ID=persos.grade
WHERE persos.ID='.$_POST['virer_id']);
    if($virer[0])
      {
	if($virer[1]['grade'])
	  $plus='
grade=\'0\',
';
	else
	  $plus='';
	request('UPDATE persos
SET compagnie=1,'.$plus.'
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
forum_compa=0
WHERE ID='.$_POST['virer_id'].'
AND compagnie='.$perso['ID_compa'].'
AND niveau_compa<'.$perso['niveau_compa'].'
LIMIT 1');
	if(affected_rows())
	  {
	    update_droits_forum($_POST['virer_id']);
	    echo'<p>Perso viré.</p>';
	  }
	else
	  add_message(3,'Vous ne pouvez pas virer ce perso.');
      }
    else
      add_message(3,'Perso inconuu.');
  }
$persos=my_fetch_array('SELECT ID,
CONCAT(ID,CONCAT("(",CONCAT(nom,")"))) AS name
FROM persos
WHERE compagnie=\''.$perso['ID_compa'].'\'
AND niveau_compa<\''.$perso['niveau_compa'].'\'
ORDER BY ID ASC');
if($persos[0])
  {
    echo'<form method="post" action="compagnie.php?colo_virer">
<p>',form_select('Virer : ','virer_id',$persos,''),'<br />
',form_check('Confirmation','virer_conf'),'
',form_submit('virer_ok','Ok'),'</p>
</form>
';
  }
?>