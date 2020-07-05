<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header();
if(isset($_SESSION['com_perso']) && $_SESSION['com_perso']){
  $camp=my_fetch_array('SELECT armee FROM persos WHERE ID='.$_SESSION['com_perso']);
  $camp=$camp[1]['armee'];
}
else{
  $camp=0;
}
$lescamps=my_fetch_array('SELECT ID,
nom
FROM camps
WHERE (camps.visible=1 OR camps.ID='.$camp.')');
$lescamps[0]++;
$lescamps[$lescamps[0]]=array(0,'Tous');
echo' <div id="liste_persos" class="liste">
  <h2>Personnages</h2>
  <p>Vous pouvez consulter ici la liste de l\'ensemble des personnages d\'i-tac.</p>
  <form method="get" action="liste_persos.php">
   <p>',form_select('Voir les personnages ','id',$lescamps,''),'
',form_submit('camp_ok','Ok'),'</p>
  </form>
';
if($lescamps[0]){
  echo'<ul>
';
  for($i=1;$i<$lescamps[0];$i++){
    $nbr_persos=my_fetch_array('SELECT COUNT(*) FROM persos WHERE armee='.$lescamps[$i]['ID']);
    echo'<li>'.bdd2html($lescamps[$i]['nom']).' : '.$nbr_persos[1][0].' personnages</li>
' ;
  }
  echo'</ul>
';
}
if(isset($_GET['id']) && is_numeric($_GET['id'])){
  if(isset($_GET['debut']) && is_numeric($_GET['debut'])){
    $debut=$_GET['debut'];
  }
  else{
    $debut=0;
  }
  $persos=my_fetch_array('SELECT persos.nom,
persos.armee,
persos.grade_reel, 
grades.nom AS grade,
grades.niveau AS niveau,
grades.ID AS grade_ID,
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
 WHERE '.($_GET['id']?'persos.armee='.$_GET['id'].' AND ':'').'
 (camps.visible=1 OR camps.ID='.$camp.')
ORDER BY mat ASC
LIMIT '.$debut.', 50');
  $nbr_persos=my_fetch_array('SELECT COUNT(*)
FROM persos
INNER JOIN camps
ON persos.armee=camps.ID 
WHERE '.($_GET['id']?'persos.armee='.$_GET['id'].' AND ':'').'
 (camps.visible=1 OR camps.ID='.$camp.')');

  if($persos[0]){
    $liens='</ul>
<ul class="liste_liens">
';
    if($debut>=50){
      $liens.='<li><a href="liste_persos.php?id='.$_GET['id'].'&amp;debut=0">50 premiers</a></li>
';
    }
    else{
      /*      $liens.='<li>50 premiers</li>
       ';*/
    }
    if($debut>0){
      $liens.='<li><a href="liste_persos.php?id='.$_GET['id'].'&amp;debut='.max($debut-50,0).'">50 pr&eacute;c&eacute;dents</a></li>
';
    }
    else{
      /*      $liens.='<li>50 pr&eacute;c&eacute;dents</li>
       ';*/
    }

    if($debut+50<=$nbr_persos[1][0]){
      $liens.='<li><a href="liste_persos.php?id='.$_GET['id'].'&amp;debut='.($debut+50).'">50 suivants</a></li>
';
      if($debut+50<$nbr_persos[1][0]){
	$liens.='<li><a href="liste_persos.php?id='.$_GET['id'].'&amp;debut='.($nbr_persos[1][0]-50).'">50 derniers</a></li>
';
      }
      else{
	/*	$liens.='<li>50 derniers</li>
	 ';*/
      }
    }
    else{
      /*      $liens.='<li>50 suivants</li>
<li>50 derniers</li>
 ';*/
    }
    echo $liens.'</ul>
<ul>
';
    for($i=1;$i<=$persos[0];$i++)
    {
      echo'<li>',bdd2html($persos[$i]['nom']),' (<a href="fiche.php?id=',$persos[$i]['mat'],'">',bdd2html($persos[$i]['init_camp']),'-',bdd2html($persos[$i]['init_compa']),'-',$persos[$i]['mat'],'</a>) : ',($persos[$i]['grade']?bdd2html(grade_spec_autre($persos[$i]['armee'],$persos[$i]['grade_ID'],$persos[$i]['grade'])).' ('.numero_camp_grade($persos[$i]['armee'],$persos[$i]['grade_reel']).') '.($persos[$i]['niveau']==1?' du groupe <a href="compagnies.php?id='.$persos[$i]['compa_ID'].'">'.bdd2html($persos[$i]['compagnie']).'</a>':''):numero_camp_grade($persos[$i]['armee'],$persos[$i]['grade_reel'])),'</li>
';
    }
    echo $liens;
  }
}
echo' </div>
';
com_footer();
?>