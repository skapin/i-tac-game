<?php

function consoleWaypoint($niveau_gene,$camp,$carte_id)
{
	/*
	* ITAC - 2010-04-07
	* Skapin
	*/
	if($niveau_gene !=-1 /*50*/)
	{ 
		//Reutilisation de $camp/$map/$x/$y		
		//on verifie si il faut supprimer un waypoint ou pas
		if(isset($_POST['strat_ok'], $_POST['strat_suppr_waypoint']))
		{
			request('DELETE FROM waypoint WHERE id='.$_POST['strat_suppr_waypoint'].' and camps='.$camp.' ');

		}	
		//on verifie si on doit ajouter un Waypoint ou non
		if(isset($_POST['strat_ok'],$_POST['strat_color'], $_POST['strat_x'],$_POST['strat_y'],$_POST['carte_id_wp'],$_POST['strat_label']) && is_numeric($_POST['strat_x']) && is_numeric($_POST['strat_y']))
		{
			$exclu_compa=0; 
			//on prepare les couleurs
			$couleur_label=explode('_',$_POST['strat_color']);
			if(post_on('strat_exclu_compa_ok'))
			{
				$exclu_compa=$_POST['strat_exclu_compa'];
			}

			request("INSERT INTO waypoint (`camps`,`carte`,`x`,`y`,`label`,`color_R`,`color_G`,`color_B`,`grade`,`compa`)
				VALUES(".$camp.",".$_POST['carte_id_wp'].",".$_POST['strat_x'].",			".$_POST['strat_y'].",'".$_POST['strat_label']."',".$couleur_label[0].",".$couleur_label[1].",".$couleur_label[2].",".(post_on('strat_exclu_generaux')*50). ",".$exclu_compa.")");
			add_message(3,'Votre Zone stratégique a bien ajoutée<br /> Elle s\'affichera au prochain rafraichissement de la carte');
		}

		//---------------Affichage---------------

		$waypoints=my_fetch_array('SELECT id,CONCAT(label,\' ; X=\',x,\' / Y=\',y) FROM waypoint WHERE camps='.$camp.' ');
		$compas=my_fetch_array('SELECT id,CONCAT(nom,\'\ (\',initiales,\'\) \')
			FROM compagnies
			WHERE compagnies.camp='.$camp.' AND compagnies.valide=1');		

		$color=array(7,
			array('200_010_200','violet'),
			array('015_128_015','vert'),
			array('128_000_000','bordeaux'),
			array('210_150_010','jaune'),
			array('000_255_255','cyan'),
			array('150_150_150','gris'),
			array('000_000_128','bleu marine'));


		echo'<h1>Zones Stratégiques</h1>
			<form method="post" action="carte.php">
			<p>',form_select('Zone a supprimer : ','strat_suppr_waypoint',$waypoints,''),'<br />
			',form_submit('strat_ok','Supprimer'),'
			</p> 
			</form>
			<h1>Ajouter Zone</h1>
			<form name="waypointForm" method="post" action="carte.php">
			<p>
			',form_text('Label : ','strat_label','3',''),' ',
			form_select('Couleur du label  : ','strat_color',$color,''),'<br /> ',
			form_text('X : ','strat_x','3',''),' ',
			form_text('Y : ','strat_y','3',''),'<br />',
			form_check('Excusivement pour les généraux ? :','strat_exclu_generaux'),'<br />', 
			form_check('Affecter à un kvindek ? :','strat_exclu_compa_ok'),' ',
			form_select('Choix du kvindek  : ','strat_exclu_compa',$compas,''),'<br />',' 			
			<input type="hidden" name="carte_id_wp" value="',$carte_id,'" />',
			form_submit('strat_ok','Ajouter'),'
			</p>
			</form>
			';
	}
}
?>


