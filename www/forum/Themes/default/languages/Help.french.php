<?php
// Version: 1.1; Help

global $helptxt;

$helptxt = array();

$txt[1006] = 'Fermer la fen&ecirc;tre';

$helptxt['manage_boards'] = '
	<b>G&eacute;stion des Sections et des Cat&eacute;gories</b><br />
	Dans ce menu, vous pouvez cr&eacute;er/r&eacute;organiser/supprimer des sections et les cat&eacute;gories
        les concernant.  Par exemple, si vous avez un gros site offrant des informations vari&eacute;es
        sur plusieurs sujets tels que &quot;Sports&quot; et &quot;Voitures&quot; et &quot;Musique&quot;, ces
        titres seraient ceux des cat&eacute;gories que vous cr&eacute;eriez.  Sous chacune de ces cat&eacute;gories vous voudriez assur&eacute;ment ins&eacute;rer, de mani&egrave;re hi&eacute;rarchique, des <i>sous-cat&eacute;gories</i>,
        ou &quot;sections&quot;, pour des fils de discussion les concernant.  C\'est une simple hi&eacute;rarchie, avec cette structure&nbsp;: <br />
	<ul>
		<li>
			<b>Sports</b>
			&nbsp;- Une &quot;cat&eacute;gorie&quot;
		</li>
		<ul>
			<li>
				<b>Baseball</b>
				&nbsp;- Une section de la cat&eacute;gorie &quot;Sports&quot;
			</li>
			<ul>
				<li>
					<b>Stats</b>
					&nbsp;- Une sous-section de la section &quot;Baseball&quot;
				</li>
			</ul>
			<li><b>Football</b>
			&nbsp;- Une section de la cat&eacute;gorie &quot;Sports&quot;</li>
		</ul>
	</ul>
	Les cat&eacute;gories vous permettent de s&eacute;parer votre forum en diff&eacute;rents fils de discussion (&quot;Voitures,
	Sports&quot;), et les &quot;sections&quot; en dessous sont les fils de discussion dans lesquels
        vos membres peuvent poster.  Un utilisateur int&eacute;ress&eacute; par les Pintos
        voudra poster un message dans &quot;Voitures->Pinto&quot;. Les cat&eacute;gories permettent aux gens
        de rapidement trouver ce qui les int&eacute;resse&nbsp;: au lieu d\'un &quot;Magasin&quot;, vous avez
        un &quot;Magasin d\'informatique&quot; et un &quot;Magasin de chaussures&quot; o&ugrave; vous pouvez aller.  Cela simplifie
        votre recherche d\'un &quot;disque dur&quot;, parce que vous pouvez aller directement au &quot;Magasin d\'informatique&quot;
        plut&ocirc;t qu\'au &quot;Magasin de chaussures&quot; (o&ugrave; vous ne trouverez sans doute pas
        votre disque dur ;) ).<br />
	Comme pr&eacute;cis&eacute; plus haut, une section est un fil de discussion cl&eacute; sous une cat&eacute;gorie m&egrave;re.
        Si vous voulez discuter de &quot;Pintos&quot;, vous irez &agrave; la cat&eacute;gorie &quot;Voitures&quot; et
        irez &agrave; la section &quot;Pinto&quot; pour y poster votre avis &agrave; propos de cette automobile.<br />
	Les fonctions administratives possibles ici sont de cr&eacute;er des nouvelles sections
        sous chaque cat&eacute;gorie, r&eacute;ordonner les sections (placer &quot;Pinto&quot; sous &quot;Chevy&quot;), ou
        supprimer une section enti&egrave;rement.';

$helptxt['edit_news'] = '<b>&Eacute;dition des nouvelles</b><br />
	Ceci vous permet de d\'entrer du texte pour les nouvelles affich&eacute;es dans la barre de nouvelles et les nouvelles rotatives  (<em>news apparaissantes</em>) sur l\'accueil du forum.
	Ajoutez toutes les informations de votre choix (ex&nbsp;: &quot;Ne ratez pas la conf&eacute;rence de jeudi&quot;). Chaque nouvelle doit
        &ecirc;tre dans une bo&icirc;te s&eacute;par&eacute;e, et elles sont affich&eacute;es de mani&egrave;re al&eacute;atoire.';

$helptxt['view_members'] = '
	<ul>
		<li>
			<b>Voir tous les membres</b><br />
			Voir tous les membres dans votre forum. Il vous est pr&eacute;sent&eacute; une liste d\'hyperliens avec les pseudos des membres.  Vous pouvez cliquer
			sur n\'importe lequel de ces pseudos pour trouver plus de d&eacute;tails &agrave; propos du membre (site web, &acirc;ge, etc.), et en tant qu\'administrateur
			vous avez la possibilit&eacute; de modifier ces param&ecirc;tres. Vous avez un contr&ocirc;le vos membres, incluant la possibilit&eacute;
			de les supprimer de votre forum.<br /><br />
		</li>
		<li>
			<b>En attente d\'approbation</b><br />
			Cette rubrique n\'est affich&eacute; que si vous avez activ&eacute; l\'approbation par un administrateur des nouvelles inscriptions &agrave; votre forum. Peu importe qui s\'inscrit pour rejoindre votre
			forum, il ne sera un membre complet qu\'une fois son compte approuv&eacute; par un admin. La rubrique liste tous ces membres qui
			sont encore en attente d\'approbation, de m&ecirc;me que leur adresse courriel et leur adresse IP. Vous pouve choisir d\'accepter ou de rejeter (supprimer)
			n\'importe quel membre dans la liste en cochant la case suivant le nom du membre et en choisissant l\'action correcte &agrave; appliquer dans le menu d&eacute;roulant au bas
			de l\'&eacute;cran. Lorsque vous rejetez un membre, vous pouvez choisir de le supprimer en l\'avertissant ou non de votre d&eacute;cision.<br /><br />
		</li>
		<li>
			<b>En attente d\'activation</b><br />
			Cette rubrique n\'est visible que si vous avez activ&eacute; l\'activation des comptes membre sur votre forum. Cette section liste tous
			les membres qui n\'ont actuellement pas activ&eacute; leur nouveau compte. Depuis cet &eacute;cran, vous pouvez choisir de les accepter, de les rejeter ou de leur rappeler
			l\'activation de leur compte. Comme pour le param&egrave;tre pr&eacute;c&eacute;dent, vous avez la possibilit&eacute; d\'informer ou non le membre
			des actions que vous avez effectu&eacute;es.<br /><br />
		</li>
	</ul>';

$helptxt['ban_members'] = '<b>Bannir des membres</b><br />
	SMF offre la possibilit&eacute; de &quot;bannir&quot; des utilisateurs, afin d\'emp&ecirc;cher le retour de personnes ayant d&eacute;rang&eacute;
        l\'atmosph&egrave;re de votre forum par du pollupostage (spamming), des d&eacute;viations de sujets (trolling), etc. En tant qu\'administrateur,
        lorsque vous voyez un message, vous pouvez voir l\'adresse IP du posteur au moment de l\'envoi du message incrimin&eacute;.  Dans la liste de bannissement,
        vous entrez simplement cette adresse IP, sauvegardez, et l\'utilisateur banni ne pourra plus poster depuis son ordinateur. <br />Vous pouvez aussi
        bannir des gens par leur adresse courriel.';

$helptxt['modsettings'] = '<b>Modifier les Caract&eacute;ristiques et les Options</b><br />
	Plusieurs options peuvent &ecirc;tre modifi&eacute;es ici selon vos pr&eacute;f&eacute;rences.  Les options pour les modifications (mods) install&eacute;es vont g&eacute;n&eacute;ralement appara&icirc;tre ici.';

$helptxt['number_format'] = '<b>Format des nombres</b><br />
	Vous pouvez utiliser cette fonction afin de sp&eacute;cifier l\'allure qu\'auront les nombres ins&eacute;r&eacute;s dans votre forum. Le format de cette fonction se constitue de cette fa&ccedil;on&nbsp;:<br />
	<div style="margin-left: 2ex;">1,234.00</div><br />
	O&ugrave; \',\' est le caract&egrave;re utilis&eacute; pour s&eacute;parer les milliers des centaines, \'.\' est celui utilis&eacute; pour s&eacute;parer les unit&eacute;s des d&eacute;cimales et le nombre de z&eacute;ros dicte &agrave; quelle d&eacute;cimale les nombres doivent &ecirc;tre arrondis.';

