<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
if(isset($_GET['lite']))
  com_header_lite();
else
  com_header();
require_once('../inits/camps.php');
if(isset($_GET['id'])&&is_numeric($_GET['id']))
{
  $morts=my_fetch_array('SELECT events.date,
                                events.type,
                                events.PV, 
                                mort.nom AS mort_nom, 
                                mort.ID AS mort_mat, 
                                compa_mort.initiales AS mort_compa,
                                mort.armee AS mort_camp, 
                                frag.nom AS frag_nom, 
                                frag.ID AS frag_mat, 
                                compa_frag.initiales AS frag_compa,
                                frag.armee AS frag_camp
                         FROM events
                           INNER JOIN persos AS mort
                             ON mort.ID=events.cible
                           INNER JOIN compagnies AS compa_mort
                             ON mort.compagnie=compa_mort.ID 
                           INNER JOIN persos AS frag
                             ON frag.ID=events.tireur
                           INNER JOIN compagnies AS compa_frag
                             ON frag.compagnie=compa_frag.ID 
                         WHERE (mort.ID='.$_GET['id'].' OR frag.ID='.$_GET['id'].')
                           AND events.mort=1
                         ORDER BY events.date DESC');
  $frags=0;
  $clonages=0;
  $cloneurs='';
  $fragues='';
  for($i=1;$i<=$morts[0];$i++)
    {
      $date=date("\L\e d/m/Y à G\hi\m\\ns\s.",$morts[$i]['date']);
      if(isset($_SESSION['com_perso'])&&$morts[$i]['mort_mat']==$_GET['id'])
	{
	  // Le perso s'est fait cloner.
	  $clonages++;
	  if($morts[$i]['type']==6 && $morts[$i]['PV'])
	    $cloneurs.='<li>Mort de causes "naturelles". '.$date.'<li>
';
	    else
	  $cloneurs.='<li><a href="fiche.php?id='.$morts[$i]['frag_mat'].''.(isset($_GET['lite'])?'&amp;lite=1':'').'">'.bdd2html($morts[$i]['frag_nom']).' ('.camp_initiale($morts[$i]['frag_camp']).'-'.bdd2html($morts[$i]['frag_compa']).'-'.$morts[$i]['frag_mat'].')</a> '.$date.'</li>
';
	}
      if(isset($_SESSION['com_perso'])&&$morts[$i]['frag_mat']==$_GET['id'])
	{
	  // Le perso a fraggué.
	  $frags++;
	  $fragues.='<li><a href="fiche.php?id='.$morts[$i]['mort_mat'].''.(isset($_GET['lite'])?'&amp;lite=1':'').'">'.bdd2html($morts[$i]['mort_nom']).' ('.camp_initiale($morts[$i]['mort_camp']).'-'.bdd2html($morts[$i]['mort_compa']).'-'.$morts[$i]['mort_mat'].')</a> '.$date.'</li>
';
	}
    }
  echo'<div id="frags" class=liste>
<h3>Clonages : ',$clonages,'</h3>
',($clonages?'<ul>
'.$cloneurs.'</ul>
' :''),'<h3>Tu&eacute;s : ',$frags,'</h3>
',($frags?'<ul>
'.$fragues.'</ul>
':''),'
<a href="evenements.php?id=',$_GET['id'],(isset($_GET['lite'])?'&amp;lite=1':''),'">&Eacute;v&egrave;nements</a>
<a href="fiche.php?id=',$_GET['id'],(isset($_GET['lite'])?'&amp;lite=1':''),'">Fiche</a>
<a href="casier.php?id=',$_GET['id'],(isset($_GET['lite'])?'&amp;lite=1':''),'">Casier judiciare</a>
<form method="get" action="frags.php">
<p>
',(isset($_GET['lite'])?'<input type="hidden" name="lite" value="1" />':''),'
Voir les morts du matricule : <input type="text" size="4" name="id" />
<input type="submit" value="Voir" />
</p>
</form>
</div>
';
}
else
  echo'<div id="frags">
<form method="get" action="frags.php">
<p>
',(isset($_GET['lite'])?'<input type="hidden" name="lite" value="1" />':''),'
Voir les morts du matricule : <input type="text" size="4" name="id" />
<input type="submit" value="Voir" />
</p>
</form>
</div>
';
if(isset($_GET['lite']))
  com_footer_lite();
else
  com_footer();
?>
