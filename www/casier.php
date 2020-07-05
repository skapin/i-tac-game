<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
if(isset($_GET['lite']))
  com_header_lite();
else
  com_header();
require_once('../inits/camps.php');
if(isset($_GET['id']))
{
  if(is_numeric($_GET['id']))
    {
      $perso=my_fetch_array('SELECT persos.nom,
                                persos.ID,
                                persos.armee, 
                                perso2.nom AS nom2,
                                perso2.ID AS perso_ID2,
                                perso2.armee AS armee2,
                                compa2.initiales AS compa2,
                                casier.*
                         FROM persos
                            INNER JOIN compte
                               ON compte.ID=persos.compte
                            LEFT OUTER JOIN casier
                               ON persos.ID=casier.victime
                            LEFT OUTER JOIN persos AS perso2
                               ON perso2.ID=casier.bourreau 
                            LEFT OUTER JOIN compagnies AS compa2
                               ON compa2.ID=perso2.compagnie
                         WHERE persos.ID='.$_GET['id']);
      if($perso[0])
	{
	  echo'<div id="casier">
<h2>Casier judiciaire de ',bdd2html($perso[1]['nom']),'</h2>
<ul>
';
	    for($i=1;$i<=$perso[0];$i++)
	      {
		if($perso[$i]['debut'])
		  echo'<li>Du ',date('d-m-Y',$perso[$i]['debut']), ' au ',date('d-m-Y',$perso[$i]['fin']),' :<hr />
',($perso[$i]['assaut']?($perso[$i]['assaut']==-1?'Interdiction de prendre des armes de type Assaut.<br />':'Interdiction de prendre des armes de type Assaut n�cessitant une comp�tence sup�rieure � '.$perso[$i]['assaut'].'.<br />'):''),'
',($perso[$i]['pompe']?($perso[$i]['pompe']==-1?'Interdiction de prendre des armes de type Fusils � pompe.<br />':'Interdiction de prendre des armes de type Fusils � pompe n�cessitant une comp�tence sup�rieure � '.$perso[$i]['pompe'].'.<br />'):''),'
',($perso[$i]['snipe']?($perso[$i]['snipe']==-1?'Interdiction de prendre des armes de type Fusils de pr�cision.<br />':'Interdiction de prendre des armes de type Fusils de pr�cision n�cessitant une comp�tence sup�rieure � '.$perso[$i]['snipe'].'.<br />'):''),'
',($perso[$i]['mitrailleuse']?($perso[$i]['mitrailleuse']==-1?'Interdiction de prendre des armes de type Mitrailleuse.<br />':'Interdiction de prendre des armes de type Mitrailleuse n�cessitant une comp�tence sup�rieure � '.$perso[$i]['mitrailleuse'].'.<br />'):'').'
'.($perso[$i]['lourde']?($perso[$i]['lourde']==-1?'Interdiction de prendre des armes Lourdes.<br />':'Interdiction de prendre des armes Lourdes n�cessitant une comp�tence sup�rieure � '.$perso[$i]['lourde'].'.<br />'):''),'
',($perso[$i]['mecano']?($perso[$i]['mecano']==-1?'Interdiction de prendre des outils de r�paration.<br />':'Interdiction de prendre des outils de r�paration n�cessitant une comp�tence sup�rieure � '.$perso[$i]['mecano'].'.<br />'):''),'
',($perso[$i]['pistolet']?($perso[$i]['pistolet']==-1?'Interdiction de prendre des armes de type Pistolet.<br />':'Interdiction de prendre des armes de type Pistolet n�cessitant une comp�tence sup�rieure � '.$perso[$i]['pistolet'].'.<br />'):''),'
',($perso[$i]['cac']?($perso[$i]['cac']==-1?'Interdiction de prendre des armes de Corps � corps.<br />':'Interdiction de prendre des armes de Corps � corps n�cessitant une comp�tence sup�rieure � '.$perso[$i]['cac'].'.<br />'):''),'
',($perso[$i]['medoc']?($perso[$i]['medoc']==-1?'Interdiction de prendre du mat�riel de m�decin.<br />':'Interdiction de prendre du mat�riel de m�decin n�cessitant une comp�tence sup�rieure � '.$perso[$i]['medoc'].'.<br />'):''),'
',($perso[$i]['tube']?'Interdiction d\'utiliser le syst�me de tube.<br />':''),'
',($perso[$i]['armes']?'Interdiction de changer d\'arme dans un QG.<br />':''),'
',($perso[$i]['munars']?'Pas de ravitaillement en munitions dans les QGs.<br />':''),'
',($perso[$i]['armures']?'Interdiction de changement d\'armure dans un QG.<br />':''),'
',($perso[$i]['reparations']?'Pas de r�paration d\'armure dans les QGs.<br />':''),'
',($perso[$i]['hopital']?'Pas d\'acc�s aux h�pitaux de campagne.<br />':''),'
',($perso[$i]['grade']?'Limitation de l\'�quipement au grade de '.numero_camp_grade($perso[1]['armee'],$perso[1]['grade']-1).'.<br />':''),'
',($perso[$i]['TRT']?'T�te mise � prix.<br />':''),'
',($perso[$i]['forum']?'Acc�s aux forums de camps r�voqu�s.<br />':''),'
',($perso[$i]['VS']?($perso[$i]['VS']==-1?'Progression hi�rarchique stopp�e.':'Progression hi�rarchique divis�e par '.$perso[$i]['VS'].'.<br />'):'').'
<hr />
Peine prononc�e par : ',bdd2html($perso[$i]['nom2']),'<br />
Raison : ',bdd2html($perso[$i]['raison']),'
</li>
';
	      }
	}
      echo'</ul>
<a href="fiche.php?id=',$_GET['id'],'',(isset($_GET['lite'])?'&amp;lite=1':''),'">Fiche</a>
<a href="evenements.php?id=',$_GET['id'],'',(isset($_GET['lite'])?'&amp;lite=1':''),'">Ev�nements</a> 
<a href="frags.php?id=',$_GET['id'],'',(isset($_GET['lite'])?'&amp;lite=1':''),'">Morts</a>
<form method="get" action="casier.php">
<p>
',(isset($_GET['lite'])?'<input type="hidden" name="lite" value="1" />':''),'
Voir le casier judiciaire du matricule : <input type="text" size="4" name="id" />
<input type="submit" value="Voir" />
</p>
</form>
';
    }
  else
    {
      add_message(3,'Perso inexistant.');
      echo'<div id="casier">
<form method="get" action="casier.php">
<p>
',(isset($_GET['lite'])?'<input type="hidden" name="lite" value="1" />':''),'
Voir le casier judiciaire du matricule : <input type="text" size="4" name="id" />
<input type="submit" value="Voir" />
</p>
</form>
</div>
';
    }
}
else
  echo'<div id="casier">
<form method="get" action="casier.php">
<p>
',(isset($_GET['lite'])?'<input type="hidden" name="lite" value="1" />':''),'
Voir le casier judiciaire du matricule : <input type="text" size="4" name="id" />
<input type="submit" value="Voir" />
</p>
</form>
</div>
';
if(isset($_GET['lite']))
  com_footer_lite();
else
  com_footer();
?>
