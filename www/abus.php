<?php
$camp = 11;
if(!empty($_GET['camp']) &&
   $_GET['camp'] == 'L'){
  $camp=5;
}
if(!empty($_GET['camp']) &&
   $_GET['camp'] == 'poy'){
  $camp=15;
}
$matricules=explode(';',$_GET['matricules']);
foreach($matricules AS $mat){
  echo $mat.' : '.getPV($mat,$camp).'<br/>
';
}

function getPV($mat,$camp){
  $pv=0;
  if(is_numeric($mat)){
    $page=file_get_contents('http://www.combattre-ou-mourir.com/~com_v106/compterendu2.php?pvgagne=0&joueur1='.$camp.'&joueur2='.$mat.'&newxp=3');
    if(preg_match('`([0-9]+) PV sur ([0-9]+)`',$page,$str)){
      $pv=$str[1].'/'.$str[2];
    }
  }
  return $pv;
}
?>
<form method="get" action="abus.php">
 <p>
  <select name="camp">
   <option>A</option>
   <option>L</option>
  </select><br />
  <textarea name="matricules"></textarea>
  <input type="submit">
 </p>
</form>