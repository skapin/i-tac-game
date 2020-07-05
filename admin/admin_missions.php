<h2>Gestion des missions</h2>
<?php
if(isset($_POST["new_mission_ok"])){
  $erreur=0;
  // Nouvelle mission ajoutée.
  // Vérification des infos.
  if(!isset($_POST["new_mission_nom"]) || !$_POST["new_mission_nom"]){
    erreur(0,"Il faut choisir un nom pour la mission.");
    $erreur=1;
  }
  else if(exist_in_db("SELECT `ID`
                       FROM `missions`
                       WHERE `nom`='".post2bdd($_POST['new_mission_nom'])."'
                       LIMIT 1")){
    erreur(0,"Nom déjà utilisé pour une autre mission.");
    $erreur=1;
  }
  if(!$erreur){
    // Pas d'erreur, on enregistre la mission.
    request("INSERT
               INTO `missions` (`nom`,
                                `duree`,
                                `description`,
                                `relocalisation`)
                        VALUES ('".post2bdd($_POST['new_mission_nom'])."',
                                '".post2bdd($_POST['new_mission_duree'])."',
                                '".post2bdd($_POST['new_mission_desc'])."',
                                '".post2bdd($_POST['new_mission_reloc'])."')");
    $last_id=last_id();
    if(!$last_id)
      erreur(0,"Impossible d'enregistrer la mission dans la bdd.");
    else{
      $detail = "<ul>
<li>ID : ".$last_id."</li>
<li>Nom : ".post2html($_POST['new_mission_duree'])."</li>
<li>Durée : ".post2html($_POST['new_mission_duree'])."</li>
<li>Description : ".post2html($_POST['new_mission_desc'])."</li>
<li>Relocalisation : ".post2html($_POST['new_mission_reloc'])."</li> 	
</ul>	
"; 	
      console_log('anim_missions',"Création de la mission ".post2html($_POST['new_mission_nom']),$detail,0,0);
		
      // La mission a été enregistrée, on peut l'ajouter comme accessible
      //  aux divers camps.
      foreach($_POST as $key=>$value)
	if(ereg("new_mission_acces_[0-9]+",$key)){
	  // Un camp au moins qui a accés à cette mission.
	  // On isole son identifiant puis on teste tout.
	  $camp=explode("_",$key);
	  $id=$camp[3];
	  if(!(isset($_POST["new_mission_mini_$id"],$_POST["new_mission_maxi_$id"],$_POST["new_mission_grademin_$id"],$_POST["new_mission_grademax_$id"])&&is_numeric($_POST["new_mission_mini_$id"])&&is_numeric($_POST["new_mission_maxi_$id"])&&is_numeric($_POST["new_mission_grademin_$id"])&&is_numeric($_POST["new_mission_grademax_$id"]))){
	    erreur(0,"Valeur de type invalide.");
	  }
	  else{
	    if(!exist_in_db("SELECT `ID`
                                     FROM `camps`
                                     WHERE `ID`='$id'"))
	      erreur(0,"Identifiant de camp inexistant.");
	    else{
	      if(!request("INSERT
                                     INTO `missions_camps` (`mission`,
                                                            `camp`,
                                                            `nbr_mini`,
                                                            `nbr_maxi`,
                                                            `grade_mini`,
                                                            `grade_maxi`,
                                                            `description`)
                                                     VALUES('$last_id',
                                                            '$id',
                                                            '".$_POST["new_mission_mini_$id"]."',
                                                            '".$_POST["new_mission_maxi_$id"]."',
                                                            '".$_POST["new_mission_grademin_$id"]."',
                                                            '".$_POST["new_mission_grademax_$id"]."',
                                                            '".post2bdd($_POST["new_mission_desc_$id"])."')")){
		erreur(0,"Impossible d'activer l'accés de cette mission au camp ayant pour ID \"$id\"");
	      }
	      else {
		$detail = "<ul>
<li>Mission : ".$last_id."</li>
<li>Camp : ".$id."</li>
<li>Nombre minimum : ".$_POST["new_mission_mini_$id"]."</li>
<li>Nombre maximum : ".$_POST["new_mission_maxi_$id"]."</li>
<li>Grade minimum : ".$_POST["new_mission_grademin_$id"]."</li> 	
<li>Grade maximum : ".$_POST["new_mission_grademax_$id"]."</li> 	
<li>Description : ".post2html($_POST["new_mission_desc_$id"])."</li> 	
</ul>	
"; 	
  	  console_log('anim_missions',"Attribution de la mission ".post2html($_POST['new_mission_nom'])." au camp ".$id,$detail,0,0);
	      }
	    }
	  }
	}
      // Puis on peut traiter l'image envoyée s'il y en a eu une.
      if (!(isset($_FILES['new_mission_img'])&&is_uploaded_file($_FILES['new_mission_img']['tmp_name']))){
	erreur(0,"Pas d'image spécifiée ou erreur d'upload.");
	$erreur=1;
      }
      else if($_FILES['new_arme_img']['type']!="image/gif" && $_FILES['new_arme_img']['type']!="image/x-png"){
	erreur(0,"Mauvais type de fichier(".$_FILES['new_arme_image']['type']."), il faut du gif ou du png!");
	$erreur=1;
      }
      else if (!move_uploaded_file($_FILES['new_arme_img']['tmp_name'], "images/missions/$last_id.png")){
	erreur(0,"Erreur de déplacement de l'image.");
	$erreur=1;
      }
    }
  }
}
else if(isset($_POST["mod_mission_ok"],$_POST['mod_mission_id'])&&is_numeric($_POST['mod_mission_id'])){
  $erreur=0;
  // Mission modifiée.
  // Vérification des infos.
  $mission=my_fetch_array("SELECT `debut`,active
                               FROM `missions`
                               WHERE `ID`='$_POST[mod_mission_id]'
                               LIMIT 1");
  if(!$mission[0]){
    $erreur=1;
    erreur(0,"Identifiant de mission inconnu.");
  }
  if(!isset($_POST["mod_mission_nom"]) || !$_POST["mod_mission_nom"]){
    erreur(0,"Il faut choisir un nom pour la mission.");
    $erreur=1;
  }
  else if(exist_in_db("SELECT `ID`
                           FROM `missions`
                           WHERE `nom`='".post2bdd($_POST['mod_mission_nom'])."'
                             AND `ID`!='$_POST[mod_mission_id]'
                           LIMIT 1")){
    erreur(0,"Nom déjà utilisé pour une autre mission.");
    $erreur=1;
  }
  if(!$erreur){
    // Pas d'erreur, on modifie la mission.
    request("UPDATE `missions`
                   SET `nom`='".post2bdd($_POST['mod_mission_nom'])."',
                       `description`='".post2bdd($_POST['mod_mission_desc'])."',
                       `active`='".post_on('mod_mission_go')."',
                       `ouverte`='".post_on('mod_mission_insc')."',
                       `relocalisation`='".post2bdd($_POST['mod_mission_reloc'])."',
                       `duree`='".($_POST['mod_mission_duree']*3600)."'
  ".(!$mission[1]['debut']&&$_POST['mod_mission_duree']&&isset($_POST['mod_mission_go'])?", debut='".time()."'":'')."
                       WHERE `ID`='$_POST[mod_mission_id]'");
    if(!affected_rows()){
      erreur(0,"Impossible de modifier la mission dans la bdd.");
      $erreur=1;
    }
    else{
      $detail = "<ul>
<li>ID : ".$_POST['mod_mission_id']."</li>
<li>Nom : ".text2html($_POST['mod_mission_nom'])."</li>
<li>Active : ".isset($_POST['mod_mission_go'])."</li>
<li>Ouverte : ".isset($_POST['mod_mission_insc'])."</li>
<li>Durée : ".($_POST['mod_mission_duree']*3600)."</li>";
      if (!$mission[1]['debut']&&$_POST['mod_mission_duree']&&isset($_POST['mod_mission_go'])){ 
	$detail .= "<li>Debut : ".time()."</li>";
      }
      $detail .= "<li>Description : ".text2html($_POST['mod_mission_desc'])."</li>
<li>Relocalisation : ".text2html($_POST['mod_mission_reloc'])."</li> 	
</ul>	
"; 
      console_log('anim_missions',
		  "Modification de la mission ".text2html($_POST['mod_mission_nom']),$detail,0,0);
      // Si on vient de fermer une mission et qu'il y avait des gens dessus, on les ejecte.
      if($mission[1]['active'] && empty($_POST['mod_mission_go'])){
	// recuperation de la liste des cartes.
	$cartes=my_fetch_array('SELECT `ID`
                               FROM `cartes`
                               WHERE `mission`='.$_POST['mod_mission_id']);
	$plop='';
	for($i=1;$i<=$cartes[0];$i++){
	  if($plop){
	    $plop.=' OR ';
	  }
	  $plop.='map='.$cartes[$i]['ID'];
	}
	if($plop){
	  $time=time();
	  request('UPDATE  `persos`
           SET `date_lost_PV`=0,
               `PV`=25+5*`imp_PV`,
               `tir_restants`=100,
               `date_last_tir`='.$time.',
               `date_last_reparation`='.$time.',
               `PM`=100,
               `date_last_PM`='.$time.',
               `date_last_mouv`='.$time.',
               `date_last_recuptir`='.$time.',
               `X`=0,
               `Y`=0,
               `map`=0,
               `date_last_shot`=0,
               `sprints`=0,
               `mission`=0,
               `relocalisation`=0,
               `date_last_update`='.$time.',
               `camouflage`=0
           WHERE '.$plop);
	}
      }
      // On supprime toutes les occurences de cette mission
      // dans la table missions_camps.
      if(!request("DELETE
                           FROM `missions_camps`
                           WHERE `mission`='$_POST[mod_mission_id]'")){
	$erreur=1;
	erreur(0,"Impossible de modifier les correspondances entre cette mission et les camps qui y ont accés.");
      }
    }
  }
  if(!$erreur){
    // La mission a été enregistrée, on peut l'ajouter comme accessible
    //  aux divers camps.
    foreach($_POST as $key=>$value)
      if(ereg("mod_mission_acces_[0-9]+",$key)){
	// Un camp au moins qui a accés à cette mission.
	// On isole son identifiant puis on teste tout.
	$camp=explode("_",$key);
	$id=$camp[3];
	if(!(isset($_POST["mod_mission_mini_$id"],$_POST["mod_mission_maxi_$id"],$_POST["mod_mission_grademin_$id"],$_POST["mod_mission_grademax_$id"])&&is_numeric($_POST["mod_mission_mini_$id"])&&is_numeric($_POST["mod_mission_maxi_$id"])&&is_numeric($_POST["mod_mission_grademin_$id"])&&is_numeric($_POST["mod_mission_grademax_$id"])))
	  {
	    erreur(0,"Valeur de type invalide.");
		  }
	else
	  {
	    if(!exist_in_db("SELECT `ID` FROM `camps` WHERE `ID`='$id'"))
		      erreur(0,"Identifiant de camp inexistant.");
	    else
	      {
		if(!request("INSERT
                                     INTO missions_camps (`mission`,
                                                          `camp`,
                                                          `nbr_mini`,
                                                          `nbr_maxi`,
                                                          `grade_mini`,
                                                          `grade_maxi`,
                                                          `description`)
                                                   VALUES('$_POST[mod_mission_id]',
                                                          '$id',
                                                          '".$_POST["mod_mission_mini_$id"]."',
                                                          '".$_POST["mod_mission_maxi_$id"]."',
                                                          '".$_POST["mod_mission_grademin_$id"]."',
                                                          '".$_POST["mod_mission_grademax_$id"]."',
                                                          '".$_POST["mod_mission_desc_$id"]."')"))
			  {
			    erreur(0,"Impossible d'activer l'accés de cette mission au camp ayant pour ID \"$id\".");
			  }
			  else 
			  	{
			  			   $detail = "<ul>
<li>Mission : ".$_POST['mod_mission_id']."</li>
<li>Camp : ".$id."</li>
<li>Nombre minimum : ".$_POST["mod_mission_mini_$id"]."</li>
<li>Nombre maximum : ".$_POST["mod_mission_maxi_$id"]."</li>
<li>Grade minimum : ".$_POST["mod_mission_grademin_$id"]."</li> 	
<li>Grade maximum : ".$_POST["mod_mission_grademax_$id"]."</li> 	
<li>Description : ".$_POST["mod_mission_desc_$id"]."</li> 	
</ul>	
"; 	
  	  console_log('anim_missions',"Attribution de la mission modifiée ".$_POST['mod_mission_nom']." au camp ".$id,$detail,0,0);
				}
		      }
		  }
	      }
	  // Puis on peut traiter l'image envoyée s'il y en a eu une.
	  if (!isset($_FILES['mod_mission_img'])||!is_uploaded_file($_FILES['mod_mission_img']['tmp_name']))
	    {
	      erreur(0,"Pas d'image spécifiée ou erreur d'upload.");
	      $erreur=1;
	    }
	  else if($_FILES['mod_arme_img']['type']!="image/gif" && $_FILES['mod_arme_img']['type']!="image/x-png")
	    {
	      erreur(0,"Mauvais type de fichier(".$_FILES['mod_arme_image']['type']."), il faut du gif ou du png !");
	      $erreur=1;
	    }
	  else if (!move_uploaded_file($_FILES['mod_arme_img']['tmp_name'], "images/missions/$_POST[mod_mission_id].png"))
	    {
	      erreur(0,"Erreur de déplacement de l'image.");
	      $erreur=1;
	      }
	}
}
else if(isset($_POST['del_mission_ok'],$_POST['del_mission_id']) && is_numeric($_POST['del_mission_id'])){
  $mission=my_fetch_array("SELECT `nom`
                           FROM `missions`
                           WHERE `ID`='$_POST[del_mission_id]'
                           LIMIT 1");
  if($mission[0])
    echo'<form method="post" action="anim.php?admin_missions">
 <p>
 Êtes vous sûr de vouloir supprimer la mission '.$mission[1]['nom'].' ?<br />
 '.form_hidden("id",$_POST['del_mission_id']).' 
 '.form_submit("del_mission_no","Non").'&nbsp;&nbsp;&nbsp;&nbsp;
 '.form_submit("del_mission_yes","Oui").' 
 </p>
 </form>
 ';
  else
    erreur(0,"Identifiant de mission inconnu.");
}
else if(isset($_POST['del_mission_yes'],$_POST['id']) && is_numeric($_POST['id'])){
  request("DELETE
           FROM `missions`
           WHERE `ID`='$_POST[id]'");
  if(affected_rows()){
    console_log('anim_missions',"Suppression de la mission ".$_POST['id'],$detail,0,0);
    request("OPTIMIZE TABLE `missions`");
    if(!request("DELETE
                   FROM `missions_camps`
                   WHERE `mission`='$_POST[id]'"))
      erreur(0,"Impossible de supprimer les liaisons entre cette missions et les camps qui y avaient accés.");
    else
      request("OPTIMIZE TABLE `missions_camps`");
    request("UPDATE persos
               SET mission='0',
                   map='0',
                   X='0',
                   Y= '0'
               WHERE mission='$_POST[id]'");
  }
  else
    erreur(0,"Impossible de supprimer la mission."); 
}
    // Préparation du formulaire.
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
    $camps=my_fetch_array("SELECT `ID`,`nom` FROM `camps` WHERE ID!='0' ORDER BY `ID` ASC");
    $table1='<table>
 <tr><th>Camp</th><th>mission accessible ?</th><th>minimum de soldat</th><th>maximum de soldat</th><th>grade minimum</th><th>grade maximum</th><th>description</th></tr>
 ';
    $table2=$table1;
    for($i=1;$i<=$camps[0];$i++)
      {
	$id=$camps[$i]['ID'];
	$_POST["new_mission_grademax_$id"]=isset($_POST["new_mission_grademax_$id"])?$_POST["new_mission_grademax_$id"]:13;
	$_POST["mod_mission_grademax_$id"]=isset($_POST["mod_mission_grademax_$id"])?$_POST["mod_mission_grademax_$id"]:13;
	$table1.='<tr>
 <td>'.text2html($camps[$i]['nom']).'</td>
 <td>'.form_check("","new_mission_acces_$id").'</td>
 <td>'.form_text("","new_mission_mini_$id","","").'</td>
 <td>'.form_text("","new_mission_maxi_$id","","").'</td>
 <td>'.form_select("","new_mission_grademin_$id",$grades,"").'</td>
 <td>'.form_select("","new_mission_grademax_$id",$grades,"").'</td>
 <td>'.form_textarea("","new_mission_desc_$id","2","25").'</td>
 </tr> 
 ';
	$table2.='<tr>
 <td>'.text2html($camps[$i]['nom']).'</td>
 <td>'.form_check("","mod_mission_acces_$id").'</td>
 <td>'.form_text("","mod_mission_mini_$id","","").'</td>
 <td>'.form_text("","mod_mission_maxi_$id","","").'</td>
 <td>'.form_select("","mod_mission_grademin_$id",$grades,"").'</td>
 <td>'.form_select("","mod_mission_grademax_$id",$grades,"").'</td>
 <td>'.form_textarea("","mod_mission_desc_$id","2","25").'</td>
 </tr>
 ';
      }
    $table1.='</table>
 ';
    $table2.='</table>
 ';
    $script='<script type="text/javascript">
 function afficher_mission()
 {
  if(!document.getElementById)
    return;
 ';
    $missions=my_fetch_array("SELECT * FROM `missions` WHERE ID!='0' ORDER BY `ID` ASC");
    for($i=1;$i<=$missions[0];$i++)
      {
	$script.='  if(document.getElementById("mod_mission_id").value=='.$missions[$i]['ID'].')
  {
    nom="'.text2js($missions[$i]['nom']).'";
    description="'.text2js($missions[$i]['description']).'";
    image="images/missions/'.$missions[$i]['ID'].'.png";
    inscr='.$missions[$i]['ouverte'].';
    go='.$missions[$i]['active'].';
    duree='.($missions[$i]['duree']/3600).'; 
    reloc='.($missions[$i]['relocalisation']).'; 
 ';
	$missions_camps=my_fetch_array("SELECT * FROM missions_camps WHERE mission='".$missions[$i]['ID']."' ORDER BY camp ASC");
	for($j=1;$j<=$missions_camps[0];$j++)
	  {
	    $script.='    document.getElementById("mod_mission_acces_'.$missions_camps[$j]['camp'].'").checked="checked";
    document.getElementById("mod_mission_mini_'.$missions_camps[$j]['camp'].'").value='.$missions_camps[$j]['nbr_mini'].';
    document.getElementById("mod_mission_maxi_'.$missions_camps[$j]['camp'].'").value='.$missions_camps[$j]['nbr_maxi'].';
    document.getElementById("mod_mission_grademin_'.$missions_camps[$j]['camp'].'").value='.$missions_camps[$j]['grade_mini'].';
    document.getElementById("mod_mission_grademax_'.$missions_camps[$j]['camp'].'").value='.$missions_camps[$j]['grade_maxi'].';
    document.getElementById("mod_mission_desc_'.$missions_camps[$j]['camp'].'").value="'.text2js($missions_camps[$j]['description']).'";
 ';
	  }
	$script.='  }
  document.getElementById("mod_mission_nom").value=nom;
  document.getElementById("mod_mission_desc").value=description;
  document.getElementById("mod_mission_duree").value=duree;
  document.getElementById("mod_mission_reloc").value=reloc;
  document.getElementById("mod_mission_image").src=image;
  document.getElementById("mod_mission_insc").checked=inscr?"checked":"";
  document.getElementById("mod_mission_go").checked=go?"checked":"";
 ';
      }
    $script.='}
 '.(isset($_POST["mod_mission_ok"])?'':'afficher_mission();').'
 </script>
 ';
    // Affichage du formulaire.
    echo'<form method="post" action="anim.php?admin_missions" enctype="multipart/form-data">
 <p>
 <h3>Créer un mission:</h3>
 '.form_text("Nom : ","new_mission_nom","","").'<br />
 '.form_textarea("Description : ","new_mission_desc",2,25).'<br />
 '.form_text("Durée (en heure, laisser à 0 pour une durée infinie) : ","new_mission_duree","","4").'<br />
 '.form_text("Temps de relocalisation (en heures): ","new_mission_reloc","","4").'<br />
 '.form_image("Image : ","new_mission_image").'</p>
 '.$table1.'<p>
 '.form_submit("new_mission_ok","Créer").'<hr />
 <h3>Modifier un mission:</h3>
 '.form_select("Mission : ","mod_mission_id",$missions,"afficher_mission();").'<br />
 '.form_check("Inscriptions ouvertes ?","mod_mission_insc").'<br />
 '.form_check("Mission lancée ?","mod_mission_go").'<br />
 '.form_text("Nom : ","mod_mission_nom","","").'<br />
 '.form_textarea("Description : ","mod_mission_desc",2,25).'<br />
 '.form_text("Durée (en heure, laisser à 0 pour une durée infinie) : ","mod_mission_duree","","4").'<br />
 '.form_text("Temps de relocalisation (en heures): ","mod_mission_reloc","","4").'<br />
 '.form_image("Image : ","mod_mission_image").'</p>
 '.$table2.'<p>
 '.form_submit("mod_mission_ok","Modifier").'<hr />
 <h3>Supprimer un mission:</h3>
 '.form_select("Mission : ","del_mission_id",$missions,"").'<br />
 '.form_submit("del_mission_ok","Supprimer").'</p>
 </form>
 '.$script;
?>
