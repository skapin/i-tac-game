<?php
class comBdd{
  private $handler;
  private $driver='mysql';
  private $resource=null;
  private $result;

  public $errorMessage;
  /*
   Contructeur.
   */
  function __construct($host='',$user='',$pwd='',$base='',$driver='mysql',$handler=''){
    if(!empty($driver)){
      $this->driver=$driver;
    }
    // Si des information de connexion ont ete fournies, on se connecte.
    if(!(empty($host)  || empty($user) || empty($pwd))){
      $this->connect($host,$user,$pwd,$base);
    }
    else if(is_resource($handler)){
      $this->handler = $handler;
    }
    else{
      $this->errorMessage = 'Impossible d\'instancier la classe comBdd.';
    }
  }

  /*
   Permet d'envoyer des requetes a la bdd et de recuperer un
   handler sur le resultat.
   */
  function request($sql,$act='select'){
    $this->resource=null;
    if(is_resource($this->handler)){
      switch($this->driver){
      case 'mysql':
	$this->resource=mysql_query($sql,$this->handler);
	if(!$this->resource){
	  trigger_error(mysql_errno($this->handler).': '.mysql_error($this->handler),E_USER_ERROR);
	  return 0;
	}else if($act != 'select'){
	  return mysql_affected_rows($this->handler);
	}
	return $this->resource;
	break;
      default:
	break;
      }
    }
  }

  /*
   Recuperation de tous les resultats d'une requete dans un tableau.
   */
  function fetch($sql){
    $this->request($sql);
    $this->result='';
    if($this->resource){
      switch($this->driver){
      case 'mysql':
	while($this->result[]=mysql_fetch_array($this->resource)){}
	mysql_free_result($this->resource);
	// On supprime le dernier enregistrement du tableau car il est vide
	array_pop($this->result);
  	break;
      default:
	break;
      }
    }
    return $this->result;
  }

  /*
   Pour selectionner la base de donnees.
   */
  function selectDb($base){
    if(is_resource($this->handler)){
      switch($this->driver){
      case 'mysql':
	mysql_select_db($base,$this->handler);
	break;
      default:
	break;
      }
    }
  }

  /*
   Fonction permettant la connexion.
   Si une connexion existe deja, on deconnecte d'abord.
  */
  function connect($host,$user,$pwd,$base=''){
    if(is_resource($this->handler)){
      // Connexion deja existante, on la supprime.
      $this->disconnect();
    }
    switch($this->driver){
    case 'mysql':
      $this->handler = mysql_connect($host,$user,$pwd);
      break;
    default:
      break;
    }
    if(is_resource($this->handler) && !empty($base)){
      // Si on a specifie une base, on la selectionne
      $this->selectDb($base);
    }
  }

  /*
   Fonction de deconnection.
  */
  function disconnect(){
    if(is_resource($this->handler)){
      switch($this->driver){
      case 'mysql':
	mysql_close($this->handler);
	break;
      default:
	break;
      }
    }
  }
  /*
   Fonction permettant de recup la dernier auto-increment genere par un 
   insert.
   */
  function lastId(){
    if(is_resource($this->handler)){
      switch($this->driver){
      case 'mysql':
	mysql_insert_id($this->handler);
	break;
      default:
	break;
      }
    }
  }
}

?>