<?php
ob_start('ob_gzhandler');
// Inclusion du fichier contenant les fonctions et variables globales.
require_once('../sources/globals.php');
require_once('../sources/includes.php');
$chartes=array(1=>array('Charte des joueurs','joueurs.html'),
	       4=>array('Charte sur les donn&eacute;es personnelles','donnees.html'));
com_header();
echo'<div class="chartes">
';
if(!empty($_GET['id']) &&
   !empty($chartes[$_GET['id']])){
  echo file_get_contents('../chartes/'.$chartes[$_GET['id']][1]);
}
else{
  echo'<h2>Chartes</h2>
<p>Vous retrouvez ici l\'ensemble des chartes r&eacute;gissant le fonctionnement d\'i-tac.</p>
<ul>
';
  foreach($chartes AS $id=>$charte){
    echo'<li><a href="chartes.php?id='.$id.'">'.$charte[0].'</a></li>
';
  }
  echo'</ul>
';
}
echo'</div>
';
com_footer();
?>