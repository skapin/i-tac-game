<?php
$block=0;
if(!empty($_POST['choix_mission']) &&
   !empty($_POST['mission']) &&
   is_numeric($_POST['mission']))
  {
    afficheQGs();
    $block=1;
  }
else if(!empty($_POST['update_qgs']) &&
   !empty($_POST['update_mission']) &&
   is_numeric($_POST['update_mission']))
  {
    updateQGs();
    $_POST['mission']=$_POST['update_mission'];
    afficheQGs();
    $block=1;
  }
if($block == 0)
  {
    afficheFormulaire();
  }


function updateQGs()
{
  global $perso;
  // Recuperation de la valeur totale dispo.
  $max=computeMax($_POST['update_mission']);
  foreach($_POST AS $key=>$value)
  {
    if(preg_match('`vs_([0-9]+)`',$key,$qg) && is_numeric($value))
      {
	if(!empty($qg[1]) && exist_in_db('SELECT qgs.ID
FROM qgs
INNER JOIN cartes
  ON cartes.ID=qgs.carte
LEFT OUTER JOIN qgs_valeur
  ON qgs_valeur.qg=qgs.ID
 AND qgs_valeur.camp='.$perso['armee'].'
WHERE cartes.mission='.$_POST['update_mission'].'
 AND (qgs.visible=1
       OR qgs.visible=2
       AND qgs.camp='.$perso['armee'].')
 AND qgs.ID='.$qg[1]))
	  {
	    $value=max(0,min($max,$value,10));
	    $max=max(0,$max-$value);
	    if(exist_in_db('SELECT vs
FROM qgs_valeur
WHERE qg='.$qg[1].'
AND camp='.$perso['armee']))
	      {
		// L'enregistrement existe deja.
		request('UPDATE qgs_valeur
SET vs='.$value.'
WHERE camp='.$perso['armee'].'
  AND qg='.$qg[1]);
	      }
	    else
	      {
		// Il faut creer la valeur.
		request('INSERT INTO qgs_valeur
 (qg,camp,vs)VALUES('.$qg[1].','.$perso['armee'].','.$value.')');
	      }
	  }
      }
  }
}


function afficheQGs()
{
  global $perso;
  $max=computeMax($_POST['mission']);
  // Liste des QGs connus.
  $qgs=my_fetch_array('SELECT qgs.ID, qgs.initiales,qgs_valeur.vs
FROM qgs
INNER JOIN cartes
  ON cartes.ID=qgs.carte
LEFT OUTER JOIN qgs_valeur
  ON qgs_valeur.qg=qgs.ID
 AND qgs_valeur.camp='.$perso['armee'].'
WHERE cartes.mission='.$_POST['mission'].'
 AND (qgs.visible=1
       OR qgs.visible=2
       AND qgs.camp='.$perso['armee'].')');
  echo '<form method="post" action="gene.php?gene_qgs">
<p>Les valeurs doivent &ecirc;tre comprises entre 0 et 10 avec un total &eacute;gal &agrave; ',$max,'
',form_hidden('update_mission',$_POST['mission']),'
</p>
<ul>
';
  for($i=1;$i<=$qgs[0];$i++)
    {
      $_POST['vs_'.$qgs[$i]['ID']]=$qgs[$i]['vs'];
      echo ' <li>',form_text(post2html($qgs[$i]['initiales']),'vs_'.$qgs[$i]['ID'],3,''),'</li>
';
    }
  echo'</ul>
 <p>',form_submit('update_qgs','Ok'),'
</p>
</form>
';

}

function afficheFormulaire()
{
  global $perso;
  // Affichage de la liste des missions dispos.
  $missions=my_fetch_array('SELECT ID,nom
FROM missions
INNER JOIN missions_camps
  ON missions_camps.mission=missions.ID
WHERE missions_camps.camp='.$perso['armee']);
echo '<form method="post" action="gene.php?gene_qgs">
<p>
 ',form_select('G&eacute;rer les valeurs strat&eacute;giques des QGs de la mission:','mission',$missions,''),form_submit('choix_mission','Ok'),'
</p>
</form>
';
}

function computeMax($mission)
{
  global $perso;
  // Calculs des points dispos.
  $nbr_qgs=my_fetch_array('SELECT COUNT(*)
FROM qgs
INNER JOIN cartes
  ON cartes.ID=qgs.carte
WHERE cartes.mission='.$mission);
  $unseen=my_fetch_array('SELECT SUM(vs)
FROM qgs
INNER JOIN cartes
  ON cartes.ID=qgs.carte
INNER JOIN qgs_valeur
  ON qgs_valeur.qg=qgs.ID
 AND qgs_valeur.camp='.$perso['armee'].'
WHERE cartes.mission='.$mission.'
 AND !(qgs.visible=1
       OR qgs.visible=2
       AND qgs.camp='.$perso['armee'].')');
  return $nbr_qgs[1][0]*5-$unseen[1][0];
}
?>