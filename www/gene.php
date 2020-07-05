<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header_lite();
if(!isset($_SESSION['com_perso'])){
  echo 'Vous n\'&ecirc;tes pas loggu&eacute;.';
  com_footer();
  die();
}
// Récupération des droits.
$perso=recup_droits('gene');
require_once('../gene/gene_fonctions.php');
menu();
content();
com_footer();
?>
