<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
require_once('../inits/camps.php');
com_header();
if(isset($_SESSION['com_perso']) && $_SESSION['com_perso'])
  {
    $camp=my_fetch_array('SELECT armee FROM persos WHERE ID='.$_SESSION['com_perso']);
    $camp=$camp[1]['armee'];
  }
else
  $camp=0;
$grades=my_fetch_array('SELECT ID,
nom
FROM camps
WHERE visible=1 OR ID='.$camp);
echo'  <div class="liste">
   <h2>Grades</h2>
   <p>Vous pouvez consulter ici l\'&eacute;chelle des grades d\'exp&eacute;rience des diff&eacute;rentes factions ainsi que les grades honorifiques de leurs dirigeants.</p>
   <table id="grades">
    <tr>
     <th></th>
';
for($i=1;$i<=$grades[0];$i++)
  {
    echo'    <th>',bdd2html($grades[$i]['nom']),'</th>
';
  }
echo'   </tr>
';
for($j=0;$j<=13;$j++)
  {
    echo'   <tr>
    <td>'.$j.'</td>
';
    for($i=1;$i<=$grades[0];$i++)
      {
	echo'    <td>',numero_camp_grade($grades[$i]['ID'],$j),'</td>
';
      }
echo'   </tr>
';
  }
echo '   <tr><th colspan="'.($grades[0]+1).'">Grades honorifiques</th></tr>
';
for($j=1;$j<=3;$j++)
  {
    echo'   <tr>
    <td></td>
';
    for($i=1;$i<=$grades[0];$i++)
      {
	echo'    <td>',grade_spec_autre($grades[$i]['ID'],$j,''),'</td>
';
      }
echo'   </tr>
';
  }
echo'  </table>
  </div>
';
com_footer();
?>