<?php
  /*
   Fichier definissant les interfaces jeu<=>forum.
   Toutes les fonctions renvoie 0 en cas d'erreur, 1 si tout s'est bien passe.
  */

  // on inclut le fichier de conf du forum:
  // comme ca on a tout le tableau " $INFO[] " et si modif il y a, elle seront repercute automatiquement ici
require_once('forum/conf_global.php');

//pour la gestion des camps et autres joyeusete ( j ai fais les requires a l arrache, faudra verifier les repertoire, surtout que de base globals importe tout

//deux variables en attendant d en savoir plus sur la gestion du bazar...
$nom_forum_general = "Forum RP";
$nom_forum_armee = "Strategie&#33;";

// forum_new_member : cree un nouveau compte de membre sur le forum.
/*
 $id=>ID du compte du joueur.
 $nom=> Nom du premier perso.
 $matricule=> Matricule du premier perso.
 $camp => Id du camp du premier perso (voir '../inits/camps.php' pour en sortir les initiales)
 $login, $pass => logins et mot de passes du compte (donnes direct à partir de $_POST).
*/
function forum_new_member($id,$nom,$matricule,$camp,$login,$pass)
{
  //on passe sur la table du forum...
  select_table(1);
  // si y a un des champs qui est vide => erreur
  if(empty($nom) || empty($login) || empty($pass) || empty($camp) || empty($matricule) || empty($id) || (strtolower($login) == "guest") || (strlen($pass) < 3))
    {
      select_table(0);
      return 0;
    }
	
  //on verifie qu il existe pas deja un utilisateur avec le meme login (les IDs sont unique)
  $sql= "SELECT `id` FROM ibf_members WHERE `name` LIKE '$login'";
  if ((exist_in_db($sql) != 0))
    {
            select_table(0);
      return 0;;
    }

  // on regle les permissions via masque et non pas groupe...
  // recuperation de l id du masque et ajout du membre dans le dit masque.
  if (empty($nom))
    {
            select_table(0);
      return 0;;
    }
	
  $masque = $camp . "_soldats";
	
  $res = my_fetch_array("SELECT  `perm_id` FROM `ibf_forum_perms` WHERE `perm_name` LIKE '$masque'");
  if($res[0] == 0)
    {
            select_table(0);
      return 0;; // pas de masque pour le camp, ca serait louche... => erreur
    }
  else
    {
      $masque_camp = $res[1]['perm_id'];
    }
	
  $mat_full = camp_initiale($camp) . "-0-". $matricule;
	
  $member = array(
		  'id'              => $id,
		  'name'            => $login,
		  'password'        => $pass,
		  'email'           => "no@email.com",
		  'mgroup'          => 3,
		  'posts'           => 0,
		  'avatar'          => 'noavatar',
		  'joined'          => time(),
		  'view_sigs'       => 1,
		  'email_pm'        => 1,
		  'view_img'        => 1,
		  'view_avs'        => 1,
		  'email_pm'	=> 0,
		  'restrict_post'   => 0,
		  'view_pop'        => 1,
		  'vdirs'           => "in:Inbox|sent:Sent Items",
		  'msg_total'       => 0,
		  'new_msg'         => 0,
		  'org_perm_id' => "3," . $masque_camp ,
		  'language'        => "fr",
		  'armee'          => $camp,
		  'pseudo1'         => $nom,
		  'matricule1'	   => $mat_full
		  );
  //on insere le tout dans la table
  request("INSERT INTO `ibf_members` ". do_request($member));
  request("INSERT INTO `ibf_member_extra` (`id`) VALUES ($id)");
  select_table(0);
  return 1;
}

// forum_login : loggue un compte.
// on est loggue pour une heure sur le forum pour jouer sur la duree du login, on peut jouer sur la duree de vie des cookies
// fini
// necessite test aprofondi pour reprerer tout eventuel probleme.
function forum_login($id,$pass,$temp=false)
{
  global $INFO;
  if(empty($pass))
  {
		return 0;
	}
    
  select_table(1);
	
  $member = my_fetch_array("SELECT `id`,`name`,`mgroup`,`password`,`ip_address` FROM `ibf_members` WHERE `id`='$id'");
  if ($member[0] != 1)
	{
		select_table(0);
		return 0; // l'id n'existe pas
	}
  else
	{
		if($member[1]['password'] != "$pass" )
		{
			select_table(0);
			return 0; // le pass est mauvais
		}
    	
		$ip = $_SERVER['REMOTE_ADDR'];
		if ( $member[1]['ip_address'] == "" || $member[1]['ip_address'] == '127.0.0.1' )
		{
			// mise a jour de l IP si on l'avait pas deja
			request("UPDATE ibf_members SET `ip_address`='$ip' WHERE `id`='$id'"); 
		}
		
		// on cree la session
		$poss_session_id = "";
		//on verifie si y a deja une session dans les cookies
		if (isset($_COOKIE[$INFO['cookie_id'].'session_id']))
		{
			$poss_session_id = urldecode($_COOKIE[$INFO['cookie_id'].'session_id']);
		}
		else if(isset($_REQUEST['s']))
		{
			$poss_session_id = $_REQUEST['s'];
		}
    
    //si y a une session, $poss_seession_id existe, on va mettre a jour les infos
		if ($poss_session_id)
		{
			$session_id = $poss_session_id;
			// on vire toute les sessions a partir de l IP recuperer qui n'ont pas le bon identifiant de session.
			request("DELETE FROM `ibf_sessions` WHERE `ip_address`='$ip' AND `id` <> '$session_id'");
			// on mets a jour la bonne session
			request("UPDATE ibf_sessions SET `member_name` = '". $member[1]['name'] ."', `member_id` = '" . $member[1]['id'] ."', `running_time` = '". time() ."', `member_group` = '". $member[1]['mgroup'] ."', `login_type` = '0' WHERE id='$session_id'");
			}
      else
			{
	  		//echo "debug: Creation d'une nouvelle session<br />\n";
	  		// y a pas d identifiant de session on en cree un
	  		$session_id = md5( uniqid(microtime()) );
	  		request("DELETE FROM `ibf_sessions` WHERE `ip_address`='$ip' AND `id` <> '$session_id'");
			
	  		// les valeurs a insere pour cree une session 
	  		$tab = array (
					'id'           => $session_id,
					'member_name'  => $member[1]['name'],
					'member_id'    => $member[1]['id'],
					'running_time' => time(),
					'member_group' => $member[1]['mgroup'],
					'ip_address'   => substr($ip, 0, 50),
					'browser'      => substr($_SERVER['HTTP_USER_AGENT'], 0, 50),
					'login_type'   => 0
				); 
							
	  		request("INSERT INTO `ibf_sessions` " . do_request($tab));
	  		
	  		
	  		//echo "debug: on cree des cookies<br />\n";
	  		$INFO['cookie_domain'] = $INFO['cookie_domain'] == "" ? ""  : $INFO['cookie_domain'];
	  		$INFO['cookie_path']   = $INFO['cookie_path']   == "" ? "/" : $INFO['cookie_path'];
			
	  		// session " longue duree" qui sera efface de tt facon quand on se deloguera.
	  		// 1 heures pour une session me semble "raisonable"
	  		$expires = time() + 60*60*1;
			
	  		setcookie($INFO['cookie_id'].'session_id',$session_id, $expires); //, $INFO['cookie_path'], $INFO['cookie_domain']);
	  		setcookie($INFO['cookie_id']."member_id", $member[1]['id'], $expires); //, $INFO['cookie_path'], $INFO['cookie_domain']);
	  		setcookie($INFO['cookie_id']."pass_hash", $pass, $expires); //, $INFO['cookie_path'], $INFO['cookie_domain']);
			}
    }
  select_table(0);
  return 1;
}