$helptxt['time_format'] = '<b>Format de l\'heure</b><br />
	Vous avez la possibilit&eacute; d\'ajuster la mani&egrave;re dont le temps et les dates seront affich&eacute;s sur votre forum.  Il y a beaucoup de lettres, mais c\'est relativement simple.  La convention d\'&eacute;criture s\'accorde avec celle de la fonction <tt>strftime</tt> de PHP et est d&eacute;crite ci-dessous (plus de d&eacute;tails peuvent &ecirc;tre trouv&eacute;s sur <a href="http://www.php.net/manual/function.strftime.php" target="_blank">php.net</a>).<br />
	<br />
	Les caract&egrave;res suivants sont reconnus en tant qu\'entr&eacute;es dans la cha&icirc;ne du format de l\'heure&nbsp;: <br />
	<span class="smalltext">
	&nbsp;&nbsp;%a - Nom du jour (abbr&eacute;viation)<br />
	&nbsp;&nbsp;%A - Nom du jour (complet)<br />
	&nbsp;&nbsp;%b - Nom du mois (abbr&eacute;viation)<br />
	&nbsp;&nbsp;%B - Nom du mois (complet)<br />
	&nbsp;&nbsp;%d - Jour du mois (01 &agrave; 31)<br />
	&nbsp;&nbsp;%D - La m&ecirc;me chose que %m/%d/%y *<br />
	&nbsp;&nbsp;%e - Jour du mois (1 &agrave; 31) *<br />
	&nbsp;&nbsp;%H - Heure au format 24 heures (de 00 &agrave; 23)<br />
	&nbsp;&nbsp;%I - Heure au format 12 heures (de 01 &agrave; 12)<br />
	&nbsp;&nbsp;%m - Num&eacute;ro du mois (01 &agrave; 12)<br />
	&nbsp;&nbsp;%M - Minutes en chiffres<br />
	&nbsp;&nbsp;%p - Met &quot;am&quot; ou &quot;pm&quot; selon la p&eacute;riode de la journ&eacute;e<br />
	&nbsp;&nbsp;%R - Heure au format 24 heures *<br />
	&nbsp;&nbsp;%S - Secondes en chiffres<br />
	&nbsp;&nbsp;%T - Temps en ce moment, la m&ecirc;me chose que %H:%M:%S *<br />
	&nbsp;&nbsp;%y - Ann&eacute;e au format 2 chiffres (00 to 99)<br />
	&nbsp;&nbsp;%Y - Ann&eacute;e au format 4 chiffres<br />
	&nbsp;&nbsp;%Z - Fuseau horaire, nom ou abbr&eacute;viation<br />
	&nbsp;&nbsp;%% - Le symbole \'%\' en lui-m&ecirc;me<br />
	<br />
	<i>* Ne fonctionnent pas sur les serveurs Windows.</i></span>';

$helptxt['live_news'] = '<b>En direct de Simple Machines...</b><br />
	Cette bo&icirc;te affiche les derni&egrave;res d&eacute;p&ecirc;ches en provenance de <a href="http://www.simplemachines.org/" target="_blank">www.simplemachines.org</a>.
        Vous devriez y surveiller les annonces concernant les mises &agrave; jour, nouvelles versions de SMF et informations importantes de Simple Machines.';

$helptxt['registrations'] = '<b>Gestion des inscriptions</b><br />
	Cette section contient toutes les fonctions n&eacute;cessaires &agrave; la gestion des nouvelles inscriptions &agrave; votre forum.  Elle peut contenir jusqu\'&agrave; quatre
        rubriques, visibles selon vos param&egrave;tres de forum.  Elles sont&nbsp;:<br /><br />
	<ul>
		<li>
			<b>Inscrire un nouveau membre</b><br />
			&Agrave; partir de cet &eacute;cran, vous pouvez inscrire un nouveau membre &agrave; sa place.  Cette option peut &ecirc;tre utile lorsque les inscriptionss &agrave; un forum sont d&eacute;sactiv&eacute;es
                        pour les nouveaux membres, ou lorsque l\'administrateur souhaite se cr&eacute;er un compte de test.  Si l\'activation du nouveau compte par le membre est s&eacute;lectionn&eacute;e,
                        le nouveau membre recevra un courriel contenant un lien d\'activation, qu\'il devra cliquer avant de pouvoir utiliser son compte.  De fa&ccedil;on similaire, vous pouvez choisir d\'envoyer
                        le nouveau mot de passe &agrave; l\'adresse courriel sp&eacute;cifi&eacute;e.
		</li>
			<b>Modifier l\'accord d\'inscription</b><br />
			Ceci vous permet de sp&eacute;cifier le texte de l\'accord d\'inscription affich&eacute; lors de l\'inscription d\'un membre sur votre forum.
			Vous pouvez ajouter ou enlever n\'importe quoi du texte d\'accord par d&eacute;faut, qui est inclut avec SMF.<br /><br />
		</li>
		<li>
			<b>Choisir les noms r&eacute;serv&eacute;s</b><br />
			En utilisant cette interface, vous pouvez sp&eacute;cifier des mots que vous souhaitez voir prohib&eacute;s dans les identifiants et pseudonymes de vos membres.<br /><br />
		</li>
		<li>
			<b>Param&egrave;tres</b><br />
			Cette section ne sera visible que si vous avez la permission d\'administrer le forum. Depuis cette interface, vous pouvez d&eacute;cider de la m&eacute;thode d\'inscription
                        en vigueur sur votre forum, de m&ecirc;me que quelques autres r&eacute;glages relatifs &agrave; l\'inscription.
		</li>
	</ul>';

$helptxt['modlog'] = '<b>Journal de mod&eacute;ration</b><br />
	Cette section permet &agrave; l\'&eacute;quipe des administrateurs de conserver des traces de chaque action de mod&eacute;ration effectu&eacute;e sur le forum par un mod&eacute;rateur ou un administrateur (voire m&ecirc;me par un membre).  Afin d\'assurer
        que les mod&eacute;rateurs ne peuvent enlever les r&eacute;f&eacute;rences aux actions entreprises, les entr&eacute;es ne peuvent pas &ecirc;tre supprim&eacute;es avant 24 heures suivant leur application.
        La colonne \'Objet\' liste les variables associ&eacute;es &agrave; l\'action.';
$helptxt['error_log'] = '<b>Journal d\'Erreurs</b><br />
	Le journal d\'erreurs conserve des traces de toutes les erreurs s&eacute;rieuses rencontr&eacute;es lors de l\'utilisation de votre forum.  Il liste toutes les erreurs par date, qui peuvent &ecirc;tre r&eacute;cup&eacute;r&eacute;es
        en cliquant sur la fl&egrave;che noire accompagnant chaque date.  De plus, vous pouvez filtrer les erreurs en s&eacute;lectionnant l\'image accompagnant les statistiques des erreurs.  Ceci
        vous permet, par exemple, de filtrer les erreurs par nom de membre.  Lorsqu\'un filtre est actif les seuls r&eacute;sultats affich&eacute;s seront ceux correspondants aux crit&egrave;res du filtre.';
$helptxt['theme_settings'] = '<b>R&eacute;glages du Th&egrave;me</b><br />
	L\'&eacute;cran des r&eacute;glages vous permet de modifier certains r&eacute;glages sp&eacute;cifiques &agrave; un th&egrave;me.  Ces r&eacute;glages incluent des options telles que le r&eacute;pertoire du th&agrave;me et l\'URL du th&egrave;me, mais
        aussi des options affectant le rendu &agrave; l\'&eacute;cran de votre forum.  La plupart des th&egrave;mes poss&eacute;dent une vari&eacute;t&eacute; d\'options configurables par l\'utilisateur, vous permettant d\'adapter un th&egrave;me
        &agrave; vos besoins individuels.';
$helptxt['smileys'] = '<b>Gestionnaire d\'&eacute;motic&ocirc;nes</b><br />
	Ici, vous pouvez ajouter et supprimer des &eacute;motic&ocirc;nes et des jeux d\'&eacute;motic&ocirc;nes.  Note importante&nbsp;: si un &eacute;motic&ocirc;ne est pr&eacute;sent dans un jeu, il l\'est aussi dans tous les autres - autrement, cela pourrait &ecirc;tre
        m&ecirc;lant pour les utilisateurs utilisant des jeux diff&eacute;rents.<br /><br />

	Vous pouvez aussi modifier les ic&ocirc;nes de message dans cette interface, si vous les avez activ&eacute; sur la page des param&egrave;tres.';
$helptxt['calendar'] = '<b>G&eacute;rer le calendrier</b><br />
	Ici vous pouvez modifier les r&eacute;glages courants du calendrier, de m&ecirc;me qu\'ajouter et supprimer des f&ecirc;tes qui apparaissent dans le calendrier.';

$helptxt['serversettings'] = '<b>Param&egrave;tres Serveur</b><br />
	Ici, vous pouvez r&eacute;gler la configuration de votre serveur. Cette section comprend la base de donn&eacute;es et les chemins des dossiers, ainsi que d\'autres
	options de configuration importantes tels que les param&egrave;tres de courriel et de cache. Faites attention lors de la modification des ces param&egrave;tres,
	ils pourraient rendre le forum inaccessible';

$helptxt['topicSummaryPosts'] = 'Ceci vous permet de r&eacute;gler le nombre de messages pr&eacute;c&eacute;demment post&eacute;s affich&eacute;s dans le sommaire du fil de discussion &agrave; l\'&eacute;cran de r&eacute;ponse &agrave; un sujet.';
$helptxt['enableAllMessages'] = 'Mettez ici le nombre <em>maximum</em> de messages qu\'un fil de discussion aura lors de l\'affichage par le lien &quot;Tous&quot;.  Le r&eacute;gler au-dessous du &quot;Nombre de messages &agrave; afficher lors du visionnement d\'un fil de discussion:&quot; signifiera simplement que le lien ne sera jamais affich&eacute;, et indiquer une valeur trop &eacute;lev&eacute;e peut ralentir votre forum.';

