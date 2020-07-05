<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header();
require_once('../inits/camps.php');
require_once('../inits/terrains.php');
$where_arme='';
if(isset($_SESSION['com_perso']) && $_SESSION['com_perso'])
  {
    $camp=my_fetch_array('SELECT armee FROM persos WHERE ID='.$_SESSION['com_perso']);
    $camp=$camp[1]['armee'];
  }
else
  $camp=0;
$grade_min=0;
$grade_max=13;
$comp_min=0;
$comp_max=9;
$voir_armures=0;
$voir_gadgets=0;
$armure=7;
if(!empty($_REQUEST))
  {
    for($i=0;$i<=9;$i++)
      if(isset($_REQUEST['a'.$i]))
	{
	  if($where_arme)
	    $where_arme.='OR';
	  $where_arme.=' `type`='.$i.' ';
	}
    $voir_armures=isset($_REQUEST['a10']);
    $voir_gadgets=isset($_REQUEST['a11']);
    if(isset($_REQUEST['gi']) && is_numeric($_REQUEST['gi']))
      $grade_min=$_REQUEST['gi'];
    if(isset($_REQUEST['ga']) && is_numeric($_REQUEST['ga']))
      $grade_max=$_REQUEST['ga'];
    if(isset($_REQUEST['ci']) && is_numeric($_REQUEST['ci']))
      $comp_min=$_REQUEST['ci'];
    if(isset($_REQUEST['ca']) && is_numeric($_REQUEST['ca']))
      $comp_max=$_REQUEST['ca'];
    if(isset($_REQUEST['a']) && is_numeric($_REQUEST['a']))
      $armure=$_REQUEST['a'];
  }
else
  {
    $_REQUEST['gi']=0;
    $_REQUEST['ga']=13;
    $_REQUEST['ci']=0;
    $_REQUEST['ca']=9;
    if(isset($_SESSION['liste_matos']))
      for($i=0;$i<=9;$i++)
	if($_SESSION['liste_matos'] & $i)
	  {
	    if($where_arme)
	      $where_arme.='OR';
	    $where_arme.=' `type`='.$i.' ';
	  }
  }
    $lvl=array(10,
	       array(0,0),
	       array(1,1),
	       array(2,2),
	       array(3,3),
	       array(4,4),
	       array(5,5),
	       array(6,6),
	       array(7,7),
	       array(8,8),
	       array(9,9));
    $grades=array(14,
		  array(0,numero_camp_grade($camp,0)),
		  array(1,numero_camp_grade($camp,1)),
		  array(2,numero_camp_grade($camp,2)),
		  array(3,numero_camp_grade($camp,3)),
		  array(4,numero_camp_grade($camp,4)),
		  array(5,numero_camp_grade($camp,5)),
		  array(6,numero_camp_grade($camp,6)),
		  array(7,numero_camp_grade($camp,7)),
		  array(8,numero_camp_grade($camp,8)),
		  array(9,numero_camp_grade($camp,9)),
		  array(10,numero_camp_grade($camp,10)),
		  array(11,numero_camp_grade($camp,11)),
		  array(12,numero_camp_grade($camp,12)),
		  array(13,numero_camp_grade($camp,13)));
    $armures=array(7,
		   array(7,'Toutes'),
		   array(1,'L&eacute;g&egrave;re'),
		   array(2,'Moyenne'),
		   array(3,'L&eacute;g&egrave;re et moyenne'),
		   array(4,'Lourde'),
		   array(5,'L&eacute;g&egrave;re et lourde'),
		   array(6,'Moyenne et loudre')); 
echo' <div id="liste_armes" class="liste">
  <h2>&Eacute;quipements</h2>
  <p>Vous retrouvez ici l\'ensemble des &eacute;quipements actuellement disponibles pour les joueurs d\'i-tac. Vous disposez &eacute;galement d\'un moteur de recherche permettant de cibler au mieux ce que vos personnages peuvent utiliser. En raison des origines de ces &eacute;quipements et des paliers atteints par vos personnages, il se peut que vous retrouviez ici des armes et armures auxquelles vous n\'avez pas encore acc&eacute;s.</p>
  <form method="post" action="liste_armes.php">
   <ul id="options_armes">
    <li>',form_check('Assauts : ','a0'),'</li>
    <li>',form_check('Mitrailleuses : ','a1'),'</li>
    <li>',form_check('Snipers : ','a2'),'</li>
    <li>',form_check('Lance-Flammes : ','a3'),'</li>
    <li>',form_check('Lance-Roquettes : ','a4'),'</li>
    <li>',form_check('M&eacute;canos : ','a5'),'</li>
    <li>',form_check('Fusils &agrave; pompe : ','a6'),'</li>
    <li>',form_check('Corps &agrave; corps : ','a7'),'</li>
    <li>',form_check('M&eacute;decins : ','a8'),'</li>
    <li>',form_check('Pistolets : ','a9'),'</li>
    <li>',form_check('Armures : ','a10'),'</li>
    <li>',form_check('Gadgets : ','a11'),'</li>
   </ul>
   <ul id="options_grades">
    <li>',form_submit('o','Ok'),'</li>
   </ul>
  </form>
