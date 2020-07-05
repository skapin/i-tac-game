<?php
// Version: 1.1; ManagePermissions

$txt['permissions_title'] = 'Gestion des Permissions';
$txt['permissions_modify'] = 'Modifier';
$txt['permissions_access'] = 'Acc&egrave;de';
$txt['permissions_allowed'] = 'Autoris&eacute;es';
$txt['permissions_denied'] = 'Refus&eacute;es';

$txt['permissions_switch'] = 'Transfert vers';
$txt['permissions_global'] = 'Global';
$txt['permissions_local'] = 'Local';

$txt['permissions_groups'] = 'Permissions par groupe de membres';
$txt['permissions_all'] = 'tous';
$txt['permissions_none'] = 'aucun';
$txt['permissions_set_permissions'] = '&Eacute;tablir les permissions';

$txt['permissions_with_selection'] = 'Pour la s&eacute;lection';
$txt['permissions_apply_pre_defined'] = 'Appliquer le profil de permissions pr&eacute;d&eacute;fini';
$txt['permissions_select_pre_defined'] = 'Choisir un profil pr&eacute;d&eacute;fini';
$txt['permissions_copy_from_board'] = 'Copier les permissions de cette section';
$txt['permissions_select_board'] = 'S&eacute;lectionnez une section';
$txt['permissions_like_group'] = 'Donner les permissions comme ce groupe';
$txt['permissions_select_membergroup'] = 'Choisir un groupe de membres';
$txt['permissions_add'] = 'Ajouter une permission';
$txt['permissions_remove'] = 'Refuser une permission';
$txt['permissions_deny'] = 'Interdire la permission';
$txt['permissions_select_permission'] = 'Choisir une permission';

// All of the following block of strings should not use entities, instead use \\" for &quot; etc.
$txt['permissions_only_one_option'] = 'Vous ne pouvez choisir qu\'une seule action pour modifier les permissions';
$txt['permissions_no_action'] = 'Aucune action choisie';
$txt['permissions_deny_dangerous'] = 'Vous êtes sur le point d\'interdire une ou plusieurs permissions.\\nCeci peut être dangereux et causer des résultats inattendus si vous ne vous êtes pas assuré que personne n\'est \\"accidentellement\\" dans le ou les groupes auxquels vous interdisez les permissions.\\n\\nÊtes-vous sûr de vouloir continuer?';

$txt['permissions_boards'] = 'Permissions par section';

$txt['permissions_modify_group'] = 'Modifier un Groupe';
$txt['permissions_general'] = 'Permissions G&eacute;n&eacute;rales';
$txt['permissions_board'] = 'Permissions Globales du Forum';
$txt['permissions_commit'] = 'Sauver les Changements';
$txt['permissions_modify_local'] = 'Modifier les Permissions locales';
$txt['permissions_on'] = 'du forum';
$txt['permissions_local_for'] = 'Permissions locales pour groupe';
$txt['permissions_option_on'] = 'P';
$txt['permissions_option_off'] = 'R';
$txt['permissions_option_deny'] = 'I';
$txt['permissions_option_desc'] = 'Pour chaque groupe, vous pouvez choisir soit \'Permettre\' (P), \'Refuser\' (R), ou <span style="color: red;">\'Interdire\' (I)</span>.<br /><br />Rappelez-vous que si vous interdisez une permission, tous les membres - qu\'ils soient mod&eacute;rateurs ou autres - pr&eacute;sents dans ce groupe se verront refuser la permission aussi.<br />Pour cette raison, vous devriez interdire avec pr&eacute;caution, et seulement lorsque <b>n&eacute;cessaire</b>. Refuser, autrement, interdit &agrave; moins qu\'une information contraire soit pr&eacute;sente.';