$helptxt['enableStickyTopics'] = 'Les fils de discussion &eacute;pingl&eacute;s sont des fils de discussion qui restent en haut de la liste des sujets. Ils sont surtout utilis&eacute;s pour des messages
         importants. Bien que vous pouvez changer cette pr&eacute;f&eacute;rence dans les permissions, par d&eacute;faut, seuls les mod&eacute;rateurs et les administrateurs, par d&eacute;faut, peuvent &eacute;pingler des fils de discussion.';
$helptxt['allow_guestAccess'] = 'D&eacute;cocher cette option emp&ecirc;chera toute autre action aux invit&eacute;s &agrave; part les op&eacute;rations de base - connexion, enregistrement, rappel du mot de passe, etc. - sur votre forum.  Ce n\'est pas comme d&eacute;sactiver l\'acc&egrave;s aux sections pour les invit&eacute;s.';
$helptxt['userLanguage'] = 'Activer cette option permettra aux utilisateurs de s&eacute;lectionner la langue dans laquelle le forum leur sera affich&eacute;.  Cela n\'affectera pas la langue par d&eacute;faut.';
$helptxt['trackStats'] = 'Stats&nbsp;:<br />Ceci permettra aux visiteurs de voir les derniers messages post&eacute;s et les fils de discussion les plus populaires sur votre forum.
         &Ccedil;a affichera aussi plusieurs autres statistiques, comme le record d\'utilisateurs en ligne au m&ecirc;me moment, les nouveaux membres et les nouveaux fils de discussion.<hr />
	Pages vues&nbsp;:<br />Ajoute une autre colonne &agrave; la page des statistiques contenant le nombre de pages vues sur votre forum.';
$helptxt['titlesEnable'] = 'Activer les titres personnels permettra aux membres poss&eacute;dant les permissions suffisantes de s\'attribuer un titre sp&eacute;cial pour eux-m&ecirc;mes. Il sera affich&eacute; sous leur pseudonyme.<br /><i>Par exemple:</i><br />Jeff<br />Le gars qui rox';
$helptxt['topbottomEnable'] = 'Ceci ajoutera des boutons &quot;Monter&quot; et &quot;Descendre&quot; au d&eacute;but et &agrave; la fin de chaque sujet, afin que les visiteurs passent plus vite du haut au bas de la page et <i>vice versa</i>, sans utiliser la molette de la souris ou la barre de d&eacute;filement.';
$helptxt['onlineEnable'] = 'Ceci affichera une image indiquant si l\'utilisateur est connect&eacute; ou non en ce moment.';
$helptxt['todayMod'] = 'Cette option affichera &quot;Aujourd\'hui&quot;, ou &quot;Hier&quot;, &agrave; la place de la date.';
$helptxt['enablePreviousNext'] = 'Cette option affichera un lien vers le sujet pr&eacute;c&eacute;dant et le fil de discussion suivant.';
$helptxt['pollMode'] = 'Ceci d&eacute;termine si les sondages sont activ&eacute;s ou non. Si les sondages sont d&eacute;sactiv&eacute;s, tous les sondages actuels sont cach&eacute;s sur la liste des fils de discussion. Vous pouvez choisir de continuer &agrave; afficher la partie fil de discussion des sondages en s&eacute;lectionnant &quot;Montrer les sondages existants comme des fils de discussion&quot;.<br /><br />Pour choisir qui peut poster et voir des sondages (et similaires), vous pouvez autoriser ou refuser ces permissions. Rappelez-vous de ceci si les sondages sont d&eacute;sactiv&eacute;s.';
$helptxt['enableVBStyleLogin'] = 'Ceci affichera un champ de connexion au bas de chaque page du forum, si le visiteur n\'est pas encore connect&eacute;.';
$helptxt['enableCompressedOutput'] = 'Cette option compressera les donn&eacute;es envoy&eacute;es, afin de diminuer la consommation de bande passante, mais requiert que zlib soit install&eacute; sur le serveur.';
$helptxt['databaseSession_enable'] = 'Cette fonction utilise la base de donn&eacute;es pour le stockage des sessions - c\'est mieux pour des serveurs &agrave; charge balanc&eacute;e, mais aide &agrave; r&eacute;gler tous les probl&egrave;mes de fin de session ind&eacute;sir&eacute;e et peut aider le forum &agrave; fonctionner plus rapidement.';
$helptxt['databaseSession_loose'] = 'Activer cette option diminuera la bande passante utilis&eacute;e par le forum, et fait en sorte que lorsque l\'utilisateur revient sur ses pas, la page n\'est pas recharg&eacute;e - le point n&eacute;gatif de cette option est que les (nouveaux) ic&ocirc;nes ne seront pas mis &agrave; jour, ainsi que quelques autres choses. (except&eacute; si vous rechargez cette page plut&ocirc;t que de retourner sur vos pas.)';
$helptxt['databaseSession_lifetime'] = 'Ceci est le temps en secondes avant que la session se termine automatiquement apr&egrave;s son dernier acc&egrave;s.  Si une session n\'a pas &eacute;t&eacute; acc&eacute;d&eacute;e depuis trop longtemps, un message &quot;Session termin&eacute;e&quot; est affich&eacute;.  Tout ce qui est au-dessus de 2400 secondes est recommand&eacute;.';
$helptxt['enableErrorLogging'] = 'Ceci indexera toutes les erreurs rencontr&eacute;es, comme les connexions non r&eacute;ussies, afin que vous puissiez les consulter lorsque quelque chose ne va pas.';
$helptxt['allow_disableAnnounce'] = 'Ceci permettra aux utilisateurs de d&eacute;s&eacute;lectionner la r&eacute;ception des annonces du forum que vous envoyez en cochant &quot;Annoncer le fil de discussion&quot; lorsque vous postez un message.';
$helptxt['disallow_sendBody'] = 'Cette option supprime l\'option de recevoir le texte des r&eacute;ponses et les messages dans les courriels de notification.<br /><br />Souvent, les membres vont r&eacute;pondre au courriel de notification, ce qui signifie dans plusieurs cas la bo&icirc;te courriel du webmestre.';
$helptxt['compactTopicPagesEnable'] = 'Ceci est le nombre de pages interm&eacute;diaires &agrave; afficher lors du visionnement d\'un sujet.<br /><i>Exemple&nbsp;:</i>
		&quot;3&quot; pour afficher&nbsp;: 1 ... 4 [5] 6 ... 9 <br />
		&quot;5&quot; pour afficher&nbsp;: 1 ... 3 4 [5] 6 7 ... 9';
$helptxt['timeLoadPageEnable'] = 'Ceci affichera au bas du forum le temps en secondes utilis&eacute; par SMF pour g&eacute;n&eacute;rer la page en cours.';
$helptxt['removeNestedQuotes'] = 'Ceci n\'affichera que la citation du message en question, aucune autre citation de ce sujet ne sera imbriqu&eacute;e dans cette citation.';
$helptxt['simpleSearch'] = 'Ceci affichera un formulaire de recherche simple ainsi qu\'un lien vers un formulaire contenant plus d\'options.';
$helptxt['max_image_width'] = 'Cette option vous permet de sp&eacute;cifier une taille maximale pour les images post&eacute;es.  Les images plus petites ne seront pas affect&eacute;es.';
$helptxt['mail_type'] = 'Cette option vous permet d\'utiliser soit le r&eacute;glage par d&eacute;faut de PHP ou de le surpasser en utilisant le protocole SMTP.  PHP ne supporte pas l\'authentification (que plusieurs FAI requi&egrave;rent maintenant) donc vous devriez vous renseigner avant d\'utiliser cette option.  Notez que SMTP peut &ecirc;tre plus lent que sendmail et que certains serveurs ne prendront pas en compte les identifiants et mot de passe.<br /><br />Vous n\'avez pas &agrave; renseigner les informations SMTP si vous utilisez la configuration par d&eacute;faut de PHP.';
$helptxt['attachmentEnable'] = 'Les fichiers joints sont des fichiers que vos membres peuvent transf&eacute;rer sur votre serveur et attacher &agrave; leurs messages.<br /><br />
		<b>V&eacute;rifier l\'extension des fichiers joints</b>&nbsp;:<br /> Activez cette option pour v&eacute;rifier le type des fichiers transf&eacute;r&eacute;s.<br />
		<b>Extensions des fichiers joints autoris&eacute;es</b>&nbsp;:<br /> Vous pouvez sp&eacute;cifier pour quels types de fichiers vous permettez le transfert.<br />
                <b>R&eacute;pertoire des fichiers attach&eacute;s</b>&nbsp;:<br /> Le dossier dans lequel vous voulez entreposer les fichiers joints.<br />(exemple&nbsp;: /home/sites/monsite/www/forum/attachments)<br />
                <b>Taille max. du r&eacute;pertoire des f. j.</b> (en Ko)&nbsp;:<br /> Sp&eacute;cifiez la taille totale maximale du dossier des fichiers attach&eacute;s.<br />
                <b>Taille max. des f. j. par message</b> (en Ko)&nbsp;:<br /> Sp&eacute;cifiez la taille maximale totale des fichiers attach&eacute;s par message.<br />
                <b>Taille max. des f. j. par message</b> (en Ko)&nbsp;:<br /> Sp&eacute;cifiez la taille maximale de chaque fichier joint.<br />
                <b>Nombre maximal de f. j. par message</b> (en Ko)&nbsp;:<br /> Sp&eacute;cifiez le nombre de fichiers joints qu\'une personne peut attacher &agrave; un message.<br />
                <b>Afficher les images en f. j. en tant qu\'images dans les messages</b>&nbsp;:<br /> Si le fichier joint est une image, elle sera affich&eacute;e sous le message.<br />
		<b>Redimensionner les images lorsque affich&eacute;es sous les messages&nbsp;:</b> Si cette option est s&eacute;lectionn&eacute;e, une image suppl&eacute;mentaire (plus petite) sera sauvegard&eacute;e pour la vignette pour diminuer la consommation en bande passante.<br />
                <b>Largeur et hauteur maximale des vignettes&nbsp;:</b> Seulement utilis&eacute; avec l\'option &quot;Redimensionner les images lorsque affich&eacute;es sous les messages&quot;, la hauteur et la largeur maximale de la miniature de l\'image.  L\'image sera redimensionn&eacute;e proportionnellement.';
