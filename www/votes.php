<?php
ob_start('ob_gzhandler');
// Inclusion du fichier contenant les fonctions et variables globales.
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header();
echo'<div class="liste">
 <h2>Voter pour i-tac</h2>
 <p>Si vous appr&eacute;ciez i-tac, nous vous encourageons &agrave; voter pour nous sur les diff&eacute;rents site de cette liste.</p>
 <ul class="vote">
  <li>
   <a href="http://www.tourdejeu.net/annu/votejeu.php?id=6417">
    <img src="http://www.tourdejeu.net/images/boutonanim.gif" width="90" height="30" />
   </a>
  </li>
  <li>
<!-- Gamers\'room  -->
<p align=Center><a href="http://www.gamersroom.com/vote.php?num=1024" title="Gamers\'room - Annuaire des jeux gratuit par internet (pbem)"><img src="http://www.gamersroom.com/pub/bouton5.gif" alt="Gamers\'room" border="0" align="Middle"><br><FONT FACE="Arial, Helvetica" SIZE="1">Votez pour ce site !</FONT></a></p>
<!-- fin Gamers\'room -->
  </li>
 </ul>
</div>
';
com_footer();
?>
