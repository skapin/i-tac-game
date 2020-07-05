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
if(isset($perso['anim_armures'],$_POST['id']) &&
   $perso['anim_armures'] &&
   $_POST['id'] &&
   is_numeric($_POST['id']))
  {
    $armures=my_fetch_array("SELECT *
FROM `armures`
WHERE ID=".$_POST['id']);
    if($armures[0])
      {
	echo'<dl>
<dt>armure_nom</dt><dd>'.(bdd2js($armures[1]['nom'])).'</dd>
<dt>armure_init</dt><dd>'.(bdd2js($armures[1]['initiales'])).'</dd>
<dt>armure_type</dt><dd>'.$armures[1]['type'].'</dd>
<dt>armure_camou</dt><dd>'.$armures[1]['malus_camou'].'</dd>
<dt>armure_grade</dt><dd>'.$armures[1]['grade'].'</dd>
<dt>armure_terrain</dt><dd>'.$armures[1]['malus_terrain'].'</dd>
<dt>armure_PA</dt><dd>'.$armures[1]['PA'].'</dd>
<dt>armure_PAc</dt><dd>'.$armures[1]['PA_critiques'].'</dd>
<dt>armure_visibilite</dt><dd>'.$armures[1]['visibilite'].'</dd>
<dt>armure_critique</dt><dd>'.$armures[1]['malus_critique'].'</dd>
<dt>armure_precision</dt><dd>'.$armures[1]['bonus_precision'].'</dd>
<dt>armure_capacite</dt><dd>'.$armures[1]['capacite'].'</dd>
<dt>desc</dt><dd>'.(bdd2js($armures[1]['desc'])).'</dd>
';
        $camps=my_fetch_array("SELECT `ID`
                       FROM `camps`
                       WHERE ID!='0'
                       ORDER BY `ID` ASC");
	for($j=1;$j<=$camps[0];$j++)
	  {
	    if(exist_in_db("SELECT `camp`
                            FROM `armures_camps`
                            WHERE `armure`='".$armures[1]['ID']."'
                              AND `camp`='".$camps[$j]['ID']."'"))
	      echo'<dt class="check">armure_camp_'.$camps[$j]['ID'].'</dt><dd>1</dd>
';
	    else
	      echo'<dt class="check">armure_camp_'.$camps[$j]['ID'].'</dt><dd>0</dd>
';
	      
	  }
	echo'</dl>
';

      }
  }
else
  echo'Erreur';
?>