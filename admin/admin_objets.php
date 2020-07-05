<?php
require('../objets/bdd.php');
$connexion = new comBdd('','','','','',$GLOBALS['db']);

// Creation d'un objet.
if(!empty($_POST['new_nom']) &&
   !empty($_POST['new_visibilite']) &&
   is_numeric($_POST['new_visibilite']) &&
   !empty($_POST['new_poids']) &&
   is_numeric($_POST['new_prix']) &&
   is_numeric($_POST['new_poids'])){
  $sql='INSERT INTO objets
 (nom,poids,description,story,visible,visibilite,achetable,prix)
 VALUES("'.post2bdd($_POST['new_nom']).'",
 '.$_POST['new_poids'].',
 "'.post2bdd($_POST['new_desc']).'",
 "'.post2bdd($_POST['new_ldesc']).'",
 '.(isset($_POST['new_visible'])?'1':'0').' ,
 '.$_POST['new_visibilite'].',
 '.(isset($_POST['new_achetable'])?'1':'0').' ,
 '.$_POST['new_prix'].'
 )';
  if($connexion->request($sql,'insert')){
    echo '<p>Objet ajout&eacute;.</p>';
    $id = $connexion->lastId();
    if(uploadObjectImage($id, 'new_objet_image')){
      echo '<p>Image uploadée</p>';
    }
  }else{
    echo '<p>Erreur lors de l\'ajout.</p>';
  }
}

// Positionnement d'un objet.
if(!empty($_POST['pos_id']) && is_numeric($_POST['pos_id']) &&
   !empty($_POST['pos_map']) && is_numeric($_POST['pos_map']) &&
   !empty($_POST['pos_X']) && is_numeric($_POST['pos_X']) &&
   !empty($_POST['pos_nbr']) && is_numeric($_POST['pos_nbr']) &&
   !empty($_POST['pos_Y']) && is_numeric($_POST['pos_Y'])){
  $sql='INSERT INTO equipement
 (X,Y,map,`type`,objet_ID,nombre)
 VALUES('.$_POST['pos_X'].',
'.$_POST['pos_Y'].',
'.$_POST['pos_map'].',
3,
'.$_POST['pos_id'].',
'.$_POST['pos_nbr'].')';
  if($connexion->request($sql,'insert')){
    echo '<p>Objet(s) positionn&eacute;(s).</p>';
  }else{
    echo '<p>Erreur lors du positionnement.</p>';
  }
}

// On verifie si on a change les donnees d'un objet a terre.
foreach($_POST AS $key=>$value){
  if(preg_match('`mod\_([0-9]+)`',$key,$id)){
    $id=$id[1];
    if(isset($_POST['nbr_'.$id],
	     $_POST['X_'.$id],
	     $_POST['Y_'.$id],
	     $_POST['map_'.$id]) &&
       is_numeric($_POST['nbr_'.$id]) &&
       is_numeric($_POST['X_'.$id]) &&
       is_numeric($_POST['Y_'.$id]) &&
       is_numeric($_POST['map_'.$id])&&
       empty($_POST['perso_'.$id]) ||
       is_numeric($_POST['perso_'.$id])){
      $sql = 'SELECT nombre, X, Y, map, possesseur
FROM equipement
WHERE equipement.type = 3
AND ID = '.$id;
      $objet = $connexion->fetch($sql);
      if(empty($objet)){
	echo '<p>Erreur : objet inexistant.</p>';
      }
      else{
	if(empty($_POST['perso_'.$id])){
	  $sql='UPDATE equipement
SET X='.$_POST['X_'.$id].',
Y='.$_POST['Y_'.$id].',
map='.$_POST['map_'.$id].',
possesseur=0,
nombre='.$_POST['nbr_'.$id].'
WHERE ID='.$id;
	}else{
	  $sql='UPDATE equipement
SET X=0,
Y=0,
map=0,
possesseur='.$_POST['perso_'.$id].',
nombre='.$_POST['nbr_'.$id].'
WHERE ID='.$id;
	}
	if($connexion->request($sql,'update')){
	  echo'<p>Objet modifi&eacute;.</p>';
	}
	else{
	  echo'<p>Erreur SQL lors de la modification de l\'objet.</p>';
	}
      }
    }
  }
  else if(preg_match('`supp\_([0-9]+)`',$key,$id)){
    $id=$id[1];
    if(!isset($_POST['conf_'.$id])){
	echo'<p>Vous devez confirmer la suppression de l\'objet en cochant la case &agrave; c&ocirc;t&eacute; du bouton de suppression.</p>';
    }
    else{
      $sql='DELETE FROM equipement WHERE equipement.type=3 AND ID='.$id;
      if($connexion->request($sql,'delete')){
	echo'<p>Objet supprim&eacute;.</p>';
      }
      else{
	echo'<p>Erreur SQL lors de la suppression de l\'objet.</p>';
      }
    }
  }
}

