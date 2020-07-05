<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header_lite();
if(!isset($_SESSION['com_perso'])){
    echo 'Vous n\'&ecirc;tes pas loggu&eacute;.';
    com_footer();
    die();
}
require_once('../sources/monperso.php');
$fait=0;
$poids_max=$perso['poids_max']-($perso['inventaire']['poids']+$perso['poids_arme1']+$perso['poids_arme2']);
//***************************************************************************
// Ramassage d'une arme.
//***************************************************************************
if(isset($_POST['ramasse_arme_ok'],$_POST['equipement_id']) && is_numeric($_POST['equipement_id'])){
  request('UPDATE equipement
             SET possesseur='.$_SESSION['com_perso'].',
                 X=0,
                 Y=0,
                 map=0 
             WHERE X='.$perso['X'].'
               AND Y='.$perso['Y'].'
               AND map='.$perso['map'].'
               AND possesseur=0
               AND equipement.type=1 
               AND equipement.ID='.$_POST['equipement_id']);
  if(affected_rows()){
    $fait=1;
  }
}
//***************************************************************************
// Posage d'une arme.
//***************************************************************************
if(isset($_POST['pose_arme_ok'],$_POST['equipement_id']) && is_numeric($_POST['equipement_id'])){
  if($perso['map']){
    request('UPDATE equipement
                   SET possesseur=0,
                       X='.$perso['X'].',
                       Y='.$perso['Y'].',
                       map='.$perso['map'].',
dropped='.$time.' 
                   WHERE X=0
                     AND Y=0
                     AND map=0
                     AND possesseur='.$_SESSION['com_perso'].'
                     AND equipement.type=1 
                     AND equipement.ID='.$_POST['equipement_id']);
	$fait=1;
  }
  else{
    request('DELETE
                 FROM equipement
                 WHERE X=0
                   AND Y=0
                   AND map=0
                   AND possesseur='.$_SESSION['com_perso'].'
                   AND equipement.type=1
                   AND equipement.ID='.$_POST['equipement_id']);
    if(affected_rows()){
      $fait=1;
      request('OPTIMIZE TABLE equipement');
    }
  }
}

//***************************************************************************
// Ramassage de munitions.
//***************************************************************************
if(isset($_POST['ramasse_munar_ok'],$_POST['equipement_id'],$_POST['ramasse_nombre']) &&
   is_numeric($_POST['equipement_id']) &&
   is_numeric($_POST['ramasse_nombre']) &&
   $_POST['ramasse_nombre'] > 0){
  // On récup les infos de ce tas de munitions.
  $tas=my_fetch_array('SELECT nombre,
                                poids,
                                munars.ID AS type_munars
                         FROM equipement
                           INNER JOIN munars
                             ON equipement.objet_ID=munars.ID
                         WHERE X='.$perso['X'].'
                           AND Y='.$perso['Y'].'
                           AND map='.$perso['map'].'
                           AND possesseur=0
                           AND equipement.type=2
                           AND equipement.ID='.$_POST['equipement_id']);
  if($tas[0]){
    // Le tas existe, combien peut on prendre de ces munitions.
    $nbr=min($_POST['ramasse_nombre'],$tas[1]['nombre']);
    if($nbr){
      if($nbr==$tas[1]['nombre']){
	// Le tas est vide.
	if(isset($perso['inventaire']['munars'][$tas[1]['type_munars']])){
	  request('UPDATE equipement
                             SET nombre=`nombre`+'.$nbr.'
                             WHERE X=0
                               AND Y=0
                               AND map=0
                               AND possesseur='.$_SESSION['com_perso'].'
                               AND `type`=2
                               AND ID='.$perso['inventaire']['munars'][$tas[1]['type_munars']]['ID']);
	  if(affected_rows())
	    request('DELETE FROM equipement WHERE ID='.$_POST['equipement_id'].' LIMIT 1');
	  if(affected_rows())
	    request('OPTIMIZE TABLE equipement');
	}
	else{
	  request('UPDATE equipement
                             SET possesseur='.$_SESSION['com_perso'].',
                                 X=0,
                                 Y=0,
                                 map=0 
                             WHERE X='.$perso['X'].'
                               AND Y='.$perso['Y'].'
                               AND map='.$perso['map'].'
                               AND possesseur=0
                               AND equipement.type=2
                               AND equipement.ID='.$_POST['equipement_id']);
	}
      }
      else{
	if(isset($perso['inventaire']['munars'][$tas[1]['type_munars']]))
	  request('UPDATE equipement
                           SET nombre=`nombre`+'.$nbr.'
                           WHERE X=0
                             AND Y=0
                             AND map=0
                             AND possesseur='.$_SESSION['com_perso'].'
                             AND `type`=2
                             AND ID='.$perso['inventaire']['munars'][$tas[1]['type_munars']]['ID']);
	else
	  request('INSERT
                           INTO equipement (`type`,
                                            `objet_ID`,
                                            `possesseur`,
                                            `nombre`)
                                     VALUES(2,
                                            '.$tas[1]['type_munars'].',
                                            '.$_SESSION['com_perso'].',
                                            '.$nbr.')');
	if(affected_rows())
	  request('UPDATE equipement SET nombre=`nombre`-'.$nbr.' WHERE ID='.$_POST['equipement_id'].' LIMIT 1');
      }
      $fait=1;
    }
  }
}
//***************************************************************************
// Posage de munitions.
//***************************************************************************
if(isset($_POST['pose_munar_ok'],$_POST['equipement_id'],$_POST['pose_nombre']) &&
   is_numeric($_POST['equipement_id']) &&
   is_numeric($_POST['pose_nombre']) &&
   $_POST['pose_nombre'] > 0 &&
   isset($perso['inventaire']['munars'][$_POST['equipement_id']])){
  $nbr=min($_POST['pose_nombre'],$perso['inventaire']['munars'][$_POST['equipement_id']]['nombre']);
  if($nbr){
    if(!$perso['map']){
      if($perso['inventaire']['munars'][$_POST['equipement_id']]['nombre']==$nbr)
	request('DELETE
                           FROM equipement
                           WHERE X=0
                             AND Y=0
                             AND map=0
                             AND possesseur='.$_SESSION['com_perso'].'
                             AND equipement.type=2
                             AND objet_ID='.$_POST['equipement_id'].'
                           LIMIT 1');
      else
	request('UPDATE equipement
                           SET nombre=`nombre`-'.$nbr.'
                           WHERE X=0
                             AND Y=0
                             AND map=0
                             AND possesseur='.$_SESSION['com_perso'].'
                             AND equipement.type=2
                             AND objet_ID='.$_POST['equipement_id'].'
                           LIMIT 1');
    }
    if(exist_in_db('SELECT ID
                    FROM equipement
                    WHERE X='.$perso['X'].'
                      AND Y='.$perso['Y'].'
                      AND map='.$perso['map'].'
                      AND possesseur=0
                      AND equipement.type=2
                      AND objet_ID='.$_POST['equipement_id'].' LIMIT 1')){
      // Il y a déjà un tas de ces munitions.
      request('UPDATE equipement
                 SET nombre=`nombre`+'.$nbr.',
dropped='.$time.'
                 WHERE X='.$perso['X'].'
                   AND Y='.$perso['Y'].'
                   AND map='.$perso['map'].'
                   AND possesseur=0
                   AND equipement.type=2
                   AND objet_ID='.$_POST['equipement_id'].'
                     LIMIT 1');
      if(affected_rows()){
	if($perso['inventaire']['munars'][$_POST['equipement_id']]['nombre']==$nbr)
	  request('DELETE
                           FROM equipement
                           WHERE X=0
                             AND Y=0
                             AND map=0
                             AND possesseur='.$_SESSION['com_perso'].'
                             AND equipement.type=2
                             AND objet_ID='.$_POST['equipement_id'].'
                           LIMIT 1');
	else
	  request('UPDATE equipement
                           SET nombre=`nombre`-'.$nbr.'
                           WHERE X=0
                             AND Y=0
                             AND map=0
                             AND possesseur='.$_SESSION['com_perso'].'
                             AND equipement.type=2
                             AND objet_ID='.$_POST['equipement_id'].'
                           LIMIT 1');
      }
    }
    else{
      if($perso['inventaire']['munars'][$_POST['equipement_id']]['nombre']==$nbr)
	request('UPDATE equipement
                       SET X='.$perso['X'].',
                           Y='.$perso['Y'].',
                           map='.$perso['map'].',
                           possesseur=0,
dropped='.$time.' 
                        WHERE X=0
                          AND Y=0
                          AND map=0
                          AND possesseur='.$_SESSION['com_perso'].'
                          AND equipement.type=2
                          AND objet_ID='.$_POST['equipement_id'].'
                        LIMIT 1');
      else{  
	request('INSERT
                         INTO equipement(`type`,
                                         objet_ID,
                                         X,
                                         Y,
                                         map,
                                         nombre,
dropped)
                                 VALUES (2,
                                         '.$_POST['equipement_id'].',
                                         '.$perso['X'].',
                                         '.$perso['Y'].',
                                         '.$perso['map'].', 
                                         '.$nbr.',
'.$time.')');
	if(last_id())
	  request('UPDATE equipement
                           SET nombre=`nombre`-'.$nbr.'
                           WHERE X=0
                             AND Y=0
                             AND map=0
                             AND possesseur='.$_SESSION['com_perso'].'
                             AND equipement.type=2
                             AND objet_ID='.$_POST['equipement_id'].'
                           LIMIT 1');
      }
    }
    $fait=1;
  }
}
//***************************************************************************
// On s'equipe d'une arme de l'inventaire.
//***************************************************************************
if(isset($_POST['equipe_arme_ok'],$_POST['equipement_id'],$_POST['equipe_arme_confirm']) &&
   is_numeric($_POST['equipement_id'])){
  // On recup les infos de l'arme qu'on equipe.
  $arme=my_fetch_array('SELECT armes.ID,
                                 armes.type,
                                 armes.perte_tirs,
                                 armes.perte_PM,
                                 armes.lvl,
                                 armes.armure
                          FROM equipement
                            INNER JOIN armes
                              ON objet_ID=armes.ID
                          WHERE X=0
                            AND Y=0
                            AND map=0
                            AND possesseur='.$_SESSION['com_perso'].'
                            AND equipement.type=1
                            AND equipement.ID='.$_POST['equipement_id']);
  if($arme[0]){
    if(($arme[1]['armure']&$perso['type_armure']
	|| $arme[1]['armure']& 2
	&&$perso['special'])
       &&$perso['PM']>=$arme[1]['perte_PM']
       &&$perso['tir_restants']>=$arme[1]['perte_tirs']){
      // On peut mettre cette arme.
      /*      request('UPDATE persos
                      SET PM=`PM`-'.$arme[1]['perte_PM'].',
                      tir_restants=`tir_restants`-'.$arme[1]['perte_tirs'].'
		      WHERE ID='.$_SESSION['com_perso']);
      */
      // C'est l'arme 1 ou 2 ?
      if($arme[1]['type']<=6)
	$leslot=1;
      else
	$leslot=2;
      // On fout les munitions dans l'inventaire.
      $ok=1;
      if($perso['type_munars_arme'.$leslot]){
	request('INSERT INTO equipement 
(`type`,objet_id,nombre,possesseur)
VALUES (2,
'.$perso['type_munars_arme'.$leslot].',
'.$perso['munars_arme'.$leslot].',
'.$_SESSION['com_perso'].')');
	if(!last_id())
	  $ok=0;
      }
      if($ok){
	// On pose l'arme
	request('UPDATE equipement
               SET objet_ID='.$perso['ID_arme'.$leslot].'
               WHERE X=0
                 AND Y=0
                 AND map=0
                 AND possesseur='.$_SESSION['com_perso'].'
                 AND equipement.type=1
                 AND equipement.ID='.$_POST['equipement_id']);
	if(affected_rows())
	  request('UPDATE persos
                 SET munitions_'.$leslot.'=0,
                     matos_'.$leslot.'='.$arme[1]['ID'].',
                     PM=`PM`-'.$arme[1]['perte_PM'].',
                     tir_restants=`tir_restants`-'.$arme[1]['perte_tirs'].'
                 WHERE ID='.$_SESSION['com_perso']);
      }
      $fait=1;
    }
  }
}
//***************************************************************************
// On ramasse un objet
//***************************************************************************
if(isset($_POST['ramasse_objet_ok'],$_POST['equipement_id']) &&
   is_numeric($_POST['equipement_id'])){
  request('UPDATE equipement 
SET possesseur='.$_SESSION['com_perso'].',
X=0,
Y=0,
map=0
WHERE possesseur=0
AND `type`=3
AND X='.$perso['X'].'
AND Y='.$perso['Y'].'
AND map='.$perso['map'].'
 AND ID='.$_POST['equipement_id']);
  $fait=1;
}

//***************************************************************************
// On pose un objet
//***************************************************************************
if(isset($_POST['pose_objet_ok'],$_POST['equipement_id']) &&
   is_numeric($_POST['equipement_id'])){
  request('UPDATE equipement 
SET possesseur=0,
X='.$perso['X'].',
Y='.$perso['Y'].',
map='.$perso['map'].'
WHERE possesseur='.$_SESSION['com_perso'].'
AND X=0
AND Y=0
AND map=0
AND `type`=3
AND ID='.$_POST['equipement_id']);
  $fait=1;
}

if($fait){
  include('../sources/monperso.php');
}
if($perso['map']){
    // Recuperation du matos qui se trouve sur la case.
    $equipement=my_fetch_array('SELECT equipement.ID,
                                       equipement.type,
                                       equipement.nombre,
                                       equipement.objet_ID AS image, 
                                       armes.nom AS nom_arme,
                                       armes.poids AS poids_arme,
                                       munars.nom AS nom_munar,
                                       munars.poids AS poids_munar,
                                       objets.nom AS nom_objet,
                                       objets.description AS desc_objet,
                                       objets.poids AS poids_objet
                            FROM equipement
                              LEFT OUTER JOIN munars
                                   ON equipement.objet_ID = munars.ID
                                  AND equipement.type = 2
                              LEFT OUTER JOIN armes
                                   ON equipement.objet_ID = armes.ID
                                  AND equipement.type = 1
                              LEFT OUTER JOIN objets
                                   ON equipement.objet_ID = objets.ID
                                  AND equipement.type = 3
                            WHERE X='.$perso['X'].'
                              AND Y='.$perso['Y'].'
                              AND map='.$perso['map'].'
                              AND possesseur=0
                            ORDER BY equipement.type ASC');
}
$forcer=empty($_COOKIE['invArmes']) && empty($_COOKIE['invMunars']) && empty($_COOKIE['invSol']) && empty($_COOKIE['invSpecs']);
$strPoids=' <p>Poids de l\'&eacute;quipement : '.($perso['inventaire']['poids']+$perso['poids_arme1']+$perso['poids_arme2']).'/'.$perso['poids_max'].'.</p>
';
$new=$perso['map'] && $equipement[0];
echo'<ul id="menuHaut">
'.showMenuItem('invEquiped','&Eacute;quipement',$forcer).'
'.showMenuItem('invArmes','Armes').'
'.showMenuItem('invMunars','Munitions').'
'.showMenuItem('invSpecs','Autres').'
'.showMenuItem('invSol','Sol',false,$new).'
</ul>
<div class="framed" id="invEquipedFrame">
'.$strPoids.'
';
afficheArmes();
afficheGadgets();
echo'</div>
<div class="framed" id="invArmesFrame">
'.$strPoids.'
 <ul>
';
for($i=0;isset($perso['inventaire']['armes'][$i]);$i++){
  echo'  <li>
   <img src="images/armes/na'.$perso['inventaire']['armes'][$i]['arme_ID'].'.gif" alt="'.bdd2html($perso['inventaire']['armes'][$i]['nom']).'" /> '.bdd2html($perso['inventaire']['armes'][$i]['nom']).', poids : '.$perso['inventaire']['armes'][$i]['poids'].' kg.
   <form method="post" action="inventaire.php">
    <span>
     ',form_hidden('equipement_id',$perso['inventaire']['armes'][$i]['ID']),' 
     <input type="submit" name="pose_arme_ok" value="Poser" />
    </span>
   </form>
';
  if((($perso['inventaire']['armes'][$i]['armure'] & $perso['type_armure']
       || $perso['inventaire']['armes'][$i]['armure'] & 2
       &&$perso['special'])
      &&$perso['PM']>=$perso['inventaire']['armes'][$i]['perte_PM']
      &&$perso['tir_restants']>=$perso['inventaire']['armes'][$i]['perte_tirs'])){
    echo'   <form method="post" action="inventaire.php">
    <span>Equiper : 
     '.form_check('','equipe_arme_confirm').
     form_hidden('equipement_id',$perso['inventaire']['armes'][$i]['ID']).'
     <input type="submit" name="equipe_arme_ok" value="Equiper" />
    </span>
   </form>
';
  }
  echo'(Perte de : ',$perso['inventaire']['armes'][$i]['perte_PM'],' PT et ',$perso['inventaire']['armes'][$i]['perte_tirs'],'% de tirs pour s\'en &eacute;quiper)
  </li>
';
}
echo'</ul>
</div>
<div class="framed" id="invMunarsFrame">
'.$strPoids.'
<ul>
';
if(isset($perso['inventaire']['munars'])){
  foreach($perso['inventaire']['munars'] AS $key=>$value){
    echo'<li>',bdd2html($value['nom']),'. Poids par munition : ',($value['poids']*1000),'g, nombre : ',$value['nombre'],', poids total : ',$value['poids_total'],' kg.
<form method="post" action="inventaire.php">
<span>
',form_hidden('equipement_id',$key),'
',form_text('','pose_nombre','3',''),' 
<input type="submit" name="pose_munar_ok" value="Poser" />
</span>
</form>
</li>
';
  }
}
echo'</ul>
</div>
<div class="framed" id="invSpecsFrame">
'.$strPoids.'
<ul>
';
if(isset($perso['inventaire']['objets'])){
  foreach($perso['inventaire']['objets'] AS $value){
    echo'<li>',bdd2html($value['nom']),'. Poids : ',($value['poids']),'kg.
 <h3>Description</h3>
 <p>'.filtrage_ordre($value['desc']).'</p>
 <h3>D&eacute;tails</h3>
 <div>'.filtrage_ordre($value['story']).'</div>
<form method="post" action="inventaire.php">
<span>
',form_hidden('equipement_id',$value['ID']),'
<input type="submit" name="pose_objet_ok" value="Poser" />
</span>
</form>
</li>
';
  }
}
echo'</ul>
</div>
<div class="framed" id="invSolFrame">
'.$strPoids.'
';
if($perso['map'] && $equipement[0]){
  echo'<h2>Armes</h2>
<ul>
';
  $i=1;
  // On affiche les armes a terre.
  while($i<=$equipement[0] && $equipement[$i]['type']==1){
    echo'<li><a href="arme.php?id='.$equipement[$i]['image'].'"><img src="images/armes/na',$equipement[$i]['image'],'.gif" alt="',bdd2html($equipement[$i]['nom_arme']),'" title="',bdd2html($equipement[$i]['nom_arme']),'" /></a> ',bdd2html($equipement[$i]['nom_arme']),' poids : ',$equipement[$i]['poids_arme'],'kg.
<form method="post" action="inventaire.php">
<span>
'.form_hidden('equipement_id',$equipement[$i]['ID']).' 
<input type="submit" name="ramasse_arme_ok" value="Ramasser" />
</span>
</form>
</li>
';
    $i++;
  }
  echo'</ul>
<h2>Munitions</h2>
<ul>
';
  // Puis les munitions
  while($i<=$equipement[0] && $equipement[$i]['type']==2){
    echo'<li>',bdd2html($equipement[$i]['nom_munar']),' poids : ',($equipement[$i]['poids_munar']*1000),'g par munitions, nombre : ',$equipement[$i]['nombre'],'.
<form method="post" action="inventaire.php">
<span>
'.form_hidden('equipement_id',$equipement[$i]['ID']).'
'.form_text('','ramasse_nombre','3','').' 
<input type="submit" name="ramasse_munar_ok" value="Ramasser" />
</span>
</form>
</li>
';
    $i++;
  }
  echo'</ul>
<h2>Autres</h2>
<ul>
';
  // Puis le reste
  while($i<=$equipement[0] && $equipement[$i]['type']==3){
    echo'<li>',bdd2html($equipement[$i]['nom_objet']),' poids : ',($equipement[$i]['poids_objet']),' kg.
 <h3>Description</h3>
 <p>'.filtrage_ordre($equipement[$i]['desc_objet']).'</p>
<form method="post" action="inventaire.php">
<span>
'.form_hidden('equipement_id',$equipement[$i]['ID']).'
<input type="submit" name="ramasse_objet_ok" value="Ramasser" />
</span>
</form>
</li>
';
    $i++;
  }
  echo'</ul>
'; 
}
else{
  echo'<p>Aucun &eacute;quipement &agrave; terre.</p>
';
}
echo'</div>
';
unset($perso);
com_footer_lite();

function afficheArmes(){
  global $perso;
  for($i=1;$i<=2;$i++){
    if($perso['ID_arme'.$i]){
      echo'<div id="inv'.$i.'">
 <h2>'.bdd2html($perso['nom_arme'.$i]).' <img src="images/armes/'.$perso['ID_arme'.$i].'.gif" alt="'.$perso['ID_arme'.$i].'.gif" /></h2>
 <dl>
  <dt>Type : </dt>
  <dd>'.type_arme($perso['type_arme'.$i]).'</dd>
  <dt>Port&eacute;e : </dt>
  <dd>'.$perso['portee_arme'.$i].'</dd>
 ';
      if($perso['rayon_arme'.$i]>0){
	echo'  <dt>Rayon : </dt>
  <dd>'.$perso['rayon_arme'.$i].'</dd>
';
      }
      echo'  <dt>D&eacute;g&acirc;ts : </dt>
  <dd>'.$perso['degats_arme'.$i].'</dd>
  <dt>Tirs par cycle : </dt>
  <dd>'.$perso['nbr_tirs_arme'.$i].'</dd>
  <dt>Pr&eacute;cision min : </dt>
  <dd>'.$perso['precision_min_arme'.$i].'</dd>
  <dt>Pr&eacute;cision max : </dt>
  <dd>'.$perso['precision_max_arme'.$i].'</dd>
  <dt>Critique : </dt>
  <dd>'.$perso['degats_vie_arme'.$i].' / '.$perso['critique_arme'.$i].'%. Seuil : '.$perso['seuil_critique_arme'.$i].'%, PT par pourcent : '.$perso['pm_critique_arme'.$i].'.</dd>
';
      if($perso['munars_max_arme'.$i] > 0){
	echo'  <dt>Munitions : </dt>
  <dd>'.$perso['munars_arme'.$i].'/'.$perso['munars_max_arme'.$i].' '.bdd2html($perso['nom_mun'.$i]).'</dd>
  <dt>Munition par tir : </dt>
  <dd>'.$perso['cadence_arme'.$i].'</dd>
 ';
      }
      echo'  <dt>Poids : </dt>
  <dd>'.$perso['poids_arme'.$i].' kg</dd>
  <dt>Malus au camouflage : </dt>
  <dd>'.$perso['malus_camou_arme'.$i].'</dd>
 </dl>
</div>
';
    }
  }
}

function afficheGadgets(){
  global $perso;
  $gadProperties=array('bonus_precision'=>'Pr&eacute;cision : %i%',
		     'camou'=>'Camouflage : %i',
		     'bonus_tir_camou'=>'Malus de camouflage des tirs : -%i%',
		     'bonus_vision'=>'Vision : %i',
		     'ecl'=>'&Eacute;claireur : %i',
		     'escalade'=>'Escalade : %i',
		     'foret'=>'For&ecirc;t : %i',
		     'montagne'=>'Montagne : %i',
		     'desert'=>'D&eacute;sert : %i',
		     'marais'=>'Marais : %i',
		     'plaine'=>'Plaine : %i');
  echo'
 <h2 class="clearer">Gadgets</h2>';
  for($i=1;$i<=3;$i++){
    if($perso['ID_gad'.$i]){
      echo'
<div id="gadInv'.$i.'">
 <h3>'.bdd2html($perso['nom_gad'.$i]).'</h3>
 <ul>';
      foreach($gadProperties AS $key=>$value){
	if($perso[$key.'_gad'.$i] != 0){
	  echo '
  <li>'.str_replace('%i',$perso[$key.'_gad'.$i],$value).'</li>';
	}
      }
      if($perso['mines_gad'.$i] != 0){
	echo'
  <li>'.$perso['munitions_restantes_gad'.$i].' / '.$perso['mines_gad'.$i].' mines. D&eacute;g&acirc;ts '.$perso['degats_mines_gad'.$i].'('.$perso['pourcent_gad'.$i].'% PV), instabilit&eacute; '.$perso['instabilite_gad'.$i].', discr&eacute;tion '.$perso['discretion_gad'.$i].'</li>';
      }
echo'
 </ul>
 </div>';
    }
  }
}
//$perso['_arme'.$i]
?>