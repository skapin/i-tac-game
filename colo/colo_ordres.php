<?php
if(isset($_POST['colo_ordres_ok']))
{
  // On filtre les ordres (à modifier une fois la super fonction d'agm prête.
  $ordres=filtrage_ordre(post2text($_POST['ordres']));

  // On écrit les ordres.
  if(fichier_create('../ordres/compa_'.$perso['ID_compa'].'.html',$ordres,1))
    {
      $time=time();
      request('INSERT INTO ordres (`camp`,`compa`,`confi`,`date`,`texte`)VALUES('.$perso['armee'].','.$perso['ID_compa'].',0,'.$time.',\''.post2bdd($_POST['ordres']).'\')');
      add_message(0,'Fichier enregistré');
    }
}
$ordres=my_fetch_array('SELECT texte
                      FROM ordres
                      WHERE camp='.$perso['armee'].'
                        AND compa='.$perso['ID_compa'].'
                        AND confi=0
                      ORDER BY date DESC
                      LIMIT 1');
if($ordres[0])
{
  $_POST['ordres']=$ordres[1][0];
}

echo'<form method="post" action="compagnie.php?colo_ordres">
<p>',form_submit('colo_ordres_ok','Modifier'),'<br />
',form_textarea('Ordres :<br />','ordres',40,80),'</p>
</form>
';
?>