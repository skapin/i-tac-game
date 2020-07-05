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
if(isset($perso['anim_armes'],$_POST['id']) &&
   $perso['anim_armes'] &&
   $_POST['id'] &&
   is_numeric($_POST['id']))
  {
    $armes=my_fetch_array("SELECT *
                       FROM `armes`
WHERE ID=".$_POST['id']);
    if($armes[0])
      {
	$armures=dispo_armure($armes[1]['armure']);
	$armures=$armures[0]+2*$armures[1]+4*$armures[2];
	echo'<dl>
<dt>arme_nom</dt><dd>'.(bdd2js($armes[1]['nom'])).'</dd>
<dt>arme_type</dt><dd>'.$armes[1]['type'].'</dd>
<dt>arme_typem</dt><dd>'.$armes[1]['type_munitions'].'</dd>
<dt>arme_maxi</dt><dd>'.$armes[1]['max_munitions'].'</dd>
<dt>arme_portee</dt><dd>'.$armes[1]['portee'].'</dd>
<dt>arme_degats</dt><dd>'.$armes[1]['degats'].'</dd>
<dt>arme_minp</dt><dd>'.$armes[1]['precision_min'].'</dd>
<dt>arme_maxp</dt><dd>'.$armes[1]['precision_max'].'</dd>
<dt>arme_maxc</dt><dd>'.$armes[1]['degat_vie'].'</dd>
<dt>arme_critp</dt><dd>'.$armes[1]['critique'].'</dd>
<dt>arme_crits</dt><dd>'.$armes[1]['seuil_critique'].'</dd>
<dt>arme_critpm</dt><dd>'.$armes[1]['pm_critique'].'</dd>
<dt>arme_mun</dt><dd>'.$armes[1]['tir_munars'].'</dd>
<dt>arme_tirs</dt><dd>'.$armes[1]['tirs'].'</dd>
<dt>arme_zone</dt><dd>'.$armes[1]['zone'].'</dd>
<dt>arme_diminution</dt><dd>'.$armes[1]['diminution'].'</dd>
<dt>arme_touche</dt><dd>'.$armes[1]['touche'].'</dd>
<dt>arme_dimit</dt><dd>'.$armes[1]['dimit'].'</dd>
<dt>arme_armure</dt><dd>'.$armures.'</dd>
<dt>arme_grade</dt><dd>'.$armes[1]['grade'].'</dd>
<dt>arme_lvl</dt><dd>'.$armes[1]['lvl'].'</dd>
<dt>arme_degat_t</dt><dd>'.$armes[1]['secu_degats'].'</dd>
<dt>arme_tirs_t</dt><dd>'.$armes[1]['perte_tirs'].'</dd>
<dt>arme_PM_t</dt><dd>'.$armes[1]['perte_PM'].'</dd>
<dt>arme_poids</dt><dd>'.$armes[1]['poids'].'</dd>
<dt>arme_malus</dt><dd>'.$armes[1]['malus_camou'].'</dd>
<dt>visibilite</dt><dd>'.$armes[1]['visibilite'].'</dd>
<dt>src</dt><dd>'.$armes[1]['ID'].'</dd>
<dt class="innerHTML">desc</dt><dd>'.(bdd2html($armes[1]['description'])).'</dd>
';
        $camps=my_fetch_array("SELECT `ID`
                       FROM `camps`
                       WHERE ID!='0'
                       ORDER BY `ID` ASC");
	for($j=1;$j<=$camps[0];$j++)
	  {
	    if(exist_in_db("SELECT `camp`
                            FROM `armes_camps`
                            WHERE `arme`='".$armes[1]['ID']."'
                              AND `camp`='".$camps[$j]['ID']."'"))
	      echo'<dt class="check">arme_camp_'.$camps[$j]['ID'].'</dt><dd>1</dd>
';
	    else
	      echo'<dt class="check">arme_camp_'.$camps[$j]['ID'].'</dt><dd>0</dd>
';
	      
	  }
	echo'</dl>
';

      }
  }
else
  echo'Erreur';
?>