<?php
session_start();
include("../class/mysql_class_pdo.php");

			if ($_REQUEST['buchid'] and $_REQUEST['leserid']) { 
			
				
			 	$buchid = intval($_REQUEST['buchid']);
			 	$leserid = intval($_REQUEST['leserid']);
				$db = new Database();
				$ausgabe = $db->buch_vormerkung_entfernen($buchid, $leserid);
					
			} else { $ausgabe = "Fehler in buch_ausleihen.php" ;}
			
			echo $ausgabe ;
		
?>