$helptxt['karmaMode'] = 'Le Karma est une fonction qui affiche la popularit&eacute; d\'un membre.  Les membres, s\'ils y sont autoris&eacute;s, peuvent
                \'Applaudir\' ou \'Huer\' les autres membres; c\'est la fa&ccedil;on de calculer la popularit&eacute; d\'un membre. Vous pouvez changer le
                nombre de messages n&eacute;cessaires pour avoir un &quot;karma&quot;, le temps n&eacute;cessaire entre un second vote pour la m&ecirc;me personne, et si les administrateurs
                ont &agrave; attendre ce laps de temps aussi.<br /><br />D&eacute;finir si des groupes de membres peuvent ou non fouetter et applaudir d\'autres membres est effectu&eacute;
                par une permission.  Si vous avez des probl&egrave;mes &agrave; faire fonctionner cette propri&eacute;t&eacute; pour tout le monde, v&eacute;rifiez deux fois vos permissions.';

$helptxt['cal_enabled'] = 'Le calendrier peut &ecirc;re utilis&eacute; afin d\'afficher les anniversaires et des dates importantes &agrave; votre communaut&eacute;.<br /><br />
		<b>Montrer les jours en tant que liens vers \'Poster un &Eacute;v&eacute;nement\'</b>&nbsp;:<br />Ceci permettra &agrave; vos membres de poster des &eacute;v&eacute;nements pour ce jour, lorsqu\'ils cliquent sur la date.<br />
		<b>Montrer le num&eacute;ro de semaine</b>&nbsp;:<br />Montre le num&eacute;ro de la semaine dans l\'ann&eacute;e.<br />
		<b>Montrer les jours de f&ecirc;te sur l\'accueil du forum</b>&nbsp;:<br />Montre les jours de f&ecirc;te dans une barre sur l\'accueil du forum.<br />
		<b>Afficher les anniversaires sur l\'accueil du forum</b>&nbsp;:<br />Montre les anniversaires du jour dans une barre sur l\'accueil du forum.<br />
		<b>Montrer les &eacute;v&eacute;nements sur l\'accueil du forum</b>&nbsp;:<br />Affiche les &eacute;v&eacute;nements du jour dans une barre sur l\'accueil du forum.<br />
		<b>Permettre les &eacute;v&eacute;nements qui ne sont li&eacute;s &agrave; aucun message</b>&nbsp;:<br />Permet aux membres de poster des &eacute;v&eacute;nements sans n&eacute;cessiter la cr&eacute;ation d\'un nouveau sujet dans le forum.<br />
		<b>Ann&eacute;e minimale</b>&nbsp;:<br />S&eacute;lectionne la &quot;premi&egrave;re&quot; ann&eacute;e dans la liste du calendrier.<br />
		<b>Ann&eacute;e maximale</b>&nbsp;:<br />S&eacute;lectionne la &quot;derni&egrave;re&quot; ann&eacute;e dans la liste du calendrier<br />
		<b>Couleur des anniversaires</b>&nbsp;:<br />S&eacute;lectionne la couleur du texte des anniversaires<br />
		<b>Couleur des &eacute;v&eacute;nements</b>&nbsp;:<br />S&eacute;lectionne la couleur du texte des &eacute;v&eacute;nements<br />
		<b>Couleur des jours de f&ecirc;te</b>&nbsp;:<br />S&eacute;lectionne la couleur du texte des f&ecirc;tes<br />
		<b>Permettre aux &eacute;v&eacute;nements de durer plusieurs jours</b>&nbsp;:<br />S&eacute;lectionnez pour permettre aux &eacute;v&eacute;nements de durer plusieurs jours.<br />
		<b>Nombre de jours maximal qu\'un &eacute;v&eacute;nement peut durer</b>&nbsp;:<br />S&eacute;lectionnez le nombre de jours au total qu\'un &eacute;v&eacute;nement peut durer.<br /><br />
                Rappelez-vous que l\'usage du calendrier (poster des &eacute;v&eacute;nements, voir des &eacute;v&eacute;nements, etc.) est contr&ocirc;lable par les r&eacute;glages des permissions &agrave; partir de l\'&eacute;cran de gestion des permissions.';

$helptxt['localCookies'] = 'SMF utilise des t&eacute;moins (&quot;cookies&quot;) pour conserver les informations de connexion d\'un membre.  Les t&eacute;moins peuvent &ecirc;tre stock&eacute;s dans un dossier global (monserveur.com) ou localement (monserveur.com/chemin/vers/mon/forum).<br />
	Cochez cette option si vous subissez certains probl&egrave;mes avec des utilisateurs d&eacute;connect&eacute;s automatiquement.<hr />
	Les t&eacute;moins stock&eacute;s dans un dossier global sont moins s&eacute;curis&eacute;s lorsqu\'ils sont utilis&eacute;s sur un serveur mutualis&eacute; (comme Multimania/Lycos, Free, OVH, ...).<hr />
	Les t&eacute;moins stock&eacute;s localement ne fonctionnent pas &agrave; l\'ext&eacute;rieur du dossier du forum.  Donc, si votre forum est install&eacute; dans le r&eacute;pertoire www.monserveur.com/forum, les pages telles que www.monserveur.com/index.php ne pourront pas acc&eacute;der aux t&eacute;moins.  Lors de l\'utilisation de SSI.php, il est recommand&eacute; de stocker les t&eacute;moins dans un dossier global.';
$helptxt['enableBBC'] = 'Activer cette fonction autorisera vos membres &agrave; utiliser les Codes Forum (BBCodes) sur votre forum, afin de permettre le formatage du texte, l\'insertion d\'images et plus.';
$helptxt['time_offset'] = 'Ce ne sont pas tous les propri&eacute;taires de forums qui veulent utiliser le fuseau horaire du serveur sur lequel ils sont h&eacute;berg&eacute;s.  Utilisez cette fonction pour sp&eacute;cifier un temps de d&eacute;calage (en heures) sur lequel le forum devrait se baser pour ses dates.  Les temps n&eacute;gatifs et d&eacute;cimaux sont permis.';
$helptxt['spamWaitTime'] = 'Ici vous pouvez sp&eacute;cifier le temps minimal requis entre deux envois de messages en provenance d\'un m&ecirc;me utilisateur.  Cette option peut &ecirc;tre utilis&eacute;e afin de contrer le pollupostage (&quot;spamming&quot;).';

$helptxt['enablePostHTML'] = 'Ceci permet l\'utilisation de quelques balises HTML basiques&nbsp;:
	<ul style="margin-bottom: 0;">
		<li>&lt;b&gt;, &lt;u&gt;, &lt;i&gt;, &lt;s&gt;, &lt;em&gt;, &lt;ins&gt;, &lt;del&gt;</li>
		<li>&lt;a href=&quot;&quot;&gt;</li>
		<li>&lt;img src=&quot;&quot; alt=&quot;&quot; /&gt;</li>
		<li>&lt;br /&gt;, &lt;hr /&gt;</li>
		<li>&lt;pre&gt;, &lt;blockquote&gt;</li>
	</ul>';

$helptxt['themes'] = 'Ici vous pouvez choisir si le th&egrave;me par d&eacute;faut peut &ecirc;tre utilis&eacute; ou non, quel th&egrave;me les invit&eacute;s verront ainsi que plusieurs autres options.  Cliquez sur un th&egrave;me &agrave; droite pour changer ses propri&eacute;t&eacute;s sp&eacute;cifiques.';
$helptxt['theme_install'] = 'Ceci vous permet d\'installer des nouveaux th&egrave;mes.  Vous pouvez proc&eacute;der en partant d\'un dossier d&eacute;j&agrave; cr&eacute;&eacute;, en transf&eacute;rant une archive d\'un th&egrave;me ou en copiant le th&egrave;me par d&eacute;faut.<br /><br />Notez bien que les archives de th&egrave;mes doivent contenir un fichier de d&eacute;finition <tt>theme_info.xml</tt>.';
$helptxt['enableEmbeddedFlash'] = 'Cette option permettra &agrave; vos visiteurs d\'ins&eacute;rer des animations Flash directement dans leurs messages, comme des images.  Ceci peut causer un s&eacute;rieux risque de s&eacute;curit&eacute;, m&ecirc;me si peu ont r&eacute;ussi &agrave; exploiter ce probl&egrave;me.<br /><br />UTILISEZ CETTE OPTION &Agrave; VOS PROPRES RISQUES&nbsp;!';
// !!! Add more information about how to use them here.
$helptxt['xmlnews_enable'] = 'Permet aux gens de faire r&eacute;f&eacute;rence aux <a href="' . $scripturl . '?action=.xml;sa=news">nouvelles r&eacute;centes</a> et autres donn&eacute;es similaires.  Il est recommand&eacute; de limiter la taille des messages parce que, lorsqu\'ils sont affich&eacute;s dans certains clients tel Trillian, il est attendu que la taille soit limit&eacute;e.';
$helptxt['hotTopicPosts'] = 'Indiquez le nombre de fils de discussion n&eacute;cessaires avant que le sujet ne re&ccedil;oive les &eacute;tiquettes &quot;Sujet populaire&quot; et &quot;fils de discussion tr&egrave;s populaire&quot;.';

