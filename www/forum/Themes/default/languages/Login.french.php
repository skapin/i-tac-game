<?php
// Version: 1.1.2; Login

$txt[37] = 'Vous devez mettre un identifiant.';
$txt[38] = 'Vous n\'avez entr&eacute; aucun mot de passe';
$txt[39] = 'Mot de passe incorrect';
$txt[98] = 'Choisissez un identifiant';
$txt[155] = 'Mode Maintenance';
$txt[245] = 'Inscription r&eacute;ussie';
$txt[431] = 'Vous &ecirc;tes maintenant un membre du forum.';
// Use numeric entities in the below string.
$txt[492] = "et votre mot de passe est";
$txt[500] = 'Merci d\'entrer une adresse courriel valide, %s.';
$txt[517] = 'Informations Requises';
$txt[520] = 'Utilis&eacute; uniquement pour la connexion &agrave; SMF.';
$txt[585] = 'Je suis d\'accord';
$txt[586] = 'Je ne suis pas d\'accord';
$txt[633] = 'Attention&nbsp;!';
$txt[634] = 'Seuls les membres inscrits sont autoris&eacute;s &agrave; acc&eacute;der &agrave; cette section.';
$txt[635] = 'Merci de vous connecter ci-dessous ou';
$txt[636] = 'vous inscrire';
$txt[637] = 'sur ' . $context['forum_name'] . '.';
// Use numeric entities in the below string.
$txt[701] = "Vous pouvez le changer apr&#232;s vous &#234;tre connect&#233; en allant sur votre page de profil, ou en visitant cette page apr&#232;s identification&#160;:";
$txt[719] = "Votre identifiant est";
$txt[730] = 'Cette adresse courriel (%s) est d&eacute;j&agrave; utilis&eacute;e par un membre inscrit. Si vous pensez que c\'est une erreur, allez sur la page de connexion et utilisez le Rappel de Mot de Passe avec cette adresse.';

$txt['login_hash_error'] = 'La s&eacute;curit&eacute; des mots de passe a r&eacute;cemment &eacute;t&eacute; accrue.  Veuillez entrer de nouveau votre mot de passe.';

$txt['register_age_confirmation'] = 'J\'ai au moins %d ans';

// Use numeric entities in the below six strings.
$txt['register_subject'] = "Bienvenue sur " . $context['forum_name'];

// For the below three messages, %1$s is the display name, %2$s is the username, %3$s is the password, %4$s is the activation code, and %5$s is the activation link (the last two are only for activation.)
$txt['register_immediate_message'] = 'Vous &#234;tes maintenant inscrit avec un compte sur ' . $context['forum_name'] . ', %1$s !' . "\n\n" . 'Votre identifiant est %2$s et son mot de passe est %3$s.' . "\n\n" . 'Vous pouvez changer votre mot de passe apr&#232;s votre connexion en allant dans votre profil, ou en visitant cette page apr&#232;s votre connexion :' . "\n\n" . $scripturl . '?action=profile' . "\n\n" . $txt[130];
$txt['register_activate_message'] = 'Vous &#234;tes maintenant inscrit avec un compte sur ' . $context['forum_name'] . ', %1$s !' . "\n\n" . 'L\'identifiant de votre compte est %2$s et son mot de passe est %3$s (il peut &#234;tre chang&#233; plus tard.)' . "\n\n" . 'Avant de vous connecter, vous devez d\'abord activer votre compte. Pour se faire, veuillez suivre le lien suivant :' . "\n\n" . '%5$s' . "\n\n" . 'Si vous avez un quelconque probl&#232;me avec le processus d\'activation, veuillez utiliser le code "%4$s".' . "\n\n" . $txt[130];
$txt['register_pending_message'] = 'Votre requ&#234;te d\'inscription sur ' . $context['forum_name'] . ' a &#233;t&#233; re&#231;ue, %1$s.' . "\n\n" . 'L\'identifiant que vous avez inscrit &#233;tait %2$s et le mot de passe &#233;tait %3$s.' . "\n\n" . 'Avant de pouvoir vous connecter et utiliser ce forum, votre requ&#234;e sera &#233;tudi&#233;e et approuv&#233;e.  Lorsque ce sera fait, vous recevrez un autre courriel depuis cette adresse.' . "\n\n" . $txt[130];

