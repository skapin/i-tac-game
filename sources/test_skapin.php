<?php
include_once('fix_mysql.inc.php');
/******************************************************************
Fonction connect_db, elle permet de se connecter à la base de
donnée du jeu. Crée la variable globale $db.
*/

function mysql_connect($host, $username, $password, $new_link = FALSE, $client_flags = 0){
    global $global_link_identifier;
    $global_link_identifier = mysqli_connect($host, $username, $password);
    return $global_link_identifier;
  }

  function mysql_select_db($dbname, $link_identifier = null){
    global $global_link_identifier;
    if($link_identifier == null) {
      $link_identifier = $global_link_identifier;
    }
    return mysqli_select_db($link_identifier, $dbname);
  }

$GLOBALS['debug']=1; // Pour savoir si on est en version de debug.
$GLOBALS['bench']=1; // Pour savoir si on bench les pages.
$GLOBALS['jeupath']='/dev';

// Acces a la bdd
$GLOBALS['sql_host']='172.17.0.1'; // Serveur MySQL.
// $GLOBALS['sql_user']='root'; // User MySQL.
$GLOBALS['sql_user']='itac2020'; // User MySQL.
// $GLOBALS['sql_pass']='poogpof4fqz51vqse44gvse24gxbv'; // Pass MySQL.
$GLOBALS['sql_pass']='jfglkrg5gh4qrh5qhr1qh1'; // Pass MySQL.
$GLOBALS['sql_table']='itac01'; // Table MySQL.
$GLOBALS['sql_forum']='itac01-forum'; // Table du forum.

function connect_db()
{
  $GLOBALS['db'] = mysql_connect($GLOBALS['sql_host'],$GLOBALS['sql_user'],$GLOBALS['sql_pass']);
  
  if(!$GLOBALS['db'])
    {
      echo('Impossible de se connecter à MySQL.!!!!!');
      // echo ('Impossible de se connecter à MySQL.');
      return FALSE;
    }
  if(!mysql_select_db($GLOBALS['sql_table'],$GLOBALS['db']))
    {
      
      echo ('Impossible de se connecter à la base de données.');
      return FALSE;
    }
}
echo('--');
connect_db();
echo('--');

?>