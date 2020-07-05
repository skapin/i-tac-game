<?php 
//----skapin - 08-04-2010



 /*
$enum_etat_commande=array(0=>'En attente', 
						1=>'Livr&eacute;',
						2=>'Refus&eacute;');
*/
//on recupere les objets achetables

$objets=my_fetch_array('SELECT ID, nom, description, prix FROM objets WHERE achetable=1 ');

//---Y a t'il eu une commande ? ----------
if(isset($_POST['marche_objet_achete'],$_POST['marche_valide_ok'])) {
	//fonds suffisants ?
	$fonds=my_fetch_array('SELECT fonds FROM camps WHERE ID='.$perso['armee'].'');
	//on recupere le prix
	$cout=my_fetch_array('SELECT prix FROM objets WHERE ID='.$_POST['marche_objet_achete'].'');
	
	if($fonds[1]['fonds'] >=$cout[1]['prix']) {
		$retour=request("INSERT INTO marche_commandes (`camp`,`carte`,`id_objet`,`etat`,`bought_by`)
				VALUES (".$perso['armee'].",".$_SESSION['com_terrain']['ID'].",".$_POST['marche_objet_achete'].",0,".$_SESSION['com_perso']." ) ",'insert');
		$retour_up=request("UPDATE camps SET fonds=".($fonds[1]['fonds']-$cout[1]['prix'])." WHERE ID=".$perso['armee']."");
		if($retour && $retour_up)
		{
			echo '<p>Commande pass&eacute;e</p>';
		}
		else {
			echo '<p>Echec commande</p>';
		}
	}
 
}
//---A t'on supprimer une commande ?------
if(isset($_POST['marche_0_ok'],$_POST['marche_objet_suppr']))
{
	annuler_commande($_POST['marche_objet_suppr']);
 
}

//on recupere les commandes passe par notre camps  
	$commandes=my_fetch_array('SELECT marche_commandes.id,
										marche_commandes.id_objet,
										marche_commandes.etat,
										marche_commandes.bought_by,
										marche_commandes.timestamp,
										objets.nom,
										objets.prix,
										persos.nom AS nom_perso
										FROM marche_commandes 
										JOIN objets ON marche_commandes.id_objet=objets.ID
										JOIN persos ON marche_commandes.bought_by=persos.ID
										WHERE camp='.$perso['armee'].' AND carte='.$_SESSION['com_terrain']['ID'].' ');
//Puis on les tries
$nbr_livrees=0;
$nbr_attentes=0;
$commandes_livrees[]=0;
$commandes_attentes[]=0;
for($i=1;$i<=$commandes[0];$i++) {
	if($commandes[$i]['etat'] == 1) {
		$commandes_livrees[]=$commandes[$i];
		$nbr_livrees++;
	}
	else if ($commandes[$i]['etat'] == 0 ) {
		$commandes_attentes[]=$commandes[$i];
		$nbr_attentes++;
	}
}
$commandes_livrees[0]=$nbr_livrees;
$commandes_attentes[0]=$nbr_attentes;

//--On affiche la console-----------------             
$fonds = my_fetch_array('SELECT fonds FROM camps WHERE ID='.$perso['armee'].'');
echo '<h1>March&eacute;</h1>
<p>
	Consulter le catalogues et faite vos course !<br /><br />
	Fonds disponibles : ',$fonds[1]['fonds'],'
</p> 
<h3>Passer commande</h3>
<form method="post" action="gene.php?act=marche">
	<table> 
		<tr>
			<th>Nom</th>
			<th>Prix</th>
			<th>Description</th>
			<th>Acheter?</th>
		</tr>'; 
	for($i=1;$i<=$objets[0];$i++) {
		echo '  
		<tr>
			<td>',$objets[$i]['nom'],'</td>
			<td>',$objets[$i]['prix'],'</td>
			<td>',filtrage_ordre(post2text($objets[$i]['description'])),'</td>
			<td>',form_radio('','marche_objet_achete',$objets[$i]['ID']),'</td>		
		</tr>	
	 ';
	} 	  
	echo '
	</table>
	 <br />'
	,form_submit('marche_valide_ok','Valider Commande');
  
echo '<h3>Commandes en Attentes</h3>
<p>';
affiche_commandes(0,$commandes_attentes,true);
echo '</p>';

echo '<h3>Commandes livr&eacute;es</h3>
<p>';
affiche_commandes(1,$commandes_livrees,false);
echo '</p>';  

?>
