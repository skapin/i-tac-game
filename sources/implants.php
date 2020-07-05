<?php
$imps=array('PV'=>array('max'=>-1,
			'nom'=>'Point de vie',
			'desc'=>'+5 PV',
			'PV'=>5,
			'resume'=>'Point de vie : $imp0$ (+$imp1$ PV)',
			'factors'=>array(1,5)),
	    'vue'=>array('max'=>8,
			 'nom'=>'Vision',
			 'desc'=>'+1 &agrave; la vision',
			 'resume'=>'Vision : $imp0$ (+$imp0$ à la vision)',
			 'factors'=>array(1)),
	    'resist'=>array('max'=>10,
			    'nom'=>'R&eacute;sistance &agrave; la douleur',
			    'desc'=>'-10% malus de blessure',
			    'resume'=>'Résistance à la douleur : $imp0$ (-$imp1$% de malus de blessures)',
			    'factors'=>array(1,5)),
	    'regen'=>array('max'=>5,
			   'nom'=>'R&eacute;g&eacute;n&eacute;ration',
			   'desc'=>'
     <ul>
      <li>+1 &agrave; la r&eacute;g&eacute;n&eacute;ration</li>
      <li>-10% malus de r&eacute;g&eacute;n&eacute;ration d&ucirc; au terrain</li>
     </ul>
     ',
			   'resume'=>'Régénération : $imp0$ (-$imp1$% au malus de régénération dû aux terrains)',
			   'factors'=>array(1,10)),
	    'vit1'=>array('max'=>10,
			  'nom'=>'Mouvement',
			  'desc'=>'-2% au prix d\'une case en armure l&eacute;g&egrave;re',
			  'resume'=>'Mouvement : $imp0$ (-$imp1$% au prix d\'une case en armure légère)',
			  'factors'=>array(1,2)),
	    'force'=>array('max'=>10,
			   'nom'=>'Force',
			   'desc'=>'
     <ul>
      <li>+10% aux dégâts maximum des armes de corps à corps</li>
      <li>+2 kg portable en armure légère</li>
      <li>permet d\'utiliser des armes pour armure moyenne au bout de 5 implants</li>
      <li>-5% de malus dû à l\'état d\'une armure moyenne</li>
     </ul>
    ',
			   'resume'=>'Force : $imp0$ (+$imp1$% aux dégâts maximum des armes de corps à corps, +$imp2$ kg portable en armure légère, -$imp3$% de malus dû à l\'état d\'une armure moyenne)',
			   'factors'=>array(1,10,2,5)),
	    'endu'=>array('max'=>10,
			  'nom'=>'Endurance',
			  'desc'=>'
     <ul>
      <li>+4 sprints possibles en armure légère</li>
      <li>régénération des sprints augmentée de 5%</li>
      <li>malus dû à l\'état d\'une armure légère réduit de 10%</li>
      <li>malus dû à l\'état d\'une armure moyenne réduit de 5%</li>
     </ul>
    ',
			  'resume'=>'Endurance : $imp0$ (+$imp1$ sprints possibles en armure légère, -$imp2$% de malus dû à l\'état d\'une armure légère, -$imp3$% de malus dû à l\'état d\'une armure moyenne, -$imp4$% au malus de régénération dû au terrain)',
			  'factors'=>array(1,4,10,5,5)),
	    'vit2'=>array('max'=>10,
			  'nom'=>'Interface d\'armure moyenne(vitesse)',
			  'desc'=>'-2% au prix d\'une case en armure moyenne',
			  'resume'=>'Interface d\'armure moyenne (vitesse) : $imp0$ (-$imp1$% au prix d\'une case en armure moyenne)',
			  'factors'=>array(1,2)),
	    'resist2'=>array('max'=>10,
			     'nom'=>'Interface d\'armure moyenne (résistance)',
			     'desc'=>'-10% de malus de dégâts sur une armure moyenne',
			     'resume'=>'Interface d\'armure moyenne (résistance) : $imp0$ (-$imp1$% de malus de dégâts sur une armure moyenne)',
			     'factors'=>array(1,10)),
	    'vit4'=>array('max'=>10,
			  'nom'=>'Interface d\'armure lourde (vitesse)',
			  'desc'=>'-2% au prix d\'une case en armure lourde',
			  'resume'=>'Interface d\'armure lourde (vitesse) : $imp0$ (-$imp1$% au prix d\'une case en armure lourde)',
			  'factors'=>array(1,2)),
	    'resist4'=>array('max'=>10,
			     'nom'=>'Interface d\'armure lourde (résistance)',
			     'desc'=>'-10% de malus de dégâts sur une armure lourde',
			     'resume'=>'Interface d\'armure lourde (résistance) : $imp0$ (-$imp1$% de malus de dégâts sur une armure lourde)',
			     'factors'=>array(1,10)),
	    'precision'=>array('max'=>10,
			       'nom'=>'Interface de visée',
			       'desc'=>'+1% de précision en armure lourde',
			       'resume'=>'Interface de visée : $imp0$ (+$imp1$% de precision en armure lourde)',
			       'factors'=>array(1,1)));
