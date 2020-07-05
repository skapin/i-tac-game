<?php
// Version: 1.1; ManageBoards

$txt[41] = 'Gestion des Sections et des Cat&eacute;gories';
$txt[43] = 'Ordre';
$txt[44] = 'Nom complet';
$txt[672] = 'C\'est le nom qu\'elle portera.';
$txt[677] = 'Modifiez vos cat&eacute;gories (<em>category</em>) et sections (<em>board</em>) ici. Listez les mod&eacute;rateurs comme ceci&nbsp;: <em>&quot;identifiant1&quot;, &quot;identifiant2&quot;</em>. (utilisez les identifiants des membres, pas les pseudonymes&nbsp;!)<br />Pour cr&eacute;er une nouvelle section, cliquez sur le bouton \'Ajouter une section\'. Pour faire une sous-section (<em>child board</em>), choisissez \'Sous-section de ...\' dans la liste d&eacute;roulante d\'ordre lors de la cr&eacute;ation d\'une section.';
$txt['parent_members_only'] = 'Membres r&eacute;guliers';
$txt['parent_guests_only'] = 'Invit&eacute;s';
$txt['catConfirm'] = 'Voulez-vous r&eacute;ellement effacer cette cat&eacute;gorie&nbsp;?';
$txt['boardConfirm'] = 'Voulez-vous r&eacute;ellement effacer cette section&nbsp;?';

$txt['catEdit'] = 'Modifier la Cat&eacute;gorie';
$txt['boardsEdit'] = 'Modifier les Sections';
$txt['collapse_enable'] = 'R&eacute;tractable';
$txt['collapse_desc'] = 'Permettre aux membres de r&eacute;duire cette cat&eacute;gorie&nbsp;?';
$txt['catModify'] = '(modifier)';

$txt['mboards_order_after'] = 'Apr&egrave;s ';
$txt['mboards_order_inside'] = 'Dans ';
$txt['mboards_order_first'] = '&Agrave; la premi&egrave;re place';

$txt['mboards_new_cat'] = 'Cr&eacute;er une nouvelle cat&eacute;gorie';
$txt['mboards_new_board'] = 'Ajouter une section';
$txt['mboards_new_cat_name'] = 'Nouvelle cat&eacute;gorie';
$txt['mboards_add_cat_button'] = 'Ajouter une cat&eacute;gorie';
$txt['mboards_new_board_name'] = 'Nouvelle section';

$txt['mboards_name'] = 'Nom';
$txt['mboards_modify'] = 'modifier';
$txt['mboards_permissions'] = 'permissions';
// Don't use entities in the below string.
$txt['mboards_permissions_confirm'] = 'Etes-vous sûr de vouloir changer le fonctionnement de cette section pour utiliser des permissions locales ?';

$txt['mboards_delete_cat'] = 'Effacer la cat&eacute;gorie';
$txt['mboards_delete_board'] = 'Effacer la section';

$txt['mboards_delete_cat_contains'] = 'Supprimer cette cat&eacute;gorie effacera aussi les sections suivantes, ainsi que leurs fils de discussion, messages et fichiers joints';
$txt['mboards_delete_option1'] = 'Supprimer la cat&eacute;gorie et toutes les sections qu\'elle contient.';
$txt['mboards_delete_option2'] = 'Supprimer la cat&eacute;gorie et d&eacute;placer toutes ses sections vers';
$txt['mboards_delete_error'] = 'Aucune cat&eacute;gorie s&eacute;lectionn&eacute;e&nbsp;!';
$txt['mboards_delete_board_contains'] = 'Supprimer cette section d&eacute;placera les sous-sections suivantes, ainsi que tous les fils de discussion, messages et fichiers joints qu\'elles contiennent';
$txt['mboards_delete_board_option1'] = 'Supprimer la section et d&eacute;placer les sous-sections au niveau de la cat&eacute;gorie.';
$txt['mboards_delete_board_option2'] = 'Supprimer la section et d&eacute;placer les sous-sections dans&nbsp;';
$txt['mboards_delete_board_error'] = 'Aucune section s&eacute;lectionn&eacute;e&nbsp;!';
$txt['mboards_delete_what_do'] = 'Veuillez s&eacute;lectionner ce que vous d&eacute;sirez faire avec ces sections';
$txt['mboards_delete_confirm'] = 'Confirmer';
$txt['mboards_delete_cancel'] = 'Annuler';

