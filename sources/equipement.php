<?php
$infos='';
$grade=$perso['peine_grade']?$perso['peine_grade']-1:$perso['grade_reel'];
echo'   <div id="listeMatos">
	<div id="armureListe" class="displayMatos">
	<h2>Armures</h2>
	<select id="selectArmure">
	<option value="ALE"'.($perso['type_armure']==1?' selected="selected"':'').'>',catArmure(1),'</option>
	<option value="AMO"'.($perso['type_armure']==2?' selected="selected"':'').'>',catArmure(2),'</option>
	<option value="ALO"'.($perso['type_armure']==4?' selected="selected"':'').'>',catArmure(4),'</option>
	</select>
	';

$sql='SELECT armures.*
	FROM armures
	INNER JOIN armures_camps
	ON armures_camps.armure=ID
	WHERE armures_camps.camp='.$perso['armee'].'
	ORDER BY `type` ASC, PA ASC';
$armures=my_fetch_array($sql);

$str_armures=array(1=>'     <select size="20" id="ALE">
	',
	2=>'     <select size="20" id="AMO">
	',
	4=>'     <select size="20" id="ALO">
	');

$liste_armures[0]=0;
for($i=1;$i<=$armures[0];$i++){
	if((($armures[$i]['grade']<=$grade &&
		($time-$perso['date_last_shot'])>$GLOBALS['tour'] &&
		(!$_SESSION['com_terrain'] ||
		$_SESSION['com_terrain']['prix_'.$perso['type_armure']]) &&
		(!$perso['peine_armures'] ||
		(!$perso['X'] &&
		!$perso['Y'] &&
		!$perso['map']))) ||
	$armures[$i]['ID']==$perso['ID_armure'])){
		$capa = $armures[$i]['capacite']+($armures[$i]['type']==1?2*$perso['imp_force']:0);

		$str_armures[$armures[$i]['type']].='      <option value="'.$armures[$i]['ID'].'">'.bdd2html($armures[$i]['nom']).'</option>
			';
		$infos.='    <div id="infos_A'.$armures[$i]['ID'].'" class="infos">
			<p class="titleBar">'.bdd2html($armures[$i]['nom']).'</p>
			<dl>
			<dt>PA : </dt>
			<dd>'.$armures[$i]['PA'].'</dd>
			<dt>Accessible au grade : </dt>
			<dd>'.numero_camp_grade($perso['armee'],$armures[$i]['grade']).'</dd>
			<dt>Malus de mouvement : </dt>
			<dd>'.$armures[$i]['malus_terrain'].'%</dd>
			<dt>P&eacute;nalit&eacute; de camouflage : </dt>
			<dd>'.$armures[$i]['malus_camou'].'%</dd>
			<dt>Bonus de pr&eacute;cision : </dt>
			<dd>'.$armures[$i]['bonus_precision'].'%</dd>
			<dt>R&eacute;sistance aux critiques : </dt>
			<dd>'.$armures[$i]['malus_critique'].'%</dd>
			<dt>Poids transportable : </dt>
			<dd>'.$capa.' kg</dd>
			<dt>Bonus aux d&eacute;g&acirc;ts C&agrave;C : </dt>
			<dd>'.$armures[$i]['degatsCaC'].'%</dd>
			</dl>
			<div class="clearer"></div>
			</div>
			';
		$liste_armures[]=array($armures[$i]['ID'],bdd2html($armures[$i]['nom']));
		$liste_armures[0]++;
	}
}
echo $str_armures[1],'     </select>
	',$str_armures[2],'     </select>
	',$str_armures[4],'     </select>
	</div>
	';
unset($armures);
unset($str_armures);

$gadgets=my_fetch_array('SELECT gadgets.*,
	terrains.nom AS nom_pont
	FROM gadgets
	LEFT OUTER JOIN terrains
	ON terrains.ID=gadgets.type_pont
	WHERE grade_1<='.$grade.'
	OR grade_2<='.$grade.'
	OR grade_3<='.$grade.'
	OR gadgets.ID='.($perso['ID_gad1']>0?$perso['ID_gad1']:0).'
	OR gadgets.ID='.($perso['ID_gad2']>0?$perso['ID_gad2']:0).'
	OR gadgets.ID='.($perso['ID_gad3']>0?$perso['ID_gad3']:0).'
	ORDER BY grade_1 DESC');
$str_gadgets=array(1=>'    <div id="gad1Liste"  class="displayMatos">
	<h2>Gadget 1</h2>
	<select size="20">
	',
	2=>'    <div id="gad2Liste" class="displayMatos">
	<h2>Gadget 2</h2>
	<select size="20">
	',
	3=>'    <div id="gad3Liste" class="displayMatos">
	<h2>Gadget 3</h2>
	<select size="20">
	');

$gadget1=array(0);
$gadget2=array(0);
$gadget3=array(0);
for($i=1;$i<=$gadgets[0];$i++){
	$class='';
	$str_armure='';
	$k=0;
	// On verifie ce que n&eacute;cessite ce gadget pour être utilisé.
	// Les armures d'abord
	$lesarmures=dispo_armure($gadgets[$i]['armure']);
	if($lesarmures[0]){
		$class.=' ALE';
		$str_armure.=catArmure(1);
		$k++;
	}
	if($lesarmures[1]){
		$class.=' AMO';
		if($k)
			$str_armure.=', ';
		$str_armure.=catArmure(2);
		$k++;
	}
	if($lesarmures[2]){
		$class.=' ALO';
		if($k)
			$str_armure.=', ';
		$str_armure.=catArmure(4);
	}
	// Puis les armes
	$k=0;
	$str_armes='';
	$armes=dispo_arme($gadgets[$i]['arme']);
	for($j=0;$j<=9;$j++){
		if($armes[$j]){
			$class.=' armes_'.$j;
			if($k)
				$str_armes.=', ';
			$str_armes.=nom_type_arme($j);
			$k++;
		}
	}
	$class=trim($class);
	// Puis on ajoute les gadgets dans les 3 listes si c'est possible.
	if($gadgets[$i]['grade_1']<=$perso['grade_reel'] &&
		$perso['date_last_used_gad1']+$GLOBALS['tour'] < $time ||
	$perso['ID_gad1'] == $gadgets[$i]['ID']){
		$str_gadgets[1].='      <option value="'.$gadgets[$i]['ID'].'" class="'.$class.'" label="'.$gadgets[$i]['stack'].'">'.bdd2html($gadgets[$i]['nom']).'</option>
			';
		$gadget1[0]++;
		$gadget1[$gadget1[0]]=array($gadgets[$i]['ID'],bdd2html($gadgets[$i]['nom']));
	}
	if($gadgets[$i]['grade_2']<=$perso['grade_reel'] &&
		$perso['date_last_used_gad2']+$GLOBALS['tour'] < $time ||
	$perso['ID_gad2'] == $gadgets[$i]['ID']){
		$str_gadgets[2].='      <option value="'.$gadgets[$i]['ID'].'" class="'.$class.'" label="'.$gadgets[$i]['stack'].'">'.bdd2html($gadgets[$i]['nom']).'</option>
			';
		$gadget2[0]++;
		$gadget2[$gadget2[0]]=array($gadgets[$i]['ID'],bdd2html($gadgets[$i]['nom']));
	}
	if($gadgets[$i]['grade_3']<=$perso['grade_reel'] &&
		$perso['date_last_used_gad3']+$GLOBALS['tour'] < $time ||
	$perso['ID_gad3'] == $gadgets[$i]['ID']){
		$str_gadgets[3].='      <option value="'.$gadgets[$i]['ID'].'" class="'.$class.'" label="'.$gadgets[$i]['stack'].'">'.bdd2html($gadgets[$i]['nom']).'</option>
			';
		$gadget3[0]++;
		$gadget3[$gadget3[0]]=array($gadgets[$i]['ID'],bdd2html($gadgets[$i]['nom']));
	}
	// Maintenant on prépare la div contenant les infos du gadget
	$infos.='   <div id="infos_C'.$gadgets[$i]['ID'].'" class="infos">
		<p class="titleBar">'.bdd2html($gadgets[$i]['nom']).'</p>
		<dl>
		';
	if($gadgets[$i]['bonus_precision'])
		$infos.='     <dt>Bonus &agrave; la pr&eacute;cision : </dt>
		<dd>'.$gadgets[$i]['bonus_precision'].'%</dd>
		';
	if($gadgets[$i]['bonus_camou'])
		$infos.='     <dt>Bonus &agrave; la comp&eacute;tence camouflage : </dt>
		<dd>'.$gadgets[$i]['bonus_camou'].'</dd>
		';
	if($gadgets[$i]['bonus_tir_camou'])
		$infos.='     <dt>Diminution du malus au camouflage lors d\'un tir : </dt>
		<dd>'.$gadgets[$i]['bonus_tir_camou'].'</dd>
		';
	if($gadgets[$i]['bonus_vision'])
		$infos.='     <dt>Bonus &agrave; la vision  : </dt>
		<dd>'.$gadgets[$i]['bonus_vision'].' cases</dd>
		';
	if($gadgets[$i]['eclaireur'])
		$infos.='     <dt>Bonus &agrave; la comp&eacute;tence &eacute;claireur  : </dt>
		<dd>'.$gadgets[$i]['eclaireur'].'</dd>
		';
	if($gadgets[$i]['escalade'])
		$infos.='     <dt>Bonus &agrave; la comp&eacute;tence escalade  : </dt>
		<dd>'.$gadgets[$i]['escalade'].'</dd>
		';
	if($gadgets[$i]['foret'])
		$infos.='     <dt>Bonus &agrave; la comp&eacute;tence For&ecirc;t : </dt>
		<dd>'.$gadgets[$i]['foret'].'</dd>
		';
	if($gadgets[$i]['montagne'])
		$infos.='     <dt>Bonus &agrave; la comp&eacute;tence Montagne : </dt>
		<dd>'.$gadgets[$i]['montagne'].'</dd>
		';
	if($gadgets[$i]['desert'])
		$infos.='     <dt>Bonus &agrave; la comp&eacute;tence D&eacute;sert : </dt>
		<dd>'.$gadgets[$i]['desert'].'</dd>
		';
	if($gadgets[$i]['marais'])
		$infos.='     <dt>Bonus &agrave; la comp&eacute;tence Marais  : </dt>
		<dd>'.$gadgets[$i]['marais'].'</dd>
		';
	if($gadgets[$i]['plaine'])
		$infos.='     <dt>Bonus &agrave; la comp&eacute;tence Plaine  : </dt>
		<dd>'.$gadgets[$i]['plaine'].'</dd>
		';
	if($gadgets[$i]['nage'])
		$infos.='     <dt>Bonus &agrave; la comp&eacute;tence Eau  : </dt>
		<dd>'.$gadgets[$i]['nage'].'</dd>
		';
	if($gadgets[$i]['pont'])
		$infos.='     <dt>Bonus &agrave; la comp&eacute;tence Pont  : </dt>
		<dd>'.$gadgets[$i]['pont'].'</dd>
		';
	if($gadgets[$i]['mines'])
		$infos.='     <dt>Nombre de mines : </dt>
		<dd>'.$gadgets[$i]['mines'].'</dd>
		<dt>D&eacute;g&acirc;ts : </dt>
		<dd>'.$gadgets[$i]['degats_mines'].' ('.$gadgets[$i]['pourcent_PV'].'% direct PV)</dd>
		<dt>Discr&eacute;tion : </dt>
		<dd>'.$gadgets[$i]['discretion'].' %</dd>
		<dt>Instabilit&eacute; : </dt>
		<dd>'.$gadgets[$i]['instabilite'].' %</dd>
		';
	$infos.='     <dt>Position  : </dt>
		<dd>'.$gadgets[$i]['stack'].'</dd>
		';
	$infos.='    </dl>
		<hr class="detail" />
		<dl class="detail">
		<dt>Utilisable avec les armures : </dt>
		<dd> '.$str_armure.'</dd>
		<dt>et les armes de type : </dt>
		<dd>'.$str_armes.'</dd>
		</dl>
		<hr class="detail" />';
	$infos.= (bdd2html($gadgets[$i]['description'])?'
		<p class="detail" >'.bdd2html($gadgets[$i]['description']).'</p>':'').'
		</div>
		';
}
echo $str_gadgets[1],'     </select>
	</div>
	',$str_gadgets[2],'     </select>
	</div>
	',$str_gadgets[3],'     </select>
	</div>
	<div class="displayMatos" id="armePListe">
	<h2>Arme primaire</h2>
	<select id="selectArmeP">
	<option value="0"'.($perso['type_arme1']==0?' selected="selected"':'').'>Armes d\'assaut</option>
	<option value="1"'.($perso['type_arme1']==1?' selected="selected"':'').'>Mitrailleuses</option>
	<option value="2"'.($perso['type_arme1']==2?' selected="selected"':'').'>Sniper</option>
	<option value="3"'.($perso['type_arme1']==3?' selected="selected"':'').'>Lance flammes</option>
	<option value="4"'.($perso['type_arme1']==4?' selected="selected"':'').'>Lance roquettes</option>
	<option value="5"'.($perso['type_arme1']==5?' selected="selected"':'').'>M&eacute;cano</option>
	<option value="6"'.($perso['type_arme1']==6?' selected="selected"':'').'>Fusils &agrave; pompe</option>
	</select>
	';
unset($gadgets);
unset($str_gadgets);
$armes1[0]=0;
$armes2[0]=0;
$armes=my_fetch_array('SELECT armes.*,
	munars.poids AS poids_m,
	munars.nom AS nom_m
	FROM armes
	INNER JOIN armes_camps
	ON armes_camps.arme=armes.ID
	INNER JOIN munars
	ON munars.ID=armes.type_munitions
	WHERE `camp`='.$perso['armee'].'
	ORDER BY `type` ASC,grade ASC , lvl ASC');

$type=-1;
$secondary=0;
for($i=1;$i<=$armes[0];$i++){
	if($armes[$i]['type']!=$type){
		if($type>=0){
			echo'     </select>
				';
		}
		if($type >= 6 && $secondary == 0){
			echo'    </div>
				<div class="displayMatos" id="armeSListe">
				<h2>Arme de secours</h2>
				<select id="selectArmeS">
				<option value="7"'.($perso['type_arme2']==7?' selected="selected"':'').'>Corps &agrave; corps</option>
				<option value="8"'.($perso['type_arme2']==8?' selected="selected"':'').'>M&eacute;decin</option>
				<option value="9"'.($perso['type_arme2']==9?' selected="selected"':'').'>Pistolets</option>
				</select>
				';
			$secondary=1;
		}
		$type=$armes[$i]['type'];
		echo'     <select id="voir_armes_',$type,'" size="20">
			';
	}
	if($perso['peine_'.bdd_arme($type)]){
		$niveau_arme=$perso['peine_'.bdd_arme($type)]-1;
	}
	else{
		$niveau_arme=$perso[bdd_arme($type)];
	}
	if(($niveau_arme+$grade>=$armes[$i]['palier'] &&
		$perso['peine_'.bdd_arme($type)]!=-1) ||
		$perso['ID_arme1']==$armes[$i]['ID'] ||
	$perso['ID_arme2']==$armes[$i]['ID']){
		$class="ok";
		$k=0;
		$str_armure='';
		$lesarmures=dispo_armure($armes[$i]['armure']);
		if($lesarmures[0] || ($lesarmures[1]&&$perso['imp_force']>=5)){
			$str_armure.=catArmure(1);
			$k++;
			$class.=' ALE';
		}
		if($lesarmures[1]){
			if($k)
				$str_armure.=', ';
			$str_armure.=catArmure(2);
			$k++;
			$class.=' AMO';
		}
		if($lesarmures[2]){
			if($k)
				$str_armure.=', ';
			$str_armure.=catArmure(4);
			$class.=' ALO';
		}
		if($armes[$i]['type']<=6){
			$armes1[0]++;
			$armes1[$armes1[0]][0]=$armes[$i]['ID'];
			$armes1[$armes1[0]][1]=bdd2html($armes[$i]['nom']);
		}
		else{
			$armes2[0]++;
			$armes2[$armes2[0]][0]=$armes[$i]['ID'];
			$armes2[$armes2[0]][1]=bdd2html($armes[$i]['nom']);
		}
		echo'      <option value="',$armes[$i][0],'"',($class?' class="'.$class.'"':''),'>',bdd2html($armes[$i]['nom']),'</option>
			';
		// Maintenant on pr&eacute;pare la bulle d'info de cette arme.
		$infos.='   <div id="infos_arme_'.$armes[$i]['ID'].'" class="infos">
			<p class="titleBar">'.bdd2html($armes[$i]['nom']).'</p>
			<dl>
			<dt>Port&eacute;e : </dt>
			<dd>'.$armes[$i]['portee'].' cases</dd>
			<dt>D&eacute;g&acirc;ts : </dt>
			<dd>'.$armes[$i]['degats'].'</dd>
			<dt>Tirs : </dt>
			<dd>'.$armes[$i]['tirs'].'</dd>
			';
		if($armes[$i]['type']==4 || $armes[$i]['type']==3){
			$infos.='     <dt>Diminution des d&eacute;g&acirc;ts par case : </dt>
				<dd>'.$armes[$i]['diminution'].'%</dd>
				';
		}
		$infos.='     <dt>Pr&eacute;cision minimum : </dt>
			<dd>'.$armes[$i]['precision_min'].'%.</dd>
			<dt>Pr&eacute;cision maximum : </dt>
			<dd> '.$armes[$i]['precision_max'].'%</dd>
			<dt>Critique : </dt>
			<dd>'.$armes[$i]['degat_vie'].'/'.$armes[$i]['critique'].'%</dd>
			<dt>Seuil : </dt>
			<dd>'.$armes[$i]['seuil_critique'].'</dd>
			';
		if($armes[$i]['type']==4){
			$infos.='     <dt>Rayon d\'effet : </dt>
				<dd>'.$armes[$i]['zone'].' cases</dd>
				<dt>Chances de toucher en cas de dispersion : </dt>
				<dd>'.$armes[$i]['touche'].'%</dd>
				';
		}
		if($armes[$i]['type']==4 || $armes[$i]['type']==3){
			$infos.='     <dt>Diminution des chances de toucher par case de distance : </dt>
				<dd>'.$armes[$i]['dimit'].'%</dd>
				';
		}
		$infos.='    </dl>
			<hr />
			<dl>
			<dt>Munitions : </dt>
			<dd>'.$armes[$i]['max_munitions'].'</dd>
			<dt>Cadence : </dt>
			<dd>'.$armes[$i]['tir_munars'].'</dd>
			<dt>Type de munitions : </dt>
			<dd>'.bdd2html($armes[$i]['nom_m']).'</dd>
			<dt>Malus au camouflage de : </dt>
			<dd>'.$armes[$i]['malus_camou'].'</dd>
			</dl>
			<hr class="detail" />
			<dl class="detail" >
			<dt>Palier (grade+comp&eacute;tence) : </dt>
			<dd>'.$armes[$i]['palier'].'</dd>
			<dt>Armure : </dt>
			<dd>'.$str_armure.'</dd>
			<dt>Poids avec munitions : </dt>
			<dd class="poidsarme1">'.ceil($armes[$i]['poids']+$armes[$i]['poids_m']*$armes[$i]['max_munitions']).'</dd>
			</dl>
			<hr class="detail" />
			'.(bdd2html($armes[$i]['description'])?'    <p class="detail" >'.bdd2html($armes[$i]['description']).'
			</p>':'').'   </div>
			';
	}
}
if($armes[0])
	echo'     </select>
	</div>
	';
unset($armes);
echo' </div>
	<div class="weapon">
	<h2>Arme primaire</h2>
	<div id="armeP">
	</div>
	</div>
	<div id="middle">
	<h2>Armure</h2>
	<div id="armure">
	</div>
	<form method="post" action="jouer.php" id="formEquip"'.(!empty($framed)?' target="_parent"':'').'>
	<p>
	',form_hidden('arme1',0),'
	',form_hidden('arme2',0),'
	',form_hidden('gadget1',0),'
	',form_hidden('gadget2',0),'
	',form_hidden('gadget3',0),'
	',form_hidden('armor',0),'
	<input type="submit" name="equipement_ok" id="equipement_ok" value="Valider" />
	<input type="reset" id="equipement_reset" value="R&agrave;Z" /><br/>
	<p id="poidsTransportable">Poids: N/A</p>
	</p>
	</form>
	</div>
	<div class="weapon">
	<h2>Arme secondaire</h2>
	<div id="armeS">
	</div>
	</div>
	<div class="gadget">
	<h2>Gadget primaire</h2>
	<div id="gad1">
	</div>
	</div>
	<div class="gadget">
	<h2>Gadget secondaire</h2>
	<div id="gad2">
	</div>
	</div>
	<div class="gadget">
	<h2>Gadget tertiaire</h2>
	<div id="gad3">
	</div>
	</div>
	',$infos,'
	';
if(!empty($perso['ID_armure'])){
	if($perso['type_armure'] == 1){
		$armor='ALE';
	}
	else if($perso['type_armure'] == 2){
		$armor='AMO';
	}
	else{
		$armor='ALO';
	}
	echo'  <script type="text/javascript">
		currentArmorType="'.$armor.'";
	currentWeaponType='.$perso['type_arme1'].';
	currentWeaponType2='.$perso['type_arme2'].';
	chosenArmorType="'.$armor.'";
	chosenWeaponType1='.$perso['type_arme1'].';
	chosenWeaponType2='.$perso['type_arme2'].';
	chosenGadgetPos1='.$perso['stack_gad1'].';
	chosenGadgetPos2='.$perso['stack_gad2'].';
	chosenGadgetPos3='.$perso['stack_gad3'].';
	currentArmor='.$perso['ID_armure'].';
	currentWeapon1='.$perso['ID_arme1'].';
	currentWeapon2='.$perso['ID_arme2'].';
	currentGad1='.$perso['ID_gad1'].';
	currentGad2='.$perso['ID_gad2'].';
	currentGad3='.$perso['ID_gad3'].';
	// ITAC - LD - 2010-01-24
	// ITAC - LD - BEGIN
	// http://dandoy.fr/mantis/view.php?id=9
	maxPoids=0;
	poidsArme1=0;
	poidsArme2=0;
	curPoids=0;
	// ITAC - LD - END
	</script>
		';
}
?>
