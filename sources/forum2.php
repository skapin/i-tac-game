<?php
/*
 Fichier definissant les interfaces jeu <=> forum
 renvoient false si tout s'est bien passe, une chaine de caractere sinon.
*/
  /*
// On recup plein de trucs de SMF. YouplaBoom.

define('SMF', 1);
@set_magic_quotes_runtime(0);
error_reporting(E_ALL);
require('../site/forum/Settings.php');
$sourcedir='../site/forum/Sources';
require_once($sourcedir . '/QueryString.php');
require_once($sourcedir . '/Subs.php');
require_once($sourcedir . '/Errors.php');
require_once($sourcedir . '/Load.php');
require_once($sourcedir . '/Security.php');
require('../site/forum/Sources/Subs-Boards.php');
// Connect to the MySQL database.
if (empty($db_persist))
  $db_connection = @mysql_connect($db_server, $db_user, $db_passwd);
else
  $db_connection = @mysql_pconnect($db_server, $db_user, $db_passwd);

// Show an error if the connection couldn't be made.
if (!$db_connection || !@mysql_select_db($db_name, $db_connection))
  db_fatal_error();

// Load the settings from the settings table, and perform operations like optimizing.
reloadSettings();
// Clean the request variables, add slashes, etc.
cleanRequest();
$context = array();

// Fin des trucs copies.
*/

/*
 Note sur les groupes forum :
I) Droits principaux :
 - Administrator = acces a tout. Peut etre donne dans la console d'admin du jeu
 - membre = acces aux forums publics
 - diplomate = acces aux forums publics + forums intercamps
 - invite = voit les forums publics (pas de possibilite de poster)
II) Droits supplementaires :
 - compa_Y = acces aux forum RP et HRP de sa compagnie
 - camp_X = acces aux forums rp et hrp de son camp
 - em_X = acces au forum EM de son camp
 - gene_X = acces au forum des generaux de son camp
 */

$idRP=1;
$idStrat=2;

$prefixes=array('compaRP'=>'',
		'compaHRP'=>'Groupe : ',
		'campRP'=>'',
		'campHRP'=>'Camp : ',
		'em'=>'État-major du camp : ',
		'gene'=>'Généraux du camp : ');

// forumNewCamp
// TODO : voir s'il est possible de passer uniquement par les fonctions de SMF sans avoir un 
// truc trop ignoble.
/* Cree les forums HRP, RP, colos et generaux du camp ainsi que les groupes associes
 * $id : id du camp
 * $nom : nom du camp, doit etre filtre
 * $color : couleur du camp
 */
