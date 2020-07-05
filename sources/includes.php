<?php
// Fichiers à inclure :
session_save_path('../session');
include('../sources/erreurs.php');   // Gestion d'erreurs.
include('../sources/bdd.php');       // Pour la gestion de bdd.
include('../sources/messages.php');   // Affichage des messages d'infos.
include('../sources/fonctions.php'); // Fonctions courament utilisées.
include('../sources/forum2.php');  // Interface forum.
// Si le jeu est fermé on stoppe tout.
if(file_exists('ferme.lock')){
  session_start();
  start_message();
  add_message(0,'<p>Le site est actuellement ferm&eacute;.</p>');
  com_html();
  die();
}
require_once('../inits/camps.php');
?>
