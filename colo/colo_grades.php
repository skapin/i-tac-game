<?php
//****************************************************************************
// On donne un grade à quelqu'un.
//****************************************************************************
if(isset($_POST['give_grade_ok'],$_POST['give_grade_id'],$_POST['give_grade_perso']) &&
   is_numeric($_POST['give_grade_id']) &&
   is_numeric($_POST['give_grade_perso']))
  {
    $niveag=my_fetch_array('SELECT niveau
                            FROM grades
                            WHERE ID='.$_POST['give_grade_id'].'
                            AND valide=1
                            AND compa='.$perso['ID_compa']. '
                            AND priorite<='.$perso['niveau_compa'].'
                            AND camp=0');
    $niveaup=my_fetch_array('SELECT grades.niveau
                             FROM persos
                               LEFT OUTER JOIN grades
                                 ON grades.ID=persos.grade
                             WHERE persos.ID='.$_POST['give_grade_perso'].'
                               AND persos.armee='.$perso['armee'].'
                               AND persos.compagnie='.$perso['ID_compa'].'
                               AND persos.compagnie!=1
                               AND persos.niveau_compa<'.$perso['niveau_compa'].'
                               AND (grades.ID IS NULL OR grades.camp=0 AND grades.compa='.$perso['ID_compa'].')');
    if($niveag[0]&&
       $niveaup[0]&&
       ($niveag[1][0]==$niveaup[1][0] || !$niveag[1][0]&&!$niveaup[1][0]))
      {
	request('UPDATE persos SET grade='.$_POST['give_grade_id'].' WHERE ID='.$_POST['give_grade_perso']);
	if(!affected_rows())
	  add_message(3,'Erreur SQL');
      }
    else
      add_message(3,'Erreur dans les données fournies.');
  }
//****************************************************************************
// On enlève le grade de quelqu'un.
//****************************************************************************
if(isset($_POST['drop_grade_ok'],$_POST['drop_grade_perso'])&&is_numeric($_POST['drop_grade_perso']))
  {
    // recuperation des stats du grade.
    $grade=my_fetch_array('SELECT grades.niveau 
FROM persos
INNER JOIN grades
ON persos.grade=grades.ID
WHERE persos.ID='.$_POST['drop_grade_perso'].'');
    if($grade[0])
      {
	request('UPDATE persos
SET persos.grade='.$grade[1]['niveau'].'
WHERE persos.ID='.$_POST['drop_grade_perso'].'
AND persos.niveau_compa<'.$perso['niveau_compa'].'
AND grades.camp=0
AND grades.compa='.$perso['ID_compa'].'
AND persos.compagnie='.$perso['ID_compa']);
      }
  }

$lvl=array(4,
	   array(0,'Soldat'),
	   array(1,'Colonel'),
	   array(2,'Général'),
	   array(3,'Général en chef'));
$persos=my_fetch_array('SELECT persos.ID,
                               CONCAT_WS(\'(\',
                                         persos.nom,
                                         CONCAT_WS(\'-\',
                                         camps.initiale,
                                         compagnies.initiales,
                                         CONCAT_WS(\')\',persos.ID,CONCAT_WS(\' : \',\'\',grades.nom)))) AS nom
                        FROM persos
                          INNER JOIN compagnies
                            ON compagnies.ID=persos.compagnie
                          INNER JOIN camps
                            ON camps.ID=persos.armee
                          LEFT OUTER JOIN grades
                            ON grades.ID=persos.grade
                         WHERE persos.niveau_compa<'.$perso['niveau_compa'].'
                           AND persos.armee='.$perso['armee'].'
                           AND persos.compagnie='.$perso['ID_compa'].'
                           AND (grades.ID IS NULL OR grades.camp=0 AND grades.compa='.$perso['ID_compa'].')
                        ORDER BY persos.nom ASC');
$grades=my_fetch_array('SELECT grades.ID,
                               grades.nom
                        FROM grades
                        WHERE grades.ID>3
                          AND grades.valide=1
                          AND grades.priorite<='.$perso['niveau_compa'].'
                          AND grades.compa='.$perso['ID_compa'].'
                          AND grades.camp=0
                        ORDER BY grades.nom ASC');

/*echo'<form method="post" action="anim.php?admin_grades">
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
</form>*/
echo'<form method="post" action="compagnie.php?colo_grades">
<h2>Donner un grade.</h2>
<p>
',form_select('Donner à : ','give_grade_perso',$persos,''),'<br />
',form_select('le grade : ','give_grade_id',$grades,''),'<br />
',form_submit('give_grade_ok','Ok'),'
</p>
<dl>
<dt>Réservé à :</dl>

<dd id="grade_reserve"></dd>
<dt>Correspond à :</dl>
<dd id="grade_correspond"></dd>
</dl>
</form>
<form method="post" action="compagnie.php?colo_grades">
<h2>Enlever un grade.</h2>
<p>
',form_select('Virer le grade de : ','drop_grade_perso',$persos,''),'<br />
',form_submit('drop_grade_ok','Ok'),'
</p>
</form>
';//.$script;
?>