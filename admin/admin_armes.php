<h2>Gestion des armes</h2>
<?php
//*****************************************************************************
// Création d'une arme.
//*****************************************************************************
if(isset($_POST["new_arme_ok"]))
{
  // Il semble qu'une arme soit à ajouter.
  // Vérifions les infos envoyées.
  $erreur=0;
  if(!(isset($_POST["new_arme_nom"])&&$_POST["new_arme_nom"]))
    {
      // Pas de nom, pas d'arme.
      erreur(0,"Il faut choisir un nom pour l'arme.");
      $erreur=1;
    }
  else
    {
      // On vérifie si le nom n'est pas déjà utilisé.
      if(exist_in_db("SELECT `ID`
                      FROM `armes`
                      WHERE `nom`='".post2bdd($_POST['new_arme_nom'])."'
                      LIMIT 1"))
	{
	  erreur(0,"Nom d'arme déjà utilisé.");
	  $erreur=1;
	}
    }
  // Puis on vérifie le reste :
  // type d'arme existant ?
  if(!(isset($_POST["new_arme_type"])&&is_numeric($_POST["new_arme_type"])&&($_POST["new_arme_type"]>=0)&&($_POST["new_arme_type"]<=9)))
    {
      erreur(0,"Type d'arme incorrect.");
      $erreur=1;
    }
  if(!isset($_POST["new_arme_typem"]) || !is_numeric($_POST["new_arme_typem"]))
    {
      erreur(0,"Il faut choisir un type de munitions.");
      $erreur=1;
    }
     else
       {
	 // Le type de munition choisi existe t'il ?
	 if(!exist_in_db("SELECT `ID`
                          FROM `munars`
                          WHERE `ID`='$_POST[new_arme_typem]'
                          LIMIT 1"))
	   {
	     erreur(0,"Type de munitions inexistant.");
	     $erreur=1;
	   }
       }
  if(!(isset($_POST["new_arme_maxi"])&&is_numeric($_POST["new_arme_maxi"])&&($_POST["new_arme_maxi"]>=0)))
    {
      erreur(0,"Il faut spécifier un nombre maximum de munitions.");
      $erreur=1;
    }
  if(!(isset($_POST["new_arme_portee"])&&is_numeric($_POST["new_arme_portee"])&&($_POST["new_arme_portee"]>0)))
    {
      erreur(0,"Il faut une portée supérieure à 0.");
      $erreur=1;
    }
  if(!(isset($_POST["new_arme_degats"])&&is_numeric($_POST["new_arme_degats"])&&($_POST["new_arme_degats"]>0)))
    {
      erreur(0,"Il faut des dégâts supérieurs à 0.");
      $erreur=1;
    }
  if(!(isset($_POST["new_arme_minp"])&&is_numeric($_POST["new_arme_minp"])&&($_POST["new_arme_minp"]>=0)&&($_POST["new_arme_minp"]<=100)))
    {
      erreur(0,"La précision minimale doit être entre 0 et 100 (compris).");
      $erreur=1;
    }
  if(!(isset($_POST["new_arme_maxp"])&&is_numeric($_POST["new_arme_maxp"])&&($_POST["new_arme_maxp"]>=$_POST["new_arme_minp"])&&($_POST["new_arme_maxp"]<=100)))
    {
      erreur(0,"La précision maximale doit être entre la précision minimale  et 100 (compris).");
      $erreur=1;
    }
  if(!(isset($_POST["new_arme_maxc"])&&is_numeric($_POST["new_arme_degats"])&&($_POST["new_arme_maxc"]>=0)&&($_POST["new_arme_maxc"]<=$_POST["new_arme_degats"])))
    {
      erreur(0,"Le maximum de dégâts pouvant être directement imputés aux points de vie doit être compris entre 0 et le maximum de dégâts de l'arme (compris).");
      $erreur=1;
    }
  if(!(isset($_POST["new_arme_critp"])&&is_numeric($_POST["new_arme_critp"])&&($_POST["new_arme_critp"]>=0)&&($_POST["new_arme_critp"]<=100)))
    {
      erreur(0,"La pourcentage de chance qu'a chaque dégât critique de passer dans les PVs doit être entre 0 et 100 (compris).");
      $erreur=1;
    }
  if(!(isset($_POST["new_arme_crits"])&&is_numeric($_POST["new_arme_crits"])&&($_POST["new_arme_crits"]>=$_POST["new_arme_minp"])&&($_POST["new_arme_crits"]<=101)))
    {
      erreur(0,"Le seuil de critique doit être entre la précision minimale  et 101 (compris).");
      $erreur=1;
    }
  if(!(isset($_POST["new_arme_critpm"])&&is_numeric($_POST["new_arme_critpm"])&&($_POST["new_arme_critpm"]>0)))
    {
      erreur(0,"Il faut que le nombre de PA pour augmenter de 1% les chances de critique soit supérieur à 0.");
      $erreur=1;
    }
  if(!(isset($_POST["new_arme_mun"])&&is_numeric($_POST["new_arme_mun"])&&($_POST["new_arme_mun"]<=$_POST["new_arme_maxi"])))
    {
      erreur(0,"Le nombre de munitions par tir doit être inférieur ou égal au nombre de munitions maximal.");
      $erreur=1;
    }
  if(!(isset($_POST["new_arme_malus"])&&is_numeric($_POST["new_arme_malus"])&&($_POST["new_arme_malus"]>0)))
    {
      erreur(0,"Un malus de 0 ou moins au camouflage pour un tir ? Faut pas abuser non plus !");
      $erreur=1;
    }
  if(!(isset($_POST["new_arme_tirs"])&&is_numeric($_POST["new_arme_tirs"])&&($_POST["new_arme_tirs"]>0)))
    {
      erreur(0,"Il faut spécifier un nombre de tirs.");
      $erreur=1;
    }
  if(!(isset($_POST["new_arme_armure"])&&is_numeric($_POST["new_arme_armure"])&&($_POST["new_arme_armure"]>0)&&($_POST["new_arme_armure"]<=7)))
    {
      erreur(0,"Type d'armure inconnu.");
      $erreur=1;
    }
  if(!(isset($_POST["new_arme_grade"])&&is_numeric($_POST["new_arme_grade"])&&($_POST["new_arme_grade"]>=0)&&($_POST["new_arme_grade"]<=13)))
    {
      erreur(0,"Grade non réglementaire.");
      $erreur=1;
    }
  if(!(isset($_POST["new_arme_lvl"])&&is_numeric($_POST["new_arme_lvl"])&&($_POST["new_arme_lvl"]>=0)&&($_POST["new_arme_lvl"]<=100)))
    {
      erreur(0,"Niveau de compétence inateignable.");
      $erreur=1;
    }
  if(!(isset($_POST["new_arme_visibilite"])&&is_numeric($_POST["new_arme_visibilite"])&&($_POST["new_arme_visibilite"]>=0)&&($_POST["new_arme_visibilite"]<=2)))
    {
      erreur(0,"Problème sur la visibilité de l'arme.");
      $erreur=1;
    }
  if(!(isset($_POST["new_arme_zone"],$_POST["new_arme_diminution"],$_POST["new_arme_touche"],$_POST["new_arme_dimit"],$_POST["new_arme_degat_t"],$_POST["new_arme_tirs_t"],$_POST["new_arme_PM_t"],$_POST["new_arme_poids"])&&is_numeric($_POST["new_arme_zone"])&&is_numeric($_POST["new_arme_diminution"])&&is_numeric($_POST["new_arme_touche"])&&is_numeric($_POST["new_arme_dimit"])&&is_numeric($_POST["new_arme_degat_t"])&&is_numeric($_POST["new_arme_tirs_t"])&&is_numeric($_POST["new_arme_PM_t"])&&is_numeric($_POST["new_arme_poids"])))
    {
      erreur(0,"Données censée être numérique détectée comme ne l'étant pas.");
      $erreur=1;
    }
  if(!$erreur)
    {
      // Pas d'erreur, on peut enregistrer l'arme.
      request("INSERT
               INTO `armes` (`nom`,
                             `type`,
                             `type_munitions`,
                             `max_munitions`,
                             `portee`,
                             `degats`,
                             `precision_min`,
                             `precision_max`,
                             `degat_vie`,
                             `critique`,
                             `seuil_critique`,
                             `pm_critique`,
                             `tir_munars`,
                             `armure`,
                             `grade`,
                             `lvl`,
                             `malus_camou`,
                             `tirs`,
                             `zone`,
                             `diminution`,
                             `touche`,
                             `dimit`,
                             `description`,
                             `secu_degats`,
                             `perte_tirs`,
                             `perte_PM`,
                             `poids`,
                             `dropable`,
                             `visibilite`)
                      VALUES('".post2bdd($_POST['new_arme_nom'])."',
                             '$_POST[new_arme_type]',
                             '$_POST[new_arme_typem]',
                             '$_POST[new_arme_maxi]',
                             '$_POST[new_arme_portee]',
                             '$_POST[new_arme_degats]',
                             '$_POST[new_arme_minp]',
                             '$_POST[new_arme_maxp]',
                             '$_POST[new_arme_maxc]',
                             '$_POST[new_arme_critp]',
                             '$_POST[new_arme_crits]',
                             '$_POST[new_arme_critpm]',
                             '$_POST[new_arme_mun]',
                             '$_POST[new_arme_armure]',
                             '$_POST[new_arme_grade]',
                             '$_POST[new_arme_lvl]',
                             '$_POST[new_arme_malus]',
                             '$_POST[new_arme_tirs]',
                             '$_POST[new_arme_zone]',
                             '$_POST[new_arme_diminution]',
                             '$_POST[new_arme_touche]',
                             '$_POST[new_arme_dimit]',
                             '".post2bdd($_POST['new_arme_desc'])."',
                             '$_POST[new_arme_degat_t]',
                             '$_POST[new_arme_tirs_t]',
                             '$_POST[new_arme_PM_t]',
                             '$_POST[new_arme_poids]',
                             '".(empty($_POST['new_arme_dropable'])?1:0)."',
                             '$_POST[new_arme_visibilite]')");
      $id=last_id();
      if(!$id)
	erreur(0,"Impossible d'enregistrer l'arme.");
      else
	{
	  // L'arme est enregistrée en bdd.
	  $erreur=0;
	  // Upload de l'image.
	  // Ici, on vérifie si le fichier qu'il fallait uploader l'a été.
	  if (!is_uploaded_file($_FILES['new_arme_image']['tmp_name']))
	    {
	      erreur(0,"Erreur d'upload de l'image ou pas d'image uploadée.");
	      $erreur=1;
	    }
	  else
	    if($_FILES['new_arme_image']['type']!="image/gif")
	      {
		erreur(0,"Mauvais type de fichier(".$_FILES['new_arme_image']['type']."), il faut du gif !");
		$erreur=1;
	      }
	    else if (!move_uploaded_file($_FILES['new_arme_image']['tmp_name'], "images/armes/$id.gif"))
	      {
		erreur(0,"Erreur de déplacement de l'image.");
		$erreur=1;
	      }
	  if($erreur)
	    {
	      // Pas d'image, donc on supprime l'arme de la bdd.
	      request("DELETE
                       FROM `armes`
                       WHERE `ID`='$id'
                       LIMIT 1");
	      if(!affected_rows())
		erreur(0,"Impossible de supprimer l'arme de la table des armes, veuillez contacter l'admin.");
	    }
	  else
	    {
	      // On loggue la création.
	      $detail="<ul>
<li>Nom :".post2html($_POST['new_arme_nom'])."</li>
<li>Type : $_POST[new_arme_type]</li>
<li>Munitions : $_POST[new_arme_typem]</li>
<li>Chargeur : $_POST[new_arme_maxi]</li>
<li>Portée : $_POST[new_arme_portee]</li>
<li>Dégâts : $_POST[new_arme_degats]</li>
<li>Précision min : $_POST[new_arme_minp]</li>
<li>Précision max : $_POST[new_arme_maxp]</li>
<li>Critique max : $_POST[new_arme_maxc]</li>
<li>Pourcentage de critique : $_POST[new_arme_critp]</li>
<li>Seuil de critique : $_POST[new_arme_crits]</li>
<li>PM par 1% de critique : $_POST[new_arme_critpm]</li>
<li>Balles par rafale : $_POST[new_arme_mun]</li>
<li>Armure : $_POST[new_arme_armure]</li>
<li>Grade : $_POST[new_arme_grade]</li>
<li>Niveau : $_POST[new_arme_lvl]</li>
<li>Malus de camouflage : $_POST[new_arme_malus]</li>
<li>Tirs : $_POST[new_arme_tirs]</li>
<li>Rayon : $_POST[new_arme_zone]</li>
<li>Diminution des dégâts par case : $_POST[new_arme_diminution]</li>
<li>Précision sur la case touchée : $_POST[new_arme_touche]</li>
<li>Diminution de cette précision : $_POST[new_arme_dimit]</li>
<li>Description : ".post2html($_POST['new_arme_desc'])."</li>
<li>Dégâts de sécurité : $_POST[new_arme_degat_t]</li>
<li>Perte de tirs : $_POST[new_arme_tirs_t]</li>
<li>Perte de PMs : $_POST[new_arme_PM_t]</li>
<li>Poids : $_POST[new_arme_poids]</li>
<li>Visible par : $_POST[new_arme_visibilite]'</li>
</ul>
";
	      console_log('anim_armes',"Création de l'arme : ".post2html($_POST['new_arme_nom']),$detail,0,0);
	      // Upload de l'image de l'arme à terre.
	      if (!is_uploaded_file($_FILES['new_arme_image_t']['tmp_name']))
		erreur(0,"Erreur d'upload de l'image ou pas de nouvelle image uploadée.");
	      else
		if($_FILES['new_arme_image_t']['type']!="image/gif")
		  erreur(0,"Mauvais type de fichier(".$_FILES['new_arme_image_t']['type']."), il faut du gif !");
		else if (!move_uploaded_file($_FILES['new_arme_image_t']['tmp_name'], "images/armes/na$id.gif"))
		  erreur(0,"Erreur de déplacement de l'image.");
	      foreach($_POST as $key=>$value)
		{
		  if(ereg('new_arme_camp_[0-9]+',$key))
		    {
		      $camp_id=explode("_",$key);
		      if(request("INSERT
                               INTO `armes_camps` (`arme`,`camp`)
                                           VALUES ('$id','".$camp_id[3]."')"))
			erreur(0,"Impossible de rendre disponible l'arme pour un camp.");
		    }
		  $_POST[$key]="";
		}
	    }
	}
    }
}
//*****************************************************************************
// Modification d'une arme.
//*****************************************************************************
else if(isset($_POST["mod_arme_ok"]))
{
  // On souhaite modifier une arme.
  $erreur=0;
  if(!(isset($_POST["mod_arme_id"])&&is_numeric($_POST["mod_arme_id"])&&$_POST["mod_arme_id"]))
    {
      erreur(0,"Il faut choisir une arme.");
      $erreur=1;
    }
  else
    {
      // Existe t'elle au moins ?
      if(!exist_in_db("SELECT `ID`
                       FROM `armes`
                       WHERE `ID`='$_POST[mod_arme_id]'
                       LIMIT 1"))
	{
	  erreur(0,"Arme inconnue.");
	  $erreur=1;
	}
    }
  // Vérifions le nom
  if(!(isset($_POST["mod_arme_nom"])&&$_POST["mod_arme_nom"]))
    {
      erreur(0,"Il faut choisir un nom pour l'arme.");
      $erreur=1;
    }
  else
    {
      if(exist_in_db("SELECT `ID`
                      FROM `armes`
                      WHERE `nom`='".post2bdd($_POST['mod_arme_nom'])."'
                        AND `ID`!='$_POST[mod_arme_id]'
                      LIMIT 1"))
	{
	  erreur(0,"Nom d'arme déjà utilisé.");
	  $erreur=1;
	}
    }
  // Puis on vérifie le reste :
  // type d'arme existant ?
  if(!(isset($_POST["mod_arme_type"])&&is_numeric($_POST["mod_arme_type"])&&($_POST["mod_arme_type"]>=0)&&($_POST["mod_arme_type"]<=9)))
    {
      erreur(0,"Type d'arme incorrect.");
      $erreur=1;
    }
  if(!isset($_POST["mod_arme_typem"]) || !is_numeric($_POST["mod_arme_typem"]))
    {
      erreur(0,"Il faut choisir un type de munitions.");
      $erreur=1;
    }
     else
       {
	 // Le type de munition choisi existe t'il ?
	 if(!exist_in_db("SELECT `ID`
                          FROM `munars`
                          WHERE `ID`='$_POST[mod_arme_typem]'
                          LIMIT 1"))
	   {
	     erreur(0,"Type de munitions inexistant.");
	     $erreur=1;
	   }
       }
  if(!(isset($_POST["mod_arme_maxi"])&&is_numeric($_POST["mod_arme_maxi"])&&($_POST["mod_arme_maxi"]>=0)))
    {
      erreur(0,"Il faut spécifier un nombre maximum de munitions.");
      $erreur=1;
    }
  if(!(isset($_POST["mod_arme_portee"])&&is_numeric($_POST["mod_arme_portee"])&&($_POST["mod_arme_portee"]>0)))
    {
      erreur(0,"Il faut une portée supérieure à 0.");
      $erreur=1;
    }
  if(!(isset($_POST["mod_arme_degats"])&&is_numeric($_POST["mod_arme_degats"])&&($_POST["mod_arme_degats"]>0)))
    {
      erreur(0,"Il faut des dégâts supérieurs à 0.");
      $erreur=1;
    }
  if(!(isset($_POST["mod_arme_minp"])&&is_numeric($_POST["mod_arme_minp"])&&($_POST["mod_arme_minp"]>=0)&&($_POST["mod_arme_minp"]<=100)))
    {
      erreur(0,"La précision minimale doit être entre 0 et 100 (compris).");
      $erreur=1;
    }
  if(!(isset($_POST["mod_arme_maxp"])&&is_numeric($_POST["mod_arme_maxp"])&&($_POST["mod_arme_maxp"]>=$_POST["mod_arme_minp"])&&($_POST["mod_arme_maxp"]<=100)))
    {
      erreur(0,"La précision maximale doit être entre la précision minimale  et 100 (compris).");
      $erreur=1;
    }
  if(!(isset($_POST["mod_arme_maxc"])&&is_numeric($_POST["mod_arme_degats"])&&($_POST["mod_arme_maxc"]>=0)&&($_POST["mod_arme_maxc"]<=$_POST["mod_arme_degats"])))
    {
      erreur(0,"Le maximum de dégâts pouvant être directement imputés aux points de vie doit être compris entre 0 et le maximum de dégâts de l'arme (compris).");
      $erreur=1;
    }
  if(!(isset($_POST["mod_arme_critp"])&&is_numeric($_POST["mod_arme_critp"])&&($_POST["mod_arme_critp"]>=0)&&($_POST["mod_arme_critp"]<=100)))
    {
      erreur(0,"La pourcentage de chance qu'a chaque dégât critique de passer dans les PVs doit être entre 0 et 100 (compris).");
      $erreur=1;
    }
  if(!(isset($_POST["mod_arme_crits"])&&is_numeric($_POST["mod_arme_crits"])&&($_POST["mod_arme_crits"]>=$_POST["mod_arme_minp"])&&($_POST["mod_arme_crits"]<=101)))
    {
      erreur(0,"Le seuil de critique doit être entre la précision minimale  et 101 (compris).");
      $erreur=1;
    }
  if(!(isset($_POST["mod_arme_critpm"])&&is_numeric($_POST["mod_arme_critpm"])&&($_POST["mod_arme_critpm"]>0)))
    {
      erreur(0,"Il faut que le nombre de PA pour augmenter de 1% les chances de critique soit supérieur à 0.");
      $erreur=1;
    }
  if(!(isset($_POST["mod_arme_mun"])&&is_numeric($_POST["mod_arme_mun"])&&($_POST["mod_arme_mun"]<=$_POST["mod_arme_maxi"])))
    {
      erreur(0,"Le nombre de munitions par tir doit être inférieur ou égal au nombre de munitions maximal.");
      $erreur=1;
    }
  if(!(isset($_POST["mod_arme_malus"])&&is_numeric($_POST["mod_arme_malus"])&&($_POST["mod_arme_malus"]>0)))
    {
      erreur(0,"Un malus de 0 ou moins au camouflage pour un tir ? Faut pas abuser non plus !");
      $erreur=1;
    }
  if(!(isset($_POST["mod_arme_tirs"])&&is_numeric($_POST["mod_arme_tirs"])&&($_POST["mod_arme_tirs"]>0)))
    {
      erreur(0,"Il faut spécifier un nombre de tirs.");
      $erreur=1;
    }
  if(!(isset($_POST["mod_arme_armure"])&&is_numeric($_POST["mod_arme_armure"])&&($_POST["mod_arme_armure"]>0)&&($_POST["mod_arme_armure"]<=7)))
    {
      erreur(0,"Type d'armure inconnu.");
      $erreur=1;
    }
  if(!(isset($_POST["mod_arme_grade"])&&is_numeric($_POST["mod_arme_grade"])&&($_POST["mod_arme_grade"]>=0)&&($_POST["mod_arme_grade"]<=13)))
    {
      erreur(0,"Grade non réglementaire.");
      $erreur=1;
    }
  if(!(isset($_POST["mod_arme_lvl"])&&is_numeric($_POST["mod_arme_lvl"])&&($_POST["mod_arme_lvl"]>=0)&&($_POST["mod_arme_lvl"]<=100)))
    {
      erreur(0,"Niveau de compétence inateignable.");
      $erreur=1;
    }
  if(!(isset($_POST["mod_arme_visibilite"])&&is_numeric($_POST["mod_arme_visibilite"])&&($_POST["mod_arme_visibilite"]>=0)&&($_POST["mod_arme_visibilite"]<=2)))
    {
      erreur(0,"Problème sur la visibilité de l'arme.");
      $erreur=1;
    }
  if(!(isset($_POST["mod_arme_zone"],$_POST["mod_arme_diminution"],$_POST["mod_arme_touche"],$_POST["mod_arme_dimit"],$_POST["mod_arme_degat_t"],$_POST["mod_arme_tirs_t"],$_POST["mod_arme_PM_t"],$_POST["mod_arme_poids"])&&is_numeric($_POST["mod_arme_zone"])&&is_numeric($_POST["mod_arme_diminution"])&&is_numeric($_POST["mod_arme_touche"])&&is_numeric($_POST["mod_arme_dimit"])&&is_numeric($_POST["mod_arme_degat_t"])&&is_numeric($_POST["mod_arme_tirs_t"])&&is_numeric($_POST["mod_arme_PM_t"])&&is_numeric($_POST["mod_arme_poids"])))
    {
      erreur(0,"Données censées être numériques détectées comme ne l'étant pas.");
      $erreur=1;
    }
  if(!$erreur)
    {
      if(!request("DELETE
                  FROM `armes_camps`
                  WHERE `arme`='$_POST[mod_arme_id]'"))
	{
	  erreur(0,"Impossible de modifier les camps ayant accés à cette arme.");
	}
      else
	{
	  request("OPTIMIZE TABLE `armes_camps`");
	  foreach($_POST as $key=>$value)
	    if(ereg('mod_arme_camp_[0-9]+',$key))
	      {
		$camp_id=explode("_",$key);
		if(!request("INSERT
                             INTO `armes_camps` (`arme`,`camp`)
                             VALUES ('$_POST[mod_arme_id]','".$camp_id[3]."')"))
		  erreur(0,"Impossible de rendre disponible cette arme à un camp.");
	      }
	}
      request("UPDATE `armes`
               SET `nom`='".post2bdd($_POST['mod_arme_nom'])."',
                   `type`='$_POST[mod_arme_type]',
                   `type_munitions`='$_POST[mod_arme_typem]',
                   `max_munitions`='$_POST[mod_arme_maxi]',
                   `portee`='$_POST[mod_arme_portee]',
                   `degats`='$_POST[mod_arme_degats]',
                   `precision_min`='$_POST[mod_arme_minp]',
                   `precision_max`='$_POST[mod_arme_maxp]',
                   `degat_vie`='$_POST[mod_arme_maxc]',
                   `critique`='$_POST[mod_arme_critp]',
                   `seuil_critique`='$_POST[mod_arme_crits]',
                   `pm_critique`='$_POST[mod_arme_critpm]',
                   `tir_munars`='$_POST[mod_arme_mun]',
                   `armure`='$_POST[mod_arme_armure]',
                   `grade`='$_POST[mod_arme_grade]',
                   `lvl`='$_POST[mod_arme_lvl]',
                   `tirs`='$_POST[mod_arme_tirs]',
                   `zone`='$_POST[mod_arme_zone]',
                   `diminution`='$_POST[mod_arme_diminution]',
                   `touche`='$_POST[mod_arme_touche]',
                   `dimit`='$_POST[mod_arme_dimit]',
                   `malus_camou`='$_POST[mod_arme_malus]',
                   `secu_degats`='$_POST[mod_arme_degat_t]',
                   `perte_tirs`='$_POST[mod_arme_tirs_t]',
                   `perte_PM`='$_POST[mod_arme_PM_t]',
                   `poids`='$_POST[mod_arme_poids]',
                   `description`='".post2bdd($_POST['mod_arme_desc'])."',
                   `dropable`='".(empty($_POST['mod_arme_dropable'])?1:0)."',
                   `visibilite`='$_POST[mod_arme_visibilite]'
               WHERE `ID`='$_POST[mod_arme_id]'
               LIMIT 1");
      if(!affected_rows())
	erreur(0,"Impossible de modifier l'arme.");
      else
	{
	  // Loggage
	  $detail="<ul>
<li>Nom :".post2html($_POST['mod_arme_nom'])."</li>
<li>Type : $_POST[mod_arme_type]</li>
<li>Munitions : $_POST[mod_arme_typem]</li>
<li>Chargeur : $_POST[mod_arme_maxi]</li>
<li>Portée : $_POST[mod_arme_portee]</li>
<li>Dégâts : $_POST[mod_arme_degats]</li>
<li>Précision min : $_POST[mod_arme_minp]</li>
<li>Précision max : $_POST[mod_arme_maxp]</li>
<li>Critique max : $_POST[mod_arme_maxc]</li>
<li>Pourcentage de critique : $_POST[mod_arme_critp]</li>
<li>Seuil de critique : $_POST[mod_arme_crits]</li>
<li>PM par 1% de critique : $_POST[mod_arme_critpm]</li>
<li>Balles par rafale : $_POST[mod_arme_mun]</li>
<li>Armure : $_POST[mod_arme_armure]</li>
<li>Grade : $_POST[mod_arme_grade]</li>
<li>Niveau : $_POST[mod_arme_lvl]</li>
<li>Malus de camouflage : $_POST[mod_arme_malus]</li>
<li>Tirs : $_POST[mod_arme_tirs]</li>
<li>Rayon : $_POST[mod_arme_zone]</li>
<li>Diminution des dégâts par case : $_POST[mod_arme_diminution]</li>
<li>Précision sur la case touchée : $_POST[mod_arme_touche]</li>
<li>Diminution de cette précision : $_POST[mod_arme_dimit]</li>
<li>Description : ".post2html($_POST['mod_arme_desc'])."</li>
<li>Dégâts de sécurité : $_POST[mod_arme_degat_t]</li>
<li>Perte de tirs : $_POST[mod_arme_tirs_t]</li>
<li>Perte de PMs : $_POST[mod_arme_PM_t]</li>
<li>Poids : $_POST[mod_arme_poids]</li>
<li>Visible par : $_POST[mod_arme_visibilite]'</li>
</ul>
";
	  console_log('anim_armes',"Modification d'une arme, nouveau nom : ".post2html($_POST['mod_arme_nom']),$detail,0,0);
	  // Upload de l'image.
	  // Ici, on vérifie si le fichier qu'il fallait uploader l'a été.
	  if (!is_uploaded_file($_FILES['mod_arme_image']['tmp_name']))
	    erreur(0,"Erreur d'upload de l'image ou pas de nouvelle image uploadée.");
	  else
	    if($_FILES['mod_arme_image']['type']!="image/gif")
	      erreur(0,"Mauvais type de fichier(".$_FILES['mod_arme_image']['type']."), il faut du gif !");
	    else if (!move_uploaded_file($_FILES['mod_arme_image']['tmp_name'], "images/armes/$_POST[mod_arme_id].gif"))
	      erreur(0,"Erreur de déplacement de l'image.");
	  // Upload de l'image de l'arme à terre.
	  if (!is_uploaded_file($_FILES['mod_arme_image_t']['tmp_name']))
	    erreur(0,"Erreur d'upload de l'image ou pas de nouvelle image uploadée.");
	  else
	    if($_FILES['mod_arme_image_t']['type']!="image/gif")
	      erreur(0,"Mauvais type de fichier(".$_FILES['mod_arme_image_t']['type']."), il faut du gif !");
	    else if (!move_uploaded_file($_FILES['mod_arme_image_t']['tmp_name'], "images/armes/t_$_POST[mod_arme_id].gif"))
	      erreur(0,"Erreur de déplacement de l'image.");
	  foreach($_POST as $key=>$value)
	    $_POST[$key]="";
	}
    }
}
//*****************************************************************************
// Suppression d'une arme.
//*****************************************************************************
else if(isset($_POST["del_arme_ok"],$_POST['del_arme_id']) && is_numeric($_POST['del_arme_id']))
{
  $arme=my_fetch_array("SELECT `nom`
                        FROM `armes`
                        WHERE `ID`='$_POST[del_arme_id]'
                        LIMIT 1");
  if($arme[0])
    echo'<form method="post" action="anim.php?admin_armes">
 <h3>Confirmation :</h3>
 <p>Êtes vous sûr de vouloir supprimer cette arme ('.bdd2html($arme[1][0]).')?<br />
 '.form_hidden("id",$_POST['del_arme_id']).'
 '.form_submit("del_arme_no","Non").'&nbsp;&nbsp;&nbsp;&nbsp;
 '.form_submit("del_arme_yes","Oui").'
 </p>
 </form>
 <hr />
 ';
  else
    erreur(0,"Arme inconnue.");
}
else if(isset($_POST["del_arme_yes"],$_POST['id'])&&is_numeric($_POST['id']))
{
  // Suppression de la base des types de munitions.
  request("DELETE
           FROM `armes`
           WHERE `ID`='$_POST[id]'
           LIMIT 1");
  if(affected_rows())
    {
      console_log('anim_armes',"Modification de l'arme d'ID : $_POST[id]",'',0,0);
      request("OPTIMIZE TABLE `armes`");
    }
  else
    erreur(0,"Impossible de supprimer cette arme.");
}

//*****************************************************************************
// Préparation du formulaire.
//*****************************************************************************

$armes=my_fetch_array("SELECT *
                       FROM `armes` ORDER BY `nom` ASC");
$camps=my_fetch_array("SELECT `ID`, `nom`
                       FROM `camps`
                       WHERE ID!='0'
                       ORDER BY `nom` ASC");
$str_camps1=$str_camps2='';
$i=1;
$liste_armes[0]=0;
while(isset($armes[$i+1]))
{
  $liste_armes[0]++;
  $liste_armes[$i][0]=$armes[$i]["ID"];
  $liste_armes[$i][1]=$armes[$i]["nom"];
  $i++;
}
$script='<script type="text/javascript">
function afficher_arme()
{
 if(!document.getElementById)
   return;
 ';
   for($i=1;$i<=$armes[0];$i++)
{
	$armures=dispo_armure($armes[$i]['armure']);
	$armures=$armures[0]+2*$armures[1]+4*$armures[2];
	$script.='  if(document.getElementById("mod_arme_id").value=='.$armes[$i]['ID'].')
    {
      mod_arme_nom="'.(bdd2js($armes[$i]['nom'])).'";
      mod_arme_type='.$armes[$i]['type'].';
      mod_arme_typem='.$armes[$i]['type_munitions'].';
      mod_arme_maxi='.$armes[$i]['max_munitions'].';
      mod_arme_portee='.$armes[$i]['portee'].';
      mod_arme_degats='.$armes[$i]['degats'].';
      mod_arme_minp='.$armes[$i]['precision_min'].';
      mod_arme_maxp='.$armes[$i]['precision_max'].';
      mod_arme_maxc='.$armes[$i]['degat_vie'].';
      mod_arme_critp='.$armes[$i]['critique'].';
      mod_arme_crits='.$armes[$i]['seuil_critique'].';
      mod_arme_critpm='.$armes[$i]['pm_critique'].';
      mod_arme_mun='.$armes[$i]['tir_munars'].';
      mod_arme_tirs='.$armes[$i]['tirs'].';
      mod_arme_zone='.$armes[$i]['zone'].';
      mod_arme_diminution='.$armes[$i]['diminution'].';
      mod_arme_touche='.$armes[$i]['touche'].';
      mod_arme_dimit='.$armes[$i]['dimit'].';
      mod_arme_armure='.$armures.';
      mod_arme_grade='.$armes[$i]['grade'].';
      mod_arme_lvl='.$armes[$i]['lvl'].';
      mod_arme_degat_t='.$armes[$i]['secu_degats'].';
      mod_arme_tirs_t='.$armes[$i]['perte_tirs'].';
      mod_arme_PM_t='.$armes[$i]['perte_PM'].';
      mod_arme_poids='.$armes[$i]['poids'].';
      mod_arme_malus='.$armes[$i]['malus_camou'].';
      mod_arme_dropable='.$armes[$i]['dropable'].';
      visibilite='.$armes[$i]['visibilite'].';
      src='.$armes[$i]['ID'].';
      desc="'.(bdd2js($armes[$i]['description'])).'";
'; 
	for($j=1;$j<=$camps[0];$j++)
	  {
	    $script.='    var camp_'.$camps[$j]['ID'].'=';
	    if(exist_in_db("SELECT `camp`
                            FROM `armes_camps`
                            WHERE `arme`='".$armes[$i]['ID']."'
                              AND `camp`='".$camps[$j]['ID']."'"))
	      $script.='1; 
';
	    else
	      $script.='0;
'; 
	  }
$script.='    }
 ';
      }
    $script.='  document.getElementById("mod_arme_nom").value=mod_arme_nom;
  document.getElementById("mod_arme_maxi").value=mod_arme_maxi;
  document.getElementById("mod_arme_portee").value=mod_arme_portee;
  document.getElementById("mod_arme_degats").value=mod_arme_degats;
  document.getElementById("mod_arme_minp").value=mod_arme_minp;
  document.getElementById("mod_arme_maxp").value=mod_arme_maxp;
  document.getElementById("mod_arme_maxc").value=mod_arme_maxc;
  document.getElementById("mod_arme_critp").value=mod_arme_critp;
  document.getElementById("mod_arme_crits").value=mod_arme_crits;
  document.getElementById("mod_arme_critpm").value=mod_arme_critpm;
  document.getElementById("mod_arme_mun").value=mod_arme_mun;
  document.getElementById("mod_arme_tirs").value=mod_arme_tirs;
  document.getElementById("mod_arme_zone").value=mod_arme_zone;
  document.getElementById("mod_arme_diminution").value=mod_arme_diminution;
  document.getElementById("mod_arme_touche").value=mod_arme_touche;
  document.getElementById("mod_arme_dimit").value=mod_arme_dimit;
  document.getElementById("mod_arme_maxc").value=mod_arme_maxc;
  document.getElementById("mod_arme_type").value=mod_arme_type;
  document.getElementById("mod_arme_typem").value=mod_arme_typem;
  document.getElementById("mod_arme_armure").value=mod_arme_armure;
  document.getElementById("mod_arme_grade").value=mod_arme_grade;
  document.getElementById("mod_arme_lvl").value=mod_arme_lvl;
  document.getElementById("mod_arme_degat_t").value=mod_arme_degat_t;
  document.getElementById("mod_arme_tirs_t").value=mod_arme_tirs_t;
  document.getElementById("mod_arme_PM_t").value=mod_arme_PM_t;
  document.getElementById("mod_arme_poids").value=mod_arme_poids;
  document.getElementById("mod_arme_malus").value=mod_arme_malus;
  document.getElementById("mod_arme_visibilite").value=visibilite;
  document.getElementById("mod_arme_desc").innerHTML=desc;
  document.getElementById("mod_arme_desc").value=desc;
  document.getElementById("mod_arme_dropable").checked=mod_arme_dropable?"":"checked";
  document.getElementById("mod_arme_image_image").src="images/armes/"+ src +".gif";
  mod_autonomie();
  mod_PMs();
';
    for($i=1;$i<=$camps[0];$i++)
      {
	$script.='  document.getElementById("mod_arme_camp_'.$camps[$i]['ID'].'").checked=camp_'.$camps[$i]['ID'].'?"checked":"";
 ';
	$str_camps1.=form_check(bdd2html($camps[$i]['nom']),"new_arme_camp_".$camps[$i]['ID']).'<br />
 ';
	$str_camps2.=form_check(bdd2html($camps[$i]['nom']),"mod_arme_camp_".$camps[$i]['ID']).'<br />
';
      }
$script.='}
'.(isset($_POST["mod_arme_ok"])?'':'afficher_arme();').' 
</script>
';
    $munars=my_fetch_array("SELECT `ID`,`nom` FROM `munars`");
    $type_armes=array(10,
		      array(0,"Assaut"),
		      array(1,"Mitrailleuse"),
		      array(2,"Sniper"),
		      array(3,"Lance-flammes"),
		      array(4,"Lance-roquettes"),
		      array(5,"Mécano"),
		      array(6,"Fusil à pompe"),
		      array(7,"Corps à corps"),
		      array(8,"Médecin"),
		      array(9,"Pistolet")); 

    $armures=array(7,
		   array(1,"Légère"),
		   array(2,"Moyenne"),
		   array(3,"Légère et moyenne"),
		   array(4,"Lourde"),
		   array(5,"Légère et lourde"),
		   array(6,"Moyenne et loudre"),
		   array(7,"Toutes")); 
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
require_once('../inits/camps.php');
    $grades=array(14,
		  array(0,numero_camp_grade(0,0)),
		  array(1,numero_camp_grade(0,1)),
		  array(2,numero_camp_grade(0,2)),
		  array(3,numero_camp_grade(0,3)),
		  array(4,numero_camp_grade(0,4)),
		  array(5,numero_camp_grade(0,5)),
		  array(6,numero_camp_grade(0,6)),
		  array(7,numero_camp_grade(0,7)),
		  array(8,numero_camp_grade(0,8)),
		  array(9,numero_camp_grade(0,9)),
		  array(10,numero_camp_grade(0,10)),
		  array(11,numero_camp_grade(0,11)),
		  array(12,numero_camp_grade(0,12)),
		  array(13,numero_camp_grade(0,13)));
    $visible=array(3,
		   array(0,"personne"),
		   array(1,"tout le monde"),
		   array(2,"les camps qui y ont accés"));

//*****************************************************************************
// Affichage du formulaire.
//*****************************************************************************

    echo'<form method="post" action="anim.php?admin_armes" enctype="multipart/form-data">
<p>
<h3>Créer une arme :</h3>
'.form_text("Nom : ","new_arme_nom","","").'<br />
'.form_select("Type : ","new_arme_type",$type_armes,"").'<br />
'.form_select("Type de munitions : ","new_arme_typem",$munars,"").'<br />
'.form_text("Maximum de munitions : ","new_arme_maxi","3","new_autonomie();").'<br />
'.form_text("Portée : ","new_arme_portee","2","").'<br />
'.form_text("Zone d'effet : ","new_arme_zone","2","").' (LR seulement)<br />
'.form_text("Dégâts : ","new_arme_degats","2","").'<br /> 
'.form_text("Diminution des dégâts par case : ","new_arme_diminution","3","").'% (LR et LF seulement)<br />
'.form_text("Précision minimale : ","new_arme_minp","3","new_PMs();").'<br /> 
'.form_text("Précision maximale : ","new_arme_maxp","3","new_PMs();").'<br /> 
'.form_text("Critique max : ","new_arme_maxc","2","").'<br /> 
'.form_text("Pourcentage de critique : ","new_arme_critp","3","").'<br /> 
'.form_text("Seuil de critique : ","new_arme_crits","2","").'<br />
'.form_text("Chances de toucher : ","new_arme_touche","3","").' (LR seulement)<br /> 
'.form_text("Diminution des chances de toucher par case : ","new_arme_dimit","3","").' (LR seulement)<br /> 
'.form_text("PM par 1% de critique : ","new_arme_critpm","3","").'<br /> 
'.form_text("Munitions par tir : ","new_arme_mun","3","new_autonomie();").'<br /> 
'.form_text("Nombre de tirs : ","new_arme_tirs","2","new_autonomie();new_PMs();").'<br /> 
'.form_text("Malus au camouflage : ","new_arme_malus","3","").'<br />
'.form_select("Armure nécessaire : ","new_arme_armure",$armures,"").'<br /> 
'.form_select("Grade nécessaire : ","new_arme_grade",$grades,"").'<br /> 
'.form_select("Niveau nécessaire : ","new_arme_lvl",$lvl,"").'<br />
'.form_image("Image : ","new_arme_image").'<br />
'.form_image("Image à terre : ","new_arme_image_t").'<br />
'.form_text("Dégâts de sécurité : ","new_arme_degat_t","2","").'<br />
'.form_text("Perte de tirs lors de la mise dans l'inventaire : ","new_arme_tirs_t","2","").'%<br />
'.form_text("Perte de mouvements lors de la mise dans l'inventaire : ","new_arme_PM_t","2","").'<br />
'.form_text("Poids : ","new_arme_poids","2","").'<br />
'.form_textarea("Description : ","new_arme_desc",2,25).'<br /> 
 <label for="new_arme_autonomie">Autonomie : </label><input type="text" id="new_arme_autonomie" readonly="readonly" /><label for="new_arme_autonomie"> tours</label><br />
 <input type="text" id="new_arme_PMs" readonly="readonly" /><label for="new_arme_PMs"> PMs par 5% de précision.</label><br >
 '.$str_camps1.'
 '.form_select("Visible dans la liste des armes par : ","new_arme_visibilite",$visible,"").'<br />
 '.form_check('Ne tombe pas à terre ?','new_arme_dropable').'<br />
'. form_submit("new_arme_ok","Créer").'
 <h3>Modifier une arme :</h3>
 '.form_select("Arme : ","mod_arme_id",$liste_armes,"afficher_arme();").' 
 '.form_text("Nom : ","mod_arme_nom","","").'<br />
 '.form_select("Type : ","mod_arme_type",$type_armes,"").'<br />
 '.form_select("Type de munitions : ","mod_arme_typem",$munars,"").'<br />
 '.form_text("Maximum de munitions : ","mod_arme_maxi","3","mod_autonomie();").'<br />
 '.form_text("Portée : ","mod_arme_portee","2","").'<br />
 '.form_text("Zone d'effet : ","mod_arme_zone","2","").' (LR seulement)<br />
'.form_text("Dégâts : ","mod_arme_degats","2","").'<br /> 
'.form_text("Diminution des dégâts par case : ","mod_arme_diminution","3","").'% (LR et LF seulement)<br />
 '.form_text("Précision minimale : ","mod_arme_minp","3","mod_PMs();").'<br /> 
 '.form_text("Précision maximale : ","mod_arme_maxp","3","mod_PMs();").'<br /> 
 '.form_text("Critique max : ","mod_arme_maxc","2","").'<br /> 
 '.form_text("Pourcentage de critique : ","mod_arme_critp","3","").'<br /> 
 '.form_text("Seuil de critique : ","mod_arme_crits","2","").'<br /> 
'.form_text("Chances de toucher : ","mod_arme_touche","3","").' (LR seulement)<br /> 
'.form_text("Diminution des chances de toucher par case : ","mod_arme_dimit","3","").' (LR seulement)<br /> 
 '.form_text("PM par 1% de critique : ","mod_arme_critpm","3","").'<br /> 
 '.form_text("Munitions par tir : ","mod_arme_mun","3","mod_autonomie();").'<br /> 
 '.form_text("Nombre de tirs : ","mod_arme_tirs","2","mod_autonomie();mod_PMs();").'<br /> 
 '.form_text("Malus au camouflage : ","mod_arme_malus","3","").'<br /> 
 '.form_select("Armure nécessaire : ","mod_arme_armure",$armures,"").'<br /> 
 '.form_select("Grade nécessaire : ","mod_arme_grade",$grades,"").'<br /> 
 '.form_select("Niveau nécessaire : ","mod_arme_lvl",$lvl,"").'<br />
 '.form_image("Image : ","mod_arme_image").'<br />
'.form_image("Image à terre : ","mod_arme_image_t").'<br />
'.form_text("Dégâts de sécurité : ","mod_arme_degat_t","2","").'<br />
'.form_text("Perte de tirs lors de la mise dans l'inventaire : ","mod_arme_tirs_t","2","").'%<br />
'.form_text("Perte de mouvements lors de la mise dans l'inventaire : ","mod_arme_PM_t","2","").'<br />
'.form_text("Poids : ","mod_arme_poids","2","").'<br />
 '.form_textarea("Description : ","mod_arme_desc",2,25).'<br /> 
 <label for="mod_arme_autonomie">Autonomie : </label><input type="text" id="mod_arme_autonomie" readonly="readonly" /><label for="mod_arme_autonomie"> tours</label><br />
 <input type="text" id="mod_arme_PMs" readonly="readonly" /><label for="mod_arme_PMs"> PMs par 5% de précision.</label><br >
 '.$str_camps2.'
 '.form_check('Ne tombe pas à terre ?','mod_arme_dropable').'<br />
 '.form_select("Visible dans la liste des armes par : ","mod_arme_visibilite",$visible,"").'<br />
 '.form_submit("mod_arme_ok","Modifier").'
 <h3>Supprimer une arme : </h3>
 '.form_select("Arme : ","del_arme_id",$liste_armes,"").'<br />
 '.form_submit("del_arme_ok","Supprimer").'
 </p>
 </form>
 '.$script;
?>
