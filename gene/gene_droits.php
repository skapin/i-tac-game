<?php
if(isset($_POST['droits_ok'])&&is_numeric($_POST['droits_id'])){
  // On verifie que le perso est du bon camp.
  if(exist_in_db('SELECT armee
                         FROM persos
                         WHERE ID='.$_POST['droits_id'].'
                           AND niveau_gene<'.$perso['niveau_gene'].'
                           AND armee='.$perso['armee'])){
    $sql='';
    foreach($listeDroits['gene'] AS $key=>$value){
      if(autorisation($key,$value[0])){
	$sql.=($value[0]?'forum_':'gene_').$key."=".(isset($_POST[$key])?'1':'0').", ";
      }
    }
    $sql='UPDATE persos SET '.$sql.'niveau_gene='.min($_POST['niveau_gene'],$perso['niveau_gene']-1).' WHERE persos.ID='.$_POST['droits_id'];
    request($sql);
    update_droits_forum($_POST['droits_id']);
  }
}

echo'<form method="post" action="gene.php?act=droits">
<h2>Droits</h2>
<p>
',form_text('Matricule','droits_id','','',''),'<br />
',form_text('Niveau dans la hi&eacute;rarchie ? (maximum '.($perso['niveau_gene']-1).')','niveau_gene','3',''),'</p>
<h2>Gestion</h2>
<p>
';
foreach($listeDroits['gene'] AS $key=>$value){
  if(!$value[0] && autorisation($key)){
    echo form_check($listeDroits['gene'][$key][1].' ',$key,'').'<br />
';
  }
}
echo'</p>
<h2>Forum</h2>
<p>
';
foreach($listeDroits['gene'] AS $key=>$value){
  if($value[0] && autorisation($key,true)){
    echo form_check($listeDroits['gene'][$key][1].' ',$key,'').'<br />
';
  }
}
echo form_submit('droits_ok','Modifier'),'</p>
</form>
';
?>