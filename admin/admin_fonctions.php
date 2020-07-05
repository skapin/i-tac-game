<?php
function autorisation($truc){
  global $perso;
  if(isset($perso['admin']['anim_'.$truc])&&$perso['admin']['anim_'.$truc])
    return 1;
  return 0;
}

function ident(){
  if(empty($_SESSION['com_ID'])){
    die('Vous n\'&ecirc;tes pas logu&eacute;');
  }
  global $perso;
  $perso['admin']=recupAdmin();
}

function menu(){
  ident();
  if(autorisation('armes'))
    echo lien('anim.php?admin_armes','Armes');
  if(autorisation('armures'))
    echo lien('anim.php?admin_armures','Armures');
  if(autorisation('camps'))
    echo lien('anim.php?admin_camps','Camps');
  if(autorisation('cartes'))
    echo lien('anim.php?admin_cartes','Cartes');
  if(autorisation('droits'))
    echo lien('anim.php?admin_droits','Droits');
  if(autorisation('gadgets'))
    echo lien('anim.php?admin_gadgets','Gadgets');
  if(autorisation('gene'))
    echo lien('anim.php?admin_gene','G&eacute;n&eacute;raux en chef');
  if(autorisation('grades'))
    echo lien('anim.php?admin_grades','Grades');
  if(autorisation('marche'))
    echo lien('anim.php?admin_marche','March&eacute;');
  if(autorisation('missions'))
    echo lien('anim.php?admin_missions','Missions');
  if(autorisation('munitions'))
    echo lien('anim.php?admin_munars','Munitions');
  if(autorisation('news'))
    echo lien('anim.php?admin_news','News');
  if(autorisation('objets'))
    echo lien('anim.php?admin_objets','Objets');
  if(autorisation('pnjs'))
    echo lien('anim.php?admin_pnjs','PNJs');
  if(autorisation('modif'))
    echo lien('anim.php?admin_modif','Personnages');
  if(autorisation('noms'))
    echo lien('anim.php?admin_noms','Pseudos');
  if(autorisation('qgs'))
    echo lien('anim.php?admin_QGs','QGs et tubes');
  if(autorisation('rp'))
    echo lien('anim.php?admin_rp','RP');
  if(autorisation('switch'))
    echo lien('anim.php?admin_switch','Switchs');
  if(autorisation('teleport'))
    echo lien('anim.php?admin_teleport','T&eacute;l&eacute;portation');
  if(autorisation('terrains'))
    echo lien('anim.php?admin_terrains','Terrains');
  if(autorisation('transfuge'))
    echo lien('anim.php?admin_transfuge','Transfuges');
}

function lien($page,$texte){
  return '<li><a href="'.$page.'">'.$texte.'</a></li>
';
}

function content(){
  global $perso;
  switch($_SERVER['QUERY_STRING']){
  case'admin_objets':
    if(autorisation('objets'))
      include('../admin/admin_objets.php');
    break;
  case'admin_switch':
    if(autorisation('switch'))
      include('../admin/admin_switch.php');
    break;
  case'admin_objets':
    if(autorisation('objets'))
      include('../admin/admin_objets.php');
    break;
  case'admin_rp':
    if(autorisation('rp'))
      include('../admin/admin_rp.php');
    break;
  case'admin_news':
    if(autorisation('news'))
      include('../admin/admin_news.php');
    break;
  case'admin_grades':
    if(autorisation('grades'))
      include('../admin/admin_grades.php');
    break;
  case'admin_transfuge':
    if(autorisation('transfuge'))
      include('../admin/admin_transfuge.php');
    break;
  case'admin_teleport':
    if(autorisation('teleport'))
      include('../admin/admin_teleport.php');
    break;
  case'admin_noms':
    if(autorisation('noms'))
      include('../admin/admin_noms.php');
    break;
  case'admin_droits':
    if(autorisation('droits'))
      include('../admin/admin_droits.php');
    break;
  case'admin_pnjs':
    if(autorisation('pnjs'))
      include('../admin/admin_pnj.php');
    break;
  case'admin_armures':
    if(autorisation('armures'))
      include('../admin/admin_armures.php');
    break;
  case'admin_armes':
    if(autorisation('armes'))
      include('../admin/admin_armes.php');
    break;
  case'admin_munars':
    if(autorisation('munitions'))
      include('../admin/admin_munars.php');
    break;
  case'admin_gadgets':
    if(autorisation('gadgets'))
      include('../admin/admin_gadgets.php');
    break;
  case'admin_missions':
    if(autorisation('missions'))
      include('../admin/admin_missions.php');
    break;
  case'admin_marche':
    if(autorisation('marche'))
      include('../admin/admin_marche.php');
    break;
  case'admin_camps':
    if(autorisation('camps'))
      include('../admin/admin_camps.php');
    break;
  case'admin_terrains':
    if(autorisation('terrains'))
      include('../admin/admin_terrains.php');
    break;
  case'admin_cartes':
    if(autorisation('cartes'))
      include('../admin/admin_cartes.php');
    break;
  case'admin_gene':
    if(autorisation('gene'))
      include('../admin/admin_gene.php');
    break;
  case'admin_QGs':
    if(autorisation('qgs'))
      include('../admin/admin_QGs.php');
    break;
  default:
    break;
  }
}
?>