$txt['permissiongroup_general'] = 'G&eacute;n&eacute;ral';
$txt['permissionname_view_stats'] = 'Voir les stats du forum';
$txt['permissionhelp_view_stats'] = 'Les stats du forum sont une page r&eacute;sumant toutes les statistiques du forum&nbsp;: nombre de membres, nombre  de messages par jour et plusieurs Top 10. Autoriser cette permission ajoute un lien en bas de l\'accueil du forum (\'[+ de Stats]\').';
$txt['permissionname_view_mlist'] = 'Voir la liste des membres';
$txt['permissionhelp_view_mlist'] = 'La liste des membres affiche tous les membres qui se sont inscrits sur votre forum. La liste peut &ecirc;tre class&eacute;e et scrut&eacute;e. La liste des membres est accessible depuis l\'accueil du forum et la page de stats, en cliquant sur le nombre de membres.';
$txt['permissionname_who_view'] = 'Voir \'Qui est en ligne&nbsp;?\'';
$txt['permissionhelp_who_view'] = '\'Qui est en ligne&nbsp;?\' affiche tous les membres qui sont actuellement connect&eacute;s et ce qu\'ils font en ce moment. Cette permission ne fonctionnera que si vous l\'avez aussi valid&eacute;e dans \'R&eacute;glages et options\'. Vous pouvez acc&eacute;der &agrave; la page \'Qui est en ligne&nbsp;?\' en cliquant sur le lien dans la section \'Membres en ligne\' sur l\'accueil du forum. M&ecirc;me si ce n\'est pas permis, les membres pourront tout de m&ecirc;me voir qui est en ligne, mais ne pourront pas voir o&ugrave; ils sont.';
$txt['permissionname_search_posts'] = 'Rechercher des messages ou des fils de discussion';
$txt['permissionhelp_search_posts'] = 'La permission de recherche autorise l\'utilisateur &agrave; rechercher dans toutes les sections auxquels il peut acc&eacute;der. Quand la permission de recherche est activ&eacute;e, un bouton \'Recherche\' sera ajout&eacute; &agrave; la barre de menu principal du forum.';
$txt['permissionname_karma_edit'] = 'Modifier le Karma des autres';
$txt['permissionhelp_karma_edit'] = 'Le Karma est une fonctionnalit&eacute; qui affiche la popularit&eacute; d\'un membre. Pour l\'utiliser, vous devez l\'avoir activ&eacute;e dans \'Configuration des caract&eacute;ristiques et options\'. Cette permission autorise un groupe de membres &agrave; ajouter un vote. Cette permission n\'a pas d\'effet sur les invit&eacute;s.';

$txt['permissiongroup_pm'] = 'Messagerie personnelle';
$txt['permissionname_pm_read'] = 'Lire les messages personnels';
$txt['permissionhelp_pm_read'] = 'Cette permission autorise les membres &agrave; acc&eacute;der &agrave; la messagerie personnelle et &agrave; lire leurs messages personnels. Sans cette permission, un membre ne peut pas envoyer de messages personnels.';
$txt['permissionname_pm_send'] = 'Envoyer des messages personnels';
$txt['permissionhelp_pm_send'] = 'Envoyer des messages personnels &agrave; d\'autres membres inscrits. N&eacute;cessite la permission \'Lire des messages personnels\'.';

$txt['permissiongroup_calendar'] = 'Calendrier';
$txt['permissionname_calendar_view'] = 'Voir le calendrier';
$txt['permissionhelp_calendar_view'] = 'Le calendrier affiche pour chaque mois les anniversaires, les &eacute;v&egrave;nements et les jours f&eacute;ri&eacute;. Cette permission autorise l\'acc&egrave;s &agrave; ce calendrier. Quand cette permission est valid&eacute;e, un bouton est ajout&eacute; &agrave; la barre de menu principal et une liste est affich&eacute;e au bas de l\'accueuil du forum avec les anniversaires courants et &agrave; venir, &eacute;v&egrave;nements et f&ecirc;tes. Le calendrier doit &ecirc;tre activ&eacute; depuis \'Gestion des caract&eacute;ristiques et les options\'.';
$txt['permissionname_calendar_post'] = 'Cr&eacute;er des &eacute;v&egrave;nements dans le calendrier';
$txt['permissionhelp_calendar_post'] = 'Un &eacute;v&egrave;nement est un fil de discussion li&eacute; &agrave; une certaine date ou plage de dates. Vous pouvez cr&eacute;er des &eacute;v&egrave;nements depuis le calendrier. Un &eacute;v&egrave;nement ne peut &ecirc;tre cr&eacute;&eacute; que par un utilisateur qui a la permission de poster des nouveaux fils de discussion.';
$txt['permissionname_calendar_edit'] = 'Modifier les &eacute;v&egrave;nements du calendrier';
$txt['permissionhelp_calendar_edit'] = 'Un &eacute;v&egrave;nement est un fil de discussion li&eacute; &agrave; une certaine date ou plage de dates. Il peut &ecirc;tre modifi&eacute; en cliquant l\'ast&eacute;risque rouge (<span style="color: red;">*</span>) sur la page du calendrier. Pour modifier un &eacute;v&egrave;nement, l\'utilisateur doit avoir les permissions suffisantes pour modifier le premier message du fil de discussion li&eacute; &agrave; cet &eacute;v&egrave;nement.';
$txt['permissionname_calendar_edit_own'] = '&Eacute;v&egrave;nements personnels';
$txt['permissionname_calendar_edit_any'] = 'Tous les &eacute;v&egrave;nements';

