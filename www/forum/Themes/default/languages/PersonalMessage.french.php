<?php
// Version: 1.1; PersonalMessage

$txt[143] = 'Accueil des messages personnels';
$txt[148] = 'Envoyer un message';
$txt[150] = '&Agrave;';
$txt[1502] = 'Bcc';
$txt[316] = 'R&eacute;ception';
$txt[320] = 'Envois';
$txt[321] = 'Nouveau message';
$txt[411] = 'Effacer des messages';
// Don't translate "PMBOX" in this string.
$txt[412] = 'Effacer tous les messages dans votre PMBOX';
$txt[413] = '&Ecirc;tes vous s&ucirc;r de vouloir effacer tous les messages&nbsp;?';
$txt[535] = 'Destinataire';
// Don't translate the word "SUBJECT" here, as it is used to format the message - use numeric entities as well.
$txt[561] = "Nouveau message personnel: SUBJECT";
// Don't translate SENDER or MESSAGE in this language string; they are replaced with the corresponding text - use numeric entities too.
$txt[562] = "Vous venez tout juste de recevoir un message personnel de la part de SENDER sur " . $context['forum_name'] . " . " ."\n\n" . "IMPORTANT: Rappelez-vous que ceci n\'est qu\'une notification.  Ne r&#233;pondez pas &#224; ce courriel. " . "\n\n" . "Le message qui vous a &#233;t&#233; envoy&#233; est le suivant:" . "\n\n" . "MESSAGE";
$txt[748] = '(destinataires multiples&nbsp;: \'nom1, nom2\')';
// Use numeric entities in the below string.
$txt['instant_reply'] = "R&#233;pondez &#224; ce message personnel ici:";

$txt['smf249'] = '&Ecirc;tes-vous s&ucirc;r de vouloir effacer tous les messages personnels s&eacute;lectionn&eacute;s&nbsp;?';

$txt['sent_to'] = 'Envoy&eacute; &agrave;';
$txt['reply_to_all'] = 'R&eacute;pondre &agrave; tous';

$txt['pm_capacity'] = 'Capacit&eacute;';
$txt['pm_currently_using'] = '%s messages, %s%% pleine.';

$txt['pm_error_user_not_found'] = 'Impossible de trouver le membre \'%s\'.';
$txt['pm_error_ignored_by_user'] = 'Le membre  \'%s\' a bloqu&eacute; votre message personnel.';
$txt['pm_error_data_limit_reached'] = 'Le message n\'a pas pu &ecirc;tre envoy&eacute; &agrave;  \'%s\' car sa bo&icirc;te de r&eacute;ception est pleine&nbsp;!';
$txt['pm_successfully_sent'] = 'Le message a &eacute;t&eacute; envoy&eacute; &agrave; \'%s\'.';
$txt['pm_too_many_recipients'] = 'Vous ne pouvez pas envoyer de messages personnels &agrave; plus de %d destinataire(s) pour le moment.';

$txt['pm_too_many_per_hour'] = 'Vous avez d&eacute;pas&eacute; la limite de %d messages personnels par heure.';

$txt['pm_send_report'] = 'Rapport d\'envoi';
$txt['pm_save_outbox'] = 'Sauvegarder une copie dans ma bo&icirc;te d\'envoi';
$txt['pm_undisclosed_recipients'] = 'Destinataires non r&eacute;v&eacute;l&eacute;s';

$txt['pm_read'] = 'Lu';
$txt['pm_replied'] = 'R&eacute;pondu &agrave;';

// Message Pruning.
$txt['pm_prune'] = '&Eacute;laguer la bo&icirc;te';
$txt['pm_prune_desc1'] = 'Effacer tous les messages personnels ant&eacute;rieurs &agrave;';
$txt['pm_prune_desc2'] = 'jours.';
$txt['pm_prune_warning'] = '&Ecirc;tes-vous certain de vouloir &eacute;laguer votre bo&icirc;te&nbsp;?';

// Actions Drop Down.
$txt['pm_actions_title'] = 'Actions additionnelles';
$txt['pm_actions_delete_selected'] = 'Effacer la s&eacute;lection';
$txt['pm_actions_filter_by_label'] = 'Filtrer par label';
$txt['pm_actions_go'] = 'Ex&eacute;cuter';

// Manage Labels Screen.
$txt['pm_apply'] = 'Appliquer';
$txt['pm_manage_labels'] = 'G&eacute;rer les labels';
$txt['pm_labels_delete'] = '&Ecirc;tes-vous s&ucirc;r de vouloir effacer les labels s&eacute;ectionn&eacute;s&nbsp;?';
$txt['pm_labels_desc'] = 'Ici vous pouvez ajouter, modifier et supprimer les labels utilis&eacute;s dans votre centre de messagerie personnelle.';
$txt['pm_label_add_new'] = 'Ajouter un nouveau label';
$txt['pm_label_name'] = 'Nom du label';
$txt['pm_labels_no_exist'] = 'Vous n\'avez actuellement aucun label param&eacute;tr&eacute;&nbsp;!';