$txt['mboards_category'] = 'Cat&eacute;gorie';
$txt['mboards_description'] = 'Description';
$txt['mboards_description_desc'] = 'Une courte description de cette section.';
$txt['mboards_groups'] = 'Groupes autoris&eacute;s';
$txt['mboards_groups_desc'] = 'Groupes autoris&eacute;s &agrave; voir et &agrave; acc&eacute;der &agrave; cette section.<br /><em>Note&nbsp;: si le membre est dans l\'un ou l\'autre des groupes autoris&eacute;s ou que &quot;Membres r&eacute;guliers&quot; est s&eacute;lectionn&eacute;, le membre aura acc&egrave;s &agrave; la section.</em>';
$txt['mboards_groups_post_group'] = 'Ce groupe est un groupe posteur.';
$txt['mboards_permissions_title'] = 'Acc&egrave;s &agrave; la section';
$txt['mboards_permissions_desc'] = 'S&eacute;lectionnez les restrictions pour cette section. Ces restrictions ne s\'appliquent pas aux mod&eacute;rateurs et administrateurs.';
$txt['mboards_moderators'] = 'Mod&eacute;rateurs';
$txt['mboards_moderators_desc'] = 'Membres additionnels ayant des privil&egrave;ges de mod&eacute;rateurs locaux sur cette section seulement.  Veuillez noter que les administrateurs et les mod&eacute;rateurs globaux n\'ont pas &agrave; &ecirc;tre list&eacute;s ici.';
$txt['mboards_count_posts'] = 'Comptabiliser les messages';
$txt['mboards_count_posts_desc'] = 'Les nouveaux fils de discussion et les r&eacute;ponses font augmenter le compteur de messages des membres.';
$txt['mboards_unchanged'] = 'Inchang&eacute;';
$txt['mboards_theme'] = 'Th&egrave;me de la section';
$txt['mboards_theme_desc'] = 'Applique un th&egrave;me sp&eacute;cifique &agrave; cette section uniquement.';
$txt['mboards_theme_default'] = '(Th&egrave;me par d&eacute;faut.)';
$txt['mboards_override_theme'] = 'Surpasser le th&egrave;me du membre';
$txt['mboards_override_theme_desc'] = 'Force le changement du th&egrave;me de cette section pour celui sp&eacute;cifi&eacute; pr&eacute;c&eacute;demment, m&ecirc;me si le membre a choisi de ne pas utiliser les r&eacute;glages par d&eacute;faut du forum.';

$txt['mboards_order_before'] = 'Avant';
$txt['mboards_order_child_of'] = 'Sous-section de';
$txt['mboards_order_in_category'] = 'Dans la cat&eacute;gorie';
$txt['mboards_current_position'] = 'Position actuelle';
$txt['no_valid_parent'] = 'La section %s n\'a pas de section parente valide. Utilisez la fonction \'Chercher et r&eacute;p&eacute;rez les erreurs\' du panneau <em>Maintenance du forum</em> pour corriger cela.';

$txt['mboards_settings_desc'] = 'Modifier les param&egrave;tres g&eacute;n&eacute;raux des cat&eacute;gories et des sections.';
$txt['groups_manage_boards'] = 'Groupes de membres autoris&eacute;s &agrave; g&eacute;rer les sections et cat&eacute;gories';
$txt['mboards_settings_submit'] = 'Sauvegarder';
$txt['recycle_enable'] = 'Activer le recyclage des fils de discussion effac&eacute;s';
$txt['recycle_board'] = 'Section pour les fils de discussion recycl&eacute;s';
$txt['countChildPosts'] = 'Compter les messages des sous-sections dans le total des messages de sa section parente';

$txt['mboards_select_destination'] = 'S&eacute;lectionner la destination pour la section \'<b>%1$s</b>\'';
$txt['mboards_cancel_moving'] = 'Annuler le d&eacute;placement';
$txt['mboards_move'] = 'D&eacute;placer';

?>