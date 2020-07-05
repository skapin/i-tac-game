<?php
ob_start('ob_gzhandler');
require_once('../sources/globals.php');
require_once('../sources/includes.php');
if(isset($_GET['lite']))
  com_header_lite();
else
  com_header();
require_once('../inits/camps.php');
if(isset($_GET['id']))
{
  if(is_numeric($_GET['id']))
    {
      $personnage=my_fetch_array('SELECT persos.nom,
                                persos.ID,
                                persos.armee,
                                persos.grade_reel,
                                grades.ID AS grade_ID,
                                grades.nom AS grade_nom,
                                grades.niveau,
                                compagnies.initiales
                         FROM persos
                            INNER JOIN compagnies
                               ON compagnies.ID=persos.compagnie
                            LEFT OUTER JOIN grades
                               ON grades.ID=persos.grade
                         WHERE persos.ID='.$_GET['id']);
    }
  else
    {
      $personnage=my_fetch_array('SELECT persos.ID,
                                persos.nom,
                                persos.armee,
                                persos.grade_reel,
                                grades.ID AS grade_ID,
                                grades.nom AS grade_nom,
                                grades.niveau,
                                compagnies.initiales
                         FROM persos
                            INNER JOIN compagnies
                               ON compagnies.ID=persos.compagnie
                            LEFT OUTER JOIN grades
                               ON grades.ID=persos.grade
                         WHERE persos.nom LIKE \'%'.post2bdd($_GET['id']).'%\'');
    }
  if($personnage[0]==1)
    {
      // recuperation de jolies infos
      $fiche=getFiche($personnage[1]['ID']);
      //print_r($personnage[1]);

      echo'<div id="fiche" class="liste">
<h2>',bdd2html($personnage[1]['nom']),'</h2>
<ul>
<li>Matricule : ',camp_initiale($personnage[1]['armee']),'-',bdd2html($personnage[1]['initiales']),'-',$personnage[1]['ID'],'</li>
<li>Grade : ',bdd2html($personnage[1]['grade_ID']?(grade_spec_autre($personnage[1]['armee'],$personnage[1]['grade_ID'],$personnage[1]['grade_nom']).' ('.numero_camp_grade($personnage[1]['armee'],$personnage[1]['grade_reel']).')'):numero_camp_grade($personnage[1]['armee'],$personnage[1]['grade_reel'])),'</li>
</ul>
<div class="apparence">';
      if(!empty($fiche['rp_avatar'])){
	echo' <p>
  <img src="'.bdd2value($fiche['rp_avatar']).'" alt="Avatar" />
 </p>
';
      }
      echo' <h3>Description</h3>
 <div>'.filtrage_ordre(bdd2text($fiche['rp_desc'])).'
 </div>
 <h3>Biographie</h3>
 <div>'.filtrage_ordre(bdd2text($fiche['rp_bio'])).'
 </div>
</div>
<div class="textes">
 <h3>Ses textes</h3>
 <ul>
';
      if(!empty($fiche['textes'])){
	foreach($fiche['textes'] AS $texte){
	  echo'  <li>
   <a href="rp.php?act=lire&amp;id='.$texte['ID'].'">'.bdd2html($texte['titre']).'</a></li>
';
  }
}
echo' </ul>
 <h3>Ses bookmarks</h3>
 <ul>
';
 if(!empty($fiche['bookmarks'])){
  foreach($fiche['bookmarks'] AS $texte){
    echo' <li>
   <a href="rp.php?act=lire&amp;id='.$texte['ID'].'">'.bdd2html($texte['titre']).'</a> de <a href="fiche.php?id='.$texte['auteur'].'">'.bdd2html($texte['nom']).'</a>
 </li>
';
  }
}
echo' </ul>
</div>
<h2 class="up">Menu suppl&eacute;mentaire</h2>
<ul class="menu">
 <li><a href="evenements.php?id=',$personnage[1]['ID'],(isset($_GET['lite'])?'&amp;lite=1':''),'">&Eacute;v&egrave;nements</a></li>
 <li><a href="frags.php?id=',$personnage[1]['ID'],(isset($_GET['lite'])?'&amp;lite=1':''),'">Morts</a></li>
 <li><a href="casier.php?id=',$personnage[1]['ID'],(isset($_GET['lite'])?'&amp;lite=1':''),'">Casier judiciaire</a></li>
</ul>
<form method="get" action="fiche.php">
<p>
Voir la fiche du matricule : <input type="text" size="4" name="id" />
',(isset($_GET['lite'])?'<input type="hidden" name="lite" value="1" />':''),'
<input type="submit" value="Voir" />
</p>
</form>
</div>
';
    }
  else if($personnage[0]>1)
    {
      echo'<div id="fiche">
<p>Plusieurs persos corresponde à votre recherche.</p> 
<form method="get" action="fiche.php">
<p>
',(isset($_GET['lite'])?'<input type="hidden" name="lite" value="1" />':''),'
',form_select('Voir la fiche de : ','id',$personnage,''),'
<input type="submit" value="Voir" />
</p>
</form>
</div>
';
    }
  else
    {
      add_message(3,'Perso inexistant.');
      echo'<div id="fiche">
<form method="get" action="fiche.php">
<p>
',(isset($_GET['lite'])?'<input type="hidden" name="lite" value="1" />':''),'
Voir la fiche du matricule : <input type="text" size="4" name="id" />
<input type="submit" value="Voir" />
</p>
</form>
</div>
';
    }
}
else
  echo'<div id="fiche">
<form method="get" action="fiche.php">
<p>
',(isset($_GET['lite'])?'<input type="hidden" name="lite" value="1" />':''),'
Voir la fiche du matricule : <input type="text" size="4" name="id" />
<input type="submit" value="" />
</p>
</form>
<form method="post" action="fiche.php">
<p>
',(isset($_GET['lite'])?'<input type="hidden" name="lite" value="1" />':''),'
Voir la fiche des persos ayant dans leur nom : <input type="text" name="name" />
<input type="submit" value="Voir" />
</p>
</form>
</div>
';
if(isset($_GET['lite']))
  com_footer_lite();
else
  com_footer();
?>
