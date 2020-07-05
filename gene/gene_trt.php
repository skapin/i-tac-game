<?php
  //********************************************************
  // Application d'une peine.
  //********************************************************

if(isset($_POST['trt_ok'],$_POST['trt_id'])&&is_numeric($_POST['trt_id']))
  {
    if(isset($_POST['trt_duree'],$_POST['trt_grade'],$_POST['trt_assaut'],$_POST['trt_pompe'],$_POST['trt_mitrailleuse'],$_POST['trt_snipe'],$_POST['trt_lourde'],$_POST['trt_lekarz'],$_POST['trt_cac'],$_POST['trt_biotech'],$_POST['trt_pistolet'],$_POST['trt_VS'])
       &&is_numeric($_POST['trt_duree'])
       &&is_numeric($_POST['trt_grade'])
       &&is_numeric($_POST['trt_assaut'])
       &&is_numeric($_POST['trt_pompe'])
       &&is_numeric($_POST['trt_mitrailleuse'])
       &&is_numeric($_POST['trt_snipe'])
       &&is_numeric($_POST['trt_lourde'])
       &&is_numeric($_POST['trt_lekarz'])
       &&is_numeric($_POST['trt_cac'])
       &&is_numeric($_POST['trt_biotech'])
       &&is_numeric($_POST['trt_pistolet'])
       &&is_numeric($_POST['trt_VS'])
       &&(!$_POST['trt_VS'] ||
	  $_POST['trt_VS']>1 ||
	  $_POST['trt_duree'] ||
	  $_POST['trt_grade'] ||
	  $_POST['trt_assaut'] ||
	  $_POST['trt_pompe'] ||
	  $_POST['trt_mitrailleuse'] ||
	  $_POST['trt_snipe'] ||
	  $_POST['trt_lourde'] ||
	  $_POST['trt_lekarz'] ||
	  $_POST['trt_cac'] ||
	  $_POST['trt_biotech'] ||
	  $_POST['trt_pistolet'])
       &&$_POST['trt_duree']>=1)
      {
	if(!$_POST['trt_VS'])
	  $_POST['trt_VS']=-1;
	request('UPDATE persos
SET peine_assaut='.$_POST['trt_assaut'].',
peine_pompe='.$_POST['trt_pompe'].',
peine_mitrailleuse='.$_POST['trt_mitrailleuse'].',
peine_snipe='.$_POST['trt_snipe'].',
peine_lourde='.$_POST['trt_lourde'].',
peine_lekarz='.$_POST['trt_lekarz'].',
peine_cac='.$_POST['trt_cac'].',
peine_biotech='.$_POST['trt_biotech'].',
peine_pistolet='.$_POST['trt_pistolet'].',
peine_VS='.$_POST['trt_VS'].',
peine_tubes='.post_on('trt_tubes').',
peine_armes='.post_on('trt_armes').',
peine_munars='.post_on('trt_munars').',
peine_armures='.post_on('trt_armures').',
peine_reparations='.post_on('trt_reparations').',
peine_hopital='.post_on('trt_hopital').',
peine_grade='.post_on('trt_grade').',
peine_forum='.post_on('trt_forum').',
peine_TRT='.post_on('trt_TRT').','.(isset($_POST['trt_TRT'])?'
mouchard=0,
peine_debutTRT='.(time()+86400).',':'').'
peine_fin='.(time()+86400*$_POST['trt_duree']).'
WHERE  peine_assaut=0
  AND peine_pompe=0
  AND peine_mitrailleuse=0
  AND peine_snipe=0
  AND peine_lourde=0
  AND peine_lekarz=0
  AND peine_cac=0
  AND peine_biotech=0
  AND peine_pistolet=0
  AND peine_tubes=0
  AND peine_armes=0
  AND peine_munars=0
  AND peine_armures=0
  AND peine_reparations=0
  AND peine_hopital=0
  AND peine_grade=0
  AND peine_VS=0
  AND peine_TRT=0
  AND peine_forum=0
  AND niveau_gene<'.$perso['niveau_gene'].'
  AND armee='.$perso['armee'].'
  AND ID='.$_POST['trt_id'].'
LIMIT 1');
	if(!affected_rows())
	  add_message(3,'Vous ne pouvez pas appliquer une peine sur ce perso.');
	else
	  {
	    // Update des droits forum.
	    $compte=my_fetch_array('SELECT compte FROM persos WHERE ID='.$_POST['trt_id']);
	    if($compte[0])
	      update_droits_forum($compte[1]['compte']);
	    // On ajoute dans le casier judiciaire.
	    request('INSERT INTO casier
(assaut,
pompe,
snipe, 
mitrailleuse,
lourde,
mecano,
pistolet,
cac,
medoc,
tube,
armes,
munars,
armures,
reparations,
hopital,
grade,
VS,
TRT,
forum,
debut,
fin,
bourreau,
victime,
raison) 
VALUES('.$_POST['trt_assaut'].',
'.$_POST['trt_pompe'].',
'.$_POST['trt_snipe'].',
'.$_POST['trt_mitrailleuse'].',
'.$_POST['trt_lourde'].',
'.$_POST['trt_lekarz'].',
'.$_POST['trt_pistolet'].',
'.$_POST['trt_cac'].',
'.$_POST['trt_biotech'].',
'.post_on('trt_tubes').',
'.post_on('trt_armes').',
'.post_on('trt_munars').',
'.post_on('trt_armures').',
'.post_on('trt_reparations').',
'.post_on('trt_hopital').',
'.$_POST['trt_grade'].',
'.$_POST['trt_VS'].',
'.post_on('trt_TRT').',
'.post_on('trt_forum').',
'.(time()+86400).',
'.(time()+86400*$_POST['trt_duree']).',
'.$_SESSION['com_perso'].',
'.$_POST['trt_id'].',
"'.post2bdd($_POST['trt_raison']).'")');
	  }
      }
  }
  //********************************************************
  // Virage d'une peine.
  //********************************************************
if(isset($_POST['grace_ok'],$_POST['grace_id'])&&is_numeric($_POST['grace_id']))
  {
    request('UPDATE persos
SET peine_assaut=\'0\',
peine_pompe=\'0\',
peine_mitrailleuse=\'0\',
peine_snipe=\'0\',
peine_lourde=\'0\',
peine_lekarz=\'0\',
peine_cac=\'0\',
peine_biotech=\'0\',
peine_pistolet=\'0\',
peine_VS=\'0\',
peine_tubes=\'0\',
peine_armes=\'0\',
peine_munars=\'0\',
peine_armures=\'0\',
peine_reparations=\'0\',
peine_hopital=\'0\',
peine_TRT=\'0\',
peine_grade=\'0\',
peine_forum=\'0\',
peine_debutTRT=\'0\',
peine_fin=\'0\'
WHERE !(peine_assaut=\'0\'
  AND peine_pompe=\'0\'
  AND peine_mitrailleuse=\'0\'
  AND peine_snipe=\'0\'
  AND peine_lourde=\'0\'
  AND peine_lekarz=\'0\'
  AND peine_cac=\'0\'
  AND peine_biotech=\'0\'
  AND peine_pistolet=\'0\'
  AND peine_tubes=\'0\'
  AND peine_armes=\'0\'
  AND peine_munars=\'0\'
  AND peine_armures=\'0\'
  AND peine_reparations=\'0\'
  AND peine_hopital=\'0\'
  AND peine_grade=\'0\'
  AND peine_VS=\'0\'
  AND peine_forum=\'0\'
  AND peine_TRT=\'0\')
  AND ID<=\''.$_POST['grace_id'].'\'
  AND armee=\''.$perso['armee'].'\'
LIMIT 1');
    if(!affected_rows())
      add_message(2,'Impossible de grâcier ce soldat.');
    else
      {
	// Update des droits forum.
	$compte=my_fetch_array('SELECT compte FROM persos WHERE ID='.$_POST['grace_id']);
	if($compte[0])
	  update_droits_forum($compte[1]['compte']);
	// On modifie le casier judiciaire.
	request('UPDATE casier
SET fin=\''.time().'\'
WHERE victime=\''.$_POST['grace_id'].'\'
ORDER BY fin desc
LIMIT 1');
      }
  }
$persos=my_fetch_array('SELECT ID,CONCAT(ID,CONCAT(\' (\',CONCAT(nom,\')\'))) AS nom
FROM persos
WHERE peine_assaut=\'0\'
  AND peine_pompe=\'0\'
  AND peine_mitrailleuse=\'0\'
  AND peine_snipe=\'0\'
  AND peine_lourde=\'0\'
  AND peine_lekarz=\'0\'
  AND peine_cac=\'0\'
  AND peine_biotech=\'0\'
  AND peine_pistolet=\'0\'
  AND peine_tubes=\'0\'
  AND peine_armes=\'0\'
  AND peine_munars=\'0\'
  AND peine_armures=\'0\'
  AND peine_reparations=\'0\'
  AND peine_hopital=\'0\'
  AND peine_grade=\'0\'
  AND peine_VS=\'0\'
  AND peine_TRT=\'0\'
  AND peine_forum=\'0\'
  AND niveau_gene<\''.$perso['niveau_gene'].'\'
  AND armee=\''.$perso['armee'].'\'');

$trts=my_fetch_array('SELECT ID, CONCAT(ID,CONCAT(\' (\',CONCAT(nom,\')\'))) AS nom
FROM persos
WHERE !(peine_assaut=\'0\'
  AND peine_pompe=\'0\'
  AND peine_mitrailleuse=\'0\'
  AND peine_snipe=\'0\'
  AND peine_lourde=\'0\'
  AND peine_lekarz=\'0\'
  AND peine_cac=\'0\'
  AND peine_biotech=\'0\'
  AND peine_pistolet=\'0\'
  AND peine_tubes=\'0\'
  AND peine_armes=\'0\'
  AND peine_munars=\'0\'
  AND peine_armures=\'0\'
  AND peine_reparations=\'0\'
  AND peine_hopital=\'0\'
  AND peine_grade=\'0\'
  AND peine_VS=\'0\'
  AND peine_forum=\'0\'
  AND peine_TRT=\'0\')
  AND armee=\''.$perso['armee'].'\'');

$niveaux=array(11,
	       array(-1,'Type d\'arme interdit'),
	       array(0,'Aucune limitation'),
	       array(1,0),
	       array(2,1),
	       array(3,2),
	       array(4,3),
	       array(5,4),
	       array(6,5),
	       array(7,6),
	       array(8,7),
	       array(9,8));
$camp=$perso['armee'];
$grades=array(14,
	      array(1,numero_camp_grade($camp,0)),
	      array(2,numero_camp_grade($camp,1)),
	      array(3,numero_camp_grade($camp,2)),
	      array(4,numero_camp_grade($camp,3)),
	      array(5,numero_camp_grade($camp,4)),
	      array(6,numero_camp_grade($camp,5)),
	      array(7,numero_camp_grade($camp,6)),
	      array(8,numero_camp_grade($camp,7)),
	      array(9,numero_camp_grade($camp,8)),
	      array(10,numero_camp_grade($camp,9)),
	      array(11,numero_camp_grade($camp,10)),
	      array(12,numero_camp_grade($camp,11)),
	      array(13,numero_camp_grade($camp,12)),
	      array(0,numero_camp_grade($camp,13)));
$_POST['trt_VS']=1;
echo'<h1>Cours martiale</h1>
<form method="post" action="gene.php?gene_trt">
<h2>Appliquer une sentence :</h2>
<p>
',form_text('Durée : ','trt_duree','3',''),' jours.<br />
',form_select('Coupable : ','trt_id',$persos,''),'<br />
<h3>Limitation du matériel : </h3>
',form_select('Grade :','trt_grade',$grades,''),'<br />
',form_select('Armes d\'assaut :','trt_assaut',$niveaux,''),'<br />
',form_select('Fusils à pompe :','trt_pompe',$niveaux,''),'<br />
',form_select('Mitrailleuses :','trt_mitrailleuse',$niveaux,''),'<br />
',form_select('Fusils de précision :','trt_snipe',$niveaux,''),'<br />
',form_select('Armes lourdes :','trt_lourde',$niveaux,''),'<br />
',form_select('Mécano :','trt_lekarz',$niveaux,''),'<br />
',form_select('Armes de corps à corps :','trt_cac',$niveaux,''),'<br />
',form_select('Médecin :','trt_biotech',$niveaux,''),'<br />
',form_select('Pistolet :','trt_pistolet',$niveaux,''),'<br />
<h2>Interdiction d\'utiliser certains services des QGs</h2>
',form_check('Tubes :','trt_tubes'),'<br />
',form_check('Changement d\'armes :','trt_armes'),'<br />
',form_check('Munitions :','trt_munars'),'<br />
',form_check('Changement d\'armures :','trt_armures'),'<br />
',form_check('Réparation d\'armure :','trt_reparations'),'<br />
',form_check('Hôpital :','trt_hopital'),'<br />
<h2>Limitation de la progression</h2>
',form_text('Diviser la progression de grade par (0 pour bloquer la progression):','trt_VS','3',''),'
<h2>Pour les cas désespérés</h2>
',form_check('Suppression de l\'accés aux forums du camp :','trt_forum'),'<br />
',form_check('Autorisation de tirer dessus (effectif uniquement un cycle plus tard) :','trt_TRT'),'
</p>
<p>
',form_textarea('Raisons :<br />','trt_raison','20','50'),'<br />
',form_submit('trt_ok','Appliquer'),'
</p>
</form>
<form method="post" action="gene.php?gene_trt">
<h2>Accorder une grâce :</h2>
<p>
',form_select('Soldat : ','grace_id',$trts,''),'
',form_submit('grace_ok','Ok'),'
</p>
</form>
';
?>