';
/*
    <li>',form_select('Entre les grades : ','gi',$grades,''),form_select('et : ','ga',$grades,''),'.</li>
    <li>',form_select('Comp&eacute;tence comprise entre : ','ci',$lvl,''),form_select('et : ','ca',$lvl,''),'</li>
    <li>',form_select('Armures : ','a',$armures,''),'</li>
 */
if($where_arme)
  affiche_armes($camp);
if($voir_gadgets)
  affiche_gadgets($camp);
if($voir_armures)
  affiche_armures($camp);
echo' </div>
';
com_footer();
function affiche_armes($camp)
{
  global $where_arme, $grade_min, $grade_max, $comp_min, $comp_max,$armure;
  $armes=my_fetch_array('SELECT DISTINCT munars.nom AS nom_munars,
                                         munars.poids AS poids_munars,
                                         armes.*
                         FROM armes
                            INNER JOIN munars
                            ON armes.type_munitions=munars.ID
                            LEFT OUTER JOIN armes_camps
                            ON armes_camps.arme=armes.ID
                         WHERE (armes.visibilite=1
                            OR  (armes.visibilite=2
                           AND  armes_camps.camp='.$camp.'))
                           AND ('.$where_arme.')
                           AND palier <= '.($grade_max+$comp_max).' 
                           AND palier >= '.($grade_min+$comp_min).' 
                           AND armure & '.$armure.' 
                         ORDER BY `type` ASC,
                                  grade ASC,
                                  lvl ASC');
  if($armes[0])
    {
      $type=-1;
      echo '  <h2>Liste des armes</h2>
  <table>
   <tr>
    <th>Nom</th>
    <th>Smiley</th>
    <th>Caract&eacute;ristiques</th>
    <th>Pr&eacute;requis</th>
    <th>Description</th>
   </tr>
';
      for($i=1;$i<=$armes[0];$i++)
	{
	  if($armes[$i]['type']!=$type)
	    {
	      echo '   <tr>
    <th colspan="5" class="type">',type_arme($armes[$i]['type']),'</th>
   </tr>
';
	      $type=$armes[$i]['type'];
	    }
	  if($armes[$i]['armure']==7)
	    $str_armures='Toutes';
	  else
	    {
	      $armures=dispo_armure($armes[$i]['armure']);
	      $str_armure='';
	      $k=0;
	      if($armures[0])
		{
		  $str_armure.=catArmure(1);
		  $k++;
		}
	      if($armures[1])
		{
		  if($k)
		    $str_armure.=', ';
		  $str_armure.=catArmure(2);
		  $k++;
		}
	      if($armures[2])
		{
		  if($k)
		    $str_armure.=', ';
		  $str_armure.=catArmure(4);
		}
	    }
	  echo'   <tr>
    <td>',bdd2html($armes[$i]['nom']),'</td>
    <td><img src="images/armes/',$armes[$i]['ID'],'.gif" alt="',bdd2html($armes[$i]['nom']),'" /></td>
    <td>
     <dl>
      <dt>Port&eacute;e :</dt>
      <dd>',$armes[$i]['portee'],' cases.</dd>
      <dt>D&eacute;g&acirc;ts :</dt>
      <dd>',$armes[$i]['degats'],'.</dd>
      <dt>Tirs :</dt>
      <dd>',$armes[$i]['tirs'],'</dd>
';
	  if($armes[$i]['type']==4 || $armes[$i]['type']==3)
	    echo'      <dt>Diminution des d&eacute;g&acirc;ts par case :</dt>
      <dd>',$armes[$i]['diminution'],'%.</dd>
';
	  echo'      <dt>Pr&eacute;cision minimum :</dt>
      <dd>',$armes[$i]['precision_min'],'%.</dd>
      <dt>Pr&eacute;cision maximum :</dt>
      <dd> ',$armes[$i]['precision_max'],'%.</dd>
      <dt>Critique :</dt>
      <dd>',$armes[$i]['degat_vie'],'/',$armes[$i]['critique'],'%.</dd>
      <dt>Seuil :</dt>
      <dd>',$armes[$i]['seuil_critique'],'.</dd>
';
	  if($armes[$i]['type']==4)
	    echo'      <dt>Rayon d\'effet :</dt>
      <dd>',$armes[$i]['zone'],' cases.</dd>
      <dt>Chances de toucher en cas de dispersion :</dt>
      <dd>',$armes[$i]['touche'],'%.</dd>
      <dt>Diminution des chances de toucher par case de distance :</dt>
      <dd>',$armes[$i]['dimit'],'%.</dd>
';
	  echo'      <dt>Munitions :</dt>
      <dd>',$armes[$i]['max_munitions'],'.</dd>
      <dt>Cadence :</dt>
      <dd>',$armes[$i]['tir_munars'],'.</dd>
      <dt>Type de munitions :</dt>
      <dd>',bdd2html($armes[$i]['nom_munars']),'.</dd>
      <dt>Malus au camouflage de :</dt>
      <dd>',$armes[$i]['malus_camou'],'.</dd>
      <dt>Poids :</dt>
      <dd>',$armes[$i]['poids'],'kg</dd>
      <dt>Poids charg&eacute; :</dt>
      <dd>',ceil($armes[$i]['poids']+$armes[$i]['max_munitions']*$armes[$i]['poids_munars']),'kg</dd>
     </dl>
    </td>
    <td class="pre">
     <dl>
      <dt>Grade + comp&eacute;tence :</dt>
      <dd>',$armes[$i]['palier'],'.</dd>
      <dt>Armure :</dt>
      <dd>',$str_armure,'</dd>
     </dl>
    </td>
    <td class="desc">',bdd2html($armes[$i]['description']),'</td>
   </tr>
';
	}
      echo'  </table>
';
    }
}

function affiche_gadgets($camp)
{
  $gadgets=my_fetch_array('SELECT gadgets.*,
                                  terrains.nom AS nom_terrain
                           FROM gadgets
                             LEFT OUTER JOIN terrains
                             ON terrains.ID=gadgets.type_pont
                           ORDER BY grade_1 ASC');
  if($gadgets[0])
    {
      echo'  <h2>Liste des gadgets</h2>
  <table>
   <colgroup>
    <col class="nom" />
    <col class="bonus" />
    <col class="pre" />
    <col class="desc" />
   </colgroup>
   <thead>
    <tr>
     <th>Nom</th>
     <th>Bonus</th>
     <th>Pr&eacute;requis</th>
     <th>Description</th>
    </tr>
   </thead>
   <tbody>
';
      for($i=1;$i<=$gadgets[0];$i++)
	{
	  if($gadgets[$i]['armure']==7)
	    $str_armure='toutes';
	  else
	    {
	      $armures=dispo_armure($gadgets[$i]['armure']);
	      $str_armure='';
	      $k=0;
	      if($armures[0])
		{
		  $str_armure.=camp_LE($camp);
		  $k++;
		}
	      if($armures[1])
		{
		  if($k)
		    $str_armure.=', ';
		  $str_armure.=camp_MO($camp);
		  $k++;
		}
	      if($armures[2])
		{
		  if($k)
		    $str_armure.=', ';
		  $str_armure.=camp_LO($camp);
		}
	    }
	  if($gadgets[$i]['arme']==1023)
	    $str_armes='toutes';
	  else
	    {
	      $armes=dispo_arme($gadgets[$i]['arme']);
	      $k=0;
	      $str_armes='';
	      for($j=0;$j<=9;$j++)
		{
		  if($armes[$j])
		    {
		      if($k)
			$str_armes.=', ';
		      $str_armes.=nom_type_arme($j);
		      $k++;
		    }
		}
	    }
	  echo'   <tr id="g',$gadgets[$i]['ID'],'">
    <td>',bdd2html($gadgets[$i]['nom']),'</td>
    <td>
     <dl>
';
	  if($gadgets[$i]['bonus_precision'])
	    echo'      <dt>Pr&eacute;cision :</dt>
      <dd>',$gadgets[$i]['bonus_precision'],'%</dd>
';
	  if($gadgets[$i]['bonus_tir_camou'])
	    echo'      <dt title="Malus au camouflage lors d\'un tir">MCT :</dt>
      <dd>-',$gadgets[$i]['bonus_tir_camou'],'%</dd>
';
	  if($gadgets[$i]['bonus_vision'])
	    echo'      <dt>Vision :</dt>
      <dd>',$gadgets[$i]['bonus_vision'],' cases</dd>
';
	  if($gadgets[$i]['bonus_camou'])
	    echo'      <dt>Camouflage :</dt>
      <dd>',$gadgets[$i]['bonus_camou'],'</dd>
';
	  if($gadgets[$i]['eclaireur'])
	    echo'      <dt>&Eacute;claireur :</dt>
      <dd>',$gadgets[$i]['eclaireur'],'</dd>
';
	  if($gadgets[$i]['escalade'])
	    echo'      <dt>Escalade :</dt>
      <dd>',$gadgets[$i]['escalade'],'</dd>
';
	  if($gadgets[$i]['foret'])
	    echo'      <dt>Forêt :</dt>
      <dd>',$gadgets[$i]['foret'],'</dd>
';
	  if($gadgets[$i]['montagne'])
	    echo'      <dt>Montagne :</dt>
      <dd>',$gadgets[$i]['montagne'],'</dd>
';
	  if($gadgets[$i]['desert'])
	    echo'      <dt>D&eacute;sert :</dt>
      <dd>',$gadgets[$i]['desert'],'</dd>
';
	  if($gadgets[$i]['marais'])
	    echo'      <dt>Marais :</dt>
      <dd>',$gadgets[$i]['marais'],'</dd>
';
	  if($gadgets[$i]['plaine'])
	    echo'      <dt>Plaine :</dt>
      <dd>',$gadgets[$i]['plaine'],'</dd>
';
	  if($gadgets[$i]['nage'])
	    echo'      <dt>Nage :</dt>
      <dd>',$gadgets[$i]['nage'],'</dd>
';
	  if($gadgets[$i]['mines'])
	    echo'      <dt>Mines :</dt>
      <dd>',$gadgets[$i]['mines'],'</dd>
      <dt>Caracs :</dt>
      <dd>
       <dl>
        <dt>D&eacute;g&acirc;ts :</dt>
        <dd>',$gadgets[$i]['degats_mines'],' (',$gadgets[$i]['pourcent_PV'],'% dans les PV)</dd>
        <dt>Instabilit&eacute; :</dt>
        <dd>',$gadgets[$i]['instabilite'],'%</dd>
        <dt>Discr&eacute;tion :</dt>
        <dd>',$gadgets[$i]['discretion'],'%</dd>
       </dl>
      </dd>
';
	  echo'      <dt>Position :</dt>
      <dd>',$gadgets[$i]['stack'],'</dd>
';
	  echo'     </dl>
    </td>
    <td>
     <dl>
      <dt>Armures :</dt>
      <dd>',$str_armure,'</dd>
      <dt>Armes de type :</dt>
      <dd>',$str_armes,'</dd>
      <dt title="Primaire, Secondaire, Tertiaire">Grade :</dt>
      <dd>',numero_camp_grade($camp,$gadgets[$i]['grade_1']),', ',numero_camp_grade($camp,$gadgets[$i]['grade_2']),', ',numero_camp_grade($camp,$gadgets[$i]['grade_3']),'.</dd>
     </dl>
    </td>
    <td>',bdd2html($gadgets[$i]['description']),'</td>
   </tr>
';
	}
      echo'   </tbody>
  </table>
';
    }
}

function affiche_armures($camp)
{
  $type=0;
  $armures=my_fetch_array('SELECT DISTINCT armures.*
                           FROM armures
                             LEFT OUTER JOIN armures_camps
                             ON armures_camps.armure=armures.ID
                           WHERE visibilite=1
                              OR (visibilite=2
                             AND armures_camps.camp='.$camp.')
                           ORDER BY `type` ASC,
                                    `PA` ASC,
                                    `grade` ASC');
  if($armures[0])
    {
      echo'  <h2>Liste des armures</h2>
  <table>
   <tr>
    <th>Nom</th>
    <th>R&eacute;sistance</th>
    <th>Malus de mouvement</th>
    <th>P&eacute;nalit&eacute; de camouflage</th>
    <th>Bonus de pr&eacute;cision</th>
    <th>R&eacute;sistance aux critiques</th>
    <th>Poids portable</th>
    <th>Grade</th>
    <th>Description</th>
   </tr>
';
      for($i=1;$i<=$armures[0];$i++)
	{
	  if($armures[$i]['type']!=$type)
	    {
	      $type=$armures[$i]['type'];
	      echo'   <tr>
    <th class="type" colspan="9">',type_nom_armure($type,$camp),'</th>
   </tr> 
';
	    }
	  echo'   <tr id="am',$armures[$i]['ID'],'">
    <td>',bdd2html($armures[$i]['nom']),' (',bdd2html($armures[$i]['initiales']),')</td>
    <td>',$armures[$i]['PA'],' PAs ',($armures[$i]['PA_critiques']?'(critiques : '.$armures[$i]['PA_critiques'].')':''),'</td>
    <td>',$armures[$i]['malus_terrain'],'%</td>
    <td>',$armures[$i]['malus_camou'],'</td>
    <td>',$armures[$i]['bonus_precision'],'%</td>
    <td>',$armures[$i]['malus_critique'],'%</td>
    <td>',$armures[$i]['capacite'],' kg</td>
    <td>',numero_camp_grade($camp,$armures[$i]['grade']),'</td>
    <td>',bdd2html($armures[$i]['desc']),'</td>
   </tr>
';
	}
  echo'  </table>
';
    }
}
?>