function forumNewCamp($id,$nom,$color){
  global $idRP,$idStrat,$prefixes;
  select_table(1);
  /*
   Creation des groupes
   */
  // Soldat du camp
  request('INSERT INTO smf_membergroups (
groupName,
onlineColor,
stars)
VALUES(
"camp_'.$id.'",
"#'.$color.'",
"1#star.gif")');
  $idGroupCamp = last_id();
  if($idGroupCamp <= 0){
    // TODO : ajouter retour en arriere
    select_table(0);
    return 'Impossible de creer le groupe soldat.';
  }
  // Colonels
  request('INSERT INTO smf_membergroups (
groupName,
onlineColor,
stars)
VALUES(
"em_'.$id.'",
"#'.$color.'",
"2#star.gif")');
  $idGroupEm = last_id();
  if($idGroupEm <= 0){
    // TODO : ajouter retour en arriere
    select_table(0);
    return 'Impossible de creer le groupe EM.';
  }
  // Generaux
  request('INSERT INTO smf_membergroups (
groupName,
onlineColor,
stars)
VALUES(
"gene_'.$id.'",
"#'.$color.'",
"3#star.gif")');
  $idGroupGene = last_id();
  if($idGroupGene <= 0){
    // TODO : ajouter retour en arriere
    select_table(0);
    return 'Impossible de creer le groupe genes.';
  }
  /* 
   Creation des forums
   */
  // On choppe le plus gros boardOrder de la categorie RP
  $order=my_fetch_array('SELECT MAX(boardOrder) AS maxOrder FROM smf_boards WHERE ID_compa=0 AND ID_CAT='.$idRP);
  if(!empty($order[0])){
    $order=$order[1]['maxOrder']+1;
  }
  else{
    $order=2;
  }

  // Creation du forum RP
  request('INSERT INTO smf_boards (
ID_CAT,
ID_CAMP,
boardOrder,
memberGroups,
name,
`description`)
VALUES(
'.$idRP.',
'.$id.',
'.$order.',
"'.$idGroupCamp.'",
"'.$prefixes['campRP'].$nom.'",
"")');
  $idForumRP=last_id();
  if($idForumRP <= 0){
    // TODO : ajouter retour en arriere
    select_table(0);
    return 'Impossible de creer le forum RP du camp.';
  }

  // On choppe le plus gros boardOrder de la categorie Strategie
  $order=my_fetch_array('SELECT MAX(boardOrder) AS maxOrder FROM smf_boards WHERE ID_compa=0 AND ID_CAT='.$idStrat);
  if(!empty($order[0])){
    $order=$order[1]['maxOrder']+1;
  }
  else{
    $order=2;
  }
  // Creation du forum HRP
  request('INSERT INTO smf_boards (
ID_CAT,
ID_CAMP,
boardOrder,
memberGroups,
name,
`description`)
VALUES(
'.$idStrat.',
'.$id.',
'.($order+2).',
"'.$idGroupCamp.'",
"'.$prefixes['campHRP'].$nom.'",
"")');
  $idForumHRP=last_id();
  if($idForumHRP <= 0){
    // TODO : ajouter retour en arriere sur la bdd
    select_table(0);
    return 'Impossible de creer le forum HRP du camp.';
  }
  // Creation du forum EM
  request('INSERT INTO smf_boards (
ID_CAT,
ID_CAMP,
boardOrder,
memberGroups,
name,
`description`)
VALUES(
'.$idStrat.',
'.$id.',
'.($order+1).',
"'.$idGroupEm.'",
"'.$prefixes['em'].$nom.'",
"")');
  $idForumEm=last_id();
  if($idForumEm <= 0){
    // TODO : ajouter retour en arriere sur la bdd
    select_table(0);
    return 'Impossible de creer le forum EM du camp.';
  }
  // Creation du forum generaux
  request('INSERT INTO smf_boards (
ID_CAT,
ID_CAMP,
boardOrder,
memberGroups,
name,
`description`)
VALUES(
'.$idStrat.',
'.$id.',
'.$order.',
"'.$idGroupGene.'",
"'.$prefixes['gene'].$nom.'",
"")');
  $idForumGene=last_id();
  if($idForumGene <= 0){
    // TODO : ajouter retour en arriere sur la bdd
    select_table(0);
    return 'Impossible de creer le forum generaux du camp.';
  }
  orderBoards();
  select_table(0);
  return false;
}

// forumNewAccount
/* Permet de creer les differents comptes des joueurs
 * $login : login du compte, doit avoir ete filtre
 * $pass : mot de passe du compte
 * $idCount : id du compte jeu auquel appartient ce compte forum
 * $mat : matricule du perso auquel appartient ce compte, mis a 0 si le compte correspond seulement a un
 *        compte jeu et nom a un compte perso
 * $compa : compagnie a laquelle appartient le perso
 * $camp : camp auquel appartient le compte forum
 */