// For the below two messages, %1$s is the user's display name, %2$s is their username, %3$s is the activation code, and %4$s is the activation link (the last two are only for activation.)
$txt['resend_activate_message'] = 'Vous &#234;tes maintenant inscrit avec un compte sur ' . $context['forum_name'] . ', %1$s !' . "\n\n" . 'Votre identifiant est "%2$s".' . "\n\n" . 'Avant de vous connecter, vous devrez d\'abord activer votre compte.  Pour se faire, veuillez suivre ce lien :' . "\n\n" . '%4$s' . "\n\n" . 'Si vous avez un quelconque probl&#232;me avec le processus d\'activation, veuillez utiliser le code "%3$s".' . "\n\n" . $txt[130];
$txt['resend_pending_message'] = 'Votre requ&#234;te d\'inscription sur ' . $context['forum_name'] . ' a &#233;t&#233; re&#231;ue, %1$s.' . "\n\n" . 'L\'identifiant que vous avez inscrit &#233;tait %2$s.' . "\n\n" . 'Avant de pouvoir vous connecter et utiliser ce forum, votre requ&#234;te sera examin&#233;e et approuv&#233;e.  Lorsque ce sera fait, vous recevrez un autre courriel depuis cette adresse.' . "\n\n" . $txt[130];

$txt['ban_register_prohibited'] = 'D&eacute;sol&eacute;, vous n\'&ecirc;tes pas autoris&eacute; &agrave; vous inscrire sur ce forum';
$txt['under_age_registration_prohibited'] = 'D&eacute;sol&eacute;, mais les utilisateurs au-dessous de %d ans ne peuvent pas s\'inscrire sur ce forum.';

$txt['activate_account'] = 'Activation de Compte';
$txt['activate_success'] = 'Votre compte a &eacute;t&eacute; activ&eacute; avec succ&egrave;s. Vous pouvez maintenant vous connecter.';
$txt['activate_not_completed1'] = 'Votre adresse courriel doit &ecirc;tre valid&eacute;e avant que vous puissiez vous connecter.';
$txt['activate_not_completed2'] = 'Un autre courriel d\'activation&nbsp;?';
$txt['activate_after_registration'] = 'Merci de vous &ecirc;tre inscrit. Vous recevrez rapidement un courriel contenant un lien pour activer votre compte.  Si vous ne recevez rien apr&egrave;s un certain temps, v&eacute;rifiez votre bo&icirc;te &agrave; pourriels.';
$txt['invalid_userid'] = 'L\'utilisateur n\'existe pas';
$txt['invalid_activation_code'] = 'Code d\'activation invalide';
$txt['invalid_activation_username'] = 'Identifiant ou adresse courriel';
$txt['invalid_activation_new'] = 'Si vous vous &ecirc;tes inscrit avec une adresse courriel incorrecte, entrez-en une nouvelle ainsi que votre mot de passe ici.';
$txt['invalid_activation_new_email'] = 'Nouvelle adresse courriel';
$txt['invalid_activation_password'] = 'Ancien mot de passe';
$txt['invalid_activation_resend'] = 'Envoyez de nouveau le code d\'activation';
$txt['invalid_activation_known'] = 'Si vous connaissez d&eacute;j&agrave; votre code d\'activation, tapez-le ici.';
$txt['invalid_activation_retry'] = 'Code d\'activation';
$txt['invalid_activation_submit'] = 'Activer';

$txt['coppa_not_completed1'] = 'L\'administrateur n\'a toujours re&ccedil;u aucune autorisation parentale pour votre compte.';
$txt['coppa_not_completed2'] = 'Plus de d&eacute;tails&nbsp;?';

$txt['awaiting_delete_account'] = 'Votre compte a &eacute;t&eacute; marqu&eacute; pour une suppression&nbsp;!<br />Si vous voulez restaurer votre compte, veuillez lire la bo&icirc;te &quot;R&eacute;activer mon compte&quot;, et connectez-vous &agrave; nouveau.';
$txt['undelete_account'] = 'R&eacute;activer mon compte';

$txt['change_email_success'] = 'Votre adresse courriel a &eacute;t&eacute; chang&eacute;e, et un nouveau courriel d\'activation vous y a &eacute;t&eacute; envoy&eacute;.';
$txt['resend_email_success'] = 'Un nouveau courriel d\'activation a &eacute;t&eacute; envoy&eacute; avec succ&egrave;s.';
// Use numeric entities in the below three strings.
$txt['change_password'] = 'Nouveaux d&#233;tails de connexion';
$txt['change_password_1'] = 'Vos informations de connexion sur ';
$txt['change_password_2'] = 'ont &#233;t&#233; chang&#233;es et votre mot de passe chang&#233; Voici vos nouveaux d&#233;tails de connexion.';

$txt['maintenance3'] = 'Ce forum est en Mode Maintenance.';

// These two are used as a javascript alert; please use international characters directly, not as entities.
$txt['register_agree'] = 'Merci de lire et accepter les termes de l\'accord avant de soumettre ce formulaire.';
$txt['register_passwords_differ_js'] = 'Les deux mots de passe entrés sont différents !';

