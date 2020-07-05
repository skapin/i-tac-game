<?php
// Version: 1.1; index

/*	This template is, perhaps, the most important template in the theme. It
	contains the main template layer that displays the header and footer of
	the forum, namely with main_above and main_below. It also contains the
	menu sub template, which appropriately displays the menu; the init sub
	template, which is there to set the theme up; (init can be missing.) and
	the linktree sub template, which sorts out the link tree.

	The init sub template should load any data and set any hardcoded options.

	The main_above sub template is what is shown above the main content, and
	should contain anything that should be shown up there.

	The main_below sub template, conversely, is shown after the main content.
	It should probably contain the copyright statement and some other things.

	The linktree sub template should display the link tree, using the data
	in the $context['linktree'] variable.

	The menu sub template should display all the relevant buttons the user
	wants and or needs.

	For more information on the templating system, please see the site at:
	http://www.simplemachines.org/
*/

// Initialize the template... mainly little settings.
function template_init()
{
	global $context, $settings, $options, $txt;

	/* Use images from default theme when using templates from the default theme?
		if this is 'always', images from the default theme will be used.
		if this is 'defaults', images from the default theme will only be used with default templates.
		if this is 'never' or isn't set at all, images from the default theme will not be used. */
	$settings['use_default_images'] = 'never';

	/* What document type definition is being used? (for font size and other issues.)
		'xhtml' for an XHTML 1.0 document type definition.
		'html' for an HTML 4.01 document type definition. */
	$settings['doctype'] = 'xhtml';

	/* The version this template/theme is for.
		This should probably be the version of SMF it was created for. */
	$settings['theme_version'] = '1.1';

	/* Set a setting that tells the theme that it can render the tabs. */
	$settings['use_tabs'] = true;

	/* Use plain buttons - as oppossed to text buttons? */
	$settings['use_buttons'] = true;

	/* Show sticky and lock status seperate from topic icons? */
	$settings['seperate_sticky_lock'] = true;
}

// The main sub template above the content.
function template_main_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Show right to left and the character set for ease of translating.
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '><head>
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<meta name="description" content="', $context['page_title'], '" />', empty($context['robot_no_index']) ? '' : '
	<meta name="robots" content="noindex" />', '
	<meta name="keywords" content="PHP, MySQL, bulletin, board, free, open, source, smf, simple, machines, forum" />
	<script language="JavaScript" type="text/javascript" src="', $settings['default_theme_url'], '/script.js?fin11"></script>
	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
		var smf_scripturl = "', $scripturl, '";
		var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
		var smf_charset = "', $context['character_set'], '";
	// ]]></script>
	<title>', $context['page_title'], '</title>';

	// The ?fin11 part of this link is just here to make sure browsers don't cache it wrongly.
	//	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/style.css?fin11" />

	echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/print.css?fin11" media="print" />
	<link rel="stylesheet" type="text/css" href="../styles/itac/forum.css?fin11" />
';

	/* Internet Explorer 4/5 and Opera 6 just don't do font sizes properly. (they are big...)
		Thus, in Internet Explorer 4, 5, and Opera 6 this will show fonts one size smaller than usual.
		Note that this is affected by whether IE 6 is in standards compliance mode.. if not, it will also be big.
		Standards compliance mode happens when you use xhtml... */
	if ($context['browser']['needs_size_fix'])
		echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/fonts-compat.css" />';

	// Show all the relative links, such as help, search, contents, and the like.
	echo '
	<link rel="help" href="', $scripturl, '?action=help" target="_blank" />
	<link rel="search" href="' . $scripturl . '?action=search" />
	<link rel="contents" href="', $scripturl, '" />';

	// If RSS feeds are enabled, advertise the presence of one.
	if (!empty($modSettings['xmlnews_enable']))
		echo '
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name'], ' - RSS" href="', $scripturl, '?type=rss;action=.xml" />';

	// If we're viewing a topic, these should be the previous and next topics, respectively.
	if (!empty($context['current_topic']))
		echo '
	<link rel="prev" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=prev" />
	<link rel="next" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=next" />';

	// If we're in a board, or a topic for that matter, the index will be the board's index.
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="' . $scripturl . '?board=' . $context['current_board'] . '.0" />';

	echo'
