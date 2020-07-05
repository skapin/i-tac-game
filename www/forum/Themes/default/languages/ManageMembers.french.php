<?php
// Version: 1.1; ManageMembers

$txt['membergroups_title'] = 'Gestion des Groupes de Membres';
$txt['membergroups_description'] = 'Ce sont des ensembles de membres qui ont les m&ecirc;mes param&egrave;tres de permissions, apparence et droits d\'acc&egrave;s. Certains groupes sont bas&eacute;s sur le nombre de messages post&eacute;s par le membre. Vous pouvez assigner quelqu\'un &agrave; un groupe en s&eacute;lectionnant son profil et en modifiant le param&egrave;tre correspondant de son compte.';
$txt['membergroups_modify'] = 'Modifier';

$txt['membergroups_add_group'] = 'Ajouter un groupe de membres';
$txt['membergroups_regular'] = 'Groupes permanents';
$txt['membergroups_post'] = 'Groupes posteurs';

$txt['membergroups_new_group'] = 'Cr&eacute;er un nouveau groupe';
$txt['membergroups_group_name'] = 'Nom du groupe';
$txt['membergroups_new_board'] = 'Sections visibles';
$txt['membergroups_new_board_desc'] = 'Les sections que le groupe de membres peut voir';
$txt['membergroups_new_board_post_groups'] = '<em>Note&nbsp;: normalement, les groupes posteurs n\'ont pas besoin d\'un acc&eacute;s parce que le groupe dans lequel le membre est inclus lui donnera les autorisations n&eacute;cessaires.</em>';
$txt['membergroups_new_as_type'] = 'par type';
$txt['membergroups_new_as_copy'] = 'bas&eacute; sur';
$txt['membergroups_new_copy_none'] = '(aucun)';
$txt['membergroups_can_edit_later'] = 'Vous pourrez les modifier plus tard.';

$txt['membergroups_edit_group'] = 'Modifier le groupe de membre';
$txt['membergroups_edit_name'] = 'Nom du groupe';
$txt['membergroups_edit_post_group'] = 'Ce groupe est bas&eacute; sur le nombre de messages';
$txt['membergroups_min_posts'] = 'Nombre de messages requis';
$txt['membergroups_online_color'] = 'Couleur dans la liste des membres connect&eacute;s<br /><span class="smalltext" style="font-weight: normal;"><em>(en hexad&eacute;cimal, incluant le di&egrave;se)</em></span> ';
$txt['membergroups_star_count'] = 'Nombre d\'&eacute;toiles';
$txt['membergroups_star_image'] = 'Nom du fichier image';
$txt['membergroups_star_image_note'] = 'vous pouvez utiliser $language pour pointer vers un dossier diff&eacute;rent selon la langue du membre';
$txt['membergroups_max_messages'] = 'Nombre de MP maximum';
$txt['membergroups_max_messages_note'] = '0 = illimit&eacute;';
$txt['membergroups_edit_save'] = 'Sauvegarder';
$txt['membergroups_delete'] = 'Effacer';
$txt['membergroups_confirm_delete'] = '&Ecirc;tes-vous s&ucirc;r de vouloir effacer ce groupe&nbsp;?!';

$txt['membergroups_members_title'] = 'Montrer tous les membres du groupe';
$txt['membergroups_members_no_members'] = 'Ce groupe est actuellement vide';
$txt['membergroups_members_add_title'] = 'Ajouter un membre &agrave; ce groupe';
$txt['membergroups_members_add_desc'] = 'Liste des membres &agrave; ajouter';
$txt['membergroups_members_add'] = 'Ajouter les membres';
$txt['membergroups_members_remove'] = 'Enlever du groupe';

$txt['membergroups_postgroups'] = 'Groupes Posteurs';

$txt['membergroups_edit_groups'] = 'Modifier les groupes de membre';
$txt['membergroups_settings'] = 'R&eacute;glages des groupes de membres';
$txt['groups_manage_membergroups'] = 'Groupes autoris&eacute;s &agrave; modifier les groupes de membres';
$txt['membergroups_settings_submit'] = 'Enregistrer';
$txt['membergroups_select_permission_type'] = 'S&eacute;lectionner un profil de permissions';
$txt['membergroups_images_url'] = '{URL du th&egrave;me}/images/';
$txt['membergroups_select_visible_boards'] = 'Montrer les sections';

