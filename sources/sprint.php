<?php
if(isset($_POST['sprints'])&&$_POST['sprints']&&is_numeric($_POST['sprints']))
{
  if($_POST['sprints'] < 0){
    error_log($_POST['sprints']."\n",3,dirname(__FILE__).'/../logs/sprint_'.$_SESSION['com_perso'].'.log');
  }
  else{
    $sprints=min(100-$perso['PM'],$perso['sprints_max']-$perso['sprints'],$_POST['sprints']);
    request('UPDATE `persos` SET `PM`=`PM`+'.$sprints.', `sprints`=`sprints`+'.$sprints.' WHERE `ID`='.$_SESSION['com_perso']);
    add_message(2,'Vous venez de regagner '.$sprints.' PT.');
  }
}
?>