<script type="text/javascript" src="../scripts/mootools.v1.11.js"></script>
<script type="text/javascript" src="', $settings['theme_url'], '/niftycube.js"></script>
<script type="text/javascript" src="../scripts/common.js"></script>
<script type="text/javascript" src="../scripts/site.js"></script>
</head>
<body>';

	echo '
 <div id="main">
   <h1 id="title">
   <a href="../index.php">
    <img src="../images/interface/logo.gif" title="i-tac, Combattre ou Mourir : v2" alt="i-tac" width="220" height="60"/>
   </a>
  </h1>
  <div id="pub">
';
    include('../../sources/pubs.php');
  echo'  </div>
  <div class="clearer">
  </div>
  <ul id="menu">
   <li>'.(isset($_SESSION['com_ID'])?'<a href="../jouer.php" id="linkJeu">Jeu</a>':'<a href="../inscription.php" id="linkJeu"'.activeItem('inscription').'>Inscription</a>').'</li>
   <li>'.(isset($_SESSION['com_ID'])?'<a href="../account.php" id="linkAccount">Compte</a>':'<a href="../index.php?act=login" id="linkAccount"'.activeItem('index','act=login').'>Connexion</a>').'</li>
   <li><a href="../index.php?act=news" id="linkNews">News</a></li>
   <li><a href="index.php" id="linkForum" class="active">Forum</a></li>
   <li><a href="../rp.php" id="linkRP">Chroniques</a></li>
 ';
    if(isset($_SESSION['com_ID'])){
      echo'   <li>
    <ul>
     <li><strong>Personnages</strong></li>
     <li>
      <form method="post" action="index.php" id="changePerso">
        <select name="persoId" id="persoId">'.getPlayable().'</select>
      </form>
     </li>
     <li><a href="../index.php?delog_ok=1">D&eacute;connexion</a></li>
    </ul>
   </li>
';
    }//'.$script_url.'
    echo'   <li>
    <ul>
     <li><strong>Univers</strong></li>
     <li><a href="../rp.php?act=lire&amp;uid=1">Ambiances</a></li>
     <li><a href="../rp.php?act=lire&amp;uid=2">Concepts</a></li>
    </ul>
   </li> 
   <li>
    <ul>
     <li><strong>Informations</strong></li>
     <li><a href="../liste_armes.php">&Eacute;quipements</a></li>
     <li><a href="../camp.php">Camps</a></li>
     <li><a href="../compagnies.php">Groupes</a></li>
     <li><a href="../grades.php">Grades</a></li>
     <li><a href="../liste_persos.php">Personnages</a></li>
    </ul>
   </li>
   <li>
    <ul>
     <li><strong>R&egrave;gles</strong></li>
     <li><a href="http://wiki.i-tac.fr">Wiki</a></li>
     <li><a href="../tutos.php">Tutoriaux</a></li>
     <li><a href="../chartes.php">Chartes</a></li>
    </ul>
   </li>
   <li>
    <ul>
     <li><strong>Association</strong></li>
     <li><a href="http://asso.i-tac.fr">Site</a></li>
     <li><a href="http://asso.i-tac.fr/index.php?/pages/2-presentation">Pr&eacute;sentation</a></li>
     <li><a href="http://asso.i-tac.fr/index.php?/pages/1-statuts">Statuts</a></li>
     <li><a href="http://asso.i-tac.fr/index.php?/pages/3-membres">Membres</a></li>
    </ul>
   </li>
  </ul>
  <div id="content">';
    if($context['user']['is_logged']){
      template_menu();
      echo '
  <div id="userInfos">
   <table>
    <tr>
     <td>
      ',$txt['hello_member_ndt'],' <strong>',$context['user']['name'],'</strong>
     </td>
     <td class="colRight">
      <a href="', $scripturl, '?action=unread">', $txt['unread_since_visit'], '</a>
     </td>
    </tr>
    <tr>
     <td>
      ',$context['current_time'],'
     </td>
     <td class="colRight">
      <a href="', $scripturl, '?action=unreadreplies">', $txt['show_unread_replies'], '</a>
     </td>
    </tr>
    <tr>
     <td>';
      if (!empty($context['user']['total_time_logged_in'])){
	echo '
      ', $txt['totalTimeLogged1'];
      // If days is just zero, don't bother to show it.
	if ($context['user']['total_time_logged_in']['days'] > 0)
	  echo $context['user']['total_time_logged_in']['days'] . $txt['totalTimeLogged2'];
	// Same with hours - only show it if it's above zero.
	if ($context['user']['total_time_logged_in']['hours'] > 0)
	  echo $context['user']['total_time_logged_in']['hours'] . $txt['totalTimeLogged3'];
	// But, let's always show minutes - Time wasted here: 0 minutes ;).
	echo $context['user']['total_time_logged_in']['minutes'], $txt['totalTimeLogged4'], '<br />';
      }
      echo'
     </td>
     <td class="colRight">';
      // Show the mark all as read button?
      if ($settings['show_mark_read'] && !empty($context['categories'])){
	echo '
      <a href="'.($scripturl.'?action=markasread;sa=all;sesc='.$context['session_id']).'">'.$txt['452'].'</a>';
      }
      echo'
     </td>
    </tr>
   </table>
  </div>';
    }
}

