<?php
$listePubs=array(// comclick
		 array('<!-- COMCLICK France : 468 x 60 -->
<iframe src="http://fl01.ct2.comclick.com/aff_frame.ct2?id_regie=1&num_editeur=15436&num_site=1&num_emplacement=1" WIDTH="468" HEIGHT="60" marginwidth="0" marginheight="0" hspace="0" vspace="0" frameborder="0" scrolling="no" bordercolor="#000000">
</iframe>
<!-- FIN TAG -->',90),
		 // clickjeux
		 array('<script language=\'JavaScript\' src=\'http://www.clicjeux.net/banniere.php?id=982\'></script>',10));
$shown=false;
for($i=0;$i<count($listePubs)-1;$i++){
  if(rand(0,100)<=$listePubs[$i][1]){
    echo $listePubs[$i][0];
    $shown=true;
  }
}

if(!$shown){
  echo $listePubs[$i][0];
}
?>
