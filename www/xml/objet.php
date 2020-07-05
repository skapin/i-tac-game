<?php
header('Content-Type: text/javascript; charset=iso-8859-15');
ob_start('ob_gzhandler');
session_save_path('../../session');
session_start();
require_once('../sources/globals.php');
require_once('../sources/erreurs.php');
require_once('../sources/messages.php');
require_once('../sources/bdd.php');
require_once('../sources/fonctions.php');
start_message();
connect_db();
$perso=recup_droits('anim');
if(isset($perso['anim_objets'],$_REQUEST['id']) &&
   $perso['anim_objets'] &&
   $_REQUEST['id'] &&
   is_numeric($_REQUEST['id'])){
  $objets=my_fetch_array("SELECT *
FROM `objets`
WHERE ID=".$_REQUEST['id']);
    if($objets[0]){
      echo'<dl>
<dt>objet_nom</dt><dd>'.(bdd2js($objets[1]['nom'])).'</dd>
<dt>objet_visibilite</dt><dd>'.(bdd2html($objets[1]['visibilite'])).'</dd>
<dt>objet_poids</dt><dd>'.(bdd2html($objets[1]['poids'])).'</dd>
<dt class="innerHTML">objet_desc</dt><dd>'.(bdd2html($objets[1]['description'])).'</dd>
<dt class="innerHTML">objet_ldesc</dt><dd>'.(bdd2html($objets[1]['story'])).'</dd>
<dt class="check">objet_visible</dt><dd>'.(bdd2html($objets[1]['visible'])).'</dd>
</dl>
';
    }
}
?>