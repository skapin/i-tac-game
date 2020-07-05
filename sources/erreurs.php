<?php
// Nous allons faire notre propre gestion
error_reporting(0);
$old_error_handler = set_error_handler("pop_erreur");
function pop_erreur($errno, $errmsg, $filename, $linenum, $vars)
{
  error_log('In '.$filename.' at line '.$linenum.':
error '.$errno.', '.$errmsg.'
 ',3,'../logs/erreurs.log');
  /*echo('In '.$filename.' at line '.$linenum.':<br />
   error '.$errno.',<br />
'.$errmsg.'
 <br />');*/
}

?>