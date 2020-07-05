<?php
function getMap($carte,$centerX,$centerY,$dist,$armure,$camp){
  $map=array();
  // Choppage du terrain.
  if(file_exists('../cache/map'.$carte.'/'.$centerX.'_'.$centerY.'_'.$dist.'.map')){
    $map = unserialize(file_get_contents('../cache/map'.$carte.'/'.$centerX.'_'.$centerY.'_'.$dist.'.map'));
  }
  else{
    $req=request('SELECT carte_'.$carte.'.X,
carte_'.$carte.'.Y,
carte_'.$carte.'.Z,
carte_'.$carte.'.h,
carte_'.$carte.'.terrain,
carte_'.$carte.'.carto_'.$camp.' AS carto,
terrains.couvert,
terrains.prix_'.$armure.' AS cout_terrain,
terrains.competence,
terrains.nom AS nom_terrain,
terrains.style AS style_terrain,
terrains.pontable,
terrains.bloque_vue,
terrains.malus_regen,
terrains.malus_camou,
terrains.malus_vision,
terrains.malus_precision,
terrains.couvert AS cout_portee,
terrains.debut_perte AS debut_perte
FROM `carte_'.$carte.'`
INNER JOIN `terrains`
ON carte_'.$carte.'.terrain=terrains.ID
WHERE carte_'.$carte.'.X<='.($centerX+$dist).'
  AND carte_'.$carte.'.X>='.($centerX-$dist).'
  AND carte_'.$carte.'.Y>='.($centerY-$dist).'
  AND carte_'.$carte.'.Y<='.($centerY+$dist).'
LIMIT '.(($dist*2+1)*($dist*2+1)));


    // Tri des cases.
    while($cases=mysql_fetch_array($req)){
      $X=$cases['X']-$centerX;
      $Y=$cases['Y']-$centerY;
      if(sqrt($X*$X+$Y*$Y)>$dist){
	// Case trop éloignee de nous pour être visible.
	$map[$Y][$X]=array('type_case'=>'vide',
			   'visible'=>1,
			   'class'=>array(),
			   'cout_portee_total'=>14,
			   'cout_vision_total'=>14,
			   'precision'=>0,
			   'cout_vue_total'=>14);
      }
      else{
	$map[$Y][$X]=array('type_case'=>'terrain',
			   'class'=>array(),
			   'numero'=>$cases['terrain'],
			   'bloque_vue'=>$cases['bloque_vue'],
			   'praticable'=>1,
			   'pontable'=>$cases['pontable'],
			   'z_sol'=>$cases['Z'],
			   'z_perso'=>$cases['Z']+0.5,
			   'z_total'=>$cases['h'],
			   'cout_portee'=>$cases['cout_portee'],
			   'cout_vision'=>max(1,1+($cases['malus_vision']-1)*comp($cases['competence'],'vision')),
			   'perte_precision'=>max(0,$cases['malus_precision']*comp($cases['competence'],'precision')),
			   'precision'=>0,
			   'visible'=>1,
			   'special'=>array(0),
			   'prix'=>$cases['cout_terrain'],
			   'comp'=>$cases['competence'],
			   'malus_regen'=>$cases['malus_regen'],
			   'debut_perte'=>$cases['debut_perte'],
			   'nom_terrain'=>$cases['nom_terrain'],
			   'style'=>$cases['style_terrain'],
			   'terrain'=>$cases['terrain'],
			   'carto'=>$cases['carto']);
			   
			   


      }
    }
    

		
		
    mysql_free_result($req);
    // Tri des cases visibles.
    $map = set_visible($map,$dist);
    // On met la map en cache.
    /*    fichier_create('../cache/map'.$carte.'/'.$centerX.'_'.$centerY.'_'.$dist.'.map',
		   serialize($map),
		   1);*/
  }
  return $map;
}

function setQGs($map,$carte, $centerX,$centerY,$dist,$camp,$block=0,$util=0){
  $req=request('SELECT qgs.ID AS numero_qg,
qgs.initiales AS nom_qg,
qgs.nom,
qgs.utilisation,
qgs.blocage, 
qgs.camp,
qgs.prenable,
qgs.visibilite,
qgs.type AS self,
qgs.X,
qgs.Y,
qgs.tubage,
qgs.armes,
qgs.armures,
qgs.munitions,
qgs.reparation,
qgs.regeneration,
qgs.respawn,
qgs.malus_camou,
qgs.desc,
camps.nom AS nom_camp
FROM `qgs`
LEFT JOIN camps
 ON qgs.camp = camps.id
WHERE qgs.carte='.$carte.'
  AND qgs.X<='.($centerX+$dist).'
  AND qgs.X>='.($centerX-$dist).'
  AND qgs.Y>='.($centerY-$dist).'
  AND qgs.Y<='.($centerY+$dist));
  while($cases=mysql_fetch_array($req)){
    $X=$cases['X']-$centerX;
    $Y=$cases['Y']-$centerY;
    $map[$Y][$X]['praticable']=($cases['prenable']&&$cases['camp']!=$camp)?3:0;
    if(sqrt($X*$X+$Y*$Y)<=$dist){
      $map[$Y][$X]['type_case']='QG';
      $map[$Y][$X]['class']=array();
      $map[$Y][$X]['ID']=$cases['numero_qg'];
      $map[$Y][$X]['initiales']=$cases['nom_qg'];
      $map[$Y][$X]['nom']=$cases['nom'];
      $map[$Y][$X]['nom_camp']=$cases['nom_camp'];
      $map[$Y][$X]['desc']=$cases['desc'];
      $map[$Y][$X]['self']=$cases['self'];
      $map[$Y][$X]['utilisation']=$cases['utilisation'];
      $map[$Y][$X]['blocage']=$cases['blocage'];
      $map[$Y][$X]['camp']=$cases['camp'];
      $map[$Y][$X]['malus_camou']=$cases['malus_camou'];
      $map[$Y][$X]['visible']=1;
      $map[$Y][$X]['visibilite']=$cases['visibilite'];
      $map[$Y][$X]['bloque_vue']=0;
      $map[$Y][$X]['QG']=array('tubage'=>$cases['tubage'],
			       'armes'=>$cases['armes'],
			       'armures'=>$cases['armures'],
			       'munitions'=>$cases['munitions'],
			       'reparation'=>$cases['reparation'],
			       'regeneration'=>$cases['regeneration'],
			       'respawn'=>$cases['respawn']);
      // On stocke une liste de QGs.
      $liste_QG[]=array($Y,$X);
    }
  }
  mysql_free_result($req);

  // Si demande, on calcule les limites de zone des QGs.
  if($block || $util){
    $i=0;
    while(isset($liste_QG[$i])){
      $X=$liste_QG[$i][1];
      $Y=$liste_QG[$i][0];
      $i++;
      if($util && isset($map[$Y][$X])){
	// Tout d'abord la zone d'utilisation
	for($QG_X=-$map[$Y][$X]['utilisation']-1;$QG_X<=$map[$Y][$X]['utilisation']+1;$QG_X++){
	  for($QG_Y=-$map[$Y][$X]['utilisation']-1;$QG_Y<=$map[$Y][$X]['utilisation']+1;$QG_Y++){
	    $daX=$X+$QG_X;
	    $daY=$Y+$QG_Y;
	    if(isset($map[$daY][$daX])){
	      if(sqrt($QG_X*$QG_X+$QG_Y*$QG_Y)<=$map[$Y][$X]['utilisation']){
		// Case dans la zone du QG
		if(!isset($map[$daY][$daX-1]) ||
		   $map[$daY][$daX-1]['cout_vision_total']>$dist ||
		   $map[$daY][$daX-1]['type_case']=='vide' ||
		   sqrt(($QG_X-1)*($QG_X-1)+$QG_Y*$QG_Y)>$map[$Y][$X]['utilisation']){
		  $map[$daY][$daX]['class'][]='qgug ';
		}
		if(!isset($map[$daY][$daX+1]) ||
		   $map[$daY][$daX+1]['cout_vision_total']>$dist ||
		   $map[$daY][$daX+1]['type_case']=='vide' ||
		   sqrt(($QG_X+1)*($QG_X+1)+$QG_Y*$QG_Y)>$map[$Y][$X]['utilisation']){
		  $map[$daY][$daX]['class'][]='qgud ';
		}
		if(!isset($map[$daY+1][$daX]) ||
		   $map[$daY+1][$daX]['cout_vision_total']>$dist ||
		   $map[$daY+1][$daX]['type_case']=='vide' ||
		   sqrt(($QG_Y+1)*($QG_Y+1)+$QG_X*$QG_X)>$map[$Y][$X]['utilisation']){
		  $map[$daY][$daX]['class'][]='qgub ';
		}
		if(!isset($map[$daY-1][$daX]) ||
		   $map[$daY-1][$daX]['cout_vision_total']>$dist ||
		   $map[$daY-1][$daX]['type_case']=='vide' ||
		   sqrt(($QG_Y-1)*($QG_Y-1)+$QG_X*$QG_X)>$map[$Y][$X]['utilisation']){
		  $map[$daY][$daX]['class'][]='qguh ';
		}
	      }
	      else{
		// Case hors QG
		if(isset($map[$daY][$daX-1]) &&
		   $map[$daY][$daX-1]['cout_vision_total']<=$dist &&
		   $map[$daY][$daX-1]['type_case']!='vide' &&
		   sqrt(($QG_X-1)*($QG_X-1)+$QG_Y*$QG_Y)<=$map[$Y][$X]['utilisation']){
		  $map[$daY][$daX]['class'][]='qgug ';
		}
		if(isset($map[$daY][$daX+1]) &&
		   $map[$daY][$daX+1]['cout_vision_total']<=$dist &&
		   $map[$daY][$daX+1]['type_case']!='vide' &&
		   sqrt(($QG_X+1)*($QG_X+1)+$QG_Y*$QG_Y)<=$map[$Y][$X]['utilisation']){
		  $map[$daY][$daX]['class'][]='qgud ';
		}
		if(isset($map[$daY+1][$daX]) &&
		   $map[$daY+1][$daX]['cout_vision_total']<=$dist &&
		   $map[$daY+1][$daX]['type_case']!='vide' &&
		   sqrt(($QG_Y+1)*($QG_Y+1)+$QG_X*$QG_X)<=$map[$Y][$X]['utilisation']){
		  $map[$daY][$daX]['class'][]='qgub ';
		}
		if(isset($map[$daY-1][$daX]) &&
		   $map[$daY-1][$daX]['cout_vision_total']<=$dist &&
		   $map[$daY-1][$daX]['type_case']!='vide' &&
		   sqrt(($QG_Y-1)*($QG_Y-1)+$QG_X*$QG_X)<=$map[$Y][$X]['utilisation']){
		  $map[$daY][$daX]['class'][]='qguh ';
		}
	      }
	    }
	  }
	}
      }
      if($block && isset($map[$Y][$X])){
	// Puis la zone de blocage
	for($QG_X=-$map[$Y][$X]['blocage']-1;$QG_X<=$map[$Y][$X]['blocage']+1;$QG_X++){
	  for($QG_Y=-$map[$Y][$X]['blocage']-1;$QG_Y<=$map[$Y][$X]['blocage']+1;$QG_Y++){
	    $daX=$X+$QG_X;
	    $daY=$Y+$QG_Y;
	    if(isset($map[$daY][$daX])){
	      if(sqrt($QG_X*$QG_X+$QG_Y*$QG_Y)<=$map[$Y][$X]['blocage']){
		// Case dans la zone du QG
		if(!isset($map[$daY][$daX-1]) ||
		   $map[$daY][$daX-1]['cout_vision_total']>$dist ||
		   $map[$daY][$daX-1]['type_case']=='vide' ||
		   sqrt(($QG_X-1)*($QG_X-1)+$QG_Y*$QG_Y)>$map[$Y][$X]['blocage']){
		  $map[$daY][$daX]['class'][]='qgbg ';
		}
		if(!isset($map[$daY][$daX+1]) ||
		   $map[$daY][$daX+1]['cout_vision_total']>$dist ||
		   $map[$daY][$daX+1]['type_case']=='vide' ||
		   sqrt(($QG_X+1)*($QG_X+1)+$QG_Y*$QG_Y)>$map[$Y][$X]['blocage']){
		  $map[$daY][$daX]['class'][]='qgbd ';
		}
		if(!isset($map[$daY+1][$daX]) ||
		   $map[$daY+1][$daX]['cout_vision_total']>$dist ||
		   $map[$daY+1][$daX]['type_case']=='vide' ||
		   sqrt(($QG_Y+1)*($QG_Y+1)+$QG_X*$QG_X)>$map[$Y][$X]['blocage']){
		  $map[$daY][$daX]['class'][]='qgbb ';
		}
		if(!isset($map[$daY-1][$daX]) ||
		   $map[$daY-1][$daX]['cout_vision_total']>$dist ||
		   $map[$daY-1][$daX]['type_case']=='vide' ||
		   sqrt(($QG_Y-1)*($QG_Y-1)+$QG_X*$QG_X)>$map[$Y][$X]['blocage']){
		  $map[$daY][$daX]['class'][]='qgbh ';
		}
	      }
	      else{
		// Case hors QG
		if(isset($map[$daY][$daX-1]) &&
		   $map[$daY][$daX-1]['cout_vision_total']<=$dist &&
		   $map[$daY][$daX-1]['type_case']!='vide' &&
		   sqrt(($QG_X-1)*($QG_X-1)+$QG_Y*$QG_Y)<=$map[$Y][$X]['blocage']){
		  $map[$daY][$daX]['class'][]='qgbg ';
		}
		if(isset($map[$daY][$daX+1]) &&
		   $map[$daY][$daX+1]['cout_vision_total']<=$dist &&
		   $map[$daY][$daX+1]['type_case']!='vide' &&
		   sqrt(($QG_X+1)*($QG_X+1)+$QG_Y*$QG_Y)<=$map[$Y][$X]['blocage']){
		  $map[$daY][$daX]['class'][]='qgbd ';
		}
		if(isset($map[$daY+1][$daX]) &&
		   $map[$daY+1][$daX]['cout_vision_total']<=$dist &&
		   $map[$daY+1][$daX]['type_case']!='vide' &&
		   sqrt(($QG_Y+1)*($QG_Y+1)+$QG_X*$QG_X)<=$map[$Y][$X]['blocage']){
		  $map[$daY][$daX]['class'][]='qgbb ';
		}
		if(isset($map[$daY-1][$daX]) &&
		   $map[$daY-1][$daX]['cout_vision_total']<=$dist &&
		   $map[$daY-1][$daX]['type_case']!='vide' &&
		   sqrt(($QG_Y-1)*($QG_Y-1)+$QG_X*$QG_X)<=$map[$Y][$X]['blocage']){
		  $map[$daY][$daX]['class'][]='qgbh ';
		}
	      }
	    }
	  }
	}
      }
    }
  }
  return $map;
}


function set_visible($map,$dist){
  $liste_QG=array();
  require('../inits/intersect_'.$dist.'.php');

  for($Y=$dist;$Y>=-$dist;$Y--){
    for($X=-$dist;$X<=$dist;$X++){
      if(isset($map[$Y][$X]) && $map[$Y][$X]['type_case']!='vide'){
	if(sqrt($X*$X+$Y*$Y)>$dist){
	  // Case non visible.
	  $map[$Y][$X]['cout_vision_total']=$dist+1;
	  $map[$Y][$X]['perte_precision_total']=0;
	  $map[$Y][$X]['cout_portee_total']=1000;
	}
	else if($X!=0 || $Y!=0){
	  // Case peut être visible ou visable.
	  $plop=is_case_visible($X,$Y,$map,$intersect);
	  $map[$Y][$X]['cout_vision_total']=$plop[0];
	  $map[$Y][$X]['perte_precision_total']=$plop[1];
	  $map[$Y][$X]['cout_portee_total']=$plop[2];
	  if(abs($X)<=1 && abs($Y)<=1){
	    // Case auxquelles on est collé, dans tous les cas on voit 
	    // ce qu'il y a dessus.
	    $map[$Y][$X]['cout_vision_total']=1;
	    if(abs($map[$Y][$X]['z_perso']-$map[0][0]['z_perso'])<=1){
	      // Cube nous entourant, on peut frapper avec une arme de CàC.
	      $map[$Y][$X]['cout_portee_total']=1;
	    }
	  }
	}
	else{
	  // Notre case.
	  $map[$Y][$X]['cout_vision_total']=0;
	  $map[$Y][$X]['perte_precision_total']=0;
	  $map[$Y][$X]['cout_portee_total']=1000;
	}
      }
    }
  }
  unset($intersect);
  return $map;
}

function is_case_visible($x_case, $y_case,$map,$intersect){
  global $perso;

  $mulX=$x_case<0?-1:1;
  $mulY=$y_case<0?-1:1;

  if(abs($x_case)>abs($y_case)){
    // Il faut inverser x et y.
    $x_calc=abs($y_case);
    $y_calc=abs($x_case);
  }
  else{
    $x_calc=abs($x_case);
    $y_calc=abs($y_case);
  }

  if($perso['soussol']){
    $air_vision=100;
    $air_portee=1000;
    $air_precision=0;
  }
  else{
    $air_vision=1;
    $air_portee=1;
    $air_precision=0;
  }
  
  $cout_vision=0;
  $perte_precision=0;
  $cout_portee=0;
  
  $z_sortie=$map[0][0]['z_perso'];
  $plus_haut=($z_sortie>$map[$y_case][$x_case]['z_perso']?1:0);
  for($k=0;$k<count($intersect[$y_calc][$x_calc]);$k++){
    if($k && $map[$y_courant][$x_courant]['bloque_vue']){
      // La case précédente bloquait la vision.
      // Donc à partie de là, on peut virer de la boucle.
      $cout_vision+=1000;
    }
    $z_entree=$z_sortie;

    if(abs($x_case)>abs($y_case)){
      $y_courant=$intersect[$y_calc][$x_calc][$k]['case'][0]*$mulY;
      $x_courant=$intersect[$y_calc][$x_calc][$k]['case'][1]*$mulX;
    }
    else{
      $x_courant=$intersect[$y_calc][$x_calc][$k]['case'][0]*$mulX;
      $y_courant=$intersect[$y_calc][$x_calc][$k]['case'][1]*$mulY;
    }
    $x_entree=$intersect[$y_calc][$x_calc][$k]['pt_entree'][0]*$mulX;
    $y_entree=$intersect[$y_calc][$x_calc][$k]['pt_entree'][1]*$mulY;
    $x_sortie=$intersect[$y_calc][$x_calc][$k]['pt_sortie'][0]*$mulX;
    $y_sortie=$intersect[$y_calc][$x_calc][$k]['pt_sortie'][1]*$mulY;
    
    // Calcul de la hauteur à laquelle on sort de la case.
    $z_sortie=
      ($map[$y_case][$x_case]['z_perso']-$map[0][0]['z_perso'])
      / sqrt($x_case*$x_case+$y_case*$y_case)
      * pow(($x_sortie*$x_sortie+$y_sortie*$y_sortie),0.5)
      + $map[0][0]['z_perso'];

    if(!($z_entree>=$map[$y_courant][$x_courant]['z_sol']  &&
	 $z_sortie>=$map[$y_courant][$x_courant]['z_sol']) &&
       !($k==0 ||
	 $k==count($intersect[$y_calc][$x_calc])-1)){
      // La ligne de vue traverse le sol et est donc bloquée.
      $cout_vision=100;
    }
    else if($z_entree>=$map[$y_courant][$x_courant]['z_total'] && $z_sortie>=$map[$y_courant][$x_courant]['z_total']){
      // La ligne de vue passe au dessus du volume donc n'est pas affectée 
      // par la case.
      $dist_pente=pow(pow($z_sortie-$z_entree,2)+pow($x_sortie-$x_entree,2)+pow($y_sortie-$y_entree,2),0.5);
      $dist_droite=pow(pow($x_sortie-$x_entree,2)+pow($y_sortie-$y_entree,2),0.5);
      $cout_vision+=$plus_haut?$air_vision*$dist_droite:$air_vision*$dist_pente;
      $cout_portee+=$air_portee*$dist_pente;
      $perte_precision+=$air_precision*$dist_pente;
    }
    else if($z_entree<=$map[$y_courant][$x_courant]['z_total'] && $z_sortie<=$map[$y_courant][$x_courant]['z_total']){
      // La ligne de vue passe entièrement par le volume de la case.
      $dist_pente=pow(pow($z_sortie-$z_entree,2)+pow($x_sortie-$x_entree,2)+pow($y_sortie-$y_entree,2),0.5);
      $cout_vision+=$map[$y_courant][$x_courant]['cout_vision']*$dist_pente;
      $cout_portee+=$map[$y_courant][$x_courant]['cout_portee']*$dist_pente;
      $perte_precision+=$map[$y_courant][$x_courant]['perte_precision']*$dist_pente;
    }
    else{
      // Cas batard.
      $alpha=
	($map[$y_courant][$x_courant]['z_total']-$map[0][0]['z_perso'])
	* sqrt($x_case*$x_case+$y_case*$y_case)
	/ ($map[$y_case][$x_case]['z_perso']-$map[0][0]['z_perso'])
	- pow($x_entree*$x_entree+$y_entree*$y_entree,0.5);
      $dist_pente_avant=pow($alpha*$alpha+pow($map[$y_courant][$x_courant]['z_total']-$z_entree,2),0.5);
      $dist_pente_apres=pow(pow($x_sortie-$x_entree,2)+pow($y_sortie-$y_entree,2)+pow($z_sortie-$z_entree,2),0.5)-$dist_pente_avant;
      if($plus_haut){
	$cout_vision+=$air_vision*$alpha+$map[$y_courant][$x_courant]['cout_vision']*$dist_pente_apres;
	$cout_portee+=$air_portee*$dist_pente_avant+$map[$y_courant][$x_courant]['cout_portee']*$dist_pente_apres;
	$perte_precision+=$air_precision*$dist_pente_avant+$map[$y_courant][$x_courant]['perte_precision']*$dist_pente_apres;
      }
      else{
	$cout_vision+=$map[$y_courant][$x_courant]['cout_vision']*$dist_pente_avant+$air_vision*$dist_pente_apres;
	$cout_portee+=$map[$y_courant][$x_courant]['cout_portee']*$dist_pente_avant+$air_portee*$dist_pente_apres;
	$perte_precision+=$map[$y_courant][$x_courant]['perte_precision']*$dist_pente_avant+$air_precision*$dist_pente_apres;
      }
    }
  }
  return array($cout_vision,$perte_precision,$cout_portee);
}
?>