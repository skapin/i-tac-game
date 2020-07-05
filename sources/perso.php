<?php
// On calcule la taille de la barre de confiance.
if($perso['confiance']>0){
  $taille=100+$perso['confiance']*100/$GLOBALS['gain_grade'];
}
else if($perso['confiance']<0){
  $taille=100-$perso['confiance']*100/$GLOBALS['perte_grade'];
}
else{
  $taille=100;
}



$carto = $perso['cartographier'];


if($perso['cartographier']==1){
  $carto = "Oui";
}
else if($perso['cartographier']==2){
  $carto = "En mission";
}

else{
  $carto = "Non";
}

// Calcul de la regen actuelle.
$regen=$perso['imp_regen'];
if(!$perso['peine_hopital'] || 
   !$perso['peine_fin'] || 
   $perso['peine_fin']<$time){
  $qgs=my_fetch_array('SELECT DISTINCT qgs.regeneration,qgs.ID,qgs.X,qgs.Y,qgs.utilisation
 FROM qgs
WHERE qgs.carte='.$perso['map'].'
  AND qgs.X+qgs.utilisation>='.$perso['X'].'
  AND qgs.X-qgs.utilisation<='.$perso['X'].'
  AND qgs.Y+qgs.utilisation>='.$perso['Y'].'
  AND qgs.Y-qgs.utilisation<='.$perso['Y'].'
  AND (qgs.camp='.$perso['armee'].' OR qgs.type=1)');
  foreach($qgs AS $value){
    if(isset($value['X']) &&
       sqrt(pow($value['X']-$perso['X'],2)+pow($value['Y']-$perso['Y'],2))<=$value['utilisation'] &&
       !is_bloque($value['ID'])){
	$regen+=$value['regeneration'];
    }
  }
}
else if($perso['map']==0){
  $regen+=5;
}
if($_SESSION['com_terrain']){
  // S'il le faut, on applique le malus de terrain.
  if($_SESSION['com_terrain']['debut_perte']<=(100-$perso['PV']/$perso['PV_max']*100)){
    $regen-=$_SESSION['com_terrain']['malus_regen'];
  }
}
// Application des malus dus aux PVs en moins (-1 par 20% de PV perdus).
if($perso['PV']<$perso['PV_max']*0.8){
  $malusEtat=floor((1-$perso['PV']/$perso['PV_max'])/0.2);
  $regen-=$malusEtat;
}

echo'  <div id="moipersoFrame" class="framed">
  <dl id="persoInfos">
   <dd>',bdd2html($perso['nom']),'</dd>
   <dt>PV :</dt>
   <dd'.c($perso['PV'],$perso['PV_max']).'>',max(1,floor($perso['PV'])),'/',$perso['PV_max'],'</dd>
   <dt>PA :</dt>
   <dd'.c($perso['PA'],$perso['PA_max']).'>',max(0,floor($perso['PA'])),'/',$perso['PA_max'],'</dd>
   <dt>Camp :</dt>
   <dd><a href="camp.php?id=',$perso['armee'],($framed?'&amp;lite=1':''),'">',camp_nom($perso['armee']),'</a></dd>
   <dt>Groupe :</dt>
   <dd>',($perso['ID_compa']!=1?'<a href="compagnies.php?id='.$perso['ID_compa'].($framed?'&amp;lite=1':'').'">':''),bdd2html($perso['nom_compa']),($perso['ID_compa']!=1?'</a>':''),'</dd>
   <dt>Grade :</dt>
   <dd>',($perso['ID_grade']?grade_spec().' ('.numero_camp_grade($perso['armee'],$perso['grade_reel']).')':numero_camp_grade($perso['armee'],$perso['grade_reel'])),'</dd>
   <dt>Mission :</dt>
   <dd>',bdd2html($perso['nom_mission']),'</dd>
   <dt>Vision :</dt>
   <dd>',$perso['vision'],($perso['vision']!=$perso['vision_reelle']?' ('.$perso['vision_reelle'].')':''),' cases</dd> 
   <dt>R&eacute;g&eacute;n&eacute;ration :</dt>
   <dd'.c($regen,-5).'>',$regen,($perso['imp_regen']!=$regen?' ('.$perso['imp_regen'].')':''),'</dd>
   <dt>Position :</dt>
   <dd>X=',($perso['coordonnees']?$perso['X']:0),' / Y=',($perso['coordonnees']?$perso['Y']:0),'</dd>
   <dt>PT :</dt>
   <dd'.c($perso['PM'],100).'>',round($perso['PM']),'</dd>
   <dt>Cartographier :</dt>
   <dd><p>' . $carto . '</p></dd>
   <dt>Confiance :</dt>
   <dd><p style="width:',round($taille),'px;height:10px;background:url(\'styles/',$_SESSION['skin'],'/img/barre.gif\');margin-top:5px;">&nbsp;</p></dd>
  </dl>
';
$_POST['new_message']=(isset($_POST['new_message'])?post2text($_POST['new_message']):bdd2text($perso['message']));

echo'
  <form method="post" action="jouer.php"'.($framed?' target="_parent"':'').'>
   <h2>Message du jour</h2>
   ',form_textarea('','new_message','15','35'),'
   <input type="submit" name="new_message_ok" value="Modifier mon message" id="message_ok" />
  </form> 
';


if($time-$perso['date_last_shot']<$GLOBALS['tour']){
  $temps=temps(max(0,$perso['date_last_shot']+$GLOBALS['tour']-$time));
  $next_armure=$temps['heures'].'h'.$temps['minutes'].'mn';
}
else{
  $next_armure='n/a';
}
if($perso['PM']<100 || $perso['sprints']>0){
  $temps=temps(max(0,$perso['date_last_PM']+$GLOBALS['tour']/100-$time));
  $plein=temps(max(0,(100-$perso['PM'])*$GLOBALS['tour']/100));
  $next_PM=$temps['minutes'].'mn (maximum atteint dans '.$plein['heures'].'h'.$plein['minutes'].'mn)';
}
else{
  $next_PM='n/a';
}

if($perso['tir_restants']<100 && 
   $perso['nbr_tirs_arme'.$slot] &&
   $perso['nbr_tirs_arme'.$slot2]){
  $plop=$perso['nbr_tirs_arme'.$slot]*$GLOBALS['tour']/100;

  $temps=temps((($perso['tirs_restants_arme'.$slot]+1)/$perso['nbr_tirs_arme'.$slot]*$GLOBALS['tour'])-$perso['tir_restants']*$GLOBALS['tour']/100);
  $temps2=temps((($perso['tirs_restants_arme'.$slot2]+1)/$perso['nbr_tirs_arme'.$slot2]*$GLOBALS['tour'])-$perso['tir_restants']*$GLOBALS['tour']/100);
  $plein=temps(max(0,(100-$perso['tir_restants'])*$GLOBALS['tour']/100));
  $next_tir=$temps['heures'].'h'.$temps['minutes'].'mn / '.$temps2['heures'].'h'.$temps2['minutes'].'mn (maximum dans '.$plein['heures'].'h'.$plein['minutes'].'mn)';
}
else{
  $next_tir='n/a';
}
if($time-$perso['date_last_used_gad1']<$GLOBALS['tour']){
  $temps=temps($perso['date_last_used_gad1']+$GLOBALS['tour']-$time);
  $next_gad1=$temps['heures'].'h'.$temps['minutes'].'mn';
}
else{
  $next_gad1='n/a';
}
if($time-$perso['date_last_used_gad2']<$GLOBALS['tour']){
  $temps=temps($perso['date_last_used_gad2']+$GLOBALS['tour']-$time);
  $next_gad2=$temps['heures'].'h'.$temps['minutes'].'mn';
}
else{
  $next_gad2='n/a';
}
if($time-$perso['date_last_used_gad3']<$GLOBALS['tour']){
  $temps=temps($perso['date_last_used_gad3']+$GLOBALS['tour']-$time);
  $next_gad3=$temps['heures'].'h'.$temps['minutes'].'mn';
}
else{
  $next_gad3='n/a';
}
echo'<h2 class="clearer">R&eacute;cup&eacute;rations</h2>
<ul>
<li>Prochain mouvements : ',$next_PM,'</li>
<li>Prochain tir : ',$next_tir,'</li>
<li>R&eacute;g&eacute;n&eacute;ration actuelle : ',$regen,'</li>
<li>Changement d\'armure possible dans : ',$next_armure,'</li>
<li>Sprints &agrave; r&eacute;cup&eacute;rer : ',round($perso['sprints']),'</li>
<li>Changement et rechargement du gadget primaire possible dans : ',$next_gad1,'</li>
<li>Changement et rechargement du gadget secondaire possible dans : ',$next_gad2,'</li>
<li>Changement et rechargement du gadget tertiaire possible dans : ',$next_gad3,'</li>
</ul>
';
if($perso['malus_precision'] < 1||$perso['malus_mouvement'] != 1){
  echo'<h2>Malus</h2>
<ul>
<li>Pr&eacute;cision * ',$perso['malus_precision'],'</li>
<li>Co&ucirc;t des mouvements *  ',$perso['malus_mouvement'],'</li>
</ul>
';
}
if($perso['map']){
  echo'<h2>Camouflage</h2>
<ul>
<li>Comp&eacute;tence camouflage niveau ',$perso['camou'],' : '.comp('camou','reussite').'%</li> 
';
  if($perso['camouflage']==1){
    echo'<li>Vous &ecirc;tes parfaitement camoufl&eacute; : +10%</li>
';
    $bonus_camou=10;
  }
  if($perso['camouflage']==2){
    echo'<li>Vous &ecirc;tes bien camoufl&eacute; : +5%</li>
';
    $bonus_camou=5;
  }
  if($perso['camouflage']==3){
    echo'<li>Vous &ecirc;tes camoufl&eacute;</li>
';
    $bonus_camou=0;
  }
  if($perso['camouflage']==4){
    echo'<li>Vous &ecirc;tes mal camoufl&eacute; : -5%</li>
';
    $bonus_camou=-5;
  }
  if($perso['camouflage']==5){
    echo'<li>Vous &ecirc;tes trés mal camoufl&eacute; : -10%</li>
';
    $bonus_camou=-10;
  }
  if($perso['camouflage']==0||$perso['camouflage']==6){
    echo'<li>Vous n\'&ecirc;tes pas camoufl&eacute; : -15%</li>
';
    $bonus_camou=-15;
  }
  if((($time-$perso['date_last_tir'])>$GLOBALS['tour']/2) || !$perso['malus_camou_tir']){
    $malus_tir=0;
  }
  else{
    $malus_tir=round($perso['malus_camou_tir']-($time-$perso['date_last_tir'])*$perso['malus_camou_tir']/($GLOBALS['tour']/2));
  }
  echo'<li>Malus d&ucirc; &agrave; vos tirs : -',$malus_tir,'%</li>
 <li>Malus d&ucirc; &agrave; votre armure : -',$perso['malus_camou_armure'],'%</li>
 <li>Malus d&ucirc; au terrain : -',($_SESSION['com_terrain']['malus_camou'] * comp($_SESSION['com_terrain']['competence'],'camouflage')),'%</li>
</ul>
';
  $camou=min(99,$bonus_camou - $malus_tir + comp('camou','reussite') - $_SESSION['com_terrain']['malus_camou'] * comp($_SESSION['com_terrain']['competence'],'camouflage') - $perso['malus_camou_armure']);
  echo'<p>Chances de vous camoufler : '.$camou.'%</p>';
}
echo'</div>
<div id="moicompFrame" class="framed liste">
';
if($perso['stages']&&!$perso['map']){
  foreach($_POST as $key=>$value){
    $comp=explode('_',$key);
    if(isset($comp[1],$_POST['confirm_'.$key]) &&
       $comp[0]=='monte' &&
       nom_comp($comp[1])!='' &&
       $perso[$comp[1]]<$GLOBALS['competences'][$comp[1]]['stage']){
      unset($_POST[$key]);
      $gain=5000/($perso[$comp[1]]+1);
      $manque=($perso[$comp[1]]+1)*10000-$perso[$comp[1].'_reel'];
      if($gain<$manque){
	// Ca ne finit pas un niveau de compétence.
	$gain_reel=$gain;
      }
      else{
	$perso[$comp[1]]++;
	// Ca dépasse, on va calculer le gain.
	if($perso[$comp[1]]+1>=$GLOBALS['competences'][$comp[1]]['stage']){
	  $gain_reel=$manque;
	}
	else{
	  $gain_reel=$manque+($gain-$manque)*($perso[$comp[1]]+1)/($perso[$comp[1]]+2);
	}
      }
      request('UPDATE persos SET '.$comp[1].'='.$comp[1].'+'.$gain_reel.', stages=stages-1 WHERE ID='.$_SESSION['com_perso']);
      $perso['stages']--;
      $perso[$comp[1].'_reel']+=$gain_reel;
    }
  }
}
echo'<p>Stages disponibles : '.$perso['stages'].'</p>',($perso['stages']&&!$perso['map']?'
<form method="post" action="jouer.php">':''),'
<table id="listeComp">
<tr>
 <th>Comp&eacute;tence</th>
 <th>Niveau</th>
 <th>Progression</th>
 <th>Stage</th>
 <th>Niveau max obtenable par stage</th>
</tr>
',affiche_comp('camou'),'
',affiche_comp('ecl'),'
',affiche_comp('mine'),'
',affiche_comp('demine'),'
',affiche_comp('infi'),'
',affiche_comp('escalade'),'
<tr>
 <th colspan="5">Comp&eacute;tences d\'arme</th>
</tr>
',affiche_comp('assaut'),'
',affiche_comp('mitrailleuse'),'
',affiche_comp('lourde'),'
',affiche_comp('LR'),'
',affiche_comp('snipe'),'
',affiche_comp('pompe'),'
',affiche_comp('lekarz'),'
',affiche_comp('cac'),'
',affiche_comp('biotech'),'
',affiche_comp('pistolet'),'
<tr>
 <th colspan="5">Terrains</th>
</tr>
',affiche_comp('plaine'),'
',affiche_comp('foret'),'
',affiche_comp('montagne'),'
',affiche_comp('desert'),'
',affiche_comp('marais'),'
</table>',($perso['stages']&&!$perso['map']?'
</form>':''),'';
echo'</div>
';
function affiche_arme($slot){
  global $perso;
  if(!$perso['nom_arme'.$slot]){
    return;
  }
  echo'<strong>',bdd2html($perso['nom_arme'.$slot]),'</strong>
<ul>
<li>Pr&eacute;cision minimale : ',$perso['precision_min_arme'.$slot],'%</li>
<li>Pr&eacute;cision maximale : ',$perso['precision_max_arme'.$slot],'%</li>
<li>D&eacute;g&acirc;ts : ',$perso['degats_arme'.$slot],'</li>
<li>D&eacute;g&acirc;ts critiques maximum : ',$perso['degats_vie_arme'.$slot],'</li>
<li>Chances de critique : ',$perso['critique_arme'.$slot],'%</li>
<li>Pr&eacute;cision &agrave; atteindre pour pouvoir augmenter les chances de critique : ',$perso['seuil_critique_arme'.$slot],'%</li>
<li>Cadence de tir : ',$perso['nbr_tirs_arme'.$slot],'</li>
<li>Munitions par tir : ',$perso['cadence_arme'.$slot],'</li>
<li>malus aux chance de camouflage : ',$perso['malus_camou_arme'.$slot],'%</li>
<li>Munitions : ',$perso['munars_arme'.$slot],'/',$perso['munars_max_arme'.$slot],'</li>
</ul>
';
}


function affiche_gadget($i)
{
  global $perso;
  if(!$perso['nom_gad'.$i]){
    return;
  }
    echo'<strong>',bdd2html($perso['nom_gad'.$i]),'</strong>
<ul>
';
    if($perso['bonus_precision_gad'.$i])
    echo'<li>Bonus &agrave; la pr&eacute;cision: ',$perso['bonus_precision_gad'.$i],'%</li>
';
  if($perso['camou_gad'.$i])
    echo'<li>Bonus au camouflage: ',$perso['camou_gad'.$i],'%</li>
';
  if($perso['bonus_tir_camou_gad'.$i])
    echo'<li>Diminution du malus au camouflage lors d\'un tir: ',$perso['bonus_tir_camou_gad'.$i],' %</li>
';
  if($perso['bonus_vision_gad'.$i])
    echo'<li>Bonus &agrave; la vision : ',$perso['bonus_vision_gad'.$i],'</li>
';
  if($perso['ecl_gad'.$i])
    echo'<li>Bonus &agrave; la comp&eacute;tence &eacute;claireur : ',$perso['ecl_gad'.$i],'</li>
';
  if($perso['foret_gad'.$i])
    echo'<li>Bonus &agrave; la comp&eacute;tence Forêt: ',$perso['foret_gad'.$i],'</li>
';
  if($perso['montagne_gad'.$i])
    echo'<li>Bonus &agrave; la comp&eacute;tence Montagne: ',$perso['montagne_gad'.$i],'</li>
';
  if($perso['desert_gad'.$i])
    echo'<li>Bonus &agrave; la comp&eacute;tence D&eacute;sert: ',$perso['desert_gad'.$i],'</li>
';
  if($perso['marais_gad'.$i])
    echo'<li>Bonus &agrave; la comp&eacute;tence Marais: ',$perso['marais_gad'.$i],'</li>
';
  if($perso['plaine_gad'.$i])
    echo'<li>Bonus &agrave; la comp&eacute;tence Plaine: ',$perso['plaine_gad'.$i],'</li>
';
  if($perso['nage_gad'.$i])
    echo'<li>Bonus &agrave; la comp&eacute;tence Nage : ',$perso['nage_gad'.$i],'</li>
';
  if($perso['pont_gad'.$i])
    echo'<li>Bonus &agrave; la comp&eacute;tence Pont : ',$perso['pont_gad'.$i],'</li>
';
  if($perso['escalade_gad'.$i])
    echo'<li>Bonus &agrave; la comp&eacute;tence Escalade : ',$perso['escalade_gad'.$i],'</li>
';
  if($perso['ponts_gad'.$i])
    echo'<li>Nombre de ponts: ',$perso['ponts_gad'.$i],'</li>
';
  if($perso['mines_gad'.$i])
    echo'<li>Nombre de mines: ',$perso['mines_gad'.$i],'</li>
';
  if($perso['degats_mines_gad'.$i])
    echo'<li>D&eacute;g&acirc;ts des mines: ',$perso['degats_mines_gad'.$i],'%</li>
';
  echo'</ul>
  ';
}

/*<p>Stages disponibles : '.$perso['stages'].'</p>
<ul>
<li>Camouflage : ',$perso['camou'],'</li>
*/
function affiche_comp($nom)
{
  global $perso,$g1,$g2,$g3,$time,$slot;
  switch($nom)
    {
    case'camou':
    case'escalade':
    case'plaine':
    case'foret':
    case'montagne':
    case'desert':
    case'marais':
    case'nage':
    case'pont':
      $perso[$nom.'_brut']=max(0,min(9,$perso[$nom]-$g1[$slot]*$perso[$nom.'_gad1']-$g2[$slot]*$perso[$nom.'_gad2']-$g3[$slot]*$perso[$nom.'_gad3']));
      break;
    case'ecl':
      $perso['ecl_brut']=max(0,min(9,$perso['ecl']-$g1[$slot]*$perso['ecl_gad1']-$g2[$slot]*$perso['ecl_gad2']-$g3[$slot]*$perso['ecl_gad3']-(($perso['date_ecl']+$GLOBALS['tour']/5)>$time?2:0)));
      break;
    default:
      $perso[$nom.'_brut']=$perso[$nom];
      break;
    }
  $taux=max(0,min(10,floor(($perso[$nom.'_reel']-$perso[$nom.'_brut']*10000)/1000)));
  $autre=' <td></td>
';
  if($perso['stages'] && $perso[$nom.'_brut']<$GLOBALS['competences'][$nom]['stage'] && !$perso['map']){
    $autre=' <td>'.form_check('Monter ','confirm_monte_'.$nom).' '.form_submit('monte_'.$nom,'ok').'</td>
';
  }
  return '<tr>
 <td>'.nom_comp($nom).'</td>
 <td>'.$perso[$nom].($perso[$nom]!=$perso[$nom.'_brut']?' (r&eacute;el: '.$perso[$nom.'_brut'].')':'').'</td>
 <td><img src="styles/'.$_SESSION['skin'].'/img/prog_'.$taux.'.gif" title="Progression : '.($taux*10).'%" alt="'.($taux*10).'%" /></td>
'.$autre.'
 <td>'.$GLOBALS['competences'][$nom]['stage'].'</td>
</tr>';
}

function c($valeur,$max)
{
  if($max<=0)
    {
      if($valeur<$max)
	{
	  $plop=0;
	}
      else if($valeur<0)
	{
	  $plop=1;
	}
      else if($valeur<-$max/2)
	{
	  $plop=2;
	}
      else if($valeur<-$max)
	{
	  $plop=3;
	}
      else if($valeur>=-$max)
	{
	  $plop=4;
	}
    }
  else
    {
      $plop=round($valeur/$max*4);
    }
  return ' class="etat'.$plop.'"';
}
/*
<h2>Mouchard</h2>
<p>
Votre mouchard permet &agrave; votre Etat-Major de conna&icirc;tre vos actions et ainsi vous faire avancer dans la hi&eacute;rarchie suivant votre m&eacute;rite. Celui ci ne peut etre activ&eacute; ou desactiv&eacute; qu\'une fois toutes les 33h.<br />
Il est actuellement ',($perso['mouchard']?'actif':'&eacute;teint'),'.</p>
';
// Peut on activer/desactiver le mouchard ?
$temps=temps($perso['date_mouchard']+$GLOBALS['tour']*1.5-$time);
if($perso['date_mouchard']+$GLOBALS['tour']*1.5<=$time)
  {
    if(isset($_POST['mouchard_conf'],$_POST['mouchard_ok']))
      {
	request('UPDATE persos
 SET mouchard=\''.($perso['mouchard']?0:1).'\',
 date_mouchard=\''.$time.'\'
 WHERE ID=\''.$_SESSION['com_perso'].'\'');
	require_once('sources/monperso.php');
      }
    else
      echo'<form method="post" action="jouer.php">
<p>
',form_check(($perso['mouchard']?'D&eacute;sactiver':'Activer').' votre mouchard : ','mouchard_conf'),' ',form_submit('mouchard_ok','Ok'),'</p>
</form>
'; 
  }
else
  echo '<p>Changement d\'&eacute;tat possible dans ',$temps['heures'],'h',$temps['minutes'],'mn.</p>
';

 */
?>