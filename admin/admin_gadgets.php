<h2>Gestion des gadgets</h2>
<?php
//*****************************************************************************
// Création d'un gadget.
//*****************************************************************************
if(isset($_POST["new_gad_ok"])){
  $erreur=0;
  if(!(isset($_POST["new_gad_nom"])&&$_POST["new_gad_nom"])){
    erreur(0,"Il faut choisir un nom pour le gadget.");
    $erreur=1;
  }
  else{
    if(exist_in_db("SELECT `ID`
                      FROM `gadgets`
                      WHERE `nom`='".post2bdd($_POST['new_gad_nom'])."'
                      LIMIT 1")){
      erreur(0,"Nom de gagdet déjà utilisé.");
      $erreur=1;
    }
  }
  if(!(isset($_POST['new_gad_armure'])&&is_numeric($_POST['new_gad_armure'])&&$_POST['new_gad_armure']>=0&&$_POST['new_gad_armure']<=8)){
    erreur(0,"Type d'armure incorrect.");
    $erreur=1;
  }
  if(!$erreur){
    $armes=isset($_POST['new_gad_arme0'])+isset($_POST['new_gad_arme1'])*2+isset($_POST['new_gad_arme2'])*4+isset($_POST['new_gad_arme3'])*8+isset($_POST['new_gad_arme4'])*16+isset($_POST['new_gad_arme5'])*32+isset($_POST['new_gad_arme6'])*64+isset($_POST['new_gad_arme7'])*128+isset($_POST['new_gad_arme8'])*256+isset($_POST['new_gad_arme9'])*512;
    request("INSERT
               INTO `gadgets` (`nom`,
                               `description`,
                               `bonus_precision`,
                               `bonus_camou`,
                               `bonus_tir_camou`,
                               `bonus_vision`,
                               `eclaireur`,
`escalade`, 
                               `foret`,
                               `montagne`,
                               `desert`,
                               `marais`,
                               `plaine`,
                               `mines`,
                               `degats_mines`,
                               `pourcent_PV`,
                               `instabilite`,
                               `discretion`,
                               `grade_1`,
                               `grade_2`,
                               `grade_3`,
                               `armure`,
                               `arme`,
                               `stack`)
                        VALUES('".post2bdd($_POST['new_gad_nom'])."',
                               '".post2bdd($_POST['new_gad_desc'])."',
                               '$_POST[new_gad_precision]',
                               '$_POST[new_gad_camou]',
                               '$_POST[new_gad_tirc]',
                               '$_POST[new_gad_vision]',
                               '$_POST[new_gad_ecl]',
                               '$_POST[new_gad_esca]',
                               '$_POST[new_gad_fo]',
                               '$_POST[new_gad_mo]',
                               '$_POST[new_gad_de]',
                               '$_POST[new_gad_ma]',
                               '$_POST[new_gad_pl]',
                               '$_POST[new_gad_mines]',
                               '$_POST[new_gad_degats]',
                               '$_POST[new_gad_pourcent]',
                               '$_POST[new_gad_instabilite]',
                               '$_POST[new_gad_discretion]',
                               '$_POST[new_gad_grade1]',
                               '$_POST[new_gad_grade2]',
                               '$_POST[new_gad_grade3]',
                               '$_POST[new_gad_armure]',
                               '$armes',
                               '$_POST[new_gad_stack]')");
    $id=last_id();
    if(!$id){
      erreur(0,"Impossible d'enregistrer le gadget.");
    }
    else{
      $detail = "<ul>
<li>ID : ".$id."</li>
<li>Nom : ".post2html($_POST['new_gad_nom'])."</li>
<li>Description : ".post2html($_POST['new_gad_desc'])."</li>	   
<li>Bonus précision : ".$_POST['new_gad_precision']."</li>
<li>Bonus camouflage : ".$_POST['new_gad_camou']."</li>
<li>Bonus tir camouflé: ".$_POST['new_gad_tirc']."</li>
<li>Bonus vision : ".$_POST['new_gad_vision']."</li>
<li>Eclaireur : ".$_POST['new_gad_ecl']."</li>
<li>Foret : ".$_POST['new_gad_fo']."</li>
<li>Montagne : ".$_POST['new_gad_mo']."</li>
<li>Desert : ".$_POST['new_gad_de']."</li>
<li>Marais : ".$_POST['new_gad_ma']."</li>
<li>Plaine : ".$_POST['new_gad_pl']."</li>
<li>Mines : ".$_POST['new_gad_mines']."</li>
<li>Dégats mines : ".$_POST['new_gad_degats']."</li>
<li>Pourcentage de dégats dans les PV : ".$_POST['new_gad_pourcent']."</li>
<li>Instabilité : ".$_POST['new_gad_instabilite']."</li>
<li>Discrétion : ".$_POST['new_gad_discretion']."</li>
<li>grade 1 : ".$_POST['new_gad_grade1']."</li>
<li>grade 2 : ".$_POST['new_gad_grade2']."</li>
<li>grade 3 : ".$_POST['new_gad_grade3']."</li>
<li>armure : ".$_POST['new_gad_armure']."</li>
<li>arme : ".$armes."</li>
<li>Stack : ".$_POST['new_gad_stack']."</li>
</ul>	
"; 	
      console_log('anim_gadgets',"Création de gadget ".post2html($_POST['new_gad_nom']),$detail,0,0);
      foreach($_POST as $key=>$value){
	if(ereg('new_gadget_camp_[0-9]+',$key)){
	  $camp_id=explode("_",$key);
	  if(!request("INSERT
                               INTO `gadgets_camps` (`gadget`,`camp`)
                                             VALUES ('$id','".$camp_id[3]."')"))
		    erreur(0,"Impossible de rendre disponible le gadget pour un camp.");
	}
	$_POST[$key]="";
      }
    }
  }
}

//*****************************************************************************
// Modification d'un gadget.
//*****************************************************************************

else if(isset($_POST["mod_gad_ok"])){
  $erreur=0;
  if(!(isset($_POST["mod_gad_id"])&&is_numeric($_POST["mod_gad_id"])&&$_POST["mod_gad_id"])){
    erreur(0,"Il faut choisir un gadget.");
    $erreur=1;
  }
  else{
    if(!exist_in_db("SELECT `ID`
                       FROM `gadgets`
                       WHERE `ID`='$_POST[mod_gad_id]'
                       LIMIT 1")){
      erreur(0,"Gadget inconnu.");
      $erreur=1;
    }
  }
  if(!(isset($_POST["mod_gad_nom"])&&$_POST["mod_gad_nom"])){
    erreur(0,"Il faut choisir un nom pour le gadget.");
    $erreur=1;
  }
  else{
    if(exist_in_db("SELECT `ID`
                            FROM `gadgets`
                            WHERE `nom`='".post2bdd($_POST['mod_gad_nom'])."'
                              AND `ID`!='$_POST[mod_gad_id]'
                            LIMIT 1")){
      erreur(0,"Nom de gadget déjà utilisé.");
      $erreur=1;
    }
  }
  if($_POST['mod_gad_armure']<=0 || $_POST['mod_gad_armure']>=8){
    erreur(0,"Type d'armure incorrect.");
    $erreur=1;
  }
  if(!$erreur){
    if(!request("DELETE
                         FROM `gadgets_camps`
                         WHERE `gadget`='$_POST[mod_gad_id]'")){
      erreur(0,"Impossible de modifier les camps ayant accés à ce gadget.");
    }
    else{
      request("OPTIMIZE TABLE `gadgets_camps`");
      foreach($_POST as $key=>$value)
	if(ereg('mod_gadget_camp_[0-9]+',$key)){
	  $camp_id=explode("_",$key);
	  if(!request("INSERT
                                   INTO `gadgets_camps` (`gadget`,`camp`)
                                   VALUES ('$_POST[mod_gad_id]','".$camp_id[3]."')"))
	    erreur(0,"Impossible de rendre disponible ce gadget à un camp.");
	}
    }
    $armes=isset($_POST['mod_gad_arme0'])+isset($_POST['mod_gad_arme1'])*2+isset($_POST['mod_gad_arme2'])*4+isset($_POST['mod_gad_arme3'])*8+isset($_POST['mod_gad_arme4'])*16+isset($_POST['mod_gad_arme5'])*32+isset($_POST['mod_gad_arme6'])*64+isset($_POST['mod_gad_arme7'])*128+isset($_POST['mod_gad_arme8'])*256+isset($_POST['mod_gad_arme9'])*512;
    request("UPDATE `gadgets`
                     SET `nom`='".post2bdd($_POST['mod_gad_nom'])."',
                         `description`='".post2bdd($_POST['mod_gad_desc'])."',
                         `bonus_precision`='$_POST[mod_gad_precision]',
                         `bonus_camou`='$_POST[mod_gad_camou]',
                         `bonus_tir_camou`='$_POST[mod_gad_tirc]',
                         `bonus_vision`='$_POST[mod_gad_vision]',
                         `eclaireur`='$_POST[mod_gad_ecl]',
                         `escalade`='$_POST[mod_gad_esca]',
                         `foret`='$_POST[mod_gad_fo]',
                         `montagne`='$_POST[mod_gad_mo]',
                         `desert`='$_POST[mod_gad_de]',
                         `marais`='$_POST[mod_gad_ma]',
                         `plaine`='$_POST[mod_gad_pl]',
                         `mines`='$_POST[mod_gad_mines]',
                         `degats_mines`='$_POST[mod_gad_degats]',
                         `pourcent_PV`='$_POST[mod_gad_pourcent]',
                         `instabilite`='$_POST[mod_gad_instabilite]',
                         `discretion`='$_POST[mod_gad_discretion]',
                         `grade_1`='$_POST[mod_gad_grade1]',
                         `grade_2`='$_POST[mod_gad_grade2]',
                         `grade_3`='$_POST[mod_gad_grade3]',
                         `armure`='$_POST[mod_gad_armure]',
                         `arme`='$armes',
                         `stack`='$_POST[mod_gad_stack]'
                     WHERE `ID`='$_POST[mod_gad_id]'
                     LIMIT 1");
    if(!affected_rows())
      erreur(0,"Impossible de modifier le gadget.");
    else{
      $detail = "<ul>
<li>ID : ".$_POST['mod_gad_id']."</li>
<li>Nom : ".post2html($_POST['mod_gad_nom'])."</li>
<li>Description : ".post2html($_POST['mod_gad_desc'])."</li>	   
<li>Bonus précision : ".$_POST['mod_gad_precision']."</li>
<li>Bonus camouflage : ".$_POST['mod_gad_camou']."</li>
<li>Bonus tir camouflé: ".$_POST['mod_gad_tirc']."</li>
<li>Bonus vision : ".$_POST['mod_gad_vision']."</li>
<li>Eclaireur : ".$_POST['mod_gad_ecl']."</li>
<li>Foret : ".$_POST['mod_gad_fo']."</li>
<li>Montagne : ".$_POST['mod_gad_mo']."</li>
<li>Desert : ".$_POST['mod_gad_de']."</li>
<li>Marais : ".$_POST['mod_gad_ma']."</li>
<li>Plaine : ".$_POST['mod_gad_pl']."</li>
<li>Mines : ".$_POST['mod_gad_mines']."</li>
<li>Dégats mines : ".$_POST['mod_gad_degats']."</li>
<li>Poucentage de dégats dans les PV : ".$_POST['mod_gad_pourcent']."</li>
<li>Instabilité : ".$_POST['mod_gad_instabilite']."</li>
<li>Discrétion : ".$_POST['mod_gad_discretion']."</li>
<li>grade 1 : ".$_POST['mod_gad_grade1']."</li>
<li>grade 2 : ".$_POST['mod_gad_grade2']."</li>
<li>grade 3 : ".$_POST['mod_gad_grade3']."</li>
<li>armure : ".$_POST['mod_gad_armure']."</li>
<li>arme : ".$armes."</li>
<li>Stack : ".$_POST['new_gad_stack']."</li>
</ul>	
"; 	
      console_log('anim_gadgets',"Modification du gadget ".post2html($_POST['mod_gad_nom']),$detail,0,0);
      foreach($_POST as $key=>$value)
	$_POST[$key]="";
    }
  }
}

//*****************************************************************************
// Suppression d'un gadget.
//*****************************************************************************

else if(isset($_POST["del_gad_ok"],$_POST['del_gad_id'])&&is_numeric($_POST['del_gad_id'])){
  $gadget=my_fetch_array("SELECT `nom`
                        FROM `gadgets`
                        WHERE `ID`='$_POST[del_gad_id]'
                        LIMIT 1");
  if($gadget[0])
    echo'<form method="post" action="anim.php?admin_gadgets">
 <h3>Confirmation :</h3>
 <p>Êtes vous sûr de vouloir supprimer ce gadget ('.bdd2html($gadget[1][0]).')?<br />
'.form_hidden("id",$_POST['del_gad_id']).' 
'.form_submit("del_gad_no","Non").'&nbsp;&nbsp;&nbsp;&nbsp;
'.form_submit("del_gad_yes","Oui").' 
 </p>
 </form>
 <hr />
 ';
  else
    erreur(0,"Gadget inconnu.");
}
else if(isset($_POST["del_gad_yes"],$_POST['del_id'])&&is_numeric($_POST['del_id'])){
  // Suppression de la base du gadget.
  request("DELETE
           FROM `gadgets`
           WHERE `ID`='$_POST[id]'
           LIMIT 1");
  if(affected_rows()){
    console_log('anim_gadgets',"Suppression du gadget ".$_POST['id'],$detail,0,0);

    request("OPTIMIZE TABLE `gadgets`");
  }
  else
    erreur(0,"Impossible de supprimer ce gadget.");
}

//*****************************************************************************
// Préparation du formulaire.
//*****************************************************************************

$script='<script type="text/javascript">
 function afficher_gadget()
 {
  if(!document.getElementById)
    return;
 ';
    $camps=my_fetch_array("SELECT `ID`, `nom`
                           FROM `camps`
                           WHERE ID!='0'
                           ORDER BY `ID` ASC");
$str_camps1=$str_camps2='';
$gadgets=my_fetch_array("SELECT `ID`,`nom`,`description`,bonus_precision,bonus_camou,bonus_tir_camou,bonus_vision,eclaireur,escalade,foret,montagne,desert,plaine,marais,mines,degats_mines,pourcent_PV,instabilite,discretion,grade_1,grade_2,grade_3,armure,arme,stack
                         FROM gadgets
                         ORDER BY ID ASC");
for($i=1;$i<=$gadgets[0];$i++)
{
  $script.='if(document.getElementById("mod_gad_id").value=='.$gadgets[$i]['ID'].')
    {
      mod_gad_nom="'.bdd2js($gadgets[$i]['nom']).'";
      mod_gad_desc="'.bdd2js($gadgets[$i]['description']).'";
      mod_gad_precision="'.$gadgets[$i]['bonus_precision'].'";
      mod_gad_camou="'.$gadgets[$i]['bonus_camou'].'";
      mod_gad_tirc="'.$gadgets[$i]['bonus_tir_camou'].'";
      mod_gad_vision="'.$gadgets[$i]['bonus_vision'].'";
      mod_gad_ecl="'.$gadgets[$i]['eclaireur'].'";
      mod_gad_esca="'.$gadgets[$i]['escalade'].'";
      mod_gad_fo="'.$gadgets[$i]['foret'].'";
      mod_gad_mo="'.$gadgets[$i]['montagne'].'";
      mod_gad_de="'.$gadgets[$i]['desert'].'";
      mod_gad_ma="'.$gadgets[$i]['marais'].'";
      mod_gad_pl="'.$gadgets[$i]['plaine'].'";
      mod_gad_mines="'.$gadgets[$i]['mines'].'";
      mod_gad_degats="'.$gadgets[$i]['degats_mines'].'";
      mod_gad_pourcent="'.$gadgets[$i]['pourcent_PV'].'";
      mod_gad_instabilite="'.$gadgets[$i]['instabilite'].'";
      mod_gad_discretion="'.$gadgets[$i]['discretion'].'";
      mod_gad_grade1="'.$gadgets[$i]['grade_1'].'";
      mod_gad_grade2="'.$gadgets[$i]['grade_2'].'";
      mod_gad_grade3="'.$gadgets[$i]['grade_3'].'";
      mod_gad_armure="'.$gadgets[$i]['armure'].'";
      mod_gad_stack="'.$gadgets[$i]['stack'].'";
 ';
  for($j=0;$j<=9;$j++)
    {
      if($gadgets[$i]['arme'] & pow(2,$j))
	$script.='mod_gad_arme'.$j.'=1;
';
      else
	$script.='mod_gad_arme'.$j.'=0;
';

    }
  for($j=1;$j<=$camps[0];$j++)
    {
      $script.='    var camp_'.$camps[$j]['ID'].'=';
      if(exist_in_db("SELECT `camp`
                      FROM `gadgets_camps`
                      WHERE `gadget`='".$gadgets[$i]['ID']."'
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
$script.='  document.getElementById("mod_gad_nom").value=mod_gad_nom;
  document.getElementById("mod_gad_desc").value=mod_gad_desc;
  document.getElementById("mod_gad_precision").value=mod_gad_precision;
  document.getElementById("mod_gad_camou").value=mod_gad_camou;
  document.getElementById("mod_gad_tirc").value=mod_gad_tirc;
  document.getElementById("mod_gad_vision").value=mod_gad_vision;
  document.getElementById("mod_gad_ecl").value=mod_gad_ecl;
  document.getElementById("mod_gad_esca").value=mod_gad_esca;
  document.getElementById("mod_gad_fo").value=mod_gad_fo;
  document.getElementById("mod_gad_mo").value=mod_gad_mo;
  document.getElementById("mod_gad_de").value=mod_gad_de;
  document.getElementById("mod_gad_ma").value=mod_gad_ma;
  document.getElementById("mod_gad_pl").value=mod_gad_pl;
  document.getElementById("mod_gad_mines").value=mod_gad_mines;
  document.getElementById("mod_gad_degats").value=mod_gad_degats;
  document.getElementById("mod_gad_pourcent").value=mod_gad_pourcent;
  document.getElementById("mod_gad_instabilite").value=mod_gad_instabilite;
  document.getElementById("mod_gad_discretion").value=mod_gad_discretion;
  document.getElementById("mod_gad_grade1").value=mod_gad_grade1;
  document.getElementById("mod_gad_grade2").value=mod_gad_grade2;
  document.getElementById("mod_gad_grade3").value=mod_gad_grade3;
  document.getElementById("mod_gad_armure").value=mod_gad_armure;
  document.getElementById("mod_gad_stack").value=mod_gad_stack;
  document.getElementById("mod_gad_arme0").checked=mod_gad_arme0?"checked":"";
  document.getElementById("mod_gad_arme1").checked=mod_gad_arme1?"checked":"";
  document.getElementById("mod_gad_arme2").checked=mod_gad_arme2?"checked":"";
  document.getElementById("mod_gad_arme3").checked=mod_gad_arme3?"checked":"";
  document.getElementById("mod_gad_arme4").checked=mod_gad_arme4?"checked":"";
  document.getElementById("mod_gad_arme5").checked=mod_gad_arme5?"checked":"";
  document.getElementById("mod_gad_arme6").checked=mod_gad_arme6?"checked":"";
  document.getElementById("mod_gad_arme7").checked=mod_gad_arme7?"checked":"";
  document.getElementById("mod_gad_arme8").checked=mod_gad_arme8?"checked":"";
  document.getElementById("mod_gad_arme9").checked=mod_gad_arme9?"checked":"";
 ';
for($i=1;$i<=$camps[0];$i++)
{
  $script.='  document.getElementById("mod_gadget_camp_'.$camps[$i]['ID'].'").checked=camp_'.$camps[$i]['ID'].'?"checked":"";
 ';
  $str_camps1.=form_check(text2html($camps[$i]['nom']),"new_gadget_camp_".$camps[$i]['ID']).'<br />
 ';
  $str_camps2.=form_check(text2html($camps[$i]['nom']),"mod_gadget_camp_".$camps[$i]['ID']).'<br />
';
}
$script.='}
'.(isset($_POST['mod_gad_ok'])?'':'afficher_gadget();').'
 </script>
 ';
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
$armures=array(7,
	       array(1,"Andromeda"),
	       array(2,"Emaziel"),
	       array(3,"Andromeda et Emaziel"),
	       array(4,"Nayra"),
	       array(5,"Andromeda et Nayra"),
	       array(6,"Emaziel et Nayra"),
	       array(7,"Toutes"));
$ponts=my_fetch_array("SELECT `ID`,`nom`
                       FROM `terrains`
                       WHERE `is_posable`='1'");

//*****************************************************************************
// Affichage du formulaire.
//*****************************************************************************

   echo'<form method="post" action="anim.php?admin_gadgets">
<p>
<h3>Créer un gadget :</h3>
'.form_text("Nom : ","new_gad_nom","","").'<br />
'.form_text("Bonus en précision : ","new_gad_precision","3","").'<br />
'.form_text("Bonus de camouflage : ","new_gad_camou","3","").'<br />
'.form_text("Diminution du malus de camouflage lors des tirs : ","new_gad_tirc","3","").'<br />
'.form_text("Bonus de vision : ","new_gad_vision","2","").'<br />
'.form_text("Bonus à la compétence éclaireur: ","new_gad_ecl","2","").'<br />
'.form_text("Bonus à la compétence escalade: ","new_gad_esca","2","").'<br />
'.form_text("Bonus à la compétence forêt : ","new_gad_fo","2","").'<br />
'.form_text("Bonus à la compétence montagne : ","new_gad_mo","2","").'<br />
'.form_text("Bonus à la compétence désert : ","new_gad_de","2","").'<br />
'.form_text("Bonus à la compétence marais : ","new_gad_ma","2","").'<br />
'.form_text("Bonus à la compétence plaine : ","new_gad_pl","2","").'<br />
'.form_text("Nombre de mines : ","new_gad_mines","2","").'<br />
'.form_text("Dégâts des mines : ","new_gad_degats","3","").'<br />
'.form_text("Pourcentage direct PV : ","new_gad_pourcent","3","").'<br />
'.form_text("Instabiilité : ","new_gad_instabilite","3","").'<br />
'.form_text("Discrétion : ","new_gad_discretion","3","").'<br />
'.form_select("Grade nécessaire en gadget primaire : ","new_gad_grade1",$grades,"").'<br />
'.form_select("Grade nécessaire en gadget secondaire : ","new_gad_grade2",$grades,"").'<br />
'.form_select("Grade nécessaire en gadget tertiaire : ","new_gad_grade3",$grades,"").'<br />
'.form_textarea("Description : ","new_gad_desc",2,25).'<br />
'.$str_camps1.'
<h4>Compatible avec les armes de type :</h4>
'.form_check('Assaut','new_gad_arme0').'<br />
'.form_check('Mitrailleuse','new_gad_arme1').'<br />
'.form_check('Sniper','new_gad_arme2').'<br />
'.form_check('Lance flammes','new_gad_arme3').'<br />
'.form_check('Lance roquettes','new_gad_arme4').'<br />
'.form_check('Mécano','new_gad_arme5').'<br />
'.form_check('Fusil à pompe','new_gad_arme6').'<br />
'.form_check('Corps à corps','new_gad_arme7').'<br />
'.form_check('Médecin','new_gad_arme8').'<br />
'.form_check('Pistolet','new_gad_arme9').'<br />
'.form_select("Armure nécessaire : ","new_gad_armure",$armures,"").'<br />
'.form_text("Position : ","new_gad_stack","2","").'<br />
'.form_submit("new_gad_ok","Créer").'<hr />
<h3>Modifier un gadget :</h3>
'.form_select("Gadget : ","mod_gad_id",$gadgets,"afficher_gadget();").' 
'.form_text("Nom : ","mod_gad_nom","","").'<br />
'.form_text("Bonus en précision : ","mod_gad_precision","3","").'<br />
'.form_text("Bonus de camouflage : ","mod_gad_camou","3","").'<br />
'.form_text("Diminution du malus de camouflage lors des tirs : ","mod_gad_tirc","3","").'<br />
'.form_text("Bonus de vision : ","mod_gad_vision","2","").'<br />
'.form_text("Bonus à la compétence éclaireur: ","mod_gad_ecl","2","").'<br />
'.form_text("Bonus à la compétence escalade: ","mod_gad_esca","2","").'<br />
'.form_text("Bonus à la compétence forêt : ","mod_gad_fo","2","").'<br />
'.form_text("Bonus à la compétence montagne : ","mod_gad_mo","2","").'<br />
'.form_text("Bonus à la compétence désert : ","mod_gad_de","2","").'<br />
'.form_text("Bonus à la compétence marais : ","mod_gad_ma","2","").'<br />
'.form_text("Bonus à la compétence plaine : ","mod_gad_pl","2","").'<br />
'.form_text("Nombre de mines : ","mod_gad_mines","2","").'<br />
'.form_text("Dégâts des mines : ","mod_gad_degats","3","").'<br />
'.form_text("Pourcentage direct PV : ","mod_gad_pourcent","3","").'<br />
'.form_text("Instabiilité : ","mod_gad_instabilite","3","").'<br />
'.form_text("Discrétion : ","mod_gad_discretion","3","").'<br />
'.form_select("Grade nécessaire en gadget primaire : ","mod_gad_grade1",$grades,"").'<br />
'.form_select("Grade nécessaire en gadget secondaire : ","mod_gad_grade2",$grades,"").'<br />
'.form_select("Grade nécessaire en gadget tertiaire : ","mod_gad_grade3",$grades,"").'<br />
'.form_textarea("Description : ","mod_gad_desc",2,25).'<br />
'.$str_camps2.' 
<h4>Compatible avec les armes de type :</h4>
'.form_check('Assaut','mod_gad_arme0').'<br />
'.form_check('Mitrailleuse','mod_gad_arme1').'<br />
'.form_check('Sniper','mod_gad_arme2').'<br />
'.form_check('Lance flammes','mod_gad_arme3').'<br />
'.form_check('Lance roquettes','mod_gad_arme4').'<br />
'.form_check('Mécano','mod_gad_arme5').'<br />
'.form_check('Fusil à pompe','mod_gad_arme6').'<br />
'.form_check('Corps à corps','mod_gad_arme7').'<br />
'.form_check('Médecin','mod_gad_arme8').'<br />
'.form_check('Pistolet','mod_gad_arme9').'<br />
'.form_select("Armure nécessaire : ","mod_gad_armure",$armures,"").'<br />
'.form_text("Position : ","mod_gad_stack","2","").'<br />
'.form_submit("mod_gad_ok","Modifier").'<hr />
<h3>Supprimer un gadget : </h3>
'.form_select("Gadget : ","del_gad_id",$gadgets,"").' 
'.form_submit("del_gad_ok","Supprimer").'
</p>
</form>
'.$script;
?>
