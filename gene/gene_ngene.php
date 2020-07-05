<?php
if(isset($_POST['nomme_ok'],$_POST['nomme_gene']) && is_numeric($_POST['nomme_gene'])){
  $soldat=my_fetch_array('SELECT grades.niveau,
                                persos.compte
                         FROM persos
                           LEFT OUTER JOIN grades
                           ON grades.ID=persos.grade
                         WHERE persos.ID='.$_POST['nomme_gene'].'
                           AND persos.armee='.$perso['armee']);
  if($soldat[0] && !$soldat[1]['niveau']){
      // Le perso existe et n'est ni géné en chef, ni colonel, ni général.
    request('UPDATE persos
               SET persos.grade=2,
                   gene_ordres=1,
                   gene_compas=1,
                   gene_trt=1,
                   gene_transfuge=1, 
                   gene_medailles=1,
                   gene_ngene=0,
                   gene_droits=1,
                   gene_grades=1,
                   gene_marche=1,
                   gene_qgs=1, 
                   ordres=1,
                   ordresconfi=1,
                   forum_em=1,
                   forum_gene=1,
                   forum_mem=1,
                   forum_mcamp=1,
                   niveau_gene=80
               WHERE persos.ID='.$_POST['nomme_gene']);
      if(affected_rows()){
	if(!request('DELETE FROM demande_compagnie WHERE ID='.$_POST['nomme_gene'])){
	  add_message(3,'Impossible de supprimer en bdd les demandes de creation de compagnies faites par ce perso.');
	}
	update_droits_forum($_POST['nomme_gene']);
      }
  }
}
if(isset($_POST['degrade_ok']) && is_numeric($_POST['degrade_gene'])){
  $soldat=my_fetch_array('SELECT grades.niveau,
                                persos.compte
                         FROM persos
                           INNER JOIN grades
                           ON grades.ID=persos.grade
                         WHERE persos.ID='.$_POST['degrade_gene'].'
                           AND persos.armee='.$perso['armee']);
  if($soldat[0] && $soldat[1]['niveau']==2){
      // Le perso est bien général.
      request('UPDATE persos
               SET persos.grade=0,
                   gene_ordres=0,
                   gene_compas=0,
                   gene_trt=0,
                   gene_medailles=0,
                   gene_ngene=0,
                   gene_transfuge=0, 
                   gene_droits=0,
                   gene_grades=0,
                   gene_marche=0,
                   gene_qgs=0, 
                   ordresconfi=0,
                   forum_em=0,
                   forum_gene=0,
                   forum_mem=0,
                   forum_mcamp=0,
                   niveau_gene=0
               WHERE persos.ID='.$_POST['degrade_gene']);
      update_droits_forum($_POST['degrade_gene']);
  }
}
$persos=my_fetch_array('SELECT persos.ID,
                               persos.nom,
                               grades.niveau
                        FROM persos
                          LEFT OUTER JOIN grades
                             ON persos.grade=grades.ID
                        WHERE persos.armee='.$perso['armee'].'
AND grades.niveau=2
                        ORDER BY persos.ID ASC');
$a_degrader=array(0);
for($i=1;$i<=$persos[0];$i++){
  $a_degrader[]=array($persos[$i]['ID'],$persos[$i]['ID'].'('.$persos[$i]['nom'].')');
  $a_degrader[0]++;
}
echo'<form method="post" action="gene.php?act=ngene">
<p>
',form_text('Nommer comme g&eacute;n&eacute;ral : ','nomme_gene','','',''),'<br />
',form_submit('nomme_ok','Ok'),'</p>
<p>
',form_select('Virer de son poste de général : ','degrade_gene',$a_degrader,''),'<br />
',form_submit('degrade_ok','Ok'),'</p>
</form>
';
?>