$txt['permissiongroup_maintenance'] = 'Administration du forum';
$txt['permissionname_admin_forum'] = 'Administrer le forum et la base de donn&eacute;es';
$txt['permissionhelp_admin_forum'] = 'Cette permission autorise un utilisateur &agrave;&nbsp;:<ul><li>modifier les param&egrave;tres du forum, de la base de donn&eacute;es et du th&egrave;me</li><li>g&eacute;rer les paquets</li><li>utiliser les outils de maintenance du forum et de la base de donn&eacute;es</li><li>voir le Journal de Mod&eacute;ration et d\'Erreurs.</li></ul> Utilisez cette permission avec pr&eacute;caution, elle est tr&egrave;s puissante.';
$txt['permissionname_manage_boards'] = 'Gestion des sections et cat&eacute;gories';
$txt['permissionhelp_manage_boards'] = 'Cette permission autorise la cr&eacute;ation, la modification et la suppression des sections et cat&eacute;gories.';
$txt['permissionname_manage_attachments'] = 'Gestion des fichiers joints et avatars';
$txt['permissionhelp_manage_attachments'] = 'Cette permission autorise l\'acc&egrave;s au gestionnaire de fichiers joints, o&ugrave; tous les fichiers attach&eacute;s et avatars sont list&eacute;s et peuvent &ecirc;tre supprim&eacute;s.';
$txt['permissionname_manage_smileys'] = 'Gestion des &eacute;motic&ocirc;nes';
$txt['permissionhelp_manage_smileys'] = 'Ceci permet l\'acc&egrave;s au gestionnaire des &eacute;motic&ocirc;nes. Dans le centre de gestion des &eacute;motic&ocirc;nes, vous pouvez ajouter, modifier et supprimer des &eacute;motic&ocirc;nes et jeux d\'&eacute;motic&ocirc;nes.';
$txt['permissionname_edit_news'] = 'Modifier les nouvelles';
$txt['permissionhelp_edit_news'] = 'La fonction \'Nouvelles\' affiche une ligne d\'informations al&eacute;atoire sur chaque page. Pour l\'utiliser, activez la dans les param&egrave;tres du forum.';

$txt['permissiongroup_member_admin'] = 'Administration des membres';
$txt['permissionname_moderate_forum'] = 'Gestion des membres du forum';
$txt['permissionhelp_moderate_forum'] = 'Cette permission inclut toutes les fonctions importantes de mod&eacute;ration des membres&nbsp;:<ul><li>acc&egrave;s aux inscriptions</li><li>acc&egrave;s au panneau de gestion des membres</li><li>acc&egrave;s aux informations de profil &eacute;tendu, ainsi qu\'&agrave; la traque des adresses IP et utilisateurs et au statut invisible</li><li>activation de comptes</li><li>r&eacute;ception des notifications d\'inscription et approbation des inscriptions</li><li>immunit&eacute; contre le rejet des MP</li><li>plusieurs autres caract&eacute;ristiques.</li></ul>';
$txt['permissionname_manage_membergroups'] = 'Gestion et assignation des groupes de membres';
$txt['permissionhelp_manage_membergroups'] = 'Cette permission permet &agrave; l\'utilisateur de modifier les groupes de membres et d\'assigner des membres &agrave; certains groupes.';
$txt['permissionname_manage_permissions'] = 'Gestion des permissions';
$txt['permissionhelp_manage_permissions'] = 'Cette permission permet &agrave; un utilisateur de modifier toutes les permissions d\'un groupe de membres, globalement ou pour des sections individuelles.';
$txt['permissionname_manage_bans'] = 'Gestion de la liste des bannissements';
$txt['permissionhelp_manage_bans'] = 'Cette permission autorise un utilisateur d\'ajouter ou d\'enlever des utilisateurs, adresses IP, h&ocirc;tes et adresses courriel de la liste des bannissements.  Elle permet aussi de voir et enlever des entr&eacute;es d\'utilisateurs bannis qui tentent de se connecter au forum.';
$txt['permissionname_send_mail'] = 'Envoyer un courriel du forum aux membres';
$txt['permissionhelp_send_mail'] = 'Envoi massif d\'un courriel &agrave; tous les membres du forum ou juste quelques groupes de membres par courriel ou message personnel (ce dernier n&eacute;cessite la permission \'Envoyer un message personnel\'.)';

