<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header_lite();
if(!isset($_SESSION['com_perso'])){
    echo 'Vous n\'&ecirc;tes pas loggu&eacute;.';
    com_footer();
    die();
}
require_once('../sources/monperso.php');
require_once('../inits/competences.php');
require_once('../inits/terrains.php');
if($perso['sprints']<$perso['sprints_max'] &&
   $perso['PM']<100)
{
  echo'<form method="post" action="jouer.php" target="_parent">
<p>
',form_check('','sprint_confirm'),'
',form_text('Sprinter de : ','sprints','3',''),' (',min($perso['sprints_max']-$perso['sprints'],100-$perso['PM']),' maximum) 
',form_submit('sprint_ok','Ok'),'
</p>
</form>
';
}

// On calcule le niveau de camouflage du perso. Si c'est inf�rieur � 0 il ne pourra pas se camoufler.
if(!$perso['camouflage'] && $perso['map'])
{
  /*
Premi�rement, y a le malus d� � l'armure, celui d� au terrain.
Apr�s, le malus d� � un tir qui s'estompe apr�s la moiti� d'un tour.
   */
  echo'<h2>Camouflage</h2>
<h3>Bonus et malus :</h3>
<ul>
<li>Comp�tence camouflage niveau ',$perso['camou'],' : '.comp('camou','reussite').'%</li> 
';
  if($perso['camouflage']==1)
    {
      echo'<li>Vous �tes parfaitement camoufl� : +10%</li>
';
      $bonus_camou=10;
    }
  if($perso['camouflage']==2)
    {
      echo'<li>Vous �tes bien camoufl� : +5%</li>
';
      $bonus_camou=5;
    }
  if($perso['camouflage']==3)
    {
      echo'<li>Vous �tes camoufl�</li>
';
      $bonus_camou=0;
    }
  if($perso['camouflage']==4)
    {
      echo'<li>Vous �tes mal camoufl� : -5%</li>
';
      $bonus_camou=-5;
    }
  if($perso['camouflage']==5)
    {
      echo'<li>Vous �tes tr�s mal camoufl� : -10%</li>
';
      $bonus_camou=-10;
    }
  if($perso['camouflage']==0||$perso['camouflage']==6)
    {
            echo'<li>Vous n\'�tes pas camoufl� : -15%</li>
';
      $bonus_camou=-15;
    }

  if((($time-$perso['date_last_tir'])>$GLOBALS['tour']/2) || !$perso['malus_camou_tir'])
    $malus_tir=0;
  else
    $malus_tir=round($perso['malus_camou_tir']-($time-$perso['date_last_tir'])*$perso['malus_camou_tir']/($GLOBALS['tour']/2));

  echo'<li>Malus d� � vos tirs : -',$malus_tir,'%</li>
 <li>Malus d� � votre armure : -',$perso['malus_camou_armure'],'%</li>
 <li>Malus d� au terrain : -',($_SESSION['com_terrain']['malus_camou'] * comp($_SESSION['com_terrain']['competence'],'camouflage')),'%</li>
</ul>
';
  
  $camou=$bonus_camou - $malus_tir + comp('camou','reussite') - $_SESSION['com_terrain']['malus_camou'] * comp($_SESSION['com_terrain']['competence'],'camouflage') - $perso['malus_camou_armure'];

  $cout=round($_SESSION['com_terrain']['prix_'.$perso['type_armure']] * comp($_SESSION['com_terrain']['competence'],'mouv')*(comp('camou','cout')-1));

  if($cout<=$perso['PM'] && $camou>0) // Les chances d'arriver � bien se camoufler sont sup�rieures � 0 : on peut passer en mode camou.
    echo'<form method="post" action="jouer.php" target="_parent">
<p>
',form_check('','camou_confirm'),'
',form_submit('camou_ok','Se camoufler.'),' (Chances de r�ussites : ',$camou,' %, co�t : ',$cout,' PM)
</p>
</form>
';
  else
    echo'<p>
Impossible de vous camoufler, chances de r�ussites : ',$camou,'%
</p>
';
}
else if($perso['map'])
{
  if($perso['camouflage']==1)
    {
      $bonus_camou=10;
    }
  if($perso['camouflage']==2)
    {
      $bonus_camou=5;
    }
  if($perso['camouflage']==3)
    {
      $bonus_camou=0;
    }
  if($perso['camouflage']==4)
    {
      $bonus_camou=-5;
    }
  if($perso['camouflage']==5)
    {
      $bonus_camou=-10;
    }
  if($perso['camouflage']==0||$perso['camouflage']==6)
    {
      $bonus_camou=-15;
    }


  if((($time-$perso['date_last_tir'])>$GLOBALS['tour']/2) || !$perso['malus_camou_tir'])
    $malus_tir=0;
  else
    $malus_tir=round($perso['malus_camou_tir']-($time-$perso['date_last_tir'])*$perso['malus_camou_tir']/($GLOBALS['tour']/2));

  
  $camou=$bonus_camou - $malus_tir + comp('camou','reussite') - $_SESSION['com_terrain']['malus_camou'] * comp($_SESSION['com_terrain']['competence'],'camouflage') - $perso['malus_camou_armure'];
  echo'<p>Vous &ecirc;tes camoufl&eacute;. Chances de camouflage &agrave; votre prochain mouvement: ',(round($camou/10)*10),'%</p>
<form method="post" action="jouer.php" target="_parent">
<p>
',form_check('','camou_confirm'),'
',form_submit('camou_stop_ok','Ne plus avancer camoufl�'),'
</p>
</form>
';
}
if($perso['PM']>=20 && $perso['map'])
{
  $time=temps($GLOBALS['tour']/5);
  if($perso['date_ecl']-time()>0)
    $ecl=temps($perso['date_ecl']-time());
  else
    $ecl=0;
  echo'<h2>Eclaireur</h2>
<form method="post" action="jouer.php" target="_parent">
<p>
',form_check('','ecl_confirm'),'
',form_submit('ecl_ok','Observer le terrain.'),'<br />
Co�te 20 PM pour augmenter ou prolonger la dur�e d\'augmentation de votre comp�tence �claireur de 2 niveaux pendant ',$time['heures'],'h',$time['minutes'],'mn.',($ecl?' (Vous b�n�ficiez d�j� d\'un bonus durant encore '.$ecl['heures'].'h'.$ecl['minutes'].'mn)':''),'
</p>
</form>
';
}
unset($perso);
com_footer_lite();
?>