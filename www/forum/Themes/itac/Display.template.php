<?php
  // Version: 1.1; Display

function template_main(){
  global $context, $settings, $options, $txt, $scripturl, $modSettings;
  // Build the normal button array.
  $normal_buttons = array(
			  'reply' => array('test' => 'can_reply', 'text' => 146, 'image' => 'reply.gif', 'lang' => true, 'url' => $scripturl . '?action=post;topic=' . $context['current_topic'] . '.' . $context['start'] . ';num_replies=' . $context['num_replies']),
			  'notify' => array('test' => 'can_mark_notify', 'text' => 125, 'image' => 'notify.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . ($context['is_marked_notify'] ? $txt['notification_disable_topic'] : $txt['notification_enable_topic']) . '\');"', 'url' => $scripturl . '?action=notify;sa=' . ($context['is_marked_notify'] ? 'off' : 'on') . ';topic=' . $context['current_topic'] . '.' . $context['start'] . ';sesc=' . $context['session_id']),
			  'custom' => array(),
			  'send' => array('test' => 'can_send_topic', 'text' => 707, 'image' => 'sendtopic.gif', 'lang' => true, 'url' => $scripturl . '?action=sendtopic;topic=' . $context['current_topic'] . '.0'),
			  'print' => array('text' => 465, 'image' => 'print.gif', 'lang' => true, 'custom' => 'target="_blank"', 'url' => $scripturl . '?action=printpage;topic=' . $context['current_topic'] . '.0'),
			  );

  // Special case for the custom one.
  if ($context['user']['is_logged'] && $settings['show_mark_read']){
    $normal_buttons['custom'] = array('text' => 'mark_unread', 'image' => 'markunread.gif', 'lang' => true, 'url' => $scripturl . '?action=markasread;sa=topic;t=' . $context['mark_unread_time'] . ';topic=' . $context['current_topic'] . '.' . $context['start'] . ';sesc=' . $context['session_id']);
  }
  elseif ($context['can_add_poll']){
    $normal_buttons['custom'] = array('text' => 'add_poll', 'image' => 'add_poll.gif', 'lang' => true, 'url' => $scripturl . '?action=editpoll;add;topic=' . $context['current_topic'] . '.' . $context['start'] . ';sesc=' . $context['session_id']);
  }
  else{
    unset($normal_buttons['custom']);
  }
  echo '
 <div class="links">
 <p class="pl">',$txt[139],': ',$context['page_index'],'</p>
 <p class="bl">', template_button_strip($normal_buttons, 'top'),'</p>
 ', theme_linktree(), '
 </div>';

  // Is this topic also a poll?
  if ($context['is_poll']){
    echo '
 <table>
 <tr>
  <td>
   <img src="', $settings['images_url'], '/topic/', $context['poll']['is_locked'] ? 'normal_poll_locked' : 'normal_poll', '.gif" alt="" align="bottom" /> ', $txt['smf43'], '
  </td>
 </tr>
 <tr>
  <td>',$txt['smf21'],':</td>
  <td>
   ', $context['poll']['question'];
    if (!empty($context['poll']['expire_time'])){
      echo '
    &nbsp;(', ($context['poll']['is_expired'] ? $txt['poll_expired_on'] : $txt['poll_expires_on']), ': ', $context['poll']['expire_time'], ')';
    }
    // Are they not allowed to vote but allowed to view the options?
    if ($context['poll']['show_results'] || !$context['allow_vote']){
      echo '
  <table>
   <tr>
    <td>
     <table>';
      // Show each option with its corresponding percentage bar.
      foreach ($context['poll']['options'] as $option){
	/* style="padding-right: 2ex;', $option['voted_this'] ? 'font-weight: bold;' : '', '"*/
	echo '
      <tr>
       <td>', $option['option'], '</td>', $context['allow_poll_view'] ? '
       <td nowrap="nowrap">'.$option['bar'].' '.$option['votes'].' ('.$option['percent'].'%)</td>' : '', '
      </tr>';
      }
      echo '
     </table>
    </td>
    <td>';
      
      // If they are allowed to revote - show them a link!
      if ($context['allow_change_vote']){
	echo '
      <a href="', $scripturl, '?action=vote;topic=', $context['current_topic'], '.', $context['start'], ';poll=', $context['poll']['id'], ';sesc=', $context['session_id'], '">', $txt['poll_change_vote'], '</a><br />';
      }
      // If we're viewing the results... maybe we want to go back and vote?
      if ($context['poll']['show_results'] && $context['allow_vote']){
	echo '
      <a href="', $scripturl, '?topic=', $context['current_topic'], '.', $context['start'], '">', $txt['poll_return_vote'], '</a><br />';
      }
      // If they're allowed to lock the poll, show a link!
      if ($context['poll']['lock']){
	echo '
      <a href="', $scripturl, '?action=lockVoting;topic=', $context['current_topic'], '.', $context['start'], ';sesc=', $context['session_id'], '">', !$context['poll']['is_locked'] ? $txt['smf30'] : $txt['smf30b'], '</a><br />';
      }
      // If they're allowed to edit the poll... guess what... show a link!
      if ($context['poll']['edit']){
	echo '
      <a href="', $scripturl, '?action=editpoll;topic=', $context['current_topic'], '.', $context['start'], '">', $txt['smf39'], '</a>';
      }
      echo '
     </td>
    </tr>', $context['allow_poll_view'] ? '
    <tr>
     <td colspan="2">' . $txt['smf24'] . ': ' . $context['poll']['total_votes'] . '</td>
    </tr>' : '', '
   </table><br />';
    }
    // They are allowed to vote! Go to it!
    else{
      echo '
   <form action="', $scripturl, '?action=vote;topic=', $context['current_topic'], '.', $context['start'], ';poll=', $context['poll']['id'], '" method="post" accept-charset="', $context['character_set'], '">
    <table>
     <tr>
      <td colspan="2">';
      
      // Show a warning if they are allowed more than one option.
      if ($context['poll']['allowed_warning']){
	echo '
       ', $context['poll']['allowed_warning'], '
      </td>
     </tr>
     <tr>
      <td>';
      }
      // Show each option with its button - a radio likely.
      foreach ($context['poll']['options'] as $option){
	echo '
       ', $option['vote_button'], ' ', $option['option'], '<br />';
      }
      echo '
      </td>
      <td>';

      // Allowed to view the results? (without voting!)
      if ($context['allow_poll_view']){
	echo '
      <a href="', $scripturl, '?topic=', $context['current_topic'], '.', $context['start'], ';viewResults">', $txt['smf29'], '</a><br />';
      }
      // Show a link for locking the poll as well...
      if ($context['poll']['lock']){
	echo '
      <a href="', $scripturl, '?action=lockVoting;topic=', $context['current_topic'], '.', $context['start'], ';sesc=', $context['session_id'], '">', (!$context['poll']['is_locked'] ? $txt['smf30'] : $txt['smf30b']), '</a><br />';
      }
      // Want to edit it? Click right here......
      if ($context['poll']['edit']){
	echo '
      <a href="', $scripturl, '?action=editpoll;topic=', $context['current_topic'], '.', $context['start'], '">', $txt['smf39'], '</a>';
      }
      echo '
     </td>
    </tr>
    <tr>
     <td colspan="2"><input type="submit" value="', $txt['smf23'], '" /></td>
    </tr>
   </table>
   <input type="hidden" name="sc" value="', $context['session_id'], '" />
  </form>';
    }
    echo '
 </td>
 </tr>
 </table>';
  }

  echo '
 <form action="', $scripturl, '?action=quickmod2;topic=', $context['current_topic'], '.', $context['start'], '" method="post" accept-charset="', $context['character_set'], '" name="quickModForm" id="quickModForm" style="margin: 0;" onsubmit="return in_edit_mode == 1 ? modify_save(\'' . $context['session_id'] . '\') : confirm(\'' . $txt['quickmod_confirm'] . '\');">';
  // These are some cache image buttons we may want.
  $reply_button = create_button('quote.gif', 145, 'smf240', 'align="middle"');
  $modify_button = create_button('modify.gif', 66, 17, 'align="middle"');
  $remove_button = create_button('delete.gif', 121, 31, 'align="middle"');
  $split_button = create_button('split.gif', 'smf251', 'smf251', 'align="middle"');

  // Time to display all the posts
  // Get all the messages...
  while ($message = $context['get_message']()){
    echo '
<table class="post">
 <tr>
  <th colspan="3">';
    // Show the message anchor and a "new" anchor if this message is new.
    if ($message['id'] != $context['first_message']){
      echo '
   <a name="msg', $message['id'], '"></a>', $message['first_new'] ? '<a name="new"></a>' : '';
    }
    echo '<a href="', $message['href'], '">',!empty($message['counter'])?$txt[146].' #'.$message['counter']:'',' ',$message['time'],'</a>
  </th>
 </tr>
 <tr>
  <td rowspan="'.(!empty($message['member']['signature']) && empty($options['show_no_signatures'])?'3':'2').'" class="tcl1">';
    // Show information about the poster of this message.
    echo '
   ', $message['member']['link'];
    // Don't show these things for guests.
    if (!$message['member']['is_guest']){
      // Show avatars, images, etc.?
      if (!empty($settings['show_user_images']) && 
	  empty($options['show_no_avatars']) && 
	  !empty($message['member']['avatar']['image'])){
	echo '<br />
<br />  
    ', $message['member']['avatar']['image'], '<br />';
	// This shows the popular messaging icons.
	echo '
    ', $message['member']['icq']['link'], '
    ', $message['member']['msn']['link'], '
    ', $message['member']['aim']['link'], '
    ', $message['member']['yim']['link'], '<br />';

	// Show the profile, website, email address, and personal message buttons.
	if ($settings['show_profile_buttons']){
	  // Don't show the profile button if you're not allowed to view the 
	  //profile.
	  if ($message['member']['can_view_profile']){
	    echo '
    <a href="', $message['member']['href'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/icons/profile_sm.gif" alt="' . $txt[27] . '" title="' . $txt[27] . '" border="0" />' : $txt[27]), '</a>';
	  }
	  // Don't show an icon if they haven't specified a website.
	  if ($message['member']['website']['url'] != ''){
	    echo '
    <a href="', $message['member']['website']['url'], '" title="' . $message['member']['website']['title'] . '" target="_blank">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/www_sm.gif" alt="' . $txt[515] . '" border="0" />' : $txt[515]), '</a>';
	  }
	  // Don't show the email address if they want it hidden.
	  if (empty($message['member']['hide_email'])){
	    echo '
    <a href="mailto:', $message['member']['email'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/email_sm.gif" alt="' . $txt[69] . '" title="' . $txt[69] . '" border="0" />' : $txt[69]), '</a>';
	  }
	  // Since we know this person isn't a guest, you *can* message them.
	  if ($context['can_send_pm']){
	    echo '
    <a href="', $scripturl, '?action=pm;sa=send;u=', $message['member']['id'], '" title="', $message['member']['online']['label'], '">', $settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/im_' . ($message['member']['online']['is_online'] ? 'on' : 'off') . '.gif" alt="' . $message['member']['online']['label'] . '" border="0" />' : $message['member']['online']['label'], '</a>';
	  }
	}
      }
    }
    // Otherwise, show the guest's email.
    elseif (empty($message['member']['hide_email'])){
      echo '
    <br />
    <br />
    <a href="mailto:', $message['member']['email'], '">', ($settings['use_image_buttons'] ? '<img src="' . $settings['images_url'] . '/email_sm.gif" alt="' . $txt[69] . '" title="' . $txt[69] . '" border="0" />' : $txt[69]), '</a>';
    }
    // Done with the information about the poster... on to the post itself.
    echo '
  </td>
  <td colspan="2" class="tcl2">
   <div', $message['can_modify'] ? ' id="msg_' . $message['id'] . '"' : '','>',$message['body'],'</div>',$message['can_modify'] ? '
   <img src="' . $settings['images_url'] . '/icons/modify_inline.gif" alt="" align="right" id="modify_button_' . $message['id'] . '" style="cursor: pointer; display: none;" onclick="modify_msg(\'' . $message['id'] . '\', \'' . $context['session_id'] . '\')" />' : '' , '
  </td>
 </tr>';
    // Show the member's signature?
    if (!empty($message['member']['signature']) && empty($options['show_no_signatures'])){
      echo '
 <tr>
  <td colspan="2">', $message['member']['signature'], '</td>
 </tr>';
    }
    echo'
 <tr>
  <td id="modified_', $message['id'], '" class="modified">';
    // Show  Last Edit: Time by Person  if this post was edited.
    if ($settings['show_modify'] && !empty($message['modified']['name'])){
      echo '
        &#171; ', $txt[211], ': ', $message['modified']['time'], ' ', $txt[525], ' ', $message['modified']['name'], ' &#187;';
    }
    echo '
  </td>
  <td class="buttons">';
    // Can they reply? Have they turned on quick reply?
    if ($context['can_reply'] && !empty($options['display_quick_reply'])){
      echo '
   <a href="', $scripturl, '?action=post;quote=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';num_replies=', $context['num_replies'], ';sesc=', $context['session_id'], '" onclick="doQuote(', $message['id'], ', \'', $context['session_id'], '\'); return false;">', $reply_button, '</a>';
    }
    // So... quick reply is off, but they *can* reply?
    elseif ($context['can_reply']){
      echo '
   <a href="', $scripturl, '?action=post;quote=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';num_replies=', $context['num_replies'], ';sesc=', $context['session_id'], '">', $reply_button, '</a>';
    }
    // Can the user modify the contents of this post?
    if ($message['can_modify']){
      echo '
   <a href="', $scripturl, '?action=post;msg=', $message['id'], ';topic=', $context['current_topic'], '.', $context['start'], ';sesc=', $context['session_id'], '">', $modify_button, '</a>';
    }
    // How about... even... remove it entirely?!
    if ($message['can_remove']){
      echo '
   <a href="', $scripturl, '?action=deletemsg;topic=', $context['current_topic'], '.', $context['start'], ';msg=', $message['id'], ';sesc=', $context['session_id'], '" onclick="return confirm(\'', $txt[154], '?\');">', $remove_button, '</a>';
    }
    // Show a checkbox for quick moderation?
    if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && $message['can_remove']){
      echo '
   <input type="checkbox" name="msgs[]" value="', $message['id'], '" class="check" ', empty($settings['use_tabs']) ? 'onclick="document.getElementById(\'quickmodSubmit\').style.display = \'\';"' : '', ' />';
    }
    echo '
  </td>
 </tr>
 </table>';
  }
    // As before, build the custom button right.
    if ($context['can_add_poll'])
      $normal_buttons['custom'] = array('text' => 'add_poll', 'image' => 'add_poll.gif', 'lang' => true, 'url' => $scripturl . '?action=editpoll;add;topic=' . $context['current_topic'] . '.' . $context['start'] . ';sesc=' . $context['session_id']);
    elseif ($context['user']['is_logged'] && $settings['show_mark_read'])
      $normal_buttons['custom'] = array('text' => 'mark_unread', 'image' => 'markunread.gif', 'lang' => true, 'url' => $scripturl . '?action=markasread;sa=topic;t=' . $context['mark_unread_time'] . ';topic=' . $context['current_topic'] . '.' . $context['start'] . ';sesc=' . $context['session_id']);

  echo '
 <div class="links">
 <p class="pl">',$txt[139],': ',$context['page_index'],'</p>
 <p class="bl">', template_button_strip($normal_buttons, 'top'),'</p>
 ', theme_linktree(), '
 </div>';


  if ($context['show_spellchecking']){
    echo '
 <script language="JavaScript" type="text/javascript" src="' . $settings['default_theme_url'] . '/spellcheck.js"></script>';
  }
  echo '
 <script language="JavaScript" type="text/javascript" src="' . $settings['default_theme_url'] . '/xml_topic.js"></script>
 <script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
	quickReplyCollapsed = ', !empty($options['display_quick_reply']) && $options['display_quick_reply'] == 2 ? 'false' : 'true', ';

	smf_topic = ', $context['current_topic'], ';
	smf_start = ', $context['start'], ';
	smf_show_modify = ', $settings['show_modify'] ? '1' : '0', ';

	// On quick modify, this is what the body will look like.
	var smf_template_body_edit = \'<div id="error_box" style="padding: 4px; color: red;"></div><textarea class="editor" name="message" rows="12" style="width: 94%; margin-bottom: 10px;">%body%</textarea><br /><input type="hidden" name="sc" value="', $context['session_id'], '" /><input type="hidden" name="topic" value="', $context['current_topic'], '" /><input type="hidden" name="msg" value="%msg_id%" /><div style="text-align: center;"><input type="submit" name="post" value="', $txt[10], '" onclick="return modify_save(\\\'' . $context['session_id'] . '\\\');" accesskey="s" />&nbsp;&nbsp;', $context['show_spellchecking'] ? '<input type="button" value="' . $txt['spell_check'] . '" onclick="spellCheck(\\\'quickModForm\\\', \\\'message\\\');" />&nbsp;&nbsp;' : '', '<input type="submit" name="cancel" value="', $txt['modify_cancel'], '" onclick="return modify_cancel();" /></div>\';

	// And this is the replacement for the subject.
	var smf_template_subject_edit = \'<input type="text" name="subject" value="%subject%" size="60" style="width: 99%;"  maxlength="80" />\';

	// Restore the message to this after editing.
	var smf_template_body_normal = \'%body%\';
	var smf_template_subject_normal = \'<a href="', $scripturl, '?topic=', $context['current_topic'], '.msg%msg_id%#msg%msg_id%">%subject%</a>\';
	var smf_template_top_subject = "', $txt[118], ': %subject% &nbsp;(', $txt[641], ' ', $context['num_views'], ' ', $txt[642], ')"

	if (window.XMLHttpRequest)
		showModifyButtons();
