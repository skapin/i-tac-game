<?php
if(!empty($_GET['act']) &&
   $_GET['act'] == 'addBM' &&
   !empty($_GET['id']) &&
   is_numeric($_GET['id']) &&
   !empty($_SESSION['com_perso'])){
  // On verifie si on peut ajouter ce texte.
    $texte=my_fetch_array('SELECT rp_textes.ID
FROM rp_textes
LEFT JOIN rp_perms
  ON rp_textes.ID=rp_perms.texteID
WHERE rp_textes.statut=1
AND (rp_textes.type & 1
OR rp_textes.auteur='.$perso.'
OR rp_perms.camp='.$camp.'
OR rp_perms.compa='.$compa.'
OR rp_perms.perso='.$perso.')
AND rp_textes.ID='.$_GET['id']);
    if(!$texte[0]){
      add_message(1,'Texte inconnu');
    }
    else{
      $bm=my_fetch_array('SELECT persoID FROM rp_bookmarks
WHERE texteID='.$_GET['id'].'
 AND persoID='.$_SESSION['com_perso']);
      if(!$bm[0]){
	request('INSERT INTO rp_bookmarks(persoID,texteID)
VALUES('.$_SESSION['com_perso'].','.$_GET['id'].')');
	add_message(1,'Favori ajout&eacute;.');
      }
    }
}

if(!empty($_POST['avatar']) &&
   !empty($_POST['changeAvatar'])){
  request('UPDATE persos SET rp_avatar="'.post2bdd($_POST['avatar']).'" WHERE ID='.$_SESSION['com_perso']);
  if(affected_rows()){
    add_message(1,'Avatar chang&eacute;.');
  }
}
if(!empty($_POST['rp_desc']) &&
   !empty($_POST['changeDesc'])){
  request('UPDATE persos SET rp_desc="'.post2bdd($_POST['rp_desc']).'" WHERE ID='.$_SESSION['com_perso']);
  if(affected_rows()){
    add_message(1,'Description chang&eacute;e.');
  }
}
if(!empty($_POST['rp_bio']) &&
   !empty($_POST['changeBio'])){
  request('UPDATE persos SET rp_bio="'.post2bdd($_POST['rp_bio']).'" WHERE ID='.$_SESSION['com_perso']);
  if(affected_rows()){
    add_message(1,'Biographie chang&eacute;e.');
  }
}

if(!empty($_POST['supBm']) &&
   !empty($_POST['bmId']) &&
   is_numeric($_POST['bmId'])){
  request('DELETE FROM rp_bookmarks WHERE persoID='.$_SESSION['com_perso'].'
AND texteID='.$_POST['bmId']);
  if(affected_rows()){
    add_message(1,'Bookmark supprim&eacute;.');
  }
}


$mafiche=getFiche($_SESSION['com_perso']);
echo' <h2>Votre fiche</h2>
<h3>Avatar</h3>
<form method="post" action="rp.php">
 <p>
';
if(!empty($mafiche['rp_avatar'])){
  echo'  <img src="'.bdd2value($mafiche['rp_avatar']).'" alt="Votre avatar" /><br />';
}
echo'  <label for="avatar">Adresse d\'un nouvel avatar :
   <input type="text" id="avatar" name="avatar" />
  </label>
  <input type="submit" value="changer" name="changeAvatar" />
 </p>
</form>
<h3>Description</h3>
<form method="post" action="rp.php">
 <p>
  <textarea name="rp_desc" class="small">'.bdd2text($mafiche['rp_desc']).'</textarea>
  <input type="submit" value="changer" name="changeDesc" />
 </p>
</form>
<h3>Biographie</h3>
<form method="post" action="rp.php">
 <p>
  <textarea name="rp_bio" class="small">'.bdd2text($mafiche['rp_bio']).'</textarea>
  <input type="submit" value="changer" name="changeBio" />
 </p>
</form>
<h3>Vos textes</h3>
<ul>
';
if(!empty($mafiche['textes'])){
  foreach($mafiche['textes'] AS $texte){
    echo'  <li>
   <a href="rp.php?act=lire&amp;id='.$texte['ID'].'">'.bdd2html($texte['titre']).'</a></li>
';
  }
}
echo' </ul>
<h3>Vos bookmarks</h3>
<ul>
';
if(!empty($mafiche['bookmarks'])){
  foreach($mafiche['bookmarks'] AS $texte){
    echo' <li>
  <form method="post" action="rp.php">
   <input type="hidden" name="bmId" value="'.$texte['ID'].'" />
   <a href="rp.php?act=lire&amp;id='.$texte['ID'].'">'.bdd2html($texte['titre']).'</a> de <a href="fiche.php?id='.$texte['auteur'].'">'.bdd2html($texte['nom']).'</a>
   <input type="submit" value="Supprimer" name="supBm" />
  </form>
 </li>
';
  }
}
echo' </ul>
</form>
';
?>