$txt['permissiongroup_profile'] = 'Profils des membres';
$txt['permissionname_profile_view'] = 'Voir le sommaire du profil et les stats';
$txt['permissionhelp_profile_view'] = 'Cette permission autorise les utilisateurs cliquant sur un pseudonyme &agrave; voir le sommaire des param&egrave;tres de profil, quelques statistiques et tous les messages de ce membre.';
$txt['permissionname_profile_view_own'] = 'Profil personnel';
$txt['permissionname_profile_view_any'] = 'Tous les profils';
$txt['permissionname_profile_identity'] = 'Modifier les param&egrave;tres du compte';
$txt['permissionhelp_profile_identity'] = 'Les param&egrave;tres de compte sont les param&egrave;tres de base du profil, comme mot de passe, adresse courriel, groupe de membres et langue pr&eacute;f&eacute;r&eacute;e.';
$txt['permissionname_profile_identity_own'] = 'Profil personnel';
$txt['permissionname_profile_identity_any'] = 'tous les profils';
$txt['permissionname_profile_extra'] = 'Modifier les param&egrave;tres additionnels du profil';
$txt['permissionhelp_profile_extra'] = 'Les param&egrave;tre additionnels incluent les avatars, th&egrave;me pr&eacute;f&eacute;r&eacute;, notifications et messages personnels.';
$txt['permissionname_profile_extra_own'] = 'Profil personnel';
$txt['permissionname_profile_extra_any'] = 'Tous les profils';
$txt['permissionname_profile_title'] = 'Modifier le texte personnel';
$txt['permissionhelp_profile_title'] = 'Le texte personnel; est affich&eacute; sur la page du fil de discussion, sous le profil de chaque membre qui a un titre personnel.';
$txt['permissionname_profile_title_own'] = 'Profil personnalis&eacute;';
$txt['permissionname_profile_title_any'] = 'Tous les profils';
$txt['permissionname_profile_remove'] = 'Effacer le compte';
$txt['permissionhelp_profile_remove'] = 'Cette permission autorise un membre &agrave; effacer son compte, quand elle est r&eacute;gl&eacute;e sur \'Compte personnel\'.';
$txt['permissionname_profile_remove_own'] = 'Compte personnel';
$txt['permissionname_profile_remove_any'] = 'Tous les comptes';

$txt['permissionname_profile_server_avatar'] = 'S&eacute;lectionner un avatar &agrave; partir du serveur';
$txt['permissionhelp_profile_server_avatar'] = 'Si vous l\'activez, ceci permettra &agrave; un utilisateur de S&eacute;lectionner un avatar &agrave; partir des collections install&eacute;es sur le serveur.';
$txt['permissionname_profile_upload_avatar'] = 'T&eacute;l&eacute;chargez un avatar sur le serveur';
$txt['permissionhelp_profile_upload_avatar'] = 'Cette permission permettra auc utilisateurs de t&eacute;l&eacute;charger leurs avatars personnels sur le serveur.';

$txt['permissionname_profile_remote_avatar'] = 'Choisir un avatar externe';
$txt['permissionhelp_profile_remote_avatar'] = 'Comme les avatars peuvent influencer n&eacute;gativement le temps de cr&eacute;ation de page, il est possible d\'interdire &agrave; certains groupes de membres &agrave; l\'utilisation d\'avatars de serveurs externes. ';

