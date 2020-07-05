<?php
$ok=0;
if(isset($_POST['new_perso_ok'])  && isset($_SESSION['com_perso']) && !$_SESSION['com_2persos'])
  {
    if(isset($_POST['pseudoi']) && $_POST['pseudoi'] && !is_numeric($_POST['pseudoi']))
      {
	if(!(exist_in_db('SELECT ID FROM persos WHERE nom="'.post2bdd($_POST['pseudoi']).'" LIMIT 1') ||
	     exist_in_db('SELECT ID FROM compte WHERE login="'.post2bdd($_POST['pseudoi']).'" LIMIT 1')))
	  {
	    request('INSERT
                     INTO `persos`(`PV`,
                                   `PA`,
                                   `PV_max`,
                                   `tir_restants`,
                                   `date_last_tir`,
                                   `PM`,
                                   `date_last_PM`,
                                   `armee`,
                                   `mouchard`,
                                   `compte`,
                                   `nom`,
                                   `message`,
                                   `arme`,
                                   `ordres`,
                                   `implants_dispo`,
                                   `cloned`,
                                   `compagnie`)
                            VALUES(25,
                                   0,
                                   25,
                                   100,
                                   '.$time.',
                                   150,
                                   '.$time.',
                                   '.$perso['armee'].',
                                   1,
                                   '.$_SESSION['com_ID'].',
                                   "'.post2bdd($_POST['pseudoi']).'",
                                   "Pitié je suis nouveau.",
                                   1,
                                   1,
                                   2,
                                   1,
                                   1)');
	      $id=last_id();
	      if($id)
	      {
		request('UPDATE `compte` SET `perso_2`='.$id.' WHERE `ID`='.$_SESSION['com_ID']);
		if(!affected_rows())
		  {
		    add_message(3,'Impossible de finir de créer le perso.');
		    if(!request('DELETE FROM persos WHERE ID='.$id))
		      add_message(3,'Erreur lors de la destruction du perso à moitié créé, veuillez contacter l\'admin.');
		  }
		else
		  {
		    // Modification sur le fofo
		    select_table(1);
		    if(!forum_new_perso($_SESSION['com_ID'],post2bdd($_POST['pseudoi']),$id,$perso['armee']))
		      {
			select_table(0);
			add_message(3,'Impossible d\'ajouter le nouveau perso sur le compte forum.');
			request('UPDATE `compte` SET `perso_2`=0 WHERE `ID`='.$_SESSION['com_ID']);
			if(!affected_rows())
			  add_message(3,'Erreur lors de la suppression de la référence à ce perso au niveau de votre compte, veuillez contacter l\'admin.');
			request('DELETE FROM persos WHERE ID='.$id);
			if(!affected_rows())
			  add_message(3,'Erreur lors de la destruction du perso à moitié créé, veuillez contacter l\'admin.');
		      }
		    else
		      {
			select_table(0);
			$ok=1;
			$_SESSION['com_2persos']=$_SESSION['com_perso'];
			$_SESSION['com_perso']=$id;
			$done=1;
		      }
		  }
	      }
	      else
		add_message(3,'impossible d\'enregistrer le perso en bdd.');
	  }
	else
	  add_message(3,'Pseudo déjà utilisé.');
      }
    else
	add_message(3,'Il faut choisir un pseudo pour votre perso.');
  }
if(!$ok)
{
  echo'<div id="creer_perso">
<p>
Vous n\'avez pas activez votre second perso. Si vous le souhaitez, vous pouvez le faire dés maintenant en lui choisissant un pseudo et en validant.
</p>
<form action="jouer.php" method="post">
<p>
<label for="pseudoi">Pseudo :</label><input type="text" name="pseudoi" id="pseudoi" size="21" maxlength="250" class="inp_text" /><br />
<input name="new_perso_ok" type="submit" value="Activer" />
</p>
</form>
</div>
';
  $exclusif=1;
}
?>
