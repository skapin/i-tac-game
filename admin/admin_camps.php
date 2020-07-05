<h2>Gestion des camps</h2>
<?php
//*****************************************************************************
// Création d'un camp
//*****************************************************************************
if(isset($_POST['new_camp_ok'])){
  // On vient de poster un nouveau camp.
  // Vérifions qu'il n'y ait pas d'erreur.
  $erreur=0;
  // Tout d'abord le nom.
  if(isset($_POST['new_camp_nom'])&&$_POST['new_camp_nom']){
    if(exist_in_db('SELECT `ID`
                      FROM `camps`
                      WHERE `nom`="'.post2bdd($_POST['new_camp_nom']).'"
                      LIMIT 1')){
      $erreur=1;
      erreur(0,'Nom de camp déjà utilisé.');
    }
  }
  else{
    $erreur=1;
    erreur(0,'Vous devez donner un nom au camp.');
  }
  // Puis les initiales.
  if(isset($_POST['new_camp_init'])&&$_POST['new_camp_init']){
    if(exist_in_db("SELECT `ID`
                      FROM `camps`
                      WHERE `initiale`='".post2bdd($_POST["new_camp_init"])."'
                      LIMIT 1")){
      $erreur=1;
      erreur(0,"Initiales de camp déjà utilisées.");
    }
  }
  else{
    $erreur=1;
    erreur(0,"Vous devez choisir une ou deux lettres servant à identifier ce camp.");
  }
  // Enfin on verifie les valeurs RVB des petits carrés les représentant
  // sur la map.
  if(!(isset($_POST["new_camp_R"],$_POST["new_camp_V"],$_POST["new_camp_B"])&&is_numeric($_POST["new_camp_R"])&&is_numeric($_POST["new_camp_V"])&&is_numeric($_POST["new_camp_B"]))||!($_POST["new_camp_R"]>=0&&$_POST["new_camp_R"]<=255&&$_POST["new_camp_V"]>=0&&$_POST["new_camp_V"]<=255&&$_POST["new_camp_B"]>=0&&$_POST["new_camp_B"]<=255)){
    $erreur=1;
    erreur(0,"Il faut que les valeurs en Rouge Vert et Bleu sur la map soient comprises entre 0 et 255 inclus.");
  }
  else if(exist_in_db("SELECT `ID`
                       FROM `camps`
                       WHERE `map_R`='$_POST[new_camp_R]'
                         AND `map_V`='$_POST[new_camp_V]'
                         AND `map_B`='$_POST[new_camp_B]'
                       LIMIT 1")){
    $erreur=1;
    erreur(0,"Couleur sur la map déjà utilisée.");
  }
  if(isset($_POST["new_camp_ouvert"]))
    $ouvert=1;
  else
    $ouvert=0;
  if(isset($_POST["new_camp_visible"]))
    $visible=1;
  else
    $visible=0;
  // S'il n'y a pas eu d'erreur, on peut enregistrer ces infos.
  if(!$erreur){
    request("INSERT
               INTO `camps` (`nom`,
                             `initiale`,
                             `map_R`,
                             `map_V`,
                             `map_B`,
                             `camou_R`,
                             `camou_V`,
                             `camou_B`,
                             `visible`,
                             `ouvert`,
                             `grade_0`,
                             `grade_1`,
                             `grade_2`,
                             `grade_3`,
                             `grade_4`,
                             `grade_5`,
                             `grade_6`,
                             `grade_7`,
                             `grade_8`,
                             `grade_9`,
                             `grade_10`,
                             `grade_11`,
                             `grade_12`,
                             `grade_13`,
                             `grade_spec_3`,
                             `grade_spec_2`,
                             `grade_spec_1`)
                      VALUES('".post2bdd($_POST['new_camp_nom'])."',
                             '".post2bdd($_POST['new_camp_init'])."',
                             '$_POST[new_camp_R]',
                             '$_POST[new_camp_V]',
                             '$_POST[new_camp_B]',
                             '$_POST[new_camp_CR]',
                             '$_POST[new_camp_CV]',
                             '$_POST[new_camp_CB]',
                             '$visible',
                             '$ouvert',
                             '".post2bdd($_POST['new_grade_0'])."',
                             '".post2bdd($_POST['new_grade_1'])."',
                             '".post2bdd($_POST['new_grade_2'])."',
                             '".post2bdd($_POST['new_grade_3'])."',
                             '".post2bdd($_POST['new_grade_4'])."',
                             '".post2bdd($_POST['new_grade_5'])."',
                             '".post2bdd($_POST['new_grade_6'])."',
                             '".post2bdd($_POST['new_grade_7'])."',
                             '".post2bdd($_POST['new_grade_8'])."',
                             '".post2bdd($_POST['new_grade_9'])."',
                             '".post2bdd($_POST['new_grade_10'])."',
                             '".post2bdd($_POST['new_grade_11'])."',
                             '".post2bdd($_POST['new_grade_12'])."',
                             '".post2bdd($_POST['new_grade_13'])."',
                             '".post2bdd($_POST['new_grade_spec_3'])."',
                             '".post2bdd($_POST['new_grade_spec_2'])."',
                             '".post2bdd($_POST['new_grade_spec_1'])."')");
    $id=last_id();
    if(!$id){
      erreur(0,"Impossible d'enregistrer le camp dans la bdd.");
      $erreur=1;
    }
    else{
      echo forumNewCamp($id,post2bdd($_POST['new_camp_nom']),'');
      // loggage
/*
	  $detail="<ul>
<li>Nom : ".post2html($_POST['new_camp_nom'])."</li>
<li>Initiale : ".post2html($_POST['new_camp_init'])."</li>
<li>Couleur : $_POST[new_camp_couleur]</li>
<li>Couleur des camous : $_POST[new_camp_camou]</li>
<li>R : $_POST[new_camp_R]</li>
<li>G : $_POST[new_camp_V]</li>
<li>B : $_POST[new_camp_B]</li>
<li>camou R : $_POST[new_camp_CR]</li>
<li>camou G : $_POST[new_camp_CV]</li>
<li>camou B : $_POST[new_camp_CB]</li>
<li>Nom des armures légères : ".post2html($_POST['new_camp_LE'])."</li>
<li>Nom des armures moyennes : ".post2html($_POST['new_camp_MO'])."</li>
<li>Nom des armures lourdes : ".post2html($_POST['new_camp_LO'])."</li>
<li>Visible : $visible</li>
<li>Ouvert : $ouvert</li>
<li>Grade 1 : ".post2html($_POST['new_grade_0'])."</li>
<li>Grade 2 : ".post2html($_POST['new_grade_1'])."</li>
<li>Grade 3 : ".post2html($_POST['new_grade_2'])."</li>
<li>Grade 4 : ".post2html($_POST['new_grade_3'])."</li>
<li>Grade 5 : ".post2html($_POST['new_grade_4'])."</li>
<li>Grade 6 : ".post2html($_POST['new_grade_5'])."</li>
<li>Grade 7 : ".post2html($_POST['new_grade_6'])."</li>
<li>Grade 8 : ".post2html($_POST['new_grade_7'])."</li>
<li>Grade 9 : ".post2html($_POST['new_grade_8'])."</li>
<li>Grade 10 : ".post2html($_POST['new_grade_9'])."</li>
<li>Grade 11 : ".post2html($_POST['new_grade_10'])."</li>
<li>Grade 12 : ".post2html($_POST['new_grade_11'])."</li>
<li>Grade 13 : ".post2html($_POST['new_grade_12'])."</li>
<li>Grade 14 : ".post2html($_POST['new_grade_13'])."</li>
<li>Grade de général en chef : ".post2html($_POST['new_grade_spec_3'])."</li>
<li>Grade de général : ".post2html($_POST['new_grade_spec_2'])."</li>
<li>Grade de colonel : ".post2html($_POST['new_grade_spec_1'])."</li>
<li>Grade  : $_POST[new_camp_font]</li>
</ul>
";
 console_log('anim_camps',$visible?"Création du camp : ".post2html($_POST['new_camp_nom']):"",$detail,0,0);*/
/*	  require_once('sources/forum.php');
	  if(!forum_new_camp($id,post2bdd($_POST['new_camp_nom'])))
	    {
	      erreur(0,"Impossible de créer les forums associés à ce camp. On va tenter de revenir en arrière.");
	      request("DELETE FROM camps WHERE ID='$id' LIMIT 1");
	      if(!affected_rows())
		erreur(0,"Il y a eu une erreur lors du retour, demandez à l'admin de virer le camp directement en bdd.");
	      $erreur=1;
	    }
	  else
	    }*/
	  write_camps();
	}
    }
  if(!$erreur)
    foreach($_POST as $key)
      $_POST[$key]='';
}

//*****************************************************************************
// Modification d'un camp.
//*****************************************************************************

else if(isset($_POST["mod_camp_ok"],$_POST['mod_camp_id'])&&is_numeric($_POST['mod_camp_id']))
{
  // On veut modifier un camp.
  // Vérifions qu'il n'y ait pas d'erreur.
  $erreur=0;
  // Tout d'abord l'ID posté.
  $nom_camp=my_fetch_array("SELECT `nom`
                   FROM `camps`
                   WHERE `ID`='$_POST[mod_camp_id]'
                   LIMIT 1");
  if(!$nom_camp[0])
    {
      $erreur=1;
      erreur(0,"Identifiant de camp inconnu.");
    }
  // Tout d'abord le nom.
  if(isset($_POST["mod_camp_nom"])&&$_POST["mod_camp_nom"])
    {
      if(exist_in_db("SELECT `ID`
                      FROM `camps`
                      WHERE `nom`='".post2bdd($_POST['mod_camp_nom'])."'
                        AND `ID`!='$_POST[mod_camp_id]'
                      LIMIT 1"))
	{
	  $erreur=1;
	  erreur(0,"Nom de camp déjà utilisé par un autre camp.");
	}
    }
  else
    {
      $erreur=1;
      erreur(0,"Vous devez donner un nom au camp.");
    }
  // Puis les initiales.
  if(isset($_POST["mod_camp_init"])&&$_POST["mod_camp_init"])
    {
      if(exist_in_db("SELECT `ID`
                      FROM `camps`
                      WHERE `initiale`='".post2bdd($_POST['mod_camp_init'])."'
                        AND `ID`!='$_POST[mod_camp_id]'
                      LIMIT 1"))
	{
	  $erreur=1;
	  erreur(0,"Initiales de camp déjà utilisé par un autre camp.");
	}
    }
  else
    {
      $erreur=1;
      erreur(0,"Vous devez choisir une ou deux lettres servant à identifier ce camp.");
    }
    
  // Maintenant la couleur des cases sur lesquelles sont les persos.
  // Il faut que ce soit en héxa et qu'il n'y ait pas de doublets.
  /*  if(!ctype_xdigit($_POST["mod_camp_couleur"]))
    {
      $erreur=1;
      erreur(0,"La couleur des persos doit être fournie en hexadécimal.");
    }
  else if(exist_in_db("SELECT `ID`
                       FROM `camps`
                       WHERE (`couleur`='$_POST[mod_camp_couleur]'
                           OR `couleur_camou`='$_POST[mod_camp_couleur]')
                         AND `ID`!='$_POST[mod_camp_id]'
                       LIMIT 1"))
    {
      $erreur=1;
      erreur(0,"Couleur déjà utilisé par un autre camp.");
    }
  // Idem pour la couleur lorsqu'ils sont camous.
  if(!ctype_xdigit($_POST["mod_camp_camou"]))
    {
      $erreur=1;
      erreur(0,"La couleur des persos camous doit être fournie en héxadécimal.");
    }
  else if(exist_in_db("SELECT `ID`
                       FROM `camps`
                       WHERE (`couleur_camou`='$_POST[mod_camp_camou]'
                           OR `couleur`='$_POST[mod_camp_camou]')
                         AND `ID`!='$_POST[mod_camp_id]'
                         LIMIT 1"))
    {
      $erreur=1;
      erreur(0,"Couleur déjà utilisé pour un autre camp.");
    }
  if(!ctype_xdigit($_POST["mod_camp_font"]))
    {
      $erreur=1;
      erreur(0,"La couleur de la police doit être fournie en héxadécimal.");
      }*/
  // Enfin on verifie les valeurs RVB des petits carrés les représentant
  // sur la map.
  if(!(is_numeric($_POST["mod_camp_R"])&&is_numeric($_POST["mod_camp_V"])&&is_numeric($_POST["mod_camp_B"]))||!($_POST["mod_camp_R"]>=0&&$_POST["mod_camp_R"]<=255&&$_POST["mod_camp_V"]>=0&&$_POST["mod_camp_V"]<=255&&$_POST["mod_camp_B"]>=0&&$_POST["mod_camp_B"]<=255))
    {
      $erreur=1;
      erreur(0,"Il faut que les valeurs en Rouge Vert et Bleu sur la map soient comprises entre 0 et 255 inclus.");
    }
  else if(exist_in_db("SELECT `ID`
                       FROM `camps`
                       WHERE `map_R`='$_POST[mod_camp_R]'
                         AND `map_V`='$_POST[mod_camp_V]'
                         AND `map_B`='$_POST[mod_camp_B]'
                         AND `ID`!='$_POST[mod_camp_id]'
                       LIMIT 1"))
    {
      $erreur=1;
      erreur(0,"Couleur sur la map déjà utilisée.");
    }
  if(isset($_POST["mod_camp_ouvert"]))
    $ouvert=1;
  else
    $ouvert=0;
  if(isset($_POST["mod_camp_visible"]))
    $visible=1;
  else
    $visible=0;
  // S'il n'y a pas eu d'erreur, on peut enregistrer ces infos.
  if(!$erreur)
    {
      request("UPDATE camps
               SET nom='".post2bdd($_POST['mod_camp_nom'])."',
                   initiale='".post2bdd($_POST['mod_camp_init'])."',
                   map_R='$_POST[mod_camp_R]',
                   map_V='$_POST[mod_camp_V]',
                   map_B='$_POST[mod_camp_B]',
                   camou_R='$_POST[mod_camp_CR]',
                   camou_V='$_POST[mod_camp_CV]',
                   camou_B='$_POST[mod_camp_CB]',
                   ouvert='$ouvert',
                   visible='$visible',
                   grade_0='".post2bdd($_POST['mod_grade_0'])."',
                   grade_1='".post2bdd($_POST['mod_grade_1'])."',
                   grade_2='".post2bdd($_POST['mod_grade_2'])."',
                   grade_3='".post2bdd($_POST['mod_grade_3'])."',
                   grade_4='".post2bdd($_POST['mod_grade_4'])."',
                   grade_5='".post2bdd($_POST['mod_grade_5'])."',
                   grade_6='".post2bdd($_POST['mod_grade_6'])."',
                   grade_7='".post2bdd($_POST['mod_grade_7'])."',
                   grade_8='".post2bdd($_POST['mod_grade_8'])."',
                   grade_9='".post2bdd($_POST['mod_grade_9'])."',
                   grade_10='".post2bdd($_POST['mod_grade_10'])."',
                   grade_11='".post2bdd($_POST['mod_grade_11'])."',
                   grade_12='".post2bdd($_POST['mod_grade_12'])."',
                   grade_13='".post2bdd($_POST['mod_grade_13'])."',
                   grade_spec_3='".post2bdd($_POST['mod_grade_spec_3'])."',
                   grade_spec_2='".post2bdd($_POST['mod_grade_spec_2'])."',
                   grade_spec_1='".post2bdd($_POST['mod_grade_spec_1'])."'
               WHERE ID='$_POST[mod_camp_id]'");
      if(!affected_rows())
	{
	  erreur(0,"Impossible d'enregistrer les modifications du camp dans la bdd.");
	  $erreur=1;
	}
      else
	{
	  // loggage
	  /*	  $detail="<ul>
<li>Nom : ".post2html($_POST['mod_camp_nom'])."</li>
<li>Initiale : ".post2html($_POST['mod_camp_init'])."</li>
<li>Couleur : $_POST[mod_camp_couleur]</li>
<li>Couleur des camous : $_POST[mod_camp_camou]</li>
<li>R : $_POST[mod_camp_R]</li>
<li>G : $_POST[mod_camp_V]</li>
<li>B : $_POST[mod_camp_B]</li>
<li>camou R : $_POST[mod_camp_CR]</li>
<li>camou G : $_POST[mod_camp_CV]</li>
<li>camou B : $_POST[mod_camp_CB]</li>
<li>Nom des armures légères : ".post2html($_POST['mod_camp_LE'])."</li>
<li>Nom des armures moyennes : ".post2html($_POST['mod_camp_MO'])."</li>
<li>Nom des armures lourdes : ".post2html($_POST['mod_camp_LO'])."</li>
<li>Visible : $visible</li>
<li>Ouvert : $ouvert</li>
<li>Grade 1 : ".post2html($_POST['mod_grade_0'])."</li>
<li>Grade 2 : ".post2html($_POST['mod_grade_1'])."</li>
<li>Grade 3 : ".post2html($_POST['mod_grade_2'])."</li>
<li>Grade 4 : ".post2html($_POST['mod_grade_3'])."</li>
<li>Grade 5 : ".post2html($_POST['mod_grade_4'])."</li>
<li>Grade 6 : ".post2html($_POST['mod_grade_5'])."</li>
<li>Grade 7 : ".post2html($_POST['mod_grade_6'])."</li>
<li>Grade 8 : ".post2html($_POST['mod_grade_7'])."</li>
<li>Grade 9 : ".post2html($_POST['mod_grade_8'])."</li>
<li>Grade 10 : ".post2html($_POST['mod_grade_9'])."</li>
<li>Grade 11 : ".post2html($_POST['mod_grade_10'])."</li>
<li>Grade 12 : ".post2html($_POST['mod_grade_11'])."</li>
<li>Grade 13 : ".post2html($_POST['mod_grade_12'])."</li>
<li>Grade 14 : ".post2html($_POST['mod_grade_13'])."</li>
<li>Grade de général en chef : ".post2html($_POST['mod_grade_spec_3'])."</li>
<li>Grade de général : ".post2html($_POST['mod_grade_spec_2'])."</li>
<li>Grade de colonel : ".post2html($_POST['mod_grade_spec_1'])."</li>
<li>Grade  : $_POST[mod_camp_font]</li>
</ul>
";
 console_log('anim_camps',$visible?"Modification du camp : ".post2html($_POST['mod_camp_nom']):"",$detail,0,0);*/
	  write_camps();
	  //	  forum_mod_camp($_POST['mod_camp_id'],post2bdd($_POST['mod_camp_nom']));
	}
    }
  if(!$erreur)
    foreach($_POST as $key)
      $_POST[$key]='';
}

//*****************************************************************************
// Suppression d'un camp.
//*****************************************************************************

else if(isset($_POST["del_camp_ok"]))
{
  // Vérifions que les camps sont okay
  if(isset($_POST["del_camp_id"])&&isset($_POST["del_camp_id2"]))
    {
      if(is_numeric($_POST["del_camp_id"])&&is_numeric($_POST["del_camp_id2"]))
	{
	  if($_POST["del_camp_id"]!=$_POST["del_camp_id2"])
	    {
	      $camps=my_fetch_array("SELECT ID,nom
                                     FROM camps
                                     WHERE ID='$_POST[del_camp_id]'
                                        OR ID='$_POST[del_camp_id2]'
                                     LIMIT 2");
	      if($camps[0]==2)
		echo'<p>Souhaitez vous supprimer '.($camps[1]["ID"]==$_POST["del_camp_id"]?bdd2html($camps[1]["nom"]):bdd2html($camps[2]["nom"])).' et envoyer les soldats en faisant partie dans '.($camps[1]["ID"]==$_POST["del_camp_id2"]?bdd2html($camps[1]["nom"]):bdd2html($camps[2]["nom"])).' ?</p>
 <form method="post" action="anim.php?admin_camps">
 <p>
 '.form_hidden("del_id",$_POST["del_camp_id"]).' 
 '.form_hidden("del_id2",$_POST["del_camp_id2"]).'
 '.form_submit("del_camp_no","Non").'&nbsp;&nbsp;&nbsp;&nbsp;
 '.form_submit("del_camp_yes","Oui").' 
  </p>
 </form>
 ';
	      else
		erreur(0,"Un ou plusieurs identifiants erronés.");
	    }
	  else
	    erreur(0,"Le camp de destination ne peut pas être le camp supprimé.");
	}
      else
	erreur(0,"Types d'identifiants incorrects.");
    }
}
else if(isset($_POST["del_camp_yes"]))
{
  // Vérifions que les camps sont okay
  if(isset($_POST["del_id"])&&isset($_POST["del_id2"]))
    {
      if(is_numeric($_POST["del_id"])&&is_numeric($_POST["del_id2"]))
	{
	  if($_POST["del_id"]!=$_POST["del_id2"])
	    {
	      $camps=my_fetch_array("SELECT `ID`
                                     FROM `camps`
                                     WHERE `ID`='$_POST[del_id]'
                                        OR `ID`='$_POST[del_id2]'
                                     LIMIT 2");
	      if($camps[0]==2)
		{
		  if(request("UPDATE `persos`
                              SET `armee`='$_POST[del_id2]',
                                  compagnie='1',
                                  grade='0',
                                  gene_compas='0',
                                  gene_droits='0',
                                  gene_gene='0',
                                  gene_medailles='0',
                                  gene_ordres='0',
                                  gene_trt='0',
                                  gene_grades='0', 
                                  colo_HRP='0',
                                  colo_RP='0',
                                  colo_colo='0',
                                  colo_criteres='0',
                                  colo_droits='0',
                                  colo_sigle='0',
                                  colo_valider='0',
                                  colo_ordres='0',
                                  colo_virer='0',
                                  colo_grades='0',
                                  ordres='1',
                                  ordresconfi='0',
                                  ordrescompa='0',
                                  forum_gene='0',
                                  forum_em='0',
                                  forum_compa='0',
                                  niveau_gene='0',
                                  niveau_compa='0' 
                              WHERE `ID`='$_POST[del_id]'"))
		    {
		      request("DELETE
                               FROM `camps`
                               WHERE `ID`='$_POST[del_id]'");
		      if(affected_rows())
			{
			  request("OPTIMIZE TABLE `camps`");
			  // Log
			  console_log('anim_camps',"Suprresion du camp d'ID : $_POST[del_id]","",0,0);
			  request("DELETE FROM compagnies WHERE camp='$_POST[del_id]'");
			  if(affected_rows())
			    request("OPTIMIZE TABLE compagnies");
			  write_camps();
			  forum_del_camp($_POST['del_id'],$_POST['del_id2']);
			}
		      else
			erreur(0,"Impossible de supprimer l'armée de la bdd.");
		    }
		  else
		    erreur(0,"Impossible de modifier les armées d'apartenance des persos.");
		}
	      else
		erreur(0,"Un ou plusieurs identifiants erronés.");
	    }
	  else
	    erreur(0,"Le camp de destination ne peut pas être le camp supprimé.");
	}
      else
	erreur(0,"Types d'identifiants incorrects.");
    }
}

//*****************************************************************************
// Préparation du formulaire.
//*****************************************************************************

$palette='';
$i=0;
while(isset($GLOBALS["terrains"][$i]))
{
  $palette.='<td style="background-color:#'.$GLOBALS["terrains"][$i]['couleur'].';"><span>&nbsp;</span></td>';
  $i++;
}

echo'<h3>Résumé des camps</h3>
 <table class="resume">
 <tr><th>Nom</th><th>Initiales</th><th>couleur sur la map</th><th>couleur des camous sur la map</th><th>Inscriptions ouvertes ?</th><th>Visible ?</th></tr>
 ';
$script='<script type="text/javascript">
 function affiche_camp()
 {
  if(!document.getElementById)
    return;
 ';
    $camps=my_fetch_array("SELECT *
                           FROM camps
                           WHERE ID!='0'
                           ORDER BY ID ASC");
for($i=1;$i<=$camps[0];$i++)
{
  echo'<tr><th>'.bdd2html($camps[$i]["nom"]).'</th><td>'.bdd2html($camps[$i]["initiale"]).'</td><td style="color:rgb('.$camps[$i]["map_R"].','.$camps[$i]["map_V"].','.$camps[$i]["map_B"].');">R:'.$camps[$i]["map_R"].'/ V:'.$camps[$i]["map_V"].'/ B:'.$camps[$i]["map_B"].'</td><td style="color:rgb('.$camps[$i]["camou_R"].','.$camps[$i]["camou_V"].','.$camps[$i]["camou_B"].');">R:'.$camps[$i]["camou_R"].'/ V:'.$camps[$i]["camou_V"].'/ B:'.$camps[$i]["camou_B"].'</td><td>'.($camps[$i]["ouvert"]?'oui':'non').'</td><td>'.($camps[$i]["visible"]?'oui':'non').'</td>
 ';
  $script.='  if(document.getElementById("mod_camp_id").value=="'.$camps[$i]["ID"].'")
    {
      nom="'.bdd2js($camps[$i]["nom"]).'";
      R='.$camps[$i]["map_R"].';
      V='.$camps[$i]["map_V"].';
      B='.$camps[$i]["map_B"].';
      CR='.$camps[$i]["camou_R"].';
      CV='.$camps[$i]["camou_V"].';
      CB='.$camps[$i]["camou_B"].';
      ouvert= '.$camps[$i]["ouvert"].';
      visible= '.$camps[$i]["visible"].';
      init="'.bdd2js($camps[$i]["initiale"]).'"; 
      grade_0="'.bdd2js($camps[$i]["grade_0"]).'";
      grade_1="'.bdd2js($camps[$i]["grade_1"]).'";
      grade_2="'.bdd2js($camps[$i]["grade_2"]).'";
      grade_3="'.bdd2js($camps[$i]["grade_3"]).'";
      grade_4="'.bdd2js($camps[$i]["grade_4"]).'";
      grade_5="'.bdd2js($camps[$i]["grade_5"]).'";
      grade_6="'.bdd2js($camps[$i]["grade_6"]).'";
      grade_7="'.bdd2js($camps[$i]["grade_7"]).'";
      grade_8="'.bdd2js($camps[$i]["grade_8"]).'";
      grade_9="'.bdd2js($camps[$i]["grade_9"]).'";
      grade_10="'.bdd2js($camps[$i]["grade_10"]).'";
      grade_11="'.bdd2js($camps[$i]["grade_11"]).'";
      grade_12="'.bdd2js($camps[$i]["grade_12"]).'";
      grade_13="'.bdd2js($camps[$i]["grade_13"]).'";
      grade_spec_3="'.bdd2js($camps[$i]["grade_spec_3"]).'";
      grade_spec_2="'.bdd2js($camps[$i]["grade_spec_2"]).'";
      grade_spec_1="'.bdd2js($camps[$i]["grade_spec_1"]).'";
    }
 ';
    
}
$script.='  document.getElementById("mod_camp_nom").value=nom;
  document.getElementById("mod_camp_R").value=R;
  document.getElementById("mod_camp_V").value=V;
  document.getElementById("mod_camp_B").value=B;
  document.getElementById("mod_camp_CR").value=CR;
  document.getElementById("mod_camp_CV").value=CV;
  document.getElementById("mod_camp_CB").value=CB;
  document.getElementById("mod_camp_init").value=init;
  document.getElementById("mod_camp_ouvert").checked=ouvert?"checked":"";
  document.getElementById("mod_camp_visible").checked=visible?"checked":"";
  colorize("mod_couleur","mod");
  document.getElementById("mod_grade_0").value=grade_0;
  document.getElementById("mod_grade_1").value=grade_1;
  document.getElementById("mod_grade_2").value=grade_2;
  document.getElementById("mod_grade_3").value=grade_3;
  document.getElementById("mod_grade_4").value=grade_4;
  document.getElementById("mod_grade_5").value=grade_5;
  document.getElementById("mod_grade_6").value=grade_6;
  document.getElementById("mod_grade_7").value=grade_7;
  document.getElementById("mod_grade_8").value=grade_8;
  document.getElementById("mod_grade_9").value=grade_9;
  document.getElementById("mod_grade_10").value=grade_10;
  document.getElementById("mod_grade_11").value=grade_11;
  document.getElementById("mod_grade_12").value=grade_12;
  document.getElementById("mod_grade_13").value=grade_13;
  document.getElementById("mod_grade_spec_3").value=grade_spec_3;
  document.getElementById("mod_grade_spec_2").value=grade_spec_2;
  document.getElementById("mod_grade_spec_1").value=grade_spec_1;
 }
 '.(isset($_POST['mod_camp_ok'])?'':'affiche_camp();').'
 </script>
 ';

//*****************************************************************************
// Affichage du formulaire.
//*****************************************************************************

echo'</table>
 <form method="post" action="anim.php?admin_camps">
 <h3>Créer un camp:</h3>
 <p>
 '.form_text("Nom : ","new_camp_nom","","").'<br /> 
 '.form_text("Initiales : ","new_camp_init","3","").'<br /> 
 '.form_check("Inscriptions ouvertes ?","new_camp_ouvert").'<br />
 '.form_check("Camp visible ?","new_camp_visible").'<br />
 </p>
 <h4>Couleur sur la carte :</h4>
 <p>
 '.form_text("Rouge (0-255) : ","new_camp_R","4","colorize('new_couleur','new');").'<br /> 
 '.form_text("Vert (0-255) : ","new_camp_V","4","colorize('new_couleur','new');").'<br /> 
 '.form_text("Bleu (0-255) : ","new_camp_B","4","colorize('new_couleur','new');").'<br /> 
<table id="new_couleur" class="palette">
<tr>'.$palette.'
</tr>
</table> 
 </p>
 <h4>Couleur sur la carte des camouflés :</h4>
 <p>
 '.form_text("Rouge (0-255) : ","new_camp_CR","4","").'<br /> 
 '.form_text("Vert (0-255) : ","new_camp_CV","4","").'<br /> 
 '.form_text("Bleu (0-255) : ","new_camp_CB","4","").'</p>
 '.form_text("Grade 1 : ","new_grade_0","","").'<br /> 
 '.form_text("Grade 2 : ","new_grade_1","","").'<br /> 
 '.form_text("Grade 3 : ","new_grade_2","","").'<br /> 
 '.form_text("Grade 4 : ","new_grade_3","","").'<br /> 
 '.form_text("Grade 5 : ","new_grade_4","","").'<br /> 
 '.form_text("Grade 6 : ","new_grade_5","","").'<br /> 
 '.form_text("Grade 7 : ","new_grade_6","","").'<br /> 
 '.form_text("Grade 8 : ","new_grade_7","","").'<br /> 
 '.form_text("Grade 9 : ","new_grade_8","","").'<br /> 
 '.form_text("Grade 10 : ","new_grade_9","","").'<br /> 
 '.form_text("Grade 11 : ","new_grade_10","","").'<br /> 
 '.form_text("Grade 12 : ","new_grade_11","","").'<br /> 
 '.form_text("Grade 13 : ","new_grade_12","","").'<br /> 
 '.form_text("Grade 14 : ","new_grade_13","","").'<br /> 
 '.form_text("Grade de général en chef : ","new_grade_spec_3","","").'<br /> 
 '.form_text("Grade de général : ","new_grade_spec_2","","").'<br /> 
 '.form_text("Grade de colonel : ","new_grade_spec_1","","").'<br /> 
 '.form_submit("new_camp_ok","Créer").'
 <h3>Modifier un camp:</h3>
 <p>
 '.form_select("Camp : ","mod_camp_id",$camps,"affiche_camp();").' <br />
 '.form_text("Nom : ","mod_camp_nom","","").'<br /> 
 '.form_text("Initiales : ","mod_camp_init","3","").'<br /> 
 '.form_check("Inscriptions ouvertes ?","mod_camp_ouvert").'<br />
 '.form_check("Camp visible ?","mod_camp_visible").'<br />
 </p>
 <h4>Couleur sur la carte:</h4>
 <p>
 '.form_text("Rouge (0-255) : ","mod_camp_R","4","colorize('mod_couleur','mod');").'<br /> 
 '.form_text("Vert (0-255) : ","mod_camp_V","4","colorize('mod_couleur','mod');").'<br /> 
 '.form_text("Bleu (0-255) : ","mod_camp_B","4","colorize('mod_couleur','mod');").'<br /> 
<table id="mod_couleur" class="palette">
<tr>'.$palette.'
</tr>
</table> 
 </p>
 <h4>Couleur sur la carte des camouflés :</h4>
 <p>
 '.form_text("Rouge (0-255) : ","mod_camp_CR","4","").'<br /> 
 '.form_text("Vert (0-255) : ","mod_camp_CV","4","").'<br /> 
 '.form_text("Bleu (0-255) : ","mod_camp_CB","4","").'</p>

 '.form_text("Grade 1 : ","mod_grade_0","","").'<br /> 
 '.form_text("Grade 2 : ","mod_grade_1","","").'<br /> 
 '.form_text("Grade 3 : ","mod_grade_2","","").'<br /> 
 '.form_text("Grade 4 : ","mod_grade_3","","").'<br /> 
 '.form_text("Grade 5 : ","mod_grade_4","","").'<br /> 
 '.form_text("Grade 6 : ","mod_grade_5","","").'<br /> 
 '.form_text("Grade 7 : ","mod_grade_6","","").'<br /> 
 '.form_text("Grade 8 : ","mod_grade_7","","").'<br /> 
 '.form_text("Grade 9 : ","mod_grade_8","","").'<br /> 
 '.form_text("Grade 10 : ","mod_grade_9","","").'<br /> 
 '.form_text("Grade 11 : ","mod_grade_10","","").'<br /> 
 '.form_text("Grade 12 : ","mod_grade_11","","").'<br /> 
 '.form_text("Grade 13 : ","mod_grade_12","","").'<br /> 
 '.form_text("Grade 14 : ","mod_grade_13","","").'<br /> 
 '.form_text("Grade de général en chef : ","mod_grade_spec_3","","").'<br /> 
 '.form_text("Grade de général : ","mod_grade_spec_2","","").'<br /> 
 '.form_text("Grade de colonel : ","mod_grade_spec_1","","").'<br /> 
 '.form_submit("mod_camp_ok","Modifier").'
 <h3>Supprimer un camp:</h3>
 <p>
 '.form_select("","del_camp_id",$camps,"").' et envoyer ses membres dans :
 '.form_select("","del_camp_id2",$camps,"").' <br />
 '.form_submit("del_camp_ok","Supprimer").'
 </p>
 </form>
 '.$script;

function write_camps()
{
  $camps=my_fetch_array('SELECT *
                         FROM `camps`
                         WHERE ID!=0
                         ORDER BY `ID` ASC');
  $arrays='<?php
$GLOBALS[\'camps\']=array(0=>array(\'initiale\'=>\'0\',
				 \'nom\'=>\'Aucun\',
				 \'LE\'=>\'L&eacute;g&eacute;re\',
				 \'MO\'=>\'Moyenne\',
				 \'LO\'=>\'Lourde\',
				 \'ouvert\'=>\'0\',
				 \'visible\'=>\'0\',
				 \'R\'=>\'0\',
				 \'G\'=>\'0\',
				 \'B\'=>\'0\',
				 \'CR\'=>\'100\',
				 \'CG\'=>\'100\',
				 \'CB\'=>\'100\',
				 \'grade_0\'=>\'Trouffion\',
				 \'grade_1\'=>\'Caporal\',
				 \'grade_2\'=>\'Caporal-Chef\',
				 \'grade_3\'=>\'Sergent\',
				 \'grade_4\'=>\'Sergent-Chef\',
				 \'grade_5\'=>\'Adjudant\',
				 \'grade_6\'=>\'Adjudant-Chef\',
				 \'grade_7\'=>\'Major\',
				 \'grade_8\'=>\'Sous-Lieutenant\',
				 \'grade_9\'=>\'Lieutenant\',
				 \'grade_10\'=>\'Capitaine\',
				 \'grade_11\'=>\'Commandant\',
				 \'grade_12\'=>\'Lieutenant-Colonel\',
				 \'grade_13\'=>\'Colonel\',
				 \'grade_spec_3\'=>\'G&eacute;n&eacute;ral en Chef\',
				 \'grade_spec_2\'=>\'G&eacute;n&eacute;ral\',
				 \'grade_spec_1\'=>\'Colonel de Compagnie\')';
  for($i=1;$i<=$camps[0];$i++){
    $arrays.=',
			';
    $arrays.=$camps[$i]['ID'].'=>array("initiale"=>"'.bdd2js($camps[$i]['initiale']).'",
				 "nom"=>"'.bdd2js($camps[$i]['nom']).'",
				 "ouvert"=>"'.$camps[$i]['ouvert'].'",
				 "visible"=>"'.$camps[$i]['visible'].'",
				 "R"=>"'.$camps[$i]['map_R'].'",
				 "G"=>"'.$camps[$i]['map_V'].'",
				 "B"=>"'.$camps[$i]['map_B'].'",
				 "CR"=>"'.$camps[$i]['camou_R'].'",
				 "CG"=>"'.$camps[$i]['camou_V'].'",
				 "CB"=>"'.$camps[$i]['camou_B'].'",
				 "grade_0"=>"'.bdd2js($camps[$i]['grade_0']).'",
				 "grade_1"=>"'.bdd2js($camps[$i]['grade_1']).'",
				 "grade_2"=>"'.bdd2js($camps[$i]['grade_2']).'",
				 "grade_3"=>"'.bdd2js($camps[$i]['grade_3']).'",
				 "grade_4"=>"'.bdd2js($camps[$i]['grade_4']).'",
				 "grade_5"=>"'.bdd2js($camps[$i]['grade_5']).'",
				 "grade_6"=>"'.bdd2js($camps[$i]['grade_6']).'",
				 "grade_7"=>"'.bdd2js($camps[$i]['grade_7']).'",
				 "grade_8"=>"'.bdd2js($camps[$i]['grade_8']).'",
				 "grade_9"=>"'.bdd2js($camps[$i]['grade_9']).'",
				 "grade_10"=>"'.bdd2js($camps[$i]['grade_10']).'",
				 "grade_11"=>"'.bdd2js($camps[$i]['grade_11']).'",
				 "grade_12"=>"'.bdd2js($camps[$i]['grade_12']).'",
				 "grade_13"=>"'.bdd2js($camps[$i]['grade_13']).'",
				 "grade_spec_3"=>"'.bdd2js($camps[$i]['grade_spec_3']).'",
				 "grade_spec_2"=>"'.bdd2js($camps[$i]['grade_spec_2']).'",
				 "grade_spec_1"=>"'.bdd2js($camps[$i]['grade_spec_1']).'")';
    }
  $arrays.=');
function camp_R($id)
{
  return $GLOBALS[\'camps\'][$id][\'R\']; 
}
function camp_G($id)
{
  return $GLOBALS[\'camps\'][$id][\'G\']; 
}
function camp_B($id)
{
  return $GLOBALS[\'camps\'][$id][\'B\']; 
}
function camp_CR($id)
{
  return $GLOBALS[\'camps\'][$id][\'CR\']; 
}
function camp_CG($id)
{
  return $GLOBALS[\'camps\'][$id][\'CG\']; 
}
function camp_CB($id)
{
  return $GLOBALS[\'camps\'][$id][\'CB\']; 
}
function camp_initiale($id)
{
  return $GLOBALS[\'camps\'][$id][\'initiale\']; 
}
function camp_nom($id)
{
  return $GLOBALS[\'camps\'][$id][\'nom\']; 
}

function numero_camp_grade($camp,$numero)
{
  $numero=max(0,min(13,$numero));
  return $GLOBALS[\'camps\'][$camp][\'grade_\'.$numero]?$GLOBALS[\'camps\'][$camp][\'grade_\'.$numero]:$GLOBALS[\'camps\'][0][\'grade_\'.$numero]; 
}

function grade_spec()
{
  global $perso;
  if($perso[\'ID_grade\']>3)
    return $perso[\'nom_grade\'];
  if($perso[\'ID_grade\']>0)
    return $GLOBALS[\'camps\'][$perso[\'armee\']][\'grade_spec_\'.$perso[\'ID_grade\']]?$GLOBALS[\'camps\'][$perso[\'armee\']][\'grade_spec_\'.$perso[\'ID_grade\']]:$GLOBALS[\'camps\'][0][\'grade_spec_\'.$perso[\'ID_grade\']];
  return \'\';
}       

function grade_spec_autre($camp,$nbr_grade,$grade)
{
  if($nbr_grade>3)
    return $grade;
  if($nbr_grade>0)
    return $GLOBALS[\'camps\'][$camp][\'grade_spec_\'.$nbr_grade]?$GLOBALS[\'camps\'][$camp][\'grade_spec_\'.$nbr_grade]:$GLOBALS[\'camps\'][0][\'grade_spec_\'.$nbr_grade];
  return \'\';
}       
?>';
  if(fichier_create('../inits/camps.php',$arrays,1)){
      add_message(0,'Fichier enregistr&eacute;.');
    }
}
?>