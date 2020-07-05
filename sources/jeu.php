<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*
Calcul des chances de camouflage.
*/
$camouMouv=chances_camou(0);
$camouTir=chances_camou($perso['malus_camou_arme'.$slot]);

if($perso['map']){
	include('../sources/map.php');
	// Recuperation du terrain.
	$map = getMap($perso['map'],
		$perso['X'],
		$perso['Y'],
		$perso['vision'],
		$perso['type_armure'],
		$perso['armee']);

	// Recuperation des QGs.
	$map = setQGs($map,
		$perso['map'],
		$perso['X'],
		$perso['Y'],
		$perso['vision'],
		$perso['armee'],
		$_SESSION['affichage']['qgbloc'],
		$_SESSION['affichage']['qgutil']);
	// Le matos.
	$req=request('SELECT equipement.ID AS equipement,
		equipement.dropped AS dropped,
		equipement.nombre,
		equipement.objet_ID,
		equipement.type AS type_equipement,
		armes.nom AS arme_nom, 
		munars.nom AS munar_nom,
		objets.nom AS objet_nom,
		objets.visibilite,
		equipement.X,
		equipement.Y
		FROM `equipement`
		LEFT OUTER JOIN `armes`
		ON armes.ID=equipement.objet_ID
		AND equipement.type=1
		LEFT OUTER JOIN `munars`
		ON munars.ID=equipement.objet_ID
		AND equipement.type=2
		LEFT OUTER JOIN `objets`
		ON objets.ID=equipement.objet_ID
		AND equipement.type=3
		WHERE equipement.map='.$perso['map'].'
		AND equipement.X<='.($perso['X']+$perso['vision_reelle']).'
		AND equipement.X>='.($perso['X']-$perso['vision_reelle']).'
		AND equipement.Y<='.($perso['Y']+$perso['vision_reelle']).'
		AND equipement.Y>='.($perso['Y']-$perso['vision_reelle']));
	while($cases=mysql_fetch_array($req)){
		if($cases['dropped']+$GLOBALS['equipement_rouille']<$time &&
		$cases['type_equipement']!=3){
			// Le matos est la depuis trop longtemps, on le degage.
			request('DELETE FROM equipement WHERE ID='.$cases['equipement']);
		}
		else{
			$X=$cases['X']-$perso['X'];
			$Y=$cases['Y']-$perso['Y'];
			if($cases['type_equipement'] == 3 && sqrt($X*$X+$Y*$Y) <= $cases['visibilite'] ||
				sqrt($X*$X+$Y*$Y)<=round(0.2*$perso['vision_reelle']) ||
			(abs($X)<=1 && abs($Y)<=1)){
				$map[$Y][$X]['special'][0]++;
				$map[$Y][$X]['special'][]=array('type'=>$cases['type_equipement'],
					'nom'=>$cases['type_equipement']==1?$cases['arme_nom']:($cases['type_equipement']==2?$cases['munar_nom']:$cases['objet_nom']),
					'objet_ID'=>$cases['objet_ID'],
					'ID'=>$cases['equipement'],
					'nombre'=>$cases['nombre']);
			}
		}
	}
	mysql_free_result($req);

	// Recuperation des persos.
	$req=request('SELECT persos.X,
		persos.Y,
		persos.armee,
		persos.arme,
		compagnies.ID AS compa_ID, 
		compagnies.initiales AS compa,
		arme2.ID AS ID_arme1,
		arme2.nom AS nom_arme1,
		arme3.ID AS ID_arme2, 
		arme3.nom AS nom_arme2, 
		persos.grade_reel,
		persos.grade,
		persos.camouflage,
		persos.date_last_tir,
		persos.malus_camou_tir,
		persos.camou,
		persos.compte,
		persos.message,
		persos.nom,
		persos.ID,
		persos.map, 
		persos.PV,
		persos.peine_hopital, 
		(persos.PV_max+2*persos.imp_PV) AS PV_max,
		persos.VS,
		persos.PA, 
		persos.confiance,
		persos.date_last_regen,
		persos.date_lost_PV,
		persos.date_last_mouv, 
		persos.date_last_update, 
		persos.imp_regen,
		persos.imp_endu,
		persos.plaine,
		persos.foret,
		persos.montagne,
		persos.desert,
		persos.marais,
		persos.nage,
		persos.pont,
		persos.peine_TRT,
		persos.peine_debutTRT,
		persos.peine_fin, 
		grades.ID AS grade_ID,
		grades.nom AS nom_grade,
		grades.niveau AS niveau_grade,
		armures.type AS type_armure,
		armures.bonus_precision,
		armures.malus_critique,
		armures.malus_camou AS armure_camou,
		armures.PA AS PA_max
		FROM `persos`
		LEFT OUTER JOIN `compagnies`
		ON persos.compagnie=compagnies.ID
		INNER JOIN `armures`
		ON persos.armure=armures.ID
		LEFT OUTER JOIN `grades`
		ON persos.grade=grades.ID
		INNER JOIN `armes` AS arme2
		ON (persos.matos_1=arme2.ID)
		INNER JOIN `armes` AS arme3
		ON (persos.matos_2=arme3.ID)
		WHERE persos.map='.$perso['map'].'
		AND persos.X<='.($perso['X']+$perso['vision']).'
		AND persos.X>='.($perso['X']-$perso['vision']).'
		AND persos.Y>='.($perso['Y']-$perso['vision']).'
		AND persos.Y<='.($perso['Y']+$perso['vision']));
	while($cases=mysql_fetch_array($req)){
		$X=$cases['X']-$perso['X'];
		$Y=$cases['Y']-$perso['Y'];
		if(sqrt($X*$X+$Y*$Y)<=$perso['vision']){
			if($cases['ID']!=$_SESSION['com_perso']){
				$doa=regen_PV($cases,$map[$Y][$X]['malus_regen']*comp($map[$Y][$X]['comp'],'regen'),$map[$Y][$X]['debut_perte']);
			}
			if($cases['ID']==$_SESSION['com_perso'] || $doa[2]!=-1){
				$map[$Y][$X]['type_case']=($X||$Y)?'qqun':'moi';
				$map[$Y][$X]['mat']=$cases['ID'];
				$map[$Y][$X]['precision']=$cases['bonus_precision'];
				$map[$Y][$X]['critique']=$cases['malus_critique'];
				$map[$Y][$X]['PA']=$cases['PA'];
				$map[$Y][$X]['PA_max']=$cases['PA_max'];
				$map[$Y][$X]['camp']=$cases['armee'];
				$map[$Y][$X]['compte']=$cases['compte'];
				$map[$Y][$X]['compa']=bdd2html($cases['compa']);
				$map[$Y][$X]['compa_ID']=$cases['compa_ID'];
				$map[$Y][$X]['grade_reel']=bdd2html($cases['grade_reel']);
				$map[$Y][$X]['grade']=bdd2html($cases['nom_grade']);
				$map[$Y][$X]['nbr_grade']=$cases['grade_ID'];
				$map[$Y][$X]['niveau_grade']=$cases['niveau_grade'];
				$map[$Y][$X]['lvl_camou']=$cases['camouflage'];
				$map[$Y][$X]['armure']=$cases['type_armure'];
				$map[$Y][$X]['arme']=$cases['ID_arme'.$cases['arme']];
				$map[$Y][$X]['nom_arme']=bdd2html($cases['nom_arme'.$cases['arme']]);
				$map[$Y][$X]['arme2']=$cases['ID_arme'.($cases['arme']==1?2:1)];
				$map[$Y][$X]['arme_secours']=bdd2html($cases['nom_arme'.($cases['arme']==1?2:1)]);
				$map[$Y][$X]['nom']=bdd2html($cases['nom']);
				$map[$Y][$X]['camoufled']=($cases['camouflage']&&$cases['camouflage']<=5?1:0);
				$map[$Y][$X]['message']=$cases['message'];
				$map[$Y][$X]['bloque_vue']=0;
				$map[$Y][$X]['visible']=1;
				$map[$Y][$X]['praticable']=2;
				$map[$Y][$X]['TRT']=($cases['armee']==$perso['armee']&&$cases['peine_TRT']&&$cases['peine_debutTRT']>$time&&$cases['peine_fin']<$time);
				$req2 = request('SELECT objets.ID, objets.nom FROM 
					equipement
					INNER JOIN objets
					ON objets.ID = equipement.objet_ID
					AND equipement.type = 3
					WHERE objets.visible = 1
					AND equipement.possesseur = '.$cases['ID']);
				$map[$Y][$X]['objets'] = array();
				while($objet=mysql_fetch_array($req2)){
					if(!empty($map[$Y][$X]['objets'][$objet['ID']])){
						$map[$Y][$X]['objets'][$objet['ID']]['qty']++;
					}
					else{
						$map[$Y][$X]['objets'][$objet['ID']] = array('qty'=>1,'nom'=>$objet['nom']);
					}
				}
				mysql_free_result($req2);
			}
		}
	}
	mysql_free_result($req);

	// recuperation des mines visibles
	$req=request('SELECT mines.ID,
		mines.X,
		mines.Y,
		minesmarquees.camp
		FROM mines
		LEFT JOIN minesmarquees
		ON minesmarquees.ID = mines.ID
		WHERE (mines.poseur='.$_SESSION['com_perso'].'
		OR minesmarquees.perso='.$_SESSION['com_perso'].'
		OR minesmarquees.camp='.$perso['armee'].')
		AND mines.map='.$perso['map'].'
		AND mines.X<='.($perso['X']+$perso['vision']).'
		AND mines.X>='.($perso['X']-$perso['vision']).'
		AND mines.Y>='.($perso['Y']-$perso['vision']).'
		AND mines.Y<='.($perso['Y']+$perso['vision']));
	while($cases=mysql_fetch_array($req)){
		$X=$cases['X']-$perso['X'];
		$Y=$cases['Y']-$perso['Y'];
		if(sqrt($X*$X+$Y*$Y)<=$perso['vision']){
			$map[$Y][$X]['mines'][]=array('ID'=>$cases['ID'],
				'camp'=>$cases['camp']);
		}
	}
	// Maintenant, preparation des fleches de deplacement.
	pad(-1,-1,'NO','&#8598;');
	pad(0,-1,'N','&#8593;');
	pad(1,-1,'NE','&#8599;');
	pad(-1,0,'O','&#8592;');
	pad(1,0,'E','&#8594;');
	pad(-1,1,'SO','&#8601;');
	pad(0,1,'S','&#8595;');
	pad(1,1,'SE','&#8600;');
	// On affiche maintenant.
	$cibles='';
	$divsInfos='';
	$tableDim=52*(2*$perso['vision']+1);
	if($_SESSION['affichage']['lite']){
		$tableDim=50*(2*$perso['vision']+1);
	}
	echo' <table style="width:'.$tableDim.'px;height:'.$tableDim.'px;" id="vue"'.($_SESSION['affichage']['lite']?' class="lite"':'').'> 
		';
	$last_caseY=$perso['vision']+1;
	// Tableau stockant les cases a updater dans la carto.
	$toUpdateCarto = array();
	for($Y=-$perso['vision'];$Y<=$perso['vision'];$Y++){
		$daY=($perso['coordonnees']?$perso['Y']:0)+$Y;
		echo'  <tr>
			';
		for($X=-$perso['vision'];$X<=$perso['vision'];$X++){
			$daX=($perso['coordonnees']?$perso['X']:0)+$X;
			//*****************************************************************
			// Si on a un LR, on vérifie si cette case est ciblable
			//*****************************************************************
			//*****************************************************************
			// Si la case est visible, on va l'afficher.
			//*****************************************************************
			if(isset($map[$Y][$X]['cout_vision_total']) && $map[$Y][$X]['cout_vision_total']<=$perso['vision'] || isset($map[$Y][$X]['type_case']) && $map[$Y][$X]['type_case']=='QG'){
				if($map[$Y][$X]['carto'] == 0 && $perso['cartographier'] > 0 ||
				$map[$Y][$X]['carto'] == 2 && $perso['cartographier'] == 1){
					// case a mettre a jour au niveau de la cartographie
					$toUpdateCarto[]=array($X+$perso['X'],$Y+$perso['Y']);
				}

				$content='';
				// Pour voir les lignes de niveau.
				if(isset($map[$Y-1][$X]) &&
					$map[$Y-1][$X]['type_case']!='vide' &&
				$map[$Y][$X]['z_sol'] !=  $map[$Y-1][$X]['z_sol']){
					$map[$Y][$X]['class'][]='bordhautepais';
					if($_SESSION['affichage']['hauteur']){
						$map[$Y][$X]['infos']['hauteur']=$map[$Y][$X]['z_sol'];
					}
				}
				if(isset($map[$Y+1][$X]) &&
					$map[$Y+1][$X]['type_case']!='vide' &&
				$map[$Y][$X]['z_sol'] !=  $map[$Y+1][$X]['z_sol']){
					$map[$Y][$X]['class'][]='bordbasepais';
					if($_SESSION['affichage']['hauteur']){
						$map[$Y][$X]['infos']['hauteur']=$map[$Y][$X]['z_sol'];
					}
				}
				if(isset($map[$Y][$X+1]) &&
					$map[$Y][$X+1]['type_case']!='vide' &&
				$map[$Y][$X]['z_sol'] !=  $map[$Y][$X+1]['z_sol']){
					$map[$Y][$X]['class'][]='borddroitepais';
					if($_SESSION['affichage']['hauteur']){
						$map[$Y][$X]['infos']['hauteur']=$map[$Y][$X]['z_sol'];
					}
				}
				if(isset($map[$Y][$X-1]) &&
					$map[$Y][$X-1]['type_case']!='vide' &&
				$map[$Y][$X]['z_sol'] !=  $map[$Y][$X-1]['z_sol']){
					$map[$Y][$X]['class'][]='bordgaucheepais';
					if($_SESSION['affichage']['hauteur']){
						$map[$Y][$X]['infos']['hauteur']=$map[$Y][$X]['z_sol'];
					}
				}
				if($_SESSION['affichage']['portee']){
					// Pour voir la limite de portee de votre arme.
					if(!($X==0 && $Y==0)){
						if(isset($map[$Y][$X+1]) &&
							$map[$Y][$X+1]['type_case']!='vide' &&
							($map[$Y][$X]['cout_portee_total']>$perso['portee_arme'.$slot] &&
							$map[$Y][$X+1]['cout_portee_total']<=$perso['portee_arme'.$slot] &&
							$map[$Y][$X+1]['cout_vision_total']<=$perso['vision'] ||
							$map[$Y][$X]['cout_portee_total']<=$perso['portee_arme'.$slot] &&
							($map[$Y][$X+1]['cout_portee_total']>$perso['portee_arme'.$slot] ||
							$map[$Y][$X+1]['cout_vision_total']>$perso['vision']) &&
						!($Y==0 && $X==-1))){
							$map[$Y][$X]['class'][]='porteedroite';
						}
						if(isset($map[$Y][$X-1]) &&
							$map[$Y][$X-1]['type_case'] != 'vide' &&
							($map[$Y][$X]['cout_portee_total']>$perso['portee_arme'.$slot] &&
							$map[$Y][$X-1]['cout_portee_total']<=$perso['portee_arme'.$slot] &&
							$map[$Y][$X-1]['cout_vision_total']<=$perso['vision'] ||
							$map[$Y][$X]['cout_portee_total']<=$perso['portee_arme'.$slot] &&
							($map[$Y][$X-1]['cout_portee_total']>$perso['portee_arme'.$slot] ||
							$map[$Y][$X-1]['cout_vision_total']>$perso['vision']) &&
						!($Y==0 && $X==1))){
							$map[$Y][$X]['class'][]='porteegauche';
						}
						if(isset($map[$Y-1][$X]) &&
							$map[$Y-1][$X]['type_case'] != 'vide' &&
							($map[$Y][$X]['cout_portee_total']>$perso['portee_arme'.$slot] &&
							$map[$Y-1][$X]['cout_portee_total']<=$perso['portee_arme'.$slot] &&
							$map[$Y-1][$X]['cout_vision_total']<=$perso['vision'] ||
							$map[$Y][$X]['cout_portee_total']<=$perso['portee_arme'.$slot] &&
							($map[$Y-1][$X]['cout_portee_total']>$perso['portee_arme'.$slot] ||
							$map[$Y-1][$X]['cout_vision_total']>$perso['vision']) &&
						!($X==0 && $Y==1))){
							$map[$Y][$X]['class'][]='porteehaut';
						}
						if(isset($map[$Y+1][$X]) &&
							$map[$Y+1][$X]['type_case'] != 'vide' &&
							($map[$Y][$X]['cout_portee_total']>$perso['portee_arme'.$slot] &&
							$map[$Y+1][$X]['cout_portee_total']<=$perso['portee_arme'.$slot] &&
							$map[$Y+1][$X]['cout_vision_total']<=$perso['vision'] ||
							$map[$Y][$X]['cout_portee_total']<=$perso['portee_arme'.$slot] &&
							($map[$Y+1][$X]['cout_portee_total']>$perso['portee_arme'.$slot] ||
							$map[$Y+1][$X]['cout_vision_total']>$perso['vision']) &&
						!($X==0 && $Y==-1))){
							$map[$Y][$X]['class'][]='porteebas';
						}
					}
				}
				// Presence de mines.
				if(!empty($map[$Y][$X]['mines'])){
					$map[$Y][$X]['infos']['mine']=1;
				}
				switch($map[$Y][$X]['type_case']){
					//***********************************************************
					// Un joueur
					//***********************************************************
					case'moi':
					case 'qqun':
					// Si c'est un perso, on voit s'il est camouflé.
					if($map[$Y][$X]['type_case']!='moi' &&
						($map[$Y][$X]['camoufled'] &&
						$map[$Y][$X]['compte'] != $_SESSION['com_ID'] &&
					ceil($perso['vision']*comp('ecl',$map[$Y][$X]['lvl_camou']-1))<$map[$Y][$X]['cout_vision_total'])){
						$map[$Y][$X]['visible']=0; 
					}
					if($map[$Y][$X]['visible']){
						affiche_perso();
					}
					else{
						affiche_terrain();
					}
					break;
					//***********************************************************
					// Un QG
					//***********************************************************
					case 'QG':
					$over='';
					// a bouger dans affiche_QG
					affiche_QG();
					break;
					//***********************************************************
					// Une case vide.
					//***********************************************************
					case 'vide':
					echo'   <td class="vide',$laclasse,'">&nbsp;</td>
						';
					break;
					//***********************************************************
					// Un terrain
					//***********************************************************
					default:
					affiche_terrain();
				}
			}
			else if(!empty($map[$Y][$X]['bouge'])){
				// Case vide vers laquelle on peut se deplacer.
				$divsInfos.='<div id="infos'.$X.'_'.$Y.'" class="infos">
					Cout en temps : '.round($map[$Y][$X]['bouge']['prix']).(round($map[$Y][$X]['bouge']['prix'])==round($perso['PM'])&&$map[$Y][$X]['bouge']['prix']>$perso['PM']?'+':'').' / '.round($perso['PM']).'<br />
					';
				if(isset($map[$Y][$X]['bouge']['chancesInfi'])){
					$divsInfos.='Chances de s\'infiltrer : '.$map[$Y][$X]['bouge']['chancesInfi'].'%<br />
						';
				}
				$divsInfos.=' </div>
					';
				echo'   <td class="vide" id="'.$X.'_'.$Y.'"><form method="post" action="jouer.php">
					<input type="'.($map[$Y][$X]['bouge']['class']=='no'?'button':'submit').'" name="bouge_'.$map[$Y][$X]['bouge']['nom'].'" id="bouge_'.$map[$Y][$X]['bouge']['nom'].'" value="'.$map[$Y][$X]['bouge']['value'].'" class="'.($map[$Y][$X]['bouge']['class']=='no'?'no':($map[$Y][$X]['bouge']['infi']?'infi':'ok')).'" />
					</form>
					</td>
					';
			}
			else{
				echo'   <td class="vide">&nbsp;</td>
					';
			}
		}
		echo'  </tr>
			'; 
	}
	echo' </table>
		'.$divsInfos;
}

// Update de la carto si besoin
if(!empty($toUpdateCarto)){
	/* On met a jour l'image de carto */
	// Verification du lock
	if(!file_exists('../cartes/'.$perso['map'].'_'.$perso['armee'].'.lock')){
		file_put_contents('../cartes/'.$perso['map'].'_'.$perso['armee'].'.lock','.'); // On pose le lock
		$im = ImageCreateFromPng('../cartes/carto_'.$perso['map'].'_'.$perso['armee'].'.png');
		$im2 = ImageCreateFromPng('../cartes/'.$perso['map'].'.png');

		$which = '';
		foreach($toUpdateCarto AS $coords){
			if(!empty($which)){
				$which .= ' OR ';
			}
			$which .= 'X='.$coords[0].' AND Y='.$coords[1];
			imagecopy($im, $im2, $coords[0], $coords[1], $coords[0], $coords[1], 1, 1);
		}
		if(imagepng($im,'../cartes/carto_'.$perso['map'].'_'.$perso['armee'].'.png')){
			request('UPDATE carte_'.$perso['map'].' SET carto_'.$perso['armee'].'='.$perso['cartographier'].' WHERE '.$which); 
		}
		unlink('../cartes/'.$perso['map'].'_'.$perso['armee'].'.lock');
	}
}


//*************************************************************************
// Si on a de quoi tirer, on affiche le formulaire de tir.
//*************************************************************************

/*if($cibles && ($perso['munars_arme'.$slot]>0 || $perso['cadence_arme'.$slot]==0) && $perso['tirs_restants_arme'.$slot]>=1){
echo'<form method="post" action="jouer.php#comlink">*/
/*<div id="form_attaque">
<input type="hidden" name="tireur" value="'.$_SESSION['com_perso'].'" />
';
}
*/

echo'<div id="comlink"'.(isset($_COOKIE['widthCL']) && $_COOKIE['widthCL']!='closed'?' style="width:'.$_COOKIE['widthCL'].'px;height:'.$_COOKIE['heightCL'].'px"':'').'>
	<p class="titleBar"><a href="#" id="resizeCL">Redimensionner</a><a href="#" id="closeCL" class="close">Fermer</a><span id="titleCL">'.(isset($_COOKIE['titleCL'])?$_COOKIE['titleCL']:'Carte').'</span></p>
<iframe name="framelink" '.(isset($_COOKIE['hrefCL'])?'src="'.$_COOKIE['hrefCL'].'"':'src="carte.php"').' id="framelink">
	</iframe>
	</div>
	</div>
	';
//*************************************************************************
// Ajout du necessaire au javascript.
//*************************************************************************
echo'<iframe id="action" name="action"></iframe>
	<script type="text/javascript">
	var PM='.$perso['PM'].';
';
if(!empty($cibles) &&
	($perso['munars_arme'.$slot] || !$perso['cadence_arme'.$slot]) &&
$perso['tirs_restants_arme'.$slot]>=1){
	// Nom de l'action mise sur le bouton de "tir".
	$value='attaquer';
	if($perso['type_arme'.$slot]==5)
	{
		$value='réparer';
	}
	else if($perso['type_arme'.$slot]==8)
	{
		$value='soigner';
	}
	echo'var tirName="'.$value.'";
	';

	// Valeurs necessaires a la gestion de la concentration.
	echo'var preciMax='.$perso['precision_max_arme'.$slot].';
	var preciMin='.$perso['precision_min_arme'.$slot].';
	nbTirs='.$perso['nbr_tirs_arme'.$slot].'; 
	var seuilCrit='.$perso['seuil_critique_arme'.$slot].';
	var prixCrit='.$perso['pm_critique_arme'.$slot].';
	var malus_precision='.$perso['malus_precision'].';
	var type_arme='.($perso['type_arme'.$slot]==3?'1':($perso['type_arme'.$slot]==4?'2':'0')).';
	var Cibles=new Object();
	var camouflage='.$camouTir.';
	';
	// Listage des cibles possibles.
	foreach($cibles AS $cible)
	{
		echo'Cibles["'.$cible['X'].'_'.$cible['Y'].'"]=new Object();
		Cibles["'.$cible['X'].'_'.$cible['Y'].'"]["precision"]='.$cible['precision'].';
		Cibles["'.$cible['X'].'_'.$cible['Y'].'"]["critique"]='.$cible['crit'].';
		Cibles["'.$cible['X'].'_'.$cible['Y'].'"]["mat"]='.$cible['mat'].';
		';
	}
}

echo'</script>
	';

unset($map);

//***************************************************************************
// Fonction qui permet de savoir où afficher les flèches de déplacement.
//***************************************************************************

function pad($pas_X,$pas_Y,$dir,$value){
	global $map,$perso,$camouMouv;
	$X=0;
	$Y=0;
	$prix=0;
	$infi_cout=comp('infi','cout');

	$diag=($pas_X != 0 && $pas_Y != 0);
	$prix=calcMouv(array($map[$Y][$X],
		empty($map[$Y+$pas_Y][$X+$pas_X])?0:$map[$Y+$pas_Y][$X+$pas_X],
		empty($map[$Y+2*$pas_Y][$X+2*$pas_X])?0:$map[$Y+2*$pas_Y][$X+2*$pas_X],
		empty($map[$Y+3*$pas_Y][$X+3*$pas_X])?0:$map[$Y+3*$pas_Y][$X+3*$pas_X]),
		$diag);
	if($prix[0] > 0){
		$map[$Y+($prix[1]+1)*$pas_Y]
			[$X+($prix[1]+1)*$pas_X]
			['bouge']=array('nom'=>$dir,
			'prix'=>$prix[0],
			'infi'=>$prix[1],
			'class'=>$prix[0]>$perso['PM']?'no':($prix[1]?'infi':'bouge'),
			'value'=>$value);
		if($prix[1]>0){
			$map[$Y+($prix[1]+1)*$pas_Y]
				[$X+($prix[1]+1)*$pas_X]
				['bouge']['chancesInfi']=$GLOBALS['competences']['infi'][$perso['infi']]['reussite'][$prix[1]];
		}
		$map[$Y+($prix[1]+1)*$pas_Y][$X+($prix[1]+1)*$pas_X]['bouge']['chancesCamou']=$camouMouv;
	}
}



function affiche_terrain()
{
	$over='';
	global $map,$daX,$daY,$Y,$X,$perso,$cases_pontables,$slot,$divsInfos,$cibles;
	// Classe de la case.
	$class=$map[$Y][$X]['style'];
	if(!empty($map[$Y][$X]['class'])){
		foreach($map[$Y][$X]['class'] AS $sup){
			$class.=' '.$sup;
		}
	}

	// Preparation de la bulle d'infos.
	$div='';
	if(isset($map[$Y][$X]['bouge'])){
		// On peut aller vers cette case.
		$div.='<p>Aller en '.$daX.' / '.$daY.'</p>
			<p>Cout en temps : '.round($map[$Y][$X]['bouge']['prix']).(round($map[$Y][$X]['bouge']['prix'])==round($perso['PM'])&&$map[$Y][$X]['bouge']['prix']>$perso['PM']?'+':'').' / '.round($perso['PM']).'<br />
			';
		if(isset($map[$Y][$X]['bouge']['chancesInfi'])){
			$div.='Chances de s\'infiltrer : '.$map[$Y][$X]['bouge']['chancesInfi'].'%<br />
				';
		}
		if(isset($map[$Y][$X]['bouge']['chancesCamou']) &&
		$perso['camouflage']>0){
			$div.='Camouflage : '.$map[$Y][$X]['bouge']['chancesCamou'].'%<br />
				';
		}
		$div.='</p>';
	}
	if($map[$Y][$X]['special'][0] && 
	$map[$Y][$X]['cout_vision_total']<=$perso['vision']){
		// Il y a du matos dessus
		$armes='';
		$munars='';
		$autres='';
		$imgObjets = array();
		for($k=1;$k<=$map[$Y][$X]['special'][0];$k++){
			if($map[$Y][$X]['special'][$k]['type']==1){
				// C'est une arme.
				$armes.='<li><img src="images/armes/na'.$map[$Y][$X]['special'][$k]['objet_ID'].'.gif" /> '.bdd2html($map[$Y][$X]['special'][$k]['nom']).'</li>';
			}
			else if($map[$Y][$X]['special'][$k]['type']==2){
				// Ce sont des munitions.
				$munars.='<li>'.bdd2html($map[$Y][$X]['special'][$k]['nom']).' (nombre : '.$map[$Y][$X]['special'][$k]['nombre'].')</li>
					';
			}
			else if($map[$Y][$X]['special'][$k]['type']==3){
				// Ce sont des objets
				$autres.='<li><img src="images/objets/'.$map[$Y][$X]['special'][$k]['objet_ID'].'.gif" /> '.bdd2html($map[$Y][$X]['special'][$k]['nom']).'</li>
					';
				$imgObjets[$map[$Y][$X]['special'][$k]['objet_ID']] = 1;
			}
		}
		$div.=($armes?'  <h3>Armes :</h3>
			<ul>'.$armes.'</ul>
			':'').($munars?' <h3>Munitions :</h3>
			<ul>'.$munars.'</ul>
			':'').($autres?' <h3>Autres :</h3>
			<ul>'.$autres.'</ul>
			':'');
		if($armes || $munars || $autres){
			$map[$Y][$X]['infos']['matos']=1;
		}
	}
	if($perso['type_arme'.$slot]==4
		&& $map[$Y][$X]['cout_portee_total']<=$perso['portee_arme'.$slot]
		&&($perso['munars_arme'.$slot]>0 || $perso['cadence_arme'.$slot]==0)
	&& $perso['tirs_restants_arme'.$slot]>=1){
		// On a un lance roquetes donc on peut tirer dessus
		$cibles = array();
		$cibles[]=array('mat'=>0,
			'precision'=>$map[$Y][$X]['precision']-$map[$Y][$X]['perte_precision_total']+$perso['precision_min_arme'.$slot],
			'crit'=>0,
			'X'=>$X,
			'Y'=>$Y);
		if(empty($div)){
			$div.='<p>Tirer en X='.$daX.', Y='.$daY.'</p>';
		}
	}

	if($perso['type_arme'.$slot]==3 &&
		$map[$Y][$X]['cout_portee_total']<=$perso['portee_arme'.$slot] &&
		($perso['munars_arme'.$slot]>0 ||
		$perso['cadence_arme'.$slot]==0) &&
	$perso['tirs_restants_arme'.$slot]>=1){
		// Case pouvant peut etre ciblable au Lance flammes.
		if($X<0 && $Y>0){
			$direction='NO';
		}
		if($X==0 && $Y>0){
			$direction='N';
		}
		if($X>0 && $Y>0){
			$direction='NE';
		}
		if($X<0 && $Y==0){
			$direction='O';
		}
		if($X==0 && $Y==0){
			$direction='C';
		}
		if($X>0 && $Y==0){
			$direction='E';
		}
		if($X<0 && $Y<0){
			$direction='SO';
		}
		if($X==0 && $Y<0){
			$direction='S';
		}
		if($X>0 && $Y<0){
			$direction='SE';
		}
		$cibles[]=array('mat'=>'"'.$direction.'"',
			'precision'=>$map[$Y][$X]['precision']-$map[$Y][$X]['perte_precision_total']+$perso['precision_min_arme'.$slot],
			'crit'=>0,
			'X'=>$X,
			'Y'=>$Y);
		if(empty($div)){
			$div.='<p>Tirer vers X='.$daX.', Y='.$daY.'</p>';
		}
	}
	if(!empty($div)){
		$divsInfos.=' <div id="infos'.$X.'_'.$Y.'" class="infos">
			'.$div.'</div>
			';
	}
	// affichage de la case
	echo'   <td class="',$class,'"'.(empty($div)?'':' id="'.$X.'_'.$Y.'"').'>';
	if(isset($map[$Y][$X]['bouge'])){
		echo'<p class="'.$map[$Y][$X]['bouge']['class'].'"><a href="'.($map[$Y][$X]['bouge']['class']=='no'?'#':'jouer.php?bouge='.$map[$Y][$X]['bouge']['nom']).'" class="'.$map[$Y][$X]['bouge']['nom'].'">'.$map[$Y][$X]['bouge']['value'].'</a></p>';
		/*'<form method="post" action="jouer.php">
		<input type="'.($map[$Y][$X]['bouge']['class']=='no'?'button':'submit').'" name="bouge_'.$map[$Y][$X]['bouge']['nom'].'" id="bouge_'.$map[$Y][$X]['bouge']['nom'].'" value="'.$map[$Y][$X]['bouge']['value'].'" class="'.($map[$Y][$X]['bouge']['class']=='no'?'no':($map[$Y][$X]['bouge']['infi']?'infi':'ok')).'" />
		</form>
		';*/
}
if(!empty($map[$Y][$X]['infos']['mine'])){
	echo'<p class="mine">Mine</p>';
}
if(!empty($map[$Y][$X]['infos']['matos'])){
	if(!empty($imgObjets)){
		foreach($imgObjets AS $id=>$unused){
			echo '<p><img src="images/objets/'.$id.'.gif" /></p>';
		}
	}
	else{
		echo'<p class="matos">Butin</p>';
	}
}
if(!empty($map[$Y][$X]['infos']['hauteur'])){
	echo'<p class="hauteur">'.$map[$Y][$X]['infos']['hauteur'].'</p>';
}
echo'</td>';
}

function affiche_perso()
{
	global $map,$cibles,$script,$lescript,$divsInfos,$onclick,$Y,$X,$daX,$daY,$perso,$slot,$time,$camouMouv;
	// Peut on lui tirer dessus ?
	if($map[$Y][$X]['type_case']!='moi' &&
		$map[$Y][$X]['cout_portee_total']<=$perso['portee_arme'.$slot] &&
		($perso['munars_arme'.$slot]>0 ||
		!$perso['cadence_arme'.$slot]) &&
	$perso['tirs_restants_arme'.$slot]>=1){
		$mat=$map[$Y][$X]['mat'];
		if($perso['type_arme'.$slot]==3){
			if($X<0 && $Y>0){
				$mat='"NO"';
			}
			if($X==0 && $Y>0){
				$mat='"N"';
			}
			if($X>0 && $Y>0){
				$mat='"NE"';
			}
			if($X<0 && $Y==0){
				$mat='"O"';
			}
			if($X==0 && $Y==0){
				$mat='"C"';
			}
			if($X>0 && $Y==0){
				$mat='"E"';
			}
			if($X<0 && $Y<0){
				$mat='"SO"';
			}
			if($X==0 && $Y<0){
				$mat='"S"';
			}
			if($X>0 && $Y<0){
				$mat='"SE"';
			}
		}
		// En fonction de l'etat de l'armure, elle donne plus ou moins de bonus de precision
		if($perso['type_arme'.$slot]!= 5 &&
		$perso['type_arme'.$slot]!= 8){
			$map[$Y][$X]['precision']=$map[$Y][$X]['precision']*max(0,ceil($map[$Y][$X]['PA']*4/$map[$Y][$X]['PA_max']))/4;
		}
		$cibles = array();
		$cibles[]=array('mat'=>$mat,
			'precision'=>$map[$Y][$X]['precision']-$map[$Y][$X]['perte_precision_total']+$perso['precision_min_arme'.$slot],
			'crit'=>$perso['critique_arme'.$slot]-$map[$Y][$X]['critique'],
			'X'=>$X,
			'Y'=>$Y);
	}

	$stat_armure='<img src="styles/'.$_SESSION['skin'].'/img/inconnu.gif" alt="?" title="&Eacute;tat de l\'armure : inconnu" />';
	if($map[$Y][$X]['cout_vision_total']<=$perso['vision_armure'] || 
	$map[$Y][$X]['cout_vision_total']<=sqrt(2)){
		$plop=25*max(0,ceil($map[$Y][$X]['PA']*4/$map[$Y][$X]['PA_max']));
		$stat_armure='<img src="styles/'.$_SESSION['skin'].'/img/arm_'.$plop.'.gif" alt="'.$plop.'%" title="&Eacute;tat de l\'armure : '.$plop.'%" />';
	}

	$divsInfos.='<div id="infos'.$X.'_'.$Y.'" class="infos">
		<p><a href="fiche.php?id='.$map[$Y][$X]['mat'].'&amp;lite=1" target="framelink">'.bdd2html($map[$Y][$X]['nom']).'</a> ('.camp_initiale($map[$Y][$X]['camp']).'-<a href="compagnies.php?id='.$map[$Y][$X]['compa_ID'].'&amp;lite=1" target="framelink">'.$map[$Y][$X]['compa'].'</a>-'.$map[$Y][$X]['mat'].')</p>
		<h3>Informations</h3>
		<p>'.bdd2html($map[$Y][$X]['grade']?(grade_spec_autre($map[$Y][$X]['camp'],$map[$Y][$X]['nbr_grade'],$map[$Y][$X]['grade']).' ('.numero_camp_grade($map[$Y][$X]['camp'],$map[$Y][$X]['grade_reel']).')'):numero_camp_grade($map[$Y][$X]['camp'],$map[$Y][$X]['grade_reel'])).'<br />
		'.bdd2html(type_nom_armure($map[$Y][$X]['armure'],$map[$Y][$X]['camp'])).' '.$stat_armure.' <a href="arme.php?id='.$map[$Y][$X]['arme'].'" target="framelink">'.bdd2html($map[$Y][$X]['nom_arme']).'</a> / <a href="arme.php?id='.$map[$Y][$X]['arme2'].'" target="framelink">'.bdd2html($map[$Y][$X]['arme_secours']).'</a><br />
		X='.$daX.' / Y='.$daY.($map[$Y][$X]['camoufled']?' Camoufl&eacute;':'').'</p>
		<ul>
		<li><a href="evenements.php?id='.$map[$Y][$X]['mat'].'&amp;lite=1" target="framelink">&Eacute;v&egrave;nements</a></li>
		</ul>
		';
	if(!empty($map[$Y][$X]['objets'])){
		$divsInfos.='<h3>Objets</h3>
			<ul>';
		foreach($map[$Y][$X]['objets'] AS $id=>$data){
			$divsInfos.='<li>'.$data['qty'].'x<img src="images/objets/'.$id.'.gif" /> '.$data['nom'].'</li>';
		}
		$divsInfos.='</ul>';
	}
	if($X == 0 && $Y == 0){
		$divsInfos.='<h3>Actions</h3>
			<form method="post" action="jouer.php" target="_parent">
			<p>
			<input type="submit" name="changeArme" value="Changer d\'arme" />
			</p>
			</form>
			';
		if($perso['sprints']<$perso['sprints_max'] &&
		$perso['PM']<100){
			$divsInfos.='<form method="post" action="jouer.php" target="_parent">
				<p class="sprints">
				<label title="('.floor(min($perso['sprints_max']-$perso['sprints'],100-$perso['PM'])).' maximum)">Sprint : <input type="text" name="sprints" size="3" /></label>
				'.form_submit('sprint_ok','Ok').'
				</p>
				</form>
				';
		}
		if(!$perso['camouflage']){
			$cout=round($_SESSION['com_terrain']['prix_'.$perso['type_armure']] * comp($_SESSION['com_terrain']['competence'],'mouv')*(comp('camou','cout')-1));
			if($cout<=$perso['PM'] && $camouMouv>0){
				// Les chances d'arriver a bien se camoufler sont superieures a 0
				$divsInfos.='<form method="post" action="jouer.php" target="_parent">
					<p>
					'.form_check('','camou_confirm').'
					'.form_submit('camou_ok','Se camoufler').' (Chances de r&eacute;ussite : '.$camouMouv.' %, co&ucirc;t : '.$cout.' PT)
					</p>
					</form>
					';
			}
		}
		else{
			$n='';
			if($perso['camouflage'] == 1){
				$strCamou='parfaitement ';
			}
			if($perso['camouflage'] == 2){
				$strCamou='bien ';
			}
			if($perso['camouflage'] == 3){
				$strCamou='';
			}
			if($perso['camouflage'] == 4){
				$strCamou='mal ';
			}
			if($perso['camouflage'] == 5){
				$strCamou='tr&eacute;s mal ';
			}
			if($perso['camouflage'] == 6){
				$n='n\'';
				$strCamou='pas ';
			}
			$divsInfos.='<form method="post" action="jouer.php" target="_parent">
				<p>Vous '.$n.'&ecirc;tes '.$strCamou.'camoufl&eacute;</p>
				<p>
				'.form_check('','camou_confirm').'
				'.form_submit('camou_stop_ok','Ne plus avancer camoufl&eacute;').'
				</p>
				</form>
				';
		}
		if($perso['PM']>=20){
			$time=temps($GLOBALS['tour']/5);
			if($perso['date_ecl']-time()>0){
				$ecl=temps($perso['date_ecl']-time());
			}
			else{
				$ecl=0;
			}
			$divsInfos.='<form method="post" action="jouer.php" target="_parent">
				<p>
				'.form_check('','ecl_confirm').'
				'.form_submit('ecl_ok','Observer le terrain').'<br />
				</p>
				</form>
				';
		}
		if(($perso['mines_gad1'] > 0 &&
			$perso['munitions_restantes_gad1'] > 0 ||
			$perso['mines_gad2'] > 0 &&
			$perso['munitions_restantes_gad2'] > 0 ||
			$perso['mines_gad3'] > 0 &&
			$perso['munitions_restantes_gad3'] > 0) &&
			$perso['type_armure']==1&&
		$perso['PM']>=10){
			$divsInfos.='<h4>Minage</h4>';
			for($i=1;$i<=3;$i++){
				if($perso['mines_gad'.$i] > 0 &&
				$perso['munitions_restantes_gad'.$i]){
					$divsInfos.=' <form method="post" action="jeuAjax.php" target="action" class="formMine'.$i.'">
						<label for="pos'.$i.'">
						'.bdd2html($perso['nom_gad'.$i]).' 
						<input title="Marquer" type="checkbox" name="pos'.$i.'" id="pos'.$i.'" checked="checked" />
						<input type="submit" name="mine'.$i.'" value="Poser" />
						</label>
						</form>
						';
				}

			}

		}
		if(!empty($map[$Y][$X]['mines'])){
			foreach($map[$Y][$X]['mines'] AS $mine){
				$divsInfos.=' <div class="formDemine'.$mine['ID'].' deminage">
					Mine : ';
				if(!$mine['camp']){
					$divsInfos.='  <form method="post" action="jeuAjax.php" target="action" class="formMark'.$mine['ID'].'">
						<input value="'.$mine['ID'].'" type="hidden" name="mineID" />
						<input value="Marquer" type="submit" name="mark" />
						</form>
						';
				}
				if($perso['type_armure']==1 && $perso['PM']>=20){
					$divsInfos.=' <form method="post" action="jeuAjax.php" target="action" class="formDeminage'.$mine['ID'].'" >
						<input value="'.$mine['ID'].'" type="hidden" name="mineID" />
						<input type="submit" name="demine" title="D&eacute;miner co&ucirc;te 30 PM" value="D&eacute;miner" />
						</form>
						';
				}
				$divsInfos.=' <form method="post" action="jeuAjax.php" target="action">
					<input value="'.$mine['ID'].'" type="hidden" name="mineID" />
					<input type="submit" name="declenchemine" value="D&eacute;clencher" />
					</form>
					</div>';
			}
		}
	}
	else{
		// ITAC - LD - 19-03-2010
		// ITAC - LD - BEGIN
		// http://www.dandoy.fr/mantis/view.php?id=45
		/* 
		$matricule_send_message = $map[$Y][$X]['mat'] + 1;
		$divsInfos.='<h3>Communication</h3>
			<form method="post" action="forum/index.php?action=pm;sa=send;nick=' . $matricule_send_message . '" target="_parent">	<p>   <input type="submit" name="envoyerMessage" value="Envoyer un message" />
			</p></form>'; */
		$matricule_send_message = $map[$Y][$X]['mat'];
		$divsInfos.='<h3>Communication</h3>
			<form method="post" action="forum/index.php?action=pm;sa=send;u=' . $matricule_send_message . '" target="_parent">	<p>   <input type="submit" name="envoyerMessage" value="Envoyer un message" />
			</p></form>';
		// ITAC - LD - END
	}


	$divsInfos.=($map[$Y][$X]['message']?' <h3>Message</h3><div class="mdt">'.filtrage_ordre(bdd2text($map[$Y][$X]['message'])).'</div>':'').'
		</div>
		';

	// Classe de la case.
	$class='camou'.(abs($X)<=1 && abs($Y)<=1?'0':'').$map[$Y][$X]['lvl_camou'].' '.$map[$Y][$X]['style'];
	if(!empty($map[$Y][$X]['class'])){
		foreach($map[$Y][$X]['class'] AS $sup){

			$class.=' '.$sup;
		}
	}
	if($map[$Y][$X]['compte']==$_SESSION['com_ID']){
		$class.=' m';
	}
	else if($map[$Y][$X]['camp']==$perso['armee']){
		$class.=' ca';
	}
	else{
		$class.=' ce';
	}
	echo'   <td id="'.$X.'_'.$Y.'" class="',$class,'">';
	// Smiley
	$smiley='<img src="images/armes/'.$map[$Y][$X]['arme'].'.gif" />';
	switch($_SESSION['affichage']['smiley']){
		case 1:
		$smiley='<img src="images/armes/na'.$map[$Y][$X]['arme'].'.gif" />';
		break;
		case 2:
		$smiley='<p>'.bdd2html($map[$Y][$X]['nom_arme']).' / '.bdd2html($map[$Y][$X]['arme_secours']).'</p>';
		break;
		case 3:
		$smiley='';
		break;
		default:
		break;
	}
	echo $smiley;
	// Matricule
	$tag='';
	if($_SESSION['affichage']['tag'] & 4){
		$tag.=camp_initiale($map[$Y][$X]['camp']);
	}
	if($_SESSION['affichage']['tag'] & 2){
		if($tag!=''){
			$tag.='-';
		}
		$tag.=$map[$Y][$X]['compa'];
	}
	if($_SESSION['affichage']['tag'] & 1){
		if($tag!=''){
			$tag.='-';
		}
		$tag.=$map[$Y][$X]['mat'];
	}
	echo' <p class="g'.$map[$Y][$X]['niveau_grade'].'">',$tag,'</p>
		';
	if(!empty($map[$Y][$X]['infos']['mine'])){
		echo'<p class="mine">Mine</p>';
	}
	if(!empty($map[$Y][$X]['special'])){
		$imgObjets = array();
		for($k=1;$k<=$map[$Y][$X]['special'][0];$k++){
			if($map[$Y][$X]['special'][$k]['type']==3){
				// Ce sont des objets
				$autres.='<li><img src="images/objets/'.$map[$Y][$X]['special'][$k]['objet_ID'].'.gif" /> '.bdd2html($map[$Y][$X]['special'][$k]['nom']).'</li>
					';
				$imgObjets[$map[$Y][$X]['special'][$k]['objet_ID']] = 1;
			}
		}
		if(!empty($imgObjets)){
			foreach($imgObjets AS $id=>$unused){
				echo '<p><img src="images/objets/'.$id.'.gif" /></p>';
			}
		}
	}
	if(!empty($map[$Y][$X]['infos']['hauteur'])){
		echo'<p class="hauteur">'.$map[$Y][$X]['infos']['hauteur'].'</p>';
	}
	echo'</td>';
}

function affiche_QG()
{
	global $map,$Y,$X,$daX,$daY,$perso,$slot,$slot2,$divsInfos,$time;
	
	// ITAC - LD - 2010-03-08
	// ITAC - LD - BEGIN
	// Prise en compte de la visibilite des QG
	// http://www.dandoy.fr/mantis/view.php?id=41
	// add_message(4,'X: ' . abs($X) . ' | Y: ' . abs($Y) . ' | ' . $map[$Y][$X]['QG']['nom'] . ' | visibilite ' . $map[$Y][$X]['QG']['visibilite'] . '.<br />');
	if(abs($X) > $map[$Y][$X]['QG']['visibilite'] 
	|| abs($Y) > $map[$Y][$X]['QG']['visibilite'])
	{
	        affiche_terrain();
		return;
	}
	// ITAC - LD - END
	
	$id='';
	$plus='';
	$options=array('tubage'=>array('gare','Gare',true),
		'armes'=>array('arsenal','Arsenal',false),
		'armures'=>array('hangar','Hangar',true),
		'munitions'=>array('armurerie','Armurerie',false),
		'reparation'=>array('atelier','Atelier',true),
		'regeneration'=>array('hopital','H&ocirc;pital',true),
		'respawn'=>array('aeroport','A&eacute;roport',true));
	$bloque=is_bloque($map[$Y][$X]['ID']);
	$divsInfos.='<div id="infos'.$X.'_'.$Y.'" class="infos">
		'.bdd2html($map[$Y][$X]['nom']).'
		';
	if(!empty($map[$Y][$X]['desc'])){
		$divsInfos.=' <h3>Description</h3>
			<p>'.filtrage_ordre($map[$Y][$X]['desc']).'</p>
			';
	}
	$divsInfos.='<h3>Informations</h3>
		<dl>
		<dt>Zone d\'utilisation :</dt>
		<dd>'.$map[$Y][$X]['utilisation'].' case'.($map[$Y][$X]['utilisation']>0?'s':'').'</dd>
		<dt>Zone de blocage :</dt>
		<dd>'.($map[$Y][$X]['blocage']?$map[$Y][$X]['blocage'].' case'.($map[$Y][$X]['blocage']>0?'s':''):'Imblocable').'<dd/>
		';
	if($map[$Y][$X]['QG']['reparation']){
		$divsInfos.='<dt>Atelier :</dt>
			<dd>niveau '.$map[$Y][$X]['QG']['reparation'].'</dd>
			';
		// '.($bloque?' (inutilisable)':'').'
	}
	if($map[$Y][$X]['QG']['regeneration']){
		$divsInfos.='<dt>H&ocirc;pital :</dt>
			<dd>niveau '.$map[$Y][$X]['QG']['regeneration'].'</dd>
			';
	}
	$divsInfos.='<dt>Possesseur :</dt>
		<dd>'.(empty($map[$Y][$X]['nom_camp'])?'aucun':bdd2html($map[$Y][$X]['nom_camp'])).'</dd>
		<dt class="clearer"></dt>
		</dl>
		';
	// Classe de la case.
	$class=empty($map[$Y][$X]['style'])?'':$map[$Y][$X]['style'];
	if(!empty($map[$Y][$X]['class'])){
		foreach($map[$Y][$X]['class'] AS $sup){
			$class.=' '.$sup;
		}
	}
	//  ',bdd2html($map[$Y][$X]['initiales']),'

	echo'  <td id="'.$X.'_'.$Y.'" class="'.$class.'">
		<ul>
		';
	if(sqrt($X*$X+$Y*$Y)<=$map[$Y][$X]['utilisation'] &&
		($map[$Y][$X]['camp']==$perso['armee'] ||
	$map[$Y][$X]['self'])){
		$plus='Hover';
		$div='';
		if(($map[$Y][$X]['QG']['armes'] &&
			!$perso['peine_armes']) ||
			($map[$Y][$X]['QG']['armures'] &&
			!$bloque &&
		!$perso['peine_armures'])){
			// On peut changer son matos dans ce QG.
			$div.='<form method="post" action="monmatos.php" target="framelink" title="&Eacute;quipement">
				<p>
				<input type="submit" name="go_equip_ok" value="Changer d\'&eacute;quipement" />
				</p>
				</form>
				';
		}
		// Pour le rechargement des armes.
		if($map[$Y][$X]['QG']['munitions'] &&
		!$perso['peine_munars']){
			$div.='<form method="post" action="jeuAjax.php" target="action">
				<p>
				<input type="hidden" name="qg_id" value="'.$map[$Y][$X]['ID'].'" />
				<input type="submit" name="recharge_arme_ok" value="Recharger votre arme" />
				</p>
				</form>
				';
		}
		// Reparation d'armure ?
		if($map[$Y][$X]['QG']['reparation'] &&
			!$bloque &&
			$perso['PA']<$perso['PA_max']&&
		!$perso['peine_reparations']){
			$PA=calcRepair($map[$Y][$X]['QG']['reparation']);
			if(round($PA)>=1){
				$div.='<form method="post" action="jeuAjax.php" target="action">
					<p>
					<input type="hidden" name="qg_id" value="'.$map[$Y][$X]['ID'].'" />

					<input type="submit" name="repare_ok" value="R&eacute;parer votre armure" /> de '.round($PA).' PA
					</p>
					</form>
					';
			}
		}
		// Recharge en mines ?
		if($map[$Y][$X]['QG']['munitions'] &&
			($perso['munitions_restantes_gad1']<$perso['mines_gad1'] &&
			$perso['date_last_used_gad1']+$GLOBALS['tour']<$time ||
			$perso['munitions_restantes_gad2']<$perso['mines_gad2'] && 
			$perso['date_last_used_gad2']+$GLOBALS['tour']<$time ||
			$perso['munitions_restantes_gad3']<$perso['mines_gad3'] &&
			$perso['date_last_used_gad3']+$GLOBALS['tour']<$time) &&
		!$perso['peine_munars']){
			$divsInfos.='<form method="post" action="jeuAjax.php" target="action">
				<p>
				<input type="hidden" name="qg_id" value="'.$map[$Y][$X]['ID'].'" />
				<input type="submit" name="recharge_gad_ok" value="Recharger vos gadgets" />
				</p>
				</form>
				';
		}
		if($map[$Y][$X]['QG']['tubage'] &&
		!$bloque&&!$perso['peine_tubes']){
			// QG pas bloque donc y a peut etre moyen de tuber.
			$tubes=my_fetch_array('SELECT tubes.ID,
				tubes.QG_2,
				tubes.prix,
				qgs.initiales,
				qgs.nom,
				qgs.malus_camou
				FROM tubes
				INNER JOIN qgs
				ON tubes.QG_2=qgs.ID
				WHERE tubes.QG_1='.$map[$Y][$X]['ID'].'
				AND (qgs.camp='.$perso['armee'].'
				OR qgs.type=1)
				ORDER BY QG_2 ASC');
			$prix=151;
			if($tubes[0]){
				$j=1;
				for($i=1;$i<=$tubes[0];$i++){
					if(!is_bloque($tubes[$i][1])){
						$tubes[$j][1]='QG '.$tubes[$i][3].' ('.$tubes[$i][2].' PM)';
						$j++;
						$prix=min($prix,$tubes[$i][2]);
						if($tubes[$i][2]>$perso['PM'])
							$tubes[$i]['disabled']=1;
					}
				}
				$tubes[0]=$j-1;
				if($tubes[0])
					$div.='<form method="post" action="jouer.php">
					<p>
					'.form_hidden('qg_dep',$map[$Y][$X]['ID']).'
					'.form_select('','qg_dest',$tubes,'').'
					'.($prix<=$perso['PM']?form_submit('tube_ok','y aller'):'').'
					</p>
					</form>
					';
				else
					$div.='<p>Aucun transport disponible pour le moment</p>
					';
			}
		}
		if($map[$Y][$X]['QG']['respawn']&&!$bloque && $perso['relocalisation']<$time){
			// Ce qg permet d'apparaitre et donc de sortir de la mission.
			$div.='<form method="post" action="jouer.php">
				<p>
				<input type="submit" name="sortir_ok" value="Quitter la mission" />
				</p>
				</form>
				';
		}
		if(!empty($div)){
			$divsInfos.='<h3>Actions</h3>
				'.$div;
		}
	}
	if(isset($map[$Y][$X]['bouge'])){
		$divsInfos.='<p> '.round($map[$Y][$X]['bouge']['prix']).' PT pour le prendre</p>';
	}

	foreach($options AS $key=>$img){
		if($img[0] && !empty($map[$Y][$X]['QG'][$key])){
			if($map[$Y][$X]['camp']!=$perso['armee'] &&
			!$map[$Y][$X]['self']){
				$plus='Ennemi';
			}
			else if($bloque && $img[2]==true){
				$plus='';
			}
			else if(sqrt($X*$X+$Y*$Y)<=$map[$Y][$X]['utilisation']){
				$plus='Utilisation';
			}
			else{
				$plus='Allie';
			}
			echo '    <li><img src="styles/'.$_SESSION['skin'].'/img/'.$img[0].$plus.'.gif" title="'.$img[1].'" /></li>
				';
		}
		$divsInfos.='';
	}
	echo'   </ul>
		';
	if(isset($map[$Y][$X]['bouge'])){
		echo'<p><a href="jouer.php?bouge='.$map[$Y][$X]['bouge']['nom'].'" class="qg">Prendre</a></p>';
	}
	echo'</td>
		';
	$divsInfos.='</div>
		';
}

function quality_tube($malus){
	if($malus<50)
		return 'bien';
	if($malus<100)
		return 'plut&ocirc;t bien';
	return 'mal';
}

?>
