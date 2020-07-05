<?php
ob_start('ob_gzhandler');
// Inclusion du fichier contenant les fonctions et variables globales.
require_once('../sources/globals.php');
require_once('../sources/includes.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

com_header();
// print_messages();
if(!isset($_SESSION['com_ID']) && 
   isset($_GET['act']) && 
   $_GET['act'] == 'login'){
  echo'  <div class="account">
   <h2>Connexion</h2>
   <form method="post" action="index.php">
   <p>
    <label for="login">Login :</label>
    <input type="text" id="login" name="login" title="login" />
    <label for="pass">Mot de passe :</label>
    <input type="password" id="pass" name="pass" title="mot de passe" />
    <input type="submit" id="loginOk" value="Ok" />
   </p>
   </form>
   <p>Avertissement : il semble qu\'internet explorer plante lorsque vous vous connectez. Tant que ce bug n\'est pas 
corrig&eacute;, je vous conseille d\'utiliser un autre navigateur tel que <a href="http://www.opera.com/download/">Opera</a> ou <a href="http://www.mozilla-europe.org/fr/">Mozilla Firefox</a>.
   </p>';
  /*   <h2>R&eacute;cup&eacute;ration de mot de passe</h2>
';
  if(isset($_GET['id'],$_GET['key'],$_GET['conf']) &&
     is_numeric($_GET['id'])){
    require('objets/bdd.php');
    $connexion = new comBdd('','','','','',$GLOBALS['db']);
    $sql = 'UPDATE compte SET pass=newpass, newpass="" WHERE ID='.$_GET['id'].' AND pass="'.post2bdd($_GET['key']).'" AND newpass="'.post2bdd($_GET['conf']).'" LIMIT 1';
    if($connexion->request($sql,'update')){
      echo'<p>Votre mot de passe a bien &eacute;t&eacute; chang&eacute;</p>
';
    }
    else{
      echo'<p>Erreur de lien ou SQL.</p>
';
    }
  }
  else if(!empty($_POST['mail']) && !empty($_POST['log'])){
    require('objets/bdd.php');
    $connexion = new comBdd('','','','','',$GLOBALS['db']);
    $sql = 'SELECT ID, login, pass FROM compte WHERE mail="'.post2bdd($_POST['mail']).'" AND login="'.post2bdd($_POST['log']).'"';
    $compte=$connexion->fetch($sql);
    if(!empty($compte)){
      $pass=substr(md5(crypt(rand())),0,8);
      $sql='UPDATE compte SET newpass="'.md5($pass).'" WHERE ID='.$compte[0]['ID'];
      if($connexion->request($sql,'update')){
	$title = 'Changement de mot de passe.';
	$to = $_POST['mail']; // Il faudra filtrer cela.
	$message = "Bonjour,\r\n".
	  "il semble que vous ayez demandé la génération d'un nouveau mot de passe. Pour l'activer, veuillez cliquer sur ce lien :\r\n".
	  $GLOBALS['root_url'].'index.php?act=login&id='.$compte[0]['ID'].'&key='.$compte[0]['pass'].'&conf='.md5($pass)."\r\n".
	  'Votre nouveau motde passe sera : '.$pass."\r\n";
	if(!mail($to,$title,$message)){
	  echo'<p>Erreur d\'envoi du mail de confirmation.</p>
';
	}
	else{
	  echo'<p>Un mail de confirmation vient de vous &ecirc;tre envoy&eacute;.</p>
';
	}
      }
      else{
	echo '<p>Erreur SQL lors de la g&eacute;n&eacute;ration du password.</p>
';
      }
    }
    else{
      echo '   <p>Couple login/e-mail inconnu.</p>
';
    }
  }
  echo'   <p>Un mail contenant un nouveau mot de passe vous sera envoy&eacute;. Ce mot de passe ne sera actif que lorsque vous l\'aurez valid&eacute; en suivant le lien donn&eacute; dans le mail.
   </p>
   <form method="post" action="index.php?act=login">
   <p>
    <label for="mail">Votre e-mail :</label>
    <input type="text" id="mail" name="mail" />
    <label for="log">Votre login :</label>
    <input type="text" id="log" name="log" />
    <input type="submit" value="Ok" />
   </p>
   </form>
  */
  echo'</div>
';
}
else if(isset($_SESSION['com_ID']) || 
	isset($_GET['act']) && 
	$_GET['act'] == 'news'){
  require('../objets/bdd.php');
  if(!isset($_SESSION['com_lastnews'])){
    $_SESSION['com_lastnews']=time();
  }
  $connexion = new comBdd('','','','','',$GLOBALS['db']);
  $active = 'anim';
  if(!empty($_COOKIE['game'])){
    $active = 'anim';
  }
  if(!empty($_COOKIE['event'])){
    $active = 'anim';
  }
  if(!empty($_COOKIE['text'])){
    $active = 'text';
  }
  if(!empty($_COOKIE['map'])){
    $active = 'map';
  }
  if(!empty($_COOKIE['irl'])){
    $active = 'irl';
  }
  $forcer=empty($_COOKIE['game'])&&empty($_COOKIE['event'])&&empty($_COOKIE['text'])&&empty($_COOKIE['ordres']);

  $camp=0;
  if(isset($_SESSION['com_perso'])){
    $sql='SELECT armee FROM persos WHERE ID='.$_SESSION['com_perso'];
    $plop=$connexion->fetch($sql);
    if(!empty($plop)){
      $camp=$plop[0]['armee'];
    }
  }else{
    $camp=0;
  }

  $new=array(false,false,false,false,false);
  $anim=$connexion->fetch('SELECT `date` FROM news
WHERE news.visibilite=0
AND (news.camp=0
OR news.camp='.$camp.')
ORDER BY `date` DESC
LIMIT 1');
  if(!empty($anim[0]) &&
     file_exists('news/anim_'.$camp.'.html') &&
     isset($_SESSION['com_lastnews']) &&
     $anim[0]['date']>$_SESSION['com_lastnews']){
    $new[0]=true;
  }
  $anim=$connexion->fetch('SELECT `date` FROM news
WHERE news.visibilite=1
AND (news.camp=0
OR news.camp='.$camp.')
ORDER BY `date` DESC
LIMIT 1');
  if(!empty($anim[0]) &&
     file_exists('news/jeu_'.$camp.'.html')&&
     $anim[0]['date']>$_SESSION['com_lastnews']){
    $new[1]=true;
  }
  $frag=$connexion->fetch('SELECT events.date
FROM events
WHERE events.mort=1 AND events.type=1
ORDER BY date DESC
 LIMIT 1');
  if(!empty($frag[0]) &&
     $frag[0]['date']>$_SESSION['com_lastnews']){
    $new[2]=true;
  }
  if(getLastTextTime()>$_SESSION['com_lastnews']){
    $new[3]=true;
  }
  if(!empty($_SESSION['com_perso'])){
    $dateordres=0;
    include('../sources/monperso.php');
    if($perso['ID_compa']!=1 &&
       file_exists('../ordres/compa_'.$perso['ID_compa'].'.html')){
      $dateordres=filemtime('../ordres/compa_'.$perso['ID_compa'].'.html');
    }
    if($perso['ordres'] &&
       file_exists('../ordres/gene_camp_'.$perso['armee'].'.html')){
      $dateordres=max($dateordres,filemtime('../ordres/gene_camp_'.$perso['armee'].'.html'));
    }
    if($dateordres>$perso['lastordres']){
      $new[4]=true;
    }
  }

  echo'   <ul id="menuHaut">
'.showMenuItem('anim','Animation',$forcer,$new[0]).'
'.showMenuItem('game','Jeu',false,$new[1]);
  if(!empty($_SESSION['com_perso'])){
    echo showMenuItem('ordres','Ordres',false,$new[4]);
  }
  echo showMenuItem('text','Chroniques',$new[3]).'
 '.showMenuItem('event','&Eacute;v&egrave;nements',false,$new[2]).'
   </ul>
   <div class="framed" id="animFrame">
';
  echo(file_exists('../news/anim_'.$camp.'.html')?file_get_contents('../news/anim_'.$camp.'.html'):'');
  echo'   </div>
   <div class="framed" id="gameFrame">
';
  echo(file_exists('../news/jeu_'.$camp.'.html')?file_get_contents('../news/jeu_'.$camp.'.html'):'');
  echo'   </div>
';
  if(!empty($_SESSION['com_perso'])){
    echo'   <div class="framed" id="ordresFrame">
';
    if($perso['ID_compa']!=1 &&
       file_exists('../ordres/compa_'.$perso['ID_compa'].'.html')){
      echo'    <h3>Ordres de compagnie</h3>
'.@file_get_contents('../ordres/compa_'.$perso['ID_compa'].'.html');
    }
    if($perso['ordres'] &&
       file_exists('../ordres/gene_camp_'.$perso['armee'].'.html')){
      echo'    <h3>Ordres de camp</h3>
'.@file_get_contents('../ordres/gene_camp_'.$perso['armee'].'.html');;
    }
    echo'</div>
';
  }
  echo'   <div class="framed" id="textFrame">
';
  lastTextList(3);
  echo'   </div>
   <div class="framed" id="eventFrame">
';
  $sql='SELECT events.date,
tireur.nom AS tireur_nom,
tireur.ID AS tireur_ID,
tireur.armee AS tireur_armee,
compagnie1.initiales AS tireur_compa,
cible.nom AS cible_nom,
cible.ID AS cible_ID,
cible.armee AS cible_armee,
compagnie2.initiales AS cible_compa
FROM events
INNER JOIN persos AS tireur
ON tireur.ID = events.tireur
INNER JOIN compagnies AS compagnie1
ON compagnie1.ID = tireur.compagnie 
INNER JOIN persos AS cible
ON cible.ID = events.cible
INNER JOIN compagnies AS compagnie2
ON compagnie2.ID = cible.compagnie 
WHERE events.mort=1 AND events.type=1
ORDER BY date DESC
LIMIT 20
';
  $frags=$connexion->fetch($sql);
  if(!empty($frags)){
    echo'    <h3>Derniers frags</h3>
    <ul>
';
    foreach($frags AS $frag){
      $date=date("d/m/Y - H:i",$frag['date']);
      $cible=bdd2html($frag['cible_nom']).' (<a href="evenements.php?id='.$frag['cible_ID'].'">'.camp_initiale($frag['cible_armee']).'-'.bdd2html($frag['cible_compa']).'-'.$frag['cible_ID'].'</a>)';
      $tireur=bdd2html($frag['tireur_nom']).' (<a href="evenements.php?id='.$frag['tireur_ID'].'">'.camp_initiale($frag['tireur_armee']).'-'.bdd2html($frag['tireur_compa']).'-'.$frag['tireur_ID'].'</a>)';
      echo'     <li>'.$date.' : '.$tireur.' a tu&eacute; '.$cible.'.</li>
';
    }
    echo'    </ul>
';
  }
  echo'   </div>
';
  if(!empty($_SESSION['com_ID'])){
    $_SESSION['com_lastnews']=time();
    $sql='UPDATE compte SET lastnews='.time().' WHERE ID='.$_SESSION['com_ID'];
    $connexion->request($sql);
    if(!empty($_SESSION['com_perso'])){
      $sql='UPDATE persos SET lastordres='.time().' WHERE ID='.$_SESSION['com_perso'];
      $connexion->request($sql);
    }
  }
}
else{
  echo(@file_get_contents('presentation.html'));
}
com_footer();
?>
