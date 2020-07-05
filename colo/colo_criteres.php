<?php
if(isset($_POST['compa_criteres_ok'],$_POST['compa_mini'],$_POST['compa_maxi']) &&
   is_numeric($_POST['compa_mini']) &&
   is_numeric($_POST['compa_maxi']) &&
   $perso['ID_compa']>1)
{
  if($_POST['compa_mini']>=0 && $_POST['compa_mini']<=13 && $_POST['compa_maxi']>=0 && $_POST['compa_maxi']<=13)
    {
      request('UPDATE compagnies
               SET grade_mini='.$_POST['compa_mini'].',
                   grade_maxi='.$_POST['compa_maxi'].',
                   inscriptions='.post_on('compa_inscr').'
               WHERE ID='.$perso['ID_compa']);
    }
  else
    add_message(3,'Valeur incorrecte pour les grades.');
}
$compa=my_fetch_array('SELECT grade_mini,
                              grade_maxi,
                              inscriptions
                       FROM compagnies
                       WHERE ID='.$perso['ID_compa'].'
                       LIMIT 1');
if($compa[0])
{
  if($compa[1]['inscriptions'])
    $_POST['compa_inscr']='on';
  $_POST['compa_mini']=$compa[1]['grade_mini'];
  $_POST['compa_maxi']=$compa[1]['grade_maxi'];
  $grades=array(14,
		array(0,numero_camp_grade(0,0)),
		array(1,numero_camp_grade(0,1)),
		array(2,numero_camp_grade(0,2)),
		array(3,numero_camp_grade(0,3)),
		array(4,numero_camp_grade(0,4)),
		array(5,numero_camp_grade(0,5)),
		array(6,numero_camp_grade(0,6)),
		array(7,numero_camp_grade(0,7)),
		array(8,numero_camp_grade(0,8)),
		array(9,numero_camp_grade(0,9)),
		array(10,numero_camp_grade(0,10)),
		array(11,numero_camp_grade(0,11)),
		array(12,numero_camp_grade(0,12)),
		array(13,numero_camp_grade(0,13)));
      
  echo'<form method="post" action="compagnie.php?colo_criteres">
<p>
',form_check('Inscriptions ouvertes ? ','compa_inscr'),'<br />
',form_select('Grade minimum pour postuler : ','compa_mini',$grades,''),'<br />
',form_select('Grade maximum pour postuler : ','compa_maxi',$grades,''),'<br />
',form_submit('compa_criteres_ok','Ok'),'
</p>
</form>
';
}
?>