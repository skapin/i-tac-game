<?php
header('Content-Type: text/javascript; charset=iso-8859-15');
ob_start('ob_gzhandler');
session_save_path('../../session');
session_start();
require_once('../sources/globals.php');
require_once('../sources/erreurs.php');
require_once('../sources/messages.php');
require_once('../objets/bdd.php');
require_once('../sources/fonctions.php');
start_message();
if(empty($_POST['id']) ||
   empty($_SESSION['com_perso']) ||
   !is_numeric($_POST['id'])){
  echo'alert("erreur");';
}
else{
  $bd = new comBdd($GLOBALS['sql_host'],
		   $GLOBALS['sql_user'],
		   $GLOBALS['sql_pass'],
		   $GLOBALS['sql_table']);
  $texte=$bd->fetch('SELECT `type`,
statut,
titre,
texte,
perso,
camp,
detail
FROM rp_textes
LEFT JOIN rp_perms
ON rp_perms.texteID=rp_textes.ID
WHERE auteur='.$_SESSION['com_perso'].'
AND rp_textes.ID='.$_POST['id']);
  if(empty($texte)){
    echo'alert("Vous n\'etes pas l\'auteur de ce texte");';
  }
  else{
    $perm="";
    if($texte[0]['type'] & 1){
      // Visible a tous.
      $perm="checked";
    }
    $open="";
    if($texte[0]['type'] & 2){
      // Ouvert a tous.
      $open="checked";
    }
    $fin="";
    if($texte[0]['statut'] == 1){
      // Fini
      $fin="checked";
    }

    // On met a jour les valeurs du formulaire.
    echo'$("mod_text").value="'.bdd2js($texte[0]['texte']).'";
$("mod_titre").value="'.bdd2js($texte[0]['titre']).'";
$("mod_perm").setProperty("checked","'.$perm.'");
$("mod_fin").setProperty("checked","'.$fin.'");
';
    // Recuperation des droits.
    $perms=$bd->fetch('SELECT rp_perms.detail,
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
WHERE rp_perms.texteID='.$_POST['id']);
    if(!empty($perms)){
      $detail='';
      $str='';
      $lnk='';
      foreach($perms AS $perm){
	if($perm['detail']==1){
	  $detail='voir';
	}
	else if($perm['detail']==2){
	  $detail='&eacute;crire une suite';
	}
	else if($perm['detail']==3){
	  $detail='voir et &eacute;crire une suite';
	}

	if($perm['ID_camp']){
	  $str='(Camp) '.bdd2js($perm['nom_camp']).' : '.$detail;
	  $lnk='camp';
	  $id=$perm['ID_camp'];
	}
	if($perm['ID_perso']){
	  $str='(Perso) '.bdd2js($perm['nom_perso']).' ('.$perm['ID_perso'].') : '.$detail;
	  $lnk='perso';
	  $id=$perm['ID_perso'];
	}
	if($perm['ID_compa']){
	  $str='(Compagnie) '.bdd2js($perm['nom_compa']).' : '.$detail;
	  $lnk='compa';
	  $id=$perm['ID_compa'];
	}
	echo'li=new Element("li");
//li.setHTML();
$("liste_perms").adopt(li);
form=new Element("form").setProperties({action:"rp.php",method:"post" });
li.adopt(form);
label=new Element("label").setHTML("'.$str.' ");
form.adopt(label);
hidden=new Element("input").setProperties({name:"sup_type",value:"'.$lnk.'",type:"hidden"});
form.adopt(hidden);
hidden=new Element("input").setProperties({name:"sup_id",value:"'.$id.'",type:"hidden"});
form.adopt(hidden);
hidden=new Element("input").setProperties({name:"sup_text_id",value:"'.$_POST['id'].'",type:"hidden"});
form.adopt(hidden);
sub=new Element("input").setProperties({type:"submit",value:"Supprimer",name:"sup_perm"});
form.adopt(sub);
';
      }
    }
  }
}
?>