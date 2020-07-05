<?php
if(empty($userID)){
  API_erreur('notvalid');
}
else{
  $persos=$db->fetch('SELECT persos.ID AS ID_perso,
persos.nom AS nom_perso,
camps.ID AS ID_camp,
camps.nom AS nom_camp,
camps.initiale AS init_camp,
compagnies.ID AS ID_compa,
compagnies.nom AS nom_compa,
compagnies.initiales AS init_compa
FROM persos
INNER JOIN camps
ON persos.armee=camps.ID
INNER JOIN compagnies
ON persos.compagnie=compagnies.ID
WHERE compte='.$userID);
  foreach($persos AS $perso){
    echo'
 <personnage ID="'.$perso['ID_perso'].'">
  <nom>'.bdd2xml($perso['nom_perso']).'</nom>
  <camp ID="'.$perso['ID_camp'].'" init="'.bdd2xml($perso['init_camp']).'">'.bdd2xml($perso['nom_camp']).'</camp>
  <compagnie ID="'.$perso['ID_compa'].'" init="'.bdd2xml($perso['init_compa']).'">'.bdd2xml($perso['nom_compa']).'</compagnie>
 </personnage>';
  }
}
?>