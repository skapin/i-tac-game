<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
require_once('../sources/waypoints.php');
com_header_lite();
if(isset($_SESSION['com_perso']) && $_SESSION['com_perso'])
{
	// Pas besoin de grand chose à propos de ce perso, donc on 
	//fait une petite requete au lieu de passer par monperso.php
	$camp=my_fetch_array('SELECT armee,X,Y,map,niveau_gene FROM persos WHERE ID='.$_SESSION['com_perso']);
	$map=$camp[1]['map'];
	$X=$camp[1]['X'];
	$Y=$camp[1]['Y'];
	$niveau_gene=$camp[1]['niveau_gene'];
	$camp=$camp[1]['armee'];	
}
else
{
	$camp=0;
}

//----Waypoint-----------------
echo ' <script language="javascript">
	if (!document.all) {document.captureEvents(Event.MouseMove);}
document.onmousemove = position;
function position(evenement)
{
	element = document.all?event.srcElement:evenement.target;
	if (element.name!="carte" ) return;
	document.formulaire.x.value = Math.floor(document.all?event.x:evenement.layerX /2);
	document.formulaire.y.value = Math.floor(document.all?event.y:evenement.layerY /2);
}
function clickImage()
{
	document.waypointForm.strat_x.value = document.formulaire.x.value;
	document.waypointForm.strat_y.value = document.formulaire.y.value;
}
</script>';
//---END Waypoint---------------

