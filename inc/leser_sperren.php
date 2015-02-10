<?php
session_start();
include("../class/mysql_class_pdo.php");	

			if (isset($_REQUEST['leserid'])) $leserid = intval($_REQUEST['leserid']);
								
			$db = new Database();
			if (intval($_REQUEST['leserid']) != '0' ) {
			$sperre = $db->leser_sperren($leserid);
			
			}
		echo $sperre ;
?>
