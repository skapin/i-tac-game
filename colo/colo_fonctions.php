<?php
function autorisation($truc){
  global $perso,$listeDroits;
  if(!empty($perso['colo_'.$truc]) && !$listeDroits['colo'][$truc][0] ||
     !empty($perso['forum_'.$truc]) && $listeDroits['colo'][$truc][0]){
     return 1;
  }
  return 0;
}

function menu(){
  global $perso;
  $postulants=my_fetch_array('SELECT COUNT(*)
                              FROM demande_compagnie
INNER JOIN persos
ON persos.ID = demande_compagnie.ID
                              WHERE creation=0
AND persos.compagnie=1
                               AND compa='.$perso['ID_compa']);

  if(autorisation('criteres'))
    echo lien('compagnie.php?colo_criteres','Critères d\'inscriptions');
  if(autorisation('RP'))
    echo lien('compagnie.php?colo_desc','Description de la compagnie');
  if(autorisation('droits'))
    echo lien('compagnie.php?colo_droits','Droits');
  echo lien('compagnie.php?colo_liste','Membres');
  if(autorisation('ordres'))
    echo lien('compagnie.php?colo_ordres','Ordres');
  if(autorisation('valider'))
    echo lien('compagnie.php?colo_valider','Postulants ('.$postulants[1][0].')');
  if(autorisation('virer'))
    echo lien('compagnie.php?colo_virer','Virer un membre');
  if(autorisation('colo')){
      echo lien('compagnie.php?colo_colo','<br />
Nommer un nouveau colonel
<br />');
      echo lien('compagnie.php?colo_detruire','<br />
Détruire la compagnie');
    }
}
function lien($page,$texte)
{
  return '<li><a href="'.$page.'">'.$texte.'</a></li>
';
}

function content()
{
  global $perso,$time;
  switch($_SERVER['QUERY_STRING'])
    {
      case'colo_ordres':
	if(autorisation('ordres'))
	  include('../colo/colo_ordres.php');
      break;
      case'colo_liste':
	include('../colo/colo_liste.php');
      break;
      case'colo_criteres':
	if(autorisation('criteres'))
	  include('../colo/colo_criteres.php');
      break;
      case'colo_valider':
	if(autorisation('valider'))
	  include('../colo/colo_valider.php');
      break;
      case'colo_droits':
	if(autorisation('droits'))
	  include('../colo/colo_droits.php');
      break;
      case'colo_desc':
	if(autorisation('RP'))
	  include('../colo/colo_description.php');
      break;
      case'colo_virer':
	if(autorisation('virer'))
	  include('../colo/colo_virer.php');
      break;
      case'colo_colo':
	if(autorisation('colo'))
	  include('../colo/colo_colo.php');
      break;
      case'colo_detruire':
	if(autorisation('colo'))
	  include('../colo/colo_detruire.php');
      break;
    default:
      break;
      }
}
?>