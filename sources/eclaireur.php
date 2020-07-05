<?php
if(isset($_POST['ecl_ok'])&&isset($perso)&&$perso['PM']>=20 && $perso['map'])
{
  if($perso['date_ecl']-time()<=0)
    {
      $timer=$time+$GLOBALS['tour']/5;
      add_message(2,'Vous êtes maintenant plus attentif à votre environnement.');
    }
  else
    {
      $timer=$perso['date_ecl']+$GLOBALS['tour']/5;
      add_message(2,'Vous avez augmenté la durée de vos observations.');
    }
  $update=monte_comp('ecl',0.5);
  request('UPDATE `persos` SET `date_ecl`='.$timer.',`PM`=`PM`-20 '.$update[0].' WHERE `ID`='.$_SESSION['com_perso']);
}
?>