$txt['permissiongroup_general_board'] = 'G&eacute;n&eacute;ral';
$txt['permissionname_moderate_board'] = 'Mod&eacute;rer une section';
$txt['permissionhelp_moderate_board'] = "La permission de mod&eacute;rer une section ajoute quelques petites permissions qui font du mod&eacute;rateur un r&eacute;el mod&eacute;rateur. Inclut r&eacute;ponse aux fils de discussion bloqu&eacute;s, changer la date d'expiration d'un sondage et voir les r&eacute;sultats d'un sondage.";

$txt['permissiongroup_topic'] = 'fils de discussion';
$txt['permissionname_post_new'] = 'Poster des nouveaux fils de discussion';
$txt['permissionhelp_post_new'] = "Cette permission autorise les utilisateurs &agrave; poster de nouveaux fils de discussion.  Elle n'autorise pas &agrave; poster des r&eacute;ponses aux fils de discussion.";
$txt['permissionname_merge_any'] = 'Fusionner un fil de discussion';
$txt['permissionhelp_merge_any'] = 'Fusionner deux fils de discussion ou plus en un seul. L\'ordre des messages dans le fil de discussion final sera bas&eacute; sur la date de cr&eacute;ation des messages. Un utilisateur ne peut fusionner les fils de discussion que sur un forum o&ugrave; il est autoris&eacute; &agrave; fusionner. Pour fusionner plusieurs fils de discussion &agrave; la fois, cet utilisateur doit activer les options de mod&eacute;ration rapide dans son profil.';
$txt['permissionname_split_any'] = 'S&eacute;parer un fil de discussion';
$txt['permissionhelp_split_any'] = 'S&eacute;parer un fil de discussion en deux fils de discussion distincts.';
$txt['permissionname_send_topic'] = 'Envoyer les fils de discussion &agrave; un ami';
$txt['permissionhelp_send_topic'] = 'Cette permission autorise un utilisateur &agrave; envoyer par courriel un fil de discussion &agrave; des amis, en entrant leur adresse courriel et en autorisant l\'ajout d\'un message.';
$txt['permissionname_make_sticky'] = '&Eacute;pingler des fils de discussion';
$txt['permissionhelp_make_sticky'] = 'Les fils de discussion &eacute;pingl&eacute;s sont affich&eacute;s en haut des sections. Ils sont utiles pour fournir des informations ou autres messages importants.';
$txt['permissionname_move'] = 'D&eacute;placer un fil de discussion';
$txt['permissionhelp_move'] = 'D&eacute;placer un fil de discussion depuis une section vers une autre. Les utilisateurs ne peuvent choisir comme destination que les sections o&ugrave; ils ont acc&egrave;s.';
$txt['permissionname_move_own'] = 'Fil de discussion personnel';
$txt['permissionname_move_any'] = 'Tous les fils de discussion';
$txt['permissionname_lock'] = 'fils de discussion bloqu&eacute;s';
$txt['permissionhelp_lock'] = 'Cette permission autorise un utilisateur &agrave; bloquer un fil de discussion. Cela emp&egrave;che quiconque de r&eacute;pondre &agrave; ce fil de discussion. Seuls les membres ayant la permission \'Mod&eacute;rer un Forum\' peuvent encore poster dans un fil de discussion bloqu&eacute;.';
$txt['permissionname_lock_own'] = 'Fil de discussion personnel';
$txt['permissionname_lock_any'] = 'Tous les fils de discussion';
$txt['permissionname_remove'] = 'Effacer des fils de discussion';
$txt['permissionhelp_remove'] = "Efface les fils de discussion. Notez que cette permission ne permet pas d'effacer des messages sp&eacute;cifiques dans le fil de discussion&nbsp;!";
$txt['permissionname_remove_own'] = 'Fil de discussion personnel';
$txt['permissionname_remove_any'] = 'Tous les fils de discussion';
$txt['permissionname_post_reply'] = 'R&eacute;pondre aux fils de discussion';
$txt['permissionhelp_post_reply'] = 'Cette permission autorise &agrave; r&eacute;pondre aux fils de discussion.';
$txt['permissionname_post_reply_own'] = 'Fil de discussion personnel';
$txt['permissionname_post_reply_any'] = 'Tous les fils de discussion';
$txt['permissionname_modify_replies'] = 'Modifier les r&eacute;ponses aux fils de discussion personnels';
$txt['permissionhelp_modify_replies'] = 'Cette permission autorise le membre ayant d&eacute;marr&eacute; un fil de discussion &agrave; modifier toutes les r&eacute;ponses &agrave; ce fil de discussion.';
$txt['permissionname_delete_replies'] = 'Effacer les r&eacute;ponses aux sujets personnels';
$txt['permissionhelp_delete_replies'] = 'Cette permission autorise le membre ayant d&eacute;marr&eacute; un sujet &agrave; effacer toutes les r&eacute;ponses &agrave; ce fil de discussion.';
$txt['permissionname_announce_topic'] = 'Annoncer un fil de discussion';
$txt['permissionhelp_announce_topic'] = 'Ceci permet d\'envoyer un courriel d\'annonce &agrave; propos d\'un fil de discussion &agrave; tous les membres ou &agrave; quelques groupes de membres seulement.';