// forum_delog : deloggue un compte.
// COMV2 : suppression des paths/domain => plus de pb de login si on change le pass (bizarre :/ )
function forum_delog($id)
{
  select_table(1);
  global $INFO;
  $session_id = $_COOKIE[$INFO['cookie_id'].'session_id'];
  request("UPDATE ibf_sessions SET member_name='', member_id='0', login_type='0' WHERE id='". $session_id ."'");
  select_table(0);
    
  setcookie($INFO['cookie_id'].'session_id','0', -1); //, $INFO['cookie_path'], $INFO['cookie_domain']);
  setcookie($INFO['cookie_id']."member_id", '0', -1); //, $INFO['cookie_path'], $INFO['cookie_domain']);
  setcookie($INFO['cookie_id']."pass_hash", '0', -1); //, $INFO['cookie_path'], $INFO['cookie_domain']);
  
  return 1;
}

// forum_del_member : detruit un compte forum.
function forum_del_member($id)
{
  select_table(1);
  if (exist_in_db("SELECT `id` FROM `ibf_members` WHERE `id` LIKE '$id'"))
    {
      request("DELETE FROM `ibf_members` WHERE `id` LIKE '$id'");
      request("DELETE FROM `ibf_member_extra` WHERE `id` LIKE '$id'");
      select_table(0);
      return 1;
    }
  else
    {
      // le compte en'existe pas on peut donc pas supprimer.
      select_table(0);
      return 0;
    }
}

// forum_mod_pass : modifie le mot de passe d'un compte.
function forum_mod_pass($id,$new_pass)
{
  // on verifie le pass avant de verifier que le compte existe, on va commencer par les verifs les moins couteuses.
  if (empty($new_pass) && (strlen($new_pass) < 3))
    {
      return 0;
    }
  select_table(1);
  // on verifie que le compte existe maintenant....
  if (exist_in_db("SELECT `id` FROM `ibf_members` WHERE `id` LIKE '$id'"))
    {
      request("UPDATE `ibf_members` SET `password`= '$new_pass' WHERE `id` LIKE '$id'");
      select_table(0);
      return 1;
    }
  else
    {
      // pas de compte donc pas de modif faisable...
      select_table(0);
      return 0;
    }
}

// forum_new_perso : ajoute le nom du second perso à un membre du forum.
function forum_new_perso($id,$nom,$matricule,$camp)
{
  select_table(1);
  $mat_full = camp_initiale($camp) . "-0-". $matricule;
  if (exist_in_db("SELECT `id` FROM `ibf_members` WHERE `id` LIKE '$id'"))
    {
      request("UPDATE `ibf_members` SET `pseudo2`= '$nom', `matricule2` = '$mat_full' WHERE `id` LIKE '$id'");
      select_table(0);
      return 1;
    }
  else
    {
      // le compte n'existe pas
      select_table(0);
      return 0;
    }
}

