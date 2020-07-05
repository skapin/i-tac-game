<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header(1);
if(!isset($_SESSION['com_perso'])){
  com_header(2);
  echo 'Vous n\'êtes pas loggué.';
  com_footer();
  die();
}
require_once('../inits/camps.php');
require_once('../inits/competences.php');
require_once('../inits/terrains.php');
require_once('../sources/monperso.php');

$framed=true;
echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
<link rel="stylesheet" href="',$_SESSION['path'],'styles/',$_SESSION['skin'],'/global.css" type="text/css" />
<link rel="stylesheet" href="',$_SESSION['path'],'styles/',$_SESSION['skin'],'/matos.css" type="text/css" />
<title>',$GLOBALS['titre'],'</title>
<script type="text/javascript" src="scripts/mootools.v1.11.js"></script>
<script type="text/javascript" src="scripts/niftycube.js"></script>
<script type="text/javascript" src="scripts/site.js"></script>
</head>
<body>
  <div id="matosFrame">
';
include('../sources/equipement.php');
echo'</div>
';
com_footer();
?>