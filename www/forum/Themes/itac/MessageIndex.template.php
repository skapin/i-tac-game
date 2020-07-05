<?php
// Version: 1.1; MessageIndex

function template_main()
{
  global $context, $settings, $options, $scripturl, $modSettings, $txt;
  if (isset($context['boards']) && (!empty($options['show_children']) || $context['start'] == 0)){
    echo '
 <table class="cat">
  <tr>
   <td class="catName" colspan="5">', $txt['parent_boards'], '</td>
  </tr>
  <tr>
   <th class="tc1" colspan="2">Forum</th>
   <th class="tc2">',$txt[330],'</th>
   <th class="tc3">',$txt[21],'</th>
   <th class="tc4">',$txt[22],'</th>
  </tr>';
    foreach ($context['boards'] as $board){
      echo '
   <tr>
    <td ',!empty($board['children'])?'rowspan="2"':'',' class="tcl1"><a href="', $scripturl, '?action=unread;board=', $board['id'], '.0">';
      // If the board is new, show a strong indicator.
      if ($board['new']){
	echo '<img src="', $settings['images_url'], '/new_some.gif" alt="', $txt[333], '" title="', $txt[333], '" />';
	}
      // This board doesn't have new posts, but its children do.
      elseif ($board['children_new']){
	echo '<img src="', $settings['images_url'], '/new_some.gif" alt="', $txt[333], '" title="', $txt[333], '" />';
	}
      // No new posts at all! The agony!!
      else{
	echo '<img src="', $settings['images_url'], '/new_none.gif" alt="', $txt[334], '" title="', $txt[334], '" />';
	}
      echo '</a>
    </td>
    <td class="tcl2',!empty($board['children'])?' hasChildren':'','">
     <a href="', $board['href'], '" name="b', $board['id'], '">', $board['name'], '</a><br />
     ', $board['description'];
      // Show the "Moderators: ". Each has name, href, link, and id. 
      // (but we're gonna use link_moderators.)
      if (!empty($board['moderators'])){
	echo '
     <div>', count($board['moderators']) == 1 ? $txt[298] : $txt[299], ': ', implode(', ', $board['link_moderators']), '</div>';
      }
      // Show some basic information about the number of posts, etc.
      echo '
    </td>
    <td class="tcl3"',!empty($board['children'])?' rowspan="2"':'','>', $board['topics'],'</td>
    <td class="tcl4"',!empty($board['children'])?' rowspan="2"':'','>', $board['posts'],'</td>
    <td class="tcl5"',!empty($board['children'])?' rowspan="2"':'','>';
      /* The board's and children's 'last_post's have:
       time, timestamp (a number that represents the time.), 
       id (of the post), topic (topic id.),
       link, href, subject, start 
       (where they should go for the first unread post.),
       and member. (which has id, name, link, href, username in it.) */
      if (!empty($board['last_post']['id'])){
	  echo '
     ', $board['last_post']['link'], '<br />
     ', $board['last_post']['time'];
      }
      echo '
     </td>
    </tr>';
      // Show the "Child Boards: ". (there's a link_children but we're going to bold the new ones...)
      if (!empty($board['children'])){
	// Sort the links into an array with new boards bold so it can be imploded.
	$children = array();
	/* Each child in each board's children has:
	 id, name, description, new (is it new?), topics (), posts (), 
	 href, link, and last_post. */
	foreach ($board['children'] as $child){
	  $child['link'] = '<a href="' . $child['href'] . '" title="' . ($child['new'] ? $txt[333] : $txt[334]) . ' (' . $txt[330] . ': ' . $child['topics'] . ', ' . $txt[21] . ': ' . $child['posts'] . ')"'.($child['new'] ?' class="new"':'').'>' . $child['name'] . '</a>';
	  $children[] = $child['link'];
	}
	//', !empty($settings['seperate_sticky_lock']) ? '3' : '', '
	echo '
    <tr>
     <td class="tcl2 isChild">
      <span>', $txt['parent_boards'], ': ', implode(', ', $children), '</span>
     </td>
    </tr>';
      }
    }
    echo'
 </table>';
  }
  // Create the button set...
  $normal_buttons = array(
			  'markread' => array('text' => 'mark_read_short', 'image' => 'markread.gif', 'lang' => true, 'url' => $scripturl . '?action=markasread;sa=board;board=' . $context['current_board'] . '.0;sesc=' . $context['session_id']),
		'notify' => array('test' => 'can_mark_notify', 'text' => 125, 'image' => 'notify.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . ($context['is_marked_notify'] ? $txt['notification_disable_board'] : $txt['notification_enable_board']) . '\');"', 'url' => $scripturl . '?action=notifyboard;sa=' . ($context['is_marked_notify'] ? 'off' : 'on') . ';board=' . $context['current_board'] . '.' . $context['start'] . ';sesc=' . $context['session_id']),
		'new_topic' => array('test' => 'can_post_new', 'text' => 'smf258', 'image' => 'new_topic.gif', 'lang' => true, 'url' => $scripturl . '?action=post;board=' . $context['current_board'] . '.0'),
		'post_poll' => array('test' => 'can_post_poll', 'text' => 'smf20', 'image' => 'new_poll.gif', 'lang' => true, 'url' => $scripturl . '?action=post;board=' . $context['current_board'] . '.0;poll'),
			  );

  // They can only mark read if they are logged in and it's enabled!
  if (!$context['user']['is_logged'] || !$settings['show_mark_read']){
    unset($normal_buttons['markread']);
  }
  echo '
<div class="links">
 <p class="pl">',$txt[139],': ',$context['page_index'],'</p>
 <p class="bl">', template_button_strip($normal_buttons, 'top'),'</p>
 ', theme_linktree(), '
</div>';
  if (!$context['no_topic_listing']){
    // If Quick Moderation is enabled start the form.
    if (!empty($options['display_quick_mod']) && !empty($context['topics'])){
      echo '
	<form action="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], '" method="post" accept-charset="', $context['character_set'], '" name="quickModForm" id="quickModForm" style="margin: 0;">';
    }
    // Are there actually any topics to show?
    if (!empty($context['topics'])){
      echo '
 <table class="cat">
  <tr>
   <td class="catName" colspan="5">'.$context['name'].'</td>
  </tr>
  <tr>
   <th class="tc1" colspan="2">',$txt[70],'</th>
   <th class="tc2">',$txt[110],'</th>
   <th class="tc3">',$txt[301],'</th>
   <th class="tc4">',$txt[111],'</th>';
      // Show a "select all" box for quick moderation?
      if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1){
	echo '
   <td>
    <input type="checkbox" onclick="invertAll(this, this.form, \'topics[]\');" class="check" />
   </td>';
      }
      // If it's on in "image" mode, don't show anything but the column.
      elseif (!empty($options['display_quick_mod'])){
	echo '
   <td></td>';
      }
    }
    // No topics.... just say, "sorry bub".
    else{
      echo '
   <td>',$txt[151],'</td>';
    }
    echo '
  </tr>';
    foreach ($context['topics'] as $topic){
      echo '
  <tr>
   <td class="tcl1">
    <img src="', $topic['first_post']['icon_url'], '" alt="" />
   </td>
   <td class="tcl2',($topic['is_locked']?' locked':''),($topic['is_sticky']?' sticky':''),($topic['new'] && $context['user']['is_logged']?' new':''),'"',(!empty($topic['quick_mod']['remove'])?' id="topic_'.$topic['first_post']['id'].'" onmouseout="mouse_on_div = 0;" onmouseover="mouse_on_div = 1;" ondblclick="modify_topic(\'' . $topic['id'] . '\', \'' . $topic['first_post']['id'] . '\', \'' . $context['session_id'] . '\');"' : ''),'>
    ', $topic['first_post']['link'],'
    <span>', $topic['pages'], '</span>
    ',$txt[525],'
    ', $topic['first_post']['member']['name'], '
   </td>
   <td class="tcl3">',$topic['replies'],'</td>
   <td class="tcl4">',$topic['views'],'</td>
   <td class="tcl5">
    <a href="', $topic['last_post']['href'], '">', $topic['last_post']['time'], '</a><br />
    ',$txt[525],' ',$topic['last_post']['member']['name'], '
   </td>';
      // Show the quick moderation options?
      if (!empty($options['display_quick_mod'])){
	echo '
   <td>';
	if ($options['display_quick_mod'] == 1){
	  echo '
    <input type="checkbox" name="topics[]" value="', $topic['id'], '" class="check" />';
	}
	else{
	  // Check permissions on each and show only the ones they are allowed 
	  // to use.
	  if ($topic['quick_mod']['remove']){
	    echo '<a href="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], ';actions[', $topic['id'], ']=remove;sesc=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');"><img src="', $settings['images_url'], '/icons/quick_remove.gif" width="16" alt="', $txt[63], '" title="', $txt[63], '" /></a>';
	  }
	  if ($topic['quick_mod']['lock']){
	    echo '<a href="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], ';actions[', $topic['id'], ']=lock;sesc=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');"><img src="', $settings['images_url'], '/icons/quick_lock.gif" width="16" alt="', $txt['smf279'], '" title="', $txt['smf279'], '" /></a>';
	  }
	  if ($topic['quick_mod']['lock'] || $topic['quick_mod']['remove']){
	    echo '<br />';
	  }
	  if ($topic['quick_mod']['sticky']){
	    echo '<a href="', $scripturl, '?action=quickmod;board=', $context['current_board'], '.', $context['start'], ';actions[', $topic['id'], ']=sticky;sesc=', $context['session_id'], '" onclick="return confirm(\'', $txt['quickmod_confirm'], '\');"><img src="', $settings['images_url'], '/icons/quick_sticky.gif" width="16" alt="', $txt['smf277'], '" title="', $txt['smf277'], '" /></a>';
	  }
	  if ($topic['quick_mod']['move']){
	    echo '<a href="', $scripturl, '?action=movetopic;board=', $context['current_board'], '.', $context['start'], ';topic=', $topic['id'], '.0"><img src="', $settings['images_url'], '/icons/quick_move.gif" width="16" alt="', $txt[132], '" title="', $txt[132], '" /></a>';
	  }
	}
	echo '</td>';
      }
      echo '
  </tr>';
    }

    if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && !empty($context['topics'])){
      echo '
  <tr class="catbg">
   <td colspan="8" align="right">
    <select name="qaction"', $context['can_move'] ? ' onchange="this.form.moveItTo.disabled = (this.options[this.selectedIndex].value != \'move\');"' : '', '>
     <option value="">--------</option>
     ', $context['can_remove'] ? '<option value="remove">' . $txt['quick_mod_remove'] . '</option>' : '', '
     ', $context['can_lock'] ? '<option value="lock">' . $txt['quick_mod_lock'] . '</option>' : '', '
     ', $context['can_sticky'] ? '<option value="sticky">' . $txt['quick_mod_sticky'] . '</option>' : '', '
     ', $context['can_move'] ? '<option value="move">' . $txt['quick_mod_move'] . ': </option>' : '', '
     ', $context['can_merge'] ? '<option value="merge">' . $txt['quick_mod_merge'] . '</option>' : '', '
     <option value="markread">', $txt['quick_mod_markread'], '</option>
    </select>';
      if ($context['can_move']){
	echo '
    <select id="moveItTo" name="move_to" disabled="disabled">';
	foreach ($context['jump_to'] as $category){
	  foreach ($category['boards'] as $board){
	    if (!$board['is_current']){
	      echo '
     <option value="', $board['id'], '"', !empty($board['selected']) ? ' selected="selected"' : '', '>', str_repeat('-', $board['child_level'] + 1), ' ', $board['name'], '</option>';
	    }
	  }
	}
	echo '
    </select>';
      }
      echo '
    <input type="submit" value="', $txt['quick_mod_go'], '" onclick="return document.forms.quickModForm.qaction.value != \'\' &amp;&amp; confirm(\'', $txt['quickmod_confirm'], '\');" />
   </td>
  </tr>';
    }
    
    echo '
 </table>