if($camp)
{
	require_once('../inits/camps.php');
	require_once('../inits/terrains.php');
	if(!isset($_POST['carte_id']))
	{
		$action='map';
		$carte=$map;
	}
	else
	{
		$carte=explode('_',$_POST['carte_id']);
		if(is_numeric($carte[1]))
		{
			$action=$carte[0];
			$carte=$carte[1];
		}
		else
		{
			$action='map';
			$carte=$map;
		}
	}
	// Affichage de la carte.
	$droits=my_fetch_array('SELECT `tubes`,
		`satellite`,
		tubes_rand,
		topo_rand,
		radar_date
		FROM cartes_radars
		INNER JOIN cartes
		ON carte=cartes.ID 
		WHERE camp='.$camp.'
		AND carte='.$carte.' 
		LIMIT 1');
	if($droits[0]){
		switch($action){
			case 'topo':
			$image=$carte.'_'.$droits[1]['topo_rand'].'_topo.png';
			break;
			case 'tubes':
			if($droits[1]['tubes'])
				$image=$carte.'_'.$droits[1]['tubes_rand'].'_tubes.png';
			break;
			case 'map':
			$image=satellite($carte,$droits[1]['radar_date'],$camp,$droits[1]['satellite']);
			break;
			default:
			break;
		}
	}
	if(isset($image)){
		echo'<div id="map">
			<img name="carte" src="images/radars/'.$image.'" onclick="clickImage()" />
			';
		echo '<form name="formulaire">
			X = <input name="x" type="text" value="" size=4> Y = <input name="y" type="text" value="" size=4>
			</form>';




	}

	if(isset($_POST['carto'])){
		request('UPDATE persos
			SET cartographier = '.$_POST['carto'].'
			WHERE X='.$X.' AND Y='.$Y.' AND armee='.$camp.' AND ID='.$_SESSION['com_perso']);
	}
	if(($action=='topo' || $action == 'map') && 
	$map == $carte){
		if(!empty($image)){
			$size=@getimagesize('images/radars/'.$image);
			// Affichage de notre position.
			$mul=2;
			if($action == 'topo')
				$mul=1;
			echo' <div style="background:url(\'styles/'.$_SESSION['skin'].'/img/position.gif\');height:10px;width:10px;overflow:hidden;position:absolute;left:',($mul*$X-4),'px;top:',($mul*$Y-4),'px;display:none;" id="position"></div>
				</div>
				<script type="text/javascript" src="scripts/map.js"></script>
				<p><label for="show">Voir votre position? </label><input type="checkbox" onclick="$(\'position\').setStyle(\'display\',this.checked?\'block\':\'none\');" id="show" /></p>
				';
		}
	}
	else if($action=='qgs'){
		// On récupère une liste des QGs et on affiche les infos.
		$qgs=my_fetch_array('SELECT nom,
			initiales,
			visibilite,
			blocage,
			utilisation,
			tubage,
			armes,
			armures,
			munitions,
			X,
			Y,
			reparation,
			regeneration,
			camp,
			respawn,
			prenable,
			`type`,
			visible,
			malus_camou 
			FROM qgs
			WHERE carte='.$carte.'
		AND (visible=1
			OR visible=2
			AND camp='.$camp.')
			ORDER BY initiales ASC');
		echo'<table id="liste_qg">
			<thead><tr><th>Qg</th><th>X/Y</th><th>visible à</th><th>utilisable à</th><th>zone de blocage</th><th>possède</th><th>Appartient à</th><th>En libre service ?</th><th>Prenable ?</th></tr></thead>
			<tbody> 
			';
		for($i=1;$i<=$qgs[0];$i++){
			echo'<tr><th>',
				bdd2html($qgs[$i]['initiales']),'</th><td>',
				$qgs[$i]['X'],'/',$qgs[$i]['Y'],'</td><td>',
				$qgs[$i]['visibilite'],' cases</td><td>',
				$qgs[$i]['utilisation'],' cases</td><td>',
				$qgs[$i]['blocage'],' cases</td><td><ul>',
				($qgs[$i]['armes']?'<li>Un arsenal</li>':''),
				($qgs[$i]['munitions']?'<li>Un stock de munitions</li>':''),
				($qgs[$i]['armures']?'<li>Un entrepôt d\'armures</li>':''),
				($qgs[$i]['reparation']?'<li>Un atelier de réparation</li>':''),
				($qgs[$i]['regeneration']?'<li>Un hopital de campagne de niveau '.$qgs[$i]['regeneration'].'</li>':''),
				($qgs[$i]['tubage']?'<li>Un accés au système de tubes ('.quality_tube($qgs[$i]['malus_camou']).' caché)'.'</li>':''),
				($qgs[$i]['respawn']?'<li>Une piste d\'avion</li>':''),
				'</ul></td><td>',
				camp_nom($qgs[$i]['camp']),'</td><td>',
				($qgs[$i]['type']?'En libre service':''),'</td><td>',
				($qgs[$i]['prenable']?'Prenable':'Non prenable'),'</td></tr>
				';
		}
		echo'</tbody>
			</table>
			';
	}
	// Affichage du formulaire de choix de carte.

	$cartes=my_fetch_array('SELECT `tubes`,
		`satellite`,
		nom, 
		cartes.ID 
		FROM cartes
		INNER JOIN cartes_radars
		ON cartes_radars.carte=cartes.ID
		AND cartes_radars.camp='.$camp.'
		AND terrain=1 
		ORDER BY cartes.nom ASC');


	if($cartes[0]){
		$select='<label for="carte_id">Voir la carte : </label>
			<select name="carte_id" id="carte_id">
			';
		$map=-1;
		for($i=1;$i<=$cartes[0];$i++){
			if($cartes[$i]['ID']!=$map){
				$select.=($map!=-1?'</optgroup>
					':'').'<optgroup label="'.bdd2js($cartes[$i]['nom']).'">
					';
				$map=$cartes[$i]['ID'];
			}
			// if($cartes[$i]['satellite'])
			$select.='<option value="map_'.$map.'">'.bdd2html($cartes[$i]['nom']).' - GPS</option>
				';
			if($cartes[$i]['tubes'])
				$select.='<option value="tubes_'.$map.'">'.bdd2html($cartes[$i]['nom']).' - Carte des tubes</option>
				';
			$select.='<option value="topo_'.$map.'">'.bdd2html($cartes[$i]['nom']).' - Lignes de niveau</option>
				<option value="qgs_'.$map.'">'.bdd2html($cartes[$i]['nom']).' - Liste des QGs</option>
				';
		}
		echo'<form method="post" action="carte.php">
			<p>
			',$select,($map!=-1?'</optgroup>':''),'</select>
			',form_submit('carte_ok','Voir'),'</p>
			</form>
			';		
		//on charge le module de WayPoint (source/waypoints.php)
		consoleWaypoint($niveau_gene,$camp,$carte);
	}
	else
		echo'<p>Vous n\'avez accés à aucune carte.</p>
		';
}
else
	echo'<p>Vous n\'êtes pas logué.</p>
	';
echo'</div>
	';
com_footer_lite();

function satellite($id,$radar_date,$camp,$droit=0)
{
	$time=time();
	if($radar_date+$GLOBALS['refresh_carte']<$time || 
	!file_exists('images/radars/'.$id.'_'.$camp.'_'.$radar_date.'.png')){
		// Il faut remplacer la carte.
		if(!create_carte($id,$time,$camp,$droit)){
			if(request('UPDATE cartes_radars
				SET radar_date='.$time.'
				WHERE carte='.$id.' AND camp='.$camp.'
			LIMIT 1')){
				if(file_exists('images/radars/'.$id.'_'.$camp.'_'.$radar_date.'.png'))
					unlink('images/radars/'.$id.'_'.$camp.'_'.$radar_date.'.png');
				return $id.'_'.$camp.'_'.$time.'.png';
			}
			else
				return $id.'_'.$camp.'_'.$radar_date.'.png';
		}
		else
			return $id.'_'.$camp.'_'.$radar_date.'.png';
	}
	else
		return $id.'_'.$camp.'_'.$radar_date.'.png';
}

function create_carte($carte_id,$time,$camp,$gps)
{
	$erreur=0;
	$time=time();
	if(file_exists('../cartes/carto_'.$carte_id.'_'.$camp.'.png'))
	{
		$dim=getimagesize('../cartes/carto_'.$carte_id.'_'.$camp.'.png');
		if($qgs=my_fetch_array('SELECT initiales,X,Y,camp
			FROM qgs
			WHERE carte='.$carte_id.'
			AND (`visible`=1 OR `visible`=2 AND `camp`='.$camp.')'))
		{
			$im=imagecreatefrompng('../cartes/carto_'.$carte_id.'_'.$camp.'.png');
			if($im)
			{
				$noir=imagecolorexact($im,0,0,0);
				if($noir < 0)
				{
					$noir=imagecolorallocate($im,0,0,0);
				}
				/*
				* ITAC - LD - 2010-02-07
				* ITAC - LD -BEGIN
				* http://www.dandoy.fr/mantis/view.php?id=15
				*/
				if($gps > 0)
				{
					$sql = "";
					if($gps == 1)
					{
						$sql = 'SELECT X,Y,armee,camouflage,mouchard,date_camou,date_decamou
							FROM persos
							WHERE armee='.$camp.' AND map='.$carte_id;
					}
					else
					{
						$sql = 'SELECT X,Y,armee,camouflage,mouchard,date_camou,date_decamou
							FROM persos
							WHERE map='.$carte_id;
					}
					/*
					* ITAC - LD - END
					*/

				$persos=my_fetch_array($sql);
				for($i=1;$i<=$persos[0];$i++){
					// affichage des gens pas camous.
					if(imagecolorat($im,$persos[$i]['X'],$persos[$i]['Y']) != $noir && // Le pixel ne doit pas etre noir
					!($persos[$i]['camouflage'] &&
						(($persos[$i]['camouflage']<6 &&
						$persos[$i]['date_camou']+$GLOBALS['temps_camou']<$time) ||
						($persos[$i]['camouflage']==6 &&
						$persos[$i]['date_decamou']+$GLOBALS['temps_decamou']>$time))))
					{
						$couleur=imagecolorexact($im,camp_R($persos[$i]['armee']),camp_G($persos[$i]['armee']),camp_B($persos[$i]['armee']));
						if($couleur==-1)
						{
							$couleur=imagecolorallocate($im,camp_R($persos[$i]['armee']),camp_G($persos[$i]['armee']),camp_B($persos[$i]['armee']));
						}
						imagesetpixel($im,$persos[$i]['X'],$persos[$i]['Y'],$couleur);
					}
				}
			}
			//-----------------Skapin -- Ajout de WayPoint
			$player=my_fetch_array('SELECT compagnie,niveau_gene FROM persos WHERE ID='.$_SESSION['com_perso']);

			$req_waypoint='SELECT * FROM waypoint WHERE camps='.$camp.' AND carte='.$carte_id.' AND ( grade<='.$player[1]['niveau_gene'].' OR compa='.$player[1]['compagnie'].' )' ;
			$waypoints=my_fetch_array($req_waypoint);
			for($i=1;$i<=$waypoints[0];$i++) {

				$offsetX=5;
				$offsetY=-5;
				if($dim[0]-$waypoints[$i]['x']<20)
				{
					$offsetX=-20;
				}
				if($waypoints[$i]['y']<20)
				{
					$offsetY=0;
				}
				$couleur=imagecolorallocate($im,$waypoints[$i]['color_R'],$waypoints[$i]['color_G'],$waypoints[$i]['color_B']);
				imagestring($im,1,$waypoints[$i]['x']+$offsetX,$waypoints[$i]['y']+$offsetY,$waypoints[$i]['label'],$couleur);

				$croix=imagecolorallocate($im,$waypoints[$i]['color_R'],$waypoints[$i]['color_G'],$waypoints[$i]['color_B']);
				$croix_in=imagecolorallocate($im,30,20,10);
				imagesetpixel($im,$waypoints[$i]['x']-1,$waypoints[$i]['y'],$croix_in);
				imagesetpixel($im,$waypoints[$i]['x'],$waypoints[$i]['y']-1,$croix_in);
				imagesetpixel($im,$waypoints[$i]['x'],$waypoints[$i]['y']+1,$croix_in);
				imagesetpixel($im,$waypoints[$i]['x']+1,$waypoints[$i]['y'],$croix_in);						

				imagesetpixel($im,$waypoints[$i]['x'],$waypoints[$i]['y'],$croix);
				imagesetpixel($im,$waypoints[$i]['x']-1,$waypoints[$i]['y']+1,$croix);
				imagesetpixel($im,$waypoints[$i]['x']-1,$waypoints[$i]['y']-1,$croix);
				imagesetpixel($im,$waypoints[$i]['x']+1,$waypoints[$i]['y']-1,$croix);
				imagesetpixel($im,$waypoints[$i]['x']+1,$waypoints[$i]['y']+1,$croix);
			}
			//------------------------END WayPoint
		}
		for($i=1;$i<=$qgs[0];$i++)
		{
			// On affiche le qg.
			// Sa couleur est elle deja dans la palette ? Si non, on l'ajoute.
			$couleur=imagecolorexact($im,camp_R($qgs[$i]['camp']),camp_G($qgs[$i]['camp']),camp_B($qgs[$i]['camp']));
			if($couleur==-1)
			{
				$couleur=imagecolorallocate($im,camp_R($qgs[$i]['camp']),camp_G($qgs[$i]['camp']),camp_B($qgs[$i]['camp']));
			}
			imagefilledrectangle ($im,
				$qgs[$i]['X']-1,
				$qgs[$i]['Y']-1,
				$qgs[$i]['X']+1,
				$qgs[$i]['Y']+1,
				$couleur);
			$offsetX=5;
			$offsetY=-5;
			if($dim[0]-$qgs[$i]['X']<10)
			{
				$offsetX=-10;
			}
			if($qgs[$i]['Y']<10)
			{
				$offsetY=0;
			}
			imagestring($im,1,$qgs[$i]['X']+$offsetX,$qgs[$i]['Y']+$offsetY,$qgs[$i]['initiales'],$noir);
		}
		$mul=2;
		$im2=imagecreate($mul*$dim[0],$mul*$dim[1]);
		imagecopyresized($im2,$im,0,0,0,0,$mul*$dim[0],$mul*$dim[1],$dim[0],$dim[1]);
		if(!$erreur)
		{
			if(!imagepng($im2,'images/radars/'.$carte_id.'_'.$camp.'_'.$time.'.png'))
			{
				$erreur=1;
			}
		}
	}
}
else
{
	$erreur=1;
}
return $erreur;
}

function bddX2mapX($X,$dim)
{
	return $dim[0]/2+$X;
}

function bddY2mapY($Y,$dim)
{
	return $dim[1]/2-$Y;
}

function quality_tube($malus)
{
	if($malus<50)
		return 'bien';
	if($malus<100)
		return 'plutôt bien';
	return 'mal';
}
?>