<?php
function autorisation($truc,$fofo=false){
  global $perso;
  if(!empty($perso['gene_'.$truc]) &&!$fofo ||
     !empty($perso['forum_'.$truc]) &&$fofo)
    return 1;
  return 0;
}

function menu(){
  global $listeDroits,$perso;
  foreach($listeDroits['gene'] AS $key=>$value){
    if(!$value[0] && autorisation($key)){
      echo lien('gene.php?act='.$key,$value[1]);
    }
  }
}

function lien($page,$texte){
  return '<li><a href="'.$page.'">'.$texte.'</a></li>
';
}
function content(){
  global $listeDroits,$perso;
  if(!empty($_GET['act'])){
    $act=$_GET['act'];
    if(!empty($listeDroits['gene'][$act]) &&
       !$listeDroits['gene'][$act][0] &&
       autorisation($act)){
      include('../gene/gene_'.$act.'.php');
    }
  }
}
?>