$txt['admin_browse_approve'] = 'Membres dont le compte est en attente d\'approbation';
$txt['admin_browse_approve_desc'] = 'Ici vous pouvez g&eacute;rer tous les membres en attente d\'approbation de leur compte.';
$txt['admin_browse_activate'] = 'Membres dont le compte est en attente d\'activation';
$txt['admin_browse_activate_desc'] = 'Cette interface liste tous les membres qui n\'ont pas encore activ&eacute; leur compte sur votre forum.';
$txt['admin_browse_awaiting_approval'] = 'En attente d\'approbation <span style="font-weight: normal">(%d)</span>';
$txt['admin_browse_awaiting_activate'] = 'En attente d\'activation <span style="font-weight: normal">(%d)</span>';

$txt['admin_browse_username'] = 'Identifiant';
$txt['admin_browse_email'] = 'Adresse courriel';
$txt['admin_browse_ip'] = 'Adresse IP';
$txt['admin_browse_registered'] = 'Inscrit';
$txt['admin_browse_id'] = 'ID';
$txt['admin_browse_with_selected'] = 'Avec la s&eacute;lection';
$txt['admin_browse_no_members_approval'] = 'Aucun compte n\'est actuellement en attente d\'approbation.';
$txt['admin_browse_no_members_activate'] = 'Aucun compte n\'est actuellement en attente d\'activation.';

// Don't use entities in the below strings, except the main ones. (lt, gt, quot.)
$txt['admin_browse_warn'] = 'tous les membres sélectionnés?';
$txt['admin_browse_outstanding_warn'] = 'tous les membres affectés?';
$txt['admin_browse_w_approve'] = 'Approuver';
$txt['admin_browse_w_activate'] = 'Activer';
$txt['admin_browse_w_delete'] = 'Supprimer';
$txt['admin_browse_w_reject'] = 'Rejeter';
$txt['admin_browse_w_remind'] = 'Rappeler';
$txt['admin_browse_w_approve_deletion'] = 'Approuver (Supression de comptes)';
$txt['admin_browse_w_email'] = 'et envoyer un courriel';
$txt['admin_browse_w_approve_require_activate'] = 'Approuver et requérir une activation';

$txt['admin_browse_filter_by'] = 'Filtrer par';
$txt['admin_browse_filter_show'] = 'Afficher';
$txt['admin_browse_filter_type_0'] = 'les nouveaux comptes non activés';
$txt['admin_browse_filter_type_2'] = 'les changements d\'adresse courriel non vérifiés';
$txt['admin_browse_filter_type_3'] = 'les nouveaux comptes non approuvés';
$txt['admin_browse_filter_type_4'] = 'les suppressions de comptes non approuvées';
$txt['admin_browse_filter_type_5'] = 'les comptes non approuvés notés "Sous l\'âge minimum"';

$txt['admin_browse_outstanding'] = 'Membres exceptionnels';
$txt['admin_browse_outstanding_days_1'] = 'Avec tous les membres inscrits depuis plus longtemps que';
$txt['admin_browse_outstanding_days_2'] = 'jours';
$txt['admin_browse_outstanding_perform'] = 'Effectuer l\'action suivante';
$txt['admin_browse_outstanding_go'] = 'Effectuer l\'action';

// Use numeric entities in the below nine strings.
$txt['admin_approve_reject'] = "Inscription rejet&#233;e";
$txt['admin_approve_reject_desc'] = "Malheureureusement, votre application pour rejoindre " . $context['forum_name'] . " a &#233;t&#233; rejet&#233;e.";
$txt['admin_approve_delete'] = "Compte supprim&#233;";
$txt['admin_approve_delete_desc'] = "Votre compte sur " . $context['forum_name'] . "a &#233;t&#233; supprim&#233;.  Ceci peut &#234;tre caus&#233; parce que vous n\'avez jamais activ&#233; votre compte, auquel cas vous devriez &#234;tre en mesure de vous inscrire &#224; nouveau.";
$txt['admin_approve_remind'] = "Rappel d\'inscription";
$txt['admin_approve_remind_desc'] = "Vous n\'avez toujours pas activ&#233; votre compte sur";
$txt['admin_approve_remind_desc2'] = "Veuillez cliquer sur le lien suivant pour activer votre compte :";
$txt['admin_approve_accept_desc'] = "Votre compte a &#233;t&#233; activ&#233; manuellement par l\'administrateur et vous pouvez d&#232;s maintenant vous connecter et poster.";
$txt['admin_approve_require_activation'] = "Votre compte sur " . $context['forum_name'] . " a &#233;t&#233; approuv&#233; par l'administrateur du forum et doit maintenant &#234;tre activ&#233; avant de que vous puissiez poster.";

?>