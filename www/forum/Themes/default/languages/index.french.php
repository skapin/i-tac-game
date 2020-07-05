<?php
// Version: 1.1.2; index

global $forum_copyright, $forum_version, $webmaster_email;

// Locale (strftime, pspell_new) and spelling. (pspell_new, can be left as '' normally.)
// For more information see:
//   - http://www.php.net/function.pspell-new
//   - http://www.php.net/function.setlocale
// Again, SPELLING SHOULD BE '' 99% OF THE TIME!!  Please read this!
$txt['lang_locale'] = 'fr_FR';
$txt['lang_dictionary'] = 'fr';
$txt['lang_spelling'] = 'french';

// Character set and right to left?
$txt['lang_character_set'] = 'ISO-8859-1';
$txt['lang_rtl'] = false;

$txt['days'] = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
$txt['days_short'] = array('Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam');
// Months must start with 1 => 'January'. (or translated, of course.)
$txt['months'] = array(1 => 'Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
$txt['months_titles'] = array(1 => 'Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
$txt['months_short'] = array(1 => 'Jan', 'F&eacute;v', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Ao&ucirc;t', 'Sep', 'Oct', 'Nov', 'D&eacute;c');

$txt['newmessages0'] = 'est nouveau';
$txt['newmessages1'] = 'sont nouveaux';
$txt['newmessages3'] = 'Nouveau';
$txt['newmessages4'] = ',';

$txt[2] = 'Admin';

$txt[10] = 'Sauvegarder';

$txt[17] = 'Modifier';
$txt[18] = $context['forum_name'] . ' - Accueil';
$txt[19] = 'Membres';
$txt[20] = 'Nom de la section';
$txt[21] = 'Messages';
$txt[22] = 'Dernier message';

$txt[24] = '(Pas de titre)';
$txt[26] = 'Messages';
$txt[27] = 'Voir le profil';
$txt[28] = 'Invit&eacute;';
$txt[29] = 'Auteur';
$txt[30] = 'le';
$txt[31] = 'Enlever';
$txt[33] = 'Nouveau fil de discussion';

$txt[34] = 'Identifiez-vous';
// Use numeric entities in the below string.
$txt[35] = 'Identifiant';
$txt[36] = 'Mot de passe';

$txt[40] = 'Cet identifiant n\'existe pas.';

$txt[62] = 'Mod&eacute;rateur';
$txt[63] = 'Effacer le fil';
$txt[64] = 'Fils de discussion';
$txt[66] = 'Modifier le message';
$txt[68] = 'Pseudonyme';
$txt[69] = 'Courriel';
$txt[70] = 'Titre';
$txt[72] = 'Message';

$txt[79] = 'Profil';

$txt[81] = 'Choisir un mot de passe';
$txt[82] = 'V&eacute;rifier le mot de passe';
$txt[87] = 'Rang';

$txt[92] = 'Voir le profil de';
$txt[94] = 'Total';
$txt[95] = 'Messages';
$txt[96] = 'Site Web';
$txt[97] = 'Inscrivez-vous';

$txt[101] = 'Index des messages';
$txt[102] = 'Nouvelles';
$txt[103] = 'Accueil';

$txt[104] = 'Bloquer/d&eacute;bloquer le fil de discussion';
$txt[105] = 'Soumettre';
$txt[106] = 'Une erreur s\'est produite&nbsp;!';
$txt[107] = '&agrave;';
$txt[108] = 'D&eacute;connexion';
$txt[109] = 'D&eacute;marr&eacute; par';
$txt[110] = 'R&eacute;ponses';
$txt[111] = 'Dernier message';
$txt[114] = 'Connexion Admin';
// Use numeric entities in the below string.
$txt[118] = 'Fil de discussion';
$txt[119] = 'Aide';
$txt[121] = 'Effacer le message';
$txt[125] = 'Notifier';
$txt[126] = 'Voulez-vous recevoir un courriel de notification si quelqu\'un r&eacute;pond &agrave; ce fil de discussion&nbsp;?';
// Use numeric entities in the below string.
$txt[130] = "Cordialement,\nL'&#233;quipe ". $context['forum_name'];
$txt[131] = 'Notification de r&eacute;ponse';
$txt[132] = 'D&eacute;placer le fil de discussion';
$txt[133] = 'D&eacute;placer vers';
$txt[139] = 'Pages';
$txt[140] = 'Membres actifs dans les ' . $modSettings['lastActive'] . ' derni&egrave;res minutes';
$txt[144] = 'Messages personnels';
$txt[145] = 'Citer en r&eacute;ponse';
$txt[146] = 'R&eacute;pondre';

$txt[151] = 'Pas de messages...';
$txt[152] = 'vous avez';
$txt[153] = 'messages';
$txt[154] = 'Effacer ce message';

$txt[158] = 'Membres en ligne';
$txt[159] = 'Message personnel';
$txt[160] = 'Aller &agrave;';
$txt[161] = 'aller';
$txt[162] = '&ecirc;tes-vous s&ucirc;r de vouloir effacer ce fil de discussion&nbsp;?';
$txt[163] = 'Oui';
$txt[164] = 'Non';

$txt[166] = 'R&eacute;sultats de recherche';
$txt[167] = 'Fin des r&eacute;sultats';
$txt[170] = 'D&eacute;sol&eacute;, aucune correspondance trouv&eacute;e';
$txt[176] = 'le';

$txt[182] = 'Rechercher';
$txt[190] = 'Toutes';

$txt[193] = 'Retour';
$txt[194] = 'Rappel de mot de passe';
$txt[195] = 'Discussion d&eacute;marr&eacute;e par';
$txt[196] = 'Titre';
$txt[197] = 'Post&eacute; par';
$txt[200] = 'Liste de tous les membres inscrits sur ce forum.';
$txt[201] = 'Merci d\'accueillir';
$txt[208] = 'Centre d\'administration';
$txt[211] = 'Derni&egrave;re &eacute;dition';
$txt[212] = 'Voulez-vous d&eacute;sactiver la notification pour ce fil de discussion&nbsp;?';

$txt[214] = 'Messages r&eacute;cents';

$txt[227] = 'Localisation';
$txt[231] = 'Sexe';
$txt[233] = 'Inscrit le';

$txt[234] = 'Voir les plus r&eacute;cents messages du forum.';
$txt[235] = 'est le dernier fil de discussion mis &agrave; jour';

$txt[238] = 'Homme';
$txt[239] = 'Femme';

$txt[240] = 'Caract&egrave;re invalide dans l\'identifiant / pseudonyme.';

$txt['welcome_guest'] = 'Bienvenue, <b>' . $txt[28] . '</b>. Veuillez <a href="' . $scripturl . '?action=login">vous connecter</a> ou <a href="' . $scripturl . '?action=register">vous inscrire</a>.';
$txt['welcome_guest_activate'] = '<br />Avez-vous perdu votre <a href="' . $scripturl . '?action=activate">courriel d\'activation?</a>';
$txt['hello_member'] = 'Salut,';
// Use numeric entities in the below string.
$txt['hello_guest'] = 'Bienvenue,';
$txt[247] = 'Salut,';
$txt[248] = 'Bienvenue,';
$txt[249] = 'S\'il vous pla&icirc;t';
$txt[250] = 'Retour';
$txt[251] = 'Merci de choisir une destination';

// Escape any single quotes in here twice.. 'it\'s' -> 'it\\\'s'.
$txt[279] = "Post&eacute; par";

$txt[287] = 'Sourire';
$txt[288] = 'F&acirc;ch&eacute;';
$txt[289] = 'Souriant';
$txt[290] = 'Rire';
$txt[291] = 'Triste';
$txt[292] = 'Clin d\'oeil';
$txt[293] = 'Grima&ccedil;ant';
$txt[294] = 'Choqu&eacute;';
$txt[295] = 'Cool';
$txt[296] = 'Huh';
$txt[450] = 'Roulement des yeux';
$txt[451] = 'Tire la langue';
$txt[526] = 'Embarrass&eacute;';
$txt[527] = 'L&egrave;vres scell&eacute;es';
$txt[528] = 'Ind&eacute;ci';
$txt[529] = 'Bisou';
$txt[530] = 'Pleurs';

$txt[298] = 'Mod&eacute;rateur';
$txt[299] = 'Mod&eacute;rateurs';

$txt[300] = 'Marquer les fils de discussion comme lus pour cette section';
$txt[301] = 'Vues';
$txt[302] = 'Nouveau';

$txt[303] = 'Voir tous les membres';
$txt[305] = 'Voir';
$txt[307] = 'Courriel';

$txt[308] = 'Affichage des membres';
$txt[309] = 'du';
$txt[310] = 'total des membres';
$txt[311] = '&agrave;';
$txt[315] = 'Mot de passe oubli&eacute;&nbsp;?';

$txt[317] = 'Date';
// Use numeric entities in the below string.
$txt[318] = 'De';
$txt[319] = 'Sujet';
$txt[322] = 'V&eacute;rifier les nouveaux messages';
$txt[324] = '&agrave;';

$txt[330] = 'fils de discussion';
$txt[331] = 'Membres';
$txt[332] = 'Liste des membres';
$txt[333] = 'Nouveaux messages';
$txt[334] = 'Pas de nouveau message';

$txt['sendtopic_send'] = 'Envoyer';

$txt[371] = 'D&eacute;calage horaire';
$txt[377] = 'ou';

$txt[398] = 'D&eacute;sol&eacute;, aucune correspondance trouv&eacute;e';

$txt[418] = 'Notification';

$txt[430] = 'D&eacute;sol&eacute; %s, vous &ecirc;tes banni de ce forum&nbsp;!';

$txt[452] = 'Marquer TOUS les messages comme lus';

$txt[454] = 'Fil populaire (plus de ' . $modSettings['hotTopicPosts'] . ' interventions)';
$txt[455] = 'Fil tr&egrave;s populaire (plus de ' . $modSettings['hotTopicVeryPosts'] . ' interventions)';
$txt[456] = 'Fil bloqu&eacute;';
$txt[457] = 'Fil normal';
$txt['participation_caption'] = 'Fil dans lequel vous &ecirc;tes intervenu';

$txt[462] = 'Aller';

$txt[465] = 'Imprimer';
$txt[467] = 'Profil';
$txt[468] = 'R&eacute;sum&eacute; de la discussion';
$txt[470] = 'N/A';
$txt[471] = 'message';
$txt[473] = 'Ce nom est d&eacute;j&agrave; utilis&eacute; par un autre membre.';

$txt[488] = 'Total des membres';
$txt[489] = 'Total des messages';
$txt[490] = 'Total des fils de discussion';

$txt[497] = 'Dur&eacute;e de connexion (en minutes)&nbsp;';

$txt[507] = 'Pr&eacute;visualiser';
$txt[508] = 'Toujours connect&eacute;';

$txt[511] = 'Journalis&eacute;e';
// Use numeric entities in the below string.
$txt[512] = 'IP';

$txt[513] = 'ICQ';
$txt[515] = 'WWW';

$txt[525] = 'par';

$txt[578] = 'heures';
$txt[579] = 'jours';

$txt[581] = ', notre membre le plus r&eacute;cent.';

$txt[582] = 'Rechercher';

$txt[603] = 'AOL IM';
// In this string, please use +'s for spaces.
$txt['aim_default_message'] = 'Salut.+Es-tu+disponible?';
$txt[604] = 'Yahoo! IM';

$txt[616] = 'Attention, ce forum est en \'Mode Maintenance\'.';

$txt[641] = 'Lu';
$txt[642] = 'fois';

$txt[645] = 'Stats du forum';
$txt[656] = 'Dernier membre';
$txt[658] = 'Total des cat&eacute;gories';
$txt[659] = 'Dernier message';

$txt[660] = 'Vous avez';
$txt[661] = 'Cliquez';
$txt[662] = 'ici';
$txt[663] = 'pour les voir.';

$txt[665] = 'Total des sections';

$txt[668] = 'Imprimer la page';

$txt[679] = 'Cela doit &ecirc;tre une adresse courriel valide.';

$txt[683] = 'Je suis un geek&nbsp;!!';
$txt[685] = $context['forum_name'] . ' - Centre d\'informations';

$txt[707] = 'Envoyer ce fil';

$txt['sendtopic_title'] = 'Envoyer le fil de discussion &quot;%s&quot; &agrave; un ami.';
// Use numeric entities in the below string.
$txt['sendtopic_dear'] = "Cher %s,";
$txt['sendtopic_this_topic'] = "J\'aimerais que tu lises « %s » sur le forum " . $context['forum_name'] . ".  Pour le lire, clique sur ce lien";
$txt['sendtopic_thanks'] = "Merci";
$txt['sendtopic_sender_name'] = 'Votre nom';
$txt['sendtopic_sender_email'] = 'Votre adresse courriel';
$txt['sendtopic_receiver_name'] = 'Nom du destinataire';
$txt['sendtopic_receiver_email'] = 'Adresse courriel du destinataire';
$txt['sendtopic_comment'] = 'Ajouter un commentaire';
// Use numeric entities in the below string.
$txt['sendtopic2'] = "Un commentaire a aussi &#233;t&#233; ajout&#233; 	&#224; propos de cette discussion";

$txt[721] = 'Cacher l\'adresse courriel au public&nbsp;?';

$txt[737] = 'Tout cocher';

// Use numeric entities in the below string.
$txt[1001] = "Erreur de base de donn&#233;es";
$txt[1002] = 'Merci de r&eacute;essayer.  Si l\'erreur se reproduit, merci de signaler cette erreur &agrave; un administrateur.';
$txt[1003] = 'Fichier';
$txt[1004] = 'Ligne';
// Use numeric entities in the below string.
$txt[1005] = "SMF a d&#233;tect&#233; et a automatiquement essay&#233; de r&#233;parer une erreur dans votre base de donn&#233;es.  Si le probl&#232;me persiste ou si vous continuez de recevoir ces courriels, contactez votre h&#233;bergeur.";
$txt['database_error_versions'] = '<b>Note&nbsp;:</b> Il semble que votre base de donn&eacute;es <em>puisse</em> n&eacute;cessiter d\'une mise &agrave; jour.  Actuellement, la version des fichiers du forum est ' . $forum_version . ', tandis que votre base de donn&eacute;es est &agrave; la version SMF ' . $modSettings['smfVersion'] . '. Les erreurs pr&eacute;c&eacute;dentes peuvent peut-&ecirc;tre dispara&icirc;tre si vous ex&eacute;cutez la derni&egrave;re version de upgrade.php.';
$txt['template_parse_error'] = 'Erreur d\aper&ccedil;u du th&egrave;me&nbsp;!';
$txt['template_parse_error_message'] = 'Il semble que le forum rencontre actuellement quelques difficult&eacute;s avec l\'aper&ccedil;u du th&egrave;me. Le probl&egrave;me devrait &ecirc;tre temporaire; revenez plus tard. Si vous continuez &agrave; voir ce message, contactez l\'administrateur.<br /><br />Vous pouvez aussi essayer de <a href="javascript:location.reload();">recharger cette page</a>.';
$txt['template_parse_error_details'] = 'Un probl&egrave;me s\'est produit durant le chargement du th&egrave;me ou du fichier de langue <tt><b>%1$s</b></tt>.  V&eacute;rifiez la syntaxe -- rappelez-vous que les apostrophes (<tt>\'</tt>) ont g&eacute;n&eacute;ralement besoin d\'&ecirc;tre &eacute;chapp&eacute;es &agrave; l\'aide d\'une barre oblique (<tt>\\</tt>) -- et r&eacute;ssayez. Pour conna&icirc;tre plus de d&eacute;tails sur ces erreurs par PHP, essayez d\'<a href="' . $boardurl . '%1$s">acc&eacute;der directement au fichier</a>.<br /><br />Vous voudrez sans doute aussi <a href="javascript:location.reload();">recharger la page</a> ou <a href="' . $scripturl . '?theme=1">utiliser le th&egrave;me par d&eacute;faut</a>.';

$txt['smf10'] = '<strong>Aujourd\'hui</strong> &agrave; ';
$txt['smf10b'] = '<strong>Hier</strong> &agrave; ';
$txt['smf20'] = 'Nouveau sondage';
$txt['smf21'] = 'Question';
$txt['smf23'] = 'Soumettre le vote';
$txt['smf24'] = 'Total des votants';
$txt['smf25'] = 'Raccourcis&nbsp;: tapez [ALT]+[S] pour soumettre/poster ou [ALT]+[P] pour pr&eacute;visualiser';
$txt['smf29'] = 'Voir les r&eacute;sultats';
$txt['smf30'] = 'Bloquer les votes';
$txt['smf30b'] = 'D&eacute;bloquer les votes';
$txt['smf39'] = 'Modifier le sondage';
$txt['smf43'] = 'Sondage';
$txt['smf47'] = '1 jour';
$txt['smf48'] = '1 semaine';
$txt['smf49'] = '1 mois';
$txt['smf50'] = 'Toujours';
$txt['smf52'] = 'Connexion avec identifiant, mot de passe et dur&eacute;e de la session';
$txt['smf53'] = '1 heure';
$txt['smf56'] = 'D&Eacute;PLAC&Eacute;';
$txt['smf57'] = 'Merci de pr&eacute;ciser la raison du d&eacute;placement de ce fil de discussion.';
$txt['smf60'] = 'D&eacute;sol&eacute;, vous n\'avez pas assez post&eacute; pour modifier ce karma - il vous faut au moins ';
$txt['smf62'] = 'D&eacute;sol&eacute;, vous ne pouvez pas r&eacute;p&eacute;ter cette action sur le karma sans attendre';
$txt['smf82'] = 'Section';
$txt['smf88'] = 'dans';
$txt['smf96'] = 'Fil &eacute;pingl&eacute;';

$txt['smf138'] = 'Effacer';

$txt['smf199'] = 'Vos messages personnels';

$txt['smf211'] = 'Ko';

$txt['smf223'] = '[plus de stats]';

// Use numeric entities in the below string.
$txt['smf238'] = 'Code';
$txt['smf239'] = 'Citation de';
$txt['smf240'] = 'Citation';

$txt['smf251'] = 'S&eacute;parer';
$txt['smf252'] = 'Regrouper des fils';
$txt['smf254'] = 'Titre du nouveau fil de discussion';
$txt['smf255'] = 'S&eacute;parer seulement ce message.';
$txt['smf256'] = 'S&eacute;parer le fil apr&egrave;s et en incluant ce message.';
$txt['smf257'] = 'Choisir les messages &agrave; s&eacute;parer.';
$txt['smf258'] = 'Nouveau fil de discussion';
$txt['smf259'] = 'Ce fil de discussion a &eacute;t&eacute; s&eacute;par&eacute; en deux avec succ&egrave;s.';
$txt['smf260'] = 'Fil d\'origine';
$txt['smf261'] = 'Merci de choisir quels messages vous voulez s&eacute;parer.';
$txt['smf264'] = 'Fils r&eacute;unis avec succ&egrave;s.';
$txt['smf265'] = 'Nouveau fil de discussion fusionn&eacute;';
$txt['smf266'] = 'Fils de discussion &agrave; fusionner';
$txt['smf267'] = 'Section de destination';
$txt['smf269'] = 'Fil de discussion de destination';
$txt['smf274'] = '&ecirc;tes-vous s&ucirc;r de vouloir fusionner';
$txt['smf275'] = 'avec';
$txt['smf276'] = 'Cette fonction r&eacute;unira les messages de deux fils de discussion en un seul fil. Les messages seront class&eacute;s par date de publication. Le plus ancien message deviendra le premier du nouveau fil fusionn&eacute;.';

$txt['smf277'] = '&eacute;pingler le fil';
$txt['smf278'] = 'D&eacute;pingler le fil';
$txt['smf279'] = 'Bloquer le fil';
$txt['smf280'] = 'D&eacute;bloquer le fil';

$txt['smf298'] = 'avanc&eacute;e';

$txt['smf299'] = 'RISQUE DE SECURITE MAJEUR';
$txt['smf300'] = 'Vous n\'avez pas enlev&eacute; ';

$txt['smf301'] = 'Page g&eacute;n&eacute;r&eacute;e en ';
$txt['smf302'] = ' secondes avec ';
$txt['smf302b'] = ' requ&ecirc;tes.';

$txt['smf315'] = 'Utilisez cette fonction pour informer les mod&eacute;rateurs et administrateurs d\'un message abusif ou erron&eacute;.<br /><i>Veuillez noter que votre adresse courriel sera r&eacute;v&eacute;l&eacute;e aux mod&eacute;rateurs si vous utilisez cette fonction.</i>';

$txt['online2'] = 'En ligne';
$txt['online3'] = 'Hors ligne';
$txt['online4'] = 'Message personnel (En ligne)';
$txt['online5'] = 'Message personnel (Hors ligne)';
$txt['online8'] = '&eacute;tat';

$txt['topbottom4'] = 'Haut de page';
$txt['topbottom5'] = 'Bas de page';

$forum_copyright = '<a href="http://www.simplemachines.org/" title="Simple Machines Forum" target="_blank">Powered by ' . $forum_version . '</a> | 
<a href="http://www.simplemachines.org/about/copyright.php" title="Free Forum Software" target="_blank">SMF &copy; 2006, Simple Machines LLC</a>';

$txt['calendar3'] = 'Anniversaires&nbsp;:';
$txt['calendar4'] = '&eacute;v&egrave;nement&nbsp;:';
$txt['calendar3b'] = 'Prochains anniversaires&nbsp;:';
$txt['calendar4b'] = 'Prochains &eacute;v&egrave;nement&nbsp;:';
// Prompt for holidays in the calendar, leave blank to just display the holiday's name.
$txt['calendar5'] = '';
$txt['calendar9'] = 'Mois&nbsp;:';
$txt['calendar10'] = 'Ann&eacute;e&nbsp;:';
$txt['calendar11'] = 'Jour&nbsp;:';
$txt['calendar12'] = 'Titre de l\'&eacute;v&egrave;nement&nbsp;:';
$txt['calendar13'] = 'Post&eacute; dans&nbsp;:';
$txt['calendar20'] = 'Modifier l\'&eacute;v&egrave;nement';
$txt['calendar21'] = 'Effacer cet &eacute;v&egrave;nement&nbsp;?';
$txt['calendar22'] = 'Effacer l\'&eacute;v&egrave;nement';
$txt['calendar23'] = 'Poste l\'&eacute;v&egrave;nement';
$txt['calendar24'] = 'Calendrier';
$txt['calendar37'] = 'Lien vers le calendrier';
$txt['calendar43'] = 'Lier l\'&eacute;v&egrave;nement';
$txt['calendar47'] = 'Prochains &eacute;v&egrave;nements';
$txt['calendar47b'] = 'Calendrier d\'aujourd\'hui';
$txt['calendar51'] = 'Semaine';
$txt['calendar54'] = 'Nombre de jours&nbsp;:';
$txt['calendar_how_edit'] = 'comment modifiez-vous ces &eacute;v&eacute;nements&nbsp;?';
$txt['calendar_link_event'] = 'Lier l\'&eacute;v&eacute;nement &agrave; un sujet&nbsp;:';
$txt['calendar_confirm_delete'] = 'Voulez-vous vraiment supprimer cet &eacute;v&eacute;nement&nbsp;?';
$txt['calendar_linked_events'] = '&Eacute;v&eacute;nements li&eacute;s';

$txt['moveTopic1'] = 'Poster un message de redirection';
$txt['moveTopic2'] = 'Changer le titre du fil de discussion';
$txt['moveTopic3'] = 'Nouveau titre';
$txt['moveTopic4'] = 'Changer le titre de tous les messages';

$txt['theme_template_error'] = 'Impossible de charger le mod&egrave;le \'%s\'.';
$txt['theme_language_error'] = 'Impossible de charger le fichier de langues \'%s\'.';

$txt['parent_boards'] = 'Sous-section';

$txt['smtp_no_connect'] = '&Eacute;chec de connexion au serveur SMTP';
$txt['smtp_port_ssl'] = 'Le port SMTP est incorrect; Il doit &ecirc;tre mis &agrave; 465 pour un serveur SSL.';

$txt['smtp_bad_response'] = 'Erreur en r&eacute;ception des codes de r&eacute;ponses du serveur mail';
$txt['smtp_error'] = 'Incident survenu lors d\'envoi de courriel. Erreur&nbsp;: ';
$txt['mail_send_unable'] = 'Impossible d\'envoyer un courriel &agrave; l\'adresse \'%s\'.';

$txt['mlist_search'] = 'Rechercher des membres';
$txt['mlist_search2'] = 'Chercher &agrave; nouveau';
$txt['mlist_search_email'] = 'Recherche par adresse courriel';
$txt['mlist_search_messenger'] = 'Recherche par identifiant MSN Messenger';
$txt['mlist_search_group'] = 'Recherche par rang';
$txt['mlist_search_name'] = 'Recherche par pseudonyme';
$txt['mlist_search_website'] = 'Recherche par site web';
$txt['mlist_search_results'] = 'R&eacute;sultats de la recherche pour';

$txt['attach_downloaded'] = 'T&eacute;l&eacute;charg&eacute;';
$txt['attach_viewed'] = 'vu';
$txt['attach_times'] = 'fois';

$txt['MSN'] = 'MSN IM';

$txt['settings'] = 'Param&egrave;tres';
$txt['never'] = 'Jamais';
$txt['more'] = 'plus';

$txt['hostname'] = 'H&ocirc;te';
$txt['you_are_post_banned'] = 'Desol&eacute; %s, vous n\'avez plus le droit d\'envoyer ou poster de message personnel sur ce forum.';
$txt['ban_reason'] = 'Raison';

$txt['tables_optimized'] = 'Tables de donn&eacute;es optimis&eacute;es';

$txt['add_poll'] = 'Ajouter un sondage';
$txt['poll_options6'] = 'Vous ne pouvez choisir que %s options maximum.';
$txt['poll_remove'] = 'Retirer le sondage';
$txt['poll_remove_warn'] = '&Ecirc;tes-vous s&ucirc;r de vouloir retirer ce sondage de ce fil de discussion&nbsp;?';
$txt['poll_results_expire'] = 'Les r&eacute;sultats seront affich&eacute;s &agrave; la cl&ocirc;ture des votes';
$txt['poll_expires_on'] = 'Cl&ocirc;ture des votes';
$txt['poll_expired_on'] = 'Votes cl&ocirc;tur&eacute;s';
$txt['poll_change_vote'] = 'Enlever son vote';
$txt['poll_return_vote'] = 'Options de vote';

$txt['quick_mod_remove'] = 'Supprimer la s&eacute;lection';
$txt['quick_mod_lock'] = 'Bloquer la s&eacute;lection';
$txt['quick_mod_sticky'] = '&Eacute;pingler la s&eacute;lection';
$txt['quick_mod_move'] = 'D&eacute;placer la s&eacute;lection vers';
$txt['quick_mod_merge'] = 'Fusionner la s&eacute;lection';
$txt['quick_mod_markread'] = 'Marquer la s&eacute;lection comme lue';
$txt['quick_mod_go'] = 'Aller&nbsp;!';
$txt['quickmod_confirm'] = '&Ecirc;tes-vous s&ucirc;r de vouloir faire cela &nbsp;?';

$txt['spell_check'] = 'V&eacute;rification orthographique';

$txt['quick_reply_1'] = 'R&eacute;ponse Rapide';
$txt['quick_reply_2'] = 'Dans la <em>R&eacute;ponse Rapide</em> vous pouvez utiliser du BBCode et des &eacute;motic&ocirc;nes comme sur un message normal, mais &agrave; partir d\'une interface beaucoup plus simple et d&eacute;pouill&eacute;e.';
$txt['quick_reply_warning'] = 'Attention&nbsp;: ce fil de discussion est actuellement bloqu&eacute;!<br />Seuls les administrateurs et les mod&eacute;rateurs peuvent y r&eacute;pondre.';

$txt['notification_enable_board'] = '&Ecirc;tes-vous s&ucirc;r de vouloir activer la notification des nouveaux fils de discussion pour cette section&nbsp;?';
$txt['notification_disable_board'] = '&Ecirc;tes-vous s&ucirc;r de vouloir d&eacute;sactiver la notification des nouveaux fils de discussion pour cette section&nbsp;?';
$txt['notification_enable_topic'] = '&Ecirc;tes-vous s&ucirc;r de vouloir activer la notification des nouvelles r&eacute;ponses pour ce fil de discussion&nbsp;?';
$txt['notification_disable_topic'] = '&Ecirc;tes-vous s&ucirc;r de vouloir d&eacute;sactiver la notification des nouvelles r&eacute;ponses pour ce fil de discussion&nbsp;?';

$txt['rtm1'] = 'Signaler au mod&eacute;rateur';

$txt['unread_topics_visit'] = 'Fils de discussion r&eacute;cents non lus';
$txt['unread_topics_visit_none'] = 'Aucun fil de discussion non lu trouv&eacute; depuis votre derni&egrave;re visite.  <a href="' . $scripturl . '?action=unread;all">Cliquez ici pour lire tous les fils de discussion non lus</a>.';
$txt['unread_topics_all'] = 'Tous les fils non lus';
$txt['unread_replies'] = 'Fils mis &agrave; jour';

$txt['who_title'] = 'Qui est en ligne';
$txt['who_and'] = ' et ';
$txt['who_viewing_topic'] = ' sur ce fil de discussion.';
$txt['who_viewing_board'] = ' dans cette section.';
$txt['who_member'] = 'Membre';

$txt['powered_by_php'] = 'Propuls&eacute; par PHP';
$txt['powered_by_mysql'] = 'Propuls&eacute; par MySQL';
$txt['valid_html'] = 'HTML 4.01 valide&nbsp;!';
$txt['valid_xhtml'] = 'XHTML 1.0 Transitionnel valide&nbsp;!';
$txt['valid_css'] = 'CSS valide&nbsp;!';

$txt['guest'] = 'Invit&eacute;';
$txt['guests'] = 'Invit&eacute;s';
$txt['user'] = 'Membre';
$txt['users'] = 'Membres';
$txt['hidden'] = 'Cach&eacute;';
$txt['buddy'] = 'Ami';
$txt['buddies'] = 'Amis';
$txt['most_online_ever'] = 'Record de connexion total';
$txt['most_online_today'] = 'Record de connexion aujourd\'hui';

$txt['merge_select_target_board'] = 'Choisir la section de destination pour le fil fusionn&eacute;';
$txt['merge_select_poll'] = 'Choisir quel sondage le fil fusionn&eacute; poss&eacute;dera';
$txt['merge_topic_list'] = 'Choisir les fils de discussion &agrave; fusionner';
$txt['merge_select_subject'] = 'Choisir le titre du fil fusionn&eacute;';
$txt['merge_custom_subject'] = 'Titre personnel';
$txt['merge_enforce_subject'] = 'Changer le titre de tous les messages';
$txt['merge_include_notifications'] = 'Inclure les notifications&nbsp;?';
$txt['merge_check'] = 'Fusionner&nbsp;?';
$txt['merge_no_poll'] = 'Pas de sondage';

$txt['response_prefix'] = 'Re&nbsp;: ';
$txt['current_icon'] = 'Ic&ocirc;ne actuelle';

$txt['smileys_current'] = 'Jeu d\'&eacute;motic&ocirc;nes actuel';
$txt['smileys_none'] = 'Pas d\'&eacute;motic&ocirc;nes';
$txt['smileys_forum_board_default'] = 'D&eacute;faut du forum / de la section';

$txt['search_results'] = 'R&eacute;sultats de recherche';
$txt['search_no_results'] = 'Aucun r&eacute;sultat';

$txt['totalTimeLogged1'] = 'Temps de connexion total&nbsp;: ';
$txt['totalTimeLogged2'] = ' jours, ';
$txt['totalTimeLogged3'] = ' heures et ';
$txt['totalTimeLogged4'] = ' minutes.';
$txt['totalTimeLogged5'] = 'j ';
$txt['totalTimeLogged6'] = 'h ';
$txt['totalTimeLogged7'] = 'm';

$txt['approve_thereis'] = 'Il y a';
$txt['approve_thereare'] = 'Il y a';
$txt['approve_member'] = 'un membre';
$txt['approve_members'] = 'membres';
$txt['approve_members_waiting'] = 'en attente d\'approbation.';

$txt['notifyboard_turnon'] = 'Voulez-vous recevoir un courriel de notification quand quelqu\'un poste un nouveau fil de discussion dans cette section&nbsp;?';
$txt['notifyboard_turnoff'] = '&Ecirc;tes-vous s&ucirc;r de ne pas vouloir recevoir de notifications pour des nouveaux fils de discussion dans cette section&nbsp;?';

$txt['activate_code'] = 'Votre code d\'activation est';

$txt['find_members'] = 'Trouver des membres';
$txt['find_username'] = 'Identifiant, pseudo, ou adresse courriel';
$txt['find_buddies'] = 'Montrer les amis seulements';
$txt['find_wildcards'] = 'Jokers autoris&eacute;s&nbsp;: *, ?';
$txt['find_no_results'] = 'Pas de r&eacute;sultat';
$txt['find_results'] = 'R&eacute;sultats';
$txt['find_close'] = 'Fermer';

$txt['unread_since_visit'] = 'Messages non lus depuis votre derni&egrave;re visite.';
$txt['show_unread_replies'] = 'R&eacute;ponses &agrave; vos messages.';

$txt['change_color'] = 'Changer de couleur';

$txt['quickmod_delete_selected'] = 'Effacer la s&eacute;lection';

// In this string, don't use entities. (&amp;, etc.)
$txt['show_personal_messages'] = 'Vous avez reçu un ou plusieurs nouveau(x) message(s) personnel(s).\\nLes voir maintenant (dans une nouvelle fenêtre)?';

$txt['previous_next_back'] = '&laquo; sujet pr&eacute;c&eacute;dent |';
$txt['previous_next_forward'] = '| sujet suivant &raquo;';

$txt['movetopic_auto_board'] = '[SECTION]';
$txt['movetopic_auto_topic'] = '[LIEN DU FIL DE DISCUSSION]';
$txt['movetopic_default'] = 'Ce fil de discussion a &eacute;t&eacute; d&eacute;plac&eacute; vers ' . $txt['movetopic_auto_board'] . ".\n\n" . $txt['movetopic_auto_topic'];

$txt['upshrink_description'] = 'Cacher ou afficher l\'ent&ecirc;te.';

$txt['mark_unread'] = 'Marquer non lu';

$txt['ssi_not_direct'] = 'Veuillez ne pas acc&eacute;der directement &agrave; SSI.php par l\'URL; vous devrez utiliser le chemin (%s) ou ajouter ?ssi_function=quelquechose.';
$txt['ssi_session_broken'] = 'SSI.php a &eacute;t&eacute; incapable de charger une session&nbsp;!  Ceci peut causer des probl&egrave; pour la d&eacute;connexion et d\'autres fonctions - veuillez vous assurer que SSI.php est inclus avant *tout* le reste de votre code dans vos scripts&nbsp;!';

// Escape any single quotes in here twice.. 'it\'s' -> 'it\\\'s'.
$txt['preview_title'] = 'Pr&eacute;visualiser le message';
$txt['preview_fetch'] = 'Charge la pr&eacute;visualisation&hellip;';
$txt['preview_new'] = 'Nouveau message';
$txt['error_while_submitting'] = 'L\\\'erreur ou les erreurs suivantes sont apparues durant la soumission de ce message&nbsp;:';

$txt['split_selected_posts'] = 'Messages s&eacute;lectionn&eacute;s';
$txt['split_selected_posts_desc'] = 'Les messages suivants formeront un nouveau fil de discussion apr&egrave;s la scission.';
$txt['split_reset_selection'] = 'recommencer la s&eacute;lection';

$txt['modify_cancel'] = 'Annuler';
$txt['mark_read_short'] = 'Marquer lu';

$txt['pm_short'] = 'Mes Messages Priv&eacute;s';
$txt['hello_member_ndt'] = 'Bonjour';

$txt['ajax_in_progress'] = 'Chargement...';

?>