// ]]></script>';

    $mod_buttons = array(
			 'move' => array('test' => 'can_move', 'text' => 132, 'image' => 'admin_move.gif', 'lang' => true, 'url' => $scripturl . '?action=movetopic;topic=' . $context['current_topic'] . '.0'),
			 'delete' => array('test' => 'can_delete', 'text' => 63, 'image' => 'admin_rem.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . $txt[162] . '\');"', 'url' => $scripturl . '?action=removetopic2;topic=' . $context['current_topic'] . '.0;sesc=' . $context['session_id']),
			 'lock' => array('test' => 'can_lock', 'text' => empty($context['is_locked']) ? 'smf279' : 'smf280', 'image' => 'admin_lock.gif', 'lang' => true, 'url' => $scripturl . '?action=lock;topic=' . $context['current_topic'] . '.' . $context['start'] . ';sesc=' . $context['session_id']),
			 'sticky' => array('test' => 'can_sticky', 'text' => empty($context['is_sticky']) ? 'smf277' : 'smf278', 'image' => 'admin_sticky.gif', 'lang' => true, 'url' => $scripturl . '?action=sticky;topic=' . $context['current_topic'] . '.' . $context['start'] . ';sesc=' . $context['session_id']),
			 'merge' => array('test' => 'can_merge', 'text' => 'smf252', 'image' => 'merge.gif', 'lang' => true, 'url' => $scripturl . '?action=mergetopics;board=' . $context['current_board'] . '.0;from=' . $context['current_topic']),
			 'remove_poll' => array('test' => 'can_remove_poll', 'text' => 'poll_remove', 'image' => 'admin_remove_poll.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . $txt['poll_remove_warn'] . '\');"', 'url' => $scripturl . '?action=removepoll;topic=' . $context['current_topic'] . '.' . $context['start']),
			 'calendar' => array('test' => 'calendar_post', 'text' => 'calendar37', 'image' => 'linktocal.gif', 'lang' => true, 'url' => $scripturl . '?action=post;calendar;msg=' . $context['topic_first_message'] . ';topic=' . $context['current_topic'] . '.0;sesc=' . $context['session_id']),
			 );

    if ($context['can_remove_post'] && !empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1)
      $mod_buttons[] = array('text' => 'quickmod_delete_selected', 'image' => 'delete_selected.gif', 'lang' => true, 'custom' => 'onclick="return confirm(\'' . $txt['quickmod_confirm'] . '\');" id="quickmodSubmit"', 'url' => 'javascript:document.quickModForm.submit();');


    if (!empty($options['display_quick_mod']) && $options['display_quick_mod'] == 1 && $context['can_remove_post'])
      echo '
  <input type="hidden" name="sc" value="', $context['session_id'], '" />';

    if (empty($settings['use_tabs']))
      echo '
	<script language="JavaScript" type="text/javascript"><!-- // --><![CDATA[
		document.getElementById("quickmodSubmit").style.display = "none";
	// ]]></script>';

    echo '
 </form>';

    echo '
