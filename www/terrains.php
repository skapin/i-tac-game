<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
if(isset($_GET['lite']))
  com_header_lite();
else
  com_header();
$terrains=my_fetch_array('SELECT * FROM terrains ORDER BY ID ASC');
echo'<table id="terrains">
<tr>
  <th>Terrain</th>
  <th>Co�t de base en armure l�g�re</th>
  <th>Co�t de base en armure moyenne</th>
  <th>Co�t de base en armure lourde</th>
  <th>Competence</th>
  <th>Malus en pr�cision par case</th>
  <th>Malus au camouflage</th>
  <th>Co�t de vision par case</th>
  <th>Co�t de port�e par case</th>
  <th>Malus de r�g�n�ration</th>
  <th>Perte de PV � partir de</th>
  <th>Pontable ?</th>
  <th>Est un pont ?</th>
  <th>Visible �</th>
  <th>Visible au radar</th>
  <th>R/G/B</th>
</tr>
';
for($i=1;$i<=$terrains[0];$i++)
{
  echo'<tr>
  <td class="',$terrains[$i]['style'],'">',$terrains[$i]['nom'],'</td>
  <td>',$terrains[$i]['prix_1'],'</td>
  <td>',$terrains[$i]['prix_2'],'</td>
  <td>',$terrains[$i]['prix_4'],'</td>
  <td>',$terrains[$i]['competence'],'</td>
  <td>',$terrains[$i]['malus_precision'],'</td>
  <td>',$terrains[$i]['malus_camou'],'</td>
  <td>',$terrains[$i]['malus_vision'],'</td>
  <td>',$terrains[$i]['couvert'],'</td>
  <td>',$terrains[$i]['malus_regen'],'</td>
  <td>',$terrains[$i]['debut_perte'],'%</td>
  <td>',($terrains[$i]['pontable']?'oui':'non'),'</td>
  <td>',($terrains[$i]['is_posable']?'oui':'non'),'</td>
  <td>',$terrains[$i]['visible'],'</td>
  <td>',($terrains[$i]['visible_radar']?'oui':'non'),'</td>
  <td style="background-color:rgb(',$terrains[$i]['R'],',',$terrains[$i]['G'],',',$terrains[$i]['B'],');">',$terrains[$i]['R'],'/',$terrains[$i]['G'],'/',$terrains[$i]['B'],'</td>
</tr>
';
}
echo'</table>
';
com_footer();
?>