function forumNewAccount($login,$pass,$idCount,$mat,$compa,$camp){
  select_table(1);
  /*
   Recuperation des id des groupes
   */
  $idJoueur = my_fetch_array('SELECT ID_GROUP FROM smf_membergroups WHERE groupName="Personnage" LIMIT 1');
  if($idJoueur[0] == 0){
    select_table(0);
    return 'Impossible de recuperer le groupe Personnage.';
  }
  $idJoueur=$idJoueur[1]['ID_GROUP'];
  $idCompte = my_fetch_array('SELECT ID_GROUP FROM smf_membergroups WHERE groupName="Compte" LIMIT 1');
  if($idCompte[0] == 0){
    select_table(0);
    return 'Impossible de recuperer le groupe Compte.';
  }
  $idCompte=$idCompte[1]['ID_GROUP'];
  if($mat){
    $idCamp = my_fetch_array('SELECT ID_GROUP FROM smf_membergroups WHERE groupName="camp_'.$camp.'" LIMIT 1');
    if($idCamp[0] == 0){
      select_table(0);
      return 'Impossible de recuperer le groupe du camp.';
    }
    $idCamp=$idCamp[1]['ID_GROUP'];
    if($compa>1){
      $idCompa = my_fetch_array('SELECT ID_GROUP FROM smf_membergroups WHERE groupName="compa_'.$compa.'" LIMIT 1');
      if($idCompa[0] == 0){
	select_table(0);
	return 'Impossible de recuperer le groupe de la compagnie.';
      }
      $idCompa=$idCompa[1]['ID_GROUP'];
    }
  }
  
  /*
   Creation du compte
   */
  request('INSERT INTO smf_members(
memberName,
realName,
ID_compte,
ID_perso,
ID_camp,
ID_compa,
dateRegistered,
ID_GROUP,
ID_POST_GROUP,
 passwd,
is_activated,
additionalGroups,
passwordSalt)
VALUE(
 "'.htmlspecialchars($login,ENT_QUOTES).'",
 "'.htmlspecialchars($login,ENT_QUOTES).'",
'.$idCount.',
'.$mat.',
'.$camp.',
'.$compa.',
'.time().',
 '.(empty($mat)?$idCompte:$idJoueur).',
4,
"'.sha1(strtolower($login).$pass).'",
1,
"'.($mat?$idCamp.($compa>1?','.$idCompa:''):'').'",
"'.substr(md5(rand()), 0, 4).'")');
  select_table(0);
  if(last_id() <= 0){
    return 'Compte forum non cree';
  }
  return false;
}

// forumModAccount
/* Permet de modifier les infos d'un compte
 * $id : login de l'utilisateur
 * $isPerso : true si compte associe a un perso, false si associe a un compte jeu
 * $modifs : tableau contenant les modification
 */
