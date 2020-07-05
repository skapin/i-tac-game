<?php
$time=time();
$perso=my_fetch_array('SELECT persos.ID,
persos.compte,
persos.nom,
persos.message,
persos.armee,
persos.PV,
persos.PV_max+5*imp_PV AS PV_max,
persos.date_last_regen,
persos.date_lost_PV,
persos.date_last_PM,
persos.date_last_update,
persos.date_last_shot,
persos.date_last_mouv,
persos.date_last_recuptir,
persos.date_last_reparation,
persos.date_last_bouge,
persos.tir_restants,
persos.VS,
persos.grade_reel,
persos.grade_max,
persos.confiance,
persos.PM,
persos.sprints,
persos.date_ecl,
persos.map,
persos.X,
persos.Y,
persos.relocalisation,
persos.camouflage,
persos.mouchard,
persos.date_mouchard,
persos.date_last_tir,
persos.tir_restants,
persos.malus_camou_tir,
persos.arme,
persos.stages,
persos.lastordres,
persos.cartographier,

/* Mission */
missions.ID AS ID_mission,
missions.nom AS nom_mission,

/* Carte */
cartes.soussol,
cartes.coordonnees,

/* Compagnie */
compagnies.ID AS ID_compa,
compagnies.nom AS nom_compa,
compagnies.initiales AS initiales_compa,

/* Grade */
grades.ID AS ID_grade,
grades.nom AS nom_grade,
grades.niveau AS niveau_grade,

/* Competences */
persos.camou AS camou_reel,
persos.infi AS infi_reel,
persos.ecl AS ecl_reel,
persos.mine AS mine_reel,
persos.demine AS demine_reel,
persos.assaut AS assaut_reel,
persos.mitrailleuse AS mitrailleuse_reel,
persos.snipe AS snipe_reel,
persos.lourde AS lourde_reel,
persos.LR AS LR_reel,
persos.lekarz AS lekarz_reel,
persos.pompe AS pompe_reel,
persos.cac AS cac_reel,
persos.biotech AS biotech_reel,
persos.pistolet AS pistolet_reel,
persos.plaine AS plaine_reel,
persos.foret AS foret_reel,
persos.montagne AS montagne_reel,
persos.desert AS desert_reel,
persos.marais AS marais_reel,
persos.nage AS nage_reel,
persos.pont AS pont_reel,
persos.escalade AS escalade_reel,
FLOOR(persos.camou/10000) AS camou,
FLOOR(persos.infi/10000) AS infi,
FLOOR(persos.ecl/10000) AS ecl,
FLOOR(persos.mine/10000) AS mine,
FLOOR(persos.demine/10000) AS demine,
FLOOR(persos.assaut/10000) AS assaut,
FLOOR(persos.mitrailleuse/10000) AS mitrailleuse,
FLOOR(persos.snipe/10000) AS snipe,
FLOOR(persos.lourde/10000) AS lourde,
FLOOR(persos.LR/10000) AS LR,
FLOOR(persos.lekarz/10000) AS lekarz,
FLOOR(persos.pompe/10000) AS pompe,
FLOOR(persos.cac/10000) AS cac,
FLOOR(persos.biotech/10000) AS biotech,
FLOOR(persos.pistolet/10000) AS pistolet,
FLOOR(persos.plaine/10000) AS plaine,
FLOOR(persos.foret/10000) AS foret,
FLOOR(persos.montagne/10000) AS montagne,
FLOOR(persos.desert/10000) AS desert,
FLOOR(persos.marais/10000) AS marais,
FLOOR(persos.nage/10000) AS nage,
FLOOR(persos.pont/10000) AS pont,
FLOOR(persos.escalade/10000) AS escalade,
persos.camou_max,
persos.infi_max,
persos.ecl_max,
persos.mine_max,
persos.demine_max,
persos.assaut_max,
persos.mitrailleuse_max,
persos.snipe_max,
persos.lourde_max,
persos.LR_max,
persos.lekarz_max,
persos.pompe_max,
persos.cac_max,
persos.biotech_max,
persos.pistolet_max,
persos.plaine_max,
persos.foret_max,
persos.montagne_max,
persos.desert_max,
persos.marais_max,
persos.nage_max,
persos.pont_max,
persos.escalade_max,

/* Implants */
persos.cloned,
persos.implants_dispo,
persos.imp_PV,
persos.imp_vue,
persos.imp_resist,
persos.imp_regen,
persos.imp_vit1,
persos.imp_force,
persos.imp_endu,
persos.imp_vit2,
persos.imp_resist2,
persos.imp_vit4,
persos.imp_resist4,
persos.imp_precision,
5 + persos.imp_vue AS vision_reelle,

/* Peines */
persos.peine_assaut,
persos.peine_pompe,
persos.peine_snipe,
persos.peine_mitrailleuse,
persos.peine_lekarz,
persos.peine_lourde,
persos.peine_LR,
persos.peine_cac,
persos.peine_biotech,
persos.peine_pistolet,
persos.peine_tubes,
persos.peine_armes,
persos.peine_munars,
persos.peine_armures,
persos.peine_reparations,
persos.peine_hopital,
persos.peine_grade,
persos.peine_VS,
persos.peine_forum,
persos.peine_TRT,
persos.peine_fin,
persos.peine_debutTRT,

/* Transfuge */
persos.transfuge_VS,
persos.fin_effet,

/* Defense */
persos.defense,
persos.date_defense,

/* Gadgets */
gad1.ID AS ID_gad1,
gad1.nom AS nom_gad1,
gad1.bonus_precision AS bonus_precision_gad1,
gad1.bonus_camou AS camou_gad1,
gad1.bonus_tir_camou AS bonus_tir_camou_gad1,
gad1.bonus_vision AS bonus_vision_gad1,
gad1.eclaireur AS ecl_gad1,
gad1.escalade AS escalade_gad1,
gad1.foret AS foret_gad1,
gad1.montagne AS montagne_gad1,
gad1.desert AS desert_gad1,
gad1.marais AS marais_gad1,
gad1.plaine AS plaine_gad1,
gad1.nage AS nage_gad1,
gad1.pont AS pont_gad1,
gad1.mines AS mines_gad1,
gad1.degats_mines AS degats_mines_gad1,
gad1.pourcent_PV AS pourcent_gad1,
gad1.instabilite AS instabilite_gad1,
gad1.discretion AS discretion_gad1,
gad1.arme AS arme_gad1,
gad1.armure AS armure_gad1,
gad1.stack AS stack_gad1,
persos.mun_g1 AS munitions_restantes_gad1,
persos.used_1 AS date_last_used_gad1,

gad2.ID AS ID_gad2,
gad2.nom AS nom_gad2,
gad2.bonus_precision AS bonus_precision_gad2,
gad2.bonus_camou AS camou_gad2,
gad2.bonus_tir_camou AS bonus_tir_camou_gad2,
gad2.bonus_vision AS bonus_vision_gad2,
gad2.eclaireur AS ecl_gad2,
gad2.escalade AS escalade_gad2,
gad2.foret AS foret_gad2,
gad2.montagne AS montagne_gad2,
gad2.desert AS desert_gad2,
gad2.marais AS marais_gad2,
gad2.plaine AS plaine_gad2,
gad2.nage AS nage_gad2,
gad2.pont AS pont_gad2,
gad2.mines AS mines_gad2,
gad2.degats_mines AS degats_mines_gad2,
gad2.pourcent_PV AS pourcent_gad2,
gad2.instabilite AS instabilite_gad2,
gad2.discretion AS discretion_gad2,
gad2.arme AS arme_gad2,
gad2.armure AS armure_gad2,
gad2.stack AS stack_gad2,
persos.mun_g2 AS munitions_restantes_gad2,
persos.used_2 AS date_last_used_gad2,

gad3.ID AS ID_gad3,
gad3.nom AS nom_gad3,
gad3.bonus_precision AS bonus_precision_gad3,
gad3.bonus_camou AS camou_gad3,
gad3.bonus_tir_camou AS bonus_tir_camou_gad3,
gad3.bonus_vision AS bonus_vision_gad3,
gad3.eclaireur AS ecl_gad3,
gad3.escalade AS escalade_gad3,
gad3.foret AS foret_gad3,
gad3.montagne AS montagne_gad3,
gad3.desert AS desert_gad3,
gad3.marais AS marais_gad3,
gad3.plaine AS plaine_gad3,
gad3.nage AS nage_gad3,
gad3.pont AS pont_gad3,
gad3.mines AS mines_gad3,
gad3.degats_mines AS degats_mines_gad3,
gad3.pourcent_PV AS pourcent_gad3,
gad3.instabilite AS instabilite_gad3,
gad3.discretion AS discretion_gad3,
gad3.arme AS arme_gad3,
gad3.armure AS armure_gad3,
gad3.stack AS stack_gad3,
persos.mun_g3 AS munitions_restantes_gad3,
persos.used_3 AS date_last_used_gad3,

/* Armes */
arme1.ID AS ID_arme1,
arme1.type AS type_arme1,
arme1.portee AS portee_arme1,
arme1.precision_min AS precision_min_arme1,
arme1.precision_max AS precision_max_arme1,
arme1.critique AS critique_arme1,
arme1.seuil_critique AS seuil_critique_arme1,
arme1.pm_critique AS pm_critique_arme1,
arme1.degats AS degats_arme1,
arme1.degat_vie AS degats_vie_arme1,
persos.munitions_1 AS munars_arme1,
arme1.max_munitions AS munars_max_arme1,
arme1.type_munitions AS type_munars_arme1,
arme1.tir_munars AS cadence_arme1,
FLOOR(persos.tir_restants*arme1.tirs/100) AS tirs_restants_arme1,
arme1.precision_min AS precision_min_arme1,
arme1.tirs AS nbr_tirs_arme1,
arme1.nom AS nom_arme1,
arme1.malus_camou AS malus_camou_arme1,
arme1.zone AS rayon_arme1,
arme1.diminution AS perte_degats_arme1,
arme1.touche AS touche_arme1,
arme1.dimit AS dimit_arme1,
arme1.perte_tirs AS perte_tirs_arme1,
arme1.perte_PM AS perte_PM_arme1,
arme1.lvl AS lvl_arme1,
arme1.poids AS poids_arme1,
arme1.dropable AS dropable_arme1,

arme2.ID AS ID_arme2,
arme2.type AS type_arme2,
arme2.portee AS portee_arme2,
arme2.precision_min AS precision_min_arme2,
arme2.precision_max AS precision_max_arme2,
arme2.critique AS critique_arme2,
arme2.seuil_critique AS seuil_critique_arme2,
arme2.pm_critique AS pm_critique_arme2,
arme2.degats AS degats_arme2,
arme2.degat_vie AS degats_vie_arme2,
persos.munitions_2 AS munars_arme2,
arme2.max_munitions AS munars_max_arme2,
arme2.type_munitions AS type_munars_arme2,
arme2.tir_munars AS cadence_arme2,
FLOOR(persos.tir_restants*arme2.tirs/100) AS tirs_restants_arme2,
arme2.precision_min AS precision_min_arme2,
arme2.tirs AS nbr_tirs_arme2,
arme2.nom AS nom_arme2,
arme2.malus_camou AS malus_camou_arme2,
arme2.zone AS rayon_arme2,
arme2.diminution AS perte_degats_arme2,
arme2.touche AS touche_arme2,
arme2.dimit AS dimit_arme2,
arme2.perte_tirs AS perte_tirs_arme2,
arme2.perte_PM AS perte_PM_arme2,
arme2.lvl AS lvl_arme2,
arme2.poids AS poids_arme2,
arme2.dropable AS dropable_arme2,

/* Munitions */
mun1.ID AS ID_mun1,
mun1.nom AS nom_mun1,
mun1.poids AS poids_mun1,

mun2.ID AS ID_mun2,
mun2.nom AS nom_mun2,
mun2.poids AS poids_mun2,

/* Armure */
armures.ID AS ID_armure,
persos.PA AS PA,
armures.PA AS PA_max,
armures.PA_critiques AS PA_critique,
armures.malus_camou AS malus_camou_armure,
armures.malus_terrain AS malus_terrain_armure,
armures.bonus_precision,
armures.malus_critique,
armures.nom AS nom_armure,
armures.grade AS grade_armure,
armures.initiales AS initiales_armure,
armures.type AS type_armure,
armures.capacite AS poids_max,
armures.franchissement,
armures.degatsCaC,

/* Droits */
 /* Droits de general */
persos.gene_compas,
persos.gene_droits,
persos.gene_ngene,
persos.gene_medailles,
persos.gene_ordres,
persos.gene_trt,
persos.gene_grades,
persos.niveau_gene,
persos.gene_transfuge,
persos.gene_ordres,
persos.gene_qgs,
persos.gene_strat,
persos.gene_cartographie,
  /* Droits de colonel. */
persos.colo_HRP,
persos.colo_RP,
persos.colo_colo,
persos.colo_criteres,
persos.colo_droits,
persos.colo_sigle,
persos.colo_valider,
persos.colo_ordres,
persos.colo_virer,
persos.colo_grades,
persos.niveau_compa,
  /* Lecture des ordres. */
persos.ordres,
persos.ordresconfi,
persos.ordrescompa,
  /* Acc�s aux forums. */
persos.forum_compa,
persos.forum_em,
persos.forum_gene,

/* Equipement */
equipement.ID AS ID_equipement,
equipement.camp AS camp_equipement,
equipement.securite AS securite_equipement,
equipement.desamorce AS desamorce_equipement,
equipement.nombre AS nombre_mun3,
equipement.type AS type_equipement,
  /* Arme */
arme3.ID AS ID_arme3,
arme3.nom AS nom_arme3,
arme3.type AS type_arme3,
arme3.type_munitions,
arme3.armure AS armure_arme3,
arme3.palier AS palier_arme3,
arme3.visibilite AS visibilite_arme3,
arme3.secu_degats AS secu_degats,
arme3.perte_tirs AS perte_tirs,
arme3.perte_PM AS perte_PM,
arme3.poids AS poids_arme3,
  /* Munitions */
mun3.ID AS ID_mun3,
mun3.nom AS nom_mun3,
mun3.poids AS poids_mun3,

/* Objets */
objets.nom AS nom_objet,
objets.poids AS poids_objet,
objets.description AS desc_objet,
objets.story AS story_objet

FROM persos
   LEFT OUTER JOIN armes AS arme1
        ON persos.matos_1=arme1.ID
   LEFT OUTER JOIN munars AS mun1
        ON arme1.type_munitions=mun1.ID
   LEFT OUTER JOIN armes arme2
        ON persos.matos_2=arme2.ID
   LEFT OUTER JOIN munars AS mun2
        ON arme2.type_munitions=mun2.ID
   LEFT OUTER JOIN gadgets AS gad1
        ON gad1.ID=persos.gadget_1
   LEFT OUTER JOIN gadgets AS gad2
        ON gad2.ID=persos.gadget_2
   LEFT OUTER JOIN gadgets AS gad3
        ON gad3.ID=persos.gadget_3
   LEFT OUTER JOIN missions
        ON missions.ID=persos.mission
   LEFT OUTER JOIN compagnies
        ON compagnies.ID=persos.compagnie
   LEFT OUTER JOIN armures
        ON armures.ID=persos.armure
   LEFT OUTER JOIN grades
        ON grades.ID=persos.grade
   LEFT OUTER JOIN equipement
        ON persos.ID = equipement.possesseur
   LEFT OUTER JOIN munars AS mun3
        ON equipement.objet_ID = mun3.ID
       AND equipement.type = 2
   LEFT OUTER JOIN armes AS arme3
        ON equipement.objet_ID = arme3.ID
       AND equipement.type = 1
   LEFT OUTER JOIN objets
        ON equipement.objet_ID = objets.ID
       AND equipement.type = 3
   LEFT OUTER JOIN cartes
        ON cartes.ID=persos.map
WHERE persos.ID='.$_SESSION['com_perso']);
if($perso[0]){
  //  require_once('inits/terrains.php');
  require_once('../inits/competences.php');
  $slot=$perso[1]['arme'];
  $slot==1?$slot2=2:$slot2=1;

  //***********************************************************************
  // Enregistrement de l'inventaire du perso.
  //***********************************************************************
  $perso[1]['inventaire']=array();
  $perso[1]['inventaire']['poids']=0;
  if($perso[1]['ID_equipement']){
    for($i=1;$i<=$perso[0];$i++){
      if($perso[$i]['type_equipement']==1){
	// C'est une arme.
	$perso[1]['inventaire']['armes'][]=
	  array('ID'=>$perso[$i]['ID_equipement'],
		'camp'=>$perso[$i]['camp_equipement'],
		'securite'=>$perso[$i]['securite_equipement'],
		'desamorce'=>$perso[$i]['desamorce_equipement'],
		'arme_ID'=>$perso[$i]['ID_arme3'],
		'nom'=>$perso[$i]['nom_arme3'],
		'type'=>$perso[$i]['type_arme3'],
		'type_munitions'=>$perso[$i]['type_munitions'],
		'armure'=>$perso[$i]['armure_arme3'],
		'palier'=>$perso[$i]['palier_arme3'],
		'secu_degats'=>$perso[$i]['secu_degats'],
		'perte_tirs'=>$perso[$i]['perte_tirs'],
		'perte_PM'=>$perso[$i]['perte_PM'],
		'poids'=>$perso[$i]['poids_arme3'],
		'connue'=>($perso[$i]['visibilite_arme3']==1 ||
			   $perso[$i]['camp_equipement']==$perso[$i]['armee'])
		);
	$perso[1]['inventaire']['poids']+=$perso[$i]['poids_arme3'];
      }
      if($perso[$i]['type_equipement']==3){
	// C'est un objet.
	$perso[1]['inventaire']['objets'][]=
	  array('ID'=>$perso[$i]['ID_equipement'],
		'nom'=>$perso[$i]['nom_objet'],
		'poids'=>$perso[$i]['poids_objet'],
		'desc'=>$perso[$i]['desc_objet'],
		'story'=>$perso[$i]['story_objet'],
		);
	$perso[1]['inventaire']['poids']+=$perso[$i]['poids_objet'];
      }
      else if($perso[$i]['type_equipement']==2){
	// C'est un tas de munitions.
	// On recharge nos armes, en priorite celle equipee.
	if($perso[$i]['ID_mun3']==$perso[1]['type_munars_arme'.$slot] &&
	   $perso[1]['munars_arme'.$slot]<$perso[1]['munars_max_arme'.$slot]){
	  // Arme primaire qui manque de ce type de munars.
	  $nbr=min($perso[1]['munars_max_arme'.$slot]-$perso[1]['munars_arme'.$slot],
		   $perso[$i]['nombre_mun3']);
	  request('UPDATE persos
                               SET munitions_'.$slot.'=`munitions_'.$slot.'`+'.$nbr.'
                               WHERE ID='.$_SESSION['com_perso'].'
                               LIMIT 1');
	  if(affected_rows()){
	    // La requete a fonctionne, on met a jour.
	    $perso[1]['munars_arme'.$slot]+=$nbr;
	    if($nbr==$perso[$i]['nombre_mun3']){
	      // Plus de munars en trop.
	      request('DELETE FROM equipement
                                       WHERE ID='.$perso[$i]['ID_equipement'].' LIMIT 1');
	      if(affected_rows()){
		request('OPTIMIZE TABLE equipement');
		$perso[$i]['nombre_mun3']=0;
	      }
	    }
	    else{
	      request('UPDATE equipement
                                       SET nombre=`nombre`-'.$nbr.'
                                       WHERE ID='.$perso[$i]['ID_equipement'].' LIMIT 1');
	      if(affected_rows())
		$perso[$i]['nombre_mun3']-=$nbr;
	    }
	  }
	}
	if($perso[$i]['ID_mun3']==$perso[1]['type_munars_arme'.$slot2] &&
	   $perso[1]['munars_arme'.$slot2]<$perso[1]['munars_max_arme'.$slot2]){
	  // Arme secondaire qui manque de ce type de munars.
	  $nbr=min($perso[1]['munars_max_arme'.$slot2]-$perso[1]['munars_arme'.$slot2],
		   $perso[$i]['nombre_mun3']);
	  request('UPDATE persos
                               SET munitions_'.$slot2.'=`munitions_'.$slot2.'`+'.$nbr.'
                               WHERE ID='.$_SESSION['com_perso'].'
                               LIMIT 1');
	  if(affected_rows()){
	    // La requete a fonctionne, on met a jour.
	    $perso[1]['munars_arme'.$slot2]+=$nbr;
	    if($nbr==$perso[$i]['nombre_mun3']){
	      // Plus de munars en trop.
	      request('DELETE FROM equipement
                                       WHERE ID='.$perso[$i]['ID_equipement'].' LIMIT 1');
	      if(affected_rows()){
		request('OPTIMIZE TABLE equipement');
		$perso[$i]['nombre_mun3']=0;
	      }
	    }
	    else{
	      request('UPDATE equipement
                                       SET nombre=`nombre`-'.$nbr.'
                                       WHERE ID='.$perso[$i]['ID_equipement'].' LIMIT 1');
	      if(affected_rows())
		$perso[$i]['nombre_mun3']-=$nbr;
	    }
	  }
	}
	if($perso[$i]['nombre_mun3']){
	  // Si un tas de ce type de munars est deja en notre possession, 
	  // on le fait grossir.
	  if(isset($perso[1]['inventaire']['munars'][$perso[$i]['ID_mun3']])){
	    $perso[1]['inventaire']['munars'][$perso[$i]['ID_mun3']]['nombre']+=$perso[$i]['nombre_mun3'];
	    $perso[1]['inventaire']['munars'][$perso[$i]['ID_mun3']]['poids_total']+=$perso[$i]['poids_mun3']*$perso[$i]['nombre_mun3'];
	    request('UPDATE equipement
                               SET nombre=`nombre`+'.$perso[$i]['nombre_mun3'].'
                               WHERE ID='.$perso[1]['inventaire']['munars'][$perso[$i]['ID_mun3']]['ID'].' LIMIT 1');
	    if(affected_rows())
	      request('DELETE FROM equipement
                                 WHERE ID='.$perso[$i]['ID_equipement'].' LIMIT 1');
	    if(affected_rows())
	      request('OPTIMIZE TABLE equipement');
	  }
	  else
	    $perso[1]['inventaire']['munars'][$perso[$i]['ID_mun3']]=
	      array('ID'=>$perso[$i]['ID_equipement'],
		    'nombre'=>$perso[$i]['nombre_mun3'],
		    'munar_ID'=>$perso[$i]['ID_mun3'],
		    'nom'=>$perso[$i]['nom_mun3'],
		    'poids'=>$perso[$i]['poids_mun3'],
		    'poids_total'=>$perso[$i]['poids_mun3']*$perso[$i]['nombre_mun3']);
	}
	$perso[1]['inventaire']['poids']+=$perso[$i]['poids_mun3']*$perso[$i]['nombre_mun3'];
      }
      if($i!=1)
	unset($perso[$i]);
    }
  }
  $perso=$perso[1];
  //print_r($perso[1]['inventaire']);
  
  //***********************************************************************
  // Si le perso est sur une map, on enregistre les infos du terrain sous lui
  //***********************************************************************
  
  if($perso['map']){
    $_SESSION['com_terrain']=my_fetch_array('SELECT terrains.*
                                                   FROM terrains
                                                      INNER JOIN `carte_'.$perso['map'].'`
                                                          ON `carte_'.$perso['map'].'`.terrain=terrains.ID
                                                   WHERE `X`='.$perso['X'].'
                                                     AND `Y`='.$perso['Y'].'
                                                   LIMIT 1');
    $_SESSION['com_terrain']=$_SESSION['com_terrain'][1];
  }
  else
    $_SESSION['com_terrain']=0;
  
  // Verifions si les gadgets sont utilisables ensemble avec les armes

  for($i=1;$i<=2;$i++){
    if($perso['arme_gad1'] & pow(2,$perso['type_arme'.$i]) &&
       $perso['armure_gad1'] & $perso['type_armure'])
      $g1[$i]=1;
    else
      $g1[$i]=0;

    if($perso['arme_gad2'] & pow(2,$perso['type_arme'.$i]) &&
       $perso['armure_gad2'] & $perso['type_armure'] &&
       $perso['stack_gad2']!=$perso['stack_gad1'])
      $g2[$i]=1;
    else
      $g2[$i]=0;
    if($perso['arme_gad3'] & pow(2,$perso['type_arme'.$i]) &&
       $perso['armure_gad3'] & $perso['type_armure'] &&
       $perso['stack_gad3']!=$perso['stack_gad1'] &&
       $perso['stack_gad3']!=$perso['stack_gad2'])
      $g3[$i]=1;
    else
      $g3[$i]=0;
  }
  //***********************************************************************
  // Calculs de quelques trucs du perso.
  //***********************************************************************

  $perso['sprints_max']=40+($perso['type_armure']==1?$perso['imp_endu']*4:0);
  $perso['vision']=max(1,min(13,$perso['vision_reelle']+$g1[$slot]*$perso['bonus_vision_gad1']+$g2[$slot]*$perso['bonus_vision_gad2']+$g3[$slot]*$perso['bonus_vision_gad3']));
  $perso['camou']=max(0,min(9,$perso['camou']+$g1[$slot]*$perso['camou_gad1']+$g2[$slot]*$perso['camou_gad2']+$g3[$slot]*$perso['camou_gad3']));
  $perso['ecl']=max(0,min(9,$perso['ecl']+$g1[$slot]*$perso['ecl_gad1']+$g2[$slot]*$perso['ecl_gad2']+$g3[$slot]*$perso['ecl_gad3']+(($perso['date_ecl']+$GLOBALS['tour']/5)>$time?2:0)));
  $perso['plaine']=max(0,min(9,$perso['plaine']+$g1[$slot]*$perso['plaine_gad1']+$g2[$slot]*$perso['plaine_gad2']+$g3[$slot]*$perso['plaine_gad3']));
  $perso['foret']=max(0,min(9,$perso['foret']+$g1[$slot]*$perso['foret_gad1']+$g2[$slot]*$perso['foret_gad2']+$g3[$slot]*$perso['foret_gad3']));
  $perso['montagne']=max(0,min(9,$perso['montagne']+$g1[$slot]*$perso['montagne_gad1']+$g2[$slot]*$perso['montagne_gad2']+$g3[$slot]*$perso['montagne_gad3']));
  $perso['desert']=max(0,min(9,$perso['desert']+$g1[$slot]*$perso['desert_gad1']+$g2[$slot]*$perso['desert_gad2']+$g3[$slot]*$perso['desert_gad3']));
  $perso['marais']=max(0,min(9,$perso['marais']+$g1[$slot]*$perso['marais_gad1']+$g2[$slot]*$perso['marais_gad2']+$g3[$slot]*$perso['marais_gad3']));
  $perso['nage']=max(0,min(9,$perso['nage']+$g1[$slot]*$perso['nage_gad1']+$g2[$slot]*$perso['nage_gad2']+$g3[$slot]*$perso['nage_gad3']));
  $perso['pont']=max(0,min(9,$perso['pont']+$g1[$slot]*$perso['pont_gad1']+$g2[$slot]*$perso['pont_gad2']+$g3[$slot]*$perso['pont_gad3']));
  $perso['escalade']=max(0,min(9,$perso['escalade']+$g1[$slot]*$perso['escalade_gad1']+$g2[$slot]*$perso['escalade_gad2']+$g3[$slot]*$perso['escalade_gad3']));
  $perso['vision_armure']=1+floor($perso['lekarz']/3)+floor($perso['vision_reelle']/10);
  $perso['infi']=max(0,min(9,$perso['infi']));

  //***********************************************************************
  // Calculs sur les armes.
  //***********************************************************************
  for($i=1;$i<=2;$i++){
    $perso['portee_arme'.$i]=min($perso['portee_arme'.$i],$perso['vision']);
    $perso['precision_min_arme'.$i]+=$g1[$i]*$perso['bonus_precision_gad1']
      +$g2[$i]*$perso['bonus_precision_gad2']
      +$g3[$i]*$perso['bonus_precision_gad3']
      +($perso['type_armure']==4?$perso['imp_precision']:0);
    
    $perso['precision_max_arme'.$i]+=$g1[$i]*$perso['bonus_precision_gad1']
      +$g2[$i]*$perso['bonus_precision_gad2']
      +$g3[$i]*$perso['bonus_precision_gad3']
      +($perso['type_armure']==4?$perso['imp_precision']:0);
    
    $perso['critique_arme'.$i]+=$perso[bdd_arme($perso['type_arme'.$i])];

    $perso['degats_arme'.$i]*=($perso['type_arme'.$i]==7&&$perso['type_armure']==1?1+0.1*$perso['imp_force']:1);
    $perso['degats_arme'.$i]*=($perso['type_arme'.$i]==7?1+$perso['degatsCaC']/100:1);
    
    $perso['malus_camou_arme'.$i]*=(100-($g1[$i]*$perso['bonus_tir_camou_gad1']
					 +$g2[$i]*$perso['bonus_tir_camou_gad2']
					 +$g3[$i]*$perso['bonus_tir_camou_gad3']))/100;
    $perso['poids_arme'.$i]+=$perso['poids_mun'.$i]*$perso['munars_arme'.$i];
  }
  
  //***********************************************************************
  // Calculs sur l'armure
  //***********************************************************************
  $perso['malus_terrain_armure']-=2*$perso['imp_vit'.(isset($perso['type_armure'])?$perso['type_armure']:'1')];
  $perso['special']=($perso['type_armure']==1 && $perso['imp_force']>=5?1:0);
  $perso['poids_max']+=($perso['type_armure']==1?2*$perso['imp_force']:0);
      
  //***********************************************************************
  // Calcul des malus de degats.
  //***********************************************************************
  
  if(!$perso['PA_critique']){
    $mul_PV=1;
    $mul_PA=0;
  }
  else if($perso['type_armure']==1){
    $mul_PV=0.8;
    $mul_PA=0.2*(1-($perso['PA']<$perso['PA_critique']?0.1*$perso['imp_endu']:0));
  }
  else if($perso['type_armure']==2){
    $mul_PV=0.5;
    $mul_PA=0.5*(1-($perso['PA']<$perso['PA_critique']?min(1,0.1*$perso['imp_resist2']+0.05*$perso['imp_endu']+0.05*$perso['imp_force']):0));
  }
  else{
    $mul_PV=0.2;
    $mul_PA=0.8*(1-($perso['PA']<$perso['PA_critique']?0.05*$perso['imp_resist4']:0));
  }
  $plop=0;
  if($perso['PA']<$perso['PA_critique']){
    $mul_PA=$mul_PA*pow(exp(($perso['PA_critique']-$perso['PA'])/$perso['PA_critique']),1.8);
    $plop=1;
  }
  if($perso['PV']<$perso['PV_max']){
    $mul_PV=$mul_PV*pow(exp((($perso['PV_max']-$perso['PV'])/$perso['PV_max'])*(1-0.1*$perso['imp_resist'])),1.8);
    $plop=1;
  }
  $perso['malus_precision']=max(0.5,1-($mul_PA+$mul_PV-1)*0.15);
  $perso['malus_mouvement']=min(5,$mul_PA+$mul_PV);
  //***********************************************************************
  // Calcul des malus de surcharge
  //***********************************************************************
  $perso['poids_total']=$perso['inventaire']['poids']+$perso['poids_arme1']+$perso['poids_arme2'];
  if($perso['poids_total']>$perso['poids_max']){
    $perso['malus_mouvement']*=(1+($perso['poids_total']-$perso['poids_max'])/$perso['poids_max']*2);
  }
  else if($perso['poids_total']<$perso['poids_max']){
    $perso['malus_mouvement']*=(1-($perso['poids_max']-$perso['poids_total'])/$perso['poids_max']*0.1);
  }

  //***********************************************************************
  // Acc�s aux consoles.
  //***********************************************************************
  // Recuperation des droits d'admin

  $perso['admin']=recupAdmin();
  $perso['console_anims']=$perso['admin']['anim_munitions']||$perso['admin']['anim_armes']||$perso['admin']['anim_armures']||$perso['admin']['anim_missions']||$perso['admin']['anim_gadgets']||$perso['admin']['anim_camps']||$perso['admin']['anim_cartes']||$perso['admin']['anim_qgs']||$perso['admin']['anim_news']||$perso['admin']['anim_base']||$perso['admin']['anim_droits']||$perso['admin']['anim_gene']||$perso['admin']['anim_pnjs']||$perso['admin']['anim_terrains']||$perso['admin']['anim_grades']||$perso['admin']['anim_noms']||$perso['admin']['anim_transfuge']||$perso['admin']['anim_derog']||$perso['admin']['anim_teleport'];


  $perso['console_gene']=$perso['gene_ordres']||$perso['gene_compas']||$perso['gene_trt']||$perso['gene_medailles']||$perso['gene_ngene']||$perso['gene_droits']||$perso['gene_cartographie']||$perso['gene_transfuge']||$perso['gene_qgs']||$perso['gene_strat'];

}
?>