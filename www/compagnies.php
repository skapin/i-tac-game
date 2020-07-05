<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
if(isset($_REQUEST['lite'])&&isset($_REQUEST['lite']))
  com_header_lite();
else
  com_header();
if(isset($_SESSION['com_perso']) && $_SESSION['com_perso'])
  {
    $camp=my_fetch_array('SELECT armee FROM persos WHERE ID='.$_SESSION['com_perso']);
    $camp=$camp[1]['armee'];
  }
else
  $camp=0;
echo'<div id="compagnies" class="liste">
 <h2>Groupes</h2>
 <p>Vous pouvez consulter ici les fiches descriptives RP et HRP des diff&eacute;rents groupes du jeu. Leur nom varie en fonction des factions : Kvindek pour les Enkis, Ordre pour les Seln\'as et Takhment pour les Lunmors. Vous retrouverez &eacute;galement la liste de leurs membres.</p>';
if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id']))
{
  $compa=my_fetch_array('SELECT compagnies.nom,
                                compagnies.HRP,
                                compagnies.desc,
                                compagnies.initiales,
                                grades.niveau AS perso_grade,
                                persos.ID AS matricule,
                                persos.nom AS perso_nom,
                                camps.initiale AS armee
                         FROM compagnies
                            INNER JOIN persos
                               ON persos.compagnie=compagnies.ID
                            INNER JOIN camps
                               ON compagnies.camp=camps.ID
                            LEFT OUTER JOIN grades
                               ON persos.grade=grades.ID
                         WHERE compagnies.ID='.$_REQUEST['id'].'
                           AND compagnies.valide=1
AND compagnies.ID!=1
AND (camps.visible=1 OR camps.ID='.$camp.')');
  if($compa[0]){
      echo'<h2>',bdd2html($compa[1]['nom']),'</h2>
<dl>
<dt>Pr&eacute;sentation RP :</dt>
<dd>',filtrage_ordre(bdd2text($compa[1]['desc'])),'
</dd>
<dt>Pr&eacute;sentation HRP :</dt>
<dd>',filtrage_ordre(bdd2text($compa[1]['HRP'])),'
</dd>
';
      $colo='<dt>Responsable :</dt>
';
      $membres='<ul>
';
      for($i=1;$i<=$compa[0];$i++)
	{
	  if($compa[$i]['perso_grade']==1) // C'est le colonel
	    $colo.='<dd>'.bdd2html($compa[$i]['perso_nom']).' (<a href="fiche.php?id='.$compa[$i]['matricule'].(isset($_REQUEST['lite']) && $_REQUEST['lite']?'&amp;lite=1':'').'" title="Voir fiche">'.(bdd2html($compa[$i]['armee'])).'-'.bdd2html($compa[1]['initiales']).'-'.$compa[$i]['matricule'].'</a>)</dd>
';
	  else
	    $membres.='<li>'.bdd2html($compa[$i]['perso_nom']).' (<a href="fiche.php?id='.$compa[$i]['matricule'].(isset($_REQUEST['lite']) && $_REQUEST['lite']?'&amp;lite=1':'').'" title="Voir fiche">'.(bdd2html($compa[$i]['armee'])).'-'.bdd2html($compa[1]['initiales']).'-'.$compa[$i]['matricule'].'</a>)</li>
';
	}
      echo $colo,'<dt>Membres :</dt>
<dd>
',$membres,'</ul>
</dd>
<dt class="clearer"></dt>
</dl>
';
    }
}
$compas=my_fetch_array('SELECT compagnies.ID,
                               CONCAT(compagnies.nom,CONCAT(" (",CONCAT(compagnies.initiales,")"))) AS nom
                        FROM compagnies
                        WHERE compagnies.ID!=1 AND compagnies.valide=1
                        ORDER BY compagnies.camp ASC,compagnies.nom ASC');
/*for($i=1;$i<=$compas[0];$i++)
{
  $compas[$i]['style']='color:#'.$compas[$i]['font'].';background:#'.$compas[$i]['couleur'];
}*/
echo'<form method="post" action="compagnies.php">
<p>',form_select('Voir le groupe : ','id',$compas,''),'
',(isset($_REQUEST['lite'])?'<input type="hidden" name="lite" value="1" />':''),'
',form_submit('o','Ok'),'</p>
</form>
</div>
';
if(isset($_REQUEST['lite']))
  com_footer_lite();
else
  com_footer();
?>