function forumModAccount($id,$isPerso,$modifs){
  select_table(1);
  global $prefixes;
  // Recuperation de l'id du compte forum
  if($isPerso){
    $id=my_fetch_array('SELECT ID_MEMBER FROM smf_members WHERE ID_perso='.$id.' LIMIT 1');
  }
  else{
    $id=my_fetch_array('SELECT ID_MEMBER FROM smf_members WHERE ID_compte='.$id.' LIMIT 1');
  }

  if(empty($id[0])){
    select_table(0);
    return 'Compte forum inconnu.';
  }
  $id=$id[1]['ID_MEMBER'];

  $sql='';
  $aGroups='';

  if(!empty($modifs['login'])){
    $sql.='memberName="'.$modifs['login'].'",realName="'.$modifs['login'].'",';
    if(!empty($modifs['pass'])){
      $sql.='passwd="'.$modifs['pass'].'",';
    }
  }

  // ITAC - LD - 25-01-2010
  // ITAC - LD - BEGIN
  // Suppression de tous les droits!
  // Suite a ca on peut maj correctement.
  request('DELETE FROM smf_moderators WHERE ID_MEMBER='.$id);
  // ITAC - LD - END
  
  
  // Recuperation des groupes additionnels et ajout en modo quand il faut
  if(!empty($modifs['compa']['ID'])){
    $sql.='ID_compa='.$modifs['compa']['ID'].',';
    if(!empty($modifs['compa']['forum'])){
      // Acces aux forums de la compagnie
      $idGroupCompa=my_fetch_array('SELECT ID_GROUP FROM smf_membergroups WHERE groupName="compa_'.$modifs['compa']['ID'].'" LIMIT 1');
      if(!empty($idGroupCompa[0])){
	$aGroups.=$idGroupCompa[1]['ID_GROUP'];
      }

      // Recup de l'id du forum RP de la compagnie
      $idForum=my_fetch_array('SELECT ID_BOARD FROM smf_boards WHERE name="'.$prefixes['compaRP'].$modifs['compa']['name'].'" LIMIT 1');
      if(!empty($idForum[0])){
	// Suppression de la moderation du forum
	request('DELETE FROM smf_moderators WHERE ID_MEMBER='.$id.' AND ID_BOARD='.$idForum[1]['ID_BOARD']);
	if(!empty($modifs['compa']['modoforum'])){
	  request('INSERT INTO smf_moderators (ID_MEMBER,ID_BOARD) VALUES('.$id.','.$idForum[1]['ID_BOARD'].')');
	}
      }
      // Recup de l'id du forum HRP de la compagnie
      $idForum=my_fetch_array('SELECT ID_BOARD FROM smf_boards WHERE name="'.$prefixes['compaHRP'].$modifs['compa']['name'].'" LIMIT 1');
      if(!empty($idForum[0])){
	// Suppression de la moderation du forum
	request('DELETE FROM smf_moderators WHERE ID_MEMBER='.$id.' AND ID_BOARD='.$idForum[1]['ID_BOARD']);
	if(!empty($modifs['compa']['modoforum'])){
	  request('INSERT INTO smf_moderators (ID_MEMBER,ID_BOARD) VALUES('.$id.','.$idForum[1]['ID_BOARD'].')');
	}
      }
    }
  }
  if(!empty($modifs['camp']['ID'])){
    $sql.='ID_camp='.$modifs['camp']['ID'].',';
    if(!empty($modifs['camp']['forum'])){
      // Forum de camp
      $idGroupCamp=my_fetch_array('SELECT ID_GROUP FROM smf_membergroups WHERE groupName="camp_'.$modifs['camp']['ID'].'" LIMIT 1');
      if(!empty($idGroupCamp[0])){
	  if(!empty($aGroups)){
	    $aGroups.=',';
	  }
	$aGroups.=$idGroupCamp[1]['ID_GROUP'];
      }

      // Recup de l'id du forum HRP du camp
      $idForum=my_fetch_array('SELECT ID_BOARD FROM smf_boards WHERE name="'.$prefixes['campHRP'].$modifs['camp']['name'].'" LIMIT 1');
      if(!empty($idForum[0])){
	// Suppression de la moderation du forum
	request('DELETE FROM smf_moderators WHERE ID_MEMBER='.$id.' AND ID_BOARD='.$idForum[1]['ID_BOARD']);
	if(!empty($modifs['camp']['modoforum'])){
	  request('INSERT INTO smf_moderators (ID_MEMBER,ID_BOARD) VALUES('.$id.','.$idForum[1]['ID_BOARD'].')');
	}
      }
    }

    if(!empty($modifs['camp']['em'])){
      // Forum EM
      $idGroupEm=my_fetch_array('SELECT ID_GROUP FROM smf_membergroups WHERE groupName="em_'.$modifs['camp']['ID'].'" LIMIT 1');
      if(!empty($idGroupEm[0])){
	  if(!empty($aGroups)){
	    $aGroups.=',';
	  }
	$aGroups.=$idGroupEm[1]['ID_GROUP'];
      }
      // Recup de l'id du forum EM du camp
      $idForum=my_fetch_array('SELECT ID_BOARD FROM smf_boards WHERE name="'.$prefixes['em'].$modifs['camp']['name'].'" LIMIT 1');
      if(!empty($idForum[0])){
	// Suppression de la moderation du forum
	request('DELETE FROM smf_moderators WHERE ID_MEMBER='.$id.' AND ID_BOARD='.$idForum[1]['ID_BOARD']);
	if(!empty($modifs['camp']['modoem'])){
	  request('INSERT INTO smf_moderators (ID_MEMBER,ID_BOARD) VALUES('.$id.','.$idForum[1]['ID_BOARD'].')');
	}
      }
    }
    if(!empty($modifs['camp']['gene'])){
      // Forum des generaux
      $idGroupGene=my_fetch_array('SELECT ID_GROUP FROM smf_membergroups WHERE groupName="gene_'.$modifs['camp']['ID'].'" LIMIT 1');
      if(!empty($idGroupGene[0])){
	  if(!empty($aGroups)){
	    $aGroups.=',';
	  }
	$aGroups.=$idGroupGene[1]['ID_GROUP'];
      }
      // Recup de l'id du forum des generaux du camp
      $idForum=my_fetch_array('SELECT ID_BOARD FROM smf_boards WHERE name="'.$prefixes['gene'].$modifs['camp']['name'].'" LIMIT 1');
      if(!empty($idForum[0])){
	// Suppression de la moderation du forum
	request('DELETE FROM smf_moderators WHERE ID_MEMBER='.$id.' AND ID_BOARD='.$idForum[1]['ID_BOARD']);
	if(!empty($modifs['camp']['modogene'])){
	  request('INSERT INTO smf_moderators (ID_MEMBER,ID_BOARD) VALUES('.$id.','.$idForum[1]['ID_BOARD'].')');
	}
      }
    }
  }
  $sql.='additionalGroups="'.$aGroups.'",';

  // nom du perso et compte
  if(!empty($modifs['perso']['nom'])){
    $nom=htmlspecialchars($modifs['perso']['nom'],ENT_QUOTES);
    $sql.='memberName="'.$nom.'", realName="'.$nom.'",';
  }
  if(isset($modifs['perso']['compte'])){
    $sql.='ID_compte='.$modifs['perso']['compte'].',';
  }

  // Puis le groupe primaire (joueur,diplomate ou admin)
  if(!empty($modifs['admin'])){
    $sql.='ID_GROUP=1';
  }
  else if(!empty($modifs['diplomate'])){
    $group=my_fetch_array('SELECT ID_GROUP FROM smf_membergroups WHERE groupName="Diplomate" LIMIT 1');
    if(!empty($group[0])){
      $sql.='ID_GROUP='.$group[1]['ID_GROUP'];
    }
    else{
      $sql.='ID_GROUP=""';
    }
  }
  else if($isPerso){
    $group=my_fetch_array('SELECT ID_GROUP FROM smf_membergroups WHERE groupName="Personnage" LIMIT 1');
    if(!empty($group[0])){
      $sql.='ID_GROUP='.$group[1]['ID_GROUP'];
    }
    else{
      $sql.='ID_GROUP=""';
    }
  }
  else{
    $group=my_fetch_array('SELECT ID_GROUP FROM smf_membergroups WHERE groupName="Compte" LIMIT 1');
    if(!empty($group[0])){
      $sql.='ID_GROUP='.$group[1]['ID_GROUP'];
    }
    else{
      $sql.='ID_GROUP=""';
    }
  }

  request('UPDATE smf_members SET '.$sql.' WHERE ID_MEMBER='.$id.' LIMIT 1');
  select_table(0);
}

