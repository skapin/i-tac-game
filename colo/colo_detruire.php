<?php
//***************************************************************************
// Destruction de la compagnie.
//***************************************************************************
if(isset($_POST['compa_destroy_confirm'],$_POST['compa_destroy_ok']))
{
  // D'abord, on est plus colonel.
  /*  request('UPDATE persos
           SET grade=0,
               ordresconfi=0,
               forum_em=0,
               niveau_gene=0 
           WHERE ID='.$_SESSION['com_perso']);
  if(affected_rows())
    {
      // Puis on vire les droits de tous les membres de la compagnie.
      request('UPDATE persos
               SET colo_HRP=0,
                   colo_RP=0,
                   colo_colo=0,
                   colo_criteres=0,
                   colo_droits=0,
                   colo_sigle=0,
                   colo_valider=0,
                   colo_ordres=0,
                   colo_virer=0,
                   colo_grades=0,
                   compagnie=1,
                   ordrescompa=0,
                   forum_compa=0,
                   niveau_compa=0 
               WHERE compagnie='.$perso['ID_compa']);
      if(affected_rows())// Enfin, on détruit la compagnie.
	{
	  request('DELETE
                   FROM compagnies
                   WHERE ID='.$perso['ID_compa'].' LIMIT 1');
	  // Et on vire les grades associés à la compagnie.
	  request('UPDATE persos
INNER JOIN grades
  ON persos.grade=grades.ID 
SET grade=0
WHERE grades.compa='.$perso['ID_compa']);
	  request('DELETE
FROM grades
WHERE compa='.$perso['ID_compa']);
	  if(affected_rows())
	    request('OPTIMIZE TABLE grades');
	  // On vire la compagnie du forum
	  forum_del_compa($perso['ID_compa']);
	  update_droits_forum($_SESSION['com_ID']);
	  include('sources/monperso.php');
	  echo 'Votre compagnie a bien été dissoute.
';
	}
	}*/
  require('../objets/bdd.php');
  require('../objets/compagnie.php');
  $connexion = new comBdd('','','','','',$GLOBALS['db']);
  $compa = new comCompagnie($perso['ID_compa'],$connexion);
  $compa->destroyBy($_SESSION['com_perso']);
  include('../sources/monperso.php');
  echo 'Votre compagnie a bien été dissoute.
';
}

//***************************************************************************
// Afficher le formulaire.
//***************************************************************************
echo'<form method="post" action="compagnie.php?colo_detruire">
<p>
',form_check('Il n\'y aura pas de demande de confirmation : ','compa_destroy_confirm'),'
',form_submit('compa_destroy_ok','Destruction'),'<br />
</p>
</form>
';
?>