$txt['approval_after_registration'] = 'Merci de vous &ecirc;tre inscrit. L\'administrateur doit approuver votre inscription avant que vous puissiez commencer &agrave; utiliser votre compte. Vous allez bient&ocirc;t recevoir un courriel vous informant de la d&eacute;cision de l\'administrateur.';

$txt['admin_settings_desc'] = 'Ici vous pouvez changer une vari&eacute;t&eacute; de param&egrave;tres concernant l\'inscription des nouveaux membres.';

$txt['admin_setting_registration_method'] = 'M&eacute;thode d\'inscription employ&eacute;e pour les nouveaux membres';
$txt['admin_setting_registration_disabled'] = 'Inscription d&eacute;sactiv&eacute;e';
$txt['admin_setting_registration_standard'] = 'Inscription imm&eacute;diate';
$txt['admin_setting_registration_activate'] = 'Activation par le membre';
$txt['admin_setting_registration_approval'] = 'Approbation du membre';
$txt['admin_setting_notify_new_registration'] = 'Aviser les administrateurs de l\'inscription d\'un nouveau membre';
$txt['admin_setting_send_welcomeEmail'] = 'Envoyer un courriel de bienvenue aux nouveaux membres';

$txt['admin_setting_password_strength'] = 'Niveau de s&eacute;curit&eacute; pour les mots de passe';
$txt['admin_setting_password_strength_low'] = 'Bas - 4 caract&egrave;res minimum';
$txt['admin_setting_password_strength_medium'] = 'Moyen - ne peut pas contenir l\'identifiant';
$txt['admin_setting_password_strength_high'] = '&Eacute;lev&eacute; - m&eacute;lange de diff&eacute;rents caract&egrave;res';

// Untranslated!
$txt['admin_setting_image_verification_type'] = 'Complexity of visual verification image';
// Untranslated!
$txt['admin_setting_image_verification_type_desc'] = 'The more complex the image the harder it is for bots to bypass';
// Untranslated!
$txt['admin_setting_image_verification_off'] = 'Disabled';
// Untranslated!
$txt['admin_setting_image_verification_vsimple'] = 'Very Simple - Plain text on image';
// Untranslated!
$txt['admin_setting_image_verification_simple'] = 'Simple - Overlapping coloured letters, no noise';
// Untranslated!
$txt['admin_setting_image_verification_medium'] = 'Medium - Overlapping coloured letters, with noise';
// Untranslated!
$txt['admin_setting_image_verification_high'] = 'High - Angled letters, considerable noise';
// Untranslated!
$txt['admin_setting_image_verification_sample'] = 'Sample';
// Untranslated!
$txt['admin_setting_image_verification_nogd'] = '<b>Note:</b> as this server does not have the GD library installed the different complexity settings will have no effect.';

$txt['admin_setting_coppaAge'] = '&Acirc;ge au-dessous duquel appliquer des restrictions &agrave; l\'inscription';
$txt['admin_setting_coppaAge_desc'] = '(Entrez 0 pour d&eacute;sactiver)';
$txt['admin_setting_coppaType'] = 'Action &agrave; prendre lorsqu\'un utilisateur au-dessous de l\'&agrave;ge minimum tente de s\'inscrire';
$txt['admin_setting_coppaType_reject'] = 'Rejeter son inscription';
$txt['admin_setting_coppaType_approval'] = 'N&eacute;cessiter l\'approbation d\'un parent/tuteur l&eacute;gal';
$txt['admin_setting_coppaPost'] = 'Adresse postale &agrave; laquelle les formulaires d\'approbation doivent &ecirc;tre envoy&eacute;s';
$txt['admin_setting_coppaPost_desc'] = 'S\'applique seulement si un &acirc;ge minimal est param&eacute;tr&eacute;';
$txt['admin_setting_coppaFax'] = 'Num&eacute;ro de fax auquel les formules d\'approbation doivent &ecirc;tre envoy&eacute;es';
$txt['admin_setting_coppaPhone'] = 'Num&eacute;ro &agrave; contacter par les parents ou tuteurs pour des questions sur la restriction sur l\'&agrave;ge minimum';
$txt['admin_setting_coppa_require_contact'] = 'Vous devrez fournir une adresse postale ou un num&eacute;ro de fax si l\'approbation par un parent/tuteur est requise.';

