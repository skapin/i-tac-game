<?php
$ok=0;
include('../sources/monperso.php');
$transfugeable=true;
// Recuperation de tous les persos du compte.
$persos=my_fetch_array('SELECT map FROM persos WHERE compte='.$_SESSION['com_ID']);
for($i=1;$i<=$persos[0];$i++){
  if($persos[$i]['map']>0){
    $transfugeable=false;
  }
}

if(!empty($_POST['transfuge_ok']) && $transfugeable){
  // Gestion d'une demande de transfuge.
  if(empty($_POST['transfuge_pass'])){
    add_message(1,'Il faut que vous entriez votre mot de passe pour faire une demande de transfuge.');
  }
  else if(!exist_in_db('SELECT ID FROM compte WHERE ID='.$_SESSION['com_ID'].'
 AND pass="'.sha1(strtolower(trim($_SESSION['com_login'])).trim(post2text($_POST['transfuge_pass']))).'"
 LIMIT 1')){
    add_message(1,'Mauvais mot de passe.');
  }
  else if(empty($_POST['transfuge_camp']) ||
	  !is_numeric($_POST['transfuge_camp']) ||
	  !exist_in_db('SELECT ID
FROM camps
WHERE ouvert=1
AND ID!='.$perso['armee'].'
AND ID='.$_POST['transfuge_camp'].' LIMIT 1')){
    add_message(1,'impossible de transfuger vers ce camp.');
  }
  else{
    // Suppression de toute demande precedente.
    request('DELETE FROM demandes WHERE `type`=1 AND demandeur='.$_SESSION['com_ID']);
    // Enregistrement de la demande.
    if(!request('INSERT INTO demandes
(`type`,
`demandeur`,
`sujet`, 
`raison`,
`RP`)
 VALUES(1,
'.$_SESSION['com_ID'].',
'.$_POST['transfuge_camp'].',
"'.post2bdd($_POST['transfuge_HRP']).'", 
 "'.post2bdd($_POST['transfuge_RP']).'")')){
      erreur(0, 'Probl&egrave;me lors de l\'enregistrement de votre demande en bdd, veuillez r&eacute;essayer plus tard.');
    }
    else{
      add_message(1,'Demande enregistr&eacute;e.');
    }
  }
}

if(!empty($_POST['annule_ok']) &&
   !empty($_POST['annule_transfuge'])){
  // Annulation de toute demande de transfuge en cours.
  request('DELETE FROM demandes WHERE `type`=1 AND demandeur='.$_SESSION['com_ID']);
}
if(!empty($_POST['valide_ok']) &&
   !empty($_POST['valide_transfuge']) &&
   $transfugeable){
  // On accepte le transfuge.
  $demande=my_fetch_array('SELECT sujet,diminution_VS,duree_effet FROM demandes
WHERE `type`=1
AND demandeur='.$_SESSION['com_ID'].'
AND validation_1=1
AND validation_2=1
AND diminution_VS >= 1
LIMIT 1');
  if(!$demande[0]){
    add_message(1,'Votre demande n\'a pas encore &eacute;t&eacute; accept&eacute;e.');
  }
  else if($demande[1]['sujet']==$perso['armee']){
    add_message(1,'Probl&egrave;me d\'int&eacute;grit&eacute; bdd. Veuillez contacter les admins.');
  }
  else{
    // En avant pour le changement de camp.
    $sql='UPDATE persos SET
compagnie=1,
grade=0,
ordres=1,
ordresconfi=0,
ordrescompa=0,
forum_em=0,
forum_gene=0,
forum_compa=0,
forum_mem=0,
forum_mgene=0,
forum_mcamp=0,
forum_mcompa=0,
niveau_gene=0,
niveau_compa=0,
peine_assaut=0,
peine_pompe=0,
peine_mitrailleuse=0,
peine_snipe=0,
peine_lourde=0,
peine_lekarz=0,
peine_cac=0,
peine_biotech=0,
peine_pistolet=0,
peine_VS=0,
peine_tubes=0,
peine_armes=0,
peine_munars=0,
peine_armures=0,
peine_reparations=0,
peine_hopital=0,
peine_TRT=0,
peine_grade=0,
peine_forum=0,
peine_debutTRT=0,
peine_fin=0,
transfuge_VS='.$demande[1]['diminution_VS'].',
armee='.$demande[1]['sujet'].', 
fin_effet='.($time+$demande[1]['duree_effet']*86400).',
gene_transfuge=0,
gene_compas=0,
gene_droits=0,
gene_ngene=0,
gene_medailles=0,
gene_ordres=0,
gene_trt=0,
gene_grades=0,
gene_qgs=0,
gene_strat=0,
colo_HRP=0,
colo_RP=0,
colo_colo=0,
colo_criteres=0,
colo_droits=0,
colo_sigle=0,
colo_valider=0,
colo_ordres=0,
colo_virer=0,
colo_grades=0
 WHERE compte='.$_SESSION['com_ID'];
    if(request($sql)){
      if(affected_rows()){
	request('UPDATE compte SET camp='.$demande[1]['sujet'].' WHERE ID='.$_SESSION['com_ID']);
	request('DELETE FROM demandes WHERE `type`=1 AND demandeur='.$_SESSION['com_ID']);
	// TODO : ajouter bidouille forum.
	// Recup des persos qui changent de camp
	$sql=my_fetch_array('SELECT ID FROM persos WHERE compte='.$_SESSION['com_ID']);
	for($i=1;$i<=$sql[0];$i++){
	  update_droits_forum($sql[$i]['ID']);
	}
      }
    }
  }
}


if(isset($_POST['profil_ok'])){
  $ok=1;
  if($_POST['new_pass']!=$_POST['conf_pass']){
    $ok=0;
    erreur(0,'Nouveau mot de passe et confirmation diff&eacute;rents.');
  }
  if(isset($_POST['mail'])&&$_POST['mail']&&(!ereg(".+@.+\..+",$_POST['mail']))){
    $ok=0;
    erreur(0,'Adresse mail non conforme.');
  }
  if(isset($_POST['skin']) &&
     (!is_numeric($_POST['skin']) ||
      !exist_in_db('SELECT ID FROM skins WHERE ID=\''.$_POST['skin'].'\''))){
    $ok=0;
    erreur(0,'Skin inconnu.');
  }
  if($ok){
    $i=0;
    $update='';
    if($_POST['new_pass']){
      $update='`pass`="'.sha1(strtolower(trim($_SESSION['com_login']).trim(post2text($_POST['conf_pass'])))).'"';
      $i++;
    }
    if(isset($_POST['mail'])&&$_POST['mail']){
      if($i){
	$update.=', ';
      }
      $update.='`mail`="'.post2bdd($_POST['mail']).'"';
      $i++;
    }
    if(isset($_POST['skin'])&&$_POST['skin']){
      if($i){
	$update.=', ';
      }
      $update.='`skin`='.$_POST['skin'];
      $i++;
    }
    if($i){
      request('UPDATE `compte` SET  '.$update.' WHERE `ID`='.$_SESSION['com_ID'].' AND `pass`="'.sha1(strtolower(trim($_SESSION['com_login']).trim(post2text($_POST['old_pass'])))).'" LIMIT 1');
      if(!affected_rows()){
	$ok=0;
	erreur(0,'Mot de passe erron&eacute;.');
      }
      else if($_POST['new_pass']){
	add_message(4,'Changements effectu&eacute;s');
	    //forum_mod_pass($_SESSION['com_ID'],sha1($_POST['conf_pass']));
      }
    }
  }
}

$affOptions=array('portee'=>0,
		  'hauteur'=>0,
		  'qgbloc'=>0,
		  'qgutil'=>0,
		  'lite'=>0,
		  'tag'=>1,
		  'smiley'=>1);
if(!empty($_POST['affichage_ok'])){
  $sql='';
  foreach($affOptions as $option=>$select){
    if(!empty($sql)){
      $sql.=', ';
    }
    if(isset($_POST[$option])){
      if($select==0){
	$_SESSION['affichage'][$option]=1;
      }
      else if(is_numeric($_POST[$option])){
	$_SESSION['affichage'][$option]=$_POST[$option];
      }
    }
    else{
      $_SESSION['affichage'][$option]=0;
    }
    $sql.='indic_'.$option.'='.$_SESSION['affichage'][$option];
  }
  request('UPDATE compte SET '.$sql.' WHERE ID='.$_SESSION['com_ID']);
}

$skins=my_fetch_array('SELECT ID, CONCAT(nom,CONCAT(\' (par \',CONCAT(auteur,\')\'))),repertoire
FROM skins
ORDER BY nom ASC');
if($skins[0]>1){
  for($i=1;$i<=$skins[0];$i++){
    if($skins[$i]['repertoire']==$_SESSION['skin']){
      $_POST['skin']=$skins[$i]['ID'];
    }
  }
}
$smileys=array(4,
	       array(0,'Smiley'),
	       array(1,'Arme uniquement'),
	       array(2,'Nom de l\'arme uniquement'),
	       array(3,'Rien'));
$tags=array(8,
	    array(0,'Rien'),
	    array(1,'Matricule'),
	    array(2,'Groupe'),
	    array(3,'Groupe et matricule'),
	    array(4,'Camp'),
	    array(5,'Camp et matricule'),
	    array(6,'Camp et groupe'),
	    array(7,'Camp, groupe et matricule'));


$forcer=empty($_COOKIE['accountInfo']) && empty($_COOKIE['accountTransfuge']);
echo'<ul id="menuHaut" class="account">
'.showMenuItem('accountAff','Affichage',$forcer).'
'.showMenuItem('accountInfo','Informations g&eacute;n&eacute;rales',false).'
'.showMenuItem('accountTransfuge','Transfuge',false).'
</ul>
<div id="accountAffFrame" class="framed account">
 <form method="post" action="account.php">
  <ul>
   <li>
    <label>Port&eacute;e : 
     <input type="checkbox" name="portee"'.($_SESSION['affichage']['portee']==1?' checked="checked"':'').' />
    </label>
   </li>
 <li><label>Hauteurs : <input type="checkbox" name="hauteur"'.($_SESSION['affichage']['hauteur']==1?' checked="checked"':'').' /></label></li>
 <li><label>QG, blocage : <input type="checkbox" name="qgbloc"'.($_SESSION['affichage']['qgbloc']==1?' checked="checked"':'').' /></label></li>
 <li><label>QG, utilisation : <input type="checkbox" name="qgutil"'.($_SESSION['affichage']['qgutil']==1?' checked="checked"':'').' /></label></li>
 <li><label>Affichage l&eacute;ger : <input type="checkbox" name="lite"'.($_SESSION['affichage']['lite']==1?' checked="checked"':'').' /></label></li>
<li>'.form_select('Smileys : ','smiley',$smileys,'',$_SESSION['affichage']['smiley']).'</li>
<li>'.form_select('Tags : ','tag',$tags,'',$_SESSION['affichage']['tag']).'</li>
  </ul>
  <p>
   <input type="submit" value="Ok" name="affichage_ok" />
  </p>
 </form>
</div>
<div id="accountInfoFrame" class="framed account">
 <form method="post" action="account.php">
  <p>',form_text('Adresse e-mail: ','mail','',''),'<br />
',form_password('Nouveau mot de passe: ','new_pass',''),'<br />
',form_password('Confirmation: ','conf_pass',''),'<br />
',form_select('Skin: ','skin',$skins,''),'<br />
',form_password('Ancien mot de passe: ','old_pass',''),' (obligatoire pour valider vos changements de mot de passe).<br />
',form_submit('profil_ok','Ok'),'
  </p>
 </form> 
</div>
<div id="accountTransfugeFrame" class="framed account">
';
if(!$transfugeable){
  echo' <p>Il faut que vos persos soient hors mission pour pouvoir changer de camp.</p>';
}
else{
  // Recuperation d'une possible demande deja faite.
  $demande_actuelle=my_fetch_array('SELECT *
 FROM demandes
 WHERE `type`=1
 AND demandeur='.$_SESSION['com_ID'].'
 ORDER BY ID DESC 
 LIMIT 1');
  if($demande_actuelle[0]){
    echo'<p>&Eacute;tat de votre demande actuelle :<br />
Acceptation par le camp d\'accueil : '.($demande_actuelle[1]['validation_1']?'ok':'non').'<br />
Acceptation par les MJs : '.($demande_actuelle[1]['validation_2']?'ok, division de la vitesse de progression par '.$demande_actuelle[1]['diminution_VS'].' pendant '.$demande_actuelle[1]['duree_effet'].' jours.':'non').'</p>
<form method="post" action="account.php">
 <p>
 '.form_check('Annuler la demande : ','annule_transfuge').form_submit('annule_ok','Ok').'
 </p>
</form>
';
    if($demande_actuelle[1]['validation_2'] && $demande_actuelle[1]['validation_1']){
      echo'<form method="post" action="account.php">
 <p>
 '.form_check('Accepter les conditions : ','valide_transfuge').form_submit('valide_ok','Ok').'
 </p>
</form>
';
    }
  }
  else{
    // Recuperation des camps ouverts
    $camps=my_fetch_array('SELECT ID,nom
FROM camps
 WHERE ouvert=1
AND ID!='.$perso['armee']);
    if(!$camps[0]){
      echo'<p>Aucun camp n\'est actuellement ouvert.</p>';
    }
    else{
      echo'<form method="post" action="account.php">
<p>Vous pouvez, si vous le souhaitez, demander &agrave; changer de camp. Votre demande sera trait&eacute;e par les g&eacute;n&eacute;raux du camp choisi et les MJs. S\'ils acceptent votre demande, il ne vous restera plus qu\'&agrave; valider leurs conditions.</p>
<p>
'.form_select('Destination : ','transfuge_camp',$camps,'').'
'.form_textarea("Texte RP :<br />","transfuge_RP",20,80).'
'.form_textarea("Raisons HRP :<br />","transfuge_HRP",20,80).'
',form_password('Mot de passe: ','transfuge_pass',''),'
',form_submit('transfuge_ok','Ok'),'
</p>
</form>
';
    }
  }
}
echo'</div>
';
?>