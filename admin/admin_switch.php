<?php
if(!empty($_REQUEST['days'])){
  $sql='SELECT id1,id2,moyen,switchs.IP,heure,c1.login AS l1, c2.login AS l2
FROM switchs
INNER JOIN compte AS c1
ON c1.ID=id1
INNER JOIN compte AS c2
ON c2.ID=id2
WHERE heure >= DATE_ADD(NOW(),INTERVAL -'.$_REQUEST['days'].' DAY)
ORDER BY heure DESC';
  $sw=my_fetch_array($sql);
  $trie=array();
    
  for($i=1;$i<=$sw[0];$i++){
    $id1=$sw[$i]['id1'];
    $id2=$sw[$i]['id2'];
    /*  premiere etape, on deroule jusqu'a arriver aux tableaux */
    if(!empty($trie[$id1])){
      while(is_numeric($trie[$id1])){
	$id1=$trie[$id1];
      }
    }
    if(!empty($trie[$id2])){
      while(is_numeric($trie[$id2])){
	$id2=$trie[$id2];
      }
    }
    $id=$id1;
    if(empty($trie[$id1]) && empty($trie[$id2])){
      $trie[$id1] = array('IP'=>($sw[$i]['moyen']==1)?array():array($sw[$i]),
			  'cookie'=>($sw[$i]['moyen']==1)?array($sw[$i]):array(),
			  'id'=>array($sw[$i]['id1']=>bdd2html($sw[$i]['l1']),
				      $sw[$i]['id2']=>bdd2html($sw[$i]['l2'])));
      $trie[$id2]=$id1;
    }
    else if(!empty($trie[$id1]) && !empty($trie[$id2]) && $id1 != $id2){
      $trie[$id1]=array_merge_recursive($trie[$id1],$trie[$id2]);
      $trie[$id2]=$id1;
    }
    else if(!empty($trie[$id2])){
      $id=$id2;
    }

    if($sw[$i]['moyen'] == 1){
      $trie[$id]['cookie'][]=$sw[$i];
    }
    else{
      $trie[$id]['IP'][]=$sw[$i];
    }
    $trie[$id]['id'][$sw[$i]['id1']]=bdd2html($sw[$i]['l1']);
    $trie[$id]['id'][$sw[$i]['id2']]=bdd2html($sw[$i]['l2']);
  }
    
  // Maintenant, tri du tableau
  usort($trie,'sortByCount');
    
  // Enfin, affichage
  foreach($trie AS $group){
    if(is_numeric($group)){
      continue;
    }
    echo'
 <h3>';
    foreach($group['id'] AS $id=>$login){
      echo ' '.$login.' ('.$id.') ';
    }
    echo'
 </h3>
 <h4>Cookies</h4>
 <table>
  <tr>
   <th>Date</th>
   <th>Compte 1</th>
   <th>Compte 2</th>
   <th>IP</th>
  </tr>';
    foreach($group['cookie'] AS $log){
      echo'
  <tr>
   <td>'.$log['heure'].'</td>
   <td>'.bdd2html($log['l1']).' ('.$log['id1'].')</td>
   <td>'.bdd2html($log['l2']).' ('.$log['id2'].')</td>
   <td>'.$log['IP'].'</td>
  </tr>';
    }
    echo'
 </table>
 <h4>IP</h4>
 <table>
  <tr>
   <th>Date</th>
   <th>Compte 1</th>
   <th>Compte 2</th>
   <th>IP</th>
  </tr>';
    foreach($group['IP'] AS $log){
      echo'
  <tr>
   <td>'.$log['heure'].'</td>
   <td>'.bdd2html($log['l1']).' ('.$log['id1'].')</td>
   <td>'.bdd2html($log['l2']).' ('.$log['id2'].')</td>
   <td>'.$log['IP'].'</td>
  </tr>';
    }
    echo'
 </table>';
  }
 }

