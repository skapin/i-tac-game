<?php
if(isset($_POST['droits_ok'],$_POST['droits_id'])&&is_numeric($_POST['droits_id']))
{
  // On vérifie que le perso est de la compagnie
  $leperso=my_fetch_array('SELECT grades.niveau
                         FROM persos
                           LEFT OUTER JOIN grades
                             ON persos.grade=grades.ID
                         WHERE persos.ID='.$_POST['droits_id'].'
                           AND persos.compagnie!=1
                           AND persos.niveau_compa<'.$perso['niveau_compa'].' 
                           AND persos.compagnie='.$perso['ID_compa'].'
                           AND (grades.ID IS NULL OR grades.niveau!=1)');
  if($leperso[0])
    {
      $GLOBALS['pos']=0;
      $sql='UPDATE persos SET ';
      $sql.=make_sql('RP');
      $sql.=make_sql('criteres');
      $sql.=make_sql('valider');
      $sql.=make_sql('ordres');
      $sql.=make_sql('virer');
      $sql.=make_sql('compa');
      $sql.=make_sql('mcompa');
      if($perso['niveau_compa'] && isset($_POST['niveau_compa']) && is_numeric($_POST['niveau_compa']))
	{
	  $sql.=($GLOBALS['pos']?',':'').' niveau_compa=\''.min($perso['niveau_compa']-1,$_POST['niveau_compa']).'\'';
	  $GLOBALS['pos']=1;
	}
      if($perso['ordrescompa'] && autorisation('ordres'))
	{
	  $sql.=($GLOBALS['pos']?',':'').' ordrescompa='.(isset($_POST['ordrescompa'])?'1':'0');
	  $GLOBALS['pos']=1;
	}
      if($perso['niveau_grade']==1) // Seul le colonel peut donner accés à la console de droits.
	$sql.=make_sql('droits');
      request($sql.' WHERE persos.ID='.$_POST['droits_id']);
    }
  update_droits_forum($_POST['droits_id']);
}
$persos=my_fetch_array('SELECT persos.*
                        FROM persos
                          LEFT OUTER JOIN grades
                            ON persos.grade=grades.ID
                        WHERE persos.compagnie='.$perso['ID_compa'].'
                          AND persos.compagnie!=1
                          AND persos.niveau_compa<'.$perso['niveau_compa'].'
                          AND (grades.ID IS NULL OR grades.niveau!=1)
                        ORDER BY persos.ID ASC');
$script='<script type="text/javascript">
function afficheDroits()
{
  if(!document.getElementById)
    return;
';
$ids=array(0);
for($i=1;$i<=$persos[0];$i++)
{
	  $ids[]=array($persos[$i]['ID'],$persos[$i]['ID'].' ('.bdd2text($persos[$i]['nom']).')');
	  $script.='  if(document.getElementById("droits_id").value=='.$persos[$i]['ID'].')
  {
    ';
	  $script.=scriptage('RP',$persos[$i]);
	  $script.=scriptage('criteres',$persos[$i]);
	  $script.=scriptage('valider',$persos[$i]);
	  $script.=scriptage('ordres',$persos[$i]);
	  $script.=scriptage('virer',$persos[$i]);
	  $script.=scriptage('compa',$persos[$i]);
	  $script.=scriptage('mcompa',$persos[$i]);
	  if($perso['niveau_compa'])
	    $script.='    niveau_compa='.$persos[$i]['niveau_compa'].';
';
	  if($perso['niveau_grade']==1)
	    $script.=scriptage('droits',$persos[$i]);
	  if($perso['ordrescompa'] && autorisation('ordres'))
	    $script.='    ordrescompa='.$persos[$i]['ordrescompa'].';
';
$script.='  }
';
	  $ids[0]++;
    }
$script.=fin_scriptage('RP',$persos[$i]);
$script.=fin_scriptage('criteres',$persos[$i]);
$script.=fin_scriptage('valider',$persos[$i]);
$script.=fin_scriptage('ordres',$persos[$i]);
$script.=fin_scriptage('virer',$persos[$i]);
$script.=fin_scriptage('compa',$persos[$i]);
$script.=fin_scriptage('mcompa',$persos[$i]);
if($perso['niveau_compa'])
  $script.='  document.getElementById("niveau_compa").value=niveau_compa;
';
if($perso['niveau_grade']==1)
  $script.=fin_scriptage('droits',$persos[$i]);
if($perso['ordrescompa'] && autorisation('ordres'))
  $script.='  document.getElementById("ordrescompa").checked=ordrescompa?"checked":"";
';
$script.='}
'.(isset($_POST['droits_ok'])?'':'afficheDroits();
').'</script>
';
  echo'<form method="post" action="compagnie.php?colo_droits">
<p>
',form_select('','droits_id',$ids,'afficheDroits();'),'<br />
',($perso['niveau_compa']?form_text('Niveau hiérarchique (maximum : '.($perso['niveau_compa']-1).'): ','niveau_compa','3','').'<br />':''),'
',make_form('RP','Description de la compagnie'),'
',make_form('criteres','Critères de recrutement'),'
',make_form('valider','Accepter ou non les postulants'),'
',make_form('ordres','Ordres de la compagnie'),'
',make_form('virer','Virer des membres'),'
',make_form('compa','Acc&eacute;s au forum'),'
',make_form('mcompa','Mod&eacute;ration du forum'),'
',(($perso['ordrescompa'] && $perso['colo_ordres'])?form_check('Lire les ordres de la compagnie : ','ordrescompa'):''),'<br />
',($perso['colo_colo']?make_form('droits','Gestion des droits'):''),'
',form_submit('droits_ok','Modifier'),'</p>
</form>
',$script;

function scriptage($nom,$perso)
{
  global $listeDroits;
  $plus='colo';
  if($listeDroits['colo'][$nom][0]){
    $plus='forum';
  }
  if(autorisation($nom))
    return '    droits_'.$nom.'='.$perso[$plus.'_'.$nom].';
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
  global $listeDroits;
  $plus='colo';
  if($listeDroits['colo'][$nom][0]){
    $plus='forum';
  }
  if(autorisation($nom))
    {
      $str=($GLOBALS['pos']?',':'').$plus.'_'.$nom."='".(isset($_POST['droits_'.$nom])?'1':'0')."'";
      $GLOBALS['pos']=1;
      return $str;
    }
  else
    return '';
}
?>