<?php
if(isset($_POST['gene_ordres_ok'])){
  $time=time();
  $gene=filtrage_ordre(post2text($_POST['ordres']));
  $gene='<div id="div_ordres_gene" class="content">
'.$gene.'</div>
';
  // On ecrit les ordres.
  if(fichier_create('../ordres/gene_camp_'.$perso['armee'].'.html',$gene,1)){
    request('INSERT INTO ordres (`camp`,`compa`,`date`,`texte`)
 VALUES('.$perso['armee'].',0,'.$time.',"'.post2bdd($_POST['ordres']).'")');
    add_message(0,"Fichier enregistr&eacute;");
  }
}

$gene=my_fetch_array('SELECT texte
                      FROM ordres
                      WHERE camp='.$perso['armee'].'
                        AND compa=0
                        AND confi=0
                      ORDER BY date DESC
                      LIMIT 1');
if($gene[0]){
  $_POST['ordres']=$gene[1][0];
}

echo'<form method="post" action="gene.php?act=ordres">
<p>',form_submit('gene_ordres_ok','Modifier'),'<br />
',form_textarea('Ordres :<br />','ordres',35,75),'<br />
',form_submit('gene_ordres_ok','Modifier'),'</p>
</form>
';
?>