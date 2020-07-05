<?php
$order=' ORDER BY ID ASC';
if(isset($_POST['order']))
  {
    switch($_POST['order'])
      {
      case 'mat_asc':
	$order='ORDER BY ID ASC';
	break;
      case 'mat_desc':
	$order='ORDER BY ID DESC';
	break;
      case 'nom_asc':
	$order='ORDER BY nom ASC';
	break;
      case 'nom_desc':
	$order='ORDER BY nom DESC';
	break;
      case 'gradereel_asc':
	$order='ORDER BY grade_reel ASC';
	break;
      case 'gradereel_desc':
	$order='ORDER BY grade_reel DESC';
	break;
      case 'gradecompa_asc':
	$order='ORDER BY niveau_compa ASC';
	break;
      case 'gradecompa_desc':
	$order='ORDER BY niveau_compa DESC';
	break;
      case 'gradearmee_asc':
	$order='ORDER BY niveau_gene ASC';
	break;
      case 'gradearmee_desc':
	$order='ORDER BY niveau_gene DESC';
	break;
      default:
	break;
      }
  }
$select='persos.ID,
persos.nom,'.select_droit('RP').select_droit('colo').select_droit('criteres').select_droit('droits').select_droit('valider').select_droit('ordres').select_droit('virer').'
grade_reel,
niveau_compa,
niveau_gene,
ordrescompa,
forum_compa,
forum_mcompa,
grades.nom AS grade,
armee AS camp,
persos.date_last_bouge,
persos.used_1,
persos.used_2,
persos.used_3,
persos.date_last_tir,
persos.PM,
persos.date_last_mouv,
persos.date_last_recuptir,
persos.lastordres,
grades.ID AS nbr_grade';

$persos=my_fetch_array('SELECT '.$select.'
FROM persos
LEFT OUTER JOIN grades
  ON persos.grade=grades.ID
WHERE compagnie=\''.$perso['ID_compa'].'\'
'.$order);

// Affichage des membres de la compagnie.
if($persos[0])
  {
    echo'<table id="liste_compa">
<tr><th>Activit&eacute;</th><th>Matricule</th><th>Nom</th><th>Grade</th><th>Niveau dans la compagnie</th><th>Niveau dans le camp</th><th>Voit les ordres ?</th><th>Acc&eacute;s au forum</th><th>Modère le forum</th>',th_droit('RP','Description'),th_droit('ordres','ordres'),th_droit('criteres','critères d\'accés'),th_droit('valider','Validation des postulants'),th_droit('virer','Virer des membres'),th_droit('droits','Gestion des droits'),th_droit('colo','Colonel'),'</tr>
';
    for($i=1;$i<=$persos[0];$i++){
      $actif=0;
      $alt='inactif';
      $persos[$i]['last_login']=max($persos[$i]['date_last_bouge'],
				    $persos[$i]['used_1'],
				    $persos[$i]['used_2'],
				    $persos[$i]['used_3'],
				    $persos[$i]['date_last_tir'],
				    $persos[$i]['PM'],
				    $persos[$i]['date_last_mouv'],
				    $persos[$i]['date_last_recuptir'],
				    $persos[$i]['lastordres']);
      if(time()-$persos[$i]['last_login']<=86400){
	$actif=2;
	$alt='actif';
      }
      else if(time()-$persos[$i]['last_login']<=259200){
	$actif=1;
	$alt='peu actif';
      }
      echo'<tr><td><img src="styles/'.$_SESSION['skin'].'/actif_'.$actif.'.jpg" alt="'.$alt.'"/></td><td>',$persos[$i]['ID'],'</td><td>',bdd2html($persos[$i]['nom']),'</td><td>',bdd2html($persos[$i]['grade']?(grade_spec_autre($persos[$i]['camp'],$persos[$i]['nbr_grade'],$persos[$i]['grade']).' ('.numero_camp_grade($persos[$i]['camp'],$persos[$i]['grade_reel']).')'):numero_camp_grade($persos[$i]['camp'],$persos[$i]['grade_reel'])),'</td><td>',$persos[$i]['niveau_compa'],'</td><td>',$persos[$i]['niveau_gene'],'</td><td>',($persos[$i]['ordrescompa']?'oui':'non'),'</td><td>',($persos[$i]['forum_compa']?'oui':'non'),'</td><td>',($persos[$i]['forum_mcompa']?'oui':'non'),'</td>',td_droits('RP',$persos[$i]),td_droits('ordres',$persos[$i]),td_droits('criteres',$persos[$i]),td_droits('valider',$persos[$i]),td_droits('virer',$persos[$i]),td_droits('droits',$persos[$i]),td_droits('colo',$persos[$i]),'</tr>
';
    }
    echo'</table>
';
}

function select_droit($truc)
{
  return (autorisation($truc)?'colo_'.$truc.',
':'');
}

function th_droit($truc,$texte)
{
  return (autorisation($truc)?'<th>'.$texte.'</th>':'');
}

function td_droits($truc,$perso)
{
  return (autorisation($truc)?'<td>'.($perso['colo_'.$truc]?'oui':'non').'</td>':'');
}
?>