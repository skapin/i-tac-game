<?php
if(!empty($_REQUEST['matricule']) &&
   is_numeric($_REQUEST['matricule']) &&
   !empty($_REQUEST['pwd'])){
  $pass=$db->fetch('SELECT api_pass AS pwd FROM persos WHERE ID='.$_REQUEST['matricule']);
  if(!empty($pass) &&
     (!empty($_REQUEST['enc']) &&
      bdd2text($pass[0]['pwd']) == post2text($_REQUEST['pwd']) ||
      empty($_REQUEST['enc']) &&
      bdd2text($pass[0]['pwd']) == sha1(post2text($_REQUEST['pwd'])))){
    echo'
  <isPwdOk value="true" />';
  }
  else{
    echo'
  <isPwdOk value="false" />';
  }
}
else{
  API_erreur('nomat');
}
?>