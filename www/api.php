<?php
header('Content-Type: text/xml');
// Inclusion du fichier contenant les fonctions et variables globales.
define('API',true);
require_once('../sources/globals.php');
require_once('../sources/includes.php');
require('../objets/bdd.php');
error_reporting(E_ALL);
$userID=0;
$API_erreurMessages=array('noact'=>'Aucune interface specifiee.',
			  'badact'=>'Interface inconnue.',
			  'nopass'=>'Pas de mot de passe specifie.',
			  'badpass'=>'Login ou mot de passe faux.',
			  'notvalid'=>'Il faut un couple login/pass correct pour acceder a cette fonction.',
			  'nomat'=>'Pas de matricule et/ou de mot de passe specifie');
echo '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<reponse>';
// Connexion a la bdd
$db = new comBdd($GLOBALS['sql_host'],$GLOBALS['sql_user'],$GLOBALS['sql_pass'],$GLOBALS['sql_table']);
// Recuperation de l'id du compte
API_login();

if(empty($_REQUEST['act'])){
  API_erreur('noact');
}
else{
  switch($_REQUEST['act']){
  case 'getPersosInfos':
    include('../api/getPersosInfos.php');
    break;
  case 'verifPwd':
    include('../api/verifPwd.php');
    break;
  default:
    API_erreur('badact');
    break;
  }
}

echo'
</reponse>';

function API_erreur($msg){
  global $API_erreurMessages;
  echo'
 <erreur numero="'.$msg.'">'.$API_erreurMessages[$msg].'</erreur>';
}
function API_login(){
  global $userID,$db;
  if(!empty($_REQUEST['login'])){
    if(empty($_REQUEST['pass'])){
      API_erreur('nopass');
    }
    else{
      $account=$db->fetch('SELECT ID,pass FROM compte WHERE login="'.post2bdd($_REQUEST['login']).'" LIMIT 1');
      if(!empty($account[0]) &&
	 (!empty($_REQUEST['encrypted']) &&
	  $account[0]['pass'] == $_REQUEST['pass']) ||
	 ($account[0]['pass'] == sha1(strtolower(post2text($_REQUEST['login'])).$_REQUEST['pass']))){
	$userID = $account[0]['ID'];
      }
      else{
	API_erreur('badpass');
      }
    }
  }
}

function bdd2xml($text){
  return htmlspecialchars(get_magic_quotes_runtime()==1?stripslashes($text):$text);
}
?>