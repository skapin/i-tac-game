<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header();

if(!empty($_SESSION['com_perso'])){
  if(!empty($_REQUEST['uid']) ||
     !empty($_REQUEST['id'])){
    unset($_COOKIE['mod']);
    unset($_COOKIE['fiche']);
  }
  $forcer=(!isset($_COOKIE['lire']) &&
	   !isset($_COOKIE['fiche']) &&
	   !isset($_COOKIE['mod']));
  // Affichage du menu.
  echo'   <ul id="menuHaut" class="rp">
'.showMenuItem('lire','Lire',$forcer).'
'.showMenuItem('fiche','Fiche',false).'
'.showMenuItem('mod','&Eacute;crire',false).'
   </ul>
   <div class="framed rp" id="lireFrame">
';
}
else{
  echo'  <div class="rp">
';
  if(empty($_GET['act'])){
    echo'  <h2>Chroniques</h2>
 <p>Bienvenue sur les Chroniques d\'i-tac. Vous trouverez ici tous les textes RP cr&eacute;&eacute;s avec beaucoup de talent par nos joueurs afin de faire vivre leurs personnages et l\'univers. Ils prennent chaque jour la plume pour &eacute;crire le futur de l\'Humanit&eacute;. Faites comme eux : lancez-vous, d&eacute;couvrez vos talents cach&eacute;s de conteur et partagez-les avec tout le monde !</p>
';
  }
}
include_once('../sources/rp_lire.php');
echo'  </div>
';
if(!empty($_SESSION['com_perso'])){
  echo '   <div class="framed rp" id="ficheFrame">
';
  include_once('../sources/rp_fiche.php');
  echo '   </div>
   <div class="framed rp" id="modFrame">
';
  include_once('../sources/rp_ecrire.php');
  echo '   </div>
';
}
com_footer();
?>