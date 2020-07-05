<?php
  //***************************************************************************
  // Creation d'un PNJ
  //***************************************************************************
if(!empty($_POST['new_pnj_nom'])){
  echo createPerso(post2text($_POST['new_pnj_nom']),0,(int)$_POST['new_pnj_camp']);
}
// ITAC - LD - 2009-12-29
// ITAC - LD - BEGIN
// Suppression des PNJ
// Lors de la suppression d'un PNJ, il faut aussi verifier si il n'etait pas
// attribue a un perso. si c'est le cas il faut aussi supprimer cette info.
if(!empty($_POST['del_pnj_id']))
{
	$compte=my_fetch_array('SELECT pnj FROM pnj_compte WHERE pnj="' . $_POST['del_pnj_id'] . '"');
	if($compte != 0)
	{
		request('DELETE FROM pnj_compte WHERE pnj='.$_POST['del_pnj_id']);
	}
	echo delPerso(post2text($_POST['del_pnj_id']));
}
// ITAC - LD - END
/**
 * Donation d'un PNJ
 */
if(!empty($_POST['give_pnj_ok']) &&
   !empty($_POST['give_pnj_to']) &&
   !empty($_POST['give_pnj_id']) &&
   is_numeric($_POST['give_pnj_id'])){
  if(!exist_in_db('SELECT ID FROM persos WHERE compte=0 AND ID='.$_POST['give_pnj_id'])){
    echo'<p>PNJ introuvable</p>';
  }
  else{
    $compte=my_fetch_array('SELECT ID FROM compte WHERE login="'.post2bdd($_POST['give_pnj_to']).'"');
    if(empty($compte[0])){
      echo'<p>Compte inexistant</p>';
    }
    else{
      if(request('INSERT INTO pnj_compte (compte,pnj) VALUES('.$compte[1]['ID'].','.$_POST['give_pnj_id'].')')){
	echo'<p>Contr&ocirc;le du PNJ donn&eacute;.';
      }
    }
  }
 }

/**
 * Retirage du controle d'un PNJ
 */
if(!empty($_POST['recall_pnj']) &&
   !empty($_POST['recall_compte']) &&
   is_numeric($_POST['recall_compte']) &&
   !empty($_POST['recall_id']) &&
   is_numeric($_POST['recall_id'])){
  request('DELETE FROM pnj_compte WHERE compte='.$_POST['recall_compte'].' AND pnj='.$_POST['recall_id']);
 }
//***************************************************************************
// Préparation du formulaire
//***************************************************************************
$camps=my_fetch_array('SELECT ID,nom FROM camps');
$pnjs=my_fetch_array('SELECT ID,nom
FROM persos
WHERE compte<=0 ORDER BY nom ASC');
//***************************************************************************
// Affichage du formulaire
//***************************************************************************
echo'<h2>Cr&eacute;er un nouveau PNJ</h2>
<form method="post" action="anim.php?admin_pnjs">
'.form_select('Camp : ','new_pnj_camp',$camps,'').'<br />
'.form_text('Nom : ','new_pnj_nom','','').'<br />
'.form_submit('new_pnj_ok','Ok').'</p>
</form>
<h2>Donner le controle d\'un PNJ</h2>
<form method="post" action="anim.php?admin_pnjs">
<p>
'.form_select('Donner ','give_pnj_id',$pnjs,'').' 
'.form_text('&agrave; ','give_pnj_to','','').'<br />
'.form_submit('give_pnj_ok','Ok').'</p>
</form>
<form method="post" action="anim.php?admin_pnjs">
<p><h2>Supprimer un PNJ</h2>
'.form_select('Supprimer : ','del_pnj_id',$pnjs,'').'<br />
'.form_submit('del_pnj_ok','Ok').'</p>
</form>
';
$control=my_fetch_array('SELECT compte.ID AS ID1,
compte.login AS nom1,
persos.ID AS ID2,
persos.nom AS nom2
FROM pnj_compte
INNER JOIN compte
ON compte.ID = pnj_compte.compte
INNER JOIN persos
ON persos.ID = pnj_compte.pnj'
// ITAC - LD - 2010-01-18
// ITAC - LD - BEGIN
// On trie non plus par ID mais par nom.
// ORDER BY compte.ID ASC');
. ' ORDER BY persos.nom ASC');
// ITAC - LD - END

$tri=array();
for($i=1;$i<=$control[0];$i++){
  if(!isset($tri[$control[$i]['ID1']])){
    $tri[$control[$i]['ID1']]=array();
  }
  $tri[$control[$i]['ID1']][]=array('login'=>$control[$i]['nom1'],
				    'ID'=>$control[$i]['ID2'],
				    'nom'=>$control[$i]['nom2']);
 }

echo'
<h2>Liste des PNJ</h2>
<table>
 <tr>
  <th>Compte</th>
  <th>PNJ</th>
  <th>Annuler</th>
 </tr>';
foreach($tri AS $compte=>$pnj){
  echo'
 <tr>
  <td rowspan="'.count($pnj).'">'.bdd2html($pnj[0]['login']).'</td>';
  $i=0;
  foreach($pnj AS $infos){
    if($i!=0){
      echo'
 <tr>';
    }
    $i=1;
  echo'
  <td>'.bdd2html($infos['nom']).' ('.$infos['ID'].')</td>
  <td>
   <form method="post" action="anim.php?admin_pnjs">
    <input type="hidden" name="recall_compte" value="'.$compte.'" />
    <input type="hidden" name="recall_id" value="'.$infos['ID'].'" />
    <input type="submit" name="recall_pnj" value="Annuler" />
   </form>
  </td>
 </tr>';
  }
}
echo'
</table>';
?>
