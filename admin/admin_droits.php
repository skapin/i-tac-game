<?php
if(isset($_POST['droits_ok'],$_POST['droits_id'])&&is_numeric($_POST['droits_id']))
{
  $sql="UPDATE compte
SET ";
  $GLOBALS['position']=0;
  $sql.=make_sql('munitions');
  $sql.=make_sql('armes');
  $sql.=make_sql('armures');
  $sql.=make_sql('missions');
  $sql.=make_sql('gadgets');
  $sql.=make_sql('camps');
  $sql.=make_sql('cartes');
  $sql.=make_sql('qgs');
  $sql.=make_sql('news');
  $sql.=make_sql('terrains');
  $sql.=make_sql('gene');
  $sql.=make_sql('pnjs');
  $sql.=make_sql('objets');
  request($sql." WHERE ID='$_POST[droits_id]'");
}
$persos=my_fetch_array("SELECT *
                        FROM compte");
$script='<script type="text/javascript">
function afficheDroits()
{
  if(!document.getElementById)
    return;
';
$ids=array(0);
for($i=1;$i<=$persos[0];$i++)
{
      $ids[]=array($persos[$i]['ID'],bdd2text($persos[$i]['login']));
	$ids[0]++;
      $script.='  if(document.getElementById("droits_id").value=='.$persos[$i]['ID'].')
  {
';
      $script.=scriptage('munitions',$persos[$i]);
      $script.=scriptage('armes',$persos[$i]);
      $script.=scriptage('armures',$persos[$i]);
      $script.=scriptage('missions',$persos[$i]);
      $script.=scriptage('gadgets',$persos[$i]);
      $script.=scriptage('camps',$persos[$i]);
      $script.=scriptage('cartes',$persos[$i]);
      $script.=scriptage('qgs',$persos[$i]);
      $script.=scriptage('news',$persos[$i]);
      $script.=scriptage('terrains',$persos[$i]);
      $script.=scriptage('gene',$persos[$i]);
      $script.=scriptage('pnjs',$persos[$i]);
      $script.=scriptage('objets',$persos[$i]);
      $script.='  }
';
    }
  $script.=fin_scriptage('munitions',$persos[$i]);
  $script.=fin_scriptage('armes',$persos[$i]);
  $script.=fin_scriptage('armures',$persos[$i]);
  $script.=fin_scriptage('missions',$persos[$i]);
  $script.=fin_scriptage('gadgets',$persos[$i]);
  $script.=fin_scriptage('camps',$persos[$i]);
  $script.=fin_scriptage('cartes',$persos[$i]);
  $script.=fin_scriptage('qgs',$persos[$i]);
  $script.=fin_scriptage('news',$persos[$i]);
  $script.=fin_scriptage('terrains',$persos[$i]);
  $script.=fin_scriptage('gene',$persos[$i]);
  $script.=fin_scriptage('pnjs',$persos[$i]);
  $script.=fin_scriptage('objets',$persos[$i]);
  $script.='}
'.(isset($_POST['droits_ok'])?'':'afficheDroits();
').'</script>
';
  echo'<form method="post" action="anim.php?admin_droits">
<p>
'.form_select("","droits_id",$ids,"afficheDroits();").'<br />
'.make_form('munitions','Gestion des munitions ').'
'.make_form('armes','Gestion des armes ').'
'.make_form('armures','Gestion des armures ').'
'.make_form('gadgets','Gestion des gadgets ').'
'.make_form('missions','Gestion des missions ').'
'.make_form('cartes','Gestion des cartes ').'
'.make_form('qgs','Gestion des QGs ').'
'.make_form('camps','Gestion des camps ').'
'.make_form('news','Gestion des news ').'
'.make_form('terrains','Gestion des terrains ').'
'.make_form('gene','Nomination des généraux en chef ').'
'.make_form('pnjs','Gestion des PNJs ').'
'.make_form('objets','Gestion des objets ').'
'.form_submit('droits_ok','Modifier').'</p>
</form>
'.$script;

function scriptage($nom,$perso)
{
  if(autorisation($nom))
    return '    droits_'.$nom.'='.$perso['anim_'.$nom].';
';
  else
    return '';
}
function fin_scriptage($nom)
{
      if(autorisation($nom))
	return '  document.getElementById("droits_'.$nom.'").checked=droits_'.$nom.'?"checked":"";
';
      else
	return '';
}

function make_form($nom,$label)
{
  if(autorisation($nom))
    return form_check($label,'droits_'.$nom).'<br />
';
  else
    return '';
}

function make_sql($nom)
{
  if(autorisation($nom))
    {
      $str=($GLOBALS['position']?', ':'').'anim_'.$nom."='".(isset($_POST['droits_'.$nom])?'1':'0')."'";
      $GLOBALS['position']=1;
      return $str;
    }
  else
    return '';
}
?>