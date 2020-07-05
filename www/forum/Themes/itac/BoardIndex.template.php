<?php
// Version: 1.1; BoardIndex

function template_main()
{
  global $context, $settings, $options, $txt, $scripturl, $modSettings;
  /* Each category in categories is made up of:
   id, href, link, name, is_collapsed (is it collapsed?), can_collapse (is it okay if it is?),
   new (is it new?), collapse_href (href to collapse/expand), collapse_image (up/down image),
   and boards. (see below.) */
  $first = true;
  foreach ($context['categories'] as $category){
    echo '
  <table class="cat">
   <tr>
    <td colspan="5" class="catName">';
    $first = false;
    // If this category even can collapse, show a link to collapse it.
    if ($category['can_collapse']){
      echo '
     <a href="', $category['collapse_href'], '">', $category['collapse_image'], '</a>';
    }
    echo '
     ', $category['name'], '
    </td>
   </tr>';
    // Assuming the category hasn't been collapsed...
    if (!$category['is_collapsed'] &&
	count($category['boards'])>0){
      echo '
   <tr>
    <th class="tc1" colspan="2">Forum</th>
    <th class="tc2">',$txt[330],'</th>
    <th class="tc3">',$txt[21],'</th>
    <th class="tc4">',$txt[22],'</th>
   </tr>';
      /* Each board in each category's boards has:
       new (is it new?), id, name, description, moderators (see below), 
       link_moderators (just a list.),
       children (see below.), link_children (easier to use.), children_new 
       (are they new?),	topics ( of), posts ( of), link, href, and last_post.
      (see below.) */
      foreach ($category['boards'] as $board){
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
    <td class="tcl2',!empty($board['children'])?' hasChildren':'',($board['new']?' new':''),'">
     <a href="', $board['href'], '" name="b', $board['id'], '">', $board['name'], '</a><br />
     ', $board['description'];
	// Show the "Moderators: ". Each has name, href, link, and id. 
	// (but we're gonna use link_moderators.)
	if (!empty($board['moderators'])){
	  echo '
     <div class="modos">', count($board['moderators']) == 1 ? $txt[298] : $txt[299], ': ', implode(', ', $board['link_moderators']), '</div>';
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
    }
    echo '
  </table>';
  }
}
?>