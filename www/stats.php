<?php
ob_start('ob_gzhandler');
// Inclusion du fichier contenant les fonctions et variables globales.
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header();
error_reporting(E_ALL);
require('../objets/bdd.php');
// Connexion a la bdd
$db = new comBdd($GLOBALS['sql_host'],$GLOBALS['sql_user'],$GLOBALS['sql_pass'],$GLOBALS['sql_table']);

// Nombre de PJ
$pjs=$db->fetch('SELECT COUNT(*) FROM compte');
$pjs=$pjs[0][0];

echo'
<h2>Joueurs</h2>
<ul>
 <li>Comptes : '.$pjs.'</li>';
$pjs=$db->fetch('SELECT COUNT(*) FROM compte WHERE last_login > '.(time()-86400));
$pjs=$pjs[0][0];
echo '
 <li>Actifs dans les derni&egrave;res 24h : '.$pjs.'
  <ul>';

$pjs=$db->fetch('SELECT COUNT(DISTINCT compte.ID),camps.nom
FROM compte
INNER JOIN persos
  ON persos.compte = compte.ID
INNER JOIN camps
  ON camps.ID = persos.armee
WHERE last_login > '.(time()-86400).' AND armee != 1 GROUP BY armee');

foreach($pjs AS $pj){
  echo'
   <li>'.bdd2html($pj[1]).' : '.$pj[0].'</li>';
}
echo'
  </ul>
 </li>';


$pjs=$db->fetch('SELECT COUNT(*) FROM compte WHERE last_login > '.(time()-259200));
$pjs=$pjs[0][0];
echo '
 <li>Actifs dans les derni&egrave;res 72h : '.$pjs.'</li>
</ul>';





// Stats sur les implants
$imps=array('PV',
	    'vue',
	    'resist',
	    'regen',
	    'vit1',
	    'force',
	    'endu',
	    'vit2',
	    'resist2',
	    'vit4',
	    'resist4',
	    'precision');
echo'
<h2>Repartition des implants</h2>
<table class="liste">
 <tr>
  <th>Implant</th>
  <th>Moyenne</th>
  <th>Max</th>
  <th>Repartition</th>
 </tr>';
foreach($imps AS $imp){
  $nbr=$db->fetch('SELECT AVG(imp_'.$imp.') AS moy, MAX(imp_'.$imp.') AS maxi FROM persos WHERE compte!=0');
  $repart=$db->fetch('SELECT COUNT(*) AS nbrJ, imp_'.$imp.' AS nbrI FROM persos WHERE compte!=0 AND imp_'.$imp.' > 0 GROUP BY imp_'.$imp.' ORDER BY imp_'.$imp.' DESC');
    echo'
 <tr>
  <td>'.$imp.'</td>
  <td>'.$nbr[0]['moy'].'</td>
  <td>'.$nbr[0]['maxi'].'</td>
  <td>
   <ul>';
    foreach($repart AS $r){
      echo'
    <li>'.$r['nbrI'].' => '.$r['nbrJ'].'</li>';
    }
    echo'
   </ul>
  </td>
 </tr>';
}
echo'
</table>
<h2>Repartition des armures</h2>
<table class="liste">
 <tr>
  <th>Armure</th>
  <th>Joueurs</th>
 </tr>';
$type=0;
$total=0;
$armures=$db->fetch('SELECT armure, COUNT(*) AS nbrJ, armures.type,armures.nom
FROM persos
INNER JOIN armures
ON persos.armure=armures.ID
WHERE compte!=0
GROUP BY armure
ORDER BY armures.type ASC, nbrJ Desc');
foreach($armures AS $armure){
  if($type != $armure['type']){
    if($type > 0){
      echo'
 <tr>
  <td>Total : </td>
  <td>'.$total.'</td>
 </tr>';
      $total=0;
    }
    $type = $armure['type'];
    echo'
 <tr>
  <th colspan="2">'.catArmure($type).'</th>
 </tr>';
  }
  echo'
 <tr>
  <td>'.bdd2html($armure['nom']).'</td>
  <td>'.$armure['nbrJ'].'</td>
 </tr>';
  $total+=$armure['nbrJ'];
}
if($type > 0){
  echo'
 <tr>
  <td>Total : </td>
  <td>'.$total.'</td>
 </tr>';
}
echo'
</table>
<h2>Repartition des armes</h2>
<table class="liste">
 <tr>
  <th>Arme</th>
  <th>Joueurs</th>
 </tr>';
$type=-1;
$total=0;
$armes=$db->fetch('SELECT armes.ID, COUNT(*) AS nbrJ, armes.type,armes.nom
FROM persos
INNER JOIN armes
ON persos.matos_1=armes.ID
OR persos.matos_2=armes.ID
WHERE compte!=0
GROUP BY armes.ID
ORDER BY armes.type ASC, nbrJ Desc');
foreach($armes AS $arme){
  if($type != $arme['type']){
    if($type >= 0){
      echo'
 <tr>
  <td>Total : </td>
  <td>'.$total.'</td>
 </tr>';
      $total=0;
    }
    $type = $arme['type'];
    echo'
 <tr>
  <th colspan="2">'.type_arme($type).'</th>
 </tr>';
  }
  echo'
 <tr>
  <td>'.bdd2html($arme['nom']).'</td>
  <td>'.$arme['nbrJ'].'</td>
 </tr>';
  $total+=$arme['nbrJ'];
}
if($type >= 0){
  echo'
 <tr>
  <td>Total : </td>
  <td>'.$total.'</td>
 </tr>';
}
echo'
</table>';
com_footer();
?>