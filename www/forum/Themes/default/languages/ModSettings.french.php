<?php
// Version: 1.1; ModSettings

$txt['smf3'] = 'Cette page vous permet de param&eacute;trer les caract&eacute;ristiques, mods et options de base de votre forum.  Veuillez voir les <a href="' . $scripturl . '?action=theme;sa=settings;id=' . $settings['theme_id'] . ';sesc=' . $context['session_id'] . '">r&eacute;glages du th&egrave;me</a> pour plus d\'options.  Cliquez sur l\'ic&ocirc;ne d\'aide pour plus d\'informations &agrave; propos d\'un param&egrave;tre.';

$txt['mods_cat_features'] = 'Caract&eacute;ristiques du Forum';
$txt['pollMode'] = 'R&eacute;glages des sondages';
$txt['smf34'] = 'Interdire les sondages';
$txt['smf32'] = 'Permettre les sondages';
$txt['smf33'] = 'Montrer les sondages existants comme des fils de discussion';
$txt['allow_guestAccess'] = 'Permettre aux invit&eacute;s de visiter le forum';
$txt['userLanguage'] = 'Permettre aux utilisateurs de choisir leur langue';
$txt['allow_editDisplayName'] = 'Autoriser les membres &agrave; changer leur pseudonyme&nbsp;?';
$txt['allow_hideOnline'] = 'Permettre aux membres de cacher leur pr&eacute;sence en ligne, except&eacute; aux admins&nbsp;?';
$txt['allow_hideEmail'] = 'Permettre aux utilisateurs de cacher leur adresse courriel aux autres personnes, except&eacute; aux admins&nbsp;?';
$txt['guest_hideContacts'] = 'Ne pas r&eacute;v&eacute;ler les d&eacute;tails de contact des membres aux invit&eacute;s';
$txt['titlesEnable'] = 'Activer les titres personnels';
$txt['enable_buddylist'] = 'Activer les listes d\'amis';

$txt['default_personalText'] = 'Texte personnel par d&eacute;faut';
$txt['max_signatureLength'] = 'Nombre maximum de caract&egrave;res permis dans les signatures<div class="smalltext">(0 pour aucun max.)</div>';
$txt['number_format'] = 'Format des nombres par d&eacute;faut';
$txt['time_format'] = 'Format de l\'heure (par d&eacute;faut)';
$txt['time_offset'] = 'D&eacute;calage horaire g&eacute;n&eacute;ral<div class="smalltext">(ajout&eacute; aux options sp&eacute;cifiques des membres)</div>';
$txt['failed_login_threshold'] = 'Seuil d\'erreurs de connexion';
$txt['lastActive'] = 'Seuil de rafra&icirc;chissement des utilisateurs en ligne';
$txt['trackStats'] = 'Statistiques quotidiennes activ&eacute;es';
$txt['hitStats'] = 'Pages vues quotidiennement activ&eacute;e (les stats doivent &ecirc;tre activ&eacute;s)';
$txt['enableCompressedOutput'] = 'Activer l\'envoi de donn&eacute;es compress&eacute;es';
$txt['databaseSession_enable'] = 'Utiliser des sessions en base de donn&eacute;es';
$txt['databaseSession_loose'] = 'Permettre aux navigateurs de revenir sur des pages cach&eacute;es';
$txt['databaseSession_lifetime'] = 'Secondes avant qu\'une session inutilis&eacute;e expire';
$txt['enableErrorLogging'] = 'Activer l\'indexation des erreur';
$txt['cookieTime'] = 'Dur&eacute;e par d&eacute;faut des t&eacute;moins (<em>cookies</em>) - en minutes';
$txt['localCookies'] = 'Activer l\'archivage local des t&eacute;moins<div class="smalltext">(SSI ne fonctionnera pas correctement avec cette option activ&eacute;e)</div>';
$txt['globalCookies'] = 'Utiliser des t&eacute;moins ind&eacute;pendants<div class="smalltext">(d&eacute;sactivez d\'abord l\'archivage local des cookies&nbsp;!)</div>';
$txt['securityDisable'] = 'D&eacute;sactivez la protection d\'administration';
$txt['send_validation_onChange'] = 'Envoyer un nouveau mot de passe au changement d\'une adresse courriel';
$txt['approveAccountDeletion'] = 'N&eacute;cessiter l\'approbation d\'un admin lors de la suppression d\'un compte membre';
$txt['autoOptDatabase'] = 'Optimiser les tables tous les X jours&nbsp;?<div class="smalltext">(0 pour d&eacute;sactiver.)</div>';
$txt['autoOptMaxOnline'] = 'Nombre maximum d\'utilisateurs en ligne lors de l\'optimisation<div class="smalltext">(0 pour aucun max.)</div>';
$txt['autoFixDatabase'] = 'R&eacute;parer automatiquement les tables contenant des erreurs';
$txt['allow_disableAnnounce'] = 'Permettre aux utilisateurs de d&eacute;sactiver les annonces';
$txt['disallow_sendBody'] = 'Ne pas permettre de message dans les notifications&nbsp;?';
$txt['modlog_enabled'] = 'Indexer les actions de mod&eacute;ration';
$txt['queryless_urls'] = 'URLs plus compr&eacute;hensibles pour les moteurs de recherche<div class="smalltext"><b>Serveurs Apache seulement&nbsp;!</b></div>';
$txt['max_image_width'] = 'Largeur max. des images envoy&eacute;es (0 = d&eacute;sactiv&eacute;)';
$txt['max_image_height'] = 'Hauteur max. des images envoy&eacute;es (0 = d&eacute;sactiv&eacute;)';
$txt['mail_type'] = 'Envoi de courriels';
$txt['mail_type_default'] = '(Configuration par d&eacute;faut de PHP)';
$txt['smtp_host'] = 'Serveur SMTP';
$txt['smtp_port'] = 'Port SMTP';
$txt['smtp_username'] = 'Identifiant SMTP';
$txt['smtp_password'] = 'Mot de passe SMTP';
$txt['enableReportPM'] = 'Permettre le rapport de messages personnels';
$txt['max_pm_recipients'] = 'Nombre maximum de destinataires autoris&eacute; dans un message personnel.<div class="smalltext">(0 pour illimit&eacute&nbsp;; les admins ne sont pas concern&eacute;s)</div>';