if(!empty($_POST['see_ok'])){
  $objets = my_fetch_array('SELECT equipement.ID,
objets.nom,
equipement.nombre,
equipement.X,
equipement.Y,
equipement.map AS map,
persos.ID AS ID_perso,
persos.X AS perso_X,
persos.Y AS perso_Y,
persos.map AS perso_map
FROM equipement
INNER JOIN objets
ON objets.ID =equipement.objet_ID
LEFT OUTER JOIN cartes
ON cartes.ID = equipement.map
LEFT OUTER JOIN persos
ON persos.ID = equipement.possesseur
WHERE equipement.type=3');
  if($objets[0]){
    echo'<form method="post" action="anim.php?admin_objets">
<p>Si un matricule est sp&eacute;cifi&eacute;, toute information de positionnement sera omis.
<input type="hidden" name="see_ok" value="1" />
</p>
<table>
 <tr>
  <th>Nom</th>
  <th>Nombre</th>
  <th>X</th>
  <th>Y</th>
  <th>Map</th>
  <th>Possesseur</th>
  <th>Modifier</th>
  <th>Supprimer</th>
 </tr>
';
    $maps=my_fetch_array('SELECT ID,nom FROM cartes ORDER BY nom ASC');
    $maps[0]++;
    $maps[$maps[0]]=array(0,'Hors map');
    for($i=1;$i<=$objets[0];$i++){
      $X=$objets[$i]['X'];
      $Y=$objets[$i]['Y'];
      $map=$objets[$i]['map'];
      if($objets[$i]['ID_perso']){
	$X=$objets[$i]['perso_X'];
	$Y=$objets[$i]['perso_Y'];
	$map=$objets[$i]['perso_map'];
      }
      echo' <tr>
  <td>'.bdd2html($objets[$i]['nom']).'</td>
  <td>'.form_text('','nbr_'.$objets[$i]['ID'],'3','',$objets[$i]['nombre']).'</td>
  <td>'.form_text('','X_'.$objets[$i]['ID'],'3','',$X).'</td>
  <td>'.form_text('','Y_'.$objets[$i]['ID'],'3','',$Y).'</td>
  <td>'.form_select('','map_'.$objets[$i]['ID'],$maps,'',$map).'</td>
  <td>'.form_text('','perso_'.$objets[$i]['ID'],'5','',$objets[$i]['ID_perso']).'</td>
  <td>'.form_submit('mod_'.$objets[$i]['ID'],'Modifier').'</td>
  <td>'.form_check('','conf_'.$objets[$i]['ID']).' '.form_submit('supp_'.$objets[$i]['ID'],'Supprimer').'</td>
 </tr>
';
    }
    echo'</table>
</form>
';
  }
}

// Modification d'un objet
if(!empty($_POST['mod_obj_ok']) &&
   !empty($_POST['mod_objet_id']) &&
   is_numeric($_POST['mod_objet_id'])){

  $sql='UPDATE objets
SET nom="'.post2bdd($_POST['mod_objet_nom']).'",
poids='.$_POST['mod_objet_poids'].',
description="'.post2bdd($_POST['mod_objet_desc']).'",
story="'.post2bdd($_POST['mod_objet_ldesc']).'",
visible='.(isset($_POST['mod_objet_visible'])?'1':'0').',
visibilite='.$_POST['mod_objet_visibilite'].',
achetable='.(isset($_POST['mod_objet_achetable'])?'1':'0').',
prix='.$_POST['mod_objet_prix'].',
WHERE objets.ID='.$_POST['mod_objet_id'];

  if(uploadObjectImage($_POST['mod_objet_id'], 'mod_objet_image')){
    echo '<p>Image uploadée</p>';
  }
  if($connexion->request($sql,'update')){
    echo '<p>Objet modifi&eacute;.</p>';
  }else{
    echo '<p>Erreur lors de la modification.</p>';
  }
}

$maps=my_fetch_array('SELECT ID,nom FROM cartes ORDER BY nom ASC');
$objets=my_fetch_array('SELECT ID,nom FROM objets ORDER BY nom ASC');

$_POST['pos_nbr']=1;

echo'<script type="text/javascript" src="scripts/admin.js"></script>
<form method="post" action="anim.php?admin_objets">
<h2>Stats des objet</h2>
<p>
'.form_submit("see_ok","Voir").'
</p>
</form>

<form method="post" action"anim.php?admin_objets">
<h2>Positionner un objet</h2>
<p>
'.form_text("Nombre : ","pos_nbr","3","").'
'.form_select(' Objet : ','pos_id',$objets,'').'<br />
'.form_select('Carte : ','pos_map',$maps,'').'
'.form_text(" X : ","pos_X","4","").'
'.form_text(" Y : ","pos_Y","4","").'<br :>
'.form_submit("po_ok","Ok").'
</p>
</form>

<form method="post" action"anim.php?admin_objets" enctype="multipart/form-data">
<h2>Cr&eacute;er un objet</h2>
<p>'.form_text("Nom : ","new_nom","","").'<br />
'.form_text("Poids (en kg): ","new_poids","5","").'<br />
'.form_text("Visible &agrave; : ","new_visibilite","3","").'<br />
'.form_check("Visible quand port&eacute; ?","new_visible").'<br />
'.form_check("Achetable ?","new_achetable").'<br />
'.form_text("Prix  : ","new_prix","5","").'<br />
'.form_image("Image : ","new_objet_image").'<br />
'.form_textarea("Description courte (255 car. max)<br />","new_desc","5","60").'<br />
'.form_textarea("Description longue<br />","new_ldesc","10","60").'<br />
'.form_submit("new_obj_ok","Cr&eacute;er").'
</p>
</form>

<form method="post" action"anim.php?admin_objets" enctype="multipart/form-data">
<h2>Modifier un objet</h2>
<p>
'.form_select('Objet : ','mod_objet_id',$objets,'LoadXML(\'objet\',this.value,\'mod\');').'
'.form_text("Nom : ","mod_objet_nom","","").'<br />
'.form_text("Poids (en kg): ","mod_objet_poids","5","").'<br />
'.form_image("Image : ","mod_objet_image").'<br />
'.form_text("Visible &agrave; : ","mod_objet_visibilite","3","").'<br />
'.form_check("Visible quand port&eacute; ?","mod_objet_visible").'<br />
'.form_check("Achetable ?","nmod_objet_achetable").'<br />
'.form_text("Prix  : ","mod_objet_prix","5","").'<br />
'.form_textarea("Description courte (255 car. max)<br />","mod_objet_desc","5","60").'<br />
'.form_textarea("Description longue<br />","mod_objet_ldesc","10","60").'<br />
'.form_submit("mod_obj_ok","Modifier").'
</p>
</form>
';

/**
 * Uploade une image pour un objet si besoin
 *
 * @param int ID d l'objet
 * @param nom du champ qui servait à uploader l'image
 */
function uploadObjectImage($id, $champ = 'new_objet_image'){
  print_r($_FILES);
  if(!empty($id) && !empty($_FILES[$champ])){
    if (!is_uploaded_file($_FILES[$champ]['tmp_name'])){
      erreur(0,"Erreur d'upload de l'image ou pas de nouvelle image uploadée.");
      return 0;
    }
    if(!in_array($_FILES[$champ]['type'], array("image/gif"))){
      erreur(0,"Mauvais type de fichier(".$_FILES[$champ]['type']."), il faut du gif !");
      return 0;
    }
    if(!move_uploaded_file($_FILES[$champ]['tmp_name'], "images/objets/$id.gif")){
      erreur(0,"Erreur de déplacement de l'image.");
      return 0;
    }
    return 1;
  }
  return 0;
}
?>
