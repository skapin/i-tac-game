<?php
$perso=my_fetch_array('SELECT armee,compagnie
FROM persos
WHERE ID='.$_SESSION['com_perso']);

$perso=$perso[1];
$perso['droits']=recupAdmin();

if(!empty($_POST['new_ok'])){
  $type=0;
  if(!empty($_POST['new_perm'])){
    $type=1;
  }
  if(!empty($_POST['new_open'])){
    $type+=2;
  }
  $statut=0;
  if(!empty($_POST['new_fin'])){
    $statut=1;
  }
  $auteur=$_SESSION['com_perso'];
  $spec_cat=0;
  $spec_camp=0;
  if(!empty($perso['droits']['anim_rp']) &&
     !empty($_POST['new_rp_cat']) &&
     is_numeric($_POST['new_rp_cat']) &&
     !empty($_POST['new_rp_camp']) &&
     is_numeric($_POST['new_rp_camp'])){
    $spec_camp=$_POST['new_rp_camp'];
    $spec_cat=$_POST['new_rp_cat'];
  }
  request('INSERT INTO rp_textes
 (ecrit,lastmod,`type`,auteur,statut,titre,`texte`,spec_cat,spec_camp) VALUES(NOW(),NOW(),
 '.$type.',
 '.$auteur.', 
 '.$statut.', 
 "'.post2bdd($_POST['new_titre']).'", 
 "'.post2bdd($_POST['new_text']).'",
'.$spec_cat.',
'.$spec_camp.')');
  $id=last_id();
  if($id){
    echo ' <p>Texte enregistr&eacute;.</p>
';
  }
}

if(!empty($_POST['mod_ok']) &&
   !empty($_POST['rp_id']) &&
   is_numeric($_POST['rp_id']) &&
   exist_in_db('SELECT ID FROM rp_textes WHERE ID='.$_POST['rp_id'].' AND auteur='.$_SESSION['com_perso'])){
  $type=0;
  if(!empty($_POST['mod_perm'])){
    $type=1;
  }
  if(!empty($_POST['mod_open'])){
    $type+=2;
  }
  $statut=0;
  if(!empty($_POST['mod_fin'])){
    $statut=1;
  }
  $auteur=$_SESSION['com_perso'];
  $spec_cat=0;
  $spec_camp=0;
  if(!empty($perso['droits']['anim_rp']) &&
     !empty($_POST['mod_rp_cat']) &&
     is_numeric($_POST['mod_rp_cat']) &&
     !empty($_POST['mod_rp_camp']) &&
     is_numeric($_POST['mod_rp_camp'])){
    $spec_cat=$_POST['mod_rp_cat'];
    $spec_camp=$_POST['mod_rp_camp'];
  }
  if(request('UPDATE rp_textes
SET `type`='.$type.',
auteur='.$auteur.',
lastmod=NOW(),
statut='.$statut.',
titre="'.post2bdd($_POST['mod_titre']).'",
texte="'.post2bdd($_POST['mod_text']).'",
spec_cat='.$spec_cat.',
spec_camp='.$spec_camp.'
WHERE ID='.$_POST['rp_id'])){
    echo ' <p>Texte modifi&eacute;.</p>
';
  }
}
if(!empty($_POST['rp_camp_ok']) &&
   !empty($_POST['rp_id']) &&
   is_numeric($_POST['rp_id']) &&
   exist_in_db('SELECT ID FROM rp_textes WHERE ID='.$_POST['rp_id'].' AND auteur='.$_SESSION['com_perso']) &&
   !empty($_POST['rp_camp']) &&
   is_numeric($_POST['rp_camp'])){
  $type = 0;
  if(!empty($_POST['rp_camp_see'])){
    $type=1;
  }
  if(!empty($_POST['rp_camp_suite'])){
    $type+=2;
  }
  if($type && 
     request('INSERT INTO rp_perms (texteID, camp, detail) VALUES('.$_POST['rp_id'].','.$_POST['rp_camp'].','.$type.')')){
    echo'<p>Autorisations ajout&eacute;es.</p>
';
  }
}
if(!empty($_POST['rp_compa_ok']) &&
   !empty($_POST['rp_id']) &&
   is_numeric($_POST['rp_id']) &&
   exist_in_db('SELECT ID FROM rp_textes WHERE ID='.$_POST['rp_id'].' AND auteur='.$_SESSION['com_perso']) &&
   !empty($_POST['rp_compa']) &&
   is_numeric($_POST['rp_compa'])){
  $type = 0;
  if(!empty($_POST['rp_compa_see'])){
    $type=1;
  }
  if(!empty($_POST['rp_compa_suite'])){
    $type+=2;
  }
  if($type && 
     request('INSERT INTO rp_perms (texteID, compa,detail) VALUES('.$_POST['rp_id'].','.$_POST['rp_compa'].','.$type.')')){
    echo'<p>Autorisations ajout&eacute;es.</p>
';
  }
}
if(!empty($_POST['rp_perso_ok']) &&
   !empty($_POST['rp_id']) &&
   is_numeric($_POST['rp_id']) &&
   exist_in_db('SELECT ID FROM rp_textes WHERE ID='.$_POST['rp_id'].' AND auteur='.$_SESSION['com_perso']) &&
   !empty($_POST['rp_perso']) &&
   is_numeric($_POST['rp_perso'])){
  $type = 0;
  if(!empty($_POST['rp_perso_see'])){
    $type=1;
  }
  if(!empty($_POST['rp_perso_suite'])){
    $type+=2;
  }
  if($type && 
     request('INSERT INTO rp_perms (texteID, perso, detail) VALUES('.$_POST['rp_id'].','.$_POST['rp_perso'].','.$type.')')){
    echo'<p>Autorisations ajout&eacute;es.</p>
';
  }
}
if(!empty($_POST['sup_perm']) &&
   !empty($_POST['sup_type']) &&
   !empty($_POST['sup_id']) &&
   !empty($_POST['sup_text_id']) &&
   is_numeric($_POST['sup_id']) &&
   is_numeric($_POST['sup_text_id']) &&
   exist_in_db('SELECT ID FROM rp_textes WHERE ID='.$_POST['sup_text_id'].' AND auteur='.$_SESSION['com_perso'])){
  $plop='';
  if($_POST['sup_type'] == 'camp'){
    $plop='camp';
  }
  if($_POST['sup_type'] == 'perso'){
    $plop='perso';
  }
  if($_POST['sup_type'] == 'compa'){
    $plop='compa';
  }
  if($plop){
    if(request('DELETE FROM rp_perms WHERE texteID='.$_POST['sup_text_id'].' AND '.$plop.'='.$_POST['sup_id'])){
      echo'<p>Autorisation supprim&eacute;e.</p>
';
    }
  }
}


 $texts=my_fetch_array('SELECT ID, titre,texte,`type`,statut FROM rp_textes WHERE auteur='.$_SESSION['com_perso'].' ORDER BY ecrit DESC');
$camps=my_fetch_array('SELECT ID,nom
FROM camps
 WHERE ouvert=1
OR ID='.$perso['armee']);
 $compas=my_fetch_array('SELECT ID,nom
FROM compagnies
WHERE ID!=1
AND valide=1');

echo' <h2>Nouveau texte</h2>
 <form method="post">
  <p>
   <label for="new_titre">Titre : </label>
   <input type="text" name="new_titre" id="new_titre" /><br />
   <label for="new_text">Texte : </label><br />
   <textarea id="new_text" name="new_text"></textarea><br />
   <label for="new_perm">Visible par tous ?</label>
   <input type="checkbox" name="new_perm" id="new_perm" checked="checked"/><br />
   <label for="new_fin">Fini ?</label>
   <input type="checkbox" name="new_fin" id="new_fin" /><br />
';
if($perso['droits']['anim_rp']){
  echo'   <label for="new_rp_cat">Dans la cat&eacute;gorie :</label>
   <select name="new_rp_cat" id="new_rp_cat">
    <option value="0"></option>
    <option value="1">Ambiances</option>
    <option value="2">Concepts</option>
   </select><br />
   <label for="new_rp_camp">Camp :</label>
   <select name="new_rp_camp" id="new_rp_camp">
    <option value="0"></option>
    <option value="1">Global</option>
    <option value="2">Enkis</option>
    <option value="3">Seln\'as</option>
    <option value="4">Lunmors</option>
   </select><br />
';
}
echo'   <input type="submit" name="new_ok" value="Enregistrer" />
  </p>
 </form>
 <h2>Modifier un texte</h2>
 <form method="post">
  <p>
   '.form_select('Texte : ','rp_id',$texts,'').'<br />
   <label for="mod_titre">Titre : </label>
   <input type="text" name="mod_titre" id="mod_titre" value="'.(empty($texts[1])?'':bdd2value($texts[1]['titre'])).'" /><br />
   <label for="mod_text">Texte : </label><br />
   <textarea id="mod_text" name="mod_text">'.(empty($texts[1])?'':bdd2html($texts[1]['texte'])).'</textarea><br />
   <label for="mod_perm">Visible par tous ?</label>
   <input type="checkbox" name="mod_perm" id="mod_perm" '.(empty($texts[1]) || !($texts[1]['type'] & 1)?'':'checked="checked" ').'/><br />
   <label for="mod_fin">Fini ?</label>
   <input type="checkbox" name="mod_fin" id="mod_fin" '.(empty($texts[1]['statut'])?'':'checked="checked" ').'/><br />
';
if($perso['droits']['anim_rp']){
  echo'   <label for="mod_rp_cat">Dans la cat&eacute;gorie :</label>
   <select name="mod_rp_cat" id="mod_rp_cat">
    <option value="0"></option>
    <option value="1">Ambiances</option>
    <option value="2">Concepts</option>
   </select><br />
   <label for="mod_rp_camp">Camp :</label>
   <select name="mod_rp_camp" id="mod_rp_camp">
    <option value="0"></option>
    <option value="1">Global</option>
    <option value="2">Enkis</option>
    <option value="3">Seln\'as</option>
    <option value="4">Lunmors</option>
   </select><br />
';
}
echo'   <input type="submit" name="mod_ok" value="Enregistrer" />
  </p>
  <h3>Droits particuliers</h3>
  <ul>
   <li>
    '.form_select('Camp : ','rp_camp',$camps,'').'
    '.form_check('voir ? ','rp_camp_see').'
    '.form_submit('rp_camp_ok','Ok').'
   </li>
   <li>
    '.form_select('Compagnie : ','rp_compa',$compas,'').'
    '.form_check('voir ? ','rp_compa_see').'
    '.form_submit('rp_compa_ok','Ok').'
   </li>
   <li>
    '.form_text('Perso : ','rp_perso','','').'
    '.form_check('voir ? ','rp_perso_see').'
    '.form_submit('rp_perso_ok','Ok').'
   </li>
  </ul>
 </form>
 <h4>Droits actuels</h4>
 <ul id="liste_perms">
';

/*
   <label for="mod_open">Ouvert &agrave tous ?</label>
   <input type="checkbox" name="mod_open" id="mod_open" '.(empty($texts[1]) || !($texts[1]['type'] & 2)?'':'checked="checked" ').'/><br />

*/
if(!empty($texts[0])){
  $perms=my_fetch_array('SELECT rp_perms.detail,
camps.nom AS nom_camp,
compagnies.nom AS nom_compa,
persos.nom AS nom_perso,
camps.ID AS ID_camp,
compagnies.ID AS ID_compa,
persos.ID AS ID_perso
FROM rp_perms
  LEFT JOIN camps
  ON camps.ID = rp_perms.camp
  LEFT JOIN compagnies
  ON compagnies.ID = rp_perms.compa
  LEFT JOIN persos
  ON persos.ID = rp_perms.perso
WHERE rp_perms.texteID='.$texts[1]['ID']);
  if(!empty($perms)){
    $detail='';
    $str='';
    $lnk='';
    for($i=1;$i<=$perms[0];$i++){
      if($perms[$i]['detail']==1){
	$detail='voir';
      }
      else if($perms[$i]['detail']==2){
	$detail='&eacute;crire une suite';
      }
      else if($perms[$i]['detail']==3){
	$detail='voir et &eacute;crire une suite';
      }

      if($perms[$i]['ID_camp']){
	$str='(Camp) '.bdd2js($perms[$i]['nom_camp']).' : '.$detail;
	$lnk='camp';
	$id=$perms[$i]['ID_camp'];
      }
      if($perms[$i]['ID_perso']){
	$str='(Perso) '.bdd2js($perms[$i]['nom_perso']).' ('.$perms[$i]['ID_perso'].') : '.$detail;
	$lnk='perso';
	$id=$perms[$i]['ID_perso'];
      }
      if($perms[$i]['ID_compa']){
	$str='(Compagnie) '.bdd2js($perms[$i]['nom_compa']).' : '.$detail;
	$lnk='compa';
	$id=$perms[$i]['ID_compa'];
      }
      echo'  <li><form method="post" action="rp.php">
<label>'.$str.' </label>
<input type="hidden" name="sup_type" value="'.$lnk.'" />
<input type="hidden" name="sup_id" value="'.$id.'" />
<input type="hidden" name="sup_text_id" value="'.$texts[1]['ID'].'" />
<input type="submit" name="sup_perm" value="Supprimer" />
</form>
</li>';
    }
  }
}
echo' </ul>
 <p id="debug"></p>
';

function saveScores($title,$text,$id,$del=false){
  $plop=explode(' ',$title);
  $scores=array();
  foreach($plop AS $mot){
    if(!empty($mot) && strlen($mot)>3){
      if(!empty($scores[$mot])){
	$scores[$mot]+=10;
      }
      else{
	$scores[$mot]=10;
      }
    }
  }
  $plop=explode(' ',$text);
  foreach($plop AS $mot){
    if(!empty($mot) && strlen($mot)>3){
      if(!empty($scores[$mot])){
	$scores[$mot]+=1;
      }
      else{
	$scores[$mot]=1;
      }
    }
  }

  if($del){
    request('DELETE FROM rp_search WHERE texte='.$id);
  }
  print_r($scores);
  foreach($scores AS $key=>$value){
    request('INSERT INTO rp_search
 (mot,texte,score)
 VALUES("'.$key.'",'.$id.','.ceil(1+10*log10($value)).')');
  }
}

?>