$txt['pm_posts_verification'] = 'Le compteur de messages sous lequel les utilisateurs doivent entrer un code lorsqu\'ils envoient des messages personnels.<div class="smalltext">(0 pour pas de limite, les admins sont exempt&eacute;s)</div>';
$txt['pm_posts_per_hour'] = 'Nombre de messages personnels qu\'un utilisateur peut envoyer en une heure.<div class="smalltext">(0 pour pas de limite, les mod&eacute;rateurs sont exempt&eacute;s)</div>';

$txt['mods_cat_layout'] = 'Affichage et Options';
$txt['compactTopicPagesEnable'] = 'Nombre maximum de liens vers les pages suivantes &agrave; afficher';
$txt['smf235'] = 'Pages suivantes &agrave; afficher&nbsp;:';
$txt['smf236'] = '&agrave; afficher';
$txt['todayMod'] = 'Activer l\'option &quot;Aujourd\'hui&quot;';
$txt['smf290'] = 'D&eacute;sactiv&eacute;';
$txt['smf291'] = 'Seulement Aujourd\'hui';
$txt['smf292'] = 'Aujourd\'hui &amp; Hier';
$txt['topbottomEnable'] = 'Afficher les boutons Monter/Descendre';
$txt['onlineEnable'] = 'Afficher \'En ligne\'/\'Hors ligne\' dans les messages et les MP';
$txt['enableVBStyleLogin'] = 'Afficher un champ de connexion sur toutes les pages';
$txt['defaultMaxMembers'] = 'Membres par page dans la liste des membres';
$txt['timeLoadPageEnable'] = 'Afficher le temps n&eacute;cessaire &agrave; la g&eacute;n&eacute;ration de la page';
$txt['disableHostnameLookup'] = 'D&eacute;sactiver la recherche de l\'h&ocirc;te&nbsp;?';
$txt['who_enabled'] = 'Activer &quot;Qui est en ligne&nbsp;?&quot;';

