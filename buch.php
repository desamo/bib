<?php
session_start();
//	if (!isset($_SESSION['USER'])) {
//				header("location:index.php");
//				exit ;
//	}
include ("header.php");
IF (isset($_SESSION['LESERID'])) $LID = $_SESSION['LESERID'] ;
unset($_SESSION['LESERID']);

?>	

<html>
<head>
   
<script>
 jQuery(document).ready(function(){

	 $('#suchenach').focus() ;

	
	
	
	
	
});
 </script>
</head>
<body>

	<div id="main" class="clearfix">
	
	<?php include "./inc/navi.php"; ?>
	
	<div id=Info>
					<p>Dies ist die Startseite, hier kannst du Kinder oder Bücher suchen.
					<br><br>Wenn du ein Buch ausleihen willst, wähle zuerst das Kind aus. Gib dazu einfach den Vornamen ODER den Nachnamen des Kindes ein.
					<br><br>Danach klickst du auf "Suchen" oder drückst ENTER. Jetzt klickst du weiter auf das Kind. </p>
	 			</div>
						
			 	 	
			
				<div id="Inhalt" class=clearfix>
					<?php
						$page = 1;
						
						
						if (isset($_REQUEST['Buchid'])) $buchid = $_REQUEST['Buchid'];
						// if (isset($_REQUEST['Page'])) $page = $_REQUEST['Page'];
						
						
						
						
					?>
									
					<?php $db = new database ;
					
					
					ausgeben ($db->get_buch_by_id($buchid,"ALLE"));
					
					
						
						$rows =$db->leser_finder($buchid);
						//$anzahl = $db->rowCount() ;
						//echo "- Diese Buch wurde insgesamt $anzahl Mal ausgeliehen-";
						//foreach($rows as $row) {ausgeben($row);}
						show_results($rows) ;
					
					
						
						
						//show_results($rows) ;
										 
					

					
					//print_r ( $rows );
					
				
					
					
					?>
						
		
					
							
						
				</div>
				
				<?php 
				IF (isset($LID)) $_SESSION['LESERID'] = $LID;
				?>
				
			</div>
		
	
</body>
</html>