// Labeling Drop Down.
$txt['pm_current_label'] = 'Label';
$txt['pm_msg_label_title'] = 'Attribuer un label au message';
$txt['pm_msg_label_apply'] = 'Ajouter un label';
$txt['pm_msg_label_remove'] = 'Enlever un label';
$txt['pm_msg_label_inbox'] = 'Bo&icirc;te de r&eacute;ception';
$txt['pm_sel_label_title'] = 'Label s&eacute;electionn&eacute;';
$txt['labels_too_many'] = 'D&eacute;sol&eacute;, %s messages poss&egrave;dent le nombre maximal de labels autoris&eacute;s!';

// Sidebar Headings.
$txt['pm_labels'] = 'Labels';
$txt['pm_messages'] = 'Messages';
$txt['pm_preferences'] = 'Pr&eacute;f&eacute;rences';

$txt['pm_is_replied_to'] = 'Vous avez transf&eacute;r&eacute; ou r&eacute;pondu &agrave; ce message.';

// Reporting messages.
$txt['pm_report_to_admin'] = 'Rapporter &agrave; l\'administrateur';
$txt['pm_report_title'] = 'Rapporter un message personnel';
$txt['pm_report_desc'] = 'Depuis cette page vous pouvez rapporter le message personnel que vous avez re&ccedil;u &agrave; l\'&eacute;quipe d\'administration du forum. Veuillez vous assurer d\'inclure une description de la raison de ce rapport de message, puisque &ccedil;a sera envoy&eacute; avec le contenu du message original.';
$txt['pm_report_admins'] = 'Administrateur &agrave; aviser';
$txt['pm_report_all_admins'] = 'Envoyer &agrave; tous les administrateurs';
$txt['pm_report_reason'] = 'Raison du rapport de ce message';
$txt['pm_report_message'] = 'Rapporter le message';

// Important - The following strings should use numeric entities.
$txt['pm_report_pm_subject'] = '[RAPPORT] ';
// In the below string, do not translate "{REPORTER}" or "{SENDER}".
$txt['pm_report_pm_user_sent'] = '{REPORTER} a rapport&#233; le message personnel suivant, envoy&#233; par {SENDER}, pour la raison suivante :';
$txt['pm_report_pm_other_recipients'] = 'Les autres destinataires de ce message sont :';
$txt['pm_report_pm_hidden'] = '%d destinataire(s) cach&#233;(s)';
$txt['pm_report_pm_unedited_below'] = 'Ci-dessous ce trouve le contenu original du message personnel rapport&#233; :';
$txt['pm_report_pm_sent'] = 'Envoy&#233; :';

$txt['pm_report_done'] = 'Merci d\'avoir soumis ce rapport. Vous devriez recevoir des nouvelles des administrateurs rapidement';
$txt['pm_report_return'] = 'Retourner &agrave; la bo&icirc;te de r&eacute;ception';

$txt['pm_search_title'] = 'Rechercher dans la bo&icirc;te de messages personnels';
$txt['pm_search_bar_title'] = 'Rechercher des Messages';

$txt['pm_search_text'] = 'Rechercher pour';
$txt['pm_search_go'] = 'Rechercher';
$txt['pm_search_advanced'] = 'Recherche avanc&eacute;e';
$txt['pm_search_user'] = 'par utilisateur';
$txt['pm_search_match_all'] = 'Correspondre tous les mots';
$txt['pm_search_match_any'] = 'Correspondre n\'importe quel mot';
$txt['pm_search_options'] = 'Options';
$txt['pm_search_post_age'] = '&Acirc;ge';
$txt['pm_search_show_complete'] = 'Montrer tout le message dans les r&eacute;sultats.';
$txt['pm_search_subject_only'] = 'Chercher par sujet et auteur seulement.';
$txt['pm_search_between'] = 'Entre';
$txt['pm_search_between_and'] = 'et';
$txt['pm_search_between_days'] = 'jours';
$txt['pm_search_order'] = 'Ordonner les r&eacute;sultats par';
$txt['pm_search_choose_label'] = 'Choisir les labels &agrave; rechercher, ou rechercher partout';
$txt['pm_search_results'] = 'R&eacute;sultats des Recherches';
$txt['pm_search_none_found'] = 'Aucun Message Trouv&eacute;';

$txt['pm_search_orderby_relevant_first'] = 'Plus significatif en premier';
$txt['pm_search_orderby_recent_first'] = 'Plus r&eacute;cent en premier';
$txt['pm_search_orderby_old_first'] = 'Plus ancien en premier';

$txt['pm_visual_verification_label'] = 'V&eacute;rification';
$txt['pm_visual_verification_desc'] = 'Veuillez saisir le code contenu dans l\'image ci-dessus pour envoyer ce message priv&eacute;.';
$txt['pm_visual_verification_listen'] = 'Ecouter les lettres';

?>