<div class="toolBar">
 <div class="jumpBar">
  <form action="', $scripturl, '" method="get" accept-charset="', $context['character_set'], '">
   <span class="smalltext">' . $txt[160] . ':</span>
   <select name="jumpto" id="jumpto" onchange="if (this.selectedIndex > 0 &amp;&amp; this.options[this.selectedIndex].value) window.location.href = smf_scripturl + this.options[this.selectedIndex].value.substr(smf_scripturl.indexOf(\'?\') == -1 || this.options[this.selectedIndex].value.substr(0, 1) != \'?\' ? 0 : 1);">
     <option value="">' . $txt[251] . ':</option>';
    foreach ($context['jump_to'] as $category)
    {
      echo '
     <option value="" disabled="disabled">-----------------------------</option>
     <option value="#', $category['id'], '">', $category['name'], '</option>
			<option value="" disabled="disabled">-----------------------------</option>';
      foreach ($category['boards'] as $board)
	echo '
     <option value="?board=', $board['id'], '.0"', $board['is_current'] ? ' selected="selected"' : '', '> ' . str_repeat('==', $board['child_level']) . '=> ' . $board['name'] . '</option>';
    }
    echo '
    </select>&nbsp;
    <input type="button" value="', $txt[161], '" onclick="if (this.form.jumpto.options[this.form.jumpto.selectedIndex].value) window.location.href = \'', $scripturl, '\' + this.form.jumpto.options[this.form.jumpto.selectedIndex].value;" />
   </form>
  </div>
 <ul>
  ', template_button_strip($mod_buttons, 'bottom','false','admin'),'
 </ul>
 <div class="clearer"></div>
