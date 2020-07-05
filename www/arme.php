<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header_lite();
if(isset($_GET['id']) &&
   is_numeric($_GET['id'])){
  if(isset($_SESSION['com_perso']) && $_SESSION['com_perso']){
      $camp=my_fetch_array('SELECT armee,imp_force FROM persos WHERE ID='.$_SESSION['com_perso']);
      $force=$camp[1]['imp_force'];
      $camp=$camp[1]['armee'];
  }
  else{
    $camp=0;
  }
  $arme=my_fetch_array('SELECT armes.*,
munars.nom AS nom_m,
munars.poids AS poids_m 
FROM armes
  INNER JOIN munars
    ON armes.type_munitions=munars.ID 
  LEFT OUTER JOIN armes_camps
    ON armes.ID=armes_camps.arme
   AND armes_camps.camp='.$camp.' 
WHERE (armes.visibilite=1
    OR armes_camps.camp IS NOT NULL
   AND armes.visibilite=2)
   AND armes.ID='.$_GET['id'].'
LIMIT 1');
  if($arme[0])
    {
      $str_armure='';
      $k=0;
      $lesarmures=dispo_armure($arme[1]['armure']);
      if($lesarmures[0] || ($lesarmures[1]&&$force>=5))
	{
	  $str_armure.=camp_LE($camp);
	  $k++;
	}
      if($lesarmures[1])
	{
	  if($k)
	    $str_armure.=', ';
	  $str_armure.=camp_MO($camp);
	  $k++;
	}
      if($lesarmures[2])
	{
	  if($k)
	    $str_armure.=', ';
	  $str_armure.=camp_LO($camp);
	}
      echo'<div id="arme">
<h1>',bdd2html($arme[1]['nom']),'</h1>
<dl>
<dt>Portée :</dt>
<dd>',$arme[1]['portee'],' cases.</dd>
<dt>Dégâts :</dt>
<dd> ',$arme[1]['degats'],'.</dd>
<dt>Tirs :</dt>
<dd>',$arme[1]['tirs'],'</dd>
<dt>Précision minimum :</dt>
<dd>',$arme[1]['precision_min'],'%.</dd>
<dt>Précision maximum :</dt>
<dd>',$arme[1]['precision_max'],'%.</dd>
<dt>Critique :</dt>
<dd>',$arme[1]['degat_vie'],'/',$arme[1]['critique'],'%.</dd>
<dt>Seuil :</dt>
<dd>',$arme[1]['seuil_critique'],'.</dd>
<dt>Munitions :</dt>
<dd>',$arme[1]['max_munitions'],'.</dd>
<dt>Cadence :</dt>
<dd>',$arme[1]['tir_munars'],'.</dd>
<dt>Type de munitions :</dt>
<dd>',bdd2html($arme[1]['nom_m']),'.</dd>
<dt>Malus au camouflage de :</dt>
<dd>',$arme[1]['malus_camou'],'.</dd>
<dt>Grade :</dt>
<dd>',numero_camp_grade($camp,$arme[1]['grade']),'.</dd>
<dt>Niveau :</dt>
<dd>',$arme[1]['lvl'],'.</dd>
<dt>Armure :</dt>
<dd>',$str_armure,'</dd>
<dt>Poids avec munitions :</dt>
<dd>',ceil($arme[1]['poids']+$arme[1]['poids_m']*$arme[1]['max_munitions']),' kg</dd>
</dl> 
',(bdd2html($arme[1]['description'])?'<p>'.bdd2html($arme[1]['description']).'
</p>
</div>':'');
    }
}
com_footer_lite();
?>
