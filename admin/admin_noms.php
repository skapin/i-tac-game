<?php
  //*************************************************************
  // Changement de nom.
  //*************************************************************
if(isset($_POST['new_nom_ok'],$_POST['new_nom'],$_POST['new_nom_id'])&&$_POST['new_nom']&&is_numeric($_POST['new_nom_id']))
  {
    $erreur=0;
    if(is_numeric($_POST['new_nom']))
      {
	erreur(0,"Vous ne pouvez utiliser un nombre comme pseudo.");
	$erreur=1;
      }
    else if(exist_in_db("SELECT ID FROM persos WHERE nom='".post2bdd($_POST['new_nom'])."' LIMIT 1"))
      {
	erreur(0,"Nom déjà utilisé.");
	$erreur=1;
      }
    if(!$erreur)
      {
	$compte=my_fetch_array("SELECT compte FROM persos WHERE ID='$_POST[new_nom_id]'");
	if($compte[0])
	  {
	    request("UPDATE persos SET nom='".post2bdd($_POST['new_nom'])."' WHERE ID='$_POST[new_nom_id]'");
	    if(affected_rows())
	    {
	      console_log('anim_noms',"Modification du nom pour le perso : ".post2html($_POST['new_nom'])." avec l'ID ".$POST['new_nom_id'],'',0,0);
	      update_droits_forum($_POST['new_nom_id']);
	    }
	  }
      }
  }

echo'<form method="post" action"anim.php?admin_noms">
<h2>Changer le nom d\'un perso</h2>
<p>'.form_text("Perso : ","new_nom_id",5,"").'<br />
'.form_text("Nouveau nom : ","new_nom","","").'<br />
'.form_submit("new_nom_ok","Ok").'
</p>
</form>
';
?>