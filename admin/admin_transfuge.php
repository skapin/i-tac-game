<?php
if(isset($_POST['transfuge_ok'], $_POST['transfuge_id']) && is_numeric($_POST['transfuge_id']))
  {
    if(isset($_POST['transfuge_valide'],
	     $_POST['transfuge_duree'],
	     $_POST['transfuge_VS'])
       && is_numeric($_POST['transfuge_duree'])
       && is_numeric($_POST['transfuge_VS']))
      {
	if($_POST['transfuge_VS']<1)
	  erreur(0,'On ne divise pas la progression par un nombre inférieur à 1.');
	else
	  request("UPDATE demandes
SET validation_2='1',
diminution_VS='$_POST[transfuge_VS]',
duree_effet='$_POST[transfuge_duree]'
WHERE ID='$_POST[transfuge_id]'
AND `type`='1'");
      }
    else if (isset($_POST['transfuge_refuse']))
      {
	request("DELETE FROM demandes WHERE ID='$_POST[transfuge_id]'
AND `type`='1'");
	if(affetced_rows())
	  request('OPTIMIZE TABLE demandes');
      }
  }
$transfuges=my_fetch_array('SELECT demandes.ID,
compte.login AS nom,
raison,
RP,
diminution_VS,
duree_effet,
camps.nom AS sujet
FROM demandes
  INNER JOIN compte
    ON demandes.demandeur=compte.ID
  INNER JOIN camps
    ON camps.ID=demandes.sujet
WHERE demandes.type=\'1\'');
$script='<script type="text/javascript">
function showTransfuge()
{
  if(!document.getElementById)
    return;
  var RP="";
  var HRP="";
  var VS=0;
  var duree=0;
  var camp="";
';
for($i=1;$i<=$transfuges[0];$i++)
  $script.='  if(document.getElementById("transfuge_id").value=='.$transfuges[$i]['ID'].')
  {
    RP="RP :<br />'.bdd2js(bdd2html($transfuges[$i]['RP'])).'";
    HRP="Raisons HRP :<br />'.bdd2js(bdd2html($transfuges[$i]['raison'])).'";
    camp="'.bdd2js(bdd2html($transfuges[$i]['sujet'])).'";
    duree='.$transfuges[$i]['duree_effet'].';
    VS='.$transfuges[$i]['diminution_VS'].';
  }
';
$script.='  document.getElementById("transfuge_RP").innerHTML=RP;
  document.getElementById("transfuge_HRP").innerHTML=HRP;
  document.getElementById("transfuge_camp").innerHTML=camp;
  document.getElementById("transfuge_VS").value=duree;
  document.getElementById("transfuge_duree").value=VS;
}
showTransfuge();
</script>
';
if($transfuges[0])
  {
    echo'<form method="post" action="anim.php?admin_transfuge">
<p>
'.form_select('Demandeur : ','transfuge_id',$transfuges,'showTransfuge();').'<br />
'.form_text('Division de la progression : ','transfuge_VS','3','').'
'.form_text('pendant : ','transfuge_duree','3','').' jours.<br />
Vers le camp : <span id="transfuge_camp"></span>.
<p id="transfuge_RP">
</p>
<p id="transfuge_HRP">
</p>
'.form_check('Accepter le transfuge : ','transfuge_valide').'<br />
'.form_check('Refuser le transfuge : ','transfuge_refuse').'<br />
'.form_submit('transfuge_ok','Ok').' 
</p>
</form>
'.$script;
  }
else
  echo'<p>Aucune demande de transfuge en cours.</p>';
?>