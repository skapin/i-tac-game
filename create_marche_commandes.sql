 CREATE TABLE `itac01`.`marche_commandes` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`camp` TINYINT( 4 ) NOT NULL ,
`carte` SMALLINT( 5 ) NOT NULL ,
`id_objet` INT( 10 ) NOT NULL ,
`etat` TINYINT( 3 ) NOT NULL ,
`bought_by` INT( 10 ) NOT NULL ,
`timestamp` INT( 15 ) NOT NULL DEFAULT '0'
) ENGINE = MYISAM 
