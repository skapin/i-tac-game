<?php
if(isset($_POST['new_terrain_ok']))
{
  $erreur=0;
  if(!isset($_POST['new_terrain_nom'])||!$_POST['new_terrain_nom'])
    {
      erreur(0,'il faut choisir un nom pour le terrain.');
      $erreur=1;
    }
  else if(exist_in_db("SELECT ID FROM terrains WHERE nom='".post2bdd($_POST['new_terrain_nom'])."'"))
    {
      erreur(0,'Nom déjà utilisé.');
      $erreur=1;
    }
  if($_POST['new_terrain_R']<0 || $_POST['new_terrain_R']>255 || $_POST['new_terrain_G']<0 || $_POST['new_terrain_G']>255 || $_POST['new_terrain_B']<0 || $_POST['new_terrain_B']>255 ||!is_numeric($_POST['new_terrain_R'])||!is_numeric($_POST['new_terrain_G'])||!is_numeric($_POST['new_terrain_B']))
    {
      erreur(0,'Valeurs R/G/B hors de l\'intervalle 0-255');
      $erreur=1;
    }
  else if(exist_in_db("SELECT ID FROM terrains WHERE R='$_POST[new_terrain_R]' AND G='$_POST[new_terrain_G]' AND B='$_POST[new_terrain_B]'"))
    {
      erreur(0,'Valeurs R/G/B déjà utilisées.');
      $erreur=1;
    }
  if(!(is_numeric($_POST['new_terrain_LE'])&&is_numeric($_POST['new_terrain_MO'])&&is_numeric($_POST['new_terrain_LO'])&&is_numeric($_POST['new_terrain_camou'])&&is_numeric($_POST['new_terrain_visible'])&&is_numeric($_POST['new_terrain_couvert'])&&is_numeric($_POST['new_terrain_malusP'])))
    {
      $erreur=1;
      erreur(0,'Valeur de type incorrect');
    }
  if(!$erreur)
    {
      request("INSERT
               INTO terrains (`nom`,
                              `R`,
                              `G`,
                              `B`,
                              `competence`,
                              `bloque_vue`,
                              `pontable`,
                              `prix_1`,
                              `prix_2`,
                              `prix_4`,
                              `style`,
                              `malus_camou`,
                              `visible`,
                              `visible_radar`,
                              `is_posable`,
                              `couvert`,
                              `malus_precision`,
                              `malus_vision`,
                              `malus_regen`)
                      VALUES ('".post2bdd($_POST['new_terrain_nom'])."',
                              '$_POST[new_terrain_R]',
                              '$_POST[new_terrain_G]',
                              '$_POST[new_terrain_B]',
                              '".post2bdd($_POST['new_terrain_comp'])."',
                              '".(isset($_POST['new_terrain_vue'])?'1':'0')."',
                              '".(isset($_POST['new_terrain_pont'])?'1':'0')."',
                              '$_POST[new_terrain_LE]',
                              '$_POST[new_terrain_MO]',
                              '$_POST[new_terrain_LO]',
                              '".post2bdd($_POST['new_terrain_style'])."',
                              '$_POST[new_terrain_camou]',
                              '$_POST[new_terrain_visible]',
                              '".(isset($_POST['new_terrain_radar'])?'1':'0')."',
                              '".(isset($_POST['new_terrain_pose'])?'1':'0')."',
                              '$_POST[new_terrain_couvert]',
                              '$_POST[new_terrain_malusP]',
                              '$_POST[new_terrain_malusV]',
                              '$_POST[new_terrain_malusR]')");
      if(last_id())
	{
	  write_terrains();
	  
	  // ici commence le log :
	  $detail = "<ul>
<li>ID : ".last_id()."</li>	  
<li>Nom : ".post2html($_POST['new_terrain_nom'])."</li>
<li>R : ".$_POST['new_terrain_R']."</li>
<li>G : ".$_POST['new_terrain_G']."</li>
<li>B : ".$_POST['new_terrain_B']."</li>
<li>Competence : ".post2html($_POST['new_terrain_comp'])."</li>
<li>Bloque_vue : ".(isset($_POST['new_terrain_vue'])?'1':'0')."</li>
<li>Pontable : ".(isset($_POST['new_terrain_pont'])?'1':'0')."</li>
<li>Prix_1 : ".$_POST['new_terrain_LE']."</li>
<li>Prix_2 : ".$_POST['new_terrain_MO']."</li>
<li>Prix_4 : ".$_POST['new_terrain_LO']."</li>
<li>Style : ".post2html($_POST['new_terrain_style'])."</li>
<li>Malus_camou : ".$_POST['new_terrain_camou']."</li>
<li>Visible : ".$_POST['new_terrain_visible']."</li>
<li>Visible_radar : ".(isset($_POST['new_terrain_radar'])?'1':'0')."</li>
<li>Is_posable : ".(isset($_POST['new_terrain_pose'])?'1':'0')."</li>
<li>Couvert : ".$_POST['new_terrain_couvert']."</li>
<li>Malus de précision precision : ".$_POST['new_terrain_malusP']."</li>
<li>Malus de vision : ".$_POST['new_terrain_malusV']."</li>
<li>Malus de regen : ".$_POST['new_terrain_malusR']."</li>
</ul>
";	  
	  console_log('anim_terrains',"Création du terrain : ".post2html($_POST['new_terrain_nom']),$detail,0,0);
	  // fin du log
	  
	}
      else
	erreur(0,"Impossible d'enregistrer le terrain en bdd.");
    }

}
if(isset($_POST['mod_terrain_ok']))
{
  $erreur=0;
  if(!isset($_POST['mod_terrain_nom'])||!$_POST['mod_terrain_nom'])
    {
      erreur(0,'il faut choisir un nom pour le terrain.');
      $erreur=1;
    }
  else if(exist_in_db("SELECT ID FROM terrains WHERE nom='".post2bdd($_POST['mod_terrain_nom'])."' AND ID!='$_POST[mod_terrain_id]'"))
    {
      erreur(0,'Nom déjà utilisé.');
      $erreur=1;
    }
  if($_POST['mod_terrain_R']<0 || $_POST['mod_terrain_R']>255 || $_POST['mod_terrain_G']<0 || $_POST['mod_terrain_G']>255 || $_POST['mod_terrain_B']<0 || $_POST['mod_terrain_B']>255 ||!is_numeric($_POST['mod_terrain_R'])||!is_numeric($_POST['mod_terrain_G'])||!is_numeric($_POST['mod_terrain_B']))
    {
      erreur(0,'Valeurs R/G/B hors de l\'intervalle 0-255');
      $erreur=1;
    }
  else if(exist_in_db("SELECT ID FROM terrains WHERE R='$_POST[mod_terrain_R]' AND R='$_POST[mod_terrain_G]' AND R='$_POST[mod_terrain_B]' AND ID!='$_POST[mod_terrain_id]'"))
    {
      erreur(0,'Valeurs R/G/B déjà utilisées.');
      $erreur=1;
    }
  if(!(is_numeric($_POST['mod_terrain_LE'])&&is_numeric($_POST['mod_terrain_MO'])&&is_numeric($_POST['mod_terrain_LO'])&&is_numeric($_POST['mod_terrain_camou'])&&is_numeric($_POST['mod_terrain_visible'])&&is_numeric($_POST['mod_terrain_couvert'])&&is_numeric($_POST['mod_terrain_malusP'])))
    {
      $erreur=1;
      erreur(0,'Valeur de type incorrect');
    }
  if(!$erreur)
    {
      request("UPDATE terrains
                     SET `nom`='".post2bdd($_POST['mod_terrain_nom'])."',
                         `R`='$_POST[mod_terrain_R]',
                         `G`='$_POST[mod_terrain_G]',
                         `B`='$_POST[mod_terrain_B]',
                         `competence`='".post2bdd($_POST['mod_terrain_comp'])."',
                         `bloque_vue`='".(isset($_POST['mod_terrain_vue'])?'1':'0')."',
                         `pontable`='".(isset($_POST['mod_terrain_pont'])?'1':'0')."',
                         `prix_1`='$_POST[mod_terrain_LE]',
                         `prix_2`='$_POST[mod_terrain_MO]',
                         `prix_4`='$_POST[mod_terrain_LO]',
                         `style`='".post2bdd($_POST['mod_terrain_style'])."',
                         `malus_camou`='$_POST[mod_terrain_camou]',
                         `visible`='$_POST[mod_terrain_visible]',
                         `visible_radar`='".(isset($_POST['mod_terrain_radar'])?'1':'0')."',
                         `is_posable`='".(isset($_POST['mod_terrain_pose'])?'1':'0')."',
                         `couvert`='$_POST[mod_terrain_couvert]',
                         `malus_precision`='$_POST[mod_terrain_malusP]',
                         `malus_vision`='$_POST[mod_terrain_malusV]',
                         `malus_regen`='$_POST[mod_terrain_malusR]' WHERE ID='$_POST[mod_terrain_id]'");
      if(affected_rows())
	{
	  write_terrains();
	  
	  // ici commence le log :
	  $detail = "<ul>
<li>ID : ".$_POST['mod_terrain_id']."</li>
<li>Nom : ".post2html($_POST['mod_terrain_nom'])."</li>
<li>R : ".$_POST['mod_terrain_R']."</li>
<li>G : ".$_POST['mod_terrain_G']."</li>
<li>B : ".$_POST['mod_terrain_B']."</li>
<li>Competence : ".post2html($_POST['mod_terrain_comp'])."</li>
<li>Bloque_vue : ".(isset($_POST['mod_terrain_vue'])?'1':'0')."</li>
<li>Pontable : ".(isset($_POST['mod_terrain_pont'])?'1':'0')."</li>
<li>Prix_1 : ".$_POST['mod_terrain_LE']."</li>
<li>Prix_2 : ".$_POST['mod_terrain_MO']."</li>
<li>Prix_4 : ".$_POST['mod_terrain_LO']."</li>
<li>Style : ".post2html($_POST['mod_terrain_style'])."</li>
<li>Malus_camou : ".$_POST['mod_terrain_camou']."</li>
<li>Visible : ".$_POST['mod_terrain_visible']."</li>
<li>Visible_radar : ".(isset($_POST['mod_terrain_radar'])?'1':'0')."</li>
<li>Is_posable : ".(isset($_POST['mod_terrain_pose'])?'1':'0')."</li>
<li>Couvert : ".$_POST['mod_terrain_couvert']."</li>
<li>Malus de précision precision : ".$_POST['mod_terrain_malusP']."</li>
<li>Malus de vision : ".$_POST['mod_terrain_malusV']."</li>
<li>Malus de regen : ".$_POST['mod_terrain_malusR']."</li>
</ul>
";	  
	  console_log('anim_terrains',"Modification du terrain : ".post2html($_POST['mod_terrain_nom']),$detail,0,0);
	  // fin du log
	}
      else
	erreur(0,"Impossible de modifier le terrain en bdd.");
    }

}
//*****************************************************************************
// Suppression d'un terrain.
//*****************************************************************************

else if(isset($_POST["del_terrain_ok"],$_POST['del_terrain_id'])&&is_numeric($_POST['del_terrain_id']))
{
  $terrain=my_fetch_array("SELECT `nom`,`ID`
                         FROM `terrains`
                         WHERE `ID`='$_POST[del_terrain_id]'
                         LIMIT 1");
  if($terrain[0])
    echo'<form method="post" action="anim.php?admin_terrains">
 <h3>Confirmation :</h3>
 <p>Êtes vous sûr de vouloir supprimer ce terrain ('.bdd2html($terrain[1][0]).')?<br />
'.form_hidden("id",$_POST['del_terrain_id']).'
'.form_submit("del_terrain_no","Non").'&nbsp;&nbsp;&nbsp;&nbsp;
'.form_submit("del_terrain_yes","Oui").' 
 </p>
 </form>
 <hr />
';
}
else if(isset($_POST["del_terrain_yes"],$_POST['id'])&&is_numeric($_POST['id']))
{
  $erreur=0;
  // On revérifie les données.
  $terrain=my_fetch_array("SELECT `nom`
                          FROM `terrains`
                          WHERE `ID`='$_POST[id]'
                          LIMIT 2");
  if($terrain[0]!=1)
    {
      erreur(0,'Terrain inconnu.');
      $erreur=1;
    }
  // Suppression de la base du terrain
  if(!$erreur)
    {
      request("DELETE
               FROM `terrains`
               WHERE `ID`='$_POST[id]'");
      if(affected_rows())
	{
	  // ici commence le log :
	  $detail = "<ul>
<li>Terrain supprimé : ".$terrain[1][0]." ($_POST[id])</li>
</ul>
"; 
      console_log('anim_terrains',"suppression du terrain d'ID ".$_POST['id'],$detail,0,0);
	  // fin du log 
		
	  request("OPTIMIZE TABLE `terrains`");
	  write_terrains();
	}
      else
	erreur(0,"Impossible de supprimer ce type de munitions.");
    }
}

//*****************************************************************************
// Préparation du formulaire.
//*****************************************************************************

require_once('../inits/terrains.php');
$script='<script type="text/javascript">
 function affiche_terrain()
 {
  if(!document.getElementById)
    return;
 ';
    $terrains=my_fetch_array("SELECT * FROM terrains ORDER BY ID ASC");
    echo'<table>
<tr><th>Terrain</th><th>R/G/B</th><th>competence</th><th>Bloque la vue ?</th><th>Pontable ?</th><th>Coût de base en armure légère</th><th>Coût de base en armure moyenne</th><th>Coût de base en armure lourde</th><th>Style</th><th>Malus au camouflage</th><th>Visible à</th><th>Visible au radar</th><th>Est un pont ?</th><th>Couvert</th><th>Malus en précision</th><th>Malus en vision<th><th>Malus de régénération</th></tr>
';
for($i=1;$i<=$terrains[0];$i++)
{
  echo'<tr><td>'.$terrains[$i]['nom'].'</td><td>'.$terrains[$i]['R'].'/'.$terrains[$i]['G'].'/'.$terrains[$i]['B'].'</td><td>'.$terrains[$i]['competence'].'</td><td>'.($terrains[$i]['bloque_vue']?'oui':'non').'</td><td>'.($terrains[$i]['pontable']?'oui':'non').'</td><td>'.$terrains[$i]['prix_1'].'</td><td>'.$terrains[$i]['prix_2'].'</td><td>'.$terrains[$i]['prix_4'].'</td><td>'.$terrains[$i]['style'].'</td><td>'.$terrains[$i]['malus_camou'].'</td><td>'.$terrains[$i]['visible'].'</td><td>'.($terrains[$i]['visible_radar']?'oui':'non').'</td><td>'.($terrains[$i]['is_posable']?'oui':'non').'</td><td>'.$terrains[$i]['couvert'].'</td><td>'.$terrains[$i]['malus_precision'].'</td><td>'.$terrains[$i]['malus_vision'].'</td><td>'.$terrains[$i]['malus_regen'].'</td></tr>
';
  $script.='  if(document.getElementById("mod_terrain_id").value=="'.$terrains[$i]["ID"].'")
    {
      nom="'.bdd2js($terrains[$i]["nom"]).'";
      R='.$terrains[$i]["R"].';
      G='.$terrains[$i]["G"].';
      B='.$terrains[$i]["B"].';
      comp="'.bdd2js($terrains[$i]["competence"]).'";
      vue='.$terrains[$i]["bloque_vue"].';
      pont='.$terrains[$i]["pontable"].';
      LE='.$terrains[$i]["prix_1"].';
      MO='.$terrains[$i]["prix_2"].';
      LO='.$terrains[$i]["prix_4"].';
      style="'.bdd2js($terrains[$i]["style"]).'";
      camou='.$terrains[$i]["malus_camou"].';
      visible='.$terrains[$i]["visible"].';
      radar='.$terrains[$i]["visible_radar"].';
      pose='.$terrains[$i]["is_posable"].';
      couvert='.$terrains[$i]["couvert"].';
      malus_precision='.$terrains[$i]["malus_precision"].';
      malus_vision='.$terrains[$i]["malus_vision"].';
      malus_regen='.$terrains[$i]["malus_regen"].';
    }
 ';
}
echo'</table>
';
$script.='  document.getElementById("mod_terrain_nom").value=nom;
  document.getElementById("mod_terrain_R").value=R;
  document.getElementById("mod_terrain_G").value=G;
  document.getElementById("mod_terrain_B").value=B;
  document.getElementById("mod_terrain_comp").value=comp;
  document.getElementById("mod_terrain_LE").value=LE;
  document.getElementById("mod_terrain_MO").value=MO;
  document.getElementById("mod_terrain_LO").value=LO;
  document.getElementById("mod_terrain_style").value=style;
  document.getElementById("mod_terrain_camou").value=camou;
  document.getElementById("mod_terrain_visible").value=visible;
  document.getElementById("mod_terrain_couvert").value=couvert;
  document.getElementById("mod_terrain_malusP").value=malus_precision;
  document.getElementById("mod_terrain_malusV").value=malus_vision;
  document.getElementById("mod_terrain_malusR").value=malus_regen;
  document.getElementById("mod_terrain_vue").checked=vue?"checked":"";
  document.getElementById("mod_terrain_radar").checked=radar?"checked":"";
  document.getElementById("mod_terrain_pose").checked=pose?"checked":"";
  document.getElementById("mod_terrain_pont").checked=pont?"checked":"";
 }
 '.(isset($_POST['mod_terrain_ok'])?'':'affiche_terrain();').'
 </script>
 ';

$competences=array(7,
		   array('plaine','Plaine'),
		   array('montagne','Montagne'),
		   array('foret','Forêt'),
		   array('desert','Désert'),
		   array('marais','Marais'),
		   array('nage','Nage'),
		   array('pont','Pont'));
echo'<h1>Création de terrains</h1>
<form method="post" action="anim.php?admin_terrains">
<p>
'.form_text('Nom : ','new_terrain_nom','','').'<br />
'.form_text('R : ','new_terrain_R','3','').'<br />
'.form_text('G : ','new_terrain_G','3','').'<br />
'.form_text('B : ','new_terrain_B','3','').'<br />
'.form_select('Compétence associée : ','new_terrain_comp',$competences,'').'<br />
'.form_check('Bloque la vision ?','new_terrain_vue').'<br />
'.form_check('Pontable ?','new_terrain_pont').'<br />
'.form_text('Coût Armure Légère : ','new_terrain_LE','3','').'<br />
'.form_text('Coût Armure Moyenne : ','new_terrain_MO','3','').'<br />
'.form_text('Coût Armure Lourde : ','new_terrain_LO','3','').'<br />
'.form_text('Style : ','new_terrain_style','3','').'<br />
'.form_text('Malus au camouflage : ','new_terrain_camou','3','').' %<br />
'.form_text('Visible à : ','new_terrain_visible','2','').' cases<br />
'.form_check('Visible au radar ?','new_terrain_radar').'<br />
'.form_check('Posable ?','new_terrain_pose').'<br />
'.form_text('Couvert : ','new_terrain_couvert','3','').'<br />
'.form_text('Malus en précision : ','new_terrain_malusP','3','').'<br />
'.form_text('Malus en vision : ','new_terrain_malusV','3','').'<br />
'.form_text('Malus de régénération : ','new_terrain_malusR','3','').'<br />
'.form_submit('new_terrain_ok','Créer').'
</p>
</form>
<h1>Modification de terrains</h1>
<form method="post" action="anim.php?admin_terrains">
<p>
'.form_select('Terrain : ','mod_terrain_id',$terrains,'affiche_terrain();').'<br />
'.form_text('Nom : ','mod_terrain_nom','','').'<br />
'.form_text('R : ','mod_terrain_R','3','').'<br />
'.form_text('G : ','mod_terrain_G','3','').'<br />
'.form_text('B : ','mod_terrain_B','3','').'<br />
'.form_select('Compétence associée : ','mod_terrain_comp',$competences,'').'<br />
'.form_check('Bloque la vision ?','mod_terrain_vue').'<br />
'.form_check('Pontable ?','mod_terrain_pont').'<br />
'.form_text('Coût Armure Légère : ','mod_terrain_LE','3','').'<br />
'.form_text('Coût Armure Moyenne : ','mod_terrain_MO','3','').'<br />
'.form_text('Coût Armure Lourde : ','mod_terrain_LO','3','').'<br />
'.form_text('Style : ','mod_terrain_style','3','').'<br />
'.form_text('Malus au camouflage : ','mod_terrain_camou','3','').' %<br />
'.form_text('Visible à : ','mod_terrain_visible','2','').' cases<br />
'.form_check('Visible au radar ?','mod_terrain_radar').'<br />
'.form_check('Posable ?','mod_terrain_pose').'<br />
'.form_text('Couvert : ','mod_terrain_couvert','3','').'<br />
'.form_text('Malus en précision : ','mod_terrain_malusP','3','').'<br />
'.form_text('Malus en vision : ','mod_terrain_malusV','3','').'<br />
'.form_text('Malus de régénération : ','mod_terrain_malusR','3','').'<br />
'.form_submit('mod_terrain_ok','Modifier').'
</p>
</form>
<h1>Supprimer un terrain :</h1>
<form method="post" action="anim.php?admin_terrains">
<p>
'.form_select("","del_terrain_id",$terrains,"").'<br />
'.form_submit("del_terrain_ok","Supprimer").'
</p>
</form>'.$script;

function write_terrains()
{
  $terrains=my_fetch_array("SELECT * FROM terrains ORDER BY ID ASC");
  $fichier='<?php
';
  for($i=1;$i<=$terrains[0];$i++)
    $fichier.='$GLOBALS["rgb_terrain"]['.$terrains[$i]['R'].']['.$terrains[$i]['G'].']['.$terrains[$i]['B'].']='.$terrains[$i]['ID'].';
';
  $fichier.='?>
'; 
  return fichier_create('../inits/terrains.php',$fichier,1);
}
?>
