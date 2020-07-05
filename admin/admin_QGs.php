<h2>Gestion des QGs et des tubes.</h2>
<?php
//*****************************************************************************
// Création d'un QG.
//*****************************************************************************

if(isset($_POST["new_qg_ok"],$_POST['new_qg_carte'],$_POST['new_qg_X'],$_POST['new_qg_Y'])&&is_numeric($_POST['new_qg_carte'])&&is_numeric($_POST['new_qg_X'])&&is_numeric($_POST['new_qg_Y']))
{
  $erreur=0;
  // Vérification des données.
  if(exist_in_db("SELECT ID
                  FROM qgs
                  WHERE X='$_POST[new_qg_X]'
                    AND Y='$_POST[new_qg_Y]'
                    AND carte='$_POST[new_qg_carte]'"))
    {
      erreur(0,"Coordonnées déjà utilisées par un autre QG.");
      $erreur=1;
    }
  if(!exist_in_db("SELECT ID
                   FROM cartes
                   WHERE ID='$_POST[new_qg_carte]'"))
    {
      erreur(0,"Identifiant de carte inexistant.");
      $erreur=1;
    }
  if(!(isset($_POST['new_qg_camp'])&&is_numeric($_POST['new_qg_camp'])&&exist_in_db("SELECT ID
                                                                                     FROM camps
                                                                                     WHERE ID='$_POST[new_qg_camp]'")))
    {
      erreur(0,"Identifiant de camp inexistant. Le QG n'appartient donc à aucun.");
      $_POST['new_qg_camp']=0;
    }
  if(!(isset($_POST['new_qg_vision'],$_POST['new_qg_bloc'],$_POST['new_qg_util'],$_POST['new_qg_regen'],$_POST['new_qg_visible'],$_POST['new_qg_camou'],$_POST['new_qg_reparation'])&&
       is_numeric($_POST['new_qg_vision'])&&
       is_numeric($_POST['new_qg_bloc'])&&
       is_numeric($_POST['new_qg_util'])&&
       is_numeric($_POST['new_qg_regen'])&&
       is_numeric($_POST['new_qg_visible'])&&
       is_numeric($_POST['new_qg_camou'])&&
       is_numeric($_POST['new_qg_reparation'])))
    {
      erreur(0,"Une valeur censée être numérique ne l'est pas.");
      $erreur=1;
    }
  if(!$erreur)
    {
      request("INSERT
               INTO qgs (nom,
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
                         carte,
                         reparation,
                         regeneration,
                         camp,
                         respawn,
                         prenable,
                         type,
                         visible,
                         malus_camou)
                 VALUES('".post2bdd($_POST["new_qg_nom"])."',
                        '".post2bdd($_POST["new_qg_init"])."',
                        '$_POST[new_qg_vision]',
                        '$_POST[new_qg_bloc]',
                        '$_POST[new_qg_util]',
                        '".post_on('new_qg_tube')."',
                        '".post_on('new_qg_arme')."',
                        '".post_on('new_qg_armure')."',
                        '".post_on('new_qg_reload')."',
                        '$_POST[new_qg_X]',
                        '$_POST[new_qg_Y]',
                        '$_POST[new_qg_carte]',
                        '$_POST[new_qg_reparation]',
                        '$_POST[new_qg_regen]',
                        '$_POST[new_qg_camp]',
                        '".post_on('new_qg_respawn')."',
                        '".post_on('new_qg_prenable')."',
                        '".post_on('new_qg_self')."',
                        '$_POST[new_qg_visible]',
                        '$_POST[new_qg_camou]')");
      if(!last_id())
	erreur(0,"Impossible d'enregistrer le QG dans la base de donnée.");
      else
	{
		
	  // ici commence le log :
	  $detail = "<ul>
<li>ID : ".last_id()."</li>
<li>Nom : ".post2html($_POST['new_qg_nom'])."</li>
<li>Initiale : ".post2html($_POST['new_qg_init'])."</li>
<li>Visibilité : ".$_POST['new_qg_vision']."</li>
<li>Blocage : ".$_POST['new_qg_bloc']."</li>
<li>Utilisation : ".$_POST['new_qg_util']."</li>
<li>Tubage : ".post_on('new_qg_tube')."</li>
<li>Armes : ".post_on('new_qg_arme')."</li>
<li>Armures : ".post_on('new_qg_armure')."</li>
<li>Munitions : ".post_on('new_qg_reload')."</li>
<li>X : ".$_POST['new_qg_X']."</li>
<li>Y : ".$_POST['new_qg_Y']."</li>
<li>Carte : ".$_POST['new_qg_carte']."</li>
<li>Réparation : ".$_POST['new_qg_reparation']."</li>
<li>Régénération : ".$_POST['new_qg_regen']."</li>
<li>Camp : ".$_POST['new_qg_camp']."</li>
<li>Respawn : ".post_on('new_qg_respawn')."</li>
<li>Prenable : ".post_on('new_qg_prenable')."</li>
<li>Type : ".post_on('new_qg_self')."</li>
<li>Visible : ".$_POST['new_qg_visible']."</li>
<li>Malus de camouflage : ".$_POST['new_qg_camou']."</li>
</ul>
";	  
	  console_log('anim_qgs',"Création du QG : ".post2html($_POST['new_qg_nom']),$detail,0,0);
	  // fin du log		
		
	  foreach($_POST as $key=>$value)
	    unset($_POST[$key]);
	  echo'QG enregistré.<br />
';
	}
    }
}

//*****************************************************************************
// Modification d'un QG.
//*****************************************************************************

if(isset($_POST["mod_qg_ok"],$_POST['mod_qg_carte'],$_POST['mod_qg_X'],$_POST['mod_qg_Y'],$_POST['mod_qg_id'])&&is_numeric($_POST['mod_qg_carte'])&&is_numeric($_POST['mod_qg_X'])&&is_numeric($_POST['mod_qg_Y'])&&is_numeric($_POST['mod_qg_id']))
{
  $erreur=0;
  // Vérification des données.
  if(!exist_in_db("SELECT ID
                   FROM qgs
                   WHERE ID='$_POST[mod_qg_id]'"))
    {
      erreur(0,"Identifiant de qg inexistant.");
      $erreur=1;
    }
  if(exist_in_db("SELECT ID FROM qgs WHERE X='$_POST[mod_qg_X]' AND Y='$_POST[mod_qg_Y]' AND carte='$_POST[mod_qg_carte]' AND ID!='$_POST[mod_qg_id]'"))
    {
      erreur(0,"Coordonnées déjà utilisées par un autre QG.");
      $erreur=1;
    }
  if(!exist_in_db("SELECT ID from cartes WHERE ID='$_POST[mod_qg_carte]'"))
    {
      erreur(0,"Identifiant de carte inexistant.");
      $erreur=1;
    }
  if(!(isset($_POST['mod_qg_camp'])&&is_numeric($_POST['mod_qg_camp'])&&exist_in_db("SELECT ID
                                                                                     FROM camps
                                                                                     WHERE ID='$_POST[mod_qg_camp]'")))
    {
      erreur(0,"Identifiant de camp inexistant. Le QG n'appartient donc à aucun.");
      $_POST['mod_qg_camp']=0;
    }
  if(!(isset($_POST['mod_qg_vision'],$_POST['mod_qg_bloc'],$_POST['mod_qg_util'],$_POST['mod_qg_regen'],$_POST['mod_qg_visible'],$_POST['mod_qg_camou'],$_POST['mod_qg_reparation'])&&is_numeric($_POST['mod_qg_vision'])&&is_numeric($_POST['mod_qg_bloc'])&&is_numeric($_POST['mod_qg_util'])&&is_numeric($_POST['mod_qg_regen'])&&is_numeric($_POST['mod_qg_visible'])&&is_numeric($_POST['mod_qg_camou'])&&is_numeric($_POST['mod_qg_reparation'])))
    {
      erreur(0,"Une valeur censée être numérique ne l'est pas.");
      $erreur=1;
    }
  if(!$erreur)
    {
      request("UPDATE qgs
               SET nom='".post2bdd($_POST["mod_qg_nom"])."',
                   initiales='".post2bdd($_POST["mod_qg_init"])."',
                   visibilite='$_POST[mod_qg_vision]',
                   blocage='$_POST[mod_qg_bloc]',
                   utilisation='$_POST[mod_qg_util]',
                   tubage='".post_on('mod_qg_tube')."',
                   armes='".post_on('mod_qg_arme')."',
                   armures='".post_on('mod_qg_armure')."',
                   munitions='".post_on('mod_qg_reload')."',
                   X='$_POST[mod_qg_X]',
                   Y='$_POST[mod_qg_Y]',
                   carte='$_POST[mod_qg_carte]',
                   reparation='".$_POST['mod_qg_reparation']."',
                   regeneration='$_POST[mod_qg_regen]',
                   camp='$_POST[mod_qg_camp]',
                   respawn='".post_on('mod_qg_respawn')."',
                   prenable='".post_on('mod_qg_prenable')."',
                   visible='$_POST[mod_qg_visible]',
                   malus_camou='$_POST[mod_qg_camou]',
                   `type`='".post_on('mod_qg_self')."'
               WHERE ID='$_POST[mod_qg_id]'");
      if(!affected_rows())
	erreur(0,"Impossible d'enregistrer les modification du QG dans la base de donnée.");
      else
	{
		
	    // ici commence le log :
	  $detail = "<ul>
<li>ID : ".$_POST['mod_qg_id']."</li>  
<li>Nom : ".post2html($_POST['mod_qg_nom'])."</li>
<li>Initiale : ".post2html($_POST['mod_qg_init'])."</li>
<li>Visibilité : ".$_POST['mod_qg_vision']."</li>
<li>Blocage : ".$_POST['mod_qg_bloc']."</li>
<li>Utilisation : ".$_POST['mod_qg_util']."</li>
<li>Tubage : ".post_on('mod_qg_tube')."</li>
<li>Armes : ".post_on('mod_qg_arme')."</li>
<li>Armures : ".post_on('mod_qg_armure')."</li>
<li>Munitions : ".post_on('mod_qg_reload')."</li>
<li>X : ".$_POST['mod_qg_X']."</li>
<li>Y : ".$_POST['mod_qg_Y']."</li>
<li>Carte : ".$_POST['mod_qg_carte']."</li>
<li>Réparation : ".$_POST['mod_qg_reparation']."</li>
<li>Régénération : ".$_POST['mod_qg_regen']."</li>
<li>Camp : ".$_POST['mod_qg_camp']."</li>
<li>Respawn : ".post_on('mod_qg_respawn')."</li>
<li>Prenable : ".post_on('mod_qg_prenable')."</li>
<li>Type : ".post_on('mod_qg_self')."</li>
<li>Visible : ".$_POST['mod_qg_visible']."</li>
<li>Malus de camouflage : ".$_POST['mod_qg_camou']."</li>
</ul>
";	  
	  console_log('anim_qgs',"Modification du QG : ".post2html($_POST['mod_qg_nom']),$detail,0,0);
	  // fin du log		
		
	  foreach($_POST as $key=>$value)
	    unset($_POST[$key]);
	  echo'QG modifié.<br />
';
	}
    }
}

//*****************************************************************************
// Destruction d'un QG.
//*****************************************************************************

else if(isset($_POST["del_qg_ok"],$_POST['del_qg_id'])&&is_numeric($_POST['del_qg_id']))
{
  $qg=my_fetch_array("SELECT `nom` FROM `qgs` WHERE `ID`='$_POST[del_qg_id]'");
  if($qg[0])
    {
      echo'<form method="post" action="anim.php?admin_QGs">
<h3>Confirmation :</h3>
<p>Êtes vous sûr de vouloir supprimer ce QG ('.post2html($qg[1][0]).')?<br />
'.form_hidden("id",$_POST['del_qg_id']).'
'.form_submit("del_qg_no","Non").'&nbsp;&nbsp;&nbsp;&nbsp;
'.form_submit("del_qg_yes","Oui").'
</p>
</form>
<hr />
';
    }
  else
    erreur(0,"Identifiant de QG inconnu.");
}
else if(isset($_POST["del_qg_yes"],$_POST['id'])&&is_numeric($_POST['id']))
{
  request("DELETE FROM `qgs` WHERE `ID`='$_POST[id]'");
  if(affected_rows())
    {
      console_log('anim_qgs',"Suppression du QG d'ID : $_POST[id]",'',0,0);	
    	
      request("OPTIMIZE TABLE `qgs`");
      request("DELETE FROM `tubes` WHERE `QG_1`='$_POST[id]' OR `QG_2`='$_POST[id]'");
      request("OPTIMIZE TABLE `tubes`");
    }
  else
    erreur(0,"Impossible de supprimer le QG de la base de donnée.");
}

//*****************************************************************************
// Création d'un tube.
//*****************************************************************************

else if(isset($_POST['new_tube_ok'],$_POST['new_tube_id1'],$_POST['new_tube_id2'],$_POST['new_tube_prix']) && is_numeric($_POST['new_tube_id1']) && is_numeric($_POST['new_tube_id2']) && is_numeric($_POST['new_tube_prix']))
{
  $erreur=0;
  if($_POST['new_tube_id1']==$_POST['new_tube_id2'])
    {
      $erreur=1;
      erreur(0,"Inutile de faire des tubes qui vont d'un QG au même QG.");
    }
  $qgs=my_fetch_array("SELECT `tubage`,mission
                       FROM `qgs`
                         INNER JOIN cartes
                            ON qgs.carte=cartes.ID
                       WHERE qgs.ID='$_POST[new_tube_id1]' OR qgs.ID='$_POST[new_tube_id2]' LIMIT 2");
  if($qgs[0]<2)
    {
      erreur(0,"Identifiant d'au moins un des QGs faux.");
      $erreur=1;
    }
  if(!($qgs[1]['tubage']&&$qgs[2]['tubage']&&$qgs[1]['mission']==$qgs[2]['mission']))
    {
      $erreur=1;
      erreur(0,"Au moins l'un des QGs ne permet pas de tuber... veuillez changer cela avant de tenter de créer un tube.");
    }
  if(!$erreur)
    {
      if(!request("INSERT INTO `tubes` (`QG_1`,`QG_2`,`prix`) VALUES('$_POST[new_tube_id1]','$_POST[new_tube_id2]','$_POST[new_tube_prix]')"))
	erreur(0,"Impossible d'enregistrer le tube dans le sens aller entre ces 2 QGs");
      if(isset($_POST["new_tube_ar"]))
	if(!request("INSERT INTO `tubes` (`QG_1`,`QG_2`,`prix`) VALUES('$_POST[new_tube_id2]','$_POST[new_tube_id1]','$_POST[new_tube_prix]')"))
	  erreur(0,"Impossible d'enregistrer le tube dans le sens retour entre ces 2 QGs");
    
	 // ici commence le log :
	 $detail = "<ul>
<li>QG 1 : ".$_POST['new_tube_id1']."</li>
<li>QG 2 : ".$_POST['new_tube_id2']."</li>
<li>QG 2 : ".$_POST['new_tube_prix']."</li>
</ul>
"; 
     console_log('anim_qgs',"Création d'un tube ",$detail,0,0);
     // fin du log
    }
}

//*****************************************************************************
// Destruction d'un tube.
//*****************************************************************************

else if(isset($_POST["del_tube_ok"],$_POST['del_tube_id'])&&is_numeric($_POST['del_tube_id']))
{
  $tube=my_fetch_array("SELECT qg1.initiales,qg2.initiales
                        FROM `tubes`
                           INNER JOIN qgs AS qg1
                              ON tubes.QG_1=qg1.ID
                           INNER JOIN qgs AS qg2
                              ON tubes.QG_2=qg2.ID
                        WHERE tubes.ID='$_POST[del_tube_id]'");
  if($tube[0])
    {
      echo'<form method="post" action="anim.php?admin_QGs">
<h3>Confirmation :</h3>
<p>Êtes vous sûr de vouloir supprimer le tube entre les QGs '.bdd2html($tube[1][0]).' et '.bdd2html($tube[1][1]).'?<br />
'.form_hidden("id",$_POST['del_tube_id']).'
'.form_submit("del_tube_no","Non").'&nbsp;&nbsp;&nbsp;&nbsp;
'.form_submit("del_tube_yes","Oui").'
</p>
</form>
<hr />
';
    }
  else
    erreur(0,"Identifiant de tube inconnu.");
}
else if(isset($_POST["del_tube_yes"],$_POST['id'])&&is_numeric($_POST['id']))
{
  request("DELETE FROM `tubes` WHERE `ID`='$_POST[id]'");
  if(affected_rows())
  {
  	console_log('anim_qgs',"Suppression du tube d'ID : $_POST[id]",'',0,0);
    request("OPTIMIZE TABLE `tubes`");
  }
  else
    erreur(0,"Impossible de supprimer le tube de la base de donnée.");
}

$cartes=my_fetch_array("SELECT ID,nom FROM cartes ORDER BY ID ASC");
$camps=my_fetch_array("SELECT ID,nom FROM camps ORDER BY ID ASC");
$camps[$camps[0]+1]=array(0,'Aucun');
$camps[$camps[0]+2]=array();
$camps[0]++;
$qgs=my_fetch_array("SELECT qgs.ID, CONCAT(qgs.nom,'(',qgs.X,',',qgs.Y,')') AS nom, tubage, initiales,cartes.nom
                     FROM qgs
                        INNER JOIN cartes
                           ON qgs.carte=cartes.ID
                     ORDER BY carte ASC");
$tubes=my_fetch_array("SELECT tubes.ID,
                              qgs1.initiales,
                              qgs1.nom,
                              carte1.nom,
                              qgs2.ID,
                              qgs2.initiales,
                              qgs2.nom,
                              carte2.nom
                       FROM tubes
                          INNER JOIN qgs as qgs1
                             ON tubes.QG_1=qgs1.ID
                          INNER JOIN qgs as qgs2
                             ON tubes.QG_2=qgs2.ID
                          INNER JOIN cartes as carte1
                             ON qgs1.carte = carte1.ID
                          INNER JOIN cartes as carte2
                             ON qgs2.carte = carte2.ID
                       ORDER  BY qgs1.carte ASC, qgs2.carte ASC");
$str_qgs='';
$str_qgs1='';
$str_tubes='';
if($tubes[0])
{
  $carte=$tubes[1][3];
  $str_tubes='<optgroup label="'.$carte.'">
 ';
  for($i=1;$i<=$tubes[0];$i++)
    {
      if($carte!=$tubes[$i][3])
	{
	  $carte=$tubes[$i][3];
	  $str_tubes.='</optgroup>
 <optgroup label="'.$carte.'">
';
	}
      $str_tubes.='<option value="'.$tubes[$i][0].'">Tube entre les QGs : '.$tubes[$i][1].' et '.$tubes[$i][5].($tubes[1][3]!=$tubes[1][7]?' ('.$tubes[1][7].')':'').'</option>
';
    }
	$str_tubes.='</optgroup>
 ';
}
if($qgs[0])
  {
    $carte=$qgs[1]['nom'];
    $str_qgs1=$str_qgs='<option>Choisissez un QG</option>
<optgroup label="'.$carte.'">
 ';
    set_post('mod_qg_id');
    for($i=1;$i<=$qgs[0];$i++)
      {
	if($carte!=$qgs[$i]['nom'])
	  {
	    $carte=$qgs[$i]['nom'];
	    $str_qgs.='</optgroup>
 <optgroup label="'.$carte.'">
';
	    $str_qgs1.='</optgroup>
 <optgroup label="'.$carte.'">
';
	  }
	if($qgs[$i]['tubage'])
	  $str_qgs1.='<option value="'.$qgs[$i]['ID'].'"'.($qgs[$i]['ID']==$_POST["mod_qg_id"]?' selected="selected"':'').'>'.$qgs[$i][1].' ('.$qgs[$i]['initiales'].')</option>
 ';
	$str_qgs.='<option value="'.$qgs[$i]['ID'].'"'.($qgs[$i]['ID']==$_POST["mod_qg_id"]?' selected="selected"':'').'>'.$qgs[$i][1].' ('.$qgs[$i]['initiales'].')</option>
';
      }
    $str_qgs.='</optgroup>
';
    $str_qgs1.='</optgroup>
';
  }
$visibilite=array(3,
		  array('1','Visible'),
		  array('2','Visible si pris'),
		  array('0','Caché'));
echo'<script type="text/javascript" src="scripts/admin.js"></script>
<form method="post" action="anim.php?admin_QGs">
<h3>Créer un QG:</h3>
<p>
<label for="mod_qg_id">Copier :</label>
<select name="mod_qg_id" id="mod_qg_id" onchange="LoadXML(\'qg\',this.value,\'new\');">
'.$str_qgs.'</select><br /> 
'.form_select("Sur la carte : ","new_qg_carte",$cartes,'').'<br />
'.form_text("Nom : ","new_qg_nom",0,"").'<br />
'.form_text("Nom sur la carte (10 caractères max) : ","new_qg_init",0,"").'<br />
'.form_text("X : ","new_qg_X",2,"").'<br />
'.form_text("Y : ","new_qg_Y",2,"").'<br />
'.form_text("Bloqué si quelqu'un est à : ","new_qg_bloc",1,"").' cases.<br />
'.form_text("Utilisable à : ","new_qg_util",1,"").' cases.<br />
'.form_text("Visible à : ","new_qg_vision",1,"").' cases.<br />
'.form_check("Changement d'arme ?","new_qg_arme").'<br />
'.form_check("Rechargement d'arme ?","new_qg_reload").'<br />
'.form_check("Changement d'armure ?","new_qg_armure").'<br />
'.form_text("Réparation d'armure par tour : ","new_qg_reparation",3,'').'<br />
'.form_check("Permet d'y apparaître ?","new_qg_respawn").'<br />
'.form_check("Permet de tuber ?","new_qg_tube").'<br />
'.form_text("Malus de camouflage à l'arrivée par tube: ","new_qg_camou",3,"").'%<br />
'.form_check("Prenable ?","new_qg_prenable").'<br />
'.form_select("Appartient au camp : ","new_qg_camp",$camps,'').'<br />
'.form_check("En libre service ?","new_qg_self").'<br />
'.form_text("Bonus de régénération : ","new_qg_regen",1,"").' points de vie par tour.<br />
'.form_select("Sur le radar : ","new_qg_visible",$visibilite,'').'<br />
'.form_submit("new_qg_ok","Créer").'<br />
<h3>Modifier un QG:</h3>
<p>
<select name="mod_qg_id" id="mod_qg_id" onchange="LoadXML(\'qg\',this.value,\'mod\');">
'.$str_qgs.'</select><br /> 
'.form_select("Sur la carte : ","mod_qg_carte",$cartes,'').'<br />
'.form_text("Nom : ","mod_qg_nom",0,"").'<br />
'.form_text("Nom sur la carte (10 caractères max) : ","mod_qg_init",0,"").'<br />
'.form_text("X : ","mod_qg_X",2,"").'<br />
'.form_text("Y : ","mod_qg_Y",2,"").'<br />
'.form_text("Bloqué si quelqu'un est à : ","mod_qg_bloc",1,"").' cases.<br />
'.form_text("Utilisable à : ","mod_qg_util",1,"").' cases.<br />
'.form_text("Visible à : ","mod_qg_vision",1,"").' cases.<br />
'.form_check("Changement d'arme ?","mod_qg_arme").'<br />
'.form_check("Rechargement d'arme ?","mod_qg_reload").'<br />
'.form_check("Changement d'armure ?","mod_qg_armure").'<br />
'.form_text("Réparation d'armure par tour : ","mod_qg_reparation",3,'').'<br />
'.form_check("Permet d'y apparaître ?","mod_qg_respawn").'<br />
'.form_check("Permet de tuber ?","mod_qg_tube").'<br />
'.form_text("Malus de camouflage à l'arrivée par tube: ","mod_qg_camou",3,"").'%<br />
'.form_check("Prenable ?","mod_qg_prenable").'<br />
'.form_select("Appartient au camp : ","mod_qg_camp",$camps,'').'<br />
'.form_check("En libre service ?","mod_qg_self").'<br />
'.form_text("Bonus de régénération : ","mod_qg_regen",1,"").' points de vie par tour.<br />
'.form_select("Sur le radar : ","mod_qg_visible",$visibilite,'').'<br />
'.form_submit("mod_qg_ok","Modifier").'<br />
</p>
 <h3>Supprimer un QG : </h3> 
<select name="del_qg_id" id="del_qg_id">
'.$str_qgs.'</select><br /> 
'.form_submit("del_qg_ok","Supprimer").'<hr />
 <h3>Créer un tube :</h3>
 <label for="new_tube_id1">Entre les QGs : </label><select name="new_tube_id1" id="new_tube_id1">
'.$str_qgs1.'</select>
 <label for="new_tube_id2"> et </label><select name="new_tube_id2" id="new_tube_id2">
'.$str_qgs1.'</select><br /> 
'.form_text("Cout en mouvement : ","new_tube_prix",3,"").'<br />
'.form_check("Aller retour ?","new_tube_ar").' 
'.form_submit("new_tube_ok","Créer").' 
<h3>Supprimer un tube : </h3> 
<select name="del_tube_id" id="del_tube_id">
'.$str_tubes.'</select><br /> 
'.form_submit("del_tube_ok","Supprimer").' 
</form>
';
?>