<a name="bot"></a>';

    // Finish off the form - again.
    if (!empty($options['display_quick_mod']) && !empty($context['topics'])){
      echo '
  <input type="hidden" name="sc" value="' . $context['session_id'] . '" />
 </form>';
    }
  }

  echo '
<div class="links">
 <p class="pl">',$txt[139],': ',$context['page_index'],'</p>
 <p class="bl">', template_button_strip($normal_buttons, 'top'),'</p>
 ', theme_linktree(),'
</div>';

	echo '
<div class="jumpBar">
 <form action="', $scripturl, '" method="get" accept-charset="', $context['character_set'], '" name="jumptoForm">
  <span class="smalltext"><label for="jumpto">' . $txt[160] . '</label>:</span>
  <select name="jumpto" id="jumpto" onchange="if (this.selectedIndex > 0 &amp;&amp; this.options[this.selectedIndex].value) window.location.href = smf_scripturl + this.options[this.selectedIndex].value.substr(smf_scripturl.indexOf(\'?\') == -1 || this.options[this.selectedIndex].value.substr(0, 1) != \'?\' ? 0 : 1);">
   <option value="">' . $txt[251] . ':</option>';
	// Show each category - they all have an id, name, and the boards in them.
	foreach ($context['jump_to'] as $category){
	  // Show the category name with a link to the category. (index.php id)
	  echo '
   <option value="" disabled="disabled">-----------------------------</option>
   <option value="#', $category['id'], '">', $category['name'], '</option>
   <option value="" disabled="disabled">-----------------------------</option>';
	  /* Now go through each board - they all have:
	   id, name, child_level (how many parents they have, basically...), 
	   and is_current. (is this the current board?) */
	  foreach ($category['boards'] as $board){
	    // Show some more =='s if this is a child, so as to make it look nice.
	    echo '
   <option value="?board=', $board['id'], '.0"', $board['is_current'] ? ' selected="selected"' : '', '> ', str_repeat('==', $board['child_level']), '=> ', $board['name'], '</option>';
	  }
	}
	echo '
  </select>&nbsp;
  <input type="button" value="', $txt[161], '" onclick="if (this.form.jumpto.options[this.form.jumpto.selectedIndex].value) window.location.href = \'', $scripturl, '\' + this.form.jumpto.options[this.form.jumpto.selectedIndex].value;" />
 </form>