if(!empty($_REQUEST['accounts'])){
  $accounts=explode(';',$_REQUEST['accounts']);
  $sqlNames='';
  $sqlPersos='';
  $sqlIP='';
  foreach($accounts AS $account){
    if(!empty($account) && is_numeric($account)){
      if(!empty($sqlNames)){
	$sqlNames.=' OR ';
	$sqlIP.=' OR ';
      }
      $sqlNames.='ID='.$account;
      $sqlIP.='compte='.$account;
    }
  }
  if(!empty($sqlNames)){
    $names=my_fetch_array('SELECT ID,login,mail,last_login FROM compte WHERE '.$sqlNames);
    echo'
<table class="liste">
 <tr>
  <th>ID</th>
  <th>Dernier login</th>
  <th>Nom</th>
  <th>E-mail</th>
  <th>Persos</th>
  <th>MdP (md5, metaphone, soundex)</th>
 </tr>';
    for($i=1;$i<=$names[0];$i++){
      $persos=my_fetch_array('SELECT persos.ID,persos.nom,camps.nom AS nom_camp FROM persos
INNER JOIN camps
ON camps.ID = persos.armee
 WHERE compte='.$names[$i]['ID']);
      $mdp=my_fetch_array('SELECT encrypted,metaphone,soundex FROM info_pass WHERE compte='.$names[$i]['ID']);
      echo'
 <tr>
  <td>'.$names[$i]['ID'].'</td>
  <td>'.date('Y-m-d H:i:s',$names[$i]['last_login']).'</td>
  <td>'.bdd2html($names[$i]['login']).'</td>
  <td>'.bdd2html($names[$i]['mail']).'</td>
  <td>
   <ul>';
      for($j=1;$j<=$persos[0];$j++){
	if(!empty($sqlPersos)){
	  $sqlPersos.=' OR ';
	}
	$sqlPersos.='events.cible='.$persos[$j]['ID'].' OR events.tireur='.$persos[$j]['ID'];
	echo'
    <li>'.bdd2html($persos[$j]['nom']).' ('.$persos[$j]['ID'].') / '.bdd2html($persos[$j]['nom_camp']).'</li>';
      }
      echo'
   </ul>
  </td>
  <td>
   <ul>';
      for($j=1;$j<=$mdp[0];$j++){
	echo'
    <li>'.$mdp[$j]['encrypted'].', '.$mdp[$j]['metaphone'].', '.$mdp[$j]['soundex'].'</li>';
      }
      echo'
   </ul>
  </td>
 </tr>';
    }
    echo'
</table>';
  }
  if(!empty($_REQUEST['days2'])){
    $polop=array();
    // Puis recup de tout ce qui est attaques, switchs, infos de connexion.
    $events=my_fetch_array('SELECT perso1.nom AS nom_tireur,
perso1.ID AS ID_tireur,
c1.login AS login_tireur,
perso1.compte AS compte_tireur,
perso2.nom AS nom_cible,
perso2.ID AS ID_cible,
c2.login AS login_cible,
perso2.compte AS compte_cible,
events.date,
events.type
FROM events
 INNER JOIN persos AS perso1
  ON perso1.ID=events.tireur
 LEFT OUTER JOIN compte AS c1
  ON perso1.compte=c1.ID
 INNER JOIN persos AS perso2
  ON perso2.ID=events.cible
 LEFT OUTER JOIN compte AS c2
  ON perso2.compte=c2.ID
WHERE events.type BETWEEN 1 AND 4
AND ('.$sqlPersos.')
AND events.date >='.(time()-86400*$_REQUEST['days2']));
    for($i=1;$i<=$events[0];$i++){
      if(!isset($polop[$events[$i]['date']])){
	$polop[$events[$i]['date']]='';
      }
      $plus='tire sur';
      if($events[$i]['type'] == 3){
	$plus='r&eacute;pare';
      }
      else if($events[$i]['type'] == 4){
	$plus='soigne';
      }
      else if(!in_array($events[$i]['compte_tireur'],$accounts)){
	continue;
      }
      $polop[$events[$i]['date']].='
 <li>'.date('Y-m-d H:i:s',$events[$i]['date']).' <span class="'.(in_array($events[$i]['compte_tireur'],$accounts)?'account':'target').'">'.bdd2html($events[$i]['login_tireur']).'</span> '.bdd2html($events[$i]['nom_tireur']).' ('.$events[$i]['ID_tireur'].') '.$plus.' '.bdd2html($events[$i]['nom_cible']).' ('.$events[$i]['ID_cible'].') <span class="'.(in_array($events[$i]['compte_cible'],$accounts)?'account':'target').'">'.bdd2html($events[$i]['login_cible']).'</span></li>';

    }
    unset($events);
    if(!empty($sqlIP)){
      $connexions=my_fetch_array('SELECT UNIX_TIMESTAMP(heure) AS horaire,info_connexion.IP,dns,info_connexion.compte,compte.login
FROM info_connexion
INNER JOIN compte ON
compte.ID=info_connexion.compte
WHERE ('.$sqlIP.')
AND heure>= DATE_ADD(NOW(),INTERVAL -'.$_REQUEST['days2'].' DAY)');
      for($i=1;$i<=$connexions[0];$i++){
	if(!isset($polop[$connexions[$i]['horaire']])){
	  $polop[$connexions[$i]['horaire']]='';
	}
	$polop[$connexions[$i]['horaire']].='
 <li>'.date('Y-m-d H:i:s',$connexions[$i]['horaire']).' <span class="account">'.bdd2html($connexions[$i]['login']).'</span> connexion depuis '.$connexions[$i]['IP'].' ('.bdd2html($connexions[$i]['dns']).')</li>';
      }
      unset($connexions);
    }
    krsort($polop);
    echo'
<ul>';
    foreach($polop AS $line){
      echo $line;
    }
    echo'
</ul>';
  }

 }
if(!empty($_REQUEST['mats'])){
  $accounts=explode(';',$_REQUEST['mats']);
  $sqlNames='';
  foreach($accounts AS $account){
    if(!empty($account) && is_numeric($account)){
      if(!empty($sqlNames)){
	$sqlNames.=' OR ';
      }
      $sqlNames.='ID='.$account;
    }
  }
  if(!empty($sqlNames)){
    $names=my_fetch_array('SELECT DISTINCT ID,compte FROM persos WHERE '.$sqlNames);
    echo'
<table class="liste">
 <tr>
  <th>ID</th>
  <th>Compte</th>
 </tr>';
    for($i=1;$i<=$names[0];$i++){
      echo'
 <tr>
  <td>'.$names[$i]['ID'].'</td>
  <td>'.bdd2html($names[$i]['compte']).'</td>
 </tr>';
    }
    echo'
</table>';
  }

 }
if(!empty($_POST['disable']) &&
   is_numeric($_POST['disable'])&&
   !empty($_POST['dday']) &&
   is_numeric($_POST['dday'])){
  request('UPDATE compte SET fin_vacances='.(time()+$_POST['dday']*86400).', motif_vacances="'.post2bdd($_POST['motif']).'" WHERE ID='.$_POST['disable']);
 }
if(!empty($_POST['cancel']) &&
   !empty($_POST['cancelID']) &&
   is_numeric($_POST['cancelID'])){
  request('UPDATE compte SET fin_vacances=0 WHERE ID='.$_POST['cancelID']);
 }


function sortByCount($a,$b){
  $ca=count($a['cookie'])+count($a['IP']);
  $cb=count($b['cookie'])+count($b['IP']);
  if($ca == $cb){
    return 0;
  }
  return ($ca<$cb)?1:-1;
}
?>

<form method="post" action="anim.php?admin_switch">
  <p>
  <label for="days">Tous les switchs sur :
  <input type="text" name="days" id="days" size="3"/> jours
  </label>
  <input type="submit" value="voir" />
</p>
</form>
<form method="post" action="anim.php?admin_switch">
<p>
  <label for="accounts">Toutes les infos sur les comptes (numeros separes par des ;):
  <input type="text" name="accounts" id="accounts"/>
  </label>
  <label for="days2">sur :
  <input type="text" name="days2" id="days2" size="3"/> jours
  </label>
  <input type="submit" value="voir" />
</p>
</form>
<form method="post" action="anim.php?admin_switch">
<p>
  <label for="mats">Numeros des comptes utilisant les persos matricules (separes par des ;):
  <input type="text" name="mats" id="mats"/>
  </label>
  <input type="submit" value="voir" />
</p>
</form>
<form method="post" action="anim.php?admin_switch">
<p>
  <label for="disable">D&eacute;sactiver le compte :
  <input type="text" name="disable" id="disable" size="4" />
  </label>
  <label for="dday">pour :
  <input type="text" name="dday" id="dday" size="4" /> jours
  </label><br />
  <label for="motif">Motif :
  <textarea name="motif" id="motif"/></textarea>
  </label>
  <input type="submit" value="Ok" />
</p>
</form>
<?php
$disabled=my_fetch_array('SELECT ID,login,fin_vacances,motif_vacances FROM compte WHERE fin_vacances>'.time());
echo'
<h3>Liste des comptes suspendus</h3>
<table>
 <tr>
  <th>ID</th>
  <th>Login</th>
  <th>Fin le</th>
  <th>Annuler</th>
  <th>Motif</th>
 </tr>';
for($i=1;$i<=$disabled[0];$i++){
  echo'
 <tr>
  <td>'.$disabled[$i]['ID'].'</td>
  <td>'.bdd2html($disabled[$i]['login']).'</td>
  <td>'.date('d/m/Y-G:i:s',$disabled[$i]['fin_vacances']).'</td>
  <td>
   <form method="post" action="">
    <input type="hidden" name="cancelID" value="'.$disabled[$i]['ID'].'" />
    <input type="submit" name="cancel" value="Annuler" />
   </form>
  </td>
  <td>'.bdd2html($disabled[$i]['motif_vacances']).'</td>
 </tr>';
 }
echo'
</table>';
?>
