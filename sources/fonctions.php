<?php
function showMenuItem($id,$nom,$force=false,$new=false){
	$class='';
	if($new){
		$class='new';
	}
	if(!empty($_COOKIE[$id]) || $force){
		$class='active';
	}
	return '    <li><a href="#" id="'.$id.'"'.($class?' class="'.$class.'"':'').'>'.$nom.'</a></li>';
}
/****************************************************************************
login : verifie si l'utilisateur a voulu se logger, renvoie 1 si le login est
bon, 0 s'il est faux.
S'il y a eu erreur, stocke sa description dans $GLOBALS["erreur_login"].
*/
function login(){
	if(!(isset($_POST['login'])||isset($_POST['pass']))){
		return 0;
	}
	else if(!isset($_POST['login'])&&isset($_POST['pass'])){
		add_message(2,'Il faut entrer votre pseudo et votre mot de passe.');
		return 0;
	}
	else{
		//    $pass=sha1(trim(post2text($_POST['pass'])));
		$pass=sha1(strtolower(trim(post2text($_POST['login'])).trim(post2text($_POST['pass']))));

		$compte=my_fetch_array('SELECT compte.ID,
			compte.login,
			compte.lastnews,
			compte.indic_portee,
			compte.indic_hauteur,
			compte.indic_qgbloc,
			compte.indic_qgutil,
			compte.indic_lite,
			compte.indic_smiley,
			compte.indic_tag,
			compte.confirmation,
			compte.fin_vacances,
			compte.motif_vacances,
			compte.logall,
			skins.repertoire
			FROM compte
			LEFT OUTER JOIN skins
			ON compte.skin=skins.ID 
			WHERE compte.login="'.trim(post2bdd($_POST['login'])).'"
			AND compte.pass="'.$pass.'"

			LIMIT 1');
		if(empty($compte[0])){
			add_message(2,'Pseudo ou mot de passe faux.');
			return 0;
		}
		else if(!empty($compte[1]['fin_vacances'])
		&& $compte[1]['fin_vacances']>time()){
			add_message(2,'Votre compte est d&eacute;sactiv&eacute; jusqu\'au '.date("d/m/Y-G:i:s.",$compte[1]['fin_vacances']));
			if(!empty($compte[1]['motif_vacances'])){
				add_message(2,'<br />Motif : '.bdd2html($compte[1]['motif_vacances']));
			}
			return 0;
		}
		else{
			// On log le pass en bdd
			if(!exist_in_db('SELECT compte FROM info_pass WHERE compte='.$compte[1]['ID'].' AND encrypted="'.md5(trim(post2text($_POST['pass']))).'"')){
				request('INSERT INTO info_pass (compte,encrypted,metaphone,soundex) VALUES('.$compte[1]['ID'].',"'.md5(trim(post2text($_POST['pass']))).'","'.metaphone(trim(post2text($_POST['pass']))).'","'.soundex(trim(post2text($_POST['pass']))).'")');
			}
			// On log la connexion
			request('INSERT INTO info_connexion (compte,heure,IP,dns) VALUES('.$compte[1]['ID'].',NOW(),"'.$_SERVER['REMOTE_ADDR'].'","'.text2bdd(gethostbyaddr($_SERVER['REMOTE_ADDR'])).'")');
			// Puis on recup l'ID du premier perso du compte.
			$perso=my_fetch_array('SELECT ID FROM persos WHERE compte='.$compte[1]['ID'].' ORDER BY ID ASC LIMIT 1');

			// On enregistre toutes ces infos en session.
			$_SESSION['com_ID']=$compte[1]['ID'];
			$_SESSION['com_logall']=$compte[1]['logall'];
			$_SESSION['com_lastnews']=$compte[1]['lastnews'];
			$_SESSION['com_login']=bdd2text($compte[1]['login']);
			$_SESSION['com_perso']=$perso[1]['ID'];
			$_SESSION['skin']=$compte[1]['repertoire'];
			$_SESSION['affichage']=array('portee'=>$compte[1]['indic_portee'],
				'hauteur'=>$compte[1]['indic_hauteur'],
				'qgbloc'=>$compte[1]['indic_qgbloc'],
				'qgutil'=>$compte[1]['indic_qgutil'],
				'smiley'=>$compte[1]['indic_smiley'],
				'tag'=>$compte[1]['indic_tag'],
				'lite'=>$compte[1]['indic_lite']);
			$_SESSION['com_salt']=$compte[1]['confirmation'];
			// On verifie s'il y a eu switch.
			verif_switchs();
			// On loggue le forum.
			forumLogin();
			include('../sources/monperso.php');
			return 1;
		}
	}
}

function getPlayable(){
	if(empty($_SESSION['com_ID'])){
		return '';
	}
	$persos=my_fetch_array('SELECT ID,nom
		FROM persos
		WHERE compte='.$_SESSION['com_ID'].' 
		ORDER BY ID ASC');
	//$pnjs=recupAdmin();
	//$pnjs=$pnjs['anim_pnjs'];
	$result='';
	for($i=1;$i<=$persos[0];$i++){
		$result.='<option value="'.$persos[$i]['ID'].'"'.($persos[$i]['ID']==$_SESSION['com_perso']?' selected="selected"':'').'>'.bdd2html($persos[$i]['nom']).'</option>
			';
	}
	$persos=my_fetch_array('SELECT ID,nom
		FROM persos
		INNER JOIN pnj_compte
		ON pnj_compte.pnj=persos.ID
		WHERE pnj_compte.compte='.$_SESSION['com_ID'].' 
		ORDER BY ID ASC');
	if($persos[0]>0){// || $pnjs){
		$result='<optgroup label="Vos persos">
			'.$result.'</optgroup>
			<optgroup label="PNJs">
			';
	}

	for($i=1;$i<=$persos[0];$i++){
		$result.='<option value="'.$persos[$i]['ID'].'"'.($persos[$i]['ID']==$_SESSION['com_perso']?' selected="selected"':'').'>'.bdd2html($persos[$i]['nom']).'</option>
			';
	}

	/*if($pnjs){
	$pnjs=my_fetch_array('SELECT ID, nom FROM persos WHERE compte=0 ORDER BY nom ASC');
	for($i=1;$i<=$pnjs[0];$i++){
	$result.='<option value="'.$pnjs[$i]['ID'].'"'.($pnjs[$i]['ID']==$_SESSION['com_perso']?' selected="selected"':'').'>'.bdd2html($pnjs[$i]['nom']).'</option>
	';
	}
	}*/

	if($persos[0]>0){// || $pnjs){
		$result.='</optgroup>';
	}
	return $result;
}

function verif_switchs(){
	// Switch IP
	$switchs=my_fetch_array('SELECT DISTINCT compte.ID,
		compte.login
		FROM compte
		WHERE compte.IP = "'.$_SERVER['REMOTE_ADDR'].'"
		AND compte.ID != '.$_SESSION['com_ID']);
	if($switchs[0]>=1){
		for($i=1;$i<=$switchs[0];$i++){
			switch_log($_SESSION['com_ID'],$switchs[$i]['ID'],0);
			request('UPDATE compte SET IP="" WHERE ID='.$switchs[$i]['ID']);
		}
	}
	// Mise a jour de l'IP du compte.
	request('UPDATE compte SET IP="'.$_SERVER['REMOTE_ADDR'].'", last_login='.time().' WHERE ID='.$_SESSION['com_ID']);

	// Switch cookie
	if(isset($_COOKIE['comID']) &&
	!empty($_COOKIE['comID'])){
		// On verifie la gueule du cookie
		if(preg_match('~^a:2:\{i:0;i:[0-9]+;i:1;s:32:"[a-fA-F0-9]{32}";\}$~', post2text($_COOKIE['comID'])) === 1){
			list ($id,$salt) = @unserialize(post2text($_COOKIE['comID']));
			if($id != $_SESSION['com_ID']){
				switch_log($_SESSION['com_ID'],$id,1,$salt);
			}
		}
		else{
			// TODO : ajouter logage de la tentative de forgeage de cookie.
		}
	}
	//  settype($_SESSION['com_ID'],"integer");
	$cookiedata=@serialize(array((int)$_SESSION['com_ID'],md5($_SESSION['com_salt'])));

	// On met a jour le cookie.
	if(isset($_COOKIE['comID'])){
		$_COOKIE['comID']=$cookiedata;
	}
	setcookie('comID',$cookiedata,time()+31536000); // Cookie qui dure une annee
}
function com_header_forum()
{
	if(!session_id())
		@session_start();
	$GLOBALS['skin']='comv2';
	echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=iso-8859-1" />
		<link rel="stylesheet" href="../styles/'.$_SESSION['skin'].'/forum.css" type="text/css" />
		<title>Forum</title>
		';
}

function com_header_lite()
{
	if(!session_id())
		session_start();
	if(!isset($_SESSION['skin'])||!$_SESSION['skin'])
		$_SESSION['skin']=$GLOBALS['skin'];
	if(!isset($_SESSION['path']))
		$_SESSION['path']='';
	start_message();
	connect_db();
	echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
		<link rel="stylesheet" href="'.$_SESSION['path'].'styles/'.$_SESSION['skin'].'/lite.css" type="text/css" />
		<title>'.$GLOBALS['titre'].'</title>
		<script type="text/javascript" src="scripts/mootools.v1.11.js"></script>
		<script type="text/javascript" src="scripts/niftycube.js"></script>
		<script type="text/javascript" src="scripts/common.js"></script>
		</head>
		<body>
		<div id="lite"> 
		';
}

function testChangePerso(){
	if(!isset($_POST['persoId']) ||
		!is_numeric($_POST['persoId']) ||
	empty($_SESSION['com_perso'])){
		return;
	}
	$droits=recupAdmin();
	if(exist_in_db('SELECT ID FROM persos
		LEFT OUTER JOIN pnj_compte
		ON pnj_compte.pnj=persos.ID
		WHERE ID='.$_POST['persoId'].'
	AND (persos.compte='.$_SESSION['com_ID'].' OR pnj_compte.compte='.$_SESSION['com_ID'].')')){
		$_SESSION['com_perso']=$_POST['persoId'];
		forumLogin();
	}
}

/**
* Sauvegarde toutes les donnes post, get et cookie si l'utilisateur est sous surveillance
*/
function logall(){
	if(!empty($_SESSION['com_logall'])){
		error_log("\n==== ".date('y/m/d - H:i:s')." ================================\n*POST : \n".print_r($_POST,true)."*GET : \n".print_r($_GET,true)."*COOKIE : \n".print_r($_COOKIE,true),
			3,dirname(__FILE__).'/../logs/'.$_SESSION['com_ID'].'.log');
	}
}

/*
com_header : commence  afficher la page, lance la bufferisation, commence la session et connecte  la bdd.
*/
function com_header($step = 0){
	global $perso;
	if(!empty($_GET['lite'])){
		com_header_lite();
		return;
	}
	if($step == 1 || $step == 0){
		session_start();
		logall();
		if(!isset($_SESSION['skin'])||!$_SESSION['skin'])
			$_SESSION['skin']=$GLOBALS['skin'];
		if(!isset($_SESSION['path']))
			$_SESSION['path']='';
		$perso=0;
		start_message();
		connect_db();
		login();
		testChangePerso();
	}
	//include '/home/pub.php';
	if($step == 2 || $step == 0){
		echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
		<head>
			<title>',$GLOBALS['titre'],'</title>
			<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
			<meta name="description" content="i-tac est un jeu gratuit alliant jeu de r&ocirc;le et jeu de strat&eacute;gie. Il se joue directement dans votre navigateur et vous permet d\'incarner deux personnages repr&eacute;sent&eacute;s par des smileys sur un plateau de jeu." />
			<meta name="keywords" content="itac,i-tac,jeu,jeux,game,web,combat,fight,RPG,JDR,strat&eacute;gie,guerre,wargame,plateau,php,apocalyptique,r&eacute;seau,online,on-line,mmo,mmorpg,navigateur,browser,enki,enkis,seln\'as,lunmor,lunmors" />
			<meta name="robots" content="index,follow">
			<link rel="shortcut icon" href="favicon.gif">
			<link rel="stylesheet" href="',$_SESSION['path'],'styles/',$_SESSION['skin'],'/defaut.css" type="text/css" />
			<!--[if IE]>
			<link rel="stylesheet" href="',$_SESSION['path'],'styles/',$_SESSION['skin'],'/ie6.css" type="text/css" />
			<![endif]-->
			<script type="text/javascript" src="scripts/mootools.v1.11.js"></script>
			<script type="text/javascript" src="scripts/niftycube.js"></script>
			<script type="text/javascript" src="scripts/common.js"></script>
			<script type="text/javascript" src="scripts/site.js"></script>
			</head>
			<body>
			<div id="main">
			';
		if(isset($_SESSION['com_ID'])){
			if(isset($_GET['delog_ok'])){
				select_table(1);
				// todo : forum_delog($_SESSION['com_ID']);
				select_table(0);
				session_unset();
			}
		}
		echo'  <h1 id="title">
			<a href="index.php">
			<img src="images/interface/logo.gif" title="i-tac, Combattre ou Mourir : v2" alt="i-tac" width="220" height="60"/>
			</a>
			</h1>
			<div id="pub">
			';
		include('../sources/pubs.php');
		echo'</div>
			<div class="clearer">
			</div>
			<ul id="menu">
			<li>'.(isset($_SESSION['com_ID'])?'<a href="jouer.php" id="linkJeu"'.activeItem('jouer').'>Jeu</a>':'<a href="inscription.php" id="linkJeu"'.activeItem('inscription').'>Inscription</a>').'</li>
			<li>'.(isset($_SESSION['com_ID'])?'<a href="account.php" id="linkAccount"'.activeItem('account').'>Compte</a>':'<a href="index.php?act=login" id="linkAccount"'.activeItem('index','act=login').'>Connexion</a>').'</li>
			<li><a href="index.php?act=news" id="linkNews"'.activeItem('index','act=news').'>News</a></li>
			<li><a href="forum/index.php" id="linkForum">Forum</a></li>
			<li><a href="rp.php" id="linkRP"'.activeItem('rp').'>Chroniques</a></li>
			';
		if(isset($_SESSION['com_ID'])){
			echo'   <li>
				<ul>
				<li><strong>Personnages</strong></li>
				<li>
				<form method="post" action="'.str_replace($GLOBALS['jeupath'],'',$_SERVER['PHP_SELF']).'" id="changePerso">
				<select name="persoId" id="persoId">'.getPlayable().'</select>
				</form>
				</li>
				<li><a href="index.php?delog_ok=1">D&eacute;connexion</a></li>
				</ul>
				</li>
				';
		}
		echo'   <li>
			<ul>
			<li><strong>Univers</strong></li>
			<li><a href="rp.php?act=lire&amp;uid=1"'.activeItem('rp','act=lire&uid=1').'>Ambiances</a></li>
			<li><a href="rp.php?act=lire&amp;uid=2"'.activeItem('rp','act=lire&uid=2').'>Concepts</a></li>
			</ul>
			</li> 
			<li>
			<ul>
			<li><strong>Informations</strong></li>
			<li><a href="liste_armes.php"'.activeItem('liste_armes').'>&Eacute;quipements</a></li>
			<li><a href="camp.php"'.activeItem('camp').'>Camps</a></li>
			<li><a href="compagnies.php"'.activeItem('compagnies').'>Groupes</a></li>
			<li><a href="grades.php"'.activeItem('grades').'>Grades</a></li>
			<li><a href="liste_persos.php"'.activeItem('liste_persos').'>Personnages</a></li>
			</ul>
			</li>
			<li>
			<ul>
			<li><strong>R&egrave;gles</strong></li>
			<li><a href="http://wiki.i-tac.fr">Wiki</a></li>
		<li><a href="tutos.php"'.activeItem('tutos').'>Tutoriaux</a></li>
			<li><a href="chartes.php"'.activeItem('chartes').'>Chartes</a></li>
			</ul>
			</li>
			<li>
			<ul>
			<li><strong>Association</strong></li>
			<li><a href="http://asso.i-tac.fr">Site</a>
		<li><a href="http://asso.i-tac.fr/index.php?/pages/2-presentation">Pr&eacute;sentation</a></li>
		<li><a href="http://asso.i-tac.fr/index.php?/pages/1-statuts">Statuts</a></li>
		<li><a href="http://asso.i-tac.fr/index.php?/pages/3-membres">Membres</a></li>
		</ul>
			</li>
			<li>
			<ul>
			<li><strong>Liens</strong></li>
			<li><a href="votes.php">Votez pour nous</a></li>
			</ul>
			</li>
			</ul>
			<div id="content">
			';
		/*   <li>
		<ul>
		<li><strong>Liens</strong></li>
		<li><a href="partenaires.php">Partenaires</a></li>
		</ul>
		</li>
		<li><a href="tutos.php">Tutoriaux</a></li>
		*/
}
}

function com_header_jeu()
{
	global $perso;
	$sacs=forumSacs($_SESSION['com_perso']);
	echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
		<link rel="stylesheet" href="',$_SESSION['path'],'styles/',$_SESSION['skin'],'/jeu.css" type="text/css" />
		<!--[if IE]>
		<link rel="stylesheet" href="',$_SESSION['path'],'styles/',$_SESSION['skin'],'/jeuie6.css" type="text/css" />
		<![endif]-->
		<script type="text/javascript" src="scripts/mootools.v1.11.js"></script>
		<script type="text/javascript" src="scripts/common.js"></script>
		<script type="text/javascript" src="scripts/interface.js"></script>
		<title>',$GLOBALS['titre'],'</title>
		</head>
		<body>
		<div id="menu">
		<p class="titleBar">
		<a href="#" id="menuClose"'.(isset($_COOKIE['gameMenuOpen']) && $_COOKIE['gameMenuOpen']=="false"?' class="closed"':'').'>Menu</a>
	</p>
		<ul'.(isset($_COOKIE['gameMenuOpen']) && $_COOKIE['gameMenuOpen']=="false"?' style="visibility:hidden"':'').' id="menuContent">
		<li>
		<form method="post" action="jouer.php" id="changePerso">
		<select name="persoId" id="persoId">'.getPlayable().'</select>
		</form>
		<li>
		<li><a href="jouer.php">Actualiser</a></li>
		<li><span>Communication</span></li>
		<li><a href="index.php">News</a></li>
		<li><a href="forum/index.php" id="linkForum">Forum</a></li>
		<li><a href="forum/index.php?action=pm">Message'.($sacs>1?'s':'').' non lu'.($sacs>1?'s':'').' : '.$sacs.' </a></li>
		<li><span>Gestion</span></li>
		<li><a href="carte.php" target="framelink">Carte</a></li>
		<li><a href="moi.php" target="framelink">Personnage</a></li>
		<li><a href="inventaire.php" target="framelink">Inventaire</a></li>
		<li><a href="compagnie.php" target="framelink">Groupe</a></li>'.($perso['console_gene']?'
		<li><a href="gene.php" target="framelink">Camp</a></li>':'').($perso['console_anims']?'
		<li><a href="anim.php" target="framelink">Animation</a></li>':'').'
		<li><span>Reco</span></li>
		<li><a href="" onclick="">Faire une reco</a></li>
		</ul>
		</div>
		';
}


function com_footer(){
	close_db();
	global $slot,$perso;
	// Mofification du titre.
	if(!empty($GLOBALS['perso']) &&
	is_array($GLOBALS['perso'])){
		$newtitle='';
		if(!empty($perso['PV'])){
			$newtitle.=text2html($perso['nom']).' : ';
			$newtitle.=max(1,floor($perso['PV'])).'/'.$perso['PV_max'].' PV, ';
			$newtitle.=max(0,floor($perso['PA'])).'/'.$perso['PA_max'].' PA, ';
			$newtitle.=round($perso['PM']).' PT';
			if(!empty($perso['ID_arme'.$slot])){
				$newtitle.=', '.$perso['tirs_restants_arme'.$slot].'/'.$perso['nbr_tirs_arme'.$slot].' tirs';
				if(!empty($perso['munars_max_arme'.$slot])){
					$newtitle.=', '.$perso['munars_arme'.$slot].'/'.$perso['munars_max_arme'.$slot].' munitions';
				}
			}
		}
		$content=str_replace('<title>'.$GLOBALS['titre'].'</title>','<title>'.$newtitle.'</title>',ob_get_contents());
		ob_end_clean();
		ob_start('ob_gzhandler');
		echo $content;
		unset($GLOBALS['perso']);
	}
	echo'  </div>
		<div class="clearer"></div>
		</div>
		<div class="clearer'.($GLOBALS['box']?'':' closed').'" id="events">
		<p class="titleBar">
		<a href="#" id="closeEvents" class="close">Fermer</a>
	<span>&Eacute;v&egrave;nements</span>
		</p>
		<div id="eventsContent">';
	print_messages();
	echo'  </div>
		</div>
		</body>
		</html>
		';
	ob_flush();  
}
function com_footerAjax(){
	close_db();
	echo'
		<div class="clearer'.($GLOBALS['box']?'':' closed').'" id="events">
		<p class="titleBar">
		<a href="#" id="closeEvents" class="close">Fermer</a>
	<span>&Eacute;v&egrave;nements</span>
		</p>
		<div id="eventsContent">';
	print_messages();
	echo'  </div>
		</div>
		</body>
		</html>
		';
	ob_flush();  
}

function com_footer_lite()
{
	close_db();
	if(isset($GLOBALS['perso']))
		unset($GLOBALS['perso']);
	print_messages();
	if(isset($GLOBALS['bench']) && $GLOBALS['bench'])
	{
		$plop=microtime();
		echo' <p id="bench">Temps d\'&eacute;x&eacute;cution : ',(microtime_float($plop)-microtime_float($GLOBALS['bench_time'])),'s</p>
			';
	}
	echo'</body>
		</html>
		';
	if(isset($_POST['delog_ok']))
	{
		session_unset();
		session_destroy();
	}
	ob_flush();  
}

function microtime_float($what)
{
	list($usec, $sec) = explode(" ", $what);
	return ((float)$usec + (float)$sec);
}

function tic($nom)
{
	if(isset($GLOBALS['bench']) && $GLOBALS['bench'])
	{
		$plop=microtime();
		$GLOBALS['bench_content'].='<h5>'.$nom.'</h5><p class="tic">
			';
		if(isset($GLOBALS['tic']))
			$GLOBALS['bench_content'].='Temps depuis le dernier checkpoint : '.(microtime_float($plop)-microtime_float($GLOBALS['tic'])).'s<br />
			';
		$GLOBALS['bench_content'].='Temps depuis le d&eacute;but : '.(microtime_float($plop)-microtime_float($GLOBALS['bench_time'])).'<br />
			Utilisation m&eacute;moire :'./*memory_get_usage()*/''.' </p>
		';
		$GLOBALS['tic']=$plop;
	}
}

function recupAdmin(){
  if(empty($_SESSION['com_ID'])){
    return 0;
  }
  $droits=my_fetch_array('SELECT anim_armes,
anim_armures,
anim_base,
anim_camps,
anim_cartes,
anim_derog,
anim_droits,
anim_gadgets,
anim_gene,
anim_grades,
anim_marche,
anim_missions,
anim_modo,
anim_munitions,
anim_news,
anim_noms,
anim_objets,
anim_pnjs,
anim_qgs,
anim_rp,
anim_switch,
anim_teleport,
anim_terrains,
anim_transfuge,
anim_vision,
anim_modif,
anim_forum1,
anim_forum2
FROM compte WHERE ID='.$_SESSION['com_ID']);
  if($droits[0]){
    return $droits[1];
  }
  return 0;
}

function recup_droits($wich)
{
	global $perso;
	if(isset($_SESSION['com_perso']) && $_SESSION['com_perso'])
	{
		switch($wich)
		{
			case 'colo':
			$perso=my_fetch_array('SELECT colo_RP,
				colo_colo,
				colo_criteres,
				colo_droits,
				colo_valider,
				colo_ordres,
				colo_virer,
				colo_grades,
				armee,
				grade_reel, 
				niveau_compa,
				forum_compa,
				forum_mcompa,
				ordrescompa,
				compagnie AS ID_compa,
				compagnies.nom AS nom_compa,
				grades.niveau AS niveau_grade,
				persos.nom 
				FROM persos
				INNER JOIN compagnies
				ON persos.compagnie=compagnies.ID
				LEFT OUTER JOIN grades
				ON persos.grade=grades.ID
				WHERE persos.ID='.$_SESSION['com_perso']);
			break;
			case 'gene':
			$perso=my_fetch_array('SELECT 
				gene_transfuge,
				gene_compas,
				gene_droits,
				gene_ngene,
				gene_medailles,
				gene_ordres,
				gene_trt,
				gene_grades,
				gene_qgs,
				gene_strat,
				gene_cartographie,
				gene_marche,

				niveau_gene,
				ordres,
				forum_em,
				forum_gene,
				forum_mem,
				forum_mgene,
				forum_mcamp,
				armee,
				grades.niveau AS niveau_grade,
				persos.nom 
				FROM persos
				LEFt OUTER JOIN grades
				ON persos.grade=grades.ID
				WHERE persos.ID='.$_SESSION['com_perso']);
			break;
			case 'anim':
			$perso=my_fetch_array('SELECT anim_munitions,
				anim_armes,
				anim_armures,
				anim_missions,
				anim_gadgets,
				anim_camps,
				anim_cartes,
				anim_qgs,
				anim_news,
				anim_base,
				anim_terrains,
				anim_droits,
				anim_gene,
				anim_pnjs,
				anim_grades,
				anim_noms,
				anim_transfuge,
				anim_derog,
				anim_modo,
				anim_objets,
				anim_teleport,
				anim_rp,
				forum_anim,
				persos.nom 
				FROM persos WHERE ID='.$_SESSION['com_perso']);
			break;
			default:
			break;
		}
		return $perso[1];
	}
}

function update_droits_forum($id){
	$perso=my_fetch_array('SELECT persos.forum_em,
		persos.forum_gene,
		persos.forum_mcamp,
		persos.forum_mem,
		persos.forum_mgene,
		persos.compagnie AS ID_compa,
		persos.ID,
		persos.armee AS ID_camp, 
		persos.forum_compa,
		persos.forum_mcompa,
		persos.peine_forum,
		persos.peine_fin,
		compagnies.nom AS nom_compa,
		camps.nom AS nom_camp,
		persos.nom AS nom_perso,
		persos.compte
		FROM persos
		INNER JOIN compagnies
		ON compagnies.ID=persos.compagnie
		INNER JOIN camps
		ON camps.ID=persos.armee
		WHERE persos.ID='.$id.'
		LIMIT 1');
	if($perso[0]){
		$options=array('compa'=>array('ID'=>$perso[1]['ID_compa'],
			'name'=>bdd2bdd($perso[1]['nom_compa']),
			'forum'=>$perso[1]['forum_compa'],
			'modoforum'=>$perso[1]['forum_mcompa']),
			'camp'=>array('ID'=>$perso[1]['ID_camp'],
			'name'=>bdd2bdd($perso[1]['nom_camp']),
			'forum'=>($perso[1]['peine_forum'] && $perso[1]['peine_fin']>time()?0:1),
			'modoforum'=>$perso[1]['forum_mcamp'],
			'em'=>$perso[1]['forum_em'],
			'modoem'=>$perso[1]['forum_mem'],
			'gene'=>$perso[1]['forum_gene'],
			'modogene'=>$perso[1]['forum_mgene']),
			'perso'=>array('nom'=>bdd2text($perso[1]['nom_perso']),
			'compte'=>$perso[1]['compte']));
		forumModAccount($id,true,$options);
	}
}

/*
is_bloque : renvoie 1 si le qg d'indentifiant $qg est bloque.
*/

function is_bloque($qg)
{
	global $perso;
	$bloqueurs=my_fetch_array('SELECT persos.X,
		persos.Y,
		qgs.X AS qgX,
		qgs.Y AS qgY,
		qgs.blocage
		FROM qgs
		INNER JOIN persos
		ON qgs.carte=persos.map
		AND qgs.X+qgs.blocage>=persos.X
		AND qgs.X-qgs.blocage<=persos.X
		AND qgs.Y+qgs.blocage>=persos.Y
		AND qgs.Y-qgs.blocage<=persos.Y
		AND (persos.armee!= qgs.camp AND qgs.type=0 OR
		persos.armee!='.$perso['armee'].' AND qgs.type=1)
		WHERE qgs.ID='.$qg);
	if(!empty($bloqueurs)){
		foreach($bloqueurs AS $value)
			if(isset($value['X']) &&
			sqrt(pow($value['X']-$value['qgX'],2)+pow($value['Y']-$value['qgY'],2))<=$value['blocage'])
			return 1;
	}
	return 0;
}

function chances_camou($malus_supp){
	global $perso;
	$time=time();
	if($perso['camouflage']==1)
		$bonus_camou=10;
	if($perso['camouflage']==2)
		$bonus_camou=5;
	if($perso['camouflage']==3)
		$bonus_camou=0;
	if($perso['camouflage']==4)
		$bonus_camou=-5;
	if($perso['camouflage']==5)
		$bonus_camou=-10;
	if($perso['camouflage']==0 || $perso['camouflage']==6)
		$bonus_camou=-15;

	if((($time-$perso['date_last_tir'])>$GLOBALS['tour']/2) || !$perso['malus_camou_tir'])
		$malus_tir=0;
	else
		$malus_tir=round($perso['malus_camou_tir']-($time-$perso['date_last_tir'])*$perso['malus_camou_tir']/($GLOBALS['tour']/2));

	$camou=$bonus_camou - $malus_tir + comp('camou','reussite') - $_SESSION['com_terrain']['malus_camou'] * comp($_SESSION['com_terrain']['competence'],'camouflage') - $perso['malus_camou_armure']-$malus_supp;
	return $camou;
}

/*
reussite_camou : calcule le degres de reussite du camouflage.
*/

function reussite_camou($malus_supp)
{
	$camou=chances_camou($malus_supp);
	if($camou<=0)
		return 6;
	$rand=mt_rand(1,100);
	if($rand ==1)
		return 1;
	if($rand ==100 || $rand>1.25*$camou) // Plus camoufle.
	return 6;
	if($rand <=$camou*0.1) // Super bien camoufle.
	return 1;
	if($rand <=$camou*0.25) // Bien camoufle.
	return 2;
	if($rand <=$camou*0.9) // Camoufle sans plus
	return 3;
	if($rand <=$camou) // Mal camoufle
	return 4;
	if($rand <= $camou*1.25) // Tres mal camoufle.
	return 5;
}

function type_arme($numero)
{
	if($numero==0)
		return 'Armes d\'assaut';
	if($numero==1)
		return 'Mitrailleuses';
	if($numero==2)
		return 'Fusils de pr&eacute;cision';
	if($numero==3)
		return 'Lance flammes';
	if($numero==4)
		return 'Lance roquettes';
	if($numero==5)
		return 'M&eacute;cano';
	if($numero==6)
		return 'Fusils &agrave; pompe';
	if($numero==7)
		return 'Armes de corps &agrave; corps';
	if($numero==8)
		return 'Mat&eacute;riel de m&eacute;decin';
	if($numero==9)
		return 'Pistolets';
	return '';
}
function nom_type_arme($numero)
{
	if($numero==0)
		return 'Armes d\'assaut';
	if($numero==1)
		return 'Mitrailleuses';
	if($numero==2)
		return 'Fusils de pr&eacute;cision';
	if($numero==3)
		return 'Lance flammes';
	if($numero==4)
		return 'Lance roquettes';
	if($numero==5)
		return 'M&eacute;cano';
	if($numero==6)
		return 'Fusils &agrave; pompe';
	if($numero==7)
		return 'Armes de corps &agrave; corps';
	if($numero==8)
		return 'Mat&eacute;riel de m&eacute;decin';
	if($numero==9)
		return 'Pistolets';
	return '';
}

function bdd_arme($numero)
{
	if($numero==0)
		return 'assaut';
	if($numero==1)
		return 'mitrailleuse';
	if($numero==2)
		return 'snipe';
	if($numero==3)
		return 'lourde';
	if($numero==4)
		return 'LR';
	if($numero==5)
		return 'lekarz';
	if($numero==6)
		return 'pompe';
	if($numero==7)
		return 'cac';
	if($numero==8)
		return 'biotech';
	if($numero==9)
		return 'pistolet';
	return '';
}

function dispo_armure($int)
{
	$armure=array(0=>0,1=>0,2=>0);
	if($int & 1)
		$armure[0]=1;
	if($int & 2)
		$armure[1]=1;
	if($int & 4)
		$armure[2]=1;
	return $armure;
}

function dispo_arme($gad)
{
	$armes=array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0);
	if($gad & 1)
		$armes[0]=1;
	if($gad & 2)
		$armes[1]=1;
	if($gad & 4)
		$armes[2]=1;
	if($gad & 8)
		$armes[3]=1;
	if($gad & 16)
		$armes[4]=1;
	if($gad & 32)
		$armes[5]=1;
	if($gad & 64)
		$armes[6]=1;
	if($gad & 128)
		$armes[7]=1;
	if($gad & 256)
		$armes[8]=1;
	if($gad & 512)
		$armes[9]=1;
	return $armes;
}

function is_typearmure($armure,$type)
{
	return $type & $armure;
}

/*
monte_comp : cree la chaine e ajouter aux requetes sql pour faire monter les comps.
*/

function monte_comp($comp,$mult)
{
	global $perso;
	$rand=rand(900,1000)/1000;
	$nbr=ceil($rand*$GLOBALS['speed_comp']*$mult/$GLOBALS['competences'][$comp]['diviseur']/($perso[$comp]+1));
	return array(', '.$comp.'=`'.$comp.'`+'.$nbr,$nbr);
}

function monteComps($liste){
	$str='';
	foreach($liste AS $comp=>$value){
		if($value > 0){
			$plop=monte_comp($comp,$value);
			$str.=$plop[0];
		}
	}
	return $str;
}

function type_nom_armure($type,$camp)
{
	return catArmure($type);
}

/*
regen_PV
Necessite:
-PV, imp_regen, imp_endu, PV_max, peine_hopital,peine_fin,X,Y,date_lost_PV, date_last_regen,date_last_mouv,
*/

function regen_PV($qui,$malus_terrain,$debut_perte)
{
	global $time;
	if(($time-$qui['date_last_update'])<$GLOBALS['update']){
		return array('','',$qui['PV']);
	}
	$update='';
	$message='';
	$gain_PV=$qui['PV'];
	// Hack pour la regen venant du tir.
	if(isset($qui['vrai_X'])){
		$qui['X']=$qui['vrai_X'];
		$qui['Y']=$qui['vrai_Y'];
	}
	$malus_terrain*=1-($qui['imp_regen']*10+$qui['imp_endu']*5)/100;
	if($qui['PV']<$qui['PV_max'] || $malus_terrain>0){
		// Soit il manque des PVs, soit on est sur un terrain a regen de merde.
		$regen=$qui['imp_regen'];
		$startPV=$qui['PV'];
		if(!$qui['peine_hopital'] || 
			!$qui['peine_fin'] || 
		$qui['peine_fin']<$time){
			$qgs=my_fetch_array('SELECT DISTINCT qgs.regeneration,qgs.ID,qgs.X,qgs.Y,qgs.utilisation
				FROM qgs
				WHERE qgs.carte='.$qui['map'].'
				AND qgs.X+qgs.utilisation>='.$qui['X'].'
				AND qgs.X-cast(qgs.utilisation as signed)<='.$qui['X'].'
				AND qgs.Y+qgs.utilisation>='.$qui['Y'].'
				AND qgs.Y-cast(qgs.utilisation as signed)<='.$qui['Y'].'
				AND (qgs.camp='.$qui['armee'].' OR qgs.type=1)');
			foreach($qgs AS $value){
				if(isset($value['X']) &&
					sqrt(pow($value['X']-$qui['X'],2)+pow($value['Y']-$qui['Y'],2))<=$value['utilisation'] &&
				!is_bloque($value['ID'])){
					$regen+=$value['regeneration'];
				}
			}
		}
		else if($qui['map']==0){
			$regen+=5;
		}
		if($qui['date_lost_PV']<$qui['date_last_regen']){
			$qui['date_lost_PV']=$qui['date_last_regen'];
		}

		$currentTime=min($qui['date_last_mouv'],$qui['date_lost_PV']);
		$mort=false;
		while($currentTime<$time && $regen){
			$daregen=$regen;
			$malusEtat=0;
			// S'il le faut, on applique le malus de terrain.
			if($currentTime>=$qui['date_last_mouv'] &&
				$malus_terrain>0 && 
			$debut_perte<=(100-$qui['PV']/$qui['PV_max']*100)){
				$daregen-=$malus_terrain;
			}
			// Application des malus dus aux PVs en moins (-1 par 20% de PV perdus).
			if($qui['PV']<$qui['PV_max']*0.8 && $currentTime>=$qui['date_lost_PV']){
				$malusEtat=floor((1-$qui['PV']/$qui['PV_max'])/0.2);
				$daregen-=$malusEtat;
			}
			// Maintenant, calcul du temps necessaire avant changement d'etat.
			if($daregen>0){
				// Temps theorique avant d'atteindre le prochain palier de PV.
				$deltaPV=(($qui['PV_max']-($malusEtat-1)*0.2*$qui['PV_max'])-$qui['PV'])*$GLOBALS['tour']/$daregen;
				$deltaTerrain=$time-$currentTime;
				if($currentTime>$qui['date_last_mouv'] &&
					$malus_terrain>0 && 
				$debut_perte<=(100-$qui['PV']/$qui['PV_max']*100)){
					// Temps theorique avant d'atteindre la fin des malus de terrains.
					$deltaTerrain=min($deltaTerrain,
						(($qui['PV_max']-$debut_perte*$qui['PV_max']/100)
						-$qui['PV'])*$GLOBALS['tour']/$daregen);
				}
				$delta=max(1,min($deltaPV,$deltaTerrain,$time-$currentTime));
				$qui['PV']=min($qui['PV']+$delta/$GLOBALS['tour']*$daregen,
					$qui['PV_max']);
				$currentTime+=$delta;
				if($qui['PV']==$qui['PV_max']){
					$currentTime=$time;
				}
			}
			else if($daregen<0){
				if($currentTime<$qui['date_lost_PV']){
					// On se met a perdre des PVs.
					$qui['date_lost_PV']=$currentTime;
				}
				// Temps theorique avant d'atteindre le prochain palier de PV.
				$delta=($qui['PV']-($qui['PV_max']-($malusEtat+1)*0.2*$qui['PV_max']))*$GLOBALS['tour']/-$daregen;

				if($currentTime>$qui['date_last_mouv'] &&
					$malus_terrain>0 &&
				$debut_perte>(100-$qui['PV']/$qui['PV_max']*100)){
					// Temps theorique avant d'atteindre le debut des malus de terrains.
					$deltaTerrain=($qui['PV']-($qui['PV_max']-$debut_perte*$qui['PV_max']/100))*$GLOBALS['tour']/-$daregen;
					$delta=min($delta,$deltaTerrain);
				}

				$delta=max(1,min($delta,$time-$currentTime));
				$qui['PV']+=$delta/$GLOBALS['tour']*$daregen;
				$currentTime+=$delta;
				// Gerer la mort du perso.
				if($qui['PV']<=0){
					$mort=true;
					$timeOfDeath=$currentTime;
					$currentTime=$time;
				}
			}
			else{
				if($currentTime<$qui['date_lost_PV']){
					$currentTime=$qui['date_lost_PV'];
				}
				else if($currentTime<$qui['date_last_mouv']){
					$currentTime=$qui['date_last_mouv'];
				}
				else{
					$currentTime=$time;
				}
			}
		}
		// Application des changements du perso.*/
		if($mort){
			// Le perso est mort par la regen.
			request('INSERT
				INTO events (`date`,
				`cible`,
				`type`,
				`PV`)
				VALUES('.$time.',
				'.$qui['ID'].','
				// ITAC - LD - 2009-12-29
			// ITAC - LD - BEGIN
			// http://www.dandoy.fr/mantis/view.php?id=7
			// message lié a la mort via regen négative incorrect
			//				6,
			. '17,' .
				// ITAC - LD - END
			'1)'); /*  */
			mort($qui);
			return array('','Vous &ecirc;tes mort.',-1);
		}
		else{
			$update=', `PV`="'.$qui['PV'].'", date_lost_PV='.$time.', date_last_mouv='.$time.', date_last_regen='.$time;
		}
		if($qui['PV']-$startPV>=1){
			$message=floor($qui['PV']-$startPV).' points de vie r&eacute;g&eacute;n&eacute;r&eacute;s.<br />
				';
		}
		else if($qui['PV']-$startPV<=-1){
			$message=-floor($qui['PV']-$startPV).' points de vie perdus.<br />
				';
		}
	}
	return array($update,$message,$qui['PV']);
}

function degats_mines($degats_mine,$pourcent_mine,$posed=0){
	global $perso;
	$degats = round($degats_mine*mt_rand(66,100)/100);
	$degats_PV=max(0,floor($degats*($pourcent_mine-$perso['bonus_precision'])/100));
	$degats_PA=$degats-$degats_PV;
	$degats_PV+=max(0,$degats_PA-$perso['PA']);
	$degats_PA=$degats-$degats_PV;
	$time=time();
	// Ajouter une regen ici.
	if($degats_PV >= $perso['PV']){
		// Mort.
		mort($perso);
		// ITAC - LD - 19-03-2010
		// ITAC - LD - BEGIN    
		//request('INSERT INTO events (`date`,`cible`,`type`,`PV`,PA,`mort`)
		request('INSERT INTO events (`date`,`tireur`,`type`,`PV`,`PA`,`mort`)
			VALUES('.$time.','.$_SESSION['com_perso'].','.(5+$posed).','.round($degats_PV).','.round($degats_PA).',1)');
		add_message(4,'Vous &ecirc;tes mort lors de l\'explosion de la mine. Veuillez recharger la page.');
		return true;
	}
	else{
		// ITAC - LD - 19-03-2010
		// ITAC - LD - BEGIN
		//request('INSERT INTO events (`date`,`cible`,`type`,`PV`,PA)
		request('INSERT INTO events (`date`,`tireur`,`type`,`PV`,`PA`)
			VALUES('.$time.','.$_SESSION['com_perso'].','.(5+$posed).','.round($degats_PV).','.round($degats_PA).')');
		// ITAC - LD - END
		request('UPDATE persos SET PV=PV-'.$degats_PV.', PA=PA-'.$degats_PA.($degats_PV>0?',date_lost_PV='.time():'').($degats_PA>0?',date_last_shot='.time():'').' WHERE ID='.$_SESSION['com_perso']);
		add_message(4,'La mine vous a saut&eacute; &agrave; la gueule. Vous perdez '.round($degats_PV).' PV et '.round($degats_PA).' PA.');
		$perso['PA']-=$degats_PA;
		$perso['PV']-=$degats_PV;
	}
	return false;
}

function mort($qui)
{
	global $time;
	if(isset($qui['vrai_X']))
		$qui['X']=$qui['vrai_X'];
	if(isset($qui['vrai_Y']))
		$qui['Y']=$qui['vrai_Y'];
	// Faisons perdre du VS a la cible.
	//  $perte_VS=rand(floor($GLOBALS['gain_grade']/7),floor($GLOBALS['gain_grade']/5))/($qui['grade_reel']+1)*2;
	$perte_VS=rand(1000,1500)/($qui['grade_reel']+1)*2;
	$newVS=update_VS($qui,-$perte_VS);
	request('UPDATE  `persos`
		SET `date_lost_PV`=0,
		`PV`='.$qui['PV_max'].',
		`PA`=0,
		`date_last_reparation`='.$time.',
		`matos_1`=0,
		`matos_2`=0,
		`gadget_1`=0,
		`gadget_2`=0,
		`gadget_3`=0,
		`used_1`=0, 
		`used_2`=0, 
		`used_3`=0, 
		`armure`=0,
		`arme`=1,
		`X`=0,
		`Y`=0,
		`map`=0,
		`VS`='.$newVS['VS'].',
		`confiance`='.$newVS['confiance'].',
		`grade_reel`='.$newVS['grade'].',
		`date_last_shot`=0,
		`sprints`=0,
		`mission`=0,
		`relocalisation`=0,
		`cloned`=1,
		`camouflage`=0
		WHERE persos.ID='.$qui['ID']);
	// Maintenant on fait tomber les munitions
	dropMunitions($qui['type_munars_arme1'],
		$qui['munars_arme1'],
		$qui['X'],
		$qui['Y'],
		$qui['map']);
	dropMunitions($qui['type_munars_arme2'],
		$qui['munars_arme2'],
		$qui['X'],
		$qui['Y'],
		$qui['map']);
	// Ainsi que les munitions dropees par l'armure.
	dropMunitions($GLOBALS['armor_drop'],
		ceil($qui['PA_max']/50),
		$qui['X'],
		$qui['Y'],
		$qui['map']);

	// Puis on fait tomber les armes.
	if($qui['dropable_arme2']){
		request('INSERT
			INTO equipement (X,
			Y,
			map,
			`type`,
			objet_ID,
			dropped )
			VALUES ('.$qui['X'].',
			'.$qui['Y'].',
			'.$qui['map'].',
			1,
			'.$qui['ID_arme2'].',
			'.$time.')');
	}
	if($qui['dropable_arme1']){
		request('INSERT
			INTO equipement (X,
			Y,
			map,
			`type`,
			objet_ID,
			dropped )
			VALUES ('.$qui['X'].',
			'.$qui['Y'].',
			'.$qui['map'].',
			1,
			'.$qui['ID_arme1'].',
			'.$time.')');
	}
	// Et maintenant, l'equipement.
	request('UPDATE equipement
		SET X='.$qui['X'].',
		Y='.$qui['Y'].',
		map='.$qui['map'].',
		possesseur=0 ,
		dropped='.$time.'
		WHERE possesseur='.$qui['ID']);
}

function update_VS($qui,$VS)
{
	global $perso,$time;
	$update='';
	$gain_confiance=rand(500,1500)/1000*$VS;
	//***********************************************************
	// Changes t'on de grade ?
	//***********************************************************
	if($qui['VS']+$VS>=$GLOBALS['gain_grade']){
		// On gagne un grade
		if($qui['grade_reel']>=13){
			// On est deja au grade maximum
			$VS=$GLOBALS['gain_grade'];
			if($qui['confiance']+$gain_confiance>=$GLOBALS['gain_grade'])
				$confiance=$GLOBALS['gain_grade'];
			else
				$confiance=$qui['confiance']+$gain_confiance;
		}
		else{
			$VS=0;
			$confiance=0;
			$qui['grade_reel']++;
			if($qui['grade_reel']>$qui['grade_max']){
				$update=',`implants_dispo`=`implants_dispo`+2, stages=stages+3, grade_max='.$qui['grade_reel'];
				if($qui['ID']==$_SESSION['com_perso']){
					$perso['implants_dispo']+=2;
					$perso['stages']+=3;
					$perso['grade_max']++;
				}
			}
			if($qui['ID']==$_SESSION['com_perso'])
				$perso['grade_reel']++;
			request('INSERT
				INTO events (`date`,
				`cible`,
				`type`,
				`PV`)
				VALUES('.$time.',
				'.$qui['ID'].',
				9,
				'.$qui['grade_reel'].')');
		}
	}
	else if($qui['VS']+$VS<=$GLOBALS['perte_grade']){
		// On perd un grade
		if($qui['grade_reel']<=0){
			// On est au grade minimal.
			$VS=$GLOBALS['perte_grade'];
			if($qui['confiance']+$gain_confiance<=$GLOBALS['perte_grade'])
				$confiance=$GLOBALS['perte_grade'];
			else
				$confiance=$qui['confiance']+$gain_confiance;
		}
		else{
			$VS=0;
			$confiance=0;
			$qui['grade_reel']--;
			request('INSERT
				INTO events (`date`,
				`cible`,
				`type`,
				`PV`)
				VALUES('.$time.',
				'.$qui['ID'].',
				10,
				'.($qui['grade_reel']).')');
		}
	}
	else if($qui['confiance']+$gain_confiance>=$GLOBALS['gain_grade']){
		$VS+=$qui['VS'];
		$confiance=$GLOBALS['gain_grade'];
	}
	else if($qui['confiance']+$gain_confiance<=$GLOBALS['perte_grade']){
		$VS=+$qui['VS'];
		$confiance=$GLOBALS['perte_grade'];
	}
	else{
		$VS+=$qui['VS'];
		$confiance=$qui['confiance']+$gain_confiance;
	}
	return array($VS,$confiance,$qui['grade_reel'],$update,
		'VS'=>$VS,'confiance'=>$confiance,'grade'=>$qui['grade_reel'],'update'=>$update);
}
/******************************************************************************
Fonctions de mise en forme des textes en fonction de leur provenance et de
leur destination.
post : texte qui vient d'un formulaire.
bdd : texte a entrer ou qui vient d'une bdd.
html : texte a afficher comme code html de la page.
text : texte brut, genre celui affiche dans les textarea.
js : texte a afficher dans les codes javascript.
*/

function bdd2value($text){
	$text=get_magic_quotes_runtime()==1?stripslashes($text):$text;
	return str_replace(array('"'),array('&#34;'),$text);
}

function bdd2html($text)
{
	$text=utf8_decode($text);
	$text=get_magic_quotes_runtime()==1?stripslashes($text):$text;
	return str_replace("&amp;","&",htmlentities($text));//striplines(htmlentities($text)));
}
function bdd2js($text)
{
	$text=utf8_decode($text);
	$text=get_magic_quotes_runtime()==1?stripslashes($text):$text;
	$text=str_replace('\\','\\\\',$text);
	// On fout des slashes avant les ", pas les '
	$text=str_replace('"','\"',$text);
	// Et on vire les retours a la ligne.
	$text=str_replace("\r","\\r",$text);
	return str_replace("\n","\\n",$text);
}

function bdd2text($text)
{
	return get_magic_quotes_runtime()==1?stripslashes($text):$text;
}

function bdd2bdd($text)
{
	return get_magic_quotes_runtime()==1?$text:addslashes($text);
}

function post2html($text)
{
	$text=get_magic_quotes_gpc()==1?stripslashes($text):$text;
	return striplines(htmlentities($text));
}

function post2js($text)
{
	$text=get_magic_quotes_gpc()==1?stripslashes($text):$text;
	// On fout des slashes avant les ", pas les '
	$text=str_replace('"','\"',$text);
	// Et on vire les retours a la ligne.
	$text=str_replace("\r","\\r",$text);
	return str_replace("\n","\\n",$text);
}

function post2text($text)
{
	return get_magic_quotes_gpc()==1?stripslashes($text):$text;
}

function post2bdd($text)
{
	return get_magic_quotes_gpc()==1?$text:addslashes($text);
}

function striplines($text)
{
	$text=str_replace("\r\n","<br />",$text);
	$text=str_replace("\r","<br />",$text);
	return str_replace("\n","<br />",$text);
}
function text2bdd($text)
{
	// Le text a mettre en bdd a juste besoin d'un addslashes.
	return addslashes($text);
}

function text2html($text)
{
	$text=utf8_decode($text);
	// On vire les slashs.
	$text=get_magic_quotes_gpc()==1?stripslashes($text):$text;
	// On vire les balises.
	$text=htmlentities($text);
	// Maintenant on fous des retour a la ligne xhtml.
	return striplines($text);
}

function text2js($text)
{
	// On fout des slashes avant les ", pas les '
	$text=str_replace('"','\"',$text);
	// Et on vire les retours a la ligne.
	$text=str_replace("\r","\\r",$text);
	return str_replace("\n","\\n",$text);

}

function post_on($truc)
{
	return isset($_POST[$truc])?1:0;
}

//**************************************************************
// Fonctions qui permettent de valider l'envoi de formulaire.
//  permet d'eviter les reload intempestif.
//**************************************************************

function form_tokken()
{
	$_SESSION['tokken']=mt_rand();
	return '<input type="hidden" name="tokken" id="tokken" value="'.$_SESSION['tokken'].'" />';
}

function validate_form()
{
	if(!isset($_POST['tokken'],$_SESSION['tokken']) || $_POST['tokken']!=$_SESSION['tokken'])
		unset($_POST);
	else
		unset($_SESSION['tokken']);
}

/*****************************************************************************
Fonctions de creation d'elements de formulaire.
*/
function form_text($label,$name,$size,$onchange,$value='')
{
	set_post($name);
	return '<label for="'.$name.'">'.$label.'</label><input type="text" id="'.$name.'" name="'.$name.'" value="'.($value!==''?$value:post2text($_POST[$name])).'" '.($size?'size="'.$size.'" maxlength="'.$size.'" ':'').''.($onchange?'onchange="'.$onchange.'" ':'').'/>';
}
function form_password($label,$name,$size)
{
	set_post($name);
	return '<label for="'.$name.'">'.$label.'</label><input type="password" id="'.$name.'" name="'.$name.'" value="'.post2text($_POST[$name]).'" />';
}

function form_check($label,$name)
{
	return ($label?'<label for="'.$name.'">'.$label.'</label>':'').'<input type="checkbox" id="'.$name.'" name="'.$name.'" '.(isset($_POST[$name])?'checked="checked" ':'').'/>';
}

//--skapin 08-04-2010-----
function form_radio($label,$groupe,$name)
{
  return ($label?'<label for="'.$name.'">'.$label.'</label>':'').'<input type="radio" value="'.$name.'" name="'.$groupe.'" />';
}
//---end------------------

function form_select($label,$name,$options,$onchange,$value='')
{
	set_post($name);
	$select=($label?'<label for="'.$name.'">'.$label.'</label>':'').'<select id="'.$name.'" name="'.$name.'"'.($onchange?' onchange="'.$onchange.'"':'').'>
		';
	for($i=1;$i<=$options[0];$i++){
		$test=$value?$value:$_POST[$name];
		$select.='<option value="'.$options[$i][0].'"'.($options[$i][0]==$test?' selected="selected"':'').''.(isset($options[$i]['style'])?' style="'.$options[$i]['style'].'"':'').''.(isset($options[$i]['disabled'])?' disabled="disabled"':'').'>'.$options[$i][1].'</option>
			';
	}
	return $select.'</select>';
}

function form_submit($name,$value)
{
	return '<input type="submit" name="'.$name.'" id="'.$name.'" value="'.$value.'" />';
}

function form_hidden($name,$value)
{
	return '<input type="hidden" name="'.$name.'" id="'.$name.'" value="'.$value.'" />';
}

function form_image($label,$name)
{
	return '<label for="'.$name.'">'.$label.'</label><input type="file" name="'.$name.'" id ="'.$name.'" onchange="loadImage(this,\''.$name.'_image\');" /><img src="" alt="" id="'.$name.'_image" />';
}

function form_textarea($label,$name,$rows,$cols)
{
	set_post($name);
	return ($label?'<label for="'.$name.'">'.$label.'</label>':'').'<textarea '.($cols?'cols="'.$cols.'" ':'').($rows?'rows="'.$rows.'" ':'').'name="'.$name.'" id="'.$name.'">'.post2text($_POST[$name]).'</textarea>';
}
function set_post($truc)
{
	$_POST[$truc]=isset($_POST[$truc])?$_POST[$truc]:'';
}

/*
Fonctions de traitement de fichier.
Specialement prevues pour le travail en environnement instable (les serveurs
123go par exemple :p)
*/
function fichier_create($nom_fichier,$content,$replace)
{
	// On verifie si un fichier de ce nom existe deja.
	if(file_exists($nom_fichier))
	{
		if(!$replace) // On ne doit pas remplacer ce fichier
		{
			erreur(0,"Un fichier de ce nom ($nom_fichier) existe d&eacute;j&agrave;.");
			return 0;
		}
		// On a le droit de l'overwriter.
		// On va quand meme le sauvegarder au cas ou.
		if(!copy($nom_fichier,$nom_fichier.'.back'))
		{
			// Impossible de faire une copie, y a un probleme donc on stoppe.
			erreur(0,"Impossible de faire une copie de sauvegarde de ce fichier.");
			return -1;
		}
		@chmod($nom_fichier.'.back', 0666);
		// Ouverture du fichier.
		$file=fopen($nom_fichier,"w");
		if(!$file)
		{
			// Impossible d'ouvrir :(
				erreur(0,"Impossible de cr&eacute;er le fichier.");
				if(!copy($nom_fichier.'.back',$nom_fichier))
				{
					erreur(0,"Impossible de remettre en place le fichier de sauvegarde, il va falloir le faire &agrave; la main.");
					return -3;
				}

				return -2;
			}
			if(!fwrite($file,$content))
			{
				// Impossible d'ecrire, va falloir remettre en place le fichier de
				// sauvegarde, en esperant que cela fonctionne.
				fclose($file);
				@chmod($nom_fichier, 0666); 
				erreur(0,"Impossible d'&eacute;crire le contenu du fichier. On tente de remettre en place la copie de sauvegarde.");
				if(!copy($nom_fichier.'.back',$nom_fichier))
				{
					erreur(0,"Impossible de remettre en place le fichier de sauvegarde, il va falloir le faire &agrave; la main.");
					return -3;
				}
				return -4;
			}
			else
			{
				fclose($file);
				//@chmod($nom_fichier, 0666); 
				return 1;
			}
		}
		else
		{
			// Ouverture du fichier.
			$file=fopen($nom_fichier,"w");
			if(!$file)
			{
				// Impossible d'ouvrir :(
					erreur(0,"Impossible de cr&eacute;er le fichier.");
					return -2;
				}
				if(!fwrite($file,$content))
				{
					// Impossible d'ecrire, va falloir remettre en place le fichier de
					// sauvegarde, en esperant que cela fonctionne.
					fclose($file);
					@chmod($nom_fichier, 0666); 
					erreur(0,"Impossible d'&eacute;crire le contenu du fichier.");
					return -4;
				}
				else
				{
					fclose($file);
					@chmod($nom_fichier, 0666); 
					return 1;
				}
			}
		}

		function fichier_add($nom_fichier,$content)
		{
			// On verifie si un fichier de ce nom existe deja.
			if(file_exists($nom_fichier))
			{
				// On va le sauvegarder au cas ou.
				if(!copy($nom_fichier,$nom_fichier.'.back'))
				{
					// Impossible de faire une copie, y a un probleme donc on stoppe.
					erreur(0,"Impossible de faire une copie de sauvegarde de ce fichier.");
					return -1;
				}
			}
			// Ouverture du fichier.
			$file=fopen($nom_fichier,'a');
			if(!$file)
			{
				erreur(0,"Impossible d'ouvrir le fichier.");
				return -2;
			}
			else
			{
				if(!fwrite($file,$content))
				{
					// Impossible d'ecrire.
					fclose($file);
					erreur(0,"Impossible d'&eacute;crire le contenu du fichier.");
					return -4;
				}
				else
				{
					fclose($file);
					return 1;
				}
			}
		}

/******************************************************
Quelques fonction utiliraires.
*/
function temps($time)
{
	return array('heures'=>floor($time/3600),
		'minutes'=>floor($time%3600/60),
		'secondes'=>$time%60);
}

function start_htmlize($var,$var_name,$fonction)
{
	$str='<h2>'.$var_name.'</h2>
		';
	if(is_array($var))
	{
		$str.='<dl>';
		foreach($var as $key=>$value)
			$str.='<dt>'.$key.'</dt>
			<dd>'.var_htmlize($value,$key,$fonction).'</dd>
			';
		$str.='</dl>
			';
	}
	else
		$str.= '<p>'.$fonction($var).'</p>
		';
	return $str;
}
function var_htmlize($var,$var_name,$fonction)
{
	if(is_array($var))
	{
		$str='<dl>
			';
		foreach($var as $key=>$value)
			$str.='<dt>'.$key.'</dt><dd>'.var_htmlize($value,$key,$fonction).'</dd>
			';
		$str.='</dl>
			';
	}
	else
		$str=$fonction($var);
	return $str;
}

//*****************************************************************************
// Fonction de spawn autour d'un QG. (Entree sur carte ou tubage).
//*****************************************************************************

function spawn($carte,$qg)
{
	global $perso;
	$cases=my_fetch_array('SELECT carte_'.$carte.'.X,
		carte_'.$carte.'.Y
		FROM `carte_'.$carte.'`
		INNER JOIN terrains
		ON terrains.ID=carte_'.$carte.'.terrain
		INNER JOIN qgs
		ON (qgs.camp='.$perso['armee'].'
		OR qgs.type=1) 
		AND '.($qg?'qgs.ID='.$qg:'qgs.carte='.$carte).'
		LEFT OUTER JOIN `ponts`
		ON ponts.X=carte_'.$carte.'.X
		AND ponts.Y=carte_'.$carte.'.Y
		AND ponts.map='.$carte.'
		LEFT OUTER JOIN `terrains` AS pont
		ON terrains.ID=ponts.type
		LEFT OUTER JOIN `persos`
		ON persos.X=carte_'.$carte.'.X
		AND persos.Y=carte_'.$carte.'.Y
		AND persos.map='.$carte.'
		LEFT OUTER JOIN `persos` AS bloqueur
		ON bloqueur.armee!='.$perso['armee'].'
		AND bloqueur.map='.$carte.'
		AND SQRT(POW(qgs.X-bloqueur.X,2)+POW(qgs.Y-bloqueur.Y,2))<=qgs.blocage
		LEFT OUTER JOIN `qgs` AS bloqueur2
		ON bloqueur2.X=carte_'.$carte.'.X
		AND bloqueur2.Y=carte_'.$carte.'.Y
		AND bloqueur2.carte='.$carte.'
		AND bloqueur2.ID!=qgs.ID
		WHERE persos.ID IS NULL
		AND bloqueur.ID IS NULL
		AND bloqueur2.ID IS NULL
		AND qgs.'.($qg?'tubage':'respawn').'=1
		AND SQRT(POW(qgs.X-carte_'.$carte.'.X,2)+POW(qgs.Y-carte_'.$carte.'.Y,2))<=qgs.utilisation
		AND !(carte_'.$carte.'.X=qgs.X AND carte_'.$carte.'.Y=qgs.Y)
		AND ((terrains.prix_'.$perso['type_armure'].'>0 AND ponts.type IS NULL)
		OR (pont.prix_'.$perso['type_armure'].'>0))
		ORDER BY RAND()
		LIMIT 1');
	if($cases[0])
	{
		return $cases[1];
	}
	else
		return 0;  
}
// la fonction de filtrage des ordres
// inspire largement du bbcode
function filtrage_ordre($texte)
{
	//on gicle tout ce qui ressemble de pres ou de loin a des caracteres speciaux HTML comme ca pas de balises pas prevu.
	$texte = htmlentities($texte,ENT_NOQUOTES,"ISO-8859-1");

	//on traite les retours a la lignes:
	$texte = nl2br($texte);

	// les deux tableaux servant a str_replace.
	// on mets toutes les balise dedans, sauf, les url
	// si le serveur est en php5, possibilite de reduire la taille du tableau en utilisant str_ireplace
	// accessoirement ca permettrait aussi de traiter des " betises " dans le genre [CenTEr] ... >_<
	$balise_texte=array('[b]','[B]','[/b]','[/B]',
		'[i]','[I]','[/i]','[/I]',
		'[u]','[U]','[/u]','[/U]',
		'[center]','[CENTER]','[/center]','[/CENTER]');
	$balise_html= array('<b>','<b>','</b>','</b>',
		'<i>','<i>','</i>','</i>',
		'<u>','<u>','</u>','</u>',
		'<center>','<center>','</center>','</center>');
	$texte = str_replace($balise_texte,$balise_html,$texte);
	// on remplace les autres balises
	/*
	nouvelle version, skinnable via CSS

prise en comptes dans les balise des valeurs id="X" et class="X"
pour l'instant ca marcherait tant bien que mal mais ca fonctionne :/
*/

// les balises h*
$texte = preg_replace("/\[(h[1-6]{1})(\s+id=([^(\]| )]+))?(\s+class=([^(\]| )]+))?\]/i","<\${1} id='\${3}' class='\${5}' >",$texte);
$texte = preg_replace("/\[(\/h[1-6]{1})\]/i","<\${1}>",$texte);

//les balises <p> on traite ou pas?
$texte = preg_replace("/\[p\s*(id=(.+))?\s*(class=(.+))?\]/i","<p id='\${2}' class='\${4}' >",$texte);
$texte = preg_replace("/\[\/p\]/i","</p>",$texte);

// on traite les balises span ?
// idem en dessous, va falloir compter les balises ouvertes/fermer...
$texte = preg_replace("/\[span\s*(id=(.+))?\s*(class=(.+))?\]/i","<span id='\${2}' class='\${4}' >",$texte);
$texte = preg_replace("/\[\/span\]/i","</span>",$texte);

//les couleurs
// 16 couleurs accessibles via leur " nom ": aqua, black, blue, fuchsia, gray, green, lime, maroon, navy, olive, purple, red, silver, teal, white, and yellow
// pour les autres faut passer par la valeur hexa.
// necessite de compter les ouvertures de balises...
// possibilite de grouper ca avec le span....
// TODO!

$texte = preg_replace("/\[color=([^\[]+)]/i","<span style='color:\${1};'>",$texte);
$texte = preg_replace("/\[\/color\]/i","</span>",$texte);

// le cas des URL
// il peut y avoir soit [url]une url[/url]
// soit [url=une ur] un nom[/url]

// le premier cas
$texte = preg_replace("/\[url\]([^\[]+)\[\/url\]/i","<a href='\${1}' target='_blank'>\${1}</a>",$texte);
// le second
// aucune verification n'est faite sur le format de l url... mais ca peut etre fait si y a besoin 
$texte = preg_replace("/\[url=([^\]]+)\]([^\[]+)\[\/url\]/i","<a href='\${1}' target='_blank'>\${2}</a>",$texte);

//les images
$texte = preg_replace("/\[img(\s+alt=([^\]]+))?\](http:\/\/[^\[]+)\[\/img\]/i","<img src='\${3}' alt='image externe \${1}' />",$texte);

return $texte;
}

function switch_log($actuel,$ancien,$cookie,$salt='')
{
	if($cookie == 1){
		$acc=my_fetch_array('SELECT confirmation FROM compte WHERE ID='.$ancien);
		if(empty($acc[0]) ||
		$salt != md5($acc[1]['confirmation'])){
			// Compte avec lequel on aurait switche n'existe pas.
			// TODO : ajouter enregistrement de la tentative de cheat
			return;
		}
	}

	// Enregistrement du switch en bdd
	$sql='INSERT INTO switchs(heure,id1,id2,IP,moyen) VALUES(NOW(),'.$actuel.','.$ancien.',"'.$_SERVER['REMOTE_ADDR'].'",'.$cookie.')';
	request($sql);

	// Maintenant, ecriture du joli fichier texte
	$time=time();
	$file_name='../switchs/'.date('d-m-Y',$time).($cookie==1?'':'IP').'.html';
	$content='
		<tr>
		<td>'.date('H:i:s',$time).'</td>
		<td>'.$_SERVER['REMOTE_ADDR'].'</td>';
	// On recup les infos des divers comptes mis en cause.
	$c1=my_fetch_array('SELECT login FROM compte WHERE ID='.$actuel);
	$persos1=my_fetch_array('SELECT persos.ID,
		persos.nom AS nom,
		camps.initiale AS init_camp,
		compagnies.initiales AS init_compa
		FROM persos
		INNER JOIN camps
		ON camps.ID=persos.armee
		INNER JOIN compagnies
		ON compagnies.ID=persos.compagnie
		WHERE compte='.$actuel);
	if(!empty($c1[0])){
		$content.='
			<td>'.bdd2html($c1[1]['login']).'</td>
			<td>';
		if(!empty($persos1[0])){
			$content.='
				<ul>';
			for($i=1;$i<=$persos1[0];$i++){
				$content.='
					<li>'.bdd2html($persos1[$i]['nom']).' ('.bdd2html($persos1[$i]['init_camp']).'-'.bdd2html($persos1[$i]['init_compa']).'-'.bdd2html($persos1[$i]['ID']).')</li>';
			}
			$content.='
				</ul>';
		}
		$content.='
			</td>';
	}
	else{
		$content.='
			<td></td>
			<td>
			</td>';
	}
	$c1=my_fetch_array('SELECT login FROM compte WHERE ID='.$ancien);
	$persos1=my_fetch_array('SELECT persos.ID,
		persos.nom AS nom,
		camps.initiale AS init_camp,
		compagnies.initiales AS init_compa
		FROM persos
		INNER JOIN camps
		ON camps.ID=persos.armee
		INNER JOIN compagnies
		ON compagnies.ID=persos.compagnie
		WHERE compte='.$ancien);
	if(!empty($c1[0])){
		$content.='
			<td>'.bdd2html($c1[1]['login']).'</td>
			<td>';
		if(!empty($persos1[0])){
			$content.='
				<ul>';
			for($i=1;$i<=$persos1[0];$i++){
				$content.='
					<li>'.bdd2html($persos1[$i]['nom']).' ('.bdd2html($persos1[$i]['init_camp']).'-'.bdd2html($persos1[$i]['init_compa']).'-'.bdd2html($persos1[$i]['ID']).')</li>';
			}
			$content.='
				</ul>';
		}
		$content.='
			</td>';
	}
	else{
		$content.='
			<td></td>
			<td>
			</td>';
	}
	if(file_exists($file_name))
		fichier_add($file_name,$content);
	else
		fichier_create($file_name,'<html>
		<head>
		</head>
		<body>
		<style type="text/css">
		table, td, th, tr
	{
		border-collapse:collapse;
		border:solid 1px #666; 
	} 
	</style> 
		<table>
		<tr>
		<th>Heure</th> 
		<th>IP</th> 
		<th>Login</th> 
		<th>Persos</th> 
		<th>Login</th> 
		<th>Persos</th> 
		</tr>
		'.$content,0);
}




function console_log($nom,$phrase,$detail,$compa,$camp)
{
	/*global $perso,$time;
	$nom_perso=$perso['nom'].'( matricule '.$_SESSION['com_perso'].')';
	$posted=start_htmlize($_POST,'$_POST','post2html');
	$file_name='logs/'.$nom.'/'.$compa.'_'.$camp.'_'.date('d-m-Y',$time).'.html';
	$file_name_full='logs/'.$nom.'/'.$compa.'_'.$camp.'_'.date('d-m-Y',$time).'_full.html';
	$file_name_detail='logs/'.$nom.'/'.$compa.'_'.$camp.'_'.date('d-m-Y',$time).'_detail.html';
	$content_light='<tr><td>'.date("d-m-Y &agrave; H:i:s",$time).'</td><td>'.bdd2html($nom_perso).'</td><td>'.$phrase.'</td></tr>
	';
	$content_full='<tr><td>'.date("d-m-Y &agrave; H:i:s",$time).'</td><td>'.bdd2html($nom_perso).'</td><td class="lien">'.addslashes($phrase).'</td><td>'.addslashes($detail).'</td><td>'.$posted.'</td></tr>
	';
	$content_detail='<tr><td>'.date("d-m-Y &agrave; H:i:s",$time).'</td><td>'.bdd2html($nom_perso).'</td><td class="lien">'.addslashes($phrase).'</td><td>'.addslashes($detail).'</td></tr>
	';
	if(file_exists($file_name))
	fichier_add($file_name,$content_light);
	else
	fichier_create($file_name,$content_light,0);
	if(file_exists($file_name_full))
	fichier_add($file_name_full,$content_full);
	else
	fichier_create($file_name_full,$content_full,0);
	if(file_exists($file_name_detail))
	fichier_add($file_name_detail,$content_detail);
	else
	fichier_create($file_name_detail,$content_detail,0);*/
	/*  select_table(2);
	request("INSERT
	INTO logs(`date`,
	`perso`,
	`console`,
	`action`, 
	`compagnie`,
	`camp`,
	`post`)
	VALUES('$time',
	'$_SESSION[com_perso]',
	'$nom',
	'".addslashes($phrase)."',
	'$compa',
	'$camp',
	'".start_htmlize($_POST,'$_POST','post2bdd')."')");
	select_table(0);*/
}

//*****************************************************************************
// check_data : verifie les donnees envoyees par post.
//  renvoie 1 si tout est bon, 0 dans le cas contraire.
// Prend en parametre un tableau qui a cette forme :
//array('numero'=>array('nom', nom de la variable postee
//                      'set', doit etre set
//		      'not_null', ne peut pas etre = 0
//		      'type', type de variable
//		      'mini', valeur mini
//		      'maxi', valeur maxi
//		      'enum', tableau de valeurs possibles
//		      'desc', nom reel de la variable (pour les messages d'erreur
//		      'exist'=>array('table', verifie que ea existe pas deje.
//				     'colonne',
//                                   'exception_colonne',
//                                   'exception_valeur')))
// types possibles : 0 chaene de caracteres,
//  1 : nombre,
//  2 : hexadecimal,
//  3 : valeur dans une liste.
//*****************************************************************************

function check_data($data)
{
	$ok=1;
	$i=0;
	while(isset($data[$i]))
	{
		if(!isset($_POST[$data[$i]['nom']]))
		{
			if($data[$i]['set'])
			{
				$ok=0;
				add_message(3,"Il faut sp&eacute;cifier la donn&eacute;e ".$data[$i]['desc']);
			}
		}
		else
		{
			$donnee=$_POST[$data[$i]['nom']];
			if(!$donnee && $data[$i]['not_null'])
			{
				$ok=0;
				add_message(3,"Il faut donner une valeur &agrave; la donn&eacute;e ".$data[$i]['desc']);
			}
			else
			{
				switch($data[$i]['type'])
				{
					case 0:
					if(is_array($data[$i]['exist']))
					{
						if(exist_in_db("
							SELECT `".$data[$i]['exist']['colonne']."`
							FROM `".$data[$i]['exist']['table']."`
							WHERE `".$data[$i]['exist']['colonne']."`='".post2bdd($donnee)."'".($data[$i]['exist']['exception_colonne']?"
							AND `".$data[$i]['exist']['exception_colonne']."`!='".$data[$i]['exist']['exception_valeur']."'":"")." LIMIT 1"));
						{
							$ok=0;
							add_message(3,"Valeur de ".$data[$i]['desc']." d&eacute;j&agrave; utilis&eacute;e.");
						}
					}
					case 1:
					if(!is_numeric($donnee))
					{
						$ok=0;
						add_message(3,$data[$i]['desc']." doit &ecirc;tre un nombre.");
					}
					else if(($mini||$maxi)&&($donnee<$mini || $donnee>$maxi))
					{
						$ok=0;
						add_message(3,$data[$i]['desc']." doit &ecirc;tre compris entre ".$mini." et ".$maxi." compris.");
					}
					break;
					case 2:
					if(!ctype_xdigit($donnee))
					{
						$ok=0;
						add_message(3,$data[$i]['desc']." doit &ecirc;tre un nombre hexad&eacute;cimal.");
					}
					else if(($mini||$maxi)&&($donnee<$mini || $donnee>$maxi))
					{
						$ok=0;
						add_message(3,$data[$i]['desc']." doit &ecirc;tre compris entre ".$mini." et ".$maxi." compris.");
					}
					break;
					case 3:
					if(!in_array($donnee,$data[$i]['enum']))
					{
						$ok=0;
						add_message(3,"Valeur incorrecte pour ".$data[$i]['desc']);
					}
					break;
					default:
					break;
				}
			}
		}
		$i++;
	}
	return $ok;
}

function calcRepair($PApt){
	global $perso;
	$time=time();
	$PA=min($perso['PA_max']-$perso['PA'],
		(($time-max($perso['date_last_reparation'],
		$perso['date_last_shot'],
		$perso['date_last_bouge']))/$GLOBALS['tour'])*
		$PApt);
	return $PA;
}

/*
Fonction qui sert a gerer le posage de munitions sur une case.
*/
function dropMunitions($ID,$nbr,$X,$Y,$map){
	if(empty($ID) ||
		empty($nbr) ||
	empty($map)){
		return 0;
	}
	if(!is_numeric($ID) ||
		!is_numeric($nbr) ||
		$nbr<=0 ||
		!is_numeric($X) ||
		!is_numeric($Y) ||
	!is_numeric($map)){
		trigger_error('Mauvais parametres pour la fonction dropMunitions, ID='.$ID.', nbr='.$nbr.', map='.$map);
		return -1;
	}
	// Verification de l'existence ou non d'un tas de ce type.
	// Si oui, on update le nombre de munars.
	// Si non, creation d'un tas.
	$sql='SELECT ID 
		FROM equipement 
		WHERE X='.$X.'
		AND Y='.$Y.'
		AND map='.$map.'
		AND `type`=2
		AND possesseur=0
		AND objet_ID='.$ID.'
		LIMIT 1';
	$aterre=my_fetch_array($sql);
	if(!empty($aterre[0])){
		$sql='UPDATE equipement 
			SET nombre=nombre+'.$nbr.',
			dropped='.time().'
			WHERE ID='.$aterre[1]['ID'];
		request($sql);
	}
	else{
		$sql='INSERT INTO equipement 
			(`type`,objet_ID,X,Y,map,nombre,dropped)
			VALUES(2,
				'.$ID.',
				'.$X.',
				'.$Y.',
				'.$map.',
				'.$nbr.',
				'.time().')';
			request($sql);
		}
		return 1;
	}

/*
Fonction qui calcule le cout d'un mouvement.
Parametre : un tableau de 4 cases, un booleen.
Retourne : un tableau contenant le cout en mouvement, le nombre de cases a 
infiltrer, le nombre de paliers changes, une liste de terrain traverses.
Si le mouvement est impossible, retourne 0 comme cout en mouvement plus un 
message d'erreur.
*/

function calcMouv($cases,$diag){
	global $perso;
	// On voit combien de cases sont traversees.
	if(empty($cases[0])){
		return array(0,'Gros bug dans calcMouv');
	}
	for($i=1;$cases[$i]['praticable']!=1 && $i<3;$i++){
		if(empty($cases[$i]['praticable'])){
			// La case ou on souhaite aller n'existe pas.
			return array(0,'Case inexistante');
		}
		if(abs($cases[$i-1]['z_perso']-
		$cases[$i]['z_perso'])>$perso['franchissement']){
			// Probleme de franchissement.
			return array(0,'Impossible de franchir le d&eacute;nivel&eacute;');
		}
		if($cases[$i]['praticable'] == 3){
			if($i>1){
				// QG non prenable.
				return array(0,'Impossible d\'aller sur cette case de QG');
			}
			else{
				break;
			}
		}
	}
	if($i == 3){
		return array(0,'Impossible de s\'infiltrer sur plus de 2 cases');
		}else if($cases[$i]['prix']<=0){
			// Case non praticable.
			return array(0,'Vous ne pouvez aller sur cette case');
		}

		$total=0;
		$infi=$i-1;
		$diffT=0;
		$comp=array();
		/* 
		Maintenant, on va calculer le cout de toutes les cases traversees.
		*/
	for($i=0;$i<=$infi;$i++){
		// On verifie s'il y a une difference de niveau entre les deux cases.
		// Si oui, on calcule le multiplicateur que ca cree.
		$diff=abs($cases[$i]['z_perso']-$cases[$i+1]['z_perso']);
		$diffT+=$diff;
		$mul=1;
		if($diff > 0){
			$mul=1.4142*exp(($diff-1)/3)/comp('escalade',0);
		}
		// Calcul du cout de la premiere demi-case
		$prix=calculCoutDemi($cases[$i],$diag)*$mul;
		if(!empty($comp[$cases[$i]['comp']])){
			$comp[$cases[$i]['comp']]+=$prix;
		}else{
			$comp[$cases[$i]['comp']]=$prix;
		}
		$total+=$prix;
		// Calcul de la seconde demi-case.
		$prix=calculCoutDemi($cases[$i+1],$diag)*$mul;
		if(!empty($comp[$cases[$i+1]['comp']])){
			$comp[$cases[$i+1]['comp']]+=$prix;
		}else{
			$comp[$cases[$i+1]['comp']]=$prix;
		}
		$total+=$prix;
	}
	if($infi > 0){
		$infi_cout=comp('infi','cout');
		$total+=$infi_cout[$infi];
	}
	return array($total,$infi,$diffT,$comp);
}

function calculCoutDemi($lacase,$diag){
	global $perso;
	return $lacase['prix']
		*(1+$perso['malus_terrain_armure']/100)
		*comp($lacase['comp'],'mouv')
		*($perso['camouflage']?comp('camou','cout'):1)
		*($diag?sqrt(2):1)
		*$perso['malus_mouvement']/2;
}

function lastTextList($lvl=2){
	global $perso;
	$camp=empty($perso['armee'])?-1:$perso['armee'];
	$compa=empty($perso['compagnie'])?-1:$perso['compagnie'];
	$mat=empty($_SESSION['com_perso'])?-1:$_SESSION['com_perso'];
	$textes=my_fetch_array('SELECT rp_textes.ID,
		rp_textes.titre,
		persos.nom,
		persos.ID AS mat
		FROM rp_textes
		INNER JOIN persos
		ON persos.ID=rp_textes.auteur
		LEFT JOIN rp_perms
		ON rp_textes.ID=rp_perms.texteID
		WHERE rp_textes.statut=1
		AND (rp_textes.type & 1
		OR rp_textes.auteur='.$mat.'
		OR rp_perms.camp='.$camp.'
		OR rp_perms.compa='.$compa.'
		OR rp_perms.perso='.$mat.')
		AND spec_cat=0
		ORDER BY lastmod DESC
		LIMIT 20
		');
	if($textes[0]>0){
		echo'<h'.$lvl.'>Derniers textes &eacute;crits</h'.$lvl.'>
			<ul>
			';
		for($i=1;$i<=$textes[0];$i++){
			echo' <li><a href="rp.php?act=lire&amp;id='.$textes[$i]['ID'].'">'.bdd2html($textes[$i]['titre']).'</a>';
			if($textes[$i]['mat']>0){
				echo' par <a href="fiche.php?id='.$textes[$i]['mat'].'">'.bdd2html($textes[$i]['nom']).'</a>';
			}
			else{
				//	echo' dans <a href="rp.php?act=lire&amp;uid='.(-$textes[$i]['mat']).'">'.bdd2html($textes[$i]['nom']).'</a>';
			}
			echo'</li>';
		}
		echo'</ul>
			';
	}
}

function getTextInfos($id){
	global $perso;
	$camp=empty($perso['armee'])?-1:$perso['armee'];
	$compa=empty($perso['compagnie'])?-1:$perso['compagnie'];
	$mat=empty($_SESSION['com_perso'])?-1:$_SESSION['com_perso'];
	$texte=my_fetch_array('SELECT rp_textes.titre,
		rp_textes.texte,
		rp_textes.type,
		rp_textes.auteur,
		rp_textes.suitede,
		rp_textes.spec_cat,
		rp_perms.detail,
		persos.nom
		FROM rp_textes
		LEFT JOIN persos
		ON rp_textes.auteur=persos.ID
		LEFT JOIN rp_perms
		ON rp_textes.ID=rp_perms.texteID
		WHERE rp_textes.statut=1
		AND (rp_textes.type & 1
		OR rp_textes.auteur='.$mat.'
		OR rp_perms.camp='.$camp.'
		OR rp_perms.compa='.$compa.'
		OR rp_perms.perso='.$mat.')
		AND rp_textes.ID='.$id);
	if(empty($texte[0])){
		return -1;
	}
	for($i=2;$i<=$texte[0];$i++){
		if($texte[1]['type'] == 0){
			$texte[1]['type']=$texte[$i]['type'];
		}
		else if ($texte[1]['type'] == 1 && $texte[$i]['type'] == 2 ||
			$texte[1]['type'] == 2 && $texte[$i]['type'] == 1 ||
		$texte[$i]['type'] == 3){
			$texte[1]['type']=3;
		}
		$texte[1]['detail']=max($texte[1]['detail'],$texte[$i]['detail']);
	}
	return $texte[1];
}

function getLastTextTime(){
	global $perso;
	$camp=empty($perso['armee'])?-1:$perso['armee'];
	$compa=empty($perso['compagnie'])?-1:$perso['compagnie'];
	$mat=empty($_SESSION['com_perso'])?-1:$_SESSION['com_perso'];
	$textes=my_fetch_array('SELECT UNIX_TIMESTAMP(rp_textes.lastmod) AS a
		FROM rp_textes
		LEFT JOIN rp_perms
		ON rp_textes.ID=rp_perms.texteID
		WHERE rp_textes.statut=1
		AND (rp_textes.type & 1
		OR rp_textes.auteur='.$mat.'
		OR rp_perms.camp='.$camp.'
		OR rp_perms.compa='.$compa.'
		OR rp_perms.perso='.$mat.')
		ORDER BY ecrit DESC
		LIMIT 1');
	if($textes[0]>0){
		return $textes[1]['a'];
	}
	return 0;
}

function canWriteSequel($id){
	global $perso;
	$camp=empty($perso['armee'])?-1:$perso['armee'];
	$compa=empty($perso['compagnie'])?-1:$perso['compagnie'];
	$mat=empty($_SESSION['com_perso'])?-1:$_SESSION['com_perso'];
	$texte=my_fetch_array('SELECT rp_textes.ID,
		FROM rp_textes
		LEFT JOIN rp_perms
		ON rp_textes.ID=rp_perms.texteID
		WHERE rp_textes.statut=1
		AND (rp_textes.type & 1
		OR rp_textes.auteur='.$mat.'
		OR rp_perms.camp='.$camp.'
		OR rp_perms.compa='.$compa.'
		OR rp_perms.perso='.$mat.')
	AND (rp_textes.type & 2
		OR rp_perms.detail & 2
		AND (rp_perms.camp='.$camp.'
		OR rp_perms.compa='.$compa.'
		OR rp_perms.perso='.$mat.'))
		AND rp_textes.ID='.$id.'
		LIMIT 1');
	if(empty($texte[0])){
		return false;
	}
	return true;
}

function getFiche($id){
	global $perso;
	$camp=empty($perso['armee'])?-1:$perso['armee'];
	$compa=empty($perso['compagnie'])?-1:$perso['compagnie'];
	$mat=empty($_SESSION['com_perso'])?-1:$_SESSION['com_perso'];

	$fiche=my_fetch_array('SELECT rp_avatar,
		rp_desc,
		rp_bio
		FROM persos
		WHERE ID='.$id);
	if(empty($fiche[0])){
		return -1;
	}
	$bookmarks=my_fetch_array('SELECT rp_textes.titre,
		rp_textes.ID,
		rp_textes.auteur,
		persos.nom
		FROM rp_textes
		INNER JOIN rp_bookmarks
		ON rp_bookmarks.texteID=rp_textes.ID
		INNER JOIN persos
		ON rp_textes.auteur=persos.ID
		LEFT JOIN rp_perms
		ON rp_textes.ID=rp_perms.texteID
		WHERE rp_textes.statut=1
		AND (rp_textes.type & 1
		OR rp_textes.auteur='.$mat.'
		OR rp_perms.camp='.$camp.'
		OR rp_perms.compa='.$compa.'
		OR rp_perms.perso='.$mat.')
		AND rp_bookmarks.persoID='.$id.'
		ORDER BY rp_textes.ecrit DESC');
	/*
	Attention, truc mechament moche :
	- je hack le retour de my_fetch_array pour ne pas avoir a bidouiller les
	fichier qui utiliseront cette fonction le jour ou on passera tout a la
	version plus recente (et propre) de bdd.php
	*/
$max=$bookmarks[0];
$j=1;
for($i=1;$i<=$max;$i++){
	if($bookmarks[$j]['ID']!=$bookmarks[$i]['ID']){
		$j++;
	}
	$bookmarks[$j-1]=$bookmarks[$i];
}
unset($bookmarks[$max]);
unset($bookmarks[$max+1]);
$fiche[1]['bookmarks']=$bookmarks;
/*
Meme chose pour les textes ecrits par l'utilisateur dont on matte la fiche
*/
$textes=my_fetch_array('SELECT rp_textes.titre,
	rp_textes.ID
	FROM rp_textes
	LEFT JOIN rp_perms
	ON rp_textes.ID=rp_perms.texteID
	WHERE rp_textes.statut=1
	AND (rp_textes.type & 1
	OR rp_textes.auteur='.$mat.'
	OR rp_perms.camp='.$camp.'
	OR rp_perms.compa='.$compa.'
	OR rp_perms.perso='.$mat.')
	AND rp_textes.auteur='.$id.'
	ORDER BY rp_textes.ecrit DESC');
$max=$textes[0];
for($i=1;$i<=$max;$i++){
	$textes[$i-1]=$textes[$i];
}
unset($textes[$max]);
unset($textes[$max+1]);
$fiche[1]['textes']=$textes;

return $fiche[1];
}




function createPerso($name,$gender,$camp,$account=0,$compa=1,$forumPass=0){
	global $perso;
	// Petites verifications.
	if(!is_numeric($gender) ||
		!is_numeric($camp) ||
		!is_numeric($account) ||
	!is_numeric($compa)){
		return 'Erreur de param&egrave;tres';
	}

	$polop=exist_in_db('SELECT ID FROM persos WHERE nom LIKE "'.trim(text2bdd($name)).'" LIMIT 1') || exist_in_db('SELECT ID FROM compte WHERE login LIKE "'.trim(text2bdd($name)).'" LIMIT 1');
	if($polop){
		return 'Nom utilis&eacute;';
	}
	if(!(!empty($perso['armee']) &&
		$perso['armee']==$camp ||
		$camp <= 0 ||
		!empty($perso['admin']['anim_pnjs']) ||
	exist_in_db('SELECT ID FROM camps WHERE ouvert=1 AND ID='.$camp))){
		return 'Erreur de choix d\'arm&eacute;e';
	}
	if($compa != 1 &&
		!(exist_in_db('SELECT ID FROM compagnies WHERE camp='.$camp.' AND ID='.$compa) ||
	exist_in_db('SELECT compa FROM `starting_compa` WHERE `camp`='.$camp.' AND compa='.$compa))){
		return 'Association camp/compagnie impossible';
	}
	// Si le camp est a 0, on tire un camp au hasard.
	if($camp <= 0){
		$camp=my_fetch_array('SELECT ID
			FROM `camps`
			WHERE `ouvert`=\'1\'
			ORDER BY RAND()
			LIMIT 1');
		if(empty($camp[0])){
			return 'Aucun camp n\'accepte de joueur';
		}
		$camp=$camp[1]['ID'];
	}
	// Si dans ce camp, il y des compas de depart, il faut en choisir une
	if($compa == 1){
		$compas=my_fetch_array('SELECT compa
			FROM `starting_compa`
			WHERE `camp`='.$camp.'
			ORDER BY RAND()
			LIMIT 1');
		if(!empty($compas[0])){
			$compa=$compas[1]['compa'];
		}
	}
	// Tests passes avec succes, on cree le perso dans la bdd de CoM.
	$date=time();
	$arme1=0;
	$mun1=0;
	$arme2=0;
	$mun2=0;
	$armure=0;
	$gad1=0;
	$gad2=0;
	$gad3=0;
	$PA=0;
	if(!empty($GLOBALS['default'])){
		$arme1=$GLOBALS['default']['arme1'];
		$arme2=$GLOBALS['default']['arme2'];
		$mun1=$GLOBALS['default']['mun1'];
		$mun2=$GLOBALS['default']['mun2'];
		$armure=$GLOBALS['default']['armure'];
		$PA=$GLOBALS['default']['PA'];
		$gad1=$GLOBALS['default']['g1'];
		$gad2=$GLOBALS['default']['g2'];
		$gad3=$GLOBALS['default']['g3'];
	}
	request('INSERT
		INTO `persos` (`ID`,
		`PV`,
		`PV_max`,
		`PA`,
		`tir_restants`,
		`date_last_tir`,
		`PM`,
		`date_last_PM`,
		`matos_1`,
		`matos_2`,
		`munitions_1`,
		`munitions_2`,
		`gadget_1`,
		`gadget_2`,
		`gadget_3`,
		`armee`,
		`armure`,
		`mouchard`,
		`nom`,
		`arme`,
		`ordres`,
		`implants_dispo`,
		`cloned`,
		rp_genre,
		compte,
		forum_compa,
		`compagnie`)
		VALUES("",
		25,
		25,
		'.$PA.',
		100,
		"'.$date.'",
		100,
		"'.$date.'",
		'.$arme1.',
		'.$arme2.',
		'.$mun1.',
		'.$mun2.',
		'.$gad1.',
		'.$gad2.',
		'.$gad3.',
		'.$camp.',
		'.$armure.',
		1,
		"'.trim(text2bdd($name)).'",
		1,
		1,
		2,
		1,
		'.$gender.',
		'.$account.',
		'.($compa>1?1:0).',
		'.$compa.')');
	$id=last_id();
	// Creation du compte forum a faire ici.
	if(empty($forumPass)){
		if(empty($account)){
			$forumPass=sha1('pnj');
		}
		// TODO : ajouter le cas ou on a pas donne de pass mais qu'on a specifie un compte
	}
	forumNewAccount(trim(text2bdd($name)),$forumPass,$account,$id,$compa,$camp);
	return $id;
}

function delPerso($id){
	request('DELETE FROM persos WHERE ID='.$id);
}
/*
function getEquipmentCapa($lite=false){
global $perso;
return=array('armure'=>0,
'arme'=>0,
'munars'=>0,
'regen'=>0);
if(!($perso['map']||$perso['X']||$perso['Y'])){
if($perso['date_last_shot']<($time-$GLOBALS['tour'])){
$change_armure=1;
}
$change_arme=1;
}

}*/

function catArmure($lvl){
	$str='L&eacute;g&egrave;re';
	if($lvl == 2){
		$str='Moyenne';
	}
	if($lvl == 4){
		$str='Lourde';
	}
	return $str;
}

function camp_LE($a){
	return catArmure(1);
}
function camp_MO($a){
	return catArmure(2);
}
function camp_LO($a){
	return catArmure(4);
}

function activeItem($no,$query='')
{
	$page=str_replace(array($GLOBALS['jeupath'],'.php'),'',$_SERVER['PHP_SELF']);
	if($no == $page){
		if(($no == 'index' || $no == 'rp')){
			if($query == $_SERVER['QUERY_STRING']){
				return ' class="active"';
			}
			else{
				return '';
			}
		}
		return ' class="active"';
	}
	return '';
}

//----Skapin----[09->13]-04-2010--------------
function affiche_commandes($etat,$commandes,$radio=false)
{
	echo '</form>
	<form method="post" action="gene.php?act=marche">
		<table> 
			<tr>
				<th>Numero de comande</th>
				<th>Nom de l\'objet</th> 
				<th>Prix</th>
				<th>Date</th>
				<th>Pass&eacute; Par</th>'
				 ,$radio ?'<th>Annul&eacute; ?</th>':'';
			echo '</tr>';  
		for($i=1;$i<=$commandes[0];$i++) {
			echo '   
			<tr>
				<td>',$commandes[$i]['id'],'</td>
				<td>',$commandes[$i]['nom'],'</td>
				<td>',$commandes[$i]['prix'],'</td>
				<td>',$commandes[$i]['timestamp'],'</td>
				<td><a href="fiche.php?id=',$commandes[$i]['bought_by'],'" target="_fiche">',$commandes[$i]['nom_perso'],'</a></td>';
			if($radio) { echo '<td>',form_radio('','marche_objet_suppr',$commandes[$i]['id']),'</td>'; };
			echo '</tr>'; 
		} 	  
		echo ' 
		</table> 
		 <br />'
		,form_submit('marche_'.$etat.'_ok','Valider'),
	'</form>';
} 

function annuler_commande($marche_objet_suppr) 
{
	$cout=my_fetch_array("SELECT objets.prix, marche_commandes.camp 
						FROM objets JOIN marche_commandes ON objets.ID=marche_commandes.id_objet 
						WHERE marche_commandes.id=".$marche_objet_suppr."");
	$fonds = my_fetch_array('SELECT fonds FROM camps WHERE ID='.$cout[1]['camp'].'');
	request('DELETE FROM marche_commandes 
					WHERE id='.$marche_objet_suppr.'','delete');
	request("UPDATE camps SET fonds=".($fonds[1]['fonds']+$cout[1]['prix'])." WHERE ID=".$cout[1]['camp']."",'update');	
}
//----- END -----------------------


?>
