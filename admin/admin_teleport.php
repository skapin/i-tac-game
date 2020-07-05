<?php
if(isset($_POST['carte'],$_POST['X'],$_POST['Y'],$_POST['id']) &&
   is_numeric($_POST['carte']) &&
   is_numeric($_POST['X']) &&
   is_numeric($_POST['Y']) &&
   is_numeric($_POST['id'])){
  $sql='UPDATE persos SET X='.$_POST['X'].',Y='.$_POST['Y'].',map='.$_POST['carte'].' WHERE ID='.$_POST['id'].' LIMIT 1';
  if(request($sql)){
    echo'<p>Perso d&eacute;plac&eacute;.</p>';
  }
}
  // Recuperation de la liste des cartes.
$cartes=my_fetch_array('SELECT ID, nom FROM cartes ORDER BY nom');
echo'<form method="post" action="anim.php?admin_teleport">
<p>
',form_text('T&eacute;l&eacute;porter le matricule: ','id','4',''),'<br />
',form_select('sur la carte: ','carte',$cartes,''),'<br />
',form_text('en X=','X','4',''),'
',form_text('et Y=','Y','4',''),'<br />
',form_submit('teleport_ok','Ok'),'
</p>
</form>
';
?>