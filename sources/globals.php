<?php


/*****************************************************************************
Ce fichier sert à définir certaines variables globales en debut de script.
*/
// Variables globales :
$GLOBALS['debug']=1; // Pour savoir si on est en version de debug.
$GLOBALS['bench']=1; // Pour savoir si on bench les pages.
$GLOBALS['jeupath']='/dev';

// Acces a la bdd
$GLOBALS['sql_host']='mysql'; // Serveur MySQL.
// $GLOBALS['sql_user']='root'; // User MySQL.
$GLOBALS['sql_user']='itac2020'; // User MySQL.
// $GLOBALS['sql_pass']='poogpof4fqz51vqse44gvse24gxbv'; // Pass MySQL.
$GLOBALS['sql_pass']='jfglkrg5gh4qrh5qhr1qh1'; // Pass MySQL.
$GLOBALS['sql_table']='itac01'; // Table MySQL.
$GLOBALS['sql_forum']='itac01-forum'; // Table du forum.
//$GLOBALS['sql_logs']='thessg_logs'; // Table des logs. 

// Differentes durees
$GLOBALS['tour']=600; // Un tour dure 20h.
$GLOBALS['offset']=200; // 4 heures de 'non perte'.
$GLOBALS['refresh_carte']=1*60; // On rafraichit la carte toutes les 5 mn.
$GLOBALS['update']=60; // Un perso connecte est update toutes les 60s.
// Temps avant suppression du matos a terre.
$GLOBALS['equipement_rouille']=20*3600*10;
// Un fois camoufle, il faut 4h pour disparaitre de la carte.
$GLOBALS['temps_camou']=600; 
// En cas de decamouflage lors d'un mouvement, il faut 10 mn avant de 
// reapparaitre sur la carte.
$GLOBALS['temps_decamou']=600; 

// Trucs en rapport avec la progression
$GLOBALS['gain_grade']=4000; // 3000 pour premier grade = 3 jours, 2493 pour grade max aprés 8 mois.
$GLOBALS['perte_grade']=-4000; // On perd un grade une fois arrivé à -2.000 VS
$GLOBALS['speed_comp']=3200; // Vitesse de gain des comps (base : 2300 pour dernier niveau = 6 mois)

$GLOBALS['skin']='itac'; // Nom du skin utilise, et repertoire du skin.
// ID des munars dropees par l'armure lorsqu'on tue quelqu'un.
$GLOBALS['armor_drop']=18;
// URL pour voir les fiches.
$GLOBALS['url_fiche']='http://dev.i-tac.fr/fiche.php?id=';
// Nom du jeu.
$GLOBALS['titre']='i-tac';
// URL de la base du jeu.
$GLOBALS['root_url']='http://dev.i-tac.fr/';


// Couleur des traitres.
$GLOBALS['couleur_trt']='#ff77aa';
// Couleur des traitres camoufles.
$GLOBALS['couleur_trt_camou']='#bb5588';

$listeDroits=array('colo'=>array('colo'=>array(false,'Colonel'),
				 'criteres'=>array(false,'Crit&egrave;res d\'inscription'),
				 'RP'=>array(false,'Description'),
				 'droits'=>array(false,'Droits'),
				 'grades'=>array(false,'Grades'),
				 'ordres'=>array(false,'Ordres'),
				 'valider'=>array(false,'Postulants'),
				 'virer'=>array(false,'Virer un membre'),
				 'compa'=>array(true,'Acc&eacute;s au forum'),
				 'mcompa'=>array(true,'Mod&eacute;ration du forum')),
		   'gene'=>array('compas'=>array(false,'Compagnies'),
				 'droits'=>array(false,'Droits'),
				 'ngene'=>array(false,'G&eacute;n&eacute;raux'),
				 'grades'=>array(false,'Grades'),
				 'medailles'=>array(false,'M&eacute;dailles'),
				 'ordres'=>array(false,'Ordres'),
				 'trt'=>array(false,'Peines'),
				 'qgs'=>array(false,'QG'),
				 'transfuge'=>array(false,'Transfuges'),
				 'marche'=>array(false,'March&eacute;'),
				 'mcamp'=>array(true,'Mod&eacute;ration du forum de camp'),
				 'em'=>array(true,'Acc&eacute;s au forum EM'),
				 'mem'=>array(true,'Mod&eacute;ration du forum EM'),
				 'gene'=>array(true,'Acc&eacute;s au forum des g&eacute;n&eacute;raux'),
				 'mgene'=>array(true,'Mod&eacute;ration du forum des g&eacute;n&eacute;raux')));

// Matos par defaut.
$GLOBALS['default']=array('arme1'=>1,
			  'arme2'=>201,
			  'mun1'=>120,
			  'mun2'=>36,
			  'armure'=>7,
			  'PA'=>110,
			  'g1'=>1,
			  'g2'=>27,
			  'g3'=>18);

// Choix de la timezone
date_default_timezone_set('Europe/Paris');
if ($GLOBALS['debug']) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}
?>