// forumNewCompa
/* Permet de creer les forums RP et HRP d'une compagnie
 * $id : id de la compagnie
 * $nom : nom de la compagnie
 */
function forumNewCompa($id,$nom){
  global $idRP,$idStrat,$prefixes;
  select_table(1);
  /*
   Creation des groupes
   */
  // Membres de la compagnie
  request('INSERT INTO smf_membergroups (
groupName,
stars)
VALUES(
"compa_'.$id.'",
"1#star.gif")');
  $idGroupCompa = last_id();
  if($idGroupCompa <= 0){
    // TODO : ajouter retour en arriere
    select_table(0);
    return 'Impossible de creer le groupe de la compagnie.';
  }
  /*
   Creation des forums
   */
  // Creation du forum RP
  // On choppe le plus gros boardOrder de la categorie RP
  $order=my_fetch_array('SELECT MAX(boardOrder) AS maxOrder FROM smf_boards WHERE ID_CAT='.$idRP);
  if(!empty($order[0])){
    $order=$order[1]['maxOrder']+1;
  }
  else{
    $order=2;
  }

  request('INSERT INTO smf_boards (
ID_CAT,
ID_COMPA,
boardOrder,
memberGroups,
name,
`description`)
VALUES(
'.$idRP.',
'.$id.',
'.$order.',
"'.$idGroupCompa.'",
"'.$prefixes['compaRP'].$nom.'",
"")');
  $idForumRP=last_id();
  if($idForumRP <= 0){
    // TODO : ajouter retour en arriere
    select_table(0);
    return 'Impossible de creer le forum RP du groupe.';
  }
  // On choppe le plus gros boardOrder de la categorie Strategie
  $order=my_fetch_array('SELECT MAX(boardOrder) AS maxOrder FROM smf_boards WHERE ID_CAT='.$idStrat);
  if(!empty($order[0])){
    $order=$order[1]['maxOrder']+1;
  }
  else{
    $order=1;
  }
  // Creation du forum HRP
  request('INSERT INTO smf_boards (
ID_CAT,
ID_COMPA,
boardOrder,
memberGroups,
name,
`description`)
VALUES(
'.$idStrat.',
'.$id.',
'.$order.',
"'.$idGroupCompa.'",
"'.$prefixes['compaHRP'].$nom.'",
"")');
  $idForumHRP=last_id();
  if($idForumHRP <= 0){
    // TODO : ajouter retour en arriere sur la bdd
    select_table(0);
    return 'Impossible de creer le forum HRP du groupe.';
  }
  orderBoards();
  select_table(0);
  return false;
}

