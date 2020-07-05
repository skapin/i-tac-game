
<?php
if(isset($_POST['camou_ok']))
{
  if($perso['camouflage']==1)
    $bonus_camou=10;
  if($perso['camouflage']==2)
    $bonus_camou=5;
  if($perso['camouflage']==3)
    $bonus_camou=0;
  if($perso['camouflage']==4)
    $bonus_camou=-5;
  if($perso['camouflage']==5)
    $bonus_camou=-10;
  if($perso['camouflage']==0||$perso['camouflage']==6)
    $bonus_camou=-15;

  if((($time-$perso['date_last_tir'])>$GLOBALS['tour']/2) || !$perso['malus_camou_tir'])
    $malus_tir=0;
  else
    $malus_tir=round($perso['malus_camou_tir']-($time-$perso['date_last_tir'])*$perso['malus_camou_tir']/($GLOBALS['tour']/2));

  $camou=$bonus_camou - $malus_tir + comp('camou','reussite') - $_SESSION['com_terrain']['malus_camou'] * comp($_SESSION['com_terrain']['competence'],'camouflage') - $perso['malus_camou_armure'];
  $cout=round($_SESSION['com_terrain']['prix_'.$perso['type_armure']] * comp($_SESSION['com_terrain']['competence'],'mouv')*(comp('camou','cout')-1));
  if($cout<=$perso['PM'] && $camou>0)
    {
      $camou=reussite_camou(0);
      $update=monte_comp('camou',$cout/150*0.4);
      $update=$update[0];
      request('UPDATE `persos`
               SET `camouflage`='.$camou.',
                   `PM`=`PM`-'.$cout.',
                   `date_camou`='.$time.'
                   '.$update.'
               WHERE persos.ID='.$_SESSION['com_perso'].'
               LIMIT 1');
      if(!$camou)
	add_message(2,'Trop de malus pour pouvoir vous camoufler.');
    }
  else
    add_message(1,'Pas assez de PM pour se camoufler.');
}
else if(isset($_POST['camou_stop_ok']))
{
  request('UPDATE `persos`
           SET `camouflage`=0,
               `date_camou`=0 
           WHERE `ID`='.$_SESSION['com_perso'].'
           LIMIT 1');
  add_message(2,'Vous n\'êtes plus camouflé.');
}
?>