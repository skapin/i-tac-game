<?PHP
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header_lite();

if(!isset($_SESSION['com_perso']))
  {
    echo 'Vous n\'êtes pas loggué.';
    com_footer();
    die();
  }
// Récupération des droits.
//$perso=recup_droits('gene');
require_once('../admin/admin_fonctions.php');
echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
<title>Sea, sex and guns admin zone</title>
<script type="text/javascript" src="scripts/admin.js"></script>
<link rel="stylesheet" href="styles/anim.css" type="text/css" />
</head>
<body>
<ul>
';
menu();
echo'</ul>
<div>
';
content();
echo'</div>
';
com_footer();
?>
