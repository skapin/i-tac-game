<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header_lite();
if(!isset($_SESSION['com_perso'])){
  echo 'Vous n\'&ecirc;tes pas loggu&eacute;.';
  com_footer_lite();
  die();
}
require_once('../sources/monperso.php');
$forcer=empty($_COOKIE['moiperso']) &&
  empty($_COOKIE['moicomp']) &&
  empty($_COOKIE['moiimplant']);

$framed=true;
  echo'   <ul id="menuHaut" class="lite perso">
'.showMenuItem('moiperso','&Eacute;tat',$forcer).'
'.showMenuItem('moicomp','Comp&eacute;tences',false).'
'.showMenuItem('moiimplant','Implants',false).'
   </ul>
';
include('../sources/perso.php');
echo'<div id="moiimplantFrame" class="framed">';
include('../sources/implants.php');
echo'</div>';
unset($perso); // On vire le perso de la memoire avant affichage.
com_footer_lite();
?>