function activeItem($no,$query=''){
  $page=str_replace(array('.php'),'',$_SERVER['PHP_SELF']);
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

function template_main_below()
{
	global $context, $settings, $options, $scripturl, $txt;

	echo '
	</div>';

	// Show the "Powered by" and "Valid" logos, as well as the copyright. Remember, the copyright must be somewhere!
	echo '
  <div id="footerarea" style="text-align: center; padding-bottom: 1ex;', $context['browser']['needs_size_fix'] && !$context['browser']['is_ie6'] ? ' width: 100%;' : '', '" class="clearer">
   ', theme_copyright(), '</div>';
	echo '
	<div id="ajax_in_progress" style="display: none;', $context['browser']['is_ie'] && !$context['browser']['is_ie7'] ? 'position: absolute;' : '', '">', $txt['ajax_in_progress'], '</div>
</div>
</body></html>';
}

// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree()
{
  global $context, $settings, $options;

  echo '<div class="nav">';
  // Each tree item has a URL and name. Some may have extra_before and 
  // extra_after.
  foreach ($context['linktree'] as $link_num => $tree){
    // Show something before the link?
    if (isset($tree['extra_before'])){
      echo $tree['extra_before'];
    }
    // Show the link, including a URL if it should have one.
    echo $settings['linktree_link'] && isset($tree['url']) ? '<a href="' . $tree['url'] . '" class="nav">' . $tree['name'] . '</a>' : $tree['name'];
    // Show something after the link...?
    if (isset($tree['extra_after']))
      echo $tree['extra_after'];

    // Don't show a separator for the last one.
    if ($link_num != count($context['linktree']) - 1)
      echo '&nbsp;>&nbsp;';
  }
  echo '</div>';
}

// Show the menu up top. Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	// Work out where we currently are.
	$current_action = 'home';
	if (in_array($context['current_action'], array('admin', 'ban', 'boardrecount', 'cleanperms', 'detailedversion', 'dumpdb', 'featuresettings', 'featuresettings2', 'findmember', 'maintain', 'manageattachments', 'manageboards', 'managecalendar', 'managesearch', 'membergroups', 'modlog', 'news', 'optimizetables', 'packageget', 'packages', 'permissions', 'pgdownload', 'postsettings', 'regcenter', 'repairboards', 'reports', 'serversettings', 'serversettings2', 'smileys', 'viewErrorLog', 'viewmembers')))
		$current_action = 'admin';
	if (in_array($context['current_action'], array('search', 'admin', 'calendar', 'profile', 'mlist', 'register', 'login', 'help', 'pm')))
		$current_action = $context['current_action'];
	if ($context['current_action'] == 'search2')
		$current_action = 'search';
	if ($context['current_action'] == 'theme')
		$current_action = isset($_REQUEST['sa']) && $_REQUEST['sa'] == 'pick' ? 'profile' : 'admin';

	// Are we using right-to-left orientation?
	if ($context['right_to_left'])
	{
		$first = 'last';
		$last = 'first';
	}
	else
	{
		$first = 'first';
		$last = 'last';
	}

	// Show the start of the tab section.
	echo'
   <ul id="menuHaut" class="forum">
 '.showMenuItem('forumIndex','Index','',$current_action=='home');
	if ($context['allow_search']){
	  echo showMenuItem('forumSearch','Recherche','search',$current_action=='search');
	}
	if ($context['allow_admin']){
	  echo showMenuItem('forumAdmin','Admin','admin',$current_action=='admin');
	}
	if ($context['allow_edit_profile']){
	  echo showMenuItem('forumProfil','Profil','profile',$current_action=='profile');
	}
	if ($context['user']['is_logged'] && $context['allow_pm']){
	  echo showMenuItem('forumMP','Messages','pm',$current_action=='pm',$context['user']['unread_messages'] == 1);
	}
	if ($context['allow_calendar']){
	  echo showMenuItem('forumCalendar','Calendrier','calendar',$current_action=='calendar');
	}
	if ($context['allow_memberlist']){
	  echo showMenuItem('forumMembers','Membres','mlist',$current_action=='mlist');
	}
	echo'
   </ul>';
}