</div>';

    echo '<br />';

    if ($context['can_reply'] && !empty($options['display_quick_reply']))
      {
	echo '
 <a name="quickreply"></a>
 <table border="0" cellspacing="1" cellpadding="3" class="bordercolor" width="100%" style="clear: both;">
		<tr>
				<td colspan="2" class="catbg"><a href="javascript:swapQuickReply();"><img src="', $settings['images_url'], '/', $options['display_quick_reply'] == 2 ? 'collapse' : 'expand', '.gif" alt="+" id="quickReplyExpand" /></a> <a href="javascript:swapQuickReply();">', $txt['quick_reply_1'], '</a></td>
		</tr>
	<tr id="quickReplyOptions"', $options['display_quick_reply'] == 2 ? '' : ' style="display: none"', '>
		<td class="windowbg" width="25%" valign="top">', $txt['quick_reply_2'], $context['is_locked'] ? '<br /><br /><b>' . $txt['quick_reply_warning'] . '</b>' : '', '</td>
		<td class="windowbg" width="75%" align="center">
			<form action="', $scripturl, '?action=post2" method="post" accept-charset="', $context['character_set'], '" name="postmodify" id="postmodify" onsubmit="submitonce(this);" style="margin: 0;">
				<input type="hidden" name="topic" value="' . $context['current_topic'] . '" />
				<input type="hidden" name="subject" value="' . $context['response_prefix'] . $context['subject'] . '" />
				<input type="hidden" name="icon" value="xx" />
				<input type="hidden" name="notify" value="', $context['is_marked_notify'] || !empty($options['auto_notify']) ? '1' : '0', '" />
				<input type="hidden" name="goback" value="', empty($options['return_to_post']) ? '0' : '1', '" />
				<input type="hidden" name="num_replies" value="', $context['num_replies'], '" />
				<textarea cols="75" rows="7" style="width: 95%; height: 100px;" name="message" tabindex="1"></textarea><br />
				<input type="submit" name="post" value="' . $txt[105] . '" onclick="return submitThisOnce(this);" accesskey="s" tabindex="2" />
				<input type="submit" name="preview" value="' . $txt[507] . '" onclick="return submitThisOnce(this);" accesskey="p" tabindex="4" />';
	if ($context['show_spellchecking'])
	  echo '
				<input type="button" value="', $txt['spell_check'], '" onclick="spellCheck(\'postmodify\', \'message\');" tabindex="5"/>';
	echo '
				<input type="hidden" name="sc" value="' . $context['session_id'] . '" />
				<input type="hidden" name="seqnum" value="', $context['form_sequence_number'], '" />
			</form>
		</td>
	</tr>
 </table>';
      }
    if ($context['show_spellchecking'])
      echo '
 <form action="', $scripturl, '?action=spellcheck" method="post" accept-charset="', $context['character_set'], '" name="spell_form" id="spell_form" target="spellWindow"><input type="hidden" name="spellstring" value="" /></form>';
  }

  ?>