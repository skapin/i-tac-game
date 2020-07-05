<?php
  /*$munars=my_fetch_array('SELECT nombre,
type
FROM tas_munitions
WHERE X='.$perso['X'].'
AND Y='.$perso['Y'].'
AND map='.$perso['map'].'
AND (`type`='.$perso['type_munars_arme'.$slot].'
  OR `type`='.$perso['type_munars_arme'.$slot2].')');
for($i=1;$i<=$munars[0];$i++)
{
  if($munars[$i]['type']==$perso['type_munars_arme'.$slot])
    $leslot=$slot;
  else
    $leslot=$slot2;
  if($_SESSION['com_arme'.$slot]['munars_grandmax']<($_SESSION['com_arme'.$slot]['munars']+$munars[$i]['nombre']))
    {
      // Plus de munitions par terre que ce qu'on peut prendre :(
      request("UPDATE persos SET munitions_$bddslot='".$_SESSION['com_arme'.$slot]['munars_grandmax']."' WHERE ID='$_SESSION[com_perso]'");
      request("UPDATE tas_munitions SET nombre='".($munars[$i]['nombre']-$_SESSION['com_arme'.$slot]['munars_grandmax']+$_SESSION['com_arme'.$slot]['munars'])."' WHERE X='".$perso['X']."' AND Y='".$perso['Y']."' AND map='".$perso['map']."' AND `type`='".$_SESSION['com_arme'.$slot]['type_munars']."'");
      add_message(2,"Vous avez rammasez ".($_SESSION['com_arme'.$slot]['munars_grandmax']-$_SESSION['com_arme'.$slot]['munars'])." munitions de type ".bdd2html($_SESSION['com_arme'.$slot]['nom_munars']));
    }
  else
    {
      request("UPDATE persos SET munitions_$bddslot=munitions_$bddslot+'".$munars[$i]['nombre']."' WHERE ID='$_SESSION[com_perso]'");
      request("DELETE FROM tas_munitions WHERE X='".$perso['X']."' AND Y='".$perso['Y']."' AND map='".$perso['map']."' AND `type`='".$_SESSION['com_arme'.$slot]['type_munars']."'");
      add_message(2,"Vous avez rammasez ".$munars[$i]['nombre']." munitions de type ".bdd2html($_SESSION['com_arme'.$slot]['nom_munars']));
    }
    }*/
?>