// forumDelCompa
/* Utilisee pour virer une compagnie du forum.
 * $id : id de la compagnie dans la bdd de jeu.
 */
function forumDelCompa($id){
  select_table(1);
  /*
   Suppressions de tout ce qui est associe a cette compagnie
   */
  // Groupe des membres de la compagnie
  request('DELETE FROM smf_membergroups WHERE groupName="compa_'.$id.'"');

  // Les forums
  $idForum = my_fetch_array('SELECT ID_BOARD FROM smf_boards WHERE ID_COMPA='.$id);
  for($i=1;$i<=$idForum[0];$i++){
    // Modos des forums
    request('DELETE FROM smf_moderators WHERE ID_BOARD='.$idForum[$i]['ID_BOARD']);
  }
  request('DELETE FROM smf_boards WHERE ID_COMPA='.$id);
  select_table(0);
  return false;
}



function orderBoards(){
  global $idRP,$idStrat;
  
  $goodOrder=array();
  // Categorie RP
  // Les trucs de base qui trainent en haut
  $groupes=my_fetch_array('SELECT ID_BOARD FROM smf_boards WHERE ID_CAT='.$idRP.' AND ID_compa=0 AND ID_camp=0 ORDER BY boardOrder');
  for($i=1;$i<=$groupes[0];$i++){
    $goodOrder[]=$groupes[$i]['ID_BOARD'];
  }
  // Les forums de camp
  $groupes=my_fetch_array('SELECT ID_BOARD FROM smf_boards WHERE ID_CAT='.$idRP.' AND ID_compa=0 AND ID_camp!=0 ORDER BY boardOrder');
  for($i=1;$i<=$groupes[0];$i++){
    $goodOrder[]=$groupes[$i]['ID_BOARD'];
  }
  // Les forums de compagnies
  $groupes=my_fetch_array('SELECT ID_BOARD FROM smf_boards WHERE ID_CAT='.$idRP.' AND ID_compa!=0 AND ID_camp=0 ORDER BY boardOrder');
  for($i=1;$i<=$groupes[0];$i++){
    $goodOrder[]=$groupes[$i]['ID_BOARD'];
  }

  // Categorie Strategie
  // Les trucs de base qui trainent en haut
  $groupes=my_fetch_array('SELECT ID_BOARD FROM smf_boards WHERE ID_CAT='.$idStrat.' AND ID_compa=0 AND ID_camp=0 ORDER BY boardOrder');
  for($i=1;$i<=$groupes[0];$i++){
    $goodOrder[]=$groupes[$i]['ID_BOARD'];
  }
  // Les forums de camp
  $groupes=my_fetch_array('SELECT ID_BOARD FROM smf_boards WHERE ID_CAT='.$idStrat.' AND ID_compa=0 AND ID_camp!=0 ORDER BY boardOrder');
  for($i=1;$i<=$groupes[0];$i++){
    $goodOrder[]=$groupes[$i]['ID_BOARD'];
  }
  // Les forums de compagnies
  $groupes=my_fetch_array('SELECT ID_BOARD FROM smf_boards WHERE ID_CAT='.$idStrat.' AND ID_compa!=0 AND ID_camp=0 ORDER BY boardOrder');
  for($i=1;$i<=$groupes[0];$i++){
    $goodOrder[]=$groupes[$i]['ID_BOARD'];
  }
  foreach($goodOrder AS $key=>$value){
    request('UPDATE smf_boards SET boardOrder='.$key.' WHERE ID_BOARD='.$value);
  }
  request('ALTER TABLE smf_boards ORDER BY boardOrder');
}

