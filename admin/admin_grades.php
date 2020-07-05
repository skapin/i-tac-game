<?php
//***************************************************************************
// Création d'un grade.
//***************************************************************************
if(isset($_POST['new_grade_ok']))
  {
    $erreur=0;
    if(exist_in_db("SELECT ID
                    FROM grades
                    WHERE nom='".post2bdd($_POST['new_grade_nom'])."'
                    LIMIT 1"))
      {
	add_message(0,"Attention : nom déjà utilisé.");
      }
    if(isset($_POST['new_grade_camp']) && !is_numeric($_POST['new_grade_camp']))
      $erreur=1;
    if(isset($_POST['new_grade_comp']) && !is_numeric($_POST['new_grade_comp']))
      $erreur=1;
    if(!isset($_POST['new_grade_niveau']) || !is_numeric($_POST['new_grade_niveau']) || $_POST['new_grade_niveau']<0 || $_POST['new_grade_niveau']>100)
      {
	erreur(0,"Valeur de niveau nécessaire incorrect.");
	$erreur=1;
      }
    if(!isset($_POST['new_grade_lvl']) || !is_numeric($_POST['new_grade_lvl']) || $_POST['new_grade_lvl']<0 || $_POST['new_grade_lvl']>3)
      {
	erreur(0,"Valeur de correspondance incorrect.");
	$erreur=1;
      }
    if(!$erreur)
      {
	$erreur='';
	$values='';
	if(isset($_POST['new_grade_camp_conf'],$_POST['new_grade_camp'])&&$_POST['new_grade_camp'])
	  {
	    $erreur='`camp`,';
	    $values="'$_POST[new_grade_camp]',";
	  }
	else if(isset($_POST['new_grade_comp_conf'],$_POST['new_grade_comp'])&&$_POST['new_grade_comp'])
	  {
	    $erreur='`compa`,';
	    $values="'$_POST[new_grade_comp]',";
	  }
	request("INSERT
                 INTO grades (".$erreur."
                              `nom`,
                              `valide`,
                              `niveau`,
                              `priorite`)
                      VALUES (".$values."
                              '".post2bdd($_POST['new_grade_nom'])."',
                              '1',
                              '$_POST[new_grade_lvl]',
                              '$_POST[new_grade_niveau]')");
	if(!last_id())
	  erreur(0,"Impossible d'enregistrer le grade en BdD");
	  else {
	    $detail = "<ul>
<li>ID : ".last_id()."</li>
<li>Nom : ".post2html($_POST['new_grade_nom'])."</li>
<li>Valide : ".'1'."</li>
<li>Level : ".$_POST['new_grade_lvl']."</li>
<li>Priorite : ".$_POST['new_grade_niveau']."</li> 	
</ul>	
"; 	
  	  console_log('anim_grades',"Création du grade ".$_POST['new_grade_nom'],$detail,0,0);
		
	  }
      }
  }
//****************************************************************************
// On donne un grade à quelqu'un.
//****************************************************************************
if(isset($_POST['give_grade_ok'],$_POST['give_grade_id'],$_POST['give_grade_perso'])&&is_numeric($_POST['give_grade_id'])&&is_numeric($_POST['give_grade_perso']))
  {
    $niveag=my_fetch_array("SELECT niveau,
                                   camp,
                                   compa
                            FROM grades
                            WHERE ID='$_POST[give_grade_id]'
                            AND valide='1'");
    $niveaup=my_fetch_array("SELECT grades.niveau,
                                    persos.armee,
                                    persos.compagnie 
                             FROM persos
                               LEFT OUTER JOIN grades
                                 ON grades.ID=persos.grade
                             WHERE persos.ID='$_POST[give_grade_perso]'");
    if($niveag[0]&&$niveaup[0]&&($niveag[1][0]==$niveaup[1][0] || !$niveag[1][0]&&!$niveaup[1][0])&&($niveag[1][1]==$niveaup[1][1] || !$niveag[1][1])&&($niveag[1][2]==$niveaup[1][2]||$niveag[1][2]==1))
      {
	request("UPDATE persos SET grade='$_POST[give_grade_id]' WHERE ID='$_POST[give_grade_perso]'");
	if(!affected_rows())
	  erreur(0,"Erreur SQL");
	else {
		$detail = "<ul>
<li>ID du grade : ".$_POST['give_grade_id']."</li>
<li>ID perso : ".$_POST['give_grade_perso']."</li> 	
</ul>	
"; 	
  	  console_log('anim_grades',"Attribution du grade ".$_POST['give_grade_id'].' au perso '.$_POST['give_grade_perso'],$detail,0,0);
	}  
      }
    else
      erreur(0,"Erreur dans les données fournies.");
  }
//****************************************************************************
// On enlève le grade de quelqu'un.
//****************************************************************************
if(isset($_POST['drop_grade_ok'],$_POST['drop_grade_perso'])&&is_numeric($_POST['drop_grade_perso']))
  {
    $grade=my_fetch_array('SELECT niveau
FROM grades
INNER JOIN persos
ON grades.ID=persos.grade
WHERE persos.ID='.$_POST['drop_grade_perso']);
    request('UPDATE persos
SET grade='.$grade[1]['niveau'].'
WHERE persos.ID='.$_POST['drop_grade_perso']);
    console_log('anim_grades','Dégradation du perso '.$_POST['drop_grade_perso'],'',0,0);
  }
  
//****************************************************************************
// On détruit un grade.
//****************************************************************************
if(isset($_POST['del_grade_ok'],$_POST['del_grade_id'])&&is_numeric($_POST['del_grade_id'])&&$_POST['del_grade_id']>3)
  {
    if(request("UPDATE persos
               INNER JOIN grades
                 ON persos.grade=grades.ID
             SET persos.grade=grades.niveau
             WHERE grades.ID='$_POST[del_grade_id]'"))
      request("DELETE FROM grades WHERE grades.ID='$_POST[del_grade_id]' LIMIT 1");
    if(affected_rows())
    {
      console_log('anim_grades',"Destruction du grade ".$_POST['del_grade_id'],'',0,0);
      request("OPTIMIZE TABLE grades");
    }
  }

$lvl=array(4,
	   array(0,"Soldat"),
	   array(1,"Colonel"),
	   array(2,"Général"),
	   array(3,"Général en chef"));
$compagnies=my_fetch_array("SELECT ID,nom
                            FROM compagnies
                            WHERE valide='1'
                            ORDER BY nom ASC");
$camps=my_fetch_array("SELECT ID, nom
                       FROM camps");
$persos=my_fetch_array("SELECT persos.ID,
                               CONCAT_WS('(',
                                         persos.nom,
                                         CONCAT_WS('-',
                                         camps.initiale,
                                         compagnies.initiales,
                                         CONCAT_WS(')',persos.ID,CONCAT_WS(' : ','',grades.nom)))) AS nom
                        FROM persos
                          INNER JOIN compagnies
                            ON compagnies.ID=persos.compagnie
                          INNER JOIN camps
                            ON camps.ID=persos.armee
                          LEFT OUTER JOIN grades
                            ON grades.ID=persos.grade
                        ORDER BY persos.ID ASC");
$grades=my_fetch_array("SELECT grades.ID,
                               grades.nom,
                               compagnies.nom AS compa,
                               camps.nom AS camp,
                               grades.niveau
                        FROM grades
                          LEFT OUTER JOIN compagnies
                            ON compagnies.ID=grades.compa
                          LEFT OUTER JOIN camps
                            ON camps.ID=grades.camp
                        WHERE grades.ID>'3'
                          AND grades.valide='1'
                        ORDER BY niveau ASC, camp ASC, compa ASC");

$script='<script type="text/javascript">
function showGradeToGive()
{
  if(!document.getElementById)
    return;
';
$script_del='function showGradeToDelete()
{
  if(!document.getElementById)
    return;
';
for($i=1;$i<=$grades[0];$i++)
  {
    $script.='  if(document.getElementById("give_grade_id").value=="'.$grades[$i]['ID'].'")
  {
    reserve="'.($grades[$i]['camp']?' camp : '.bdd2js(bdd2html($grades[$i]['camp'])):($grades[$i]['compa']>1?' la compagnie : '.bdd2js(bdd2html($grades[$i]['compa'])):'aucune restriction')).'";
    correspond="'.($grades[$i]['niveau']>2?'général en chef':($grades[$i]['niveau']>1?'général':($grades[$i]['niveau']>0?'colonel':'soldat'))).'";
  }
';
    $script_del.='  if(document.getElementById("give_grade_id").value=="'.$grades[$i]['ID'].'")
  {
    reserve="'.($grades[$i]['camp']?' camp : '.bdd2js(bdd2html($grades[$i]['camp'])):($grades[$i]['compa']>1?' la compagnie : '.bdd2js(bdd2html($grades[$i]['compa'])):'aucune restriction')).'";
    correspond="'.($grades[$i]['niveau']>2?'général en chef':($grades[$i]['niveau']>1?'général':($grades[$i]['niveau']>0?'colonel':'soldat'))).'";
  }
';
  }
$script.='  document.getElementById("grade_reserve").innerHTML=reserve;
  document.getElementById("grade_correspond").innerHTML=correspond;
}
showGradeToGive();
'.$script_del.'  document.getElementById("del_grade_reserve").innerHTML=reserve;
  document.getElementById("del_grade_correspond").innerHTML=correspond;
}
showGradeToDelete();
</script>
';
echo'<form method="post" action="anim.php?admin_grades">
<h2>Créer un grade.</h2>
<p>
 '.form_text("Nom : ","new_grade_nom","","").'<br />
 '.form_check("","new_grade_camp_conf").' 
 '.form_select("Réservé au camp : ","new_grade_camp",$camps,"").'<br />
 '.form_check("","new_grade_comp_conf").'
 '.form_select("Réservé à la compagnie : ","new_grade_comp",$compagnies,"").'<br />
 '.form_select("Correspond à : ","new_grade_lvl",$lvl,"").'<br />
 '.form_text("Niveau nécessaire pour le donner : ","new_grade_niveau","","").'<br />
 '.form_submit("new_grade_ok","créer").' 
</p>
<form method="post" action="anim.php?admin_grades">
<h2>Supprimer un grade.</h2>
<p>
'.form_select("Supprimer le grade : ","del_grade_id",$grades,"showGradeToDelete();").'<br />
'.form_submit("del_grade_ok","Ok").'
</p>
<dl>
<dt>Réservé à :</dl>
<dd id="del_grade_reserve"></dd>
<dt>Correspond à :</dl>
<dd id="del_grade_correspond"></dd>
</dl>
</form>
</form>
<form method="post" action="anim.php?admin_grades">
<h2>Donner un grade.</h2>
<p>
'.form_select("Donner à : ","give_grade_perso",$persos,"").'<br />
'.form_select("le grade : ","give_grade_id",$grades,"showGradeToGive();").'<br />
'.form_submit("give_grade_ok","Ok").'
</p>
<dl>
<dt>Réservé à :</dl>
<dd id="grade_reserve"></dd>
<dt>Correspond à :</dl>
<dd id="grade_correspond"></dd>
</dl>
</form>
<form method="post" action="anim.php?admin_grades">
<h2>Enlever un grade.</h2>
<p>
'.form_select("Virer le grade de : ","drop_grade_perso",$persos,"").'<br />
'.form_submit("drop_grade_ok","Ok").'
</p>
</form>
'.$script;
?>