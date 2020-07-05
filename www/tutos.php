<?php
ob_start('ob_gzhandler');
// Inclusion du fichier contenant les fonctions et variables globales.
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header();
echo'<div class="liste" id="tutos">
 <h2>Tutoriaux</h2>
 <p>Vous retrouvez ici l\'ensemble des tutoriaux d&eacute;crivant l\'utilisation d\'i-tac.</p>
<p>Cette page est amen&eacute;e &agrave; &ecirc;tre compl&eacute;t&eacute;e au fur et &agrave; mesure des &eacute;volutions du jeu.</p>
<h3>Inscription</h3>
<p>Premi&egrave;re vid&eacute;o pr&eacute;sentant les &eacute;tapes &agrave; suivre lors de l\'inscription.</p>
<div>
<object width="520" height="351">
 <param name="movie" value="http://www.dailymotion.com/swf/5uVagANLyjuAojWr3"></param>
 <param name="allowfullscreen" value="true"></param>
 <embed src="http://www.dailymotion.com/swf/5uVagANLyjuAojWr3" type="application/x-shockwave-flash" width="520" height="351" allowfullscreen="true"></embed>
</object>
</div>
<h3>Premi&egrave;re connexion</h3>
<p>Une fois inscrit, nous pouvons aller voir les diff&eacute;rents menus permettant de pr&eacute;parer nos personnages.
Vous avez donc un aper&ccedil;u des comp&eacute;tences, des implants, de l\'inventaire et du choix de mission.</p>
<div>
<object width="520" height="351">
 <param name="movie" value="http://www.dailymotion.com/swf/1gdcHy09mTwuijWrh"></param>
 <param name="allowfullscreen" value="true"></param>
 <embed src="http://www.dailymotion.com/swf/1gdcHy09mTwuijWrh" type="application/x-shockwave-flash" width="520" height="351" allowfullscreen="true"></embed>
</object>
</div>
<h3>Jouer</h3>
<p>Une fois entr&eacute; dans une mission, les affaires s&eacute;rieuses commencent. Bougez, identifiez les cibles, tirez
sur les ennemis de votre faction.</p>
<div>
<object width="520" height="351">
 <param name="movie" value="http://www.dailymotion.com/swf/1D0r4Y2XSsu11jWu5"></param>
 <param name="allowfullscreen" value="true"></param>
 <embed src="http://www.dailymotion.com/swf/1D0r4Y2XSsu11jWu5" type="application/x-shockwave-flash" width="520" height="351" allowfullscreen="true"></embed>
</object>
</div>
';
com_footer();
?>
