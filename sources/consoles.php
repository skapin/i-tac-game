<?php
echo'  <iframe name="framelink" src="compagnie.php" id="framelink">
	</iframe>
	<ul>
	';
lien('bt_carte.gif','Carte','carte.php','framelink');
lien('bt_inventaire.gif','Inventaire','inventaire.php','framelink');
lien('bt_compa.gif','Groupe','compagnie.php','framelink');
lien('bt_evenement.gif','Mes &eacute;v&eacute;nements','evenements.php?id='.$_SESSION['com_perso'].'&amp;lite=1','framelink');
if($perso['console_gene'])
	lien('bt_general.gif','Camp','gene.php','framelink');
if($perso['console_anims'])
	lien('bt_anims.gif','Animation','anim.php','framelink');
echo'  </ul>
	';
function lien($image,$alt,$url,$cible)
{
	echo'   <li><a href="',$url,'"',($cible?' target="'.$cible.'"':''),'>',$alt,'</a></li>
		';
}
?>