$txt['smf293'] = 'Karma';
$txt['karmaMode'] = 'Activer le karma';
$txt['smf64'] = 'D&eacute;sactiver le karma|Activer le karma total|Activer le karma positif/n&eacute;gatif';
$txt['karmaMinPosts'] = 'D&eacute;finir le nombre min. de messages avant de pouvoir modifier les karmas';
$txt['karmaWaitTime'] = 'D&eacute;finir le temps d\'attente (en heures) entre deux votes pour la m&ecirc;me personne';
$txt['karmaTimeRestrictAdmins'] = 'Contraindre les admins au temps d\'attente&nbsp;?';
$txt['karmaLabel'] = 'Label \'Karma\'';
$txt['karmaApplaudLabel'] = 'Label positif';
$txt['karmaSmiteLabel'] = 'Label n&eacute;gatif';

$txt['caching_information'] = '<div align="center"><b><u>Important&nbsp;! Veuillez lire ce qui suit avant d\'activer ces options.</b></u></div><br />
	SMF peut mettre des donn&eacute; en cache en utilisant des acc&eacute;l&eacute;rateurs. Les acc&eacute;l&eacute;rateurs actuellement support&eacute; sont les suivants&nbsp;:<br />
	<ul>
		<li>APC</li>
		<li>eAccelerator</li>
		<li>Turck MMCache</li>
		<li>Memcached</li>
		<li>Zend Platform/Performance Suite (pas Zend Optimizer)</li>
	</ul>
	Utiliser un syst&egrave;me de cache ne fonctionnera que si PHP est compil&eacute; avec l\'un des acc&eacute;l&eacute;rateurs pr&eacute;c&eacute;dents, ou si memcache est disponible.<br /><br />
	SMF g&egrave;re plusieurs niveaux de cache. Plus le niveau est haut et plus le processeur de votre serveur sera utilis&eacute; pour r&eacute;cup&eacute;rer les informations. Si un syst&egrave;me de cache est diponible sur votre serveur, il est recommand&eacute; de le tester au niveau 1 avant tout.
	<br /><br />
	Veuillez noter que l\'utilisation de memcache n&eacute;cessite que vous donniez quelques indications sur votre serveur dans les r&eacute;glages &agrave; effectuer ci-dessous. Elles doivent &ecirc;tre entr&eacute;es sous forme de liste, dont les &eacute;l&eacute;ments sont s&eacute;par&eacute;s par une virgule, comme dans l\'exemple suivant&nbsp;:<br />
	&quot;serveur1,serveur2,serveur3:port,serveur4&quot;<br /><br />
	Notez que si aucun port n\'est sp&eacute;cifi&eacute;, SMF utilisera le port 11211 par d&eacute;faut. SMF &eacute;quilibrera de mani&egrave;re al&eacute;atoire la charge sur les serveurs.
	<br /><br />
	%s
	<hr />';

$txt['detected_no_caching'] = '<b style="color: red;">SMF n\'a pas &eacute;t&eacute; capable de d&eacute;tecter un acc&eacute;l&eacute;rateur sur votre serveur.</b>';
$txt['detected_APC'] = '<b style="color: green">SMF a d&eacute;tect&eacute; qu\'APC est install&eacute; sur votre serveur.';
$txt['detected_eAccelerator'] = '<b style="color: green">SMF a d&eacute;tect&eacute; qu\'eAccelerator est install&eacute; sur votre serveur.';
$txt['detected_MMCache'] = '<b style="color: green">SMF a d&eacute;tect&eacute; que MMCache est install&eacute; sur votre serveur.';
$txt['detected_Zend'] = '<b style="color: green">SMF a d&eacute;tect&eacute; que Zend est install&eacute; sur votre serveur.';

$txt['cache_enable'] = 'Niveau de cache';
$txt['cache_off'] = 'Aucun cache';
$txt['cache_level1'] = 'Cache de niveau 1';
$txt['cache_level2'] = 'Cache de niveau 2 (non recommand&eacute;)';
$txt['cache_level3'] = 'Cache de niveau 3 (non recommand&eacute;)';
$txt['cache_memcached'] = 'R&eacute;glages de Memcache';

?>