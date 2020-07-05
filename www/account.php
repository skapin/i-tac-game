<?php
ob_start('ob_gzhandler');
// Inclusion du fichier contenant les fonctions et variables globales.
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header();
if(!isset($_SESSION['com_ID'])){
  echo'  <div class="account">
   <h2>Connexion</h2>
   <form method="post" action="index.php">
   <p>
    <label for="login">Login :</label>
    <input type="text" id="login" name="login" title="login" />
    <label for="pass">Mot de passe :</label>
    <input type="password" id="pass" name="pass" title="mot de passe" />
    <input type="submit" id="loginOk" value="&#8629;" />
   </p>
   </form>
   <h2>R&eacute;cup&eacute;ration de mot de passe</h2>
   <form method="post" action="index.php">
   <p>
    <label for="mail">Votre e-mail :</label>
    <input type="text" id="mail" name="mail" />
    <input type="submit" value="&#8629;" />
   </p>
   </form>
  </div>
';
}
else{
  include('../sources/profil.php');
}
com_footer();
?>