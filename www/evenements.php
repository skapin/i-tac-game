<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
if(isset($_GET['lite'])){
  com_header_lite();
}
else{
  com_header();
}

if(isset($_GET['id']) &&
   is_numeric($_GET['id'])){
  $avoir=my_fetch_array("SELECT nom FROM persos WHERE ID='$_GET[id]'");
    echo'<div id="fiche" class="liste">
';
  if($avoir[0]){
    echo'<h2>&Eacute;v&egrave;nements de ',bdd2html($avoir[1]['nom']),'</h2>
';
    if(isset($_SESSION['com_perso'])){
      require_once('../sources/monperso.php');
    }
    require_once('../inits/camps.php');
    require_once('../sources/events.php');
    $time=time();
    showEvents($time-259200,$time,$_GET['id']);
    echo'<a href="fiche.php?id=',$_GET['id'],(isset($_GET['lite'])?'&amp;lite=1':''),'">Fiche</a>
<a href="frags.php?id=',$_GET['id'],(isset($_GET['lite'])?'&amp;lite=1':''),'">Morts</a>
<a href="casier.php?id=',$_GET['id'],(isset($_GET['lite'])?'&amp;lite=1':''),'">Casier judicaire</a>
';
  }
  else{
    add_message(3,'Perso inexistant.');
  }

  echo'<form method="get" action="evenements.php">
<p>
',(isset($_GET['lite'])?'<input type="hidden" name="lite" value="1" />':''),'
Voir les &eacute;v&egrave;nements du matricule : <input type="text" size="4" name="id" />
<input type="submit" value="Voir" />
</p>
</form>
</div>
';
}
if(isset($_GET['lite']))
  com_footer_lite();
else
  com_footer();
