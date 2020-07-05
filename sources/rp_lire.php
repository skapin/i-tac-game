<?php
$camp=empty($perso['armee'])?-1:$perso['armee'];
$compa=empty($perso['compagnie'])?-1:$perso['compagnie'];
$perso=empty($_SESSION['com_perso'])?-1:$_SESSION['com_perso'];
$afficheListe=true;

if(!empty($_GET['act']) &&
   $_GET['act'] == 'lire' &&
   !empty($_GET['id']) &&
   is_numeric($_GET['id'])){
  $texte=getTextInfos($_GET['id']);
  if($texte ==-1){
    add_message(1,'Texte inconnu.');
  }
  else{
    echo'<h2>'.bdd2html($texte['titre']).'</h2>
 '.filtrage_ordre(bdd2text($texte['texte']));
    if(!empty($_SESSION['com_perso'])){
      echo' <p><a href="rp.php?act=addBM&amp;id='.$_GET['id'].'">Favori</a></p>
';
    }
    echo'<p><a href="rp.php'.(!empty($texte['spec_cat'])?'?act=lire&amp;uid='.$texte['spec_cat']:'').'">Retour</a></p>
';
    $afficheListe=false;
  }
}

else if(!empty($_GET['act']) &&
   $_GET['act'] == 'lire' &&
   !empty($_GET['uid']) &&
   is_numeric($_GET['uid'])){
  $textes=my_fetch_array('SELECT rp_textes.ID,
rp_textes.titre,
rp_textes.spec_camp
FROM rp_textes
LEFT JOIN rp_perms
  ON rp_textes.ID=rp_perms.texteID
WHERE rp_textes.spec_cat='.$_GET['uid'].' 
AND rp_textes.statut=1
AND (rp_textes.type & 1
OR rp_perms.camp='.$camp.')
ORDER BY spec_camp, lastmod DESC');
  $univers=array(1=>array('titre'=>'Ambiances',
			  'texte'=>'<p>Vous retrouvez ici tous les textes mettant en sc&egrave;ne des &eacute;v&egrave;nements du background d\'i-tac afin de mettre en lumi&egrave;re certains aspects de l\'histoire et des civilisations des factions en pr&eacute;sence. Ils constituent la r&eacute;f&eacute;rence du Role-Play d\'i-tac.</p>
<p>
Cette page est amen&eacute;e &agrave; s\'enrichir au fur et &agrave; mesure de l\'&eacute;volution du jeu avec les meilleurs textes des joueurs.</p>'),
		 2=>array('titre'=>'Concepts',
			  'texte'=>'<p>Vous retrouvez ici des textes expliquant divers aspects d\'i-tac, de son gameplay aux notions concernant le fonctionnement ou l\'organisation des factions de l\'univers.</p>'));
  $camps=array(1=>'Global',
	       2=>'Enkis',
	       3=>'Seln\'as',
	       4=>'Lunmors');
  if(!empty($univers[$_GET['uid']])){
    echo'<h2>'.$univers[$_GET['uid']]['titre'].'</h2>
'.$univers[$_GET['uid']]['texte'].'
';
    if($textes[0]>0){
      echo'<ul>
';
      $camp=0;
      for($i=1;$i<=$textes[0];$i++){
	if($camp!=$textes[$i]['spec_camp']){
	  $camp=$textes[$i]['spec_camp'];
	  echo' <li><strong>'.$camps[$camp].'</strong></li>
';
	}
	echo' <li><a href="rp.php?act=lire&amp;id='.$textes[$i]['ID'].'">'.bdd2html($textes[$i]['titre']).'</a></li>
';
      }
      echo'</ul>
';
    }
    else{
      echo'<p>Aucun texte disponible</p>';
    }
  }
  $afficheListe=false;
}
if($afficheListe){
  lastTextList();
}
?>