<?php
  /***********************************************************************
Elements des formulaires :
l : Login.
pw1 : mot de passe.
pw2 : confirmation.
e : email.
p : pseudo du premier perso.
c : camp.
v : Bouton submit.
  */
ob_start('ob_gzhandler');
// Inclusion du fichier contenant les fonctions et variables globales.
require_once('../sources/globals.php');
require_once('../sources/includes.php');
com_header();

if(isset($_POST['v'])){
  // Si le formulaire a ete envoye, on vérifie les champs.
  $erreur=0;
  if(empty($_POST['charte1'])){
    $erreur=1;
    add_message(3,'Vous devez lire et accepter la charte des joueurs');
  }
  if(empty($_POST['charte4'])){
    $erreur=1;
    add_message(3,'Vous devez prendre connaissance des informations enregistr&eacute;es par le site');
  }
  if(!isset($_POST['l'])||(!$_POST['l'])) {
    $erreur=1;
    add_message(3,'Il faut choisir un login.');
  }
  if(is_numeric($_POST['l'])){
    $erreur=1;
    add_message(3,'Vous ne pouvez pas choisir un nombre pour login');
  }
  if(!isset($_POST['pw1'])||(!$_POST['pw1'])){
    $erreur=1;
    add_message(3,'Il faut choisir un mot de passe.');
  }
  else if(!isset($_POST['pw2'])||(!$_POST['pw2'])){
    $erreur=1;
    add_message(3,'Veuillez confirmer votre mot de passe.');
  }
  else if($_POST['pw2']!=$_POST['pw1']){
    $erreur=1;
    add_message(3,'Mot de passe et confirmation diff&eacute;rents.');
  }
  if(!isset($_POST['p1'])||(!$_POST['p1'])){
    $erreur=1;
    add_message(3,'Il faut choisir un pseudo pour le premier perso.');
  }
  if(!isset($_POST['p2'])||(!$_POST['p2'])){
    $erreur=1;
    add_message(3,'Il faut choisir un pseudo pour le second perso.');
  }
  if(!isset($_POST['c'])){
    $erreur=1;
    add_message(3,'Il faut choisir un camp.');
  }
  if(!isset($_POST['e'])||(!ereg('.+@.+\..+',$_POST['e']))){
    $erreur=1;
    add_message(3,'adresse mail manquante ou erron&eacute;e.');
  }
  if(is_numeric($_POST['p1']) ||
     is_numeric($_POST['p2'])){
      $erreur=1;
      add_message(3,'Vous ne pouvez pas choisir un nombre comme pseudo');
  }
  if($_POST['p1']==$_POST['l'] ||
     $_POST['p2']==$_POST['l'] ||
     $_POST['p1']==$_POST['p2']){
      $erreur=1;
      add_message(3,'Il faut que votre login et les noms de vos persos soient diff&eacute;rents.');
  }
  $code=0;
  if(file_exists('register.lock')){
    if(empty($_POST['code']) || !is_numeric($_POST['code'])){
      $erreur=1;
      add_message(3,'Les inscriptions ne sont pas encore ouvertes');
    }
    else{
      $plop=my_fetch_array('SELECT email FROM preinscription WHERE code='.$_POST['code']);
      if(empty($plop[0])){
	$erreur=1;
	add_message(3,'Code non valide');
      }
      else if(bdd2text($plop[1]['email']) != post2text($_POST['e'])){
	$erreur=1;
	add_message(3,'Couple code / e-mail non valide');
      }
      else{
	$code=$_POST['code'];
      }
    }
  }
  if(!$erreur){
    // Pas d'erreur dans les donnees du formulaire
    //  il va falloir verifier que le login, le pseudo du premier perso
    //  et le mail ne sont pas deja utilises;
    //  puis on pourra enregistrer le compte.
      if(exist_in_db('SELECT `ID`
                      FROM `compte`
                      WHERE `login` LIKE "'.trim(post2bdd($_POST['l'])).'"')||
	 exist_in_db('SELECT `ID`
                      FROM `persos`
                      WHERE `nom` LIKE "'.trim(post2bdd($_POST['l'])).'"')){
	  add_message(3,'Login d&eacute;j&agrave; utilis&eacute;.');
	  $erreur=1;
      }
      if(exist_in_db('SELECT `ID`
                      FROM `compte`
                      WHERE `mail` LIKE "'.trim(post2bdd($_POST['e'])).'"')){
	add_message(3,'E-mail d&eacute;j&agrave; utilis&eacute;.');
	$erreur=1;
      }
      if(exist_in_db('SELECT `ID`
                      FROM `persos`
                      WHERE `nom` LIKE "'.trim(post2bdd($_POST['p1'])).'"')||
	 exist_in_db('SELECT `ID`
                      FROM `compte`
                      WHERE `login` LIKE "'.trim(post2bdd($_POST['p1'])).'"')){
	add_message(3,'Pseudo du premier perso d&eacute;j&agrave; utilis&eacute;.');
	$erreur=1;
      }
      if(exist_in_db('SELECT `ID`
                      FROM `persos`
                      WHERE `nom` LIKE "'.trim(post2bdd($_POST['p2'])).'"')||
	 exist_in_db('SELECT `ID`
                      FROM `compte`
                      WHERE `login` LIKE "'.trim(post2bdd($_POST['p2'])).'"')){
	add_message(3,'Pseudo du second perso d&eacute;j&agrave; utilis&eacute;.');
	$erreur=1;
      }
      if(!$erreur){
	// Tout est bon, on peut creer le compte.
	$pass=sha1(strtolower(trim(post2text($_POST['l'])).trim(post2text($_POST['pw1']))));
	if($_POST['c']==0){
	  $camps=my_fetch_array('SELECT COUNT(*) as nbr, armee
FROM persos
INNER JOIN camps 
ON camps.ID=persos.armee
WHERE camps.ouvert=1
GROUP BY armee
ORDER BY nbr ASC');
	  $total=0;
	  for($i=1;$i<=$camps[0];$i++){
	    $total+=$camps[$i]['nbr'];
	  }
	  $camp=0;
	  $i=1;
	  while(!$camp && $i<=$camps[0]){
	    $chances=floor(100-$camps[$i]['nbr']*100/$total);
	    $plop=mt_rand(0,100);
	    if($plop<=$chances){
	      $camp=$camps[$i]['armee'];
	    }
	    $i++;
	  }
	  if(!$camp){
	      $camp=$camps[1]['armee'];
	  }
	  /*	  $camp=my_fetch_array('SELECT ID
                                    FROM `camps`
                                    WHERE `ouvert`=\'1\'
                                    ORDER BY RAND()
                                    LIMIT 1');
				    $camp=$camp[1]['ID'];*/
	}
	else{
	  $camp=$_POST['c'];
	}
	// Enregistrement du compte.
	$date=time();
	request('INSERT INTO `compte`
(login,pass,mail,last_login,skin,register,confirmation,camp)
VALUES("'.trim(post2bdd($_POST['l'])).'",
"'.$pass.'",
"'.trim(post2bdd($_POST['e'])).'",
'.$date.',
1,
 NOW(),
 '.rand(1,100000).',
'.$camp.')');
	$id=last_id();
	if(!$id){
	  $erreur=2;
	}
	else{
	  // Creation des deux persos.
	  $compa=(!empty($_POST['c1'])&&is_numeric($_POST['c1'])?$_POST['c1']:1);
	  $idp1=createPerso(post2text($_POST['p1']),$_POST['g1'],$camp,$id,$compa);
	  if(!is_numeric($idp1)){
	    add_message(3,$idp1);
	    $erreur=3;
	  }
	  else{
	    $compa=(!empty($_POST['c2'])&&is_numeric($_POST['c2'])?$_POST['c2']:1);
	    $idp2=createPerso(post2text($_POST['p2']),$_POST['g2'],$camp,$id,$compa);
	    if(!is_numeric($idp2)){
	      add_message(3,$idp2);
	      $erreur=3;
	      // Comme la creation du second perso a chiee, on supprime le 
	      // premier de la bdd.Une fois sur du mysql5, on pourra voir de
	      // jouer avec des transactions.
	      delPerso($idp1);
	    }
	    else if($code>0){
	      // On vient d'utiliser un code de preinscription
	      request('DELETE FROM preinscription WHERE code='.$code);
	    }
	  }
	  if($erreur>0){
	    // Il y a eu erreur lors de la creation des persos. Suppression du
	    // compte.
	    request('DELETE FROM compte WHERE ID='.$id);
	  }
	  else{
	    // Creation du compte forum.
	    forumNewAccount(trim(post2bdd($_POST['l'])),$pass,$id,0,$compa,$camp);
	    add_message(1,'Votre inscription a bien &eacute;t&eacute; prise en compte. Vous pouvez d&eacute;sormais vous connecter pour jouer. Bienvenue dans l\'univers d\'i-tac !');
	  }
	}
      }
  }
}
echo '   <div id="inscription">
    <h2>Inscription</h2>
<p>Le login et le mot de passe vous permettrons de vous identifier sur le site. L\'adresse e-mail est facultative mais
permet de vous envoyer un nouveau mot de passe si vous veniez &agrave; l\'oublier.</p>
<p>Nous vous rappelons que vous n\'avez droit qu\'&agrave; un compte par joueur.</p>
<form action="inscription.php" method="post">
 <div class="compte">
  <h3>Compte</h3>
  <label for="l">Login : <input type="text" name="l" id="l" size="21" maxlength="250" class="inp_text" /></label>
  <label for="pw1">Mot de passe : <input type="password" name="pw1" id="pw1" size="21" class="inp_text" /></label>
  <label for="pw2">V&eacute;rification : <input type="password" name="pw2" id="pw2" size="21" class="inp_text" /></label>
  <label for="e">E-mail : <input type="text" name="e" id="e" size="21" class="inp_text" /></label>
  <label for="c">Camp : <select name="c" id="c">
';
$lescamps=my_fetch_array('SELECT camps.ID,
camps.nom,
compagnies.ID AS ID_compa,
compagnies.nom AS nom_compa
FROM camps
LEFT JOIN starting_compa ON
starting_compa.camp = camps.ID
LEFT JOIN compagnies ON
starting_compa.compa=compagnies.ID
WHERE camps.ouvert=1
ORDER BY camps.ID ASC');

$script='';
$camp=0;
$changed=false;
for($i=1;$i<=$lescamps[0];$i++){
  if($lescamps[$i]['ID'] != $camp){
    echo'   <option value="'.$lescamps[$i]['ID'].'">'.bdd2html($lescamps[$i]['nom']).'</option>
';
    $camp=$lescamps[$i]['ID'];
    $changed=true;
  }
  if(!empty($lescamps[$i]['ID_compa'])){
    if($changed){
      $j=0;
      $script.='Compas['.$camp.'] = new Array();
';
    }
    $script.='Compas['.$camp.']['.$j.']={"id":'.$lescamps[$i]['ID_compa'].',"nom":"'.bdd2js($lescamps[$i]['nom_compa']).'"};
';
    $j++;
  }
  $changed=false;
}

echo'<option selected="selected" value="0">Al&eacute;atoire</option>
</select></label>
 </div>
 <div class="persos" id="col2">
  <h3>Premier perso</h3>
  <label for="p1">Nom : <input type="text" name="p1" id="p1" size="21" maxlength="250" /></label>
  <label for="g1" id="lg1">Sexe : 
   <select name="g1" id="g1">
    <option value="0">Homme</option>
    <option value="1">Femme</option>
   </select>
  </label>
  <h3>Second perso</h3>
  <label for="p2">Nom : <input type="text" name="p2" id="p2" size="21" maxlength="250" /></label>
  <label for="g2" id="lg2">Sexe : 
   <select name="g2" id="g2">Sexe :
    <option value="0">Homme</option>
    <option value="1">Femme</option>
   </select>
  </label>
  <p><input name="v" type="submit" value="Engagez nous !" /></p>
 </div>
 <div class="clearer"></div>
 <div class="accept">
  <p><label for="charte1"><input type="checkbox" name="charte1" id="charte1" />J\'ai lu et j\'accepte la <a href="chartes.php?id=1">charte des joueurs</a></label></p>
  <p><label for="charte4"><input type="checkbox" name="charte4" id="charte4" />J\'ai pris connaissance des <a href="chartes.php?id=4">informations personnelles</a> enregistr&eacute;es par i-tac</label></p>
 </div>
';
if(file_exists('register.lock')){
  echo file_get_contents('register.lock');
}
echo'</form>
</div>
';
if($camp>0){
  echo'<script type="text/javascript">
Compas = new Object();
'.$script.'</script>
';
}
com_footer();
?>