//    Your site is at http://www.simplemachines.org/,<br />
//    And your forum is at http://forum.simplemachines.org/,<br />
//    Using this option will allow you to access the forum\'s cookie on your site.  Do not enable this if there are other subdomains (like hacker.simplemachines.org) not controlled by you.';
$helptxt['globalCookies'] = 'Permet l\'utilisation de t&eacute;moins (<i>cookies</i>) ind&eacute;pendants du sous-domaine.  Par exemple, si...<br />
	Votre site est situ&eacute; au http://www.simplemachines.org/,<br />
	Et votre forum est situ&eacute; au http://forum.simplemachines.org/,<br />
	Activer cette fonction vous permettra d\'utiliser les t&eacute;moins de votre forum sur votre site (gr&acirc;ce &agrave; SSI.php, par exemple).';
$helptxt['securityDisable'] = 'Ceci <i>d&eacute;sactive</i> la v&eacute;rification suppl&eacute;mentaire du mot de passe pour acc&eacute;der &agrave; la zone d\'administration.  &Ccedil;a n\'est pas recommand&eacute;&nbsp;!';
$helptxt['securityDisable_why'] = 'Ceci est votre mot de passe courant. (Le m&ecirc;me que vous utilisez pour vous connecter au forum quoi.)<br /><br />Avoir &agrave; le taper de nouveau assure que vous voulez bien effectuer quelque op&eacute;ration d\'administration, et que c\'est bien <b>vous</b> qui le faite.';
$helptxt['emailmembers'] = 'Dans ce message, vous pouvez inclure certaines &quot;variables&quot;.  Elles sont&nbsp;:<br />
	{$board_url} - L\'URL vers votre forum.<br />
	{$current_time} - L\'heure courante.<br />
	{$member.email} - L\'adresse courriel du membre.<br />
	{$member.link} - Le lien vers le profil du membre.<br />
	{$member.id} - L\'ID du membre.<br />
	{$member.name} - Le nom du membre.  (pour un message plus personnalis&eacute;.)<br />
	{$latest_member.link} - Le lien vers le profil du membre le plus r&eacute;cent.<br />
	{$latest_member.id} - L\'ID de l\'utilisateur le plus r&eacute;cemment inscrit.<br />
	{$latest_member.name} - Le nom du membre le plus r&eacute;cemment inscrit.';
$helptxt['attachmentEncryptFilenames'] = 'Encrypter les noms des fichiers joints permet le transfert de fichiers ayant un nom identique, d\'utiliser s&eacute;curitairement des fichiers .php pour g&eacute;rer ces pi&egrave;ces jointes et une plus grande s&eacute;curit&eacute;.  D\'un autre c&ocirc;t&eacute;, cela peut causer une reconstruction de base de donn&eacute;es plus difficile si quelque chose de radical arrive.';

$helptxt['failed_login_threshold'] = 'Sp&eacute;cifiez le nombre maximal de tentatives de connexion &agrave; un profil avant de rediriger l\'utilisateur vers le Rappel de Mot de Passe.';
$helptxt['oldTopicDays'] = 'Si cette option est activ&eacute;e, un avertissement sera affich&eacute; aux utilisateurs qui tenteront de r&eacute;pondre dans un fil de discussion dans lequel il n\'y a eu aucune intervention depuis plus longtemps qu\'un certain laps de temps, en jours, sp&eacute;cifi&eacute; par ce param&ecirc;tre. R&eacute;glez-la &agrave; 0 pour d&eacute;sactiver la fonction.';
$helptxt['edit_wait_time'] = 'Temps en secondes permis pour la modification d\'un message avant que la mention &quot;Derni&egrave;re &eacute;dition&quot; apparaisse.';
$helptxt['edit_disable_time'] = 'Nombre de minutes accord&eacute;es pour qu\'un utilisateur &eacute;dite ses messages. Mettre sur 0 pour d&eacute;sactiver. <br /><br /><i>Note: Cela n\'affectera pas l\'utilisateur qui a la permission d\'&eacute;diter les messages des autres.</i>';
$helptxt['enableSpellChecking'] = 'Active la v&eacute;rification orthographique.  Vous DEVEZ avoir la librairie pspell install&eacute;e sur votre serveur et PHP doit &ecirc;tre configur&eacute; de telle sorte qu\'il utilise cette librairie.  Votre serveur ' . (function_exists('pspell_new') ? '<span style="color: green">semble</span>' : '<span style="color: red">NE SEMBLE PAS</span>') . ' avoir la librairie pspell.';
$helptxt['lastActive'] = 'S&eacute;lectionnez le nombre de minutes &agrave; afficher dans &quot;Membres actifs dans les X derni&egrave;res minutes&quot;, sur l\'accueil du forum.  Par d&eacute;faut, la valeur est 15 minutes.';

$helptxt['autoOptDatabase'] = 'Cette fonction optimise votre base de donn&eacute;es tous les X jours.  Sp&eacute;cifiez 1 pour effectuer une optimisation quotidienne.  Vous pouvez aussi sp&eacute;cifier un nombre maximum d\'utilisateurs en ligne lors de l\'optimisation, donc vous ne surchargerez pas votre serveur ou importunerez un minimum de gens.';
$helptxt['autoFixDatabase'] = 'Ceci r&eacute;parera automatiquement les tables erron&eacute;es et continuera de fonctionner comme si rien ne s\'&eacute;tait produit.  Ceci peut &ecirc;tre utile, car la seule fa&ccedil;on de r&eacute;gler le probl&egrave;me est de R&Eacute;PARER la table en question, et ainsi votre forum continuera de fonctionner jusqu\'&agrave; ce que vous preniez les mesures n&eacute;cessaires.  La fonction vous envoie un courriel lors d\'un probl&egrave;me.';

$helptxt['enableParticipation'] = 'Cette fonction affiche une ic&ocirc;ne sp&eacute;ciale sur les fils de discussion dans lesquels un utilisateur est pr&eacute;c&eacute;demment intervenu.';

$helptxt['db_persist'] = 'Conserve une connexion permanente avec la base de donn&eacute;es afin d\'accro&icirc;tre les performances du forum.  Si vous &ecirc;tes sur un serveur mutualis&eacute; (Lycos, Free / Online, OVH, Celeonet, Lewis Media...), l\'activation de cette fonction peut vous causer des probl&egrave;mes avec votre h&eacute;bergeur, car elle consomme beaucoup de ressources syst&egrave;me.';

$helptxt['queryless_urls'] = 'Ceci modifie un peu l\'allure des URLs afin que les engins de recherches tels Google et Yahoo&nbsp;! les r&eacute;f&eacute;rencent mieux.  Les URLs ressembleront &agrave; index.php/topic,1.html.<br /><br />Cette option  ' . (strpos(php_sapi_name(), 'apache') !== false ? '<span style="color: green;">fonctionnera</span>' : '<span style="color: red;">ne fonctionnera pas</span>') . ' sur votre serveur.';
$helptxt['countChildPosts'] = 'S&eacute;lectionner cette option signifie que les messages et les fils de discussion dans une section parente seront compt&eacute;s dans leur totalit&eacute; sur la page d\'index.<br /><br />Cela rendra les choses notablement plus lentes, mais signifiera qu\'une parente avec aucun message ne montrera pas \'0\'.';
$helptxt['fixLongWords'] = 'Cette option casse les mots plus longs qu\'un certain nombre de lettres en plusieurs parties, afin de ne pas briser le th&egrave;me de votre forum.  (Enfin, le moins possible ^_^)';

$helptxt['who_enabled'] = 'Cette option vous permet d\'activer ou non la possibilit&eacute; de voir qui est en ligne sur le forum et ce qu\'il y fait.';

$helptxt['recycle_enable'] = '&quot;Recycle&quot; les fils de discussion et messages supprim&eacute;s vers une section sp&eacute;cifique, souvent une section cach&eacute; aux utilisateurs normaux.';

$helptxt['enableReportPM'] = 'Cette option permet aux utilisateurs de rapporter des messages personnels qu\'ils ont re&ccedil;us &agrave; l\'&eacute;quipe d\'administration. Ceci peut &ecirc;tre pratique pour aider &agrave; traquer les abus effectu&eacute;s &agrave; l\'aide du syst&egrave;me de messagerie personnelle.';
$helptxt['max_pm_recipients'] = 'Cette option vous permet de limiter la quantit&eacute; maximum de messages priv&eacute;s envoy&eacute; par un membre du forum. Cette option permet de lutter contre le Spam du syst&egrave;me de MP. Notez que les utilisateurs ayant la permission d\'envoyer des bulletins d\informations sont exempts de cette restriction. R&eacute;glez-la &agrave; 0 pour d&eacute;sactiver la fonction.';