// forum_mod_perso : mettre a jour les infos du compte des deux soldats
// compa1/2 sont des int et non pas des chaine de caracteres
// $nom2 ,$matricule2 ,$compa1 et $compa2 peuvent ne pas etre renseigner lors de l appel a la fonction.
/*
 valeur possible pour $role:
 0 => soldat " de base "
 1 => em
 2 => gene
 3 => em + gene
 4 => anim
 5 => anim + em
 6 => anim + gene
 7 => anim + gene + em
*/
function forum_mod_perso($id, $role, $camp, $nom1, $matricule1, $compa1='1', $nom2='', $matricule2='', $compa2='1')
{
  if(empty($id) || empty($nom1) || empty($matricule1) || empty($camp))
    {
      // manque des infos essentiels
      return 0;
    }
  //table du forum
  select_table(1);
  if (!exist_in_db("SELECT `id` FROM `ibf_members` WHERE `id` LIKE '$id'"))
    {
      // y a rien dans la table => probleme
      select_table(0);
      return 0;
    }
    
  // matricule pour affichage dans forum
  select_table(0); // table du jeu pour le nom de la compa
  $request = my_fetch_array("SELECT `initiales` FROM `compagnies` WHERE `id` LIKE '$compa1'"); 
  $init_compa1 = $request[1]['initiales'];
  $mat1_full = camp_initiale($camp) . "-" . $init_compa1 . "-" . $matricule1;
    
  //petite verif des infos pour le second perso
  if ($nom2 == '')
    {
      $matricule2 = "";
      $compa2 = "1";
    }
  else
    {
      $request = my_fetch_array("SELECT `initiales` FROM `compagnies` WHERE `id` LIKE '$compa2'");
      $init_compa2 =$request[1]['initiales'];
      $mat2_full = camp_initiale($camp) . "-". $init_compa2 ."-". $matricule2;
    }

  //on repasse sur le forum >_<
  select_table(1);
  //nom du masque des soldats:
  $nom_masque_soldats = $camp . "_soldats";
  // on recupere les masques du camps.
  $soldat = my_fetch_array("SELECT `perm_id` FROM `ibf_forum_perms` WHERE `perm_name` LIKE '". $camp . "_soldats'");
  $id_masque_soldats = $soldat[1]['perm_id'];
  $etatmajor = my_fetch_array("SELECT `perm_id` FROM `ibf_forum_perms` WHERE `perm_name` LIKE '". $camp ."_grades'");
  $id_masque_em = $etatmajor[1]['perm_id'];
  $generaux = my_fetch_array("SELECT `perm_id` FROM `ibf_forum_perms` WHERE `perm_name` LIKE '". $camp ."_genes'");
  $id_masque_gene = $generaux[1]['perm_id'];
	
  // bon alors la on va traiter les differents role possible:
  switch($role)
    {
    case 1:
      $groupe = 3;
      $perm_id = $id_masque_soldats . "," . $id_masque_em;
      break;
    case 2:
      $groupe = 8;
      $perm_id = $id_masque_soldats . "," . $id_masque_gene;
      break;
    case 3:
      $groupe = 8;
      $perm_id = $id_masque_soldats . "," . $id_masque_em . "," . $id_masque_gene;
      break;
    case 4:
      $groupe = 4;
      $perm_id = $id_masque_soldats; // un anim n'est qu un simple soldat de base.
      break;
    case 5:
      $groupe = 4;
      $perm_id = $id_masque_soldats . "," . $id_masque_em;
      break;
    case 6:
      $groupe = 4;
      $perm_id = $id_masque_soldats . "," . $id_masque_gene;
      break;
    case 7:
      $groupe = 4;
      $perm_id = $id_masque_soldats . "," . $id_masque_em . "," . $id_masque_gene;
      break;
    default:
      $groupe = 3;
      $perm_id = $id_masque_soldats;
      break;
    }
  $compagnie1 = $compa1;
  $compagnie2 = $compa2;

  // les masques de compagnies maintenant.
  if($compa1 == $compa2)
    {
      // on traitera qu une fois la meme compa
      $compa2 = '';
    }
  $id_masque_compa1 = '';
  $id_masque_compa2 = '';
  $nom_compa = "_compa";
  if ($compa1 != '')
    {
      $temp = my_fetch_array("SELECT `perm_id` FROM `ibf_forum_perms` WHERE `perm_name` LIKE '${compa1}${nom_compa}'");
      $id_masque_compa1 = $temp[1]['perm_id'];
    }
  if ($compa2 != '')
    {
      $temp = my_fetch_array("SELECT `perm_id` FROM `ibf_forum_perms` WHERE `perm_name` LIKE '${compa2}${nom_compa}'");
      $id_masque_compa2 = $temp[1]['perm_id'];
    }
    
  $masque_compa = $id_masque_compa2 == '' ? $id_masque_compa1 : $id_masque_compa1 .",". $id_masque_compa2;
  $masque = $masque_compa == '' ? $perm_id : $perm_id .",". $masque_compa;

  // on mets tout le bazar a jour
  request("UPDATE `ibf_members` SET 
                    `pseudo1`= '$nom1', 
                    `matricule1` = '$mat1_full', 
                    `pseudo2` = '$nom2', 
                    `matricule2` = '$mat2_full', 
                    `mgroup`='$groupe', 
                    `org_perm_id` = '3,${masque}', 
                    `compa1` = '$compagnie1', 
                    `compa2` = '$compagnie2', 
                    `armee`='$camp' 
                    WHERE `id` = '$id'");
    
  select_table(0);
  return 1;
}

// forum_del_perso1 : supprimer le premier perso d'un compte.
// Le second perso deviens le premier.
function forum_del_perso1($id)
{
  select_table(1);
  $compte = my_fetch_array("SELECT `pseudo2`,`matricule2`,`compa2` FROM `ibf_members` WHERE `id` LIKE '$id'");
  if ($compte[0] == 1)
    {
      $pseudo1 = $compte[1]['pseudo2'];
      $matricule1 = $compte[1]['matricule2'];
      $compa1= $compte[1]['compa2'];
      request("UPDATE `ibf_members` SET `pseudo1`= '$pseudo1', `matricule1` = '$matricule1', `compa1`='$compa1', `pseudo2`= '', `matricule2` = '', `compa2` = '' WHERE `id` LIKE '$id'");
      request("OPTIMIZE TABLE `ibf_members`");
      select_table(0);
      return 1;
    }
  else
    {
      // le compte n'existe pas
      select_table(0);
      return 0;
    }
}

// forum_del_perso2 : supprimer le second perso d'un compte.
function forum_del_perso2($id)
{
  select_table(1);
  if (exist_in_db("SELECT `id` FROM `ibf_members` WHERE `id` LIKE '$id'"))
    {
      request("UPDATE `ibf_members` SET `pseudo2`= '', `matricule2` = '',`compa2` = '' WHERE `id` LIKE '$id'");
      request("OPTIMIZE TABLE `ibf_members`");
      select_table(0);
      return 1;
    }
  else
    {
      // le compte n'existe pas
      select_table(0);
      return 0;
    }
}

// forum_new_camp : cree les forums et les filtres associes a un camp.
// Un forum RP et un forum HRP
// COMV2 : TODO rajouter la possibilité de voir le "G3"
function forum_new_camp($camp,$nom)
{
  select_table(1);
  global $nom_forum_general,$nom_forum_armee;
  if (empty($nom))
    {
      select_table(0);
      return 0;
    }
  else
    {
      //nom du forum RP:
      $description_rp = "Vous trouverez ici tout ce qui a trait a l'histoire de votre camp.<br />Saurez vous ecrire le futur de celui ci?";
      //nom du forum HRP:
      $description_hrp = "Forum de votre camp pour tout ce qui touche a l'organisation de celui ci, strategie, et autre HRP";
      //nom du masque des soldats:
      $nom_masque_soldats = $camp . "_soldats";
      //nom du masque des grades:
      $nom_masque_grades = $camp . "_grades";
      //nom du masque des generaux:
      $nom_masque_genes = $camp . "_genes";
		
      $res = my_fetch_array("SELECT  `perm_id` FROM `ibf_forum_perms` WHERE `perm_name` LIKE '$nom_masque_soldats'");
      if($res[0] != 0)
	{
	  select_table(0);
	  return 0; // deja un camps qui existe avec le meme nom.... => erreur
	}
		
      //on " calcul " le futur id des forums
      $id_forums = my_fetch_array("SELECT MAX(id) as top_forum FROM `ibf_forums`");
      $rp_id = $id_forums[1]['top_forum'] +1;
      $hrp_id = $id_forums[1]['top_forum'] + 2;
      $em_id = $id_forums[1]['top_forum'] + 3;
      $gene_id = $id_forums[1]['top_forum'] +4;
	
      // on recupere l'ID de la categorie dans laquel on va poste le forum
      // ici forum d'un camp => forum generaux
      $cat_id = my_fetch_array("SELECT `id` FROM `ibf_categories` WHERE  `name` LIKE '$nom_forum_general'");
      if($cat_id[0] == 0)
	{
	  select_table(0);
	  return 0;
	}
      $cat_id_rp = $cat_id[1]['id'];
      $cat_id = my_fetch_array("SELECT `id` FROM `ibf_categories` WHERE  `name` LIKE '$nom_forum_armee'");
      if($cat_id[0] == 0)
	{
	  select_table(0);
	  return 0;
	}
      $cat_id_hrp = $cat_id[1]['id'];
	
	
      //masque des groupes...
      //creation du masque associe au camp en question pour les " soldats " de bases:
      if(($nom_masque_soldats != "") && ($nom_masque_grades != "") && ($nom_masque_genes != ""))
	{
	  request("INSERT INTO `ibf_forum_perms` (`perm_name`) VALUES ('$nom_masque_soldats')");
	  $soldat_id_masque = last_id();
	  //masque pour les grades qui pourront acceder a des forums sup (pas definis encore) les forums sup:
	  request("INSERT INTO `ibf_forum_perms` (`perm_name`) VALUES ('$nom_masque_grades')");
	  $grade_id_masque = last_id();
	  request("INSERT INTO `ibf_forum_perms` (`perm_name`) VALUES ('$nom_masque_genes')");
	  $gene_id_masque = last_id();
	}
      else
	{
	  select_table(0);
	  return 0;
	}
	
      // par defaut tout le monde va dans le groupes " soldats "
      // les generaux ont un masque a part mais qui est "global" (cad pour tout les camps) et c'est ce dernier qui donne qq droit de moderations
      // il faudra bien sur a chaque creation de camps, ajoute les forum du dit camps dans la liste des forums pouvant etre moderer par les generaux.

	
      // insertion de la regle pour les modos
      $modo = array(
		    'forum_id'      		  => $rp_id				, // l ID du forum a moderer
		    'member_name'   	      => -1					, // -1 si c'est un groupe
		    'member_id'     	      => -1					, // idem au dessus
		    'edit_post'     		  => 1				    , // 1 pour oui, 0 pour non etc etc
		    'edit_topic'    		  => 1					,
		    'delete_post'   	      => 1					,
		    'delete_topic'		      => 1					,
		    'view_ip'       		  => 0					,
		    'open_topic'    	      => 1					,
		    'close_topic'   		  => 1					,
		    'mass_move'    	          => 0					,
		    'mass_prune'		      => 0                  ,
		    'move_topic'		      => 1                  ,
		    'pin_topic'	    	      => 1         			,
		    'unpin_topic'		      => 1         			,
		    'post_q'        		=> 0        , // je sais plus ce que ca fait donc je met a non par default
		    'topic_q'       		=> 0        , // je sais plus ce que ca fait donc je met a non par default
		    'allow_warn'    		=> 0                    			,
		    'edit_user'     		=> 0                    			,
		    'is_group'      		=> 1       , // si c'est un groupe qui modere on mets 1, 0 sinon
		    'group_id'      		=> 8     	, // l'id du groupedes generaux. a changer pour qu il soit recuperer dynamiquement...
		    'group_name'		=> "G&eacute;neraux"	, // le nom du group, pour les camps, ca sera donc "generaux"
		    'split_merge'		=> 1                    			, // separer/fusionner les topics
		    'can_mm'        		=> 0   , // moderation rapide si je me souviens bien dans le doute je met a non
		    'armee'			=> $camp
		    );
	
      //le forum RP
      // verification qu il n'y ait pas deja les gene en modo pour ce forum ( pas d'insertion en double.... )
      if(!exist_in_db("SELECT `group_id` FROM `ibf_moderators` WHERE `forum_id` LIKE '$rp_id'"))
	{
	  request("INSERT INTO `ibf_moderators` " .do_request($modo));
	}
      //le forum hrp
      $modo['forum_id'] = $hrp_id;
      if(!exist_in_db("SELECT `group_id` FROM `ibf_moderators` WHERE `forum_id` LIKE '$hrp_id'"))
	{
	  request("INSERT INTO `ibf_moderators` " .do_request($modo));
	}
      // le forum d'em
      $modo['forum_id'] = $em_id;
      if(!exist_in_db("SELECT `group_id` FROM `ibf_moderators` WHERE `forum_id` LIKE '$em_id'"))
	{
	  request("INSERT INTO `ibf_moderators` " .do_request($modo));
	}
      // le forums des genes
      $modo['forum_id'] = $gene_id;
      if(!exist_in_db("SELECT `group_id` FROM `ibf_moderators` WHERE `forum_id` LIKE '$gene_id'"))
	{
	  request("INSERT INTO `ibf_moderators` " .do_request($modo));
	}
      //logiquement la on en a fini avec la moderation.
	
      // on construit la requette d insertion du forum
      /*
       forum RP => on compte les postes, pas de sondages, position numero 2 (3 == forum de compa histoire d'avoir un semblant d organisation)
       forum HRP => on compte po, sondages possibles, position numero 2 (3 == forum de compa histoire d'avoir un semblant d organisation)
      */
      $forum = array (
		      'id'               => $rp_id,
		      'position'         => 2,
		      'topics'           => 0,
		      'posts'            => 0,
		      'last_post'        => "",
		      'last_poster_id'   => "",
		      'last_poster_name' => "",
		      'name'             => $nom,
		      'description'      => addslashes($description_rp),
		      'use_ibc'          => 1,
		      'use_html'         => 0,
		      'start_perms'      => $soldat_id_masque,
		      'reply_perms'      => $soldat_id_masque,
		      'read_perms'       => $soldat_id_masque,
		      'upload_perms'     => "",
		      'password'         => '',
		      'category'         => $cat_id_rp,
		      'last_id'          => "",
		      'last_title'       => "",
		      'show_rules'       => 0,
		      'preview_posts'    => 0,
		      'allow_poll'       => 0,
		      'allow_pollbump'   => 0,
		      'inc_postcount'    => 1,
		      'quick_reply'      => 1,
		      'notify_modq_emails'=> 0,
		      'status'            =>1,
		      'armee' => $camp
		      ) ;
      // le forum RP du camp
      $insert = "INSERT INTO `ibf_forums` " . do_request($forum);
      request($insert);
	
      //le forum hrp du camp
      $forum['id'] = $hrp_id;
      $forum['description'] = addslashes($description_hrp);
      $forum['category'] = $cat_id_hrp;
      $insert = "INSERT INTO `ibf_forums` " . do_request($forum);
      request($insert);
	
      // un forum d'etat major pour le camp?
      $forum['id'] = $em_id;
      $forum['name'] = "${nom}: Forum de l\'etat major";
      $forum['description'] = addslashes("Forum &agrave; acc&egrave;s restreint vous permettant de parler de choses sensibles tout en controlant qui a acces &agrave; l'information");
      $forum['category'] = $cat_id_hrp;
      $forum['read_perms' ] = $grade_id_masque .",". $gene_id_masque;
      $forum['reply_perms' ] = $grade_id_masque .",". $gene_id_masque;
      $forum['start_perms' ] = $grade_id_masque .",". $gene_id_masque;
      $forum['position' ] = 1;
      $insert = "INSERT INTO `ibf_forums` " . do_request($forum);
      request($insert);
	
	
      // un forum pour les generaux de chaque camp?
      $forum['id'] = $gene_id;
      $forum['name'] = "${nom}: Forum des G&eacute;n&eacute;raux";
      $forum['description'] = "Forum r&eacute;serv&eacute; aux g&eacute;n&eacute;raux";
      $forum['category'] = $cat_id_hrp;
      $forum['read_perms' ] = $gene_id_masque;
      $forum['reply_perms' ] = $gene_id_masque;
      $forum['start_perms' ] = $gene_id_masque;
      $forum['position' ] = 1;
      $insert = "INSERT INTO `ibf_forums` " . do_request($forum);
      request($insert);	
      //et hop theoriquement on a de jolis forums de camps
			
			//Forum du G3!
			// TODO: A tester!
			$g3 = my_fetch_array("SELECT `start_perms`,`reply_perms`,`read_perms` FROM `ibf_forums` WHERE `id` = '6'");
			
			$start = $g3[1]['start_perms'] .",". $gene_id_masque;
			$reply = $g3[1]['reply_perms'] .",". $gene_id_masque;
			$read = $g3[1]['read_perms'] .",". $gene_id_masque;
			
			request("UPDATE `ibf_forums` SET `start_perms` = '". $start ."',`reply_perms` = '". $reply ."',`read_perms` = '". $read ."' WHERE `id` = 6");
			//
			
			
      select_table(0);

      return 1;
    }
}


// forum_mod_camp : modifie les noms des forums d'un camp.
// il ne faut pas que le nom du camps change sinon j ai rien a quoi me raccrocher pour le camps :/ sauf la couleur?( a demander...)
function forum_mod_camp($camp,$new_nom)
{
  select_table(1);
  $nom = camp_nom($camp);
  if (empty($nom))
    {
      select_table(0);
      return 0;
    }
  $requete = "SELECT `id` FROM `ibf_forums` WHERE `armee` LIKE '$camp' ";
  $id = my_fetch_array($requete);
  if($id[0] == 1)
    {
      request("UPDATE `ibf_forums` SET `name` = '$new_nom' WHERE `id` LIKE ". $id[1]['id']);
      select_table(0);
      return 1;	
    }
  else
    {
      select_table(0);
      return 0;
    }
}

// forum_del_camp : detruit les forums d'un camp.
// reste a reflechir sur le probleme de la demolition des compagnie....
// act, 1 champ sup pourles forums => armee, qui sert soit a mettre le nom de l armee, soit le numero de la compa.
// modification de ca, pour => armee = uniquement le nom de l armee
// nouveau champ, compa =  id de la compa
// ce qui revient a dire qu a chaque fois qu on cree un forum de compa, on renseigne a la fois les champs armee et compa (sauf qu act, a la creation dune compa j ai pas l armee associe :/ )
// a la suppression, vu qu il faudra supprimer les modos des forums de compa, il va falloir dans un premier temps recuperer l ID de tous les forums de compa
// supprimer les modo associer a chaque compa
// puis supprimer les forums.
function forum_del_camp($camp,$nouveau_camp)
{
  select_table(1);
  if($camp == $nouveau_camp)
    {
      select_table(0);
      return 0;
    }		
  $nom = camp_nom($camp);
  if (empty($nom))
    {
      select_table(0);
      return 0;
    }
	
  if(exist_in_db("SELECT `id` FROM `ibf_forums` WHERE `armee` LIKE '$camp'"))
    {
      //nom du masque des soldats:
      $nom_masque_soldats = $camp . "_soldats";
      //nom du masque des grades:
      $nom_masque_grades = $camp . "_grades";
      //nom du masque des genes
      $nom_masque_genes = $camp . "_genes";
		
      //on recupere l'id de ce masque. ensuite tous les membres ayant ce masque dans leur droit prendront le nouveau masque
      $res = my_fetch_array("SELECT `perm_id` FROM `ibf_forum_perms` WHERE `perm_name` LIKE '$nom_masque_soldats'");
      $id_old_camp= $res[1]['perm_id'];
      $res = my_fetch_array("SELECT `perm_id` FROM `ibf_forum_perms` WHERE `perm_name` LIKE '". $nouveau_camp ."_soldats'");
      $id_new_camp= $res[1]['perm_id'];
		
      //reste a effacer toutes les compas et a expatrier les joueurs vers une nouvelle armee :x
      request("UPDATE `ibf_members` SET `org_perm_id` = '3,". $id_new_camp ."' WHERE `armee` LIKE '$camp'");
		
      //On degage tous les posts
      // meme ceux des compas.
      $res = my_fetch_array("SELECT `id` FROM `ibf_forums` WHERE `armee` LIKE '$camp'");
      for ($i=1; $i<=$res[0]; $i++)
	{
	  request("DELETE FROM `ibf_posts` WHERE `forum_id` LIKE '". $res[$i]['id'] ."'");
	}
		
      request("DELETE FROM `ibf_forums` WHERE `armee` LIKE '$camp'"); // doit detruire 2/3 forums + tous les forums de compa
      request("DELETE FROM `ibf_forum_perms` WHERE `perm_name` LIKE  '$nom_masque_soldats' OR `perm_name` LIKE '$nom_masque_grades' OR `perm_name` LIKE '$nom_masque_genes'");
		
      // effacement des modo
      request("DELETE FROM `ibf_moderators` WHERE `armee` LIKE '$camp'");
		
      request("OPTIMIZE TABLE `ibf_moderators`");
      request("OPTIMIZE TABLE `ibf_forum_perms`");
      request("OPTIMIZE TABLE `ibf_posts`");
      request("OPTIMIZE TABLE `ibf_topics`");
      select_table(0);
      return 1;
    }
  else
    {
      select_table(0);
      return 0;
    }
}

// forum_mod_desc_camp: modifie la description d'un forum associe a un camp
// $bool = true signifie que l'on modifie le forum RP du camp
// $bool = false signifie que l on modifie le forum HRP du camp
function forum_mod_desc_camp($camp,$desc,$bool)
{
  select_table(1);
  $nom = camp_nom($camp);
  if (empty($nom))
    {
      select_table(0);
      return 0;
    }
	
  if(exist_in_db("SELECT `id` FROM `ibf_forums` WHERE `armee` LIKE '$camp'"))
    {
      if($bool)
       	{
	  $cat_id = my_fetch_array("SELECT `id` FROM `ibf_categories` WHERE  `name` LIKE '$nom_forum_general'");
	  $cat = $cat_id[1]['id'];
       	}
      else
       	{
	  $cat_id = my_fetch_array("SELECT `id` FROM `ibf_categories` WHERE  `name` LIKE '$nom_forum_armee'");
	  $cat = $cat_id[1]['id'];
       	}

      request("UPDATE `ibf_forums` SET `description` = '$desc'  WHERE `armee` LIKE '$camp' AND `category` LIKE '$cat' AND `position` LIKE '2'");
      select_table(0);
      return 1;	
    }
  else
    {
      select_table(0);
      return 0;
    }
}

// forum_new_compa : cree les forum RP et HRP d'une compagnie ainsi que les masques associes.
// theoriquement => fini
function forum_new_compa($id,$nom,$camp)
{
  global $nom_forum_general,$nom_forum_armee;
  select_table(1);
  if(empty($nom) || empty($id) || empty($camp))
    {
      select_table(0);
      return 0;
    }
  else
    {
      //$nom = addslashes($nom);
      //nom du forum RP:
      $description_rp = "Vous trouverez ici tout ce qui a trait au RP de votre compagnie.";
      //nom du forum HRP:
      $description_hrp = "Forum de votre compa pour tout ce qui touche a l organisation de celui ci, strategie, et autre HRP";
      //nom du masque des soldats:
      $nom_masque_soldats = $id . "_compa";
    }
  //on " calcul " le futur id des forums
  $id_forum = my_fetch_array("SELECT MAX(id) as `top_forum` FROM `ibf_forums`");
  $rp_id  = $id_forum[1]['top_forum'] + 1;
  $hrp_id = $id_forum[1]['top_forum'] + 2;
	
  //pas de groupe de compagnie, donc le groupe sera 3, a savoir soldat => rajout " a la main " du colo, ou plus exactement via fonction separe a coder.......
  $id_groupe = 3;

	
  //creation du masque associe a la compa, pour permettre a seulement certain membres d'acceder a leurs forums
  request("INSERT INTO `ibf_forum_perms` (`perm_name`) VALUES ('$nom_masque_soldats')");
  $membre_compa = last_id();

	
  //on recupere l'ID de la categorie dans laquel on va poste le forum
  // ici forum d'un camp => forum generaux
	
  $cat_id = my_fetch_array("SELECT `id` FROM `ibf_categories` WHERE  `name` LIKE '$nom_forum_general'");
  if($cat_id[0] == 0)
    {
      select_table(0);
      return 0;
    }
  $cat_id_rp = $cat_id[1]['id'];
  //echo "$cat_id_rp <br />";
  $cat_id = my_fetch_array("SELECT `id` FROM `ibf_categories` WHERE  `name` LIKE '$nom_forum_armee'");
  if($cat_id[0] == 0)
    {
      select_table(0);
      return 0;
    }
  $cat_id_hrp = $cat_id[1]['id'];
  //echo "$cat_id_hrp <br />";	
	
  //on construit la requette d insertion du forum
  /*
   forum RP => on compte les postes, pas de sondages, position numero 3 (2 == forum de compa histoire d avoir un semblant d organisation)
   forum HRP => on compte po, sondages possibles, position numero 3 (2 == forum de compa histoire d avoir un semblant d organisation)
  */
  $forum = array (
		  'id'                => $rp_id,
		  'position'          => 3,
		  'topics'            => 0,
		  'posts'             => 0,
		  'last_post'         => "",
		  'last_poster_id'    => "",
		  'last_poster_name'  => "",
		  'name'              => $nom,
		  'description'       => $description_rp,
		  'use_ibc'           => 1,
		  'use_html'          => 0,
		  'start_perms'       => $membre_compa,
		  'reply_perms'       => $membre_compa,
		  'read_perms'        => $membre_compa,
		  'upload_perms'      => "",
		  'password'          => '',
		  'category'          => $cat_id_rp,
		  'last_id'           => "",
		  'last_title'        => "",
		  'show_rules'        => 0,
		  'preview_posts'     => 0,
		  'allow_poll'        => 0,
		  'allow_pollbump'    => 0,
		  'inc_postcount'     => 1,
		  'quick_reply'       => 1,
		  'notify_modq_emails'=> 0,
		  'status'            => 1,
		  'armee'             => $camp,
		  'compa'		=> $id
		  ) ;
  //le forum RP
  request("INSERT INTO `ibf_forums` " . do_request($forum));
	
  //le forum hrp
  $forum['id'] = $hrp_id;
  $forum['category'] = $cat_id_hrp;
  $forum['description'] = $description_hrp;
  request("INSERT INTO `ibf_forums` " . do_request($forum));
  //Attention!
  // a ce stade il n'y a aucun moderateur, il faut au minimum appele la fonction qui ajoute des modo pour une compa, avec le colonel!
  select_table(0);
  return 1;
}

// forum_mod_compa : pour changer le nom d'une compagnie
// logiquement pas de soucis ici, ca devrait marcher sans pb.
// reste a verifier les cas que j ai pas prevue...
function forum_mod_compa($id,$new_nom)
{
  if (empty($id))
    {
      return 0;
    }
  select_table(1);
	
  //poum mise a jour des noms
  if(exist_in_db("SELECT `id` FROM `ibf_forums` WHERE `compa` LIKE '$id'"))
    {
      request("UPDATE `ibf_forums` SET `name`='$new_nom' WHERE `compa` LIKE '$id'");
    }
  else
    {
      select_table(0);
      return 0;
    }
  select_table(0);
}

// forum_del_compa : Supprime une compagnie et les masques associes
function forum_del_compa($id)
{
  select_table(1);
  if (empty($id))
    {
      select_table(0);
      return 0;
    }
  $requete = my_fetch_array("SELECT `id` FROM `ibf_forums` WHERE `compa` LIKE '$id'");
  if($requete[0] != 0)
    {
      /*
       bon c pas beau, mais je sais pas encore comment je vais faire ca :x
       en gros la je recupere le matricule qui est sous la forme XXX-YYY-ZZZZ
       je remplace le YYY par 0.
       c pas efficace :/
            
       va falloir que je reflechisse a qq chose de plus  surtout que je voulais pas refaire un stockage d initial de la compa
       ni faire une lecture de la table des compa pendant l'affichage des posts mais ca risque d'arriver :'( .
      */
      $request = my_fetch_array("SELECT `id`,`armee`,`matricule1` FROM `ibf_members` WHERE `compa1` LIKE '$id'");
      for($i=1; $i <= $request[0]; $i++)
	{
	  $mat_full1 = preg_replace("/^([a-z]+)-[a-z0-9]+-([0-9]+)$/i","\${1}-0-\${2}",$request[$i]['matricule1']);
	  request("UPDATE `ibf_members` SET `compa1`='1', `matricule1`='$mat_full1' WHERE `id` LIKE '". $request[$i]['id'] ."'");
	}
            
      $request = my_fetch_array("SELECT `id`,`armee`,`matricule2` FROM `ibf_members` WHERE `compa2` LIKE '$id'");
      for($i=1; $i <= $request[0]; $i++)
	{
	  $mat_full2 = preg_replace("/^([a-z]+)-[a-z0-9]+-([0-9]+)$/i","\${1}-0-\${2}",$request[$i]['matricule2']);
	  request("UPDATE `ibf_members` SET `compa2`='1', `matricule2`='$mat_full2' WHERE `id` LIKE '". $request[$i]['id'] ."'");
	}
            
      request("UPDATE `ibf_members` SET `compa1`='1' WHERE `compa1` LIKE '$id'");
      request("UPDATE `ibf_members` SET `compa2`='1' WHERE `compa2` LIKE '$id'");
      request("DELETE FROM `ibf_posts` WHERE `forum_id` LIKE '". $requete[1]['id'] ."'");
      request("DELETE FROM `ibf_forums` WHERE `compa` LIKE '$id'");
            
      // on vire les modos
      $modo = $id . "_";
      request("DELETE FROM `ibf_moderators` WHERE `compa` LIKE '${modo}%'");
      request("OPTIMIZE TABLE `ibf_forums`");
      request("OPTIMIZE TABLE `ibf_members`");
      request("OPTIMIZE TABLE `ibf_posts`");
      select_table(0);
        
      return 1;	
    }
  else
    {
      select_table(0);
      return 0;
    }
}

//forum_compa_modo
/*
 $bool => true = ajout
 $bool => false = suppression
*/
function forum_compa_modo($id,$id_modo,$bool)
{
    
  if(!isset($id) || !isset($id_modo) || !isset($bool))
    {
      return 0;
    }
    
  select_table(1);
  $request = my_fetch_array("SELECT `name` FROM `ibf_members` WHERE `id`='$id_modo'");
  $name = $request[1]['name'];
  if($bool)
    {
      if(!exist_in_db("SELECT `mid` FROM `ibf_moderators` WHERE `compa` LIKE '". $id . "_" . $id_modo."'"))
        {
	  $modo = array(
			'forum_id'=>0, // l ID du forum a moderer
			'member_name'   	    => $name				 , // -1 si c'est un groupe
			'member_id'     	    => $id_modo			     , // idem au dessus
			'edit_post'     		=> 1				     , // 1 pour oui, 0 pour non etc etc
			'edit_topic'    		=> 1					 ,
			'delete_post'   	    => 1					 ,
			'delete_topic'		    => 1					 ,
			'view_ip'       		=> 0					 ,
			'open_topic'    	    => 1					 ,
			'close_topic'   		=> 1					 ,
			'mass_move'    	        => 0					 ,
			'mass_prune'		    => 0                     ,
			'move_topic'		    => 1                     ,
			'pin_topic'	    	    => 1         			 ,
			'unpin_topic'		    => 1         			 ,
			'post_q'        		=> 0                     , // je sais plus ce que ca fait donc je met a non par default
			'topic_q'       		=> 0                     , // je sais plus ce que ca fait donc je met a non par default
			'allow_warn'    		=> 0                     ,
			'edit_user'     		=> 0               		 ,
			'is_group'      		=> 0                     , // si c'est un groupe qui modere on mets 1, 0 sinon
			'split_merge'		    => 1                     , // separer/fusionner les topics
			'can_mm'        		=> 0                     , // moderation rapide si je me souviens bien dans le doute je met a non
		        'compa'			        => $id . "_" . $id_modo
			);   
        
	  $id = my_fetch_array("SELECT `id` FROM `ibf_forums` WHERE `compa` LIKE '$id'");
	  for($i=1; $i <= $id[0]; $i++)
            {
	      $modo['forum_id'] = $id[$i]['id'];
	      request("INSERT INTO `ibf_moderators` " .do_request($modo));
            }
	  select_table(0);
	  return 1;
        }
      //il est deja modo donc on ne rajoute pas...
      select_table(0);
      return 1;
    }
  else
    {
      $modo = $id . "_" . $id_modo ;
      request("DELETE FROM `ibf_moderators` WHERE `compa` LIKE '$modo'");
      request("OPTIMIZE TABLE `ibf_moderators`");
      select_table(0);
      return 1;
    }
    
}

function forum_sacs($compte)
{
  select_table(1);
  // Mettre la requête là.
  $id = my_fetch_array("SELECT COUNT(*) FROM `ibf_messages` WHERE `member_id`LIKE ". $compte ." AND `read_state` LIKE 0");
  $nbr=$id[1][0];
  select_table(0);
  return $nbr;
}

// fournit l'url sur laquelle il faut se rediriger pour rediger un ultra multisac
function forum_sac_compa($id_compa)
{
	select_table(1);
		$sql = my_fetch_array("SELECT `pseudo1` FROM `ibf_members` WHERE `compa1` LIKE ". $id_compa ." OR `compa2` LIKE ". $id_compa );
		$destinataires = "";

		while($sql[0]--)
		{
			$destinataires .= $sql[$id]['pseudo1'] . ",";
		}
		
		$destinataires = rtrim($destinataires,",");
		
		select_table(0);
		
		// on fait un coup de header???
		// remarque que si tu utilise les trucs de compressions de flux, peut etre que le header suffira.
		header("Location: http://". $_SERVER["SERVER_NAME"] . dirname($_SERVER["REQUEST_URI"]) ."/forum/index.php?CODE=04&act=Msg&multisac=" . $destinataires );
		//return "forum/index.php?CODE=04&act=Msg&multisac=". $destinataires;
}

// les "bonus" que je savais pas ou metttres....

// cette fonction me cree une partie de la requette d'insertion a partir d un tableau.
// les cles du tableau doivent correspondre aux colonnes de la table dans laquelle on souhaite inserer les donnees
// les valeurs, bah ce sont les valeurs que l on veut inserer.
// /me est une feignasse :p
function do_request($tab)
{
  if(is_array($tab))
    {
      $col = "";
      $val =  " VALUES (";
      $bool = false;
      foreach($tab as $key => $valeur)
      {
	   if($bool)
	   {
	       $col = $col . ",";
	       $val = $val . ",";
	   }
	   else
	   {
	       $bool = !$bool;
	   }
            
	   $col = $col . "`" . $key ."`";
	   $val = $val . "'" . $valeur ."'";
      }
      $requete = "(". $col . ")" . $val .")";
      return $requete;
    }
    else
    {
      echo "ERREUR! do_request() n'est utilisable que sur les tableaux!";
      exit;
    }
}
?>