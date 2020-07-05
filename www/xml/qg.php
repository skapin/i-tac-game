<?php
header('Content-Type: text/javascript; charset=iso-8859-15');
ob_start('ob_gzhandler');
session_save_path('../../session');
session_start();
require_once('../../sources/globals.php');
require_once('../../sources/erreurs.php');
require_once('../../sources/messages.php');
require_once('../../sources/bdd.php');
require_once('../../sources/fonctions.php');
start_message();
connect_db();
$perso['admin']=recupAdmin();
if(isset($perso['admin']['anim_qgs'],$_REQUEST['id']) &&
   $perso['admin']['anim_qgs'] &&
   $_REQUEST['id'] &&
   is_numeric($_REQUEST['id']))
  {
    $qgs=my_fetch_array('SELECT qgs.*
                     FROM qgs
 WHERE ID='.$_REQUEST['id']);
    echo'<dl>
<dt>qg_nom</dt><dd>'.bdd2js($qgs[1][1]).'</dd>
<dt>qg_init</dt><dd>'.bdd2js($qgs[1][2]).'</dd>
<dt>qg_X</dt><dd>'.$qgs[1]['X'].'</dd>
<dt>qg_Y</dt><dd>'.$qgs[1]['Y'].'</dd> 
<dt>qg_bloc</dt><dd>'.$qgs[1]['blocage'].'</dd> 
<dt>qg_util</dt><dd>'.$qgs[1]['utilisation'].'</dd> 
<dt>qg_vision</dt><dd>'.$qgs[1]['visibilite'].'</dd> 
<dt class="check">qg_arme</dt><dd>'.$qgs[1]['armes'].'</dd> 
<dt class="check">qg_reload</dt><dd>'.$qgs[1]['munitions'].'</dd> 
<dt class="check">qg_armure</dt><dd>'.$qgs[1]['armures'].'</dd> 
<dt>qg_reparation</dt><dd>'.$qgs[1]['reparation'].'</dd> 
<dt class="check">qg_respawn</dt><dd>'.$qgs[1]['respawn'].'</dd> 
<dt class="check">qg_tube</dt><dd>'.$qgs[1]['tubage'].'</dd>
<dt class="check">qg_prenable</dt><dd>'.$qgs[1]['prenable'].'</dd> 
<dt class="check">qg_self</dt><dd>'.$qgs[1]['type'].'</dd> 
<dt>qg_camp</dt><dd>'.$qgs[1]['camp'].'</dd> 
<dt>qg_carte</dt><dd>'.$qgs[1]['carte'].'</dd> 
<dt>qg_regen</dt><dd>'.$qgs[1]['regeneration'].'</dd> 
<dt>qg_visible</dt><dd>'.$qgs[1]['visible'].'</dd>
<dt>qg_camou</dt><dd>'.$qgs[1]['malus_camou'].'</dd>
</dl>';
  }
else
  echo'Erreur';
?>