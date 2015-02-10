<?php
session_start();
//	if (!isset($_SESSION['USER'])) {
//				header("location:index.php");
//				exit ;
//	}
if (isset($_GET['Leserid'])) $_SESSION['LESERID'] = $_GET['Leserid'];
include ("header.php");


?>	

<html>
<head>
   
<script>
 
 jQuery(document).ready(function(){
 	var leserid = $(document).getUrlParam("Leserid");
 	
 	$( "#suche" ).submit(function( event ) {
			event.preventDefault();
			// $('.buch').remove();
			ajax_suche(1)
		});

		show_leser(leserid)

		$("#suchenach").putCursorAtEnd();
	
	
});
 </script>
</head>
<body>
 
	<div id="main">
	
	<?php 
	include "./templates/template.tmpl";
	include "./inc/navi.php";?>
	<div id="Info">
					<p>Dies ist die Startseite, hier kannst du Kinder oder Bücher suchen.
					<br><br>Wenn du ein Buch ausleihen willst, wähle zuerst das Kind aus. Gib dazu einfach den Vornamen ODER den Nachnamen des Kindes ein.
					<br><br>Danach klickst du auf "Suchen" oder drückst ENTER. Jetzt klickst du weiter auf das Kind. </p>
	 			</div>
						
			 	 		  
			
				<div id="Inhalt">
					<?php
						$suchtext = "";
						$Kategorie = "Name";
				//		$page = 1; 
						
						if (isset($_REQUEST['suchenach'])) $suchtext = $_REQUEST['suchenach'];
						
						$Leserid = $_SESSION['LESERID'] ;
						
						if (isset($_REQUEST['categorie'])) $Kategorie = $_REQUEST['categorie'];
						if (isset($_REQUEST['Page'])) $page = $_REQUEST['Page'];
						
						$title = array("Buch","Titel", "Autor", "Signatur", "Buchreihe", "ISBN", "Schlagwörter", "Verlag");
						
						
					?>
					<div class="searchbar">
						<form name="suche" id="suche" action="leser.php">
						
						<select name="categorie" id="categorie" size=1 class=selects onChange='this.options[this.selectedIndex].value' style='width: 150px'>
								
								<?php 
									for($i=0;$i<8;$i++){ 
										?>
										<OPTION	<?php if($title[$i] == $Kategorie) echo " selected" ;?> > <?php echo $title[$i] ; ?></OPTION> 
									<?php }	?>
									
    					</select>
	                   
	                   
	                   <input name="suchenach" class="autocomplete"  id="suchenach" type="text" value="<?php echo $suchtext ?>"  >
					   <!-- <a href="./leser.php?Leserid=<?php echo $Leserid ;?>&Feld=document.suche.Feld.value&suchenach=document.suchenach.value"><button id=suchen><i class="fa fa-search"></i></button></a> -->
						
						<button id="suchen"><i class="fa fa-search"></i></button></>
						</form>
					
					
					
					</div>	
					
					
					<?php 
					// $db = new database ;
					
					
					// ausgeben ($db->get_leser_by_id($Leserid));
					
					// if ($suchtext == "") {
						
					// 	$rows =$db->buch_finder($Leserid);
					// 	$anzahl = $db->rowCount() ;
						?>
					<!--	<a class='Trefferanzeige'>-Ausgeliehene Bücher <?php //echo  $anzahl ." -</a>"; 
					// 	foreach($rows as $row) {ausgeben($row);}
					// 	//show_results($rows) ;
					
					// } else {
					// 	$query = create_sql_string($Kategorie);
					// 	$result = $db->query($query);
					// 	if ($Kategorie == "Name" or $Kategorie == "Buch") $suchtext = $db->create_full_text_search_string($suchtext) ; else $suchtext = $suchtext . "%" ;
						 				
					// 	$db->bind(':suchparameter', $suchtext) ;
						
					// 	$rows = $db->resultset() ;
					// 	show_results($rows) ;
										 
					// }

					
					//print_r ( $rows );
					
				
					
					
					?>
						
		
					
							
						
			<!-- 	</div> -->
				
				
				
			</div>
		
	 
</body>
</html>