function showMenuItem($id,$nom,$url,$force=false,$new=false){
  $class='';
  if($new){
    $class='new';
  }
  if($force){
    $class='active';
  }
  return '    <li><a href="index.php'.($url?'?action='.$url:'').'" id="'.$id.'"'.($class?' class="'.$class.'"':'').'>'.$nom.'</a></li>';
}

// Generate a strip of buttons.
function template_button_strip($button_strip, $direction = 'top', $force_reset = false, $custom_td = '')
{
  global $settings, $buttons, $context, $txt, $scripturl;
  // Create the buttons...
  foreach ($button_strip as $key => $value){
    if (isset($value['test']) && empty($context[$value['test']])){
      unset($button_strip[$key]);
      continue;
    }
    elseif (!isset($buttons[$key]) || $force_reset)
      $buttons[$key] = '<a href="' . $value['url'] . '" ' .( isset($value['custom']) ? $value['custom'] : '') . '>' . $txt[$value['text']] . '</a>';
    
    $button_strip[$key] = $buttons[$key];
  }
  if($custom_td == 'admin'){
    echo '<li>'.implode('</li><li>', $button_strip).'</li>';
  }
  else{
    echo implode(' &nbsp;|&nbsp; ', $button_strip);
  }

  if (empty($button_strip))
    return '<td>&nbsp;</td>';
  /*
  echo '
		<td class="', $direction == 'top' ? 'main' : 'mirror', 'tab_' , $context['right_to_left'] ? 'last' : 'first' , '">&nbsp;</td>
		<td class="', $direction == 'top' ? 'main' : 'mirror', 'tab_back">', implode(' &nbsp;|&nbsp; ', $button_strip) , '</td>
		<td class="', $direction == 'top' ? 'main' : 'mirror', 'tab_' , $context['right_to_left'] ? 'first' : 'last' , '">&nbsp;</td>';
  */
}

function getPlayable(){
  global $db_prefix,$ID_MEMBER;
  // TODO : ajouter liste des PNJs
  $result = db_query("
		SELECT ID_MEMBER,memberName
		FROM {$db_prefix}members
		WHERE ID_compte = '" .$_SESSION['com_ID']. "'
		ORDER BY ID_perso ASC", __FILE__, __LINE__);
  $plop=mysql_fetch_array($result);
  $str = '<optgroup label="compte">
 <option value="'.$plop['ID_MEMBER'].'"'.($ID_MEMBER==$plop['ID_MEMBER']?'selected="selected"':'').'>'.$plop['memberName'].'</option>
</optgroup>
<optgroup label="persos">
';
  while($plop=mysql_fetch_array($result)){
    $str.='  <option value="'.$plop['ID_MEMBER'].'"'.($ID_MEMBER==$plop['ID_MEMBER']?'selected="selected"':'').'>'.$plop['memberName'].'</option>
';
  }
  $str.='</optgroup>';
  return $str;
}


?>