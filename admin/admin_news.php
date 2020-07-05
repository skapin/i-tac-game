<h2>Les news</h2>
<?php
require('../objets/bdd.php');
$connexion = new comBdd('','','','','',$GLOBALS['db']);


if(isset($_POST['new_news_ok'])){
  if(!request("INSERT INTO `news` (`date`,`titre`,`auteur`,`texte`,visibilite,camp) VALUES('".time()."','".post2bdd($_POST['new_news_titre'])."','".post2bdd($_POST['new_news_auteur'])."','".post2bdd($_POST['new_news_texte'])."','".post2bdd($_POST['new_news_visibilite'])."','".post2bdd($_POST['new_news_camp'])."')"))
    erreur(0,"Impossible d'enregistrer la news.");
  else
    write_news();
}
else if(isset($_POST['mod_news_ok']))
{
  request("UPDATE `news` SET `titre`='".post2bdd($_POST['mod_news_titre'])."',`auteur`='".post2bdd($_POST['mod_news_auteur'])."',`texte`='".post2bdd($_POST['mod_news_texte'])."',`visibilite`='".post2bdd($_POST['mod_news_visibilite'])."',`camp`='".post2bdd($_POST['mod_news_camp'])."' WHERE `ID`='".post2bdd($_POST['mod_news_id'])."' LIMIT 1");
  if(!affected_rows())
    erreur(0,"Impossible de modifier la news.");
  else
	  write_news();
}
else if(isset($_POST['del_news_ok']) &&
	isset($_POST['del_news_id']) &&
	is_numeric($_POST['del_news_id'])){
  request("DELETE FROM `news` WHERE `ID`='".post2bdd($_POST['del_news_id'])."' LIMIT 1");
  write_news();
}
$camps = my_fetch_array('SELECT ID, nom FROM camps');
$news=my_fetch_array("SELECT `ID`,`titre`, texte, auteur, visibilite, camp FROM `news` ORDER BY `date` DESC");
$script='<script type="text/javascript">
function showNews()
{
  if(!document.getElementById)
    return;
  var titre="";
  var texte="";
  var auteur="";
  var camp=0;
  var visibilite=0;
';
for($i=1;$i<=$news[0];$i++)
  $script.='  if(document.getElementById("mod_news_id").value=="'.$news[$i]['ID'].'")
  {
    titre="'.bdd2js($news[$i]['titre']).'";
    texte="'.bdd2js($news[$i]['texte']).'";
    auteur="'.bdd2js($news[$i]['auteur']).'";
    camp="'.bdd2js($news[$i]['camp']).'";
    visibilite="'.bdd2js($news[$i]['visibilite']).'";
  }
';
$script.='  document.getElementById("mod_news_titre").value=titre;
  document.getElementById("mod_news_texte").value=texte;
  document.getElementById("mod_news_auteur").value=auteur;
  document.getElementById("mod_news_camp").value=camp;
  document.getElementById("mod_news_visibilite").value=visibilite;
}
showNews();
</script>
';
$camps[0]++;
$camps[$camps[0]]=array(0,'Aucun');
$types=array(2,array(0,'Animation'),array(1,'Jeu'));
echo'<form method="post" action="anim.php?admin_news">
 <p>
 <h3>Nouvelle news :</h3>
 '.form_select("Type : ","new_news_visibilite",$types,'',0).'<br /> 
 '.form_select("R&eacute;serv&eacute; au camp : ","new_news_camp",$camps,'',0).'<br /> 
 '.form_text("Titre : ","new_news_titre","","").'<br /> 
 '.form_text("Auteur : ","new_news_auteur","","").'<br /> 
 '.form_textarea("Texte : ","new_news_texte","10","50").'<br /> 
 '.form_submit("new_news_ok","Créer").'<br />
 <h3>Editer une news :</h3>
 '.form_select("News : ","mod_news_id",$news,"showNews();").'<br />
 '.form_select("Type : ","mod_news_visibilite",$types,'',0).'<br /> 
 '.form_select("R&eacute;serv&eacute; au camp : ","mod_news_camp",$camps,'',0).'<br /> 
 '.form_text("Titre : ","mod_news_titre","","").'<br /> 
 '.form_text("Auteur : ","mod_news_auteur","","").'<br /> 
 '.form_textarea("Texte : ","mod_news_texte","10","50").'<br /> 
 '.form_submit("mod_news_ok","Modifier").'<br /> 
 <h3>Supprimer une news : </h3>
 '.form_select("","del_news_id",$news,"").'<br />
 '.form_submit("del_news_ok","Supprimer").'<br /> 
 </p>
 </form>
'.$script;

function write_news()
{
  $news=my_fetch_array("SELECT * FROM `news` WHERE `date`>='".(time()-2592000)."' ORDER BY `date`DESC LIMIT 10");
  $camps = my_fetch_array('SELECT ID FROM camps');
  $camps[0]++;
  $camps[$camps[0]]=array('ID'=>0);
  for($i = 1; $i <= $camps[0]; $i++){
    $text[$camps[$i]['ID']]=array('anim'=>'<ul class="newsList">',
				  'jeu'=>'<ul class="newsList">');
  }
  for($i=1;$i<=$news[0];$i++){
    $content = ' <li>
<h3>'.bdd2html($news[$i]['titre']).'</h3>
<p>'.filtrage_ordre($news[$i]['texte']).'
</p>
<p class="footer">'.bdd2html($news[$i]['auteur']).' le '.date("d/m/y",$news[$i]['date']).'</p>
</li>
';
    $cat = 'anim';
    if($news[$i]['visibilite'] == 1){
      $cat = 'jeu';
    }
    if($news[$i]['camp'] == 0){
      for($j = 1; $j <= $camps[0]; $j++){
	$text[$camps[$j]['ID']][$cat].=$content;
      }
    }
    else{
      $text[$news[$i]['camp']][$cat].=$content;
    }
  }
  for($i = 1; $i <= $camps[0]; $i++){
    $text[$camps[$i]['ID']]['anim'].='</ul>';
    fichier_create('../news/anim_'.$camps[$i]['ID'].'.html',
		   $text[$camps[$i]['ID']]['anim'],1);
    $text[$camps[$i]['ID']]['jeu'].='</ul>';
    fichier_create('../news/jeu_'.$camps[$i]['ID'].'.html',
		   $text[$camps[$i]['ID']]['jeu'],1);
  }
}
?>