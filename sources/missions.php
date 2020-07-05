<?php
if($perso['armee']){
  $missions=my_fetch_array('SELECT *
                              FROM missions
                                 INNER JOIN missions_camps
                                    ON missions.ID=missions_camps.mission
                              WHERE missions_camps.camp='.$perso['armee'].'
                                AND missions.ouverte=1');

  if($missions[0]){
    echo'<ul id="listeMissions">
';
    for($i=1;$i<=$missions[0];$i++){
      echo' <li id="mission_',$missions[$i]['ID'],'"',($i==1 && !$perso['ID_mission'] || $perso['ID_mission'] == $missions[$i]['ID']?' class="active"':''),'><a href="#"><span>',bdd2html($missions[$i]['nom']),'</span></a></li>
';
    }
    echo'</ul>
';

    for($i=1;$i<=$missions[0];$i++){
      $inscrits=my_fetch_array('SELECT COUNT(ID)
                                      FROM persos
                                      WHERE mission='.$missions[$i]['ID'].'
                                        AND armee='.$perso['armee']);
      echo '<div id="infomission_'.$missions[$i]['ID'].'" class="mission">
<p>'.bdd2html($missions[$i][2]).'</p>
<p>'.bdd2html($missions[$i]['description']).'</p>
<p>Inscrits : '.($inscrits[1][0]).'/'.$missions[$i]['nbr_maxi'].''.($missions[$i]['ID']==$perso['ID_mission']?' (dont vous)':'').'<br />
Grade minimum : '.numero_camp_grade($perso['armee'],$missions[$i]['grade_mini']).'<br />
Grade maximum : '.numero_camp_grade($perso['armee'],$missions[$i]['grade_maxi']).'<br />
';
      if($perso['ID_arme1']&&$perso['ID_arme2']&&$perso['ID_gad1']&&$perso['ID_gad2']&&$perso['ID_gad3']){
	echo '<form method="post" action="jouer.php">
<p>
'.form_hidden('mission',$missions[$i]['ID']).' 
 <input type="submit" name="choix_mission_ok" class="bouton_'.($missions[$i]['ID']==$perso['ID_mission']?'sortir" value="Annuler"':'ok" value="S\'inscrire"').'
/>'.($missions[$i]['ID']==$perso['ID_mission'] && $missions[$i]['active']?'<input type="submit" id="entre_carte_ok" name="entre_carte_ok"  value="Entrer" class="bouton_carte" />':'').'</p>
</form> 
';
	// On choppe les cartes de la missions.
	$cartes=my_fetch_array('SELECT ID,radar_date,terrain,satellite
FROM cartes
INNER JOIN cartes_radars
ON cartes_radars.carte=cartes.ID
AND cartes_radars.camp='.$perso['armee'].'
WHERE cartes.mission='.$missions[$i]['ID'].'');
	for($j=1;$j<=$cartes[0];$j++){
	  if($cartes[$j]['satellite'] &&
	     $cartes[$j]['radar_date']){
	    echo'<img src="images/radars/'.$cartes[$j]['ID'].'_'.$cartes[$j]['radar_date'].'.png" />';
	  }
	  else if($cartes[$j]['terrain'] &&
		  $cartes[$j]['radar_date']){
	    echo'<img src="images/cartes/'.$cartes[$j]['ID'].'.png" />';
	  }
	}
      }
      echo '</div>
';
    }
  }
}
?>