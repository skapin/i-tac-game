<?php

//----Esce que l'animateur a accepter des commandes ?-------
if(isset($_POST['admin_marche_accepte'],$_POST['admin_marche_ok']) )
{

	
}

//---Supprimer une commande ?------
if(isset($_POST['admin_marche_ok'],$_POST['admin_marche_suppr']))
{
	annuler_commande($_POST['admin_marche_suppr']);
 
}

//----on recupere les commandes-----------------------------
$commandes = my_fetch_array("SELECT marche_commandes.id AS id,
									camps.nom AS camp ,
									marche_commandes.carte AS carte,
									persos.nom AS nom_perso,
									objets.nom AS nom,
									objets.prix AS prix
							FROM marche_commandes 
							JOIN objets ON marche_commandes.id_objet=objets.ID
							JOIN camps ON marche_commandes.camp=camps.ID
							JOIN persos ON marche_commandes.bought_by=persos.ID
							WHERE marche_commandes.etat=0");

echo '<h2>Gestion du March&eacute;</h2>';


//-----si aucune commande, petit message trop sympa---------
if($commandes[0] == 0 )
{
	echo '<p>Aucune commande</p>';
}   
//-------plein de commandes....----------------------------
else 
{
	echo '<h3>Demandes d\'achats</h3>';
	echo '</form>
	<form method="post" action="anim.php?admin_marche">
		<table> 
			<tr>
				<th>Numero de comande</th>
				<th>Nom de l\'objet</th> 
				<th>Prix</th>
				<th>Camp</th>
				<th>Pass&eacute;e Par</th>
				<th>Refuser ?</th>
			</tr>';  
	for($i=1;$i<=$commandes[0];$i++)
	{
		echo '   
			<tr>
				<td>',$commandes[$i]['id'],'</td>
				<td>',$commandes[$i]['nom'],'</td>
				<td>',$commandes[$i]['prix'],'</td>
				<td>',$commandes[$i]['camp'],'</td>
				<td><a href="fiche.php?id=',$commandes[$i]['bought_by'],'" target="_fiche">',$commandes[$i]['nom_perso'],'</a></td>
				<td>',form_radio('','admin_marche_suppr',$commandes[$i]['id']),'</td>
				<td>',form_radio('','admin_marche_accepte',$commandes[$i]['id']),'</td>
			</tr>'; 
	}  
	echo ' 
		</table> 
		 <br />'
		,form_submit('admin_marche_ok','Valider'),
	'</form>';
}
?>
