<?php
if(isset($_POST['transfuge_ok'], $_POST['transfuge_id']) && is_numeric($_POST['transfuge_id']))
  {
    if(isset($_POST['transfuge_valide']))
      {
	  request('UPDATE demandes
SET validation_1=1
WHERE ID='.$_POST['transfuge_id'].'
AND `type`=1
AND sujet='.$perso['armee']);
      }
    else if (isset($_POST['transfuge_refuse']))
      {
	request('DELETE FROM demandes WHERE ID='.$_POST['transfuge_id'].'
AND `type`=1
AND sujet='.$perso['armee']);
	if(affected_rows())
	  request('OPTIMIZE TABLE demandes');
      }
    unset($_POST);
  }
$transfuges=my_fetch_array('SELECT demandes.ID,
compte.login AS nom,
raison,
RP,
validation_1
FROM demandes
  INNER JOIN compte
    ON demandes.demandeur=compte.ID
WHERE sujet=\''.$perso['armee'].'\' AND
demandes.type=\'1\'');
$script='<script type="text/javascript">
function showTransfuge()
{
  if(!document.getElementById)
    return;
  var valide=0;
  var RP="";
  var HRP="";
';
for($i=1;$i<=$transfuges[0];$i++)
  $script.='  if(document.getElementById("transfuge_id").value=='.$transfuges[$i]['ID'].')
  {
    valide="'.($transfuges[$i]['validation_1']?'Dossier déjà validé.':'Dossier en attente de votre validation.').'";
    RP="RP :<br />'.bdd2js(bdd2html($transfuges[$i]['RP'])).'";
    HRP="Raisons HRP :<br />'.bdd2js(bdd2html($transfuges[$i]['raison'])).'";
  }
';
$script.='  document.getElementById("transfuge_RP").innerHTML=RP;
  document.getElementById("transfuge_HRP").innerHTML=HRP;
  document.getElementById("transfuge_valide").innerHTML=valide;
}
showTransfuge();
</script>
';
if($transfuges[0])
  {
    echo'<form method="post" action="gene.php?act=transfuge">
<p>
',form_select('Demandeur : ','transfuge_id',$transfuges,'showTransfuge();'),'<br />
<p id="transfuge_valide">
</p>
<p id="transfuge_RP">
</p>
<p id="transfuge_HRP">
</p>
',form_check('Accepter le transfuge : ','transfuge_valide'),'<br />
',form_check('Refuser le transfuge : ','transfuge_refuse'),'<br />
',form_submit('transfuge_ok','Ok'),' 
</p>
</form>
',$script;
  }
else
  echo'<p>Aucune demande de transfuge en cours.</p>';
?>