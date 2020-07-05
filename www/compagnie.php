<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header_lite();
if(!isset($_SESSION['com_perso'])){
  echo 'Vous n\'êtes pas loggué.';
  com_footer();
  die();
}

// Recuperation des droits du perso.
$perso=recup_droits('colo');
if($perso['ID_compa']==1){
  // Ce perso n'appartient à aucune compagnie
  //***************************************************************************
  // postuler pour une compagnie.
  //***************************************************************************
  if(isset($_POST['postulation_ok'],$_POST['demande_compa_id']) && is_numeric($_POST['demande_compa_id'])){
    // Peut on postuler pour cette compagnie ?
    $compa=my_fetch_array('SELECT ID
                             FROM compagnies
                             WHERE camp='.$perso['armee'].'
                               AND inscriptions=1 AND valide=1
                               AND grade_mini<='.$perso['grade_reel'].'
                               AND grade_maxi>='.$perso['grade_reel'].'
                               AND ID='.$_POST['demande_compa_id']);
    if($compa[0]){
      request('INSERT INTO demande_compagnie (ID,compa,creation)
                                           VALUES('.$_SESSION['com_perso'].','.$_POST['demande_compa_id'].',0)');
    }
    else
      add_message(3,'Impossible de postuler pour entrer dans ce groupe.');
  }
  //***************************************************************************
  // Annuler une postulation.
  //***************************************************************************
  if(isset($_POST['annule_postulation_ok'],$_POST['annule_postulation_confirm'])){
    request('DELETE FROM demande_compagnie WHERE ID='.$_SESSION['com_perso'].' AND creation=0 LIMIT 1');
    request('OPTIMIZE TABLE demande_compagnie');
  }
  //***************************************************************************
  // Creation d'une nouvelle compagnie.
  //***************************************************************************
  if(isset($_POST['new_compa_ok']) && !$perso['niveau_grade']){
    $erreur=0;
    if(isset($_POST['new_compa_nom']) && $_POST['new_compa_nom']){
      // Verifions que le nom choisi n'est pas deja pris.
      if(exist_in_db('SELECT ID FROM compagnies WHERE nom=\''.post2bdd($_POST['new_compa_nom']).'\' LIMIT 1')){
	$erreur=1;
	add_message(3,'Le nom choisi est deja utilise par un autre groupe.');
      }
    }
    else{
      $erreur=1;
      add_message(3,'Il faut choisir un nom pour votre groupe.');
    }
    if(isset($_POST['new_compa_initiales']) && $_POST['new_compa_initiales']){
      // Vérifions que le nom choisi n'est pas déjà pris.
      if(exist_in_db('SELECT ID FROM compagnies WHERE initiales=\''.post2bdd($_POST['new_compa_initiales']).'\' LIMIT 1')){
	$erreur=1;
	add_message(3,'Le sigle choisi est d&eacute;j&agrave; utilis&eacute; par un autre groupe.');
      }
    }
    else{
      $erreur=1;
      add_message(3,'Il faut choisir un sigle pour votre groupe.');
    }
    if(!$erreur){
      request('INSERT
                   INTO compagnies (nom,
                                    initiales,
                                    `desc`,
                                    camp,
                                    grade_mini,
                                    grade_maxi,
                                    valide, 
                                    HRP)
                             VALUES(\''.post2bdd($_POST['new_compa_nom']).'\',
                                    \''.post2bdd($_POST['new_compa_initiales']).'\',
                                    \''.post2bdd($_POST['new_compa_RP']).'\',
                                    '.$perso['armee'].',
                                    0,
                                    13,
                                    0,
                                    \''.post2bdd($_POST['new_compa_HRP']).'\')');
      $id=last_id();
      if($id)
	request('INSERT INTO demande_compagnie (ID,compa,`creation`) VALUES('.$_SESSION['com_perso'].','.$id.',1)');
    }
  }
  //***************************************************************************
  // Annulation de la creation d'une nouvelle compagnie.
  //***************************************************************************
  if(isset($_POST['annule_creation_ok'],$_POST['annule_creation_confirm'])){
    $compa=my_fetch_array('SELECT compagnies.ID
                             FROM compagnies
                                INNER JOIN demande_compagnie
                                   ON compagnies.ID=demande_compagnie.compa
                             WHERE demande_compagnie.ID='.$_SESSION['com_perso'].'
                               AND creation=1');
    if($compa[0]){
      request('DELETE FROM compagnies WHERE ID='.$compa[1][0].' LIMIT 1');
      request('OPTIMIZE TABLE compagnies');
      request('DELETE FROM demande_compagnie WHERE ID='.$_SESSION['com_perso'].' LIMIT 1');
      request('OPTIMIZE TABLE demande_compagnie');
    }
    else
      add_message(3,'Vous ne pouvez pas annuler cette demande.');
  }
  //***************************************************************************
  // Affichage des formulaires.
  //***************************************************************************

  // A t'il fait une demande dans une compagnie pas encore validee ?
  $demande=my_fetch_array('SELECT nom,demande_compagnie.creation FROM compagnies,demande_compagnie WHERE compagnies.ID=demande_compagnie.compa AND demande_compagnie.ID='.$_SESSION['com_perso']);
  if($demande[0]){
    if($demande[1]['creation']){
      // On a propose la creation d'une compagnie.
      echo'<form method="post" action="compagnie.php">
<p>
',form_check('Annuler votre demande cr&eacute;ation de groupe ('.bdd2html($demande[1]['nom']).'): ','annule_creation_confirm'),'
',form_submit('annule_creation_ok','Ok'),'
</p>
</form>
';
    }
    else{
      // On a demande à entrer dans une compagnie
      echo'<form method="post" action="compagnie.php">
<p>
',form_check('Annuler votre postulation dans le groupe '.bdd2html($demande[1]['nom']).' : ','annule_postulation_confirm'),'
',form_submit('annule_postulation_ok','Ok'),'
</p>
</form>
';
    }
  }
  else{
    $compas=my_fetch_array('SELECT compagnies.ID,
                                      compagnies.nom,
                                      compagnies.desc,
                                      compagnies.HRP,
                                      compagnies.initiales,
                                      persos.ID AS matricule,
                                      persos.nom AS nom_colo
                               FROM compagnies
                                 INNER JOIN persos
                                    ON persos.compagnie=compagnies.ID
                                 INNER JOIN grades
                                    ON persos.grade=grades.ID
                                WHERE grades.niveau=1
                                  AND compagnies.camp='.$perso['armee'].'
                                  AND compagnies.valide=1
                                  AND compagnies.inscriptions=1
                                  AND compagnies.grade_mini<='.$perso['grade_reel'].'
                                  AND compagnies.grade_maxi>='.$perso['grade_reel'].'
                                  AND compagnies.ID!=1');
    if($compas[0]){
      $script='<script type="text/javascript">
function afficheCompa()
{
  if(!document.getElementById)
    return;
';
      for($i=1;$i<=$compas[0];$i++){
	$script.='  if(document.getElementById("demande_compa_id").value=='.$compas[$i][0].')
  {
    sigle="'.bdd2js(bdd2html($compas[$i]['initiales'])).'";
    RP="'.bdd2js(filtrage_ordre(bdd2text($compas[$i]['desc']))).'";
    HRP="'.bdd2js(filtrage_ordre(bdd2text($compas[$i]['HRP']))).'";
    colo="'.bdd2js(bdd2html($compas[$i]['nom_colo'].' ('.$compas[$i]['matricule'].')')).'";
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

      echo'<form method="post" action="compagnie.php">
<p>',form_select('Postuler pour le groupe : ','demande_compa_id',$compas,'afficheCompa();'),'',form_submit('postulation_ok','Ok'),'</p>
</form>
<dl>
<dt>Sigle : </dt>
<dd id="compa_valider_sigle"></dd>
<dt>Colonel : </dt>
<dd id="compa_valider_colo"></dd>
<dt>Pr&eacute;sentation RP : </dt>
<dd id="compa_valider_RP"></dd>
<dt>Pr&eacute;sentation HRP : </dt>
<dd id="compa_valider_HRP"></dd>
</dl>
',$script;
    }
    if(!$perso['niveau_grade']){
      // Pas de grade particulier
      echo'<form method="post" action="compagnie.php">
<h2>Proposer votre groupe :</h2>
<p>',form_text('Nom : ','new_compa_nom','',''),'<br />
',form_text('Sigle (5caract&egrave;res maxi) : ','new_compa_initiales','5',''),'<br />
',form_textarea('Pr&eacute;sentation RP :<br />','new_compa_RP',20,60),'<br />
',form_textarea('Pr&eacute;sentation HRP :<br />','new_compa_HRP',20,60),'<br />
',form_submit('new_compa_ok','Ok'),'
</p>
</form>
';
    }
  }
}
//*****************************************************************************
// Le perso appartient a une compagnie.
//*****************************************************************************
else
{
  if(isset($_POST['quitte_compa_ok'],$_POST['quitte_compa_confirm'])&&$perso['niveau_grade']!=1)
    {
      // On quitte sa compagnie.
      request('UPDATE persos
               SET compagnie=1,
                   ordrescompa=0,
                   colo_ordres=0,
                   colo_valider=0,
                   colo_criteres=0,
                   colo_virer=0,
                   colo_droits=0,
                   colo_colo=0,
                   colo_HRP=0,
                   colo_RP=0,
                   colo_sigle=0,
                   colo_grades=0,
                   niveau_compa=0, 
                   forum_compa=0,
forum_mcompa=0
               WHERE persos.ID='.$_SESSION['com_perso']);
      update_droits_forum($_SESSION['com_perso']);
    }
  else
    {
      require_once('../colo/colo_fonctions.php');
      echo'<ul>
';
      menu();
      echo'</ul>
';
      content();
      if($perso['niveau_grade']!=1 && $perso['ID_compa']!=1) // Pas colonel, il peut quitter sa compagnie.
	echo'<form method="post" action="compagnie.php">
<p>
',form_check('Quitter votre groupe ('.bdd2html($perso['nom_compa']).') : ','quitte_compa_confirm'),'
',form_submit('quitte_compa_ok','Ok'),'
</p>
</form>
';
    }
}
unset($perso);
com_footer_lite();
?>