<?php
include_once('fix_mysql.inc.php');
/******************************************************************
Fonction connect_db, elle permet de se connecter � la base de
donn�e du jeu. Cr�e la variable globale $db.
*/
function connect_db()
{
  $GLOBALS['db'] = mysql_connect($GLOBALS['sql_host'],$GLOBALS['sql_user'],$GLOBALS['sql_pass']);
  if(!$GLOBALS['db'])
    {
      add_message(3,'Impossible de se connecter � MySQL.!!!!!');
      return FALSE;
    }
  if(!mysql_select_db($GLOBALS['sql_table'],$GLOBALS['db']))
    {
      add_message(3,'Impossible de se connecter � la base de donn�es.!!!');
      unset($GLOBALS['db']);
      return FALSE;
    }
}

/******************************************************************
Fonction select_table. Elle permet de passer � une autre table.
Prend un entier pour param�tre.
0 : le jeu.
1 : le forum.
2 : les logs.
 */

function select_table($wich)
{
  if(isset($GLOBALS['db']) && ($GLOBALS['db']))
    {
    switch($wich)
	{
	   case 0:
            if(!mysql_select_db($GLOBALS['sql_table'],$GLOBALS['db']))
                {add_message(3,'Impossible de changer de base de donn�es.');}
	       break;
	   case 1:
            if(!mysql_select_db($GLOBALS['sql_forum'],$GLOBALS['db']))
                {add_message(3,'Impossible de changer de base de donn�es.');}
            break;
	   case 2:
	       if(!mysql_select_db($GLOBALS['sql_logs'],$GLOBALS['db']))
	           {add_message(3,'Impossible de changer de base de donn�es.');}
	       break;
	   default:
	       if(!mysql_select_db($GLOBALS['sql_table'],$GLOBALS['db']))
                {add_message(3,'Impossible de changer de base de donn�es (table inconnu).');}
	       break;
	}
    }
}

/******************************************************************
Fonction request. Elle permet de faire une requ�te � la base de
donn�e.
Pour cela, il lui faut en param�tre la cha�ne de caract�res qui est
la requ�te.
 */
function request($sql,$log=false)
{
  if($log)
    {
      $log=fopen('sql.log','a');
      fwrite($log,$sql);
      fclose($log);
    }
  if(empty($GLOBALS['db']))
    connect_db();
  $req=@mysql_query($sql,$GLOBALS['db']);
  if(!$req)
  {
    trigger_error(str_replace(array("\n","\r","\t")," ",$sql).' '.mysql_error($GLOBALS['db']).' ('.mysql_errno($GLOBALS['db']).')',E_USER_WARNING);
    if($GLOBALS['debug'])
      {add_message(3,'Erreur SQL ('.$sql.').<br />');}
    else
      {add_message(3,'Erreur SQL.<br />');}
  }
  return $req;
}

/*****************************************************************
Renvoie un tableau contenant le r�sultat d'une requ�te SELECT.
prend en param�tre la requ�te.
Le tableau renvoiy� est de la forme suivant :
� l'index 0, le nombre de r�sultats bons.
� l'index 1, le premier r�sultat sous forme de tableau.
� l'index 2, le second r�sultat sous forme de tableau.
etc.
 */
function my_fetch_array($sql)
{
  $req=request($sql);
  $contenu[0]=0;
  while ($contenu[]=mysql_fetch_array($req))
    $contenu[0]++;
  mysql_free_result($req);
  return $contenu;
}

/*****************************************************************
Renvoie le nombre de lignes lues par une requ�te SQL
 */
function exist_in_db($sql)
{
  return mysql_num_rows(request($sql));
}
/*****************************************************************
Renvoie l'index g�n�r� par la dernier INSERT utilis�.
 */
function last_id()
{
  return mysql_insert_id($GLOBALS['db']);
}

/*****************************************************************
Renvoie le nombre de lignes affet�es par le dernier DELETE ou
UPDATE.
 */
function affected_rows()
{
  return mysql_affected_rows($GLOBALS['db']);
}
/*****************************************************************
Ferme la connexion � MySQL
 */
function close_db()
{
  if(isset($GLOBALS['db']) && is_resource($GLOBALS['db']))
    mysql_close($GLOBALS['db']);
}
?>