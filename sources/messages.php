<?php
function start_message()
{
  $GLOBALS['com_messages']='';
  $GLOBALS['box']=0;
  $GLOBALS['com_message_jsToAdd']='';
  $GLOBALS['com_message_script']='';
  /*$GLOBALS['com_erreurs']='';
  $_SESSION['com_message']='';*/
}

function add_message($type,$message)
{
  if($type == 5){
    $GLOBALS['com_message_jsToAdd'].=$message;
  }
  else if($type == 4){
    // Message a faire apparaitre dans une petite fenetre.
    $GLOBALS['com_message_script'].=$message;
  }
  else{
    $GLOBALS['box']=1;
    $GLOBALS['com_messages'].=$message.' 
';
  }
}

function erreur($type,$message)
{
  add_message($type,$message);
}

function print_messages()
{
  echo $GLOBALS['com_messages'];
  if(!empty($GLOBALS['com_message_script'])||
     !empty($GLOBALS['com_message_jsToAdd'])){
    echo'<script type="text/javascript">
var toShow="'.$GLOBALS['com_message_script'].'";
parent.document.getElementById("events").className = "clearer";
parent.document.getElementById("eventsContent").innerHTML = toShow;
'.$GLOBALS['com_message_jsToAdd'].';
parent.showEvents(); 
</script>
';
  }
}
?>