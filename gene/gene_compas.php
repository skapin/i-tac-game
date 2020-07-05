<?php
//***************************************************************************
// Annuler une création de compagnie.
//***************************************************************************
if(isset($_POST['compa_annuler_confirm'],$_POST['compa_annuler_ok'],$_POST['compa_valider_id']) &&
   is_numeric($_POST['compa_valider_id']))
{
  $compa=my_fetch_array('SELECT compagnies.ID,
                         persos.ID as colonel
                  FROM compagnies
                    INNER JOIN demande_compagnie
                       ON demande_compagnie.compa=compagnies.ID 
                    INNER JOIN persos
                       ON demande_compagnie.ID=persos.ID 
                  WHERE demande_compagnie.creation=1
                    AND compagnies.valide=0
                    AND compagnies.camp='.$perso['armee'].'
                    AND compagnies.ID='.$_POST['compa_valider_id']);
  if($compa[0])
    {
      request('DELETE FROM compagnies WHERE ID='.$_POST['compa_valider_id'].' LIMIT 1');
      if(affected_rows())
	{
	  request('INSERT
                 INTO events (`tireur`,
                              `cible`,
                              `date`,
                              `type`,
                              `raison`)
                       VALUES('.$_SESSION['com_perso'].',
                              '.$compa[1]['colonel'].',
                              '.time().',
                              12,
                              "'.post2bdd($_POST['compa_valider_refus']).'")');
	  request('OPTIMIZE TABLE compagnies');
	  request('DELETE FROM demande_compagnie WHERE compa='.$_POST['compa_valider_id'].' LIMIT 1');
	  request('OPTIMIZE TABLE demande_compagnie');
	}
    }
  else
    add_message(3,'Vous ne pouvez pas annuler la création de cette compagnie.');
}
//***************************************************************************
// Valider la création d'une compagnie.
//***************************************************************************
if(isset($_POST['compa_valider_confirm'],$_POST['compa_valider_ok'],$_POST['compa_valider_id']) &&
   is_numeric($_POST['compa_valider_id']))
{
  $demande=my_fetch_array('SELECT persos.ID
FROM persos
INNER JOIN demande_compagnie
ON demande_compagnie.ID=persos.ID
INNER JOIN compagnies
ON compagnies.ID=demande_compagnie.compa
LEFT OUTER JOIN grades
ON persos.grade=grades.ID
WHERE demande_compagnie.creation=1
  AND (grades.ID IS NULL OR grades.niveau=0)
  AND compagnies.valide=0
  AND compagnies.camp='.$perso['armee'].'
  AND compagnies.ID='.$_POST['compa_valider_id']);
  if($demande[0])
    {
      request('UPDATE persos
SET persos.compagnie='.$_POST['compa_valider_id'].',
                grade=1,
                colo_ordres=1,
                colo_valider=1,
                colo_criteres=1,
                colo_virer=1,
                colo_droits=1,
                colo_colo=1,
                colo_HRP=1,
                colo_RP=1,
                colo_sigle=1,
                colo_grades=1,
                ordrescompa=1,
                forum_compa=1,
                forum_mcompa=1,
                forum_em=1,
                niveau_gene=50,
                niveau_compa=100 
            WHERE persos.ID='.$demande[1]['ID']);
      request('UPDATE compagnies
 SET valide=1
 WHERE compagnies.valide=0
   AND compagnies.camp='.$perso['armee'].'
   AND compagnies.ID='.$_POST['compa_valider_id']);
      if(affected_rows())
	{
	  // On détruit la demande.
	  request('DELETE FROM demande_compagnie WHERE compa='.$_POST['compa_valider_id'].' LIMIT 1');
	  request('OPTIMIZE TABLE demande_compagnie');
	  // On récup le nom de la compagnie et le compte du futur colonel.
	  $nom=my_fetch_array('SELECT compagnies.nom,
                                  persos.ID
                           FROM compagnies
                             INNER JOIN persos
                             ON persos.compagnie=compagnies.ID
                           WHERE compagnies.ID='.$_POST['compa_valider_id']);
	  // On ajoute la compagnie dans le forum.
	  forumNewCompa($_POST['compa_valider_id'],bdd2bdd($nom[1]['nom']));
	  // On met à jour les droits du colo sur le forum.
	  update_droits_forum($nom[1]['ID']);
	}
    }
  else
    add_message(3,'Impossible de valider la création de cette compagnie.');
}
//***************************************************************************
// Supprimer une compagnie.
//***************************************************************************
if(isset($_POST['compa_detruire_confirm'],$_POST['compa_detruire_ok'],$_POST['compa_id']) &&
   is_numeric($_POST['compa_id']))
{
  require('../objets/bdd.php');
  require('../objets/compagnie.php');
  $connexion = new comBdd('','','','','',$GLOBALS['db']);
  $compa = new comCompagnie($_POST['compa_id'],$connexion);
  $compa->destroyBy($_SESSION['com_perso']);
}
$compas_av=my_fetch_array('SELECT compagnies.*,
                                  persos.ID AS matricule,
                                  persos.nom AS nom_colo
                           FROM compagnies
                             INNER JOIN demande_compagnie
                                ON demande_compagnie.compa=compagnies.ID
                             INNER JOIN persos
                                ON persos.ID=demande_compagnie.ID
                             LEFT OUTER JOIN grades
                                ON persos.grade=grades.ID
                           WHERE demande_compagnie.creation=1
                             AND (grades.ID IS NULL OR grades.niveau=0)
                             AND compagnies.camp='.$perso['armee'].'
                             AND compagnies.valide=0');
if($compas_av[0])
{
  $script='<script type="text/javascript">
function afficheCompa()
{
  if(!document.getElementById)
    return;
';
  for($i=1;$i<=$compas_av[0];$i++)
    {
      $script.='  if(document.getElementById("compa_valider_id").value=='.$compas_av[$i][0].')
  {
    sigle="'.bdd2js(bdd2html($compas_av[$i][2])).'";
    RP="'.bdd2js(bdd2html($compas_av[$i][3])).'";
    HRP="'.bdd2js(bdd2html($compas_av[$i][9])).'";
    colo="'.bdd2js(bdd2html($compas_av[$i]['nom_colo'].' ('.$compas_av[$i]['matricule'].')')).'";
  }
';
    }
  $script.='  document.getElementById("compa_valider_sigle").innerHTML=sigle;
  document.getElementById("compa_valider_colo").innerHTML=colo;
  document.getElementById("compa_valider_RP").innerHTML=RP;
  document.getElementById("compa_valider_HRP").innerHTML=HRP;
}
afficheCompa();
</script>
';
      echo'<form method="post" action="gene.php?act=compas">
<h2>Liste des compagnies proposées.</h2>
<p>
',form_select('Nom : ','compa_valider_id',$compas_av,'afficheCompa();'),'<br />
',form_check('Valider la création','compa_valider_confirm'),'
',form_submit('compa_valider_ok','Ok'),'<br />
',form_check('Annuler la création','compa_annuler_confirm'),'
',form_submit('compa_annuler_ok','Ok'),'</p>
<dl>
<dt>Sigle : </dt>
<dd id="compa_valider_sigle"></dd>
<dt>Colonel : </dt>
<dd id="compa_valider_colo"></dd>
<dt>Présentation RP : </dt>
<dd id="compa_valider_RP"></dd>
<dt>Présentation HRP : </dt>
<dd id="compa_valider_HRP"></dd>
</dl>
<p>',form_textarea('Motif du refus (le cas échéant) :','compa_valider_refus',15,70),'
</p>
</form>
',$script;
}
$compas=my_fetch_array('SELECT compagnies.*,
                                  persos.ID AS matricule,
                                  persos.nom AS nom_colo
                           FROM compagnies
                             INNER JOIN persos
                                ON persos.compagnie=compagnies.ID
                             LEFT OUTER JOIN grades
                                ON persos.grade=grades.ID
                           WHERE grades.niveau=1
                             AND compagnies.camp='.$perso['armee'].'
                             AND compagnies.valide=1');
if($compas[0])
  {
    $script='<script type="text/javascript">
function afficheCompaliste()
{
  if(!document.getElementById)
    return;
';
  for($i=1;$i<=$compas[0];$i++)
    {
      $script.='  if(document.getElementById("compa_id").value=='.$compas[$i][0].')
  {
    sigle="'.bdd2js('<a href="compagnies.php?id='.$compas[$i][0].'" target="_fiche">'.bdd2html($compas[$i][2]).'</a>').'";
    RP="'.bdd2js(bdd2html($compas[$i][3])).'";
    HRP="'.bdd2js(bdd2html($compas[$i][9])).'";
    colo="'.bdd2js('<a href="fiche.php?id='.$compas[$i]['matricule'].'" target="_fiche">'.bdd2html($compas[$i]['nom_colo'].' ('.$compas[$i]['matricule'].')').'</a>').'";
  }
';
    }
  $script.='  document.getElementById("compa_sigle").innerHTML=sigle;
  document.getElementById("compa_colo").innerHTML=colo;
  document.getElementById("compa_RP").innerHTML=RP;
  document.getElementById("compa_HRP").innerHTML=HRP;
}
afficheCompaliste();
</script>
';
    echo'<h2>Liste des compagnies actives.</h2>
<form method="post" action="gene.php?act=compas"> 
<p>
',form_select('Nom : ','compa_id',$compas,'afficheCompaliste();'),'<br />
',form_check('Détruire (il n\'y aura pas de demande confirmation) : ','compa_detruire_confirm'),'
',form_submit('compa_detruire_ok','Ok'),'</p>
</form> 
<dl>
<dt>Sigle : </dt>
<dd id="compa_sigle"></dd>
<dt>Colonel : </dt>
<dd id="compa_colo"></dd>
<dt>Présentation RP : </dt>
<dd id="compa_RP"></dd>
<dt>Présentation HRP : </dt>
<dd id="compa_HRP"></dd>
</dl>
',$script;
}
?>