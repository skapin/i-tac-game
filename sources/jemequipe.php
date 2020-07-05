<?php
if(isset($_POST['armor'],$_POST['gadget1'],$_POST['gadget2'],$_POST['gadget3'],$_POST['arme1'],$_POST['arme2']) &&
    is_numeric($_POST['arme2']) &&
    is_numeric($_POST['arme1']) &&
    is_numeric($_POST['gadget3']) &&
    is_numeric($_POST['gadget2']) &&
    is_numeric($_POST['gadget1']) &&
    is_numeric($_POST['armor'])){
  $grade=$perso['peine_grade']?$perso['peine_grade']-1:$perso['grade_reel'];
  // Il semble que l'équipement vient d'être changé.
  // Testage de ce que peut changer le perso.
  $change_armure=0;
  $change_arme=0;
  if(!($perso['map']||$perso['X']||$perso['Y'])){
    if($perso['date_last_shot']<($time-$GLOBALS['tour']))
      $change_armure=1;
    $change_arme=1;
  }
  else{
    $qgs=my_fetch_array('SELECT armes,armures,munitions,ID
        FROM qgs
        WHERE SQRT(POW(qgs.X-'.$perso['X'].',2)+POW(qgs.Y-'.$perso['Y'].',2))<=qgs.utilisation
        AND qgs.carte='.$perso['map'].'
        AND (qgs.camp='.$perso['armee'].' OR `type`=1)');
    if($qgs[0])
    {
      for($i=1;$i<=$qgs[0];$i++)
      {
        if($qgs[$i]['armes'] && !$perso['peine_armes'])
          $change_arme=1;
        if($qgs[$i]['armures'] && $perso['date_last_shot']<(time()-$GLOBALS['tour']) && !is_bloque($qgs[$i]['ID']) && !$perso['peine_armures'])
          $change_armure=1;
      }
    }
  }
  $update='';
  $i=0;
  if(($_POST['armor']!=$perso['ID_armure'])){
    // On vérifie tout d'abord qu'on peut prendre cette armure.
    if($change_armure){
      if(($time-$perso['date_last_shot'])>$GLOBALS['tour']){
        if(exist_in_db('SELECT armure
              FROM armures_camps
              WHERE armure='.$_POST['armor'].'
              AND camp='.$perso['armee'].'
              LIMIT 1')){
          $PAs=my_fetch_array('SELECT PA,`type`,capacite,grade FROM armures WHERE ID='.$_POST['armor']);
          if($PAs[0]){
            if($PAs[1]['grade']<=$grade){
              if(!$_SESSION['com_terrain'] || $_SESSION['com_terrain']['prix_'.$PAs[1]['type']]){
                $update='`PA`='.$PAs[1]['PA'].',`armure`='.$_POST['armor'];
                $perso['type_armure']=$PAs[1]['type'];
                $perso['poids_max']=$PAs[1]['capacite']+($PAs[1]['type']==1?2*$perso['imp_force']:0);
                $perso['special']=$perso['imp_force']>=5?1:0;
                $i++;
              }
              else
                add_message(1,'Impossible de prendre cette armure sur le terrain dans lequel vous vous trouvez.');
            }
            else
              add_message(1,'Vous n\'avez pas le grade nécessaire pour cette armure.');

          }
          else
            add_message(1,'Armure inconnue.');
        }
        else
          add_message(1,'Armure inconnue de votre armée.');
      }
      else
        add_message(1,'Vous ne pouvez pas changer d\'armure pour le moment. Elle a été abimée il y a trop peu de temps.');
    }
    else
      add_message(1,'Vous ne pouvez pas changer d\'armure dans ce QG.');
  }
  if($change_arme){
    // Maintenant les armes choisies.
    $armes=my_fetch_array('SELECT armes.ID, armes.type ,UNIX_TIMESTAMP(armes.firstUseDate) AS useDate, max_munitions,armure,palier,armes.poids,munars.poids AS poids_m
        FROM armes
        INNER JOIN armes_camps
        ON  armes_camps.arme=armes.ID 
        INNER JOIN munars
        ON  armes.type_munitions=munars.ID 
        WHERE (armes.ID='.$_POST['arme1'].'
          OR armes.ID='.$_POST['arme2'].')
        AND camp='.$perso['armee'].'
        LIMIT 2');
    if($armes[0]==2){
      if(($armes[1]['type']<=6 && $armes[2]['type']>6) || ($armes[2]['type']<=6 && $armes[1]['type']>6)){
        $erreur=0;
        for($j=1;$j<=$armes[0];$j++){
          if($perso['peine_'.bdd_arme($armes[$j]['type'])]){
            $niveau_arme=$perso['peine_'.bdd_arme($armes[$j]['type'])]-1;
	  }
          else{
            $niveau_arme=$perso[bdd_arme($armes[$j]['type'])];
	  }
	  // Apres ca, il faut avoir le droit de prendre ces armes.
          if($armes[$j]['palier']<=$grade+$niveau_arme){
            if(is_typearmure($perso['type_armure'],$armes[$j]['armure'])||
                ($perso['type_armure']==1&&
                 $perso['special']&&
                 is_typearmure(2,$armes[$j]['armure']))){
              if($armes[$j]['type']<=6){
		// Arme primaire
                if($i)
                  $update.=',';
                $update.='matos_1='.$armes[$j]['ID'].', munitions_1='.$armes[$j]['max_munitions'];
                $_SESSION['com_arme3']['ID']=$armes[$j]['ID'];
                $_SESSION['com_arme3']['useDate']=$armes[$j]['useDate'];
                $_SESSION['com_arme3']['type']=$armes[$j]['type'];
                $_SESSION['com_arme3']['poids']=$armes[$j]['poids']+$armes[$j]['poids_m']*$armes[$j]['max_munitions'];
                $i++;
              }
              else{
		// Arme de secours
                if($i)
                  $update.=',';
                $update.='matos_2='.$armes[$j]['ID'].',munitions_2='.$armes[$j]['max_munitions'];
                $i++;
                $_SESSION['com_arme4']['ID']=$armes[$j]['ID'];
                $_SESSION['com_arme4']['useDate']=$armes[$j]['useDate'];
                $_SESSION['com_arme4']['type']=$armes[$j]['type'];
                $_SESSION['com_arme4']['poids']=$armes[$j]['poids']+$armes[$j]['poids_m']*$armes[$j]['max_munitions'];
              }
            }
            else{
              $erreur=1;
              add_message(1,'Combinaison arme/armure impossible.');
            }
          }
          else{
            $erreur=1;
            add_message(1,'Grade ou compétence trop faibles pour utiliser cette arme.');
          }
        }
      }
      else{
        add_message(1,'Impossible de prendre 2 armes primaires ou 2 armes de secours.');
      }
    }
    else{
      add_message(1,'Il faut choisir 2 armes.');
    }
  }
  else{
    add_message(1,'Impossible de changer d\'arme dans ce QG.');
  }
  if($erreur){
    $update='';
  }
  else{
    $perso['ID_arme1']=$_SESSION['com_arme3']['ID'];
    $perso['type_arme1']=$_SESSION['com_arme3']['type'];
    $perso['ID_arme2']=$_SESSION['com_arme4']['ID'];
    $perso['type_arme2']=$_SESSION['com_arme4']['type'];
    $slot=1;
    $slot2=2;
    // Les gadgets.
    if($change_arme){
      $gadgets=my_fetch_array('SELECT ID,mines,arme,armure,stack
          FROM gadgets
          INNER JOIN gadgets_camps
          ON gadgets_camps.gadget=gadgets.ID
          WHERE ((ID='.$_POST['gadget1'].'
              AND grade_1<='.$grade.')
            OR (ID='.$_POST['gadget2'].'
              AND grade_2<='.$grade.')
            OR (ID='.$_POST['gadget3'].'
              AND grade_3<='.$grade.'))
          AND camp='.$perso['armee'].'
          LIMIT 3');
      $erreur=0;
      if($gadgets[0]<3){
        add_message(1,'Vos gadgets ne sont pas tous prenables.');
        $erreur=1;
      }
      else{
        if($gadgets[1]['stack']==$gadgets[2]['stack']||$gadgets[1]['stack']==$gadgets[3]['stack']||$gadgets[3]['stack']==$gadgets[2]['stack']){
          $erreur=1;
          add_message(1,'Vous ne pouvez prendre 2 gadgets utilisant la même position.');
        }
        for($i=1;$i<=3;$i++){
          if(!is_typearmure($perso['type_armure'],$gadgets[$i]['armure'])){
            add_message(1,'Gadget inutilisable avec le type d\'armure choisi.');
            $erreur=1;
          }
          $type_arme=dispo_arme($gadgets[$i]['arme']);
          $type_arme2=dispo_arme($gadgets[$i]['arme']);
          if(!$type_arme[$perso['type_arme1']]  && !$type_arme2[$perso['type_arme2']]){
            add_message(1,'Gadget inutilisable avec le type d\'arme choisi.');
            $erreur=1;
          }
        }
      }
      if(!$erreur){
        for($j=1;$j<=$gadgets[0];$j++){
          if(($gadgets[$j]['ID']==$_POST['gadget1']) && $_POST['gadget1']!=$perso['ID_gad1']){
            if(($time-$perso['date_last_used_gad1'])>$GLOBALS['tour']){
              if($i){
                $update.=', ';
	      }
              $update.='gadget_1='.$_POST['gadget1'];
              $perso['gadget_1']=$_POST['gadget1'];
              $i++;
              if($gadgets[$j]['mines'])
              {
                $update.=', mun_g1='.$gadgets[$j]['mines'].',used_1='.$time;
                $perso['mun_g1']=$gadgets[$j]['mines'];
              }
            }
            else
              add_message(1,'Gadget primaire utilisé depuis trop eu de temps pour être changé.');
          }
          if(($gadgets[$j]['ID']==$_POST['gadget2'])&&$_POST['gadget2']!=$perso['ID_gad2'])
          {
            if(($time-$perso['date_last_used_gad2'])>$GLOBALS['tour'])
            {
              if($i)
                $update.=', ';
              $update.='gadget_2='.$_POST['gadget2'];
              $perso['gadget_2']=$_POST['gadget2'];
              $i++;
              if($gadgets[$j]['mines'])
              {
                $update.=', mun_g2='.$gadgets[$j]['mines'].',used_2='.$time;
                $perso['mun_g2']=$gadgets[$j]['mines'];
              }
            }
            else
              add_message(1,'Gadget secondaire utilisé depuis trop peu de temps pour être changé.');
          }
          if(($gadgets[$j]['ID']==$_POST['gadget3'])&&$_POST['gadget3']!=$perso['ID_gad3'])
          {

            if(($time-$perso['date_last_used_gad3'])>$GLOBALS['tour'])
            {
              if($i)
                $update.=', ';
              $update.='gadget_3='.$_POST['gadget3'];
              $perso['gadget_3']=$_POST['gadget3'];
              $i++;
              if($gadgets[$j]['mines'])
              {
                $update.=', mun_g3='.$gadgets[$j]['mines'].',used_3='.$time;
                $perso['mun_g3']=$gadgets[$j]['mines'];
              }
            }
            else
              add_message(1,'Gadget tertiaire utilisé depuis trop peu de temps pour être changé.');
          }
        }
      }
      else
      {
        $update='';
      }
    }
    else
      add_message(1,'Impossible de changer de gadgets dans ce QG.');
  }
  if($update){
    request('UPDATE persos SET '.$update.' WHERE ID='.$_SESSION['com_perso'].' LIMIT 1');
    if($_SESSION['com_arme4']['useDate'] == 0){
      request('UPDATE armes SET firstUseDate=NOW(), firstUser='.$_SESSION['com_perso'].' WHERE ID='.$_SESSION['com_arme4']['ID']);
    }
    if($_SESSION['com_arme3']['useDate'] == 0){
      request('UPDATE armes SET firstUseDate=NOW(), firstUser='.$_SESSION['com_perso'].' WHERE ID='.$_SESSION['com_arme3']['ID']);
    }
  }
}
?>