$txt['permissiongroup_post'] = 'Messages';
$txt['permissionname_delete'] = 'Effacer les messages';
$txt['permissionhelp_delete'] = 'Retire les messages. Cela ne permet pas au membre d\'effacer le premier message d\'un fil de discussion.';
$txt['permissionname_delete_own'] = 'Messages personnels';
$txt['permissionname_delete_any'] = 'Tous les messages';
$txt['permissionname_modify'] = 'Modifier les messages';
$txt['permissionhelp_modify'] = 'Permet de modifier le contenu des messages.';
$txt['permissionname_modify_own'] = 'Messages personnels';
$txt['permissionname_modify_any'] = 'Tous les messages';
$txt['permissionname_report_any'] = 'Signaler les messages aux mod&eacute;rateurs';
$txt['permissionhelp_report_any'] = 'Cette permission ajoute un lien &agrave; chaque message, autorisant &agrave; rapporter un message suspect &agrave; un mod&eacute;rateur. Tous les mod&eacute;rateurs de cette recevront un courriel avec un lien vers le message rapport&eacute; et une description du probl&egrave;me (comme indiqu&eacute; par l\'utilisateur rapportant).';

$txt['permissiongroup_poll'] = 'Sondages';
$txt['permissionname_poll_view'] = 'Voir les sondages';
$txt['permissionhelp_poll_view'] = 'Cette permission autorise un utilisateur &agrave; voir un sondage. Sans elle, il ne verra que le fil de discussion.';
$txt['permissionname_poll_vote'] = 'Voter dans les sondages';
$txt['permissionhelp_poll_vote'] = 'Cette permission autorise un membre (inscrit) &agrave; voter. Invit&eacute;s exclus.';
$txt['permissionname_poll_post'] = 'Poster des sondages';
$txt['permissionhelp_poll_post'] = 'Cette permission autorise un membre &agrave; poster un nouveau sondage.';
$txt['permissionname_poll_add'] = 'Ajouter un sondage au fil de discussion';
$txt['permissionhelp_poll_add'] = 'Autorise un utilisateur &agrave; ajouter un sondage apr&egrave;s la cr&eacute;ation du fil de discussion. Cette permission n&eacute;cessite les droits suffisants pour modifier le premier message du fil de discussion.';
$txt['permissionname_poll_add_own'] = 'Fils de discussion personnels';
$txt['permissionname_poll_add_any'] = 'Tous les fils de discussion';
$txt['permissionname_poll_edit'] = 'Modifier les sondages';
$txt['permissionhelp_poll_edit'] = 'Cette permission autorise l\'utilisateur &agrave; modifier les options du sondage et la remise &agrave; z&eacute;ro. Pour modifier le nombre de votes maximum et la date de fin, la permission de \'Mod&eacute;rer une section\' est requise.';
$txt['permissionname_poll_edit_own'] = 'Sondage personnel';
$txt['permissionname_poll_edit_any'] = 'Tous les sondages';
$txt['permissionname_poll_lock'] = 'Verrouiller les sondages';
$txt['permissionhelp_poll_lock'] = 'Le bloquage des sondages bloque l\'arriv&eacute;e de nouveaux votes.';
$txt['permissionname_poll_lock_own'] = 'Sondage personnel';
$txt['permissionname_poll_lock_any'] = 'Tous les sondages';
$txt['permissionname_poll_remove'] = 'Effacer les sondages';
$txt['permissionhelp_poll_remove'] = 'Cette permission autorise le retrait des sondages.';
$txt['permissionname_poll_remove_own'] = 'Sondage personnel';
$txt['permissionname_poll_remove_any'] = 'Tous les sondages';

