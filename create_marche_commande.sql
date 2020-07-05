 CREATE TABLE . (
uid=1000(onslaught) gid=1000(onslaught) groupes=4(adm),20(dialout),24(cdrom),46(plugdev),112(lpadmin),119(admin),120(sambashare),1000(onslaught),1002(cellusers),1004(cvs) INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 TINYINT( 4 ) NOT NULL ,
 SMALLINT( 5 ) NOT NULL ,
 INT( 10 ) NOT NULL ,
 TINYINT( 3 ) NOT NULL ,
 INT( 10 ) NOT NULL ,
 INT( 15 ) NOT NULL DEFAULT '0'
) ENGINE = MYISAM 
