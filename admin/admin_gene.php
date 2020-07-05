<?php
if(isset($_POST['nomme_ok'],$_POST['nomme_gene']) && is_numeric($_POST['nomme_gene'])){
  $perso=my_fetch_array("SELECT grades.niveau,
                                persos.compte
                         FROM persos
                           LEFT OUTER JOIN grades
                             ON grades.ID=persos.grade
                           WHERE persos.ID='$_POST[nomme_gene]'");
  if($perso[0] && $perso[1]['niveau']!=1 && $perso[1]['niveau']!=3){
    // Le perso existe et n'est ni géné en chef ni colonel.
    request("UPDATE persos
               SET persos.grade='3',
                   gene_ordres='1',
                   gene_compas='1',
                   gene_trt='1',
                   gene_transfuge='1',
                   gene_medailles='1',
                   gene_ngene='1',
                   gene_droits='1',
                   gene_grades='1',
                   gene_qgs=1,
                   gene_strat=1,
                   ordres='1',
                   forum_em='1',
                   forum_gene='1',
                   forum_mem='1',
                   forum_mgene='1',
forum_mcamp=1,
                   niveau_gene='100' 
               WHERE persos.ID='$_POST[nomme_gene]'");
    console_log('anim_gene',"Elévation au grade de général du perso ".$_POST['nomme_gene'],'',0,0);
    request("INSERT
               INTO events (`tireur`,
                            `cible`,
                            `date`,
                            `type`)
                     VALUES('$_SESSION[com_perso]',
                            '$_POST[nomme_gene]',
                            '".time()."',
                            '13')");
    update_droits_forum($_POST['nomme_gene']);
  }
}

if(isset($_POST['degrade_ok'],$_POST['degrade_gene']) && is_numeric($_POST['degrade_gene']))
{
  $perso=my_fetch_array("SELECT grades.niveau,
                                persos.compte
                         FROM persos
                            LEFT OUTER JOIN grades
                              ON grades.ID=persos.grade
                         WHERE persos.ID='$_POST[degrade_gene]'");
  if($perso[0] && $perso[1]['niveau']==3) // Le perso est bien général en chef.
    {
      request("UPDATE persos
               SET grade='0',
                   gene_ordres='0',
                   gene_compas='0',
                   gene_trt='0',
                   gene_transfuge='0',
                   gene_medailles='0',
                   gene_ngene='0',
                   gene_droits='0',
                   gene_grades='0',
                   gene_qgs=0,
                   gene_strat=0, 
                   ordresconfi='0',
                   forum_em='0',
                   forum_gene='0',
                   forum_mem='0',
                   forum_mgene='0',
                   forum_mcamp='0',
                   niveau_gene='0' 
               WHERE persos.ID='$_POST[degrade_gene]'");
      if(!affected_rows())
	erreur(0,"Erreur SQL.");
      // On enregistre l'évènement.
      else
	{
		console_log('anim_gene',"Dégradation du général ".$_POST['degrade_gene'],'',0,0);
	  request("INSERT
               INTO events (`tireur`,
                            `cible`,
                            `date`,
                            `type`)
                     VALUES('$_SESSION[com_perso]',
                            '$_POST[degrade_gene]',
                            '".time()."',
                            '15')");
	  update_droits_forum($_POST["degrade_gene"]);
	}
    }
}
if(isset($_POST['nommeColo_ok']) && 
   !empty($_POST['compaID']) && is_numeric($_POST['compaID']) && 
   !empty($_POST['nomme_colo']) && is_numeric($_POST['nomme_colo'])){
  $perso=my_fetch_array("SELECT grades.niveau,
                                persos.compte,
persos.armee
                         FROM persos
                           LEFT OUTER JOIN grades
                             ON grades.ID=persos.grade
                           WHERE persos.ID='$_POST[nomme_colo]'");
  if($perso[0] && $perso[1]['niveau']!=1 && $perso[1]['niveau']!=3&& $perso[1]['niveau']!=2){
    // Perso sans grade
    // Seconde etape : recherche du perso actuellement colonel de la compagnie
    $currentColo = my_fetch_array('SELECT persos.ID, persos.armee
FROM persos
INNER JOIN grades
ON grades.ID = persos.grade
WHERE persos.compagnie = '.$_POST['compaID'].'
AND grades.niveau = 1');
    if($currentColo[0] && $perso[1]['armee'] = $currentColo[1]['armee']){
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
                   forum_mcompa=1,
                   compagnie='.$_POST['compaID'].'
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
                   WHERE persos.ID='.$currentColo[1]['ID']);
	  update_droits_forum($currentColo[1]['ID']);
	  echo '<p>Nouveau colonel désigné.</p>';
	}
    }
  }
}



$persos=my_fetch_array("SELECT persos.ID,
                               persos.nom,
                               persos.armee,
                               grades.niveau
                        FROM persos
                           LEFT OUTER JOIN grades
                             ON persos.grade=grades.ID
WHERE grades.niveau=3
                        ORDER BY persos.armee ASC,persos.ID ASC");

$a_degrader=array(0);
for($i=1;$i<=$persos[0];$i++){
  $a_degrader[]=array($persos[$i][0],$persos[$i][0].'('.$persos[$i][1].')');
  $a_degrader[0]++;
}
$compagnies = my_fetch_array('SELECT ID, nom FROM compagnies WHERE ID > 1 ORDER BY nom ASC');

echo'<form method="post" action="anim.php?admin_gene">
<p>
'.form_text("Nommer comme général en chef de son armée : ","nomme_gene",'','','').'<br />
'.form_submit("nomme_ok","Ok").'</p>
<p>
'.form_select("Virer de son poste de général en chef : ","degrade_gene",$a_degrader,"").'<br />
'.form_submit("degrade_ok","Ok").'</p>
</form>
<form method="post" action="anim.php?admin_gene">
<p>
'.form_text("Nommer ","nomme_colo",'','','').'
'.form_select("colonel de la compagnie : ","compaID",$compagnies,"").'<br />
'.form_submit("nommeColo_ok","Ok").'</p>
<p>
</form>

';
?>