function forumLogin($perso=true){
  select_table(1);
  $cookie_name='SMFCookie52';
  if($perso){
    $where='ID_perso='.$_SESSION['com_perso'];
  }
  else{
    $where='ID_perso=0 AND ID_compte='.$_SESSION['com_ID'];
  }
  $account=my_fetch_array('SELECT ID_MEMBER,
ID_GROUP,
passwd,
passwordSalt,
additionalGroups
FROM smf_members
WHERE '.$where.' LIMIT 1');
  if(!empty($account[0])){
    $account[1]['additionalGroups']=explode(',',$account[1]['additionalGroups']);
    // Mise en place du cookie
    // Truc original pour l'etat :
    // $cookie_state = (empty($modSettings['localCookies']) ? 0 : 1) | (empty($modSettings['globalCookies']) ? 0 : 2);
    $state=0;
    $data=serialize(array($account[1]['ID_MEMBER'],
			  sha1($account[1]['passwd'].$account[1]['passwordSalt']),
			  time() + 86400,
			  $state));
    setcookie($cookie_name, $data, time() + 86400,'/');//,$cookie_url[1], $cookie_url[0], 0);
    $_SESSION['login_' . $cookie_name] = $data;
    //setLoginCookie(600,$account[1]['ID_MEMBER'],sha1($account[1]['passwd'].$account[1]['passwordSalt']));
    // Si on est admin on l'enregistre
    if ($account[1]['ID_GROUP'] == 1 || in_array(1,$account[1]['additionalGroups'])){
      $_SESSION['admin_time'] = time();
      unset($_SESSION['just_registered']);
    }
    // Update d'infos sur le compte
    request('UPDATE smf_members SET 
lastLogin='.time().',
memberIP="'.$_SERVER['REMOTE_ADDR'].'",
memberIP2=""
WHERE ID_MEMBER='.$account[1]['ID_MEMBER']);
    //'.$_SERVER['BAN_CHECK_IP'].'
    // On vire l'ancienne entree du guest dans la liste des actifs
    request("DELETE FROM smf_log_online
	     WHERE session = 'ip".$_SERVER['REMOTE_ADDR']."'
	     LIMIT 1");
    $_SESSION['log_time'] = 0;
  }
  select_table(0);
}

function forumLogout(){
  $cookie_name='SMFCookie52';
  setcookie($cookie_name,0,time()-3600);
}

function forumSacs($id){
  select_table(1);
  $nbr=my_fetch_array('SELECT COUNT(*)
FROM smf_pm_recipients
INNER JOIN smf_members
ON smf_pm_recipients.ID_MEMBER=smf_members.ID_MEMBER
WHERE smf_members.ID_perso='.$id.'
AND smf_pm_recipients.is_read=0');
  select_table(0);
  if($nbr[0]){
    return $nbr[1][0];
  }
  return 0;
}

?>