/*  $camou=0;
  if(isset($_SESSION['com_perso'])&&$_SESSION['com_perso']==$_GET['id'])
    $camou=1;
  if(!$camou)
    $where='AND `type`<=6 AND (!events.camoufle OR (events.camoufle && perso1.ID='.$_GET['id'].' && `type`!=2))';
  else
    $where='AND !(`type`>=11 && tireur=\''.$_SESSION['com_perso'].'\')';
  $events=my_fetch_array('SELECT events.*,
                                 perso1.nom AS tireur_nom,
                                 perso1.ID AS tireur_ID,
                                 perso1.armee AS tireur_armee,
                                 compagnie1.initiales AS tireur_compa,
                                 perso2.nom AS cible_nom,
                                 perso2.ID AS cible_ID,
                                 perso2.armee AS cible_armee,
                                 compagnie2.initiales AS cible_compa
                          FROM events
                            LEFT OUTER JOIN persos AS perso1
                              ON perso1.ID=events.tireur
                            LEFT OUTER JOIN persos AS perso2
                              ON perso2.ID=events.cible
                            LEFT OUTER JOIN compagnies AS compagnie1
                              ON perso1.compagnie=compagnie1.ID
                            LEFT OUTER JOIN compagnies AS compagnie2
                              ON perso2.compagnie=compagnie2.ID
                          WHERE events.date>='.(time()-604800).'
                            AND (perso1.ID='.$_GET['id'].'
                             OR  perso2.ID='.$_GET['id'].')
                            '.$where.'
                          ORDER BY events.ID DESC
                          LIMIT 30');
  if($events[0])
    {
      echo'<div id="fiche" class="liste">
<h2>&Eacute;v&egrave;nements de ',bdd2html($events[1]['tireur_ID']==$_GET['id']?$events[1]['tireur_nom']:$events[1]['cible_nom']).'</h2>
<ul>
';
      for($i=1;$i<=$events[0];$i++)
	{
	  $date=date("\Le d/m/Y � G\hi\m\\ns\s.",$events[$i]['date']).'
';
	  echo'<li>';
	  if($events[$i]['tireur_ID']==$_GET['id']) // Le perso est le tireur.
	    {
	      if($events[$i]['cible_ID'])
		$cible='<a href="evenements.php?id='.$events[$i]['cible_ID'].(isset($_GET['lite'])?'&amp;lite=1':'').'">'.bdd2html($events[$i]['cible_nom']).' ('.camp_initiale($events[$i]['cible_armee']).'-'.bdd2html($events[$i]['cible_compa']).'-'.$events[$i]['cible_ID'].')</a>';
	      else
		$cible='';
	      if($events[$i]['type']==1) // Tir r�ussi
		{
		  if($camou==1)
		    echo 'Vous avez tir� sur ',$cible,'. Vous lui avez fait perdre ',$events[$i]['PA'],' PA et ',$events[$i]['PV'],' PV.',($events[$i]['mort']?' Il en est mort.':'').$date;
		  if(!$camou)
		    echo 'Il a tir� sur ',$cible,'.',($events[$i]['mort']?' Il en est mort.':'').$date;
		}
	      if($events[$i]['type']==2) // Tir loup�
		{
		  if($camou==1)
		    echo 'Vous avez rat� votre tir sur ',$cible,'.',$date;
		  if(!$camou)
		    echo 'Il a rat� un tir sur ',$cible,'.',$date;
		}
	      if($events[$i]['type']==3) // R�paration
		{
		  if($camou==1)
		    echo 'Vous avez r�par� l\'armure de ',$cible,'. Vous lui avez fait r�cup�rer ',$events[$i]['PA'],' PA.',$date;
		  if(!$camou)
		    echo 'Il a r�par� l\'armure de ',$cible,'.',$date;
		}
	      if($events[$i]['type']==4) // Soins
		{
		  if($camou==1)
		    echo 'Vous avez soign� ',$cible,'. Vous lui avez fait gagner ',$events[$i]['PV'],' PV.',$date;
		  if(!$camou)
		    echo 'Il a tir� sur ',$cible,'.',($events[$i]['mort']?' Il en est mort.':'').$date;
		}
	      if($events[$i]['type']==5) // Marche sur une mine
		{
		  if($camou==1)
		    echo 'Une de vos mines a saut�.',$date;
		}
	      if($events[$i]['type']==6) // Noyade
		{
		  if($camou==1)
		    echo ' Vous avez noy� ',$cible,'.',$date;
		  if(!$camou)
		    echo ' Il a noy� ',$cible,'.',$date;
		}
	      if($events[$i]['type']==7) // Posage de mine
		{
		  if($camou==1)
		    echo 'Vous avez pos� une mine en X=',$events[$i]['X'],',Y=',$events[$i]['Y'],'.',$date;
		}
	      if($events[$i]['type']==8) // Posage de pont
		{
		  if($camou==1)
		    echo 'Vous avez pos� un pont en X=',$events[$i]['X'],',Y=',$events[$i]['Y'],'.',$date;
		}
	    }
	  else if($events[$i]['cible_ID']==$_GET['id']) // Le perso est la cible.
	    {
	      if(!$events[$i]['camoufle'] && $events[$i]['tireur_armee'])
		$cible='<a href="evenements.php?id='.$events[$i]['tireur_ID'].(isset($_GET['lite'])?'&amp;lite=1':'').'">'.bdd2html($events[$i]['tireur_nom']).' ('.camp_initiale($events[$i]['tireur_armee']).'-'.bdd2html($events[$i]['tireur_compa']).'-'.$events[$i]['tireur_ID'].')</a>';
	      else
		$cible="Quelqu'un";
	      if($events[$i]['type']==1) // Tir r�ussi
		{
		  if($camou==1)
		    echo $cible,' vous a tir� dessus. Vous avez perdu ',$events[$i]['PA'],' PA et ',$events[$i]['PV'],' PV.',($events[$i]['mort']?' Vous en �tes mort.':'').$date;
		  if(!$camou)
		    echo $cible,' lui a tir� dessus.',($events[$i]['mort']?' Il en est mort.':'').$date;
		}
	      if($events[$i]['type']==2) // Tir loup�
		{
		  if($camou==1)
		    echo $cible,' vous a rat�.',$date;
		  if(!$camou)
		    echo $cible,' a rat� son tir sur lui.',$date;
		}
	      if($events[$i]['type']==3) // R�paration
		{
		  if($camou==1)
		    echo $cible,' a r�par� votre armure de ',$events[$i]['PA'],' PA.',$date;
		  if(!$camou)
		    echo $cible,' a r�par� son armure.',$date;
		}
	      if($events[$i]['type']==4) // Soins
		{
		  if($camou==1)
		    echo $cible,'Vous a soign� de ',$events[$i]['PV'],' PV.',$date;
		  if(!$camou)
		    echo $cible,' l\'a soign�.',$date;
		}
	      if($events[$i]['type']==5) // Marche sur une mine
		{
		  if($camou==1)
		    echo ' Vous avez march� sur une mine. Vous avez perdu ',$events[$i]['PA'],' PA et ',$events[$i]['PV'],' PV.',$date;
		  if(!$camou)
		    echo ' Il a march� sur une mine.',$date;
		}
	      if($events[$i]['type']==6) // Noyade
		{
		  if(!$events[$i]['PV'])
		    {
		      if($camou==1)
			echo $cible,' vous a noy�.',$date;
		      if(!$camou)
			echo $cible,' l\'a noy�.',$date;
		    }
		  else
		    {
		      if($camou==1)
			echo 'Il semble que la nature ne vous aime pas.';
		      if(!$camou)
			echo 'Homard m\'a tuer.';
		    }
		}
	      if($events[$i]['type']==7) // Mort en posant une mine
		{
		  if($events[$i]['PV']){
		  }
		}
	      if($events[$i]['type']==9) // Gain de grade
		{
		  if($camou==1)
		    echo 'Vous avez �t� promu ',numero_camp_grade($events[$i]['cible_armee'],$events[$i]['PV']),',',$date;
		}
	      if($events[$i]['type']==10) // Perte de grade
		{
     		  if($camou==1)
		    echo 'Vous avez �t� d�grad� au rang de ',numero_camp_grade($events[$i]['cible_armee'],$events[$i]['PV']),'.',$date;
		}
	      if($events[$i]['type']==11) // Postulation refus�e
		{
     		  if($camou==1)
		    echo $cible,' a refus� votre postulation.<br />
Motif : ',bdd2html($events[$i]['raison']),'<br />',$date;
		}
	      if($events[$i]['type']==12) // Cr�ation de compagnie refus�e.
		{
     		  if($camou==1)
		    echo $cible,' a refus� la cr�ation de votre compagnie.<br />
Motif : ',bdd2html($events[$i]['raison']),'<br />',$date;
		}
	      if($events[$i]['type']==13) // Nommination comme g�n�ral en chef
		{
     		  if($camou==1)
		    echo $cible,' vous a nomm� ',grade_spec_autre($perso['armee'],3,''),'.',$date;
		}
	      if($events[$i]['type']==14) // Nommination comme g�n�ral.
		{
     		  if($camou==1)
		    echo $cible,' vous a nomm� ',grade_spec_autre($perso['armee'],2,''),'.',$date;
		}
	      if($events[$i]['type']==15) // Perte de grade de g�n�ral.
		{
     		  if($camou==1)
		    echo $cible,' vous a enlev� votre grade.',$date;
		}
	      if($events[$i]['type']==16) // Cours martiale
		{
     		  if($camou==1)
		    echo $cible,' vous a fait passer en cours martiale.<br />
',bdd2html($events[$i]['raison']),'<br />',$date;
		}
	    }
	  echo'</li>
';
	}
*/
?>
