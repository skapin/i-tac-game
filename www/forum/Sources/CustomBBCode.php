<?php

if (!defined('SMF'))
	die('Hacking attempt...');


function BrowseCustomTags()
{
	global $txt, $scripturl, $context, $settings, $sc, $modSettings, $db_prefix;

	// Prepare the array of tags.
	if (!empty($modSettings['customBBCode_count']))
	{
		$count = $modSettings['customBBCode_count'] + 1; // The +1 is to make sure $new_tag is set.
		for ($i = 1; $count; $i++)
		{
			if (isset($modSettings['customBBCode_tag_' . $i]))
			{
				$tags[$i] = $modSettings['customBBCode_tag_' . $i];
				$count--;
			}
			// Find first available
			elseif (!isset($new_tag))
			{
				$new_tag = $i;
				$count--;
			}
		}

		// Sort tags.
		asort($tags);
	}
	else
	{
		$modSettings['customBBCode_count'] = 0;
		$new_tag = 1;
		$tags = array();
	}

	// Saving?
	if (isset($_GET['save']))
	{
		$save_vars = array();

		// Mark the ones to delete.
		if (isset($_POST['delete']) && is_array($_POST['delete']))
		{
			$delete_tags = $_POST['delete'];
			$_POST['customBBCode_count'] = $modSettings['customBBCode_count'];
			$save_vars[] = array('int', 'customBBCode_count');
		}
		else
			$delete_tags = array();

		foreach ($tags as $i => $tag)
		{
			// Delete it?
			if (in_array($i, $delete_tags))
			{
				db_query("
					DELETE FROM {$db_prefix}settings
					WHERE variable LIKE 'customBBCode_%_$i'
					", __FILE__, __LINE__);

				$_POST['customBBCode_count']--;
			}
			else
			{
				$save_vars[] = array('check', 'customBBCode_enable_' . $i);
				$save_vars[] = array('check', 'customBBCode_button_' . $i);
			}
		}

		saveDBSettings($save_vars);

		redirectexit('action=featuresettings;sa=custombbc');
	}

	// Display all the tags.
	$output = '
			<table width="100%" border="0" cellpadding="2" cellspacing="1" style="font-weight: normal;">
				<tr class="catbg3">
					<td>' . 'Tag' . '</td>
					<td width="50%">' . 'BBCode' . '</td>
					<td width="5%" align="center">' . 'Enable' . '</td>
					<td width="5%" align="center">' . 'Button' . '</td>
					<td width="5%" align="center">' . 'Delete' . '</td>
				</tr>';

	foreach ($tags as $i => $tag)
	{
		$output .= '
				<tr>
					<td class="windowbg"><a href="' . $scripturl . '?action=featuresettings;sa=custombbc;tag=' . $i . '">' . $tag . '</a></td>
					<td class="windowbg">';

		if (empty($modSettings['customBBCode_type_' . $i]))
			$output .= '[' . $tag . ']{content}[/' . $tag . ']';
		elseif ($modSettings['customBBCode_type_' . $i] == 1)
			$output .= '[' . $tag . '={option}]{content}[/' . $tag . ']';
		elseif ($modSettings['customBBCode_type_' . $i] == 2)
			$output .= '[' . $tag . ']';

		$output .= '</td>
					<td class="windowbg" align="center">';

		$output .= '<input type="hidden" name="customBBCode_enable_' . $i . '" value="0" /><input type="checkbox" name="customBBCode_enable_' . $i . '" id="customBBCode_enable_' . $i . '" class="check"';
		if (!empty($modSettings['customBBCode_enable_' . $i]))
			$output .= ' checked />';
		else
			$output .= ' />';

		$output .= '</td>
					<td class="windowbg" align="center">';

		$output .= '<input type="hidden" name="customBBCode_button_' . $i . '" value="0" /><input type="checkbox" name="customBBCode_button_' . $i . '" id="customBBCode_button_' . $i . '" class="check"';
		if (!empty($modSettings['customBBCode_button_' . $i]))
			$output .= ' checked />';
		else
			$output .= ' />';

		$output .= '</td>
					<td class="windowbg" align="center"><input type="checkbox" name="delete[]" value="' . $i . '" class="check" /></td>
				</tr>';
	}

	$output .= '
			</table>';

	$config_vars = array($output, '<a href="' . $scripturl . '?action=featuresettings;sa=custombbc;tag=' . $new_tag . '">' . '[Create a new tag]' . '</a>');

	$context['post_url'] = $scripturl . '?action=featuresettings2;save;sa=custombbc';
	$context['settings_title'] = $txt['customBBCode_tabtitle'];

	prepareDBSettingContext($config_vars);
}


function EditCustomTag($i)
{
	global $txt, $scripturl, $context, $settings, $sc, $modSettings;

	$config_vars = array(
			array('text', 'customBBCode_tag_' . $i, null, $txt['customBBCode_tag']),
			array('text', 'customBBCode_description_' . $i, null, $txt['customBBCode_description']),
		'',
			array('select', 'customBBCode_type_' . $i, explode('|', $txt['customBBCode_type_options']), $txt['customBBCode_type']),
			array('select', 'customBBCode_parse_' . $i, explode('|', $txt['customBBCode_parse_options']), $txt['customBBCode_parse']),
			array('select', 'customBBCode_trim_' . $i, explode('|', $txt['customBBCode_trim_options']), $txt['customBBCode_trim']),
			array('check', 'customBBCode_block_level_' . $i, null, $txt['customBBCode_block_level']),
		'',
	);

	// Saving?
	if (isset($_GET['save']))
	{
		$save_vars = $config_vars;

		// If it's a new tag, increment the count.
		if (empty($modSettings['customBBCode_tag_' . $i]))
		{
			$save_vars[] = array('int', 'customBBCode_count');

			if (empty($modSettings['customBBCode_count']))
				$_POST['customBBCode_count'] = 1;
			else
				$_POST['customBBCode_count'] = $modSettings['customBBCode_count'] + 1;
		}

		// Make sure the name is lower case.
		$_POST['customBBCode_tag_' . $i] = trim(strtolower($_POST['customBBCode_tag_' . $i]));

		// If it's not closed tag.
		if (empty($_POST['customBBCode_type_' . $i]) || $_POST['customBBCode_type_' . $i] != 2)
		{
			if (empty($_POST['customBBCode_parse_' . $i]))
			{
				$save_vars[] = array('large_text', 'customBBCode_text_' . $i);

				$_POST['customBBCode_text_' . $i] = str_replace(array('{content}', '{option}'), array('$1', '$2'), $_POST['customBBCode_text_' . $i]);
			}
			else // Split it in two, where {content}.
			{
				$save_vars[] = array('large_text', 'customBBCode_before_' . $i);
				$save_vars[] = array('large_text', 'customBBCode_after_' . $i);

				$text = str_replace('{option}', '$1', $_POST['customBBCode_text_' . $i]);

				// Ok, let's find {content}.
				$pos = strpos($text, '{content}');
				if ($pos === false)
				{
					$_POST['customBBCode_before_' . $i] = $text;
					$_POST['customBBCode_after_' . $i] = '';
				}
				else
				{
					$_POST['customBBCode_before_' . $i] = substr($text, 0, $pos);
					$_POST['customBBCode_after_' . $i] = substr($text, $pos + 9);
				}
			}
		}
		else
		{
			$save_vars[] = array('large_text', 'customBBCode_text_' . $i);
		}

		saveDBSettings($save_vars);
		redirectexit('action=featuresettings;sa=custombbc');
	}

	if (empty($modSettings['customBBCode_parse_' . $i]))
	{
		if (isset($modSettings['customBBCode_text_' . $i]))
			$modSettings['customBBCode_text_' . $i] = str_replace(array('$1', '$2'), array('{content}', '{option}'), $modSettings['customBBCode_text_' . $i]);
	}
	else
	{
		$modSettings['customBBCode_text_' . $i] = '';

		if (isset($modSettings['customBBCode_before_' . $i]))
			$modSettings['customBBCode_text_' . $i] .= str_replace('$1', '{option}', $modSettings['customBBCode_before_' . $i]) . '{content}';

		if (isset($modSettings['customBBCode_after_' . $i]))
			$modSettings['customBBCode_text_' . $i] .= str_replace('$1', '{option}', $modSettings['customBBCode_after_' . $i]);
	}

	$config_vars[] = array('large_text', 'customBBCode_text_' . $i, 6, $txt['customBBCode_text']);

	$context['post_url'] = $scripturl . '?action=featuresettings2;save;sa=custombbc;tag=' . $i;
	$context['settings_title'] = $txt['customBBCode_tabtitle'];

	prepareDBSettingContext($config_vars);
}

?>