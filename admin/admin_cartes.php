<h2>Gestion des cartes</h3>
<?php
$camps=my_fetch_array("SELECT `ID`, `nom`
	FROM `camps`
	WHERE ID!='0'
	ORDER BY `ID` ASC");
if(isset($_POST["new_carte_ok"]))
{
	$erreur=0;
	if(!isset($_POST["new_carte_nom"]) || !$_POST["new_carte_nom"])
	{
		$erreur=1;
		erreur(0,"Il faut sp&eacute;cifier un nom pour la carte.");
	}
	else if(exist_in_db("SELECT `ID`
		FROM `cartes`
		WHERE `nom`='".post2bdd($_POST["new_carte_nom"])."'"))
	{
		$erreur=1;
		erreur(0,"Nom de carte d&eacute;j&agrave; utilis&eacute;.");
	}
	if(!(isset($_POST["new_carte_mission"])&&is_numeric($_POST["new_carte_mission"]))){
		$erreur=1;
		erreur(0,"Identifiant de mission incorrect.");
	}
	else if(!exist_in_db("SELECT `ID`
		FROM `missions`
		WHERE `ID`='".$_POST["new_carte_mission"]."'"))
	{
		$erreur=1;
		erreur(0,"Identifiant de mission inconnu");
	}
	$time=time();
	if(!$erreur)
	{
		$topo_rand=rand(1,65535);
		$tube_rand=rand(1,65535);
		request("INSERT
			INTO `cartes` (`nom`,
			`mission`,
			`soussol`,
			`tube`,`topo_rand`,`tubes_rand`,coordonnees)
			VALUES('".post2bdd($_POST["new_carte_nom"])."',
			'".$_POST["new_carte_mission"]."',
			'".(isset($_POST["new_carte_ssol"])?1:0)."',
			'$time','$topo_rand','$tube_rand',".post_on('new_carte_coord').")");
		$id=last_id();
		if(!$id)
		{
			$erreur=1;
			erreur(0,"Impossible d'enregistrer la carte en bdd.");
		}
		else
		{
			$detail = "<ul>
				<li>ID : ".$id."</li>
				<li>Nom : ".post2html($_POST['new_carte_nom'])."</li>
				<li>Mission :".$_POST['new_carte_mission']."</li>
				<li>Sous-sol : ".(isset($_POST["new_carte_ssol"])?1:0)."</li>
				<li>Tube : ".$time."</li>  	
				</ul>	
				";
			$colonnesCarto = '';
			for($i=1;$i<=$camps[0];$i++)
			{
				$colonnesCarto.='`carto_'.$camps[$i]['ID'].'` tinyint(3) NOT NULL,';
			}
			console_log('anim_cartes',"Cr&eacute;ation de la carte ".post2html($_POST['new_carte_nom']),$detail,0,0);
			if(!request("CREATE TABLE `carte_$id` (`X` smallint(6) NOT NULL,
				`Y` smallint(6) NOT NULL,
				`Z` smallint(6) NOT NULL,
				`h` smallint(6) NOT NULL,
				`terrain` tinyint(4) NOT NULL,".$colonnesCarto."
				PRIMARY KEY(`X`,`Y`))"))
			{
				erreur("Impossible de cr&eacute;er la table cens&eacute;e contenir les cases de la carte.");
				request("DELETE
					FROM `cartes`
					WHERE `ID`='$id'");
				if(!affected_rows())
					erreur(0,"Impossible de supprimer la carte de la bdd. Il va falloir contacter l'admin.");
				else
					request("OPTIMIZE TABLE `cartes`");
			}
			else
			{
				foreach($_POST as $key=>$value)
					if(ereg('new_carte_camp_[0-9]+',$key))
				{
					$camp_id=explode("_",$key);
					if(!request("INSERT
						INTO `cartes_radars` (`carte`,`camp`,`terrain`,`tubes`,`satellite`)
						VALUES ('$id','$camp_id[3]','1','".post_on('new_carte_tubes_'.$camp_id[3])."','".post2bdd('new_carte_satellite_'.$camp_id[3])."')"))
						erreur(0,"Impossible de rendre disponible cette mission pour un camp.");
				}
				// Enregistrement des cases
				if(upload_map($id,'new_',0)!=2){
					add_message(0,"Carte enregistr&eacute;e");
					upload_file('new_carte_sol2','images/radars/'.$id.'_'.$topo_rand.'_topo.png');
					upload_file('new_carte_tube','images/radars/'.$id.'_'.$tube_rand.'_tubes.png');
				}
				else
				{
					request("DELETE
						FROM `cartes`
						WHERE `ID`='$id'");
					if(!affected_rows())
						erreur(0,"Impossible de supprimer la carte de la bdd. Il va falloir contacter l'admin.");
					else
						request("OPTIMIZE TABLE `cartes`");
					if(request("DELETE
						FROM cartes_radars
						WHERE carte='$id'"))
						request("OPTIMIZE TABLE `cartes_radars`");
					if(!request("DROP TABLE `carte_$id`"))
						erreur(0,"Impossible de supprimer la table servant &agrave; enregistrer les cases de la carte.");
				}
			}
		}
	}
}

//*****************************************************************************
// Modification d'une carte.
//*****************************************************************************

else if(isset($_POST["mod_carte_ok"],$_POST['mod_carte_id'])&&is_numeric($_POST['mod_carte_id']))
{
	$erreur=0;
	$carte=my_fetch_array("SELECT tubes_rand,topo_rand
		FROM `cartes`
		WHERE `ID`='$_POST[mod_carte_id]'");
	if(!$carte[0])
	{
		$erreur=1;
		erreur(0,"Identifiant de carte inconnu");
	}
	if(!isset($_POST["mod_carte_nom"]) || !$_POST["mod_carte_nom"])
	{
		$erreur=1;
		erreur(0,"Il faut sp&eacute;cifier un nom pour la carte.");
	}
	else if(exist_in_db("SELECT `ID`
		FROM `cartes`
		WHERE `nom`='".post2bdd($_POST["mod_carte_nom"])."'
		AND `ID`!='$_POST[mod_carte_id]'"))
	{
		$erreur=1;
		erreur(0,"Nom de carte d&eacute;j&agrave; utilis&eacute;.");
	}
	if(!(isset($_POST["mod_carte_mission"])&&is_numeric($_POST["mod_carte_mission"])))
	{
		$erreur=1;
		erreur(0,"Identifiant de mission incorrect.");
	}
	else if(!exist_in_db("SELECT `ID`
		FROM `missions`
		WHERE `ID`='".$_POST["mod_carte_mission"]."'"))
	{
		$erreur=1;
		erreur(0,"Identifiant de mission inconnu");
	}
	if(!$erreur)
	{
		$time=time();
		$topo_rand=rand(1,65535);
		$tubes_rand=rand(1,65535);
		request("UPDATE `cartes`
			SET `nom`='".post2bdd($_POST["mod_carte_nom"])."',
			`mission`='".$_POST["mod_carte_mission"]."',
			`soussol`='".(isset($_POST["mod_carte_ssol"])?1:0)."',
			`tube`='$time',
			tubes_rand='$tubes_rand',
			topo_rand='$topo_rand',
			coordonnees=".post_on('mod_carte_coord')." 
			WHERE `ID`='$_POST[mod_carte_id]'
			LIMIT 1");
		if(affected_rows())
		{
			if(upload_file('mod_carte_sol2','images/radars/'.$_POST['mod_carte_id'].'_'.$topo_rand.'_topo.png'))
				unlink('images/radars/'.$_POST['mod_carte_id'].'_'.$carte[1]['topo_rand'].'_topo.png');
			if(upload_file('mod_carte_tube','images/radars/'.$_POST['mod_carte_id'].'_'.$tubes_rand.'_tubes.png'))
				unlink('images/radars/'.$_POST['mod_carte_id'].'_'.$carte[1]['tubes_rand'].'_tubes.png');
			$detail = "<ul>
				<li>ID : ".$_POST['mod_carte_id']."</li>
				<li>Nom : ".post2html($_POST['mod_carte_nom'])."</li>
				<li>Mission :".$_POST['mod_carte_mission']."</li>
				<li>Sous-sol : ".(isset($_POST["mod_carte_ssol"])?1:0)."</li>
				<li>Tube : ".$time."</li>  	
				</ul>	
				"; 	
			console_log('anim_cartes',"Modification de la carte ".post2html($_POST['mod_carte_nom']),$detail,0,0);

		}
		if(!request("DELETE
			FROM `cartes_radars`
			WHERE `carte`='$_POST[mod_carte_id]'"))
		{
			erreur(0,"Impossible de modifier les camps ayant acc&eacute;s au radar sur cette carte.");
		}
		else
		{
			request("OPTIMIZE TABLE `cartes_radars`");
			foreach($_POST as $key=>$value)
			{
				if(ereg('mod_carte_camp_[0-9]+',$key))
				{
					$camp_id=explode("_",$key);
					if(!request("INSERT
						INTO `cartes_radars` (`carte`,`camp`,`terrain`,`tubes`,`satellite`)
						VALUES ('$_POST[mod_carte_id]','$camp_id[3]','1','".post_on('mod_carte_tubes_'.$camp_id[3])."','".post2bdd('mod_carte_satellite_'.$camp_id[3])."')"))
						erreur(0,"Impossible de rendre disponible cette mission pour un camp.");
				}
			}
			// On cr&eacute;e la carte.
			if(upload_map($_POST['mod_carte_id'],'mod_',1))
			{
				add_message(0,"Carte modifi&eacute;e.");
			}
		}
	}
}

//*****************************************************************************
// Suppression d'une carte.
//*****************************************************************************

else if(isset($_POST["del_carte_ok"],$_POST['del_carte_id'])&&is_numeric($_POST['del_carte_id']))
{    
	$carte=my_fetch_array("SELECT `nom`
		FROM `cartes`
		WHERE `ID`='$_POST[del_carte_id]'");
	if($carte[0])
	{
		echo'<form method="post" action="anim.php?admin_cartes">
			<h3>Confirmation :</h3>
			<p>&Ecirc;tes vous s&ucirc;r de vouloir supprimer cette carte ('.bdd2html($carte[1][0]).')?<br />
			'.form_hidden("id",$_POST['del_carte_id']).'
			'.form_submit("del_carte_no","Non").'&nbsp;&nbsp;&nbsp;&nbsp;
		'.form_submit("del_carte_yes","Oui").'
			</p>
			</form>
			<hr />
			';
	}
	else
		erreur(0,"Identifiant de carte inconnu.");
}
else if(isset($_POST["del_carte_yes"],$_POST['id'])&&is_numeric($_POST['id']))
{
	request("DELETE
		FROM `cartes`
		WHERE `ID`='$_POST[id]'");
	if(affected_rows())
	{
		// console_log('anim_cartes',"Suppression de la carte ".$_POST['id'],$detail,0,0);
		console_log('anim_cartes',"Suppression de la carte ".$_POST['id'],"",0,0);

		request("OPTIMIZE TABLE `cartes`");
		request("DELETE
			FROM `cartes_radars`
			WHERE `carte`='$_POST[id]'");
		request("OPTIMIZE TABLE `cartes_radars`");
		request("UPDATE `persos`
			SET `map`='0',`X`='0',`Y`='0'
			WHERE `map`='$_POST[id]'");
		if(!request("DROP TABLE `carte_$_POST[id]`"))
			erreur(0,"Impossible de supprimer table où les cases &eacute;taient stock&eacute;es.");
	}
	else
		erreur(0,"Impossible de supprimer la carte de la base de donn&eacute;e.");
}
else if(isset($_POST["synchro_carte_ok"],$_POST['synchro_carte_id'])&&is_numeric($_POST['synchro_carte_id']))
{
	// Preparation des images
	$imgs = array();
	$noirs = array();
	$idCarte = $_POST['synchro_carte_id'];
	for($i=1;$i<=$camps[0];$i++)
	{
		$id = $camps[$i]['ID'];
		$imgs[$id] = ImageCreateFromPng("../cartes/$idCarte.png");
		$noir[$id] = imagecolorexact($imgs[$id],0,0,0);
		if($noir[$id] < 0)
		{
			$noir[$id] = imagecolorallocate($imgs[$id],0,0,0);
		}
	}
	$sql = 'SELECT * FROM carte_'.$idCarte;
	$req = request($sql);
	while($case=mysql_fetch_array($req))
	{
		for($i=1;$i<=$camps[0];$i++)
		{
			$id = $camps[$i]['ID'];
			if(empty($case['carto_'.$id]))
			{
				imagesetpixel($imgs[$id],$case['X'],$case['Y'],$noir[$id]);
			}
		}
	}
	for($i=1;$i<=$camps[0];$i++)
	{
		$id = $camps[$i]['ID'];
		imagepng($imgs[$id],'../cartes/carto_'.$idCarte.'_'.$id.'.png');
	}
}

//*****************************************************************************
// Pr&eacute;paration du formulaire.
//*****************************************************************************

$terrains=my_fetch_array("SELECT * FROM terrains");
echo'<h3>Liste des terrains.</h3>
	';
if($terrains[0])
{
	echo'<ul>
		';
	for($i=1;$i<=$terrains[0];$i++)
		echo'<li>'.$terrains[$i]['nom'].' : r/g/b = '.$terrains[$i]['R'].'/'.$terrains[$i]['G'].'/'.$terrains[$i]['B'].'</li>
		';
	echo'</ul>
		';
} 
$missions=my_fetch_array("SELECT `ID`,`nom`
	FROM `missions`
	WHERE ID!='0'
	ORDER BY `ID` ASC");
$cartes=my_fetch_array("SELECT *
	FROM cartes
	ORDER BY ID ASC");
$str_camps1=$str_camps2='';
$script='<script type="text/javascript">
	function afficher_carte()
{
	if(!document.getElementById)
		return; 
	';
for($i=1;$i<=$cartes[0];$i++)
{
	$script.='  if(document.getElementById("mod_carte_id").value=='.$cartes[$i]['ID'].')
	{
		nom="'.text2js($cartes[$i]['nom']).'";
		mission='.$cartes[$i]['mission'].'; 
		ssol='.$cartes[$i]['soussol'].';
		coord='.$cartes[$i]['coordonnees'].';
		src="'.$cartes[$i]['ID'].'"
			';
		for($j=1;$j<=$camps[0];$j++)
		{
			$script.='    var radar_'.$camps[$j]['ID'].'=';
			if(exist_in_db("SELECT `camp`
				FROM `cartes_radars`
				WHERE `carte`='".$cartes[$i]['ID']."'
				AND `camp`='".$camps[$j]['ID']."'"))
				$script.='1; 
			';
			else
				$script.='0;
			'; 
		}
		for($j=1;$j<=$camps[0];$j++)
		{
			$script.='    var satellite_'.$camps[$j]['ID'].'=';
			if($res = my_fetch_array("SELECT `satellite`
				FROM `cartes_radars`
				WHERE `carte`='".$cartes[$i]['ID']."'
				AND `camp`='".$camps[$j]['ID']."'"))
				$script.= ($res[1]['satellite']?$res[1]['satellite']:0) .'; 
			';
			else
				$script.='0;
			'; 
		}
		$script.='  }
		';
	} 
	$script.='  document.getElementById("mod_carte_nom").value=nom;
	document.getElementById("mod_carte_mission").value=mission;
	document.getElementById("mod_carte_ssol").checked=ssol?"checked":"";
	document.getElementById("mod_carte_coord").checked=coord?"checked":"";
	document.getElementById("mod_carte_image_image").src="images/cartes/"+src+".png";
	';
	for($i=1;$i<=$camps[0];$i++)
	{
		$script.='  document.getElementById("mod_carte_camp_'.$camps[$i]['ID'].'").checked=radar_'.$camps[$i]['ID'].'?"checked":"";
		var objects = document.getElementById("mod_carte_satellite_'.$camps[$i]['ID'].'").getElementsByTagName("option");
		for(idx=0; idx < objects.length; idx=idx+1)
		{
			if(objects[idx].value == satellite_'.$camps[$i]['ID'].')
			{
				objects[idx].selected=true;
			}
		}
		';
		
		$var_sattelite = array(
			0 => 3,
			1 => array("0","Non"),
			2 => array("1","Camp"),
			3 => array("2","Tout"),
			);
		
		$str_camps1.=form_check(text2html($camps[$i]['nom']).' : terrain et QG ?',"new_carte_camp_".$camps[$i]['ID']).'<br />
			'.form_check('Tubes ?','new_carte_tubes_'.$camps[$i]['ID']).'<br />
			'
			// .form_check('Satellites (affichage des non camous) ?','new_carte_satellite_'.$camps[$i]['ID'])
			. form_select('Satellites (affichage des non camous) ?','new_carte_satellite_'.$camps[$i]['ID'],$var_sattelite,'',"0")
			.'<br />
			';
		$str_camps2.=form_check(text2html($camps[$i]['nom']).' : terrain et QG ?','mod_carte_camp_'.$camps[$i]['ID']).'<br />
			'.form_check('Tubes ?','mod_carte_tubes_'.$camps[$i]['ID']).'<br />
			'
			// .form_check('Satellites (affichage des non camous) ?','mod_carte_satellite_'.$camps[$i]['ID'])
			. form_select('Satellites (affichage des non camous) ?','mod_carte_satellite_'.$camps[$i]['ID'],$var_sattelite,'')
			.'<br />
			';
	}
	$script.='}
	'.(isset($_POST['mod_carte_ok'])?'':'afficher_carte();').'
		</script>
		';
	echo'<form method="post" action="anim.php?admin_cartes">
		<h3>Synchronisation des cartos d\'une carte :</h3>
		<p>'.form_select("Carte : ","synchro_carte_id",$cartes,"").'
		'.form_submit("synchro_carte_ok","Synchroniser").'</p> 
		</form>

		<form method="post" action="anim.php?admin_cartes" enctype="multipart/form-data">
		<h3>Cr&eacute;ation d\'une carte:</h3>
		<p>
		'.form_text("Nom : ","new_carte_nom","","").'<br />
		'.form_select("Pour la mission : ","new_carte_mission",$missions,"").'</p> 
		<h4>Acc&eacute;s au radar: </h4>
		<p>
		'.$str_camps1.'</p>
		<p>
		'.form_image("Image : ","new_carte_image").'<br />
		'.form_image("Topographie (hauteur du sol) : ","new_carte_sol").'<br />
		'.form_image("Hauteur totale : ","new_carte_total").'<br />
		'.form_image("Carte des tubes : ","new_carte_tube").'<br />
		'.form_image("Carte topographique (pour les joueurs) : ","new_carte_sol2").'<br />
		'.form_check("Sous-sol ? ","new_carte_ssol").'<br />
		'.form_check("Coordonn&eacute;es disponibles ? ","new_carte_coord").'<br />
		'.form_submit("new_carte_ok","Cr&eacute;er").'</p> 
		<h3>Modification d\'une carte:</h3>
		<p>
		'.form_select("Carte : ","mod_carte_id",$cartes,"afficher_carte();").'<br />
		'.form_text("Nom : ","mod_carte_nom","","").'<br />
		'.form_select("Pour la mission : ","mod_carte_mission",$missions,"").'</p> 
		<h4>Acc&eacute;s au radar: </h4>
		<p>
		'.$str_camps2.'</p>
		<p>
		'.form_image("Image : ","mod_carte_image").'<br />
		'.form_image("Topographie (hauteur du sol) : ","mod_carte_sol").'<br />
		'.form_image("Hauteur totale : ","mod_carte_total").'<br />
		'.form_image("Carte des tubes : ","mod_carte_tube").'<br />
		'.form_image("Carte topographique (pour les joueurs) : ","mod_carte_sol2").'<br />
		'.form_check("Sous-sol ? ","mod_carte_ssol").'<br />
		'.form_check("Coordonn&eacute;es disponibles ? ","mod_carte_coord").'<br />
		'.form_submit("mod_carte_ok","Modifier").'</p> 
		<h3>Supprimer une carte:</h3> 
		<p> 
		'.form_select("Carte : ","del_carte_id",$cartes,"").'<br />
		'.form_submit("del_carte_ok","Supprimer").'</p> 
		</form>
		'.$script;

	function upload_file($nom_fichier,$new_nom)
	{
		if (!is_uploaded_file($_FILES[$nom_fichier]['tmp_name']))
		{
			erreur(0,"Erreur d'upload.");
			return 0;
		}
		else if(($_FILES[$nom_fichier]['type']!="image/x-png")&&($_FILES[$nom_fichier]['type']!="image/png"))
		{
			erreur(0,"Mauvais type de fichier(".$_FILES[$nom_fichier]['type']."), il faut du png !");
			return 0;
		}
		else if (!move_uploaded_file($_FILES[$nom_fichier]['tmp_name'], $new_nom))
		{
			erreur(0,"Erreur de d&eacute;placement !");
			return 0;
		}
		echo'Fichier '.$new_nom.' enregistr&eacute;.<br />';
		return 1;
	}

	function upload_map($id,$prefixe,$replace)
	{
		global $camps;
		// G&eacute;n&eacute;ration de la map.
		if (!is_uploaded_file($_FILES[$prefixe.'carte_image']['tmp_name'])||!is_uploaded_file($_FILES[$prefixe.'carte_sol']['tmp_name'])||!is_uploaded_file($_FILES[$prefixe.'carte_total']['tmp_name']) )
		{
			$erreur=1;
			erreur(0,"Erreur d'upload.");
		}
		else if(($_FILES[$prefixe.'carte_image']['type']!="image/x-png")&&($_FILES[$prefixe.'carte_image']['type']!="image/png"))
		{
			$erreur=1;
			erreur(0,"Mauvais type de fichier(".$_FILES[$prefixe.'carte_image']['type']."), il faut du png !");
		}
		else if(($_FILES[$prefixe.'carte_sol']['type']!="image/x-png")&&($_FILES[$prefixe.'carte_sol']['type']!="image/png"))
		{
			$erreur=1;
			erreur(0,"Mauvais type de fichier(".$_FILES[$prefixe.'carte_sol']['type']."), il faut du png !");
		}
		else if(($_FILES[$prefixe.'carte_total']['type']!="image/x-png")&&($_FILES[$prefixe.'carte_total']['type']!="image/png"))
		{
			$erreur=1;
			erreur(0,"Mauvais type de fichier(".$_FILES[$prefixe.'carte_total']['type']."), il faut du png !");
		}
		else
		{
			if (!move_uploaded_file($_FILES[$prefixe.'carte_image']['tmp_name'], "../cartes/$id.png")||!move_uploaded_file($_FILES[$prefixe.'carte_sol']['tmp_name'], "../cartes/sol_$id.png")||!move_uploaded_file($_FILES[$prefixe.'carte_total']['tmp_name'], "../cartes/total_$id.png"))
			{
				$erreur=1;
				erreur(0,"Erreur de d&eacute;placement !");
			}
			else
			{
				$size = getimagesize("../cartes/$id.png");
				$im = ImageCreateFromPng("../cartes/$id.png");
				$size_sol = getimagesize("../cartes/sol_$id.png");
				$im_sol = ImageCreateFromPng("../cartes/sol_$id.png");
				$size_total = getimagesize("../cartes/total_$id.png");
				$im_total = ImageCreateFromPng("../cartes/total_$id.png");
				if(!$im || !$im_sol || !$im_total)
				{
					erreur(0,"Impossible de cr&eacute;er la ressource image.");
					$erreur=1;
				}
				else
				{
					if($replace)
					{
						if(!request("TRUNCATE TABLE `carte_$_POST[mod_carte_id]`"))
						{
							erreur(0,"Impossible d'effacer l'ancienne carte en bdd.");
							return 3;
						}
					}
					$min_Y=0;//-floor($size[1]/2);
					$max_Y=$size[1]-1;//floor($size[1]/2);
					$min_X=0;//-floor($size[0]/2);
					$max_X=$size[0]-1;//floor($size[0]/2);
					$y=$max_Y;
					$x=$min_X;
					$erreur=0;
					include('../inits/terrains.php');
					for($y=$min_Y;$y<=$max_Y && !$erreur;$y++)
						//	      while($y>$min_Y && !$erreur)
					{
						for($x=$min_X;$x<=$max_X && !$erreur;$x++)
							//		  $x=$min_X;
						//while($x<$max_X && !$erreur)
						{
							// R&eacute;cup&eacute;ration de l'index de la couleur du pixel en (x,y).
							$index = ImageColorAt($im, $x, $y);
							// R&eacute;cup&eacute;ration des valeurs red, green et blue correspondant
							// à la couleur de cet index.
							$rgb=imagecolorsforindex ($im, $index);
							// Même chose pour les hauteurs.
							$index=ImageColorAt($im_sol, $x, $y);
							$sol=imagecolorsforindex ($im_sol, $index);

							$index=ImageColorAt($im_total, $x, $y);
							$total=imagecolorsforindex ($im_total, $index);

							if($sol['red']==255)
								$sol['red']=254;
							if($total['red']<=$sol['red'])
								$total['red']=$sol['red']+1;
							$terrain=isset($GLOBALS['rgb_terrain'][$rgb['red']][$rgb['green']][$rgb['blue']])?$GLOBALS['rgb_terrain'][$rgb['red']][$rgb['green']][$rgb['blue']]:-1;
							if($terrain==-1)
							{
								$erreur=2;
								erreur(0,"Valeurs RGB ($rgb[red]-$rgb[green]-$rgb[blue]) inconnues pour le pixel en $x/$y");
							}
							else if(!request("INSERT INTO `carte_$id` (X,Y,Z,h,terrain) VALUES('$x','$y','$sol[red]','$total[red]','$terrain')"))
							{
								$erreur=2;
								erreur(0,"Erreur lors de l'enregistrement de la case en $x/$y");
							}
							//$x++;
						}
						//$y--;
					}
					if(!$erreur)
					{
						/*if(file_exists('../cache/map'.$id)){
						// Vidage du repertoire de cache
						if(!$dh = @opendir('../cache/map'.$id)){
						erreur(0,'Impossible d\'ouvrir le dossier de cache');
						$erreur=4;
						}
						else{
						while (false !== ($obj = readdir($dh))) {
						if($obj=='.' || $obj=='..') continue;
						if (!@unlink('../cache/map'.$id.'/'.$obj)){
						erreur(0,'Impossible de supprimer un fichier dans le dossier de cache');
						}
						}
						}
						}
						else{
						// Creation du dossier de cache
						if(!mkdir('../cache/map'.$id)){
						$erreur=3;
						erreur(0,'Impossible de creer le dossier cense stocker le cache');
						}
						}*/
						/* Creation de la topo pour chaque camp */
						$black = imagecolorallocate($im, 0, 0, 0);
						imagefilledrectangle($im, 0, 0,$max_X, $max_Y,$black);
						foreach($camps AS $i=>$camp){
							imagepng($im,'../cartes/carto_'.$id.'_'.$i.'.png');
						}
					}
				}
			}
		}
		return $erreur;
	}
	?>