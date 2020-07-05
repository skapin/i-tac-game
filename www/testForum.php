<?php
ob_start('ob_gzhandler');
// Inclusion du fichier contenant les fonctions et variables globales.
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header(1);
/*
// Creation d'un camp
echo forumNewCamp(30,'Camp 3','');
// Creation d'un compte appartenant a ce camp
echo forumNewAccount('test30','test30',4,0,0,30);
// Creation d'un perso appartenant a ce camp
echo forumNewAccount('test31','test31',4,104,0,30);
// Creation d'une compagnie
echo forumNewCompa(12,'Compagnie 12');
// Creation d'un perso y appartenant
echo forumNewAccount('test32','test32',4,105,12,30);
*/
/*
$modifs=array('diplomate'=>1,
	      'login'=>'test32',
	      'compa'=>array('ID'=>12,
			     'name'=>'Compagnie 12',
			     'forum'=>1,
			     'modoforum'=>0),
	      'camp'=>array('ID'=>30,
			    'name'=>'Camp 3',
			    'forum'=>1,
			    'em'=>1,
			    'modoforum'=>1,
			    'gene'=>1));
echo forumModAccount(105,true,$modifs);
*/
echo sha1(strtolower("Gaïa").'#!mp3r!um40k');

com_footer();
?>