</div>';

	// Javascript for inline editing.
	echo '
<script language="JavaScript" type="text/javascript" src="' . $settings['default_theme_url'] . '/xml_board.js"></script>
<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[

	// Hide certain bits during topic edit.
	hide_prefixes.push("lockicon", "stickyicon", "pages", "newicon");

	// Use it to detect when we\'ve stopped editing.
	document.onclick = modify_topic_click;

	var mouse_on_div;
	function modify_topic_click()
	{
		if (in_edit_mode == 1 && mouse_on_div == 0)
			modify_topic_save("', $context['session_id'], '");
	}

	function modify_topic_keypress(oEvent)
	{
		if (typeof(oEvent.keyCode) != "undefined" && oEvent.keyCode == 13)
		{
			modify_topic_save("', $context['session_id'], '");
			if (typeof(oEvent.preventDefault) == "undefined")
				oEvent.returnValue = false;
			else
				oEvent.preventDefault();
		}
	}

	// For templating, shown when an inline edit is made.
	function modify_topic_show_edit(subject)
	{
		// Just template the subject.
		setInnerHTML(cur_subject_div, \'<input type="text" name="subject" value="\' + subject + \'" size="60" style="width: 99%;"  maxlength="80" onkeypress="modify_topic_keypress(event)" /><input type="hidden" name="topic" value="\' + cur_topic_id + \'" /><input type="hidden" name="msg" value="\' + cur_msg_id.substr(4) + \'" />\');
	}

	// And the reverse for hiding it.
	function modify_topic_hide_edit(subject)
	{
		// Re-template the subject!
		setInnerHTML(cur_subject_div, \'<a href="', $scripturl, '?topic=\' + cur_topic_id + \'.0">\' + subject + \'</a>\');
	}

// ]]></script>';
}

function theme_show_buttons()
{
	global $context, $settings, $options, $txt, $scripturl;

	$buttonArray = array();

	// If they are logged in, and the mark read buttons are enabled..
	if ($context['user']['is_logged'] && $settings['show_mark_read'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=markasread;sa=board;board=' . $context['current_board'] . '.0;sesc=' . $context['session_id'] . '">' . $txt['mark_read_short'] . '</a>';

	// If the user has permission to show the notification button... ask them if they're sure, though.
	if ($context['can_mark_notify'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=notifyboard;sa=' . ($context['is_marked_notify'] ? 'off' : 'on') . ';board=' . $context['current_board'] . '.' . $context['start'] . ';sesc=' . $context['session_id'] . '" onclick="return confirm(\'' . ($context['is_marked_notify'] ? $txt['notification_disable_board'] : $txt['notification_enable_board']) . '\');">' . $txt[125] . '</a>';

	// Are they allowed to post new topics?
	if ($context['can_post_new'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=post;board=' . $context['current_board'] . '.0">' . $txt['smf258'] . '</a>';

	// How about new polls, can the user post those?
	if ($context['can_post_poll'])
		$buttonArray[] = '<a href="' . $scripturl . '?action=post;board=' . $context['current_board'] . '.0;poll">' . $txt['smf20'] . '</a>';

	return implode(' &nbsp;|&nbsp; ', $buttonArray);
}

?>