<h2>Gestion des armures</h2>
<?php
//*************************************************************************
// Création d'une armure
//*************************************************************************

if(isset($_POST["new_armure_ok"]))
{
  // Il semble qu'une armure soit à ajouter.
  // Vérifions les infos envoyées.
  $erreur=0;
  if(!(isset($_POST["new_armure_nom"])&&$_POST["new_armure_nom"]))
    {
      // Pas de nom, pas d'armure.
      erreur(0,"Il faut choisir un nom pour l'armure.");
      $erreur=1;
    }
  else
    {
      // On vérifie si le nom n'est pas déjà utilisé.
      if(exist_in_db("SELECT `ID`
                      FROM `armures`
                      WHERE `nom`='".post2bdd($_POST['new_armure_nom'])."'
                      LIMIT 1"))
	{
	  erreur(0,"Nom d'armure déjà utilisé.");
	  $erreur=1;
	}
    }
  // Puis on vérifie le reste :
  // type d'armure existant ?
  if(!(isset($_POST["new_armure_type"])&&($_POST["new_armure_type"]==1||$_POST["new_armure_type"]==2||$_POST["new_armure_type"]==4)))
    {
      erreur(0,"Type d'armure incorrect.");
      $erreur=1;
    }
  if(!(isset($_POST["new_armure_grade"])&&is_numeric($_POST["new_armure_grade"])&&($_POST["new_armure_grade"]>=0)&&($_POST["new_armure_grade"]<=13)))
    {
      erreur(0,"Grade non réglementaire.");
      $erreur=1;
    }
  if(!(isset($_POST["new_armure_visibilite"])&&is_numeric($_POST["new_armure_visibilite"])&&($_POST["new_armure_visibilite"]>=0)&&($_POST["new_armure_visibilite"]<=2)))
    {
      erreur(0,"Problème sur la visibilité de l'armure.");
      $erreur=1;
    }
  if(!isset($_POST["new_armure_PA"],$_POST["new_armure_PAc"],$_POST["new_armure_camou"],$_POST["new_armure_terrain"],$_POST["new_armure_precision"],$_POST["new_armure_critique"],$_POST["new_armure_capacite"],$_POST["new_armure_cac"])||!is_numeric($_POST["new_armure_PA"])||!is_numeric($_POST["new_armure_PAc"])||!is_numeric($_POST["new_armure_camou"])||!is_numeric($_POST["new_armure_terrain"])||!is_numeric($_POST["new_armure_precision"])||!is_numeric($_POST["new_armure_critique"])||!is_numeric($_POST["new_armure_capacite"])||!is_numeric($_POST["new_armure_cac"]))
    {
      erreur(0,"Une valeur est de type non numérique.");
      $erreur=1;
    }
  if(!$erreur)
    {
      // Pas d'erreur, on peut enregistrer l'armure.
      request("INSERT
               INTO `armures` (`nom`,
                               `initiales`,
                               `type`,
                               `PA`,
                               `PA_critiques`,
                               `malus_camou`,
                               `malus_terrain`,
                               `visibilite`,
                               `malus_critique`,
                               `bonus_precision`,
                               `desc`,
                               `grade`,
                               `capacite`,degatsCaC)
                        VALUES('".post2bdd($_POST['new_armure_nom'])."',
                               '".post2bdd($_POST['new_armure_init'])."',
                               '$_POST[new_armure_type]',
                               '$_POST[new_armure_PA]',
                               '$_POST[new_armure_PAc]',
                               '$_POST[new_armure_camou]',
                               '$_POST[new_armure_terrain]',
                               '$_POST[new_armure_visibilite]',
                               '$_POST[new_armure_critique]',
                               '$_POST[new_armure_precision]',
                               '".post2bdd($_POST['new_armure_desc'])."',
                               '$_POST[new_armure_grade]',
                               '$_POST[new_armure_capacite]',
'$_POST[new_armure_cac]')");
      $id=last_id();
      if(!$id)
	erreur(0,"Impossible d'enregistrer l'armure.");
      else
	{
	  // L'armure est enregistrée en bdd.
	  $detail="<ul>
<li>Nom : ".post2html($_POST['new_armure_nom'])."</li>
<li>Initiales : ".post2html($_POST['new_armure_init'])."</li>
<li>Type : $_POST[new_armure_type]</li>
<li>PA : $_POST[new_armure_PA]</li>
<li>PA critiques : $_POST[new_armure_PAc]</li>
<li>Malus de camouflage : $_POST[new_armure_camou]</li>
<li>Malus de terrain : $_POST[new_armure_terrain]</li>
<li>Visible par : $_POST[new_armure_visibilite]</li>
<li>Malus de critique : $_POST[new_armure_critique]</li>
<li>Bonus de précision : $_POST[new_armure_precision]</li>
<li>Description : <p>".post2html($_POST['new_armure_desc'])."</p></li>
<li>Grade nécessaire : $_POST[new_armure_grade]</li>
<li>Poids portable : $_POST[new_armure_capacite]</li>
</ul>
 ";
	  console_log('anim_armures',"Création de l'armure : ".post2bdd($_POST['new_armure_nom']),$detail,0,0);
	  $erreur=0;
	  foreach($_POST as $key=>$value)
	    {
	      if(ereg('new_armure_camp_[0-9]+',$key))
		{
		  $camp_id=explode("_",$key);
		  if(!request("INSERT
                               INTO `armures_camps` (`armure`,`camp`)
                                             VALUES ('$id','".$camp_id[3]."')"))
		    erreur(0,"Impossible de rendre disponible l'armure pour un camp.");
		}
	      $_POST[$key]="";
	    }
	}
    }
}

//*************************************************************************
// Modification d'une armure.
//*************************************************************************

else if(isset($_POST["mod_armure_ok"],$_POST["mod_armure_id"])&&is_numeric($_POST["mod_armure_id"]))
{
  // On souhaite modifier une armure.
  $erreur=0;
  if(!$_POST["mod_armure_id"])
    {
      erreur(0,"Il faut choisir une armure.");
      $erreur=1;
    }
  else
    {
      // Existe t'elle au moins ?
      if(!exist_in_db("SELECT `ID`
                       FROM `armures`
                       WHERE `ID`='$_POST[mod_armure_id]'
                       LIMIT 1"))
	{
	  erreur(0,"Armure inconnue.");
	  $erreur=1;
	}
    }
  if(!(isset($_POST["mod_armure_nom"])&&$_POST["mod_armure_nom"]))
    {
      erreur(0,"Il faut choisir un nom pour l'armure.");
      $erreur=1;
    }
  else
    {
      if(exist_in_db("SELECT `ID`
                      FROM `armures`
                      WHERE `nom`='".post2bdd($_POST['mod_armure_nom'])."'
                        AND `ID`!='$_POST[mod_armure_id]'
                      LIMIT 1"))
	{
	  erreur(0,"Nom d'armure déjà utilisé.");
	  $erreur=1;
	}
    }
  if(!(isset($_POST["mod_armure_type"])&&($_POST["mod_armure_type"]==1||$_POST["mod_armure_type"]==2||$_POST["mod_armure_type"]==4)))
    {
      erreur(0,"Type d'armure incorrect.");
      $erreur=1;
    }
  if(!(isset($_POST["mod_armure_grade"])&&is_numeric($_POST["mod_armure_grade"])&&($_POST["mod_armure_grade"]>=0)&&($_POST["mod_armure_grade"]<=13)))
    {
      erreur(0,"Grade non réglementaire.");
      $erreur=1;
    }
  if(!(isset($_POST["mod_armure_visibilite"])&&is_numeric($_POST["mod_armure_visibilite"])&&($_POST["mod_armure_visibilite"]>=0)&&($_POST["mod_armure_visibilite"]<=2)))
    {
      erreur(0,"Problème sur la visibilité de l'armure.");
      $erreur=1;
    }
  if(!isset($_POST["mod_armure_PA"],$_POST["mod_armure_PAc"],$_POST["mod_armure_camou"],$_POST["mod_armure_terrain"],$_POST["mod_armure_precision"],$_POST["mod_armure_critique"],$_POST["mod_armure_capacite"],$_POST["mod_armure_cac"])||!is_numeric($_POST["mod_armure_PA"])||!is_numeric($_POST["mod_armure_PAc"])||!is_numeric($_POST["mod_armure_camou"])||!is_numeric($_POST["mod_armure_terrain"])||!is_numeric($_POST["mod_armure_precision"])||!is_numeric($_POST["mod_armure_critique"])||!is_numeric($_POST["mod_armure_capacite"])||!is_numeric($_POST["mod_armure_cac"]))
    {
      erreur(0,"Une valeur est de type non numérique.");
      $erreur=1;
    }
  if(!$erreur)
    {
      if(!request("DELETE
                   FROM `armures_camps`
                   WHERE `armure`='$_POST[mod_armure_id]'"))
	{
	  erreur(0,"Impossible de modifier les camps ayant accés à cette armure.");
	}
      else
	{
	  $detail="<ul>
<li>Nom : ".post2html($_POST['mod_armure_nom'])."</li>
<li>Initiales : ".post2html($_POST['mod_armure_init'])."</li>
<li>Type : $_POST[mod_armure_type]</li>
<li>PA : $_POST[mod_armure_PA]</li>
<li>PA critiques : $_POST[mod_armure_PAc]</li>
<li>Malus de camouflage : $_POST[mod_armure_camou]</li>
<li>Malus de terrain : $_POST[mod_armure_terrain]</li>
<li>Visible par : $_POST[mod_armure_visibilite]</li>
<li>Malus de critique : $_POST[mod_armure_critique]</li>
<li>Bonus de précision : $_POST[mod_armure_precision]</li>
<li>Description : <p>".post2html($_POST['mod_armure_desc'])."</p></li>
<li>Grade nécessaire : $_POST[mod_armure_grade]</li>
<li>Poids portable : $_POST[mod_armure_capacite]</li>
</ul>
";
	  console_log('anim_armures',"Modification d'une armure, nouveau nom : ".post2bdd($_POST['mod_armure_nom']),$detail,0,0);
	  request("OPTIMIZE TABLE `armures_camps`");
	  foreach($_POST as $key=>$value)
	    if(ereg('mod_armure_camp_[0-9]+',$key))
	      {
		$camp_id=explode("_",$key);
		if(!request("INSERT
                             INTO `armures_camps` (`armure`,`camp`)
                                           VALUES ('$_POST[mod_armure_id]','".$camp_id[3]."')"))
		  erreur(0,"Impossible de rendre disponible cette armure à un camp.");
	      }
	  request("UPDATE `armures`
                   SET `nom`='".post2bdd($_POST['mod_armure_nom'])."',
                       `initiales`='".post2bdd($_POST['mod_armure_init'])."',
                       `type`='$_POST[mod_armure_type]',
                       `PA`='$_POST[mod_armure_PA]',
                       `PA_critiques`='$_POST[mod_armure_PAc]',
                       `malus_camou`='$_POST[mod_armure_camou]',
                       `malus_terrain`='$_POST[mod_armure_terrain]',
                       `malus_critique`='$_POST[mod_armure_critique]',
                       `bonus_precision`='$_POST[mod_armure_precision]',
                       `desc`='".post2bdd($_POST['mod_armure_desc'])."',
                       `visibilite`='$_POST[mod_armure_visibilite]',
                       `capacite`='$_POST[mod_armure_capacite]',
                       `degatsCaC`='$_POST[mod_armure_cac]',
                       `grade`='$_POST[mod_armure_grade]'
                   WHERE `ID`='$_POST[mod_armure_id]'
                   LIMIT 1");
	  if(!affected_rows())
	    erreur(0,"Impossible de modifier l'armure.");
	  else
	    foreach($_POST as $key=>$value)
	      $_POST[$key]="";
	}
    }
}

//*****************************************************************************
// Suppression d'une armure
//*****************************************************************************

else if(isset($_POST["del_armure_ok"],$_POST['del_armure_id'])&&is_numeric($_POST['del_armure_id']))
{
  $armure=my_fetch_array("SELECT `nom`
                          FROM `armures`
                          WHERE `ID`='$_POST[del_armure_id]'
                          LIMIT 1");
  if($armure[0])
    echo'<form method="post" action="anim.php?admin_armures">
 <h3>Confirmation :</h3>
 <p>Êtes vous sûr de vouloir supprimer cette armure ('.bdd2html($armure[1][0]).')?<br />
 '.form_hidden("id",$_POST['del_armure_id']).'
 '.form_submit("del_armure_no","Non").'&nbsp;&nbsp;&nbsp;&nbsp;
 '.form_submit("del_armure_yes","Oui").'
 </p>
 </form>
 <hr />
 ';
  else
    erreur(0,"Armure inconnue.");
}
else if(isset($_POST["del_armure_yes"],$_POST['id'])&&is_numeric($_POST['id']))
{
  // Suppression de la base des types de munitions.
  request("DELETE FROM `armures`
           WHERE `ID`='$_POST[id]'
           LIMIT 1");
  if(affected_rows())
    {
      console_log('anim_armures',"Suppression de l'armure d'ID : ".$_POST['id'],'',0,0);
      request("OPTIMIZE TABLE `armures`");
    }
  else
    erreur(0,"Impossible de supprimer cette armure.");
}

//*****************************************************************************
// Préparation du formulaire :
//*****************************************************************************

$armures=my_fetch_array("SELECT *
                         FROM `armures`");
$camps=my_fetch_array("SELECT `ID`, `nom`
                       FROM `camps`
                       WHERE ID!='0'
                       ORDER BY `ID` ASC");
$str_camps1=$str_camps2='';
$i=1;
$script='<script type="text/javascript">
function afficher_armure()
{
 if(!document.getElementById)
   return;
 ';
   for($i=1;$i<=$armures[0];$i++)
{
	$script.='  if(document.getElementById("mod_armure_id").value=='.$armures[$i]['ID'].')
    {
      mod_armure_nom="'.(bdd2js($armures[$i]['nom'])).'";
      mod_armure_init="'.(bdd2js($armures[$i]['initiales'])).'";
      mod_armure_type='.$armures[$i]['type'].';
      mod_armure_camou='.$armures[$i]['malus_camou'].';
      mod_armure_grade='.$armures[$i]['grade'].';
      mod_armure_terrain='.$armures[$i]['malus_terrain'].';
      mod_armure_PA='.$armures[$i]['PA'].';
      mod_armure_PAc='.$armures[$i]['PA_critiques'].';
      mod_armure_visibilite='.$armures[$i]['visibilite'].';
      mod_armure_critique='.$armures[$i]['malus_critique'].';
      mod_armure_precision='.$armures[$i]['bonus_precision'].';
      mod_armure_capacite='.$armures[$i]['capacite'].';
      mod_armure_cac='.$armures[$i]['degatsCaC'].';
      desc="'.(bdd2js($armures[$i]['desc'])).'";
'; 
	for($j=1;$j<=$camps[0];$j++)
	  {
	    $script.='    var camp_'.$camps[$j]['ID'].'=';
	    if(exist_in_db("SELECT `camp`
                            FROM `armures_camps`
                            WHERE `armure`='".$armures[$i]['ID']."'
                              AND `camp`='".$camps[$j]['ID']."'"))
	      $script.='1; 
';
	    else
	      $script.='0;
'; 
	  }
$script.='    }
 ';
      }
    $script.='  document.getElementById("mod_armure_nom").value=mod_armure_nom;
  document.getElementById("mod_armure_init").value=mod_armure_init;
  document.getElementById("mod_armure_type").value=mod_armure_type;
  document.getElementById("mod_armure_camou").value=mod_armure_camou;
  document.getElementById("mod_armure_terrain").value=mod_armure_terrain;
  document.getElementById("mod_armure_grade").value=mod_armure_grade;
  document.getElementById("mod_armure_PA").value=mod_armure_PA;
  document.getElementById("mod_armure_PAc").value=mod_armure_PAc;
  document.getElementById("mod_armure_visibilite").value=mod_armure_visibilite;
  document.getElementById("mod_armure_critique").value=mod_armure_critique;
  document.getElementById("mod_armure_precision").value=mod_armure_precision;
  document.getElementById("mod_armure_capacite").value=mod_armure_capacite;
  document.getElementById("mod_armure_cac").value=mod_armure_cac;
  document.getElementById("mod_armure_desc").value=desc;
';
    for($i=1;$i<=$camps[0];$i++)
      {
	$script.='  document.getElementById("mod_armure_camp_'.$camps[$i]['ID'].'").checked=camp_'.$camps[$i]['ID'].'?"checked":"";
 ';
	$str_camps1.=form_check(bdd2html($camps[$i]['nom']),"new_armure_camp_".$camps[$i]['ID']).'<br />
 ';
	$str_camps2.=form_check(bdd2html($camps[$i]['nom']),"mod_armure_camp_".$camps[$i]['ID']).'<br />
';
      }
$script.='}
'.(isset($_POST["mod_armure_ok"])?'':'afficher_armure();').' 
</script>
 ';
    $type_armures=array(3,
		   array(1,"Légère"),
		   array(2,"Moyenne"),
		   array(4,"Lourde")); 
    $grades=array(14,
		  array(0,numero_camp_grade(0,0)),
		  array(1,numero_camp_grade(0,1)),
		  array(2,numero_camp_grade(0,2)),
		  array(3,numero_camp_grade(0,3)),
		  array(4,numero_camp_grade(0,4)),
		  array(5,numero_camp_grade(0,5)),
		  array(6,numero_camp_grade(0,6)),
		  array(7,numero_camp_grade(0,7)),
		  array(8,numero_camp_grade(0,8)),
		  array(9,numero_camp_grade(0,9)),
		  array(10,numero_camp_grade(0,10)),
		  array(11,numero_camp_grade(0,11)),
		  array(12,numero_camp_grade(0,12)),
		  array(13,numero_camp_grade(0,13)));
    $visible=array(3,
		   array(1,"tout le monde"),
		   array(2,"les camps qui y ont accés"),
		   array(0,"personne"));

//*****************************************************************************
// Affichage du formulaire :
//*****************************************************************************

    echo'<form method="post" action="anim.php?admin_armures" enctype="multipart/form-data">
 <p>
 <h3>Créer une armure :</h3>
'.form_text("Nom : ","new_armure_nom","","").'<br />
'.form_text("Initiales : ","new_armure_init","3","").'<br />
'.form_select("Type : ","new_armure_type",$type_armures,"").'<br />
'.form_select("Grade nécessaire : ","new_armure_grade",$grades,"").'<br /> 
'.form_text("PA : ","new_armure_PA","3","").'<br />
'.form_text("PA critiques : ","new_armure_PAc","3","").'<br />
'.form_text("Malus de camouflage : ","new_armure_camou","3","").'<br />
'.form_text("Malus de terrain : ","new_armure_terrain","3","").'<br />
'.form_text("Bonus en précision : ","new_armure_precision","3","").'<br />
'.form_text("Diminution de critiques : ","new_armure_critique","3","").'<br />
'.form_text("Poids portable : ","new_armure_capacite","4","").'<br />
'.form_text("Bonus de dégâts au CàC : ","new_armure_cac","4","").'<br />
'.form_textarea("Description : ","new_armure_desc",2,25).'<br /> 
'.$str_camps1.'
'.form_select("Visible dans la liste des armures par : ","new_armure_visibilite",$visible,"").'<br />
'. form_submit("new_armure_ok","Créer").'
<h3>Modifier une armure :</h3>
'.form_select("Armure : ","mod_armure_id",$armures,"afficher_armure();").' 
'.form_text("Nom : ","mod_armure_nom","","").'<br />
'.form_text("Initiales : ","mod_armure_init","3","").'<br />
'.form_select("Type : ","mod_armure_type",$type_armures,"").'<br />
'.form_select("Grade nécessaire : ","mod_armure_grade",$grades,"").'<br /> 
'.form_text("PA : ","mod_armure_PA","3","").'<br />
'.form_text("PA critiques : ","mod_armure_PAc","3","").'<br />
'.form_text("Malus de camouflage : ","mod_armure_camou","3","").'<br />
'.form_text("Malus de terrain : ","mod_armure_terrain","3","").'<br />
'.form_text("Bonus en précision : ","mod_armure_precision","3","").'<br />
'.form_text("Diminution de critiques : ","mod_armure_critique","3","").'<br />
'.form_text("Poids portable : ","mod_armure_capacite","4","").'<br />
'.form_text("Bonus de dégâts au CàC : ","mod_armure_cac","4","").'<br />
'.form_textarea("Description : ","mod_armure_desc",2,25).'<br /> 
'.$str_camps2.'
'.form_select("Visible dans la liste des armures par : ","mod_armure_visibilite",$visible,"").'<br />
'.form_submit("mod_armure_ok","Modifier").'
<h3>Supprimer une armure : </h3>
'.form_select("Armure : ","del_armure_id",$armures,"").'<br />
'.form_submit("del_armure_ok","Supprimer").'
</p>
</form>
'.$script;
?>
