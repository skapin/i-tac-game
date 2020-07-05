<?php
if(isset($_POST['mod_description']))
  {
    $update='';
    // Changement de la description de la compagnie
    if(isset($_POST['mod_sigle']) && $_POST['mod_sigle'])
      {
	if(exist_in_db('SELECT ID
FROM compagnies
WHERE initiales=\''.post2bdd($_POST['mod_sigle']).'\'
  AND ID !=\''.$perso['ID_compa'].'\'
LIMIT 1'))
	  add_message(3,'Ce sigle est déjà utilisé par une autre compagnie.');
	else
	  $update.=($update?',':'').' initiales=\''.post2bdd($_POST['mod_sigle']).'\'';
      }
    $update.=($update?',':'').' `desc`=\''.post2bdd($_POST['mod_RP']).'\'';
    $update.=($update?',':'').' HRP=\''.post2bdd($_POST['mod_HRP']).'\'';
    if($update)
      {
	request('UPDATE compagnies
SET '.$update.'
WHERE ID='.$perso['ID_compa'].'
LIMIT 1');
      }
  }
$desc=my_fetch_array('SELECT initiales,
`desc`,
HRP
FROM compagnies
WHERE ID=\''.$perso['ID_compa'].'\'
LIMIT 1');
echo'<form method="post" action="compagnie.php?colo_desc">
<p>',form_submit('mod_description','Valider'),'</p>
<dl id="compa_desc">
';
if($desc[0])
  {
    $_POST['mod_sigle']=$desc[1]['initiales'];
    echo'<dt>Sigle :</dt>
<dd>',form_text('','mod_sigle','5',''),'</dd>
';
  }
if($desc[0])
  {
    $_POST['mod_RP']=$desc[1]['desc'];
    echo'<dt>Description RP :</dt>
<dd>',form_textarea('','mod_RP','',''),'</dd>
';
  }
if($desc[0])
  {
    $_POST['mod_HRP']=$desc[1]['HRP'];
    echo'<dt>Description HRP :</dt>
<dd>',form_textarea('','mod_HRP','',''),'</dd>
';
  }
echo'</form>
';
?>