$helptxt['pm_posts_verification'] = 'Cette option forcera les utilisateurs &agrave; entrer un code affich&eacute; par une image de v&eacute;rification &agrave; chaque fois qu\'ils envoient un message personnel. Seuls les utilisateurs avec un compteur de messages en dessous de l\'ensemble de nombres auront besoin de saisir le code - Cela devrait aider &agrave; lutter contre les robots spammeurs.';
$helptxt['pm_posts_per_hour'] = 'Cette option limitera le nombre de messages personnels qui pourront &ecirc;tre envoy&eacute;s par un utilisateur en une heure de temps. Cela n\'affecte pas les admins ou mod&eacute;rateurs.';

$helptxt['default_personalText'] = 'R&egrave;gle le texte par d&eacute;faut qu\'un utilisateur verra affich&eacute; sous son avatar, en tant que leur &quot;texte personnel&quot;.';

$helptxt['modlog_enabled'] = 'Rapporte toutes les actions de mod&eacute;ration dans un journal.';

$helptxt['guest_hideContacts'] = 'Si cette option est s&eacute;lectionn&eacute;e, cela cachera les adresses email et les d&eacute;tails de contact messenger
	de tous les membres de votre forum aux visiteurs';

$helptxt['registration_method'] = 'Cette fonction d&eacute;termine quelle m&eacute;thode d\'inscription doit &ecirc;tre adopt&eacute;e pour les gens d&eacute;sirant rejoindre votre forum.  Vous pouvez s&eacute;lectionner un de ces choix&nbsp;:<br /><br />
	<ul>
		<li>
			<b>Inscription d&eacute;sactiv&eacute;e&nbsp;:</b><br />
				D&eacute;sactive les proc&eacute;dures d\'inscription, ce qui signifie que personne ne peut plus s\'inscrire sur votre forum.<br />
		</li><li>
			<b>Inscription imm&eacute;diate</b><br />
				Les nouveaux membres peuvent se connecter et poster sur votre forum imm&eacute;diatement apr&egrave;s la proc&eacute;dure d\'inscription.<br />
		</li><li>
			<b>Activation par le membre</b><br />
				Lorsque cette option est s&eacute;lectionn&eacute;e, tous les membres qui s\'inscrivent au forum recevront un courriel contenant un lien pour activer leur compte.  Ils ne pourront utiliser leur identit&eacute; que lorsque leur compte aura &eacute;t&eacute; activ&eacute;.<br />
		</li><li>
			<b>Approbation du membre</b><br />
				Cette option fera en sorte que tous les nouveaux utilisateurs s\'inscrivant &agrave; votre forum devront &ecirc;tre approuv&eacute; par les administrateurs avant d\'&ecirc;tre membres de votre communaut&eacute;.
		</li>
	</ul>';
$helptxt['send_validation_onChange'] = 'Lorsque cette option est coch&eacute;e, tous les membres qui modifient leur adresse courriel dans leur profil devront r&eacute;activer leur compte gr&acirc;ce &agrave; un courriel envoy&eacute; &agrave; leur nouvelle adresse.';
$helptxt['send_welcomeEmail'] = 'Lorsque cette option est activ&eacute;e, tous les nouveaux membres recevront un courriel leur souhaitant la bienvenue sur votre communaut&eacute;.';
$helptxt['password_strength'] = 'Ce r&eacute;glage d&eacute;termine le niveau de s&eacute;curit&eacute; requis pour les mots de passe s&eacute;lectionn&eacute;s par les membres de votre forum. Plus &quot;&eacute;lev&eacute;&quot; est le niveau, plus dur il devrait &ecirc;tre de d&eacute;couvrir le mot de passe afin d\'&eacute;viter de compromettre les comptes de vos membres.
	Les niveaux possibles sont&nbsp;:
	<ul>
		<li><b>Bas&nbsp;:</b> Le mot de passe doit &ecirc;tre compos&eacute; d\'au moins quatre caract&egrave;res.</li>
		<li><b>Moyen&nbsp;:</b> Le mot de passe doit &ecirc;tre form&eacute; d\'au moins huit caract&egrave;res, et ne peut contenir des parties de l\'identifiant ou de l\'adresse courriel.</li>
		<li><b>&eacute;lev&eacute;&nbsp;:</b> Comme pour le niveau pr&eacute;c&eacute;dent, except&eacute; que le mot de passe doit aussi contenir des lettres majuscules et minuscules et au moins un chiffre.</li>
	</ul>';

$helptxt['coppaAge'] = 'La valeur sp&eacute;cifi&eacute;e dans ce champ d&eacute;termine l\'&agrave;ge minimum que doit avoir un membre pour avoir un acc&egrave; imm&eacute;diat aux sections.
	&Agrave; l\'inscription, il sera demand&eacute; aux membres de confirmer s\'ils sont au-dessus ou au-dessous de cet &acirc;ge, et dans ce second cas leur inscription sera rejet&eacute;e ou suspendue en attente d\'une autorisation parentale - d&eacute;pendamment des restrictions que vous sp&eacute;cifiez.
	Si la valeur est 0 pour cette option toutes les restrictions d\'&acirc;ge pour les prochaines inscriptions seront ignor&eacute;es.';
$helptxt['coppaType'] = 'Si la restriction d\'&acirc;ge est sp&eacute;cfi&eacute;e, ce param&egrave;tre d&eacute;finira ce qui se produit lorsqu\'un membre au-dessous de l\'&acirc;ge minimum requis tente de s\'inscrire sur votre forum. Il existe deux choix possibles&nbsp;:
	<ul>
		<li>
			<b>Rejeter son inscription&nbsp;:</b><br />
				N\'importe quel nouvel adh&eacute;rent au-dessous de l\'&acirc;ge minimum verra son inscription rejet&eacute;e imm&eacute;diatement.<br />
		</li><li>
			<b>N&eacute;cessiter l\'approbation d\'un parent/tuteur l&eacute;gal</b><br />
				N\'importe quel nouveau membre en-dessous de l\'&acirc;ge minimum qui tente de s\'inscrire sur votre forum verra son compte marqu&eacute; en attente d\'approbation et il lui sera remis un formulaire &agrave; faire remplir par ses parents ou tuteurs avant de pouvoir devenir membre de votre forum.
				Il lui sera aussi pr&eacute;sent&eacute; les informations de contact du forum ent&eacute;es sur la page des param&egrave;tres, afin que le formulaire d\'approbation parentale soit envoy&eacute;e &agrave; l\'administrateur par la poste ou par t&eacute;l&eacute;fax.
		</li>
	</ul>';
$helptxt['coppaPost'] = 'Les champs de contact sont requis afin que les formulaires d\'autorisation parentale pour les membres au-dessous de l\'&acirc;ge minimum soient envoy&eacute;s &agrave; l\'administrateur. Ces d&eacute;tails seront affich&eacute;s &agrave; tous les mineurs et il leur est requis d\'obtenir une approbation parentale. Au minimum, une adresse postale ou un num&eacute;ro de t&eacute;l&eacute;fax est requis.';

$helptxt['allow_hideOnline'] = 'Avec cette option activ&eacute;e, tous les membres pourront cacher leur &eacute;tat de connexion au forum aux autres visiteurs (except&eacute; aux administrateurs). Si elle est d&eacute;sactiv&eacute;e, seuls les utilisateurs qui peuvent mod&eacute;rer le forum peuvent cacher leur pr&eacute;sence. Notez bien que d&eacute;sactiver cette option ne changera rien dans le statut des membres connect&eacute;s en ce moment - cela ne leur emp&eacute;chera la man&oelig;uvre que pour les futures connexions.';
$helptxt['allow_hideEmail'] = 'Avec cette option activ&eacute; les membres peuvent choisir de cacher leur adresse courriel aux autres membres du forum. Toutefois, les administrateurs auront toujours acc&egrave;s &agrave; cette information.';

$helptxt['latest_support'] = 'Ce panneau vous affiche quelques-uns des probl&egrave;mes et questions les plus fr&eacute;;quents et communs et des informations sur votre serveur.  Ne vous inqui&eacute;tez pas, cette information n\'est journalis&eacute;e nulle part.<br /><br />Si cela reste bloqu&eacute; sur &quot;R&eacute;ception des informations de support&hellip;&quot;, votre ordinateur ne peut probablement pas se connecter sur <a href="http://www.simplemachines.org/" target="_blank">www.simplemachines.org</a>.';
$helptxt['latest_packages'] = 'Ici vous pouvez voir quelques-uns des paquets et mods les plus populaires et quelques-uns choisis au hasard, avec une installation facile et rapide.<br /><br />Si cette rubrique ne s\'affiche pas, votre ordinateur n\'arrive peut-&ecirc;tre pas &agrave; se connecter au site <a href="http://www.simplemachines.org/" target="_blank">www.simplemachines.org</a>.';
$helptxt['latest_themes'] = 'Cette zone vous montre quelques-uns des derniers th&egrave;mes et les plus populaires en provenance de <a href="http://www.simplemachines.org/" target="_blank">www.simplemachines.org</a>.  Cela peut n&eacute;anmoins ne pas s\'afficher correctement si votre ordinateur a du mal &agrave; se connecter au <a href="http://www.simplemachines.org/" target="_blank">www.simplemachines.org</a>.';

