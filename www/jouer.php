<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header(1);
if(!isset($_SESSION['com_perso'])){
  com_header(2);
  echo 'Vous n\'êtes pas loggué.';
  com_footer();
  die();
}
require_once('../inits/competences.php');

// On recupere les infos du perso.
include('../sources/monperso.php');
// Si ça fait longtemps que le perso n'a pas ete update, on l'update.
// Et on en profite pour recharger les infos du perso en session.
$done=0;
if(($time-$perso['date_last_update'])>$GLOBALS['update']){
  include('../sources/update_perso.php');
  $done=1;
}
$exclusif=0;
// Passage de l'arme primaire a l'arme secondaire ?
if(isset($_POST['changeArme'])){
  request('UPDATE persos
           SET `arme`='.$slot2.'
           WHERE persos.ID='.$_SESSION['com_perso']);
  $slot3=$slot2;
  $slot2=$slot;
  $slot=$slot3;
}
// On s'est equipe ?
else if(isset($_POST['equipement_ok'])||isset($_POST['alt_equipement_ok'])){
  include('../sources/jemequipe.php');
  $done=1;
}
// Ou on a peut être choisi une mission ?
else if(isset($_POST['choix_mission_ok'])){
  include('../sources/entremission.php');
  $done=1;
}
// Ou on doit spawn dans une mission.
else if(isset($_POST['entre_carte_ok'])&&$perso['ID_mission']&&!$perso['map']){
  include('../sources/entrecarte.php');
  $done=1;
}
// On peut aussi bouger.
else if(isset($_GET['bouge']) && 
	($_GET['bouge'] == 'NO' ||
	 $_GET['bouge'] == 'N' ||
	 $_GET['bouge'] == 'NE' ||
	 $_GET['bouge'] == 'O' ||
	 $_GET['bouge'] == 'E' ||
	 $_GET['bouge'] == 'SO' ||
	 $_GET['bouge'] == 'S' ||
	 $_GET['bouge'] == 'SE'))
{
  include('../sources/jebouge.php');
  $done=1;
}
// Camouflage.
else if(isset($_POST['camou_ok'],$_POST['camou_confirm']) || isset($_POST['camou_stop_ok'],$_POST['camou_confirm']))
{
  include('../sources/camoufler.php');
  $done=1;
}
// Sprint
else if(isset($_POST['sprint_ok']))
{
  include('../sources/sprint.php');
  $done=1;
}
// Observer le terrain.
else if(isset($_POST['ecl_ok'],$_POST['ecl_confirm']))
{
  include('../sources/eclaireur.php');
  $done=1;
}
// Sortir d'une mission.
else if(isset($_POST['sortir_ok']))
{
  include('../sources/jesort.php');
  $done=1;
}
// Tubage
else if(isset($_POST['tube_ok']))
{
  include('../sources/jetube.php');
  $done=1;
}
// Changement de motd
if(isset($_POST['new_message_ok'])){
  request('UPDATE persos SET message="'.post2bdd($_POST['new_message']).'" WHERE ID='.$_SESSION['com_perso']);
}

if($done){
  include('../sources/monperso.php');
}
if(!$exclusif){
  $framed=0;
  // Maintenant on va voir s'il est sur une mission ou s'il peut s'équiper.
  if(!($perso['X']||$perso['Y']||$perso['map'])){
    // Le perso n'est pas sur une map, on affiche donc le choix d'équipement
    // et le choix de mission.
    com_header(2);
    $forcer=empty($_COOKIE['implants']) && empty($_COOKIE['matos']) && empty($_COOKIE['mission']) && empty($_COOKIE['console']) && empty($_COOKIE['moicomp']);
    echo'   <ul id="menuHaut" class="jouer">
'.showMenuItem('moiperso','&Eacute;tat',$forcer).'
'.showMenuItem('moicomp','Comp&eacute;tences').'
'.showMenuItem('implants','Implants').'
'.showMenuItem('matos','&Eacute;quipement').'
'.showMenuItem('mission','Missions').'
'.showMenuItem('console','Gestion').'
   </ul>
';
    include('../sources/perso.php');
    echo'   <div class="framed" id="implantsFrame">
';
    include('../sources/implants.php');
    echo'  </div>
   <div class="framed" id="matosFrame">
';
    include('../sources/equipement.php');
    echo'  </div>
   <div class="framed" id="missionFrame">
';
    include('../sources/missions.php');
    echo'  </div>
   <div class="framed" id="consoleFrame">
';
    include('../sources/consoles.php');
    echo'  </div>
';
  }
  else{
    com_header_jeu();
    include('../sources/jeu.php');
  }
}
//unset($perso);
com_footer();
/*
clear_post() : détruit tout ce qui est contenu par les variables $_GET et $_POST
*/
function clear_posts(){
  foreach($_POST as $key => $value)
    unset($_POST[$key]);
  foreach($_GET as $key => $value)
    unset($_GET[$key]);
}
?>