$txt['permissiongroup_notification'] = 'Notifications';
$txt['permissionname_mark_any_notify'] = 'Notification des r&eacute;ponses';
$txt['permissionhelp_mark_any_notify'] = 'Cette fonction autorise les utilisateurs &agrave; recevoir une notification lorsque quand une r&eacute;ponse est post&eacute;e dans un fil de discussion auxquelles ils ont souscrit.';
$txt['permissionname_mark_notify'] = 'Notification des nouveaux fils de discussion';
$txt['permissionhelp_mark_notify'] = 'Cette fonction autorise un utilisateur &agrave; recevoir un courriel &agrave; chaque cr&eacute;ation de nouveau fil de discussion dans les sections auxquelles il a souscrit.';

$txt['permissiongroup_attachment'] = 'Fichiers joints';
$txt['permissionname_view_attachments'] = 'Voir les fichiers joints';
$txt['permissionhelp_view_attachments'] = 'Les fichiers joints sont des pi&egrave;ces attach&eacute;es aux messages post&eacute;s. Cette fonction peut &ecirc;tre activ&eacute;e et configur&eacute;e dans \'Configuration des caract&eacute;ristiques et options\'. Comme les fichiers joints ne sont pas directement accessibles, vous pouvez &eacute;viter aux membres non autoris&eacute;s de les t&eacute;l&eacute;charger.';
$txt['permissionname_post_attachment'] = 'Poster des fichiers joints';
$txt['permissionhelp_post_attachment'] = 'Les fichiers joints sont des pi&egrave;ces attach&eacute;es aux messages post&eacute;s. Un message peut en contenir plusieurs.';

$txt['permissionicon'] = '';

$txt['permission_settings_title'] = 'Param&egrave;tres des permissions';
$txt['groups_manage_permissions'] = 'Groupes de membres autoris&eacute;s &agrave; g&eacute;rer les permissions';
$txt['permission_settings_submit'] = 'Enregistrer';
$txt['permission_settings_enable_deny'] = 'Activer l\'option pour interdire des permissions';
// Escape any single quotes in here twice.. 'it\'s' -> 'it\\\'s'.
$txt['permission_disable_deny_warning'] = 'D&eacute;sactiver cette option va mettre &agrave; jour tous les permissions interdites \\\'Interdite\\\' vers le statut \\\'Refus&eacute;e\\\'.';
$txt['permission_by_membergroup_desc'] = 'Ici vous pouvez r&eacute;gler toutes les permissions globales pour chaque groupe de membres. Ces permissions sont valides dans toutes les sections qui ne fonctionnent pas avec des permissions locales param&eacute;tr&eacute;es dans l\'interface \'Permissions par section\'.';
$txt['permission_by_board_desc'] = 'Ici pous pouvez choisir si une section utilisera les permissions globales ou un r&eacute;gime de permissions locales, sp&eacute;cifiques. Utiliser des permissions locales signifie que pour cette section, vous pouvez surpasser toutes les permissions pour tous les groupes de membres par celles de votre choix.';
$txt['permission_settings_desc'] = 'Ici vous pouvez r&eacute;gler qui a la permission de changer les permissions, de m&ecirc;me que la complexit&eacute; que devrait avoir le syst&egrave;me de permissions.';
$txt['permission_settings_enable_postgroups'] = 'Activer les permissions pour les groupes posteurs';
// Escape any single quotes in here twice.. 'it\'s' -> 'it\\\'s'.
$txt['permission_disable_postgroups_warning'] = 'D&eacute;sactiver ce param&egrave;tre va enlever les permissions pr&eacute;sentement attribu&eacute;es aux groupes posteurs.';
$txt['permission_settings_enable_by_board'] = 'Activer les permissions avanc&eacute;es par section';
// Escape any single quotes in here twice.. 'it\'s' -> 'it\\\'s'.
$txt['permission_disable_by_board_warning'] = 'D&eacute;sactiver ce param&egrave;tre supprimera toutes les permissions choisies au niveau de la section.';

?>