$helptxt['secret_why_blank'] = 'Pour votre s&eacute;curit&eacute;, la r&eacute;ponse &agrave; votre question (de m&ecirc;me que votre mot de passe) est encrypt&eacute;e de telle mani&egrave;re que SMF ne peut que v&eacute;rifier si vous entrez la bonne valeur, ainsi il ne peut jamais vous r&eacute;v&eacute;ler (ni &agrave; vous ni &agrave; personne d\'autre, heureusement&nbsp;!) quelle est votre r&eacute;ponse ou votre mot de passe.';
$helptxt['moderator_why_missing'] = 'Puisque la mod&eacute;ration est effectu&eacute;e sur une base section-par-section, vous devrez assigner les membres en tant que mod&eacute;rateurs &agrave; partir de <a href="javascript:window.open(\'' . $scripturl . '?action=manageboards\'); self.close();">l\'interface de gestion des sections</a>.';

$helptxt['permissions'] = 'Les permissions sont la mani&egrave;re par laquelle vous permettez ou interdisez aux groupes de membres d\'effectuer des choses sp&eacute;cifiques.<br /><br />Vous pouvez modifier plusieurs sections en m&ecirc;me temps en utilisant les cases &agrave; cocher, ou modifier les permissions d\'un groupe particulier en cliquant sur le lien \'Modifier\'';
$helptxt['permissions_board'] = 'Si c\'est r&eacute;gl&eacute; sur \'Global\', cela signifie que cette section ne poss&egrave;dera aucune permission particuli&egrave;re autre que celles g&eacute;n&eacute;rales de votre forum.  \'Local\' signifie qu\'elle aura ses propres permissions - s&eacute;par&eacute;es des permissions globales.  Ceci vous permet d\'avoir des sections avec plus ou moins de permissions que d\'autres, sans n&eacute;cessiter de r&eacute;gler toutes les permissions pour chaque section.';
$helptxt['permissions_quickgroups'] = 'Ceci vous permet d\'utiliser les r&eacute;glages de permissions par &quot;d&eacute;faut&quot; - standard signifie &quot;rien de sp&eacute;cial&quot;, restreint signifie &quot;comme un invit&eacute;&quot;, mod&eacute;rateur signifie &quot;les m&ecirc;mes droits qu\'un mod&eacute;rateur&quot;, et enfin maintenance signifie &quot;des permissions tr&egrave;s proches de celles d\'un administrateur&quot;.';
$helptxt['permissions_deny'] = 'Interdire des permissions peut &ecirc;tre utile quand vous voulez enlever des permissions &agrave; certains membres. Vous pouvez ajouter un groupe de membres avec une permission \'interdite\' pour les membres auquels vous voulez interdire une permission.<br /><br />&Agrave; utiliser avec pr&eacute;caution, une permission interdite restera interdite peu importe dans quels autres groupes de membres le membre fait partie.';
$helptxt['permissions_postgroups'] = 'Activer les permissions pour les groupes posteurs vous permettra d\'attribuer des permissions aux membres ayant post&eacute; un certain nombre de messages. Les permissions du groupe posteur sont <em>ajout&eacute;es</em> aux permissions des membres r&eacute;guliers.';
$helptxt['permissions_by_board'] = 'Activer cette option vous permettra, pour chaque section et pour chaque groupe de membres, de param&eacute;trer diff&eacute;rentes permissions. Par d&eacute;faut, une section utilise les permissions globales, mais cette option vous permet de passer la section &agrave; des permissions locales. Ceci offre une fa&ccedil;on tr&egrave;s sophistiqu&eacute;e de g&eacute;rer vos permissions.';
$helptxt['membergroup_guests'] = 'Le groupe de membres Invit&eacute;s contient tous les utilisateurs qui ne sont pas connect&eacute;s &agrave; un compte membre sur votre forum.';
$helptxt['membergroup_regular_members'] = 'Les membres r&eacute;guliers sont tous les membres connect&eacute;s &agrave; un compte membre sur votre forum, mais qui ne sont assign&eacute;s &agrave; aucun groupe permanent.';
$helptxt['membergroup_administrator'] = 'L\'administrateur peut, par d&eacute;finition, faire tout ce qu\'il veut et voir toutes les sections. Il n\'y a aucun r&eacute;glage de permissions pour les administrateurs.';
$helptxt['membergroup_moderator'] = 'Le groupe Mod&eacute;rateur est un groupe sp&eacute;cial. Les permissions et r&eacute;glages pour ce groupe s\'appliquent aux mod&eacute;rateurs mais uniquement <em>dans la section qu\'ils mod&egrave;rent</em>. Au dehors de ces sections, ils sont consid&eacute;r&eacute;s comme n\'importe quel autre membre r&eacute;gulier.';
$helptxt['membergroups'] = 'Dans SMF il y a deux types de groupes auquels vos membres peuvent appartenir. Ce sont&nbsp;:
	<ul>
		<li><b>Groupes permanents&nbsp;:</b> Un groupe permanent est un groupe dans lequel un membre n\'est pas assign&eacute; automatiquement. Pour assigner un membre dans un groupe permanent, allez simplement dans son profil et cliquez sur &quot;Param&egrave;tres relatifs au compte&quot;. Ici vous pouvez param&eacute;trer les diff&eacute;rents groupes permanents auxquels les membres peuvent appartenir.</li>
		<li><b>Groupes posteurs&nbsp;:</b> Au contraire des groupes permanents, un membre ne peut &ecirc;tre manuellement assign&eacute; &agrave; un groupe posteur, bas&eacute; sur le nombre de message. Les membres sont plut&ocirc;t assign&eacute;s automatiqement &agrave; un groupe posteur lorsqu\'il atteint le nombre minimum de messages requis pour faire partie de ce groupe.</li>
	</ul>';

$helptxt['calendar_how_edit'] = 'Vous pouvez modifier ces &eacute;v&eacute;nements en cliquant sur l\'ast&eacute;risque (*) rouge accompagnant leur nom.';

$helptxt['maintenance_general'] = '&Agrave; partir d\'ici, vous avez la possibilit&eacute; d\'optimiser toutes les tables de votre base de donn&eacute;es (cela les rend plus petites et plus rapides&nbsp;!), de v&eacute;rifier que vous poss&eacute;dez bien la derni&egrave;re version de SMF, de rechercher des erreurs qui peuvent d&eacute;ranger votre forum, recompter les totaux et vider vos journaux.<br /><br />Les deux derni&egrave;res options ne devraient &ecirc;tre utilis&eacute;es que lorsque quelque chose ne tourne pas rond, mais ne bousilleront pas votre forum.';
$helptxt['maintenance_backup'] = 'Cette section vous permettra de faire une copie de sauvegarde des messages, des r&eacute;glages, des membres et autres informations utiles de votre forum dans un gros fichier.<br /><br />Il est recommand&eacute; d\'effectuer cette op&eacute;ration souvent, par exemple hebdomadairement, pour plus de s&eacute;curit&eacute; et de protection.';
$helptxt['maintenance_rot'] = 'Ceci vous permet de supprimer <b>compl&egrave;tement</b> et <b>irr&eacute;vocablement</b> les vieux fils de discussion. Il est recommand&eacute; que vous effectuyez une copie de sauvegarde de votre base de donn&eacute;es avant de proc&eacute;der, au cas o&ugrave; vous enleviez quelque chose que vous ne vouliez pas supprimer.<br /><br />&Agrave; utiliser avec pr&eacute;caution.';

$helptxt['avatar_allow_server_stored'] = 'Ceci permet &agrave; vos membres de choisir un avatar stock&eacute; sur votre serveur.  Ils sont, g&eacute;n&eacute;ralement, dans le m&ecirc;me r&eacute;pertoire que SMF, sous le dossier /avatars.<br />Suggestion&nbsp;: en cr&eacute;ant des r&eacute;pertoires dans le dossier des avatars, vous pouvez ranger vos avatars dans des &quot;cat&eacute;gories&quot;.';
$helptxt['avatar_allow_external_url'] = 'Avec cette option activ&eacute;e, vos membres peuvent entrer une URL de leur choix pointant vers leur avatar.  L\'avantage de cette option est qu\'elle &eacute;conomise votre bande passante.  Mais son mauvais c&ocirc;t&eacute; est que vos membres peuvent s&eacute;lectionner des images tr&egrave;s grosses et/ou &agrave; caract&egrave;re douteux, ind&eacute;sir&eacute;.';
$helptxt['avatar_download_external'] = 'Avec cette option activ&eacute;e, le forum se sert de l\'URL donn&eacute;e par l\'utilisateur pour t&eacute;l&eacute;charger l\'avatar. Avec la r&eacute;ussite de l\'op&eacute;ration, l\'avatar sera trait&eacute; comme un avatar tranf&eacute;r&eacute;.';
$helptxt['avatar_allow_upload'] = 'Cette option est semblable &agrave; &quot;Autoriser les membres &agrave; utiliser un avatar distant&quot;, exception faite que vous avez un meilleur contr&ocirc;le sur les avatars, un meilleur temps de redimension et d\'affichage, et vos membres n\'ont pas &agrave; trouver d\'endroit o&ugrave; h&eacute;berger leurs avatars.<br /><br />Toutefois, le d&eacute;savantage de cette option est qu\'au fil du temps, elle peut consommer beaucoup d\'espace sur votre h&eacute;bergement.';
$helptxt['avatar_download_png'] = 'Les images au format PNG sont plus lourdes, mais offrent un rendu de meilleure qualit&eacute;.  Si la case est d&eacute;coch&eacute;e, le format JPEG sera utilis&eacute; &agrave; la place - qui rend des fichiers moins lourds, mais de moindre qualit&eacute;, surtout sur les dessins, qu\'il rend flous.';

