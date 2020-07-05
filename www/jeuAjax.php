<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
session_start();
start_message();
connect_db();
echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
<title>CoM</title>
</head>
<body>
';
if(!isset($_SESSION['com_perso'])){
  add_message(4,'Vous n\'&ecirc;tes pas loggu&eacute;.');
  com_footerAjax();
  die();
}
// Premiere chose, loader le perso.
require("../sources/monperso.php");
$done = 0;

// Regenerer.
if(($time-$perso['date_last_update'])>$GLOBALS['update']){
  include('../sources/update_perso.php');
  $done=1;
}

// Si on a tire, on applique le tir.
if(!empty($_POST['targetCase'])){
  include('../sources/jattaque.php');
  $done = 1;
}

// Minage ?
if((!empty($_POST['mine1'])||
    !empty($_POST['mine2'])||
    !empty($_POST['mine3'])) &&
   $perso['map']!=0 &&
   $perso['type_armure']==1){
  include('../sources/posemine.php');
  $done=1;
}

// Ou deminage ?
if(!empty($_POST['mineID'])){
  include('../sources/demine.php');
  $done=1;
}

// Rechargement
if(isset($_POST['repare_ok']) || isset($_POST['recharge_arme_ok']) || isset($_POST['recharge_gad_ok'])){
  include('../sources/rechargement.php');
  $done=1;
}


if($done){
  $newtitle='';
  require("../sources/monperso.php");
  $newtitle.=text2html($perso['nom']).' : ';
  $newtitle.=max(1,floor($perso['PV'])).'/'.$perso['PV_max'].' PV, ';
  $newtitle.=max(0,floor($perso['PA'])).'/'.$perso['PA_max'].' PA, ';
  $newtitle.=round($perso['PM']).' PT';
  if(!empty($perso['ID_arme'.$slot])){
    $newtitle.=', '.$perso['tirs_restants_arme'.$slot].'/'.$perso['nbr_tirs_arme'.$slot].' tirs';
    if(!empty($perso['munars_max_arme'.$slot])){
      $newtitle.=', '.$perso['munars_arme'.$slot].'/'.$perso['munars_max_arme'.$slot].' munitions';
    }
  }
  add_message(5,'parent.document.title="'.$newtitle.'";
parent.PM='.$perso['PM'].';
');
  if(($perso['munars_arme'.$slot] || !$perso['cadence_arme'.$slot]) &&
     $perso['tirs_restants_arme'.$slot]>=1){
    // Nom de l'action mise sur le bouton de "tir".
    $value='attaquer';
    if($perso['type_arme'.$slot]==5){
      $value='r&eacute;parer';
    }
    else if($perso['type_arme'.$slot]==8){
      $value='soigner';
    }
    add_message(5,'var tirName="'.$value.'";
var preciMax='.$perso['precision_max_arme'.$slot].';
var preciMin='.$perso['precision_min_arme'.$slot].';
var nbTirs='.$perso['nbr_tirs_arme'.$slot].'; 
var seuilCrit='.$perso['seuil_critique_arme'.$slot].';
var malus_precision='.$perso['malus_precision'].';
var type_arme='.($perso['type_arme'.$slot]==3?'1':($perso['type_arme'.$slot]==4?'2':'0')).';
');
  }
}
com_footerAjax();
?>