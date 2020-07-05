<h2>Gestion des types de munitions</h2>
	<?php
//*****************************************************************************
// Cr&eacute;ation de munitions.
//*****************************************************************************

if(isset($_POST["new_munar_ok"]))
{
	$erreur=0;
	if(!(isset($_POST["new_munar_nom"])&&$_POST["new_munar_nom"]))
	{
		erreur(0,"Il faut choisir un nom pour les munitions.");
		$erreur=1;
	}
	if(!(isset($_POST["new_munar_poids"])&&is_numeric($_POST["new_munar_poids"])&&($_POST["new_munar_poids"]>=0)))
	{
		erreur(0,"Il faut choisir un nombre maximum de munitions.");
		$erreur=1;
	}
	if(!$erreur)
	{
		request("INSERT
			INTO `munars` (`nom`,`poids`)
			VALUES('".text2bdd($_POST['new_munar_nom'])."',
			'$_POST[new_munar_poids]')");
		$id=last_id();
		if(!$id)
			erreur(0,"Impossible d'enregistrer le type de munition.");
		else
		{
			$erreur=0;
			// Upload de l'image.
			// Ici, on v&eacute;rifie si le fichier qu'il fallait uploader l'a &eacute;t&eacute;.
			if (!is_uploaded_file($_FILES['new_munar_image']['tmp_name']))
			{
				erreur(0,"Erreur d'upload de l'image.");
				$erreur=1;
			}
			else
				if($_FILES['new_munar_image']['type']!="image/gif")
			{
				erreur(0,"Mauvais type de fichier(".$_FILES['new_munar_image']['type']."), il faut du gif !");
				$erreur=1;
			}
			else if (!move_uploaded_file($_FILES['new_munar_image']['tmp_name'], "images/munitions/$id.gif"))
			{
				erreur(0,"Erreur de d&eacute;placement de l'image.");
				$erreur=1;
			}
			else
				add_message(0,"Image upload&eacute;e.");
			if($erreur)
			{
				request("DELETE
					FROM `munars`
					WHERE `ID`='$id'
					LIMIT 1");
				if(!affected_rows())
					erreur(0,"Impossible de supprimer le type de munitions dans la table munars, veuillez contacter l'admin.");
			}
			else 
			{
				// ici commence le log :
				$detail = "<ul>
					<li>ID : ".$id."</li>
					<li>Nom : ".text2html($_POST['new_munar_nom'])."</li>
					<li>Poids : ".$_POST['new_munar_poids']."</li>
					</ul>
					"; 
				console_log('anim_munars',"Cr&eacute;ation d'une munition d'ID ".$id,$detail,0,0);
				// fin du log     
			}
		}
	}
}

//*****************************************************************************
// Modification de munitions.
//*****************************************************************************

else if(isset($_POST["mod_munar_ok"],$_POST['mod_munar_id'])&&is_numeric($_POST['mod_munar_id']))
{
	$erreur=0;
	// On v&eacute;rifie si le type de munitions existe.
	if(!exist_in_db("SELECT ID
		FROM munars
		WHERE ID='$_POST[mod_munar_id]'
		LIMIT 1"))
	{
		erreur(0,"Identifiant de munition inconnu.");
		$erreur=1;
	}
	if(!(isset($_POST["mod_munar_nom"])&&$_POST["mod_munar_nom"]))
	{
		erreur(0,"Il faut choisir un nom pour les munitions.");
		$erreur=1;
	}
	if(!(isset($_POST["mod_munar_poids"])&&is_numeric($_POST["mod_munar_poids"])&&($_POST["mod_munar_poids"]>=0)))
	{
		erreur(0,"Il faut choisir un nombre maximum de munitions.");
		$erreur=1;
	}
	if(!$erreur)
	{
		request("UPDATE `munars`
			SET `nom`='".text2bdd($_POST['mod_munar_nom'])."',
			`poids`='$_POST[mod_munar_poids]'
			WHERE `ID`='$_POST[mod_munar_id]'
			LIMIT 1");
		if(!affected_rows())
			erreur(0,"Impossible de modifier le type de munition.");
		else
		{
			// ici commence le log :
			$detail = "<ul>
				<li>ID : ".$_POST['mod_munar_id']."</li>
				<li>Nom : ".text2html($_POST['mod_munar_nom'])."</li>
				<li>Poids : ".$_POST['mod_munar_poids']."</li>
				</ul>
				"; 
			console_log('anim_munars',"Modification d'une munition d'ID ".$_POST['mod_munar_id'],$detail,0,0);
			// fin du log  

			// Upload de l'image.
			// Ici, on v&eacute;rifie si le fichier qu'il fallait uploader l'a &eacute;t&eacute;.
			if (!is_uploaded_file($_FILES['mod_munar_image']['tmp_name']))
				erreur(0,"Erreur d'upload de l'image");
			else
				if($_FILES['mod_munar_image']['type']!="image/gif")
				erreur(0,"Mauvais type de fichier(".$_FILES['mod_munar_image']['type']."), il faut du gif !");
			else
				if (!move_uploaded_file($_FILES['mod_munar_image']['tmp_name'], "images/munitions/$_POST[mod_munar_id].gif"))
				erreur(0,"Erreur de d&eacute;placement de l'image.");
			else
				add_message(0,"Nouvelle image upload&eacute;e.");
		}
	}
}

//*****************************************************************************
// Suppression de munitions.
//*****************************************************************************

else if(isset($_POST["del_munar_ok"],$_POST['del_munar_id'],$_POST['del_munar_id2'])&&is_numeric($_POST['del_munar_id'])&&is_numeric($_POST['del_munar_id2']))
{
	$erreur=0;
	if($_POST['del_munar_id']==$_POST['del_munar_id2'])
	{
		erreur(0,"Il faut sp&eacute;cifier un nouveau type de munitions qui remplacera celui-ci dans les armes qui l'utilisent.");
		$erreur=1;
	}
	$munar=my_fetch_array("SELECT `nom`,`ID`
		FROM `munars`
		WHERE `ID`='$_POST[del_munar_id]'
		OR `ID`='$_POST[del_munar_id2]'
		LIMIT 2");
	if($munar[0]!=2 && !$erreur)
	{
		erreur(0,"Type de munitions inconnu.");
		$erreur=1;
	}
	if(!$erreur)
		echo'<form method="post" action="anim.php?admin_munars">
		<h3>Confirmation :</h3>
		<p>Êtes vous sûr de vouloir supprimer ce type de munitions ('.($munar[1][1]==$_POST['del_munar_id']?bdd2html($munar[1][0]):bdd2html($munar[2][0])).') pour le remplacer par '.($munar[1][1]==$_POST['del_munar_id']?bdd2html($munar[2][0]):bdd2html($munar[1][0])).'?<br />
		'.form_hidden("id",$_POST['del_munar_id']).'
		'.form_hidden("id2",$_POST['del_munar_id2']).'
		'.form_submit("del_munar_no","Non").'&nbsp;&nbsp;&nbsp;&nbsp;
	'.form_submit("del_munar_yes","Oui").' 
		</p>
		</form>
		<hr />
		';
}
else if(isset($_POST["del_munar_yes"],$_POST['id'],$_POST['id2'])&&is_numeric($_POST['id'])&&is_numeric($_POST['id2']))
{
	$erreur=0;
	// On rev&eacute;rifie les donn&eacute;es.
	$munars=my_fetch_array("SELECT `nom`
		FROM `munars`
		WHERE `ID`='$_POST[id]'
		OR `ID`='$_POST[id2]'
		LIMIT 2");
	if($munars[0]!=2)
	{
		erreur(0,"Type de munitions inconnu ou m&ecric;me types de munitions s&eacute;lectionn&eacute;s.");
		$erreur=1;
	}
	// Suppression de la base des types de munitions.
	if(!$erreur)
	{
		request("DELETE
			FROM `munars`
			WHERE `ID`='$_POST[id]'");
		if(affected_rows())
		{
			// ici commence le log :
			$detail = "<ul>
				<li>Munition supprim&eacute;e : ".$_POST['id']."</li>
				<li>Munition de remplacement : ".$_POST['id2']."</li>
				</ul>
				"; 
			console_log('anim_munars',"suppression d'une munition d'ID ".$_POST['id'],$detail,0,0);
			// fin du log 

			request("OPTIMIZE TABLE `munars`");
			if(!request("UPDATE `armes`
				SET `type_munitions`='$_POST[id2]'
				WHERE `type_munitions`='$_POST[id]'"))
				erreur(0,"Impossible de modifier le type de munitions de certaines armes, veuillez contacter l'admin.");
		}
		else
			erreur(0,"Impossible de supprimer ce type de munitions.");
	}
}

//*****************************************************************************
// Pr&eacute;paration du formulaire.
//*****************************************************************************

$munars=my_fetch_array("SELECT * FROM `munars`");
$str_munars="";
$script='<script type="text/javascript">
	function afficher_munar()
{
	if(!document.getElementById)
		return;
	';
	for($i=1;$i<=$munars[0];$i++)
	{
		$str_munars.='<option value="'.$munars[$i]['ID'].'">'.post2text($munars[$i]['nom']).'</option>
			';
		$script.='  if(document.getElementById("mod_munar_id").value=='.$munars[$i]['ID'].')
		{
			mod_munar_nom="'.text2js($munars[$i]['nom']).'";
			mod_munar_poids='.$munars[$i]['poids'].';
			src="'.$munars[$i]['ID'].'";
		}
		';
	}
	$script.='  document.getElementById("mod_munar_nom").value=mod_munar_nom;
	document.getElementById("mod_munar_poids").value=mod_munar_poids;
	document.getElementById("mod_munar_image_image").src="images/munitions/"+ src +".gif";
}
'.(isset($_POST["mod_munar_ok"])?'':'afficher_munar();').'
	</script>
	';

//*****************************************************************************
// Affichage du formulaire.
//*****************************************************************************

echo'<form method="post" action="anim.php?admin_munars" enctype="multipart/form-data">
	<p>
	<h3>Cr&eacute;er un type de munition :</h3>
	'.form_text("Nom : ","new_munar_nom","","").'<br /> 
	'.form_text("Poids par munition : ","new_munar_poids","3","").'<br /> 
	'.form_image("Image : ","new_munar_image").'<br /> 
	'.form_submit("new_munar_ok","Cr&eacute;er").'<br /> 
	<h3>Modifier un type de munitions :</h3>
	'.form_select("Type de munitions : ","mod_munar_id",$munars,"afficher_munar();").'<br />
	'.form_text("Nom : ","mod_munar_nom","","").'<br /> 
	'.form_text("Poids par munition : ","mod_munar_poids","3","").'<br /> 
	'.form_image("Image : ","mod_munar_image").'<br /> 
	'.form_submit("mod_munar_ok","Modifier").'<br /> 
	<h3>D&eacute;truire le type de munitions : </h3>
	<select id="del_munar_id" name="del_munar_id">
	'.$str_munars.'</select> et remplacer par : <select id="del_munar_id2" name="del_munar_id2">
	'.$str_munars.'</select><br />
	<input type="submit" value="Supprimer" name="del_munar_ok" id="del_munar_ok" />
	</p>
	</form>
	'.$script;
?>