$helptxt['disableHostnameLookup'] = 'Ceci d&eacute;sactive la recherche du nom de l\'h&ocirc;te, fonction parfois lente sur certains serveurs.  Notez que sa d&eacute;sactivation rend le syst&egrave;me de bannissement moins efficace.';

$helptxt['search_weight_frequency'] = 'Le facteur de pertinence est utilis&eacute; pour d&eacute;terminer l\'int&ecirc;r&ecirc;t des r&eacute;sultats de recherche. Changez ces facteurs pour les faire correspondre &agrave; des valeurs int&eacute;ressantes pour votre forum.  Par exemple, un forum de nouvelles aura un facteur d\'anciennet&eacute; du  message relativement bas. Toutes les valeurs sont en relation avec les autres et doivent &ecirc;tre des valeurs positives.<br /><br />Ce facteur compte le nombre de messages correspondants et divise ce r&eacute;sultat par le nombre de messages dans un fil de discussion.';
$helptxt['search_weight_age'] = 'Le facteur de pertinence est utilis&eacute; pour d&eacute;terminer l\'int&ecirc;r&ecirc;t des r&eacute;sultats de recherche. Changez ces facteurs pour les faire correspondre &agrave; des valeurs int&eacute;ressantes pour votre forum.  Par exemple, un forum de nouvelles aura un relativement grand facteur de \'&Acirc;ge du dernier message\'. Toutes les valeurs sont en relation avec les autres et doivent &ecirc;tre des valeurs positives.<br /><br />Ce facteur v&eacute;rifie l\'&acirc;ge des derniers messages d\'un fil de discussion. Plus r&eacute;cent est le message, le plus haut dans la liste il est positionn&eacute;.';
$helptxt['search_weight_length'] = 'Le facteur de pertinence est utilis&eacute; pour d&eacute;terminer l\'int&ecirc;r&ecirc;t des r&eacute;sultats de recherche. Changez ces facteurs pour les faire correspondre &agrave; des valeurs int&eacute;ressantes pour votre forum.  Par exemple, un forum de nouvelles aura un relativement grand facteur de \'&Acirc;ge du dernier message\'. Toutes les valeurs sont en relation avec les autres et doivent &ecirc;tre des valeurs positives.<br /><br />Ce facteur est bas&eacute; sur la longueur du fil de discussion. Plus le fil de discussion contient de r&eacute;ponses, plus le pointage est &eacute;lev&eacute;.';
$helptxt['search_weight_subject'] = 'Le facteur de pertinence est utilis&eacute; pour d&eacute;terminer l\'int&ecirc;r&ecirc;t des r&eacute;sultats de recherche. Changez ces facteurs pour les faire correspondre &agrave; des valeurs int&eacute;ressantes pour votre forum.  Par exemple, un forum de nouvelles aura un relativement grand facteur de \'&Acirc;ge du dernier message\'. Toutes les valeurs sont en relation avec les autres et doivent &ecirc;tre des valeurs positives.<br /><br />Ce facteur v&eacute;rifie si le terme recherch&eacute; peut &ecirc;tre trouv&eacute; ou non dans le titre du fil de discussion.';
$helptxt['search_weight_first_message'] = 'Le facteur de pertinence est utilis&eacute; pour d&eacute;terminer l\'int&ecirc;r&ecirc;t des r&eacute;sultats de recherche. Changez ces facteurs pour les faire correspondre &agrave; des valeurs int&eacute;ressantes pour votre forum.  Par exemple, un forum de nouvelles aura un relativement grand facteur de \'&Acirc;ge du dernier message\'. Toutes les valeurs sont en relation avec les autres et doivent &ecirc;tre des valeurs positives.<br /><br />Ce facteur v&eacute;rifie si le terme recherch&eacute; peut &ecirc;tre trouv&eacute; ou non dans le premier message du fil de discussion.';

$helptxt['search_weight_sticky'] = 'Des facteurs d\'importance sont utilis&eacute;s pour d&eacute;terminer la pertinence d\'un r&eacute;sultat de recherche. Changez ces facteurs d\'importance pour que &ccedil;a corresponde &agrave; des valeurs qui sont sp&eacute;cifiquement importantes pour votre forum. Par exemple, un forum de nouvelles aura un relativement grand facteur de \'&Acirc;ge du dernier message\'. Toutes les valeurs sont en relation avec les autres et doivent &ecirc;tre des valeurs positives.<br /><br />Ce facteur v&eacute;rifie si un fil de discussion est populaire et augmente le score de pertinence si il l\'est.';
$helptxt['search'] = 'Ajuste ici tous les r&eacute;glages de la fonction recherche.';
$helptxt['search_why_use_index'] = 'Un index de recherche peut consid&eacute;rablement am&eacute;liorer l\'ex&eacute;cution des recherches sur votre forum. Particuli&egrave;rement quand le nombre de messages sur un forum est de plus en plus grand, la recherche sans index peut prendre un bon moment et augmenter la pression sur votre base de donn&eacute;es. Si votre forum a plus de 50.000 messages, vous devriez penser &agrave; cr&eacute;er un index de recherche pour assurer l`\'ex&eacute;cution maximale de votre forum.<br /><br />A noter qu\'un index de recherche peut prendre un certain espace..  Un index &agrave; texte int&eacute;gral est un index int&eacute;gr&eacute; &agrave; MySQL. C\'est relativement compact (approximativement la m&ecirc;me taille que la table message), mais beaucoup de mots ne sont pas index&eacute;s et il se peut que quelques recherches s\'av&egrave;rent tr&egrave;s lentes. L\'index personnalis&eacute; est souvent plus grand (selon votre configuration, cela peut &egrave;tre plus de 3 fois la taille de la table des messages) mais la performance est meilleure qu\'en texte int&eacute;gral et relativement stable.';

$helptxt['see_admin_ip'] = 'Les adresses IP sont affich&eacute;es aux administrateurs et aux mod&eacute;rateurs afin de faciliter la mod&eacute;ration et de rendre plus efficace la traque des personnes impliqu&eacute;es.  Rappelez-vous que toutes les adresses IP ne peuvent &ecirc;tre identifi&eacute;es, et que la plupart des adresses changent p&eacute;riodiquement.<br /><br />Les membres sont aussi autoris&eacute;s &agrave; voir leur adresse IP, mais pas celle des autres.';
$helptxt['see_member_ip'] = 'Votre adresse IP est affich&eacute;e seulement &agrave; vous et aux mod&eacute;rateurs.  Rappelez-vous que cette information ne permet pas de vous identifier en tant qu\'individu, et que la plupart des adresses changent p&eacute;riodiquement.<br /><br />Vous ne pouvez pas voir l\'adresse IP des autres, et les autres ne peuvent pas voir la v&ocirc;tre.';

$helptxt['ban_cannot_post'] = 'La restriction \'Ne peut pas poster\' rend le forum en lecture seule pour l\'utilisateur banni. L\'utilisateur ne peut pas cr&eacute;er de nouveaux fils de discussion ou r&eacute;pondre &agrave; ceux existants, envoyer des messages personnels ou voter dans les sondages. L\'utilisateur banni peut toutefois encore lire ses messages personnels et les fils de discussion.<br /><br />Un message d\'avertissement est affich&eacute; aux utilisateurs qui sont bannis avec cette restriction.';

$helptxt['posts_and_topics'] = '
	<ul>
		<li>
			<b>Param&egrave;tres des messages</b><br />
			Modifie les param&egrave;tres relatifs au postage des messages et la fa&ccedil;on dont ceux-ci sont affich&eacute;s. Vous pouvez aussi activer le correcteur orthographique ici.
		</li><li>
			<b>Code d\'affichage</b><br />
			Active le code montrant les messages dans un rendu correct. Ajuste aussi quels codes sont permis et ceux qui sont d&eacute;sactiv&eacute;s.
		</li><li>
			<b>Mots censur&eacute;s</b>
			Dans le but de prot&eacute;ger la langue de votre forum sous un certain contr&ocirc;le, vous pouvez censurer certains mots. Cette fonction vous permet de convertir des mots interdits en d\'autres mots innocents. D\'o&ugrave; une possibilit&eacute; d&eacute;riv&eacute; de remplacement de termes choisis.
		</li><li>
			<b>Param&egrave;tres des fils de discussion</b>
			Modifie les param&egrave;tres relatifs aux fils de discussion. Le nombre de fils de discussion par page, l\'activation ou non des fils de discussion &eacute;pingl&eacute;s, le nombre de messages par fil de discussion pour qu\'il soit not&eacute; comme populaire, etc.
		</li>
	</ul>';

?>