if($perso['cloned'] && $perso['implants_dispo']){
  // Ajout d'implant
  foreach($imps AS $nom=>$imp){
    if(isset($_POST['new_imp_'.$nom]) &&
       ($perso['imp_'.$nom]<$imp['max'] ||
	$imp['max'] < 0)){
      add_implant('imp_'.$nom,!empty($imp['PV']));
    }
  }
}
if($perso['cloned']){
  // Suppression d'implant
  foreach($imps AS $nom=>$imp){
    if(isset($_POST['del_'.$nom],$_POST['confirm_del_'.$nom])){
      del_implant('imp_'.$nom);
    }
  }
}
$list='';
foreach($imps AS $nom=>$imp){
  if($perso['imp_'.$nom]){
    $toreplace=array();
    $replacement=array();
    foreach($imp['factors'] AS $i=>$value){
      $toreplace[]='$imp'.$i.'$';
      $replacement[]=$perso['imp_'.$nom]*$value;
    }
    $list.=str_replace($toreplace,
		       $replacement,
		       '<li>'.$imp['resume'].show_del($nom).'</li>');
  }
}

echo'  <p>',($perso['implants_dispo']<=0?'Aucun':$perso['implants_dispo']),' implant',($perso['implants_dispo']>1?'s':''),' disponible',($perso['implants_dispo']>1?'s':''),'.</p>
  <h2>Vos implants actuels</h2>
 ',($list?($perso['cloned']?'  <form method="post" action="jouer.php">
':'').'   <ul>
'.$list.'   </ul>
'.($perso['cloned']?'  </form>
':''):'  <p>Aucun implant plac&eacute;.</p>
');

if($perso['cloned'] && $perso['implants_dispo']){
  echo'  <form method="post" action="jouer.php" class="liste">
   <h2>Ajouter des implants</h2>
';
}
else{
  echo'  <h2>Implants existants</h2>
';
}
echo'   <table>
    <tr>
     <th>Implant</th>
     <th>Effet</th>
     <th>Maximum</th>
     <th></th>
    </tr>
';
  foreach($imps AS $nom=>$imp){
    echo affiche_implant($imp['nom'],$imp['desc'],$imp['max'],'imp_'.$nom);
  }
echo'
  </table>
';
if($perso['cloned'] && $perso['implants_dispo']){
  echo' </form>
';
}

function affiche_implant($nom,$effet,$max,$bdd)
{
  global $perso;
  $plus='';
  if(($perso[$bdd]<$max || $max <0)&&
     $perso['cloned'] && $perso['implants_dispo']){
    $plus=form_submit('new_'.$bdd,'Ajouter');
  }
  return '   <tr>
    <td>'.$nom.'</td>
    <td>'.$effet.'</td>
    <td>'.($max<0?'-':$max).'</td>
    <td>'.$plus.'</td>
   </tr>';
}

function add_implant($implant,$PV)
{
  global $perso;
  request('UPDATE persos
           SET `implants_dispo`=`implants_dispo`-1,
               `'.$implant.'`=`'.$implant.'`+1'.($PV?',
`PV`=`PV`+5':'').'
               WHERE ID='.$_SESSION['com_perso']);
  if(affected_rows())
    {
      $perso['implants_dispo']--;
      $perso[$implant]++;
      if($PV)
	{
	  $perso['PV']+=5;
	  $perso['PV_max']+=5;
	}
    }
  else
    add_message(3,'Erreur SQL lors de l\'ajout de l\'implant.');
}

function show_del($implant)
{
  global $perso;
  if($perso['cloned'])
    return form_check('. Enlever ','confirm_del_'.$implant).form_submit('del_'.$implant,'Ok');
  return '';
}

function del_implant($implant)
{
  global $perso;
  if(!$perso[$implant])
    return;
  if(!in_array($implant,array('imp_PV',
			      'imp_vue',
			      'imp_vit1',
			      'imp_endu',
			      'imp_resist',
			      'imp_regen',
			      'imp_force',
			      'imp_vit2',
			      'imp_resist2',
			      'imp_vit4',
			      'imp_resist4',
			      'imp_precision')))
    return;
  $update='';
  if($implant=='imp_PV')
    {
      if($perso['PV']>5)
	$update.='PV=PV-5,
 ';
      else
	{
	  erreur(1,'Attendez d\'avoir plus de PV pour enlever cet implant.');
	  return;
	}
    }
  if($implant=='imp_endu' && $perso['sprints']>$perso['sprints_max']-10)
    {
      erreur(1,'Vous avez encore trop de sprint pour pouvoir enlever un implant d\'endurance.');
      return;
    }
  $update.=$implant.'='.$implant.'-1,
';
  $ok=1;
  foreach($GLOBALS['competences'] as $key=>$value)
    if($perso[$key.'_max']>0)
      $ok=0;
  if($ok)
    {
      // On a jamais viré d'implant.
      foreach($GLOBALS['competences'] as $key=>$value)
	$update.=$key.'_max='.$perso[$key.'_reel'].',
'.$key.'='.($perso[$key.'_reel']*0.95).',
'; 
    }
  else
    {
      // On en a déjà dégagé.
      foreach($GLOBALS['competences'] as $key=>$value)
	$update.=$key.'='.$key.'-'.($perso[$key.'_max']*0.05).',
'; 
    }
  request('UPDATE persos
SET '.$update.' implants_dispo=implants_dispo+1
WHERE ID='.$_SESSION['com_perso']);
  if(affected_rows())
    {
      echo'<p>Implant supprim&eacute;.</p>
';
      $perso['implants_dispo']++;
      $perso[$implant]--;
    }
}
?>