$txt['admin_register'] = 'Inscription d\'un nouveau membre';
$txt['admin_register_desc'] = 'D\'ici vous pouvez inscrire des nouveaux membres sur votre forum, et si vous le d&eacute;sirez, leur envoyer leurs informations de connexion par courriel.';
$txt['admin_register_username'] = 'Nouvel identifiant';
$txt['admin_register_email'] = 'Adresse courriel';
$txt['admin_register_password'] = 'Mot de passe';
$txt['admin_register_username_desc'] = 'Identifiant pour le nouveau membre';
$txt['admin_register_email_desc'] = 'Adresse courriel associ&eacute;e &agrave; ce compte membre';
$txt['admin_register_password_desc'] = 'Nouveau mot de passe du membre';
$txt['admin_register_email_detail'] = 'Envoyer le mot de passe par courriel';
$txt['admin_register_email_detail_desc'] = 'Adresse courriel requise m&ecirc;me si d&eacute;coch&eacute;';
$txt['admin_register_email_activate'] = 'N&eacute;cessite l\'activation du compte par le membre';
$txt['admin_register_group'] = 'Groupe principal';
$txt['admin_register_group_desc'] = 'Groupe de membre principal auquel le nouveau membre appartiendra';
$txt['admin_register_group_none'] = '(pas de groupe principal)';
$txt['admin_register_done'] = 'Le membre %s s\'est inscrit avec succ&egrave;s&nbsp;!';

$txt['admin_browse_register_new'] = 'Inscrire un nouveau membre';

// Use numeric entities in the below three strings.
$txt['admin_notify_subject'] = 'Un nouveau membre a rejoint la communaut&#233;';
$txt['admin_notify_profile'] = '%s vient tout juste de s\'inscrire en tant que nouveau membre sur ce forum. Cliquez le lien suivant pour visualiser son profil.';
$txt['admin_notify_approval'] = 'Avant que ce membre puisse commencer &#224; poster, son compte doit &#234;tre approuv&#233;. Cliquez le lien suivant pour aller &#224; l\'&#233;cran de gestion des approbations.';

$txt['coppa_title'] = 'Forum avec restriction d\'&acirc;ge';
$txt['coppa_after_registration'] = 'Merci de vous &ecirc;tre inscrit sur ' . $context['forum_name'] . '.<br /><br />Parce que vous &ecirc;tes &acirc;g&eacute; de moins de {MINIMUM_AGE} ans, il est l&eacute;galement requis
	que vous obteniez une autorisation de vos parents ou tuteurs l&eacute;gaux avant que vous puissiez utiliser votre compte.  Pour arranger l\'activation de votre compte, veuillez imprimer le formulaire ci-dessous&nbsp;:';
$txt['coppa_form_link_popup'] = 'Charger le formulaire dans une nouvelle fen&ecirc;tre';
$txt['coppa_form_link_download'] = 'T&eacute;l&eacute;charger le formulaire en tant que fichier texte';
$txt['coppa_send_to_one_option'] = 'Ensuite, demandez &agrave; vos parents ou tuteurs de l\'envoyer compl&eacute;t&eacute; par&nbsp;:';
$txt['coppa_send_to_two_options'] = 'Puis arrangez vous pour que votre parent/tuteur envoie le formulaire rempli par:';
$txt['coppa_send_by_post'] = 'Voie postale &agrave; l\'adresse suivante&nbsp;:';
$txt['coppa_send_by_fax'] = 'Fax au num&eacute;ro suivant&nbsp;:';
$txt['coppa_send_by_phone'] = 'Alternativement, demandez-leur de contacter l\'administrateur par t&eacute;l&eacute;phone au num&eacute;ro {PHONE_NUMBER}.';

$txt['coppa_form_title'] = 'Formulaire d\'autorisation d\'inscription au forum ' . $context['forum_name'];
$txt['coppa_form_address'] = 'Adresse';
$txt['coppa_form_date'] = 'Date';
$txt['coppa_form_body'] = 'Moi, {PARENT_NAME},<br /><br />Je donne la permission &agrave; {CHILD_NAME} (nom de l\'enfant) de devenir un membre &agrave; part enti&egrave;re du forum : ' . $context['forum_name'] . ', sous l\'identifiant : {USER_NAME}.<br /><br />Je comprends que certaines informations personnelles entr&eacute;es par {USER_NAME} peuvent &ecirc;tres affich&eacute;es &agrave; d\'autres visiteurs du forum.<br /><br />Sign&eacute;&nbsp;:<br />{PARENT_NAME} (Parent/Tuteur l&eacute;gal).';

$txt['visual_verification_label'] = 'V&eacute;rification visuelle';
$txt['visual_verification_description'] = 'Tapez les caract&egrave;res qui s\'affichent dans l\'image';
$txt['visual_verification_sound'] = 'Ecouter les lettres';
$txt['visual_verification_sound_again'] = 'Recommencer';
$txt['visual_verification_sound_close'] = 'Fermer la fen&ecirc;tre';
$txt['visual_verification_request_new'] = 'Afficher une autre image';
$txt['visual_verification_sound_direct'] = 'Un probl&egrave;me pour &eacute;couter ceci? Essayez avec ce lien direct.';

?>