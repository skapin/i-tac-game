<?php
class comCompagnie{
  var $id;
  var $nom;
  var $initiales;
  var $desc;
  var $hrp;
  var $camp;
  var $grade_mini;
  var $grade_maxi;
  var $inscriptions;
  var $valide;
  var $db;
  var $members;
  var $grades;
  var $listeDroits=array('colo_ordres','colo_valider','colo_criteres','colo_virer','colo_droits','colo_HRP','colo_RP','colo_sigle','colo_grades','ordrescompa','forum_compa','forum_mcompa');
  
  var $errorMessage;

  /*
   Constructeur.
  */
  function comCompagnie($id,$db){
    if(!empty($id) && is_numeric($id)){
      $plop = $db->fetch('SELECT nom,
initiales,
`desc`,
camp,
grade_mini,
grade_maxi,
inscriptions,
valide,
HRP
FROM compagnies
WHERE ID='.$id);
      if(!empty($plop)){
	$this->id=$id;
	$this->db=$db;
	list($this->nom,
	     $this->inititales,
	     $this->desc,
	     $this->camp,
	     $this->grade_mini,
	     $this->grade_maxi,
	     $this->inscriptions,
	     $this->valide,
	     $this->hrp)=$plop[0];
      }else{
	$this->errorMessage = 'Impossible d\'instancier la compagnie.';
      }
    }
  }

  /*
   Recuperation des membres de la compagnie.
   */
  function getMembers(){
    $this->members = $this->db->fetch('SELECT persos.ID,
persos.nom,
persos.compte,
colo_HRP,
colo_RP,
colo_criteres,
colo_droits,
colo_sigle,
colo_valider,
colo_ordres,
colo_virer,
colo_grades,
ordrescompa,
forum_compa,
forum_mcompa,
grades.nom,
grades.compa,
grades.niveau
FROM persos
LEFT OUTER JOIN grades
ON persos.grade = grades.ID
WHERE persos.compagnie='.$this->id);
    return $this->members;
  }

  /*
   Recuperation des grades associes a la compagnie.
   */
  function getGrades(){
    $this->grades = $this->db->fetch('SELECT ID, nom, niveau, valide
FROM grades
WHERE compa='.$this->id);
    return $this->grades;
  }

  /*
   Pour detruire une compagnie.
   */
  function destroyBy($destroyer){
    if(!is_numeric($destroyer)){
      return -1;
    }
    // Verification des droits de la personne qui detruit la compagnie.
    $plop = $this->db->fetch('SELECT persos.armee,
persos.compagnie,
gene_compas,
grades.niveau
FROM persos
LEFT OUTER JOIN grades
ON persos.grade = grades.ID
WHERE persos.ID='.$destroyer);
    if(empty($plop)){
      return -2;
    }
    if(($plop[0]['gene_compas'] == 1 &&
	$plop[0]['armee'] == $this->camp) ||
       ($plop[0]['niveau'] == 1 &&
	$plop[0]['compagnie'] == $this->id)){
      $this->destroy();
    }
  }

  function destroy(){
    $this->getGrades();
    $this->getMembers();
    // Update des grades portes par les membres de la compagnie.
    if(!empty($this->grades)){
      $where=array();
      foreach($this->grades AS $grade){
	if($where[$grade['niveau']]){
	  $where[$grade['niveau']].=' OR ';
	}
	$where[$grade['niveau']].='grade='.$grade['ID'];
      }
      // Update des grades.
      for($i = 0; $i <=3; $i++){
	if($where[$i]){
	  $this->db->request('UPDATE persos
SET grade='.$i.'
WHERE '.$where[$i]);
	}
      }
    }

    // Puis update du reste.
    $updateDroits = '';
    foreach($this->listeDroits AS $droit){
      $updateDroits.='`'.$droit.'`=0,
';
    }

    // Update du colonel.
    $this->db->request('UPDATE persos
SET '.$updateDroits.'compagnie=1,
grade=0,
forum_em=0,
niveau_gene=0,
niveau_compa=0
WHERE niveau_compa=100
AND compagnie='.$this->id);

    // Update des autres membres.
    $this->db->request('UPDATE persos
SET '.$updateDroits.'compagnie=1,
niveau_compa=0
WHERE compagnie='.$this->id);

    // Update des droits forum.
    foreach($this->members AS $member){
      update_droits_forum($member['ID']);
    }
    
    // Suppression du forum de la compagnie.
    forumDelCompa($this->id);

    // Suppression des grades associes a la compagnie.
    $this->db->request('DELETE FROM grades WHERE compa='.$this->id);

    // Suppression de la compagnnie.
    $this->db->request('DELETE FROM compagnies WHERE ID='.$this->id.' LIMIT 1');
  }
}
?>