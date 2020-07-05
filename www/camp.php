<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header();
echo'<div id="camp" class="liste">
 <h2>Camps</h2>
 <p>Vous pouvez consulter ici les organigrammes hi&eacute;rarchiques des diff&eacute;rentes factions. Ils repr&eacute;sentent les cha&icirc;nes d&eacute;cisionnelles et de responsabilit&eacute; et non la progression des personnages.</p>';
if(isset($_SESSION['com_perso']) && $_SESSION['com_perso'])
  {
    $camp=my_fetch_array('SELECT armee FROM persos WHERE ID='.$_SESSION['com_perso']);
    $camp=$camp[1]['armee'];
  }
else
  $camp=0;
if(isset($_GET['id']) && is_numeric($_GET['id']))
{
  $persos=my_fetch_array('SELECT persos.nom,
persos.armee,
persos.niveau_gene,
grades.nom AS grade,
grades.niveau AS niveau,
grades.ID AS ID_grade,
camps.nom AS camp,
camps.initiale AS init_camp,
compagnies.nom AS compagnie,
compagnies.initiales AS init_compa,
compagnies.ID AS compa_ID, 
persos.ID AS mat 
FROM persos
  INNER JOIN compagnies
    ON compagnies.ID=persos.compagnie
  INNER JOIN camps
    ON persos.armee=camps.ID 
  LEFT OUTER JOIN grades
    ON grades.ID=persos.grade
WHERE persos.armee='.$_GET['id'].'
  AND (camps.visible=1 OR camps.ID='.$camp.')
  AND niveau_gene>0 
ORDER BY niveau_gene DESC');
  $lvl=101;
  if($persos[0])
    echo'<h2>Hi&eacute;rarchie des ',bdd2html($persos[1]['camp']),'</h2>
<dl>
';
  for($i=1;$i<=$persos[0];$i++)
    {
      if($persos[$i]['niveau_gene']!=$lvl)
	{
	  if($lvl!=101)
	    echo'</ul>
</dd>
';
	  $lvl=$persos[$i]['niveau_gene'];
	  echo'<dt>Niveau : ',$lvl,'</dt>
<dd>
<ul>
';
	}
      echo'<li>',bdd2html($persos[$i]['nom']),' (<a href="fiche.php?id=',$persos[$i]['mat'],'">',bdd2html($persos[$i]['init_camp']),'-',bdd2html($persos[$i]['init_compa']),'-',$persos[$i]['mat'],'</a>) : ',bdd2html(grade_spec_autre($persos[$i]['armee'],$persos[$i]['ID_grade'],$persos[$i]['grade'])),($persos[$i]['niveau']==1?' du groupe <a href="compagnies.php?id='.$persos[$i]['compa_ID'].'">'.bdd2html($persos[$i]['compagnie']).'</a>':''),'</li>
';
    }
  if($lvl!=101)
    echo'</ul>
</dd>
<dt class="clearer"></dt>
</dl>
';
}
$camps=my_fetch_array('SELECT ID,
nom
FROM camps
WHERE (camps.visible=1 OR camps.ID='.$camp.')');
echo'<form method="get" action="camp.php">
<p>',form_select('Voir le camp : ','id',$camps,''),'
',form_submit('','Ok'),'</p>
</form>
</div>
';
com_footer_lite();
?>
