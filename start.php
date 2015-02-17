<?php
session_start();
//	if (!isset($_SESSION['USER'])) {
//				header("location:index.php");
//				exit ;
//	}
include ("header.php");
unset($_SESSION['LESERID']);
include ("./templates/template.tmpl");




?>	

<html>
<head>
   
<script>



 jQuery(document).ready(function(){
	
		
		$( "#suche" ).submit(function( event ) {
			 event.preventDefault();
			 $('.contain').remove();
			 ajax_suche(1)
		});
	

		 // var cat = $(document).getUrlParam("categorie");
		 // var term = $(document).getUrlParam("suchenach");
		
		 // if (term != null) {
			
			//  $('.contain').remove();
			  
			//  ajax_suche(1)

		 // }
		 $("#suchenach").putCursorAtEnd();
	 
			 
});

 
 </script>
</head>
<body>
	
	<div id="main">
	
	
	<div id="Info">
					<p>Dies ist die Startseite, hier kannst du Kinder oder Bücher suchen.
					<br><br>Wenn du ein Buch ausleihen willst, wähle zuerst das Kind aus. Gib dazu einfach den Vornamen ODER den Nachnamen des Kindes ein.
					<br><br>Danach klickst du auf "Suchen" oder drückst ENTER. Jetzt klickst du weiter auf das Kind. </p>
	 			</div>
						
		<?php include "./inc/navi.php";	?>	 	 		  
			
				<div id="Inhalt">
					
					<?php
						$suchtext = "";
						$Kategorie = "Name";
						$page = "1";
						
						if (isset($_REQUEST['suchenach'])) $suchtext = $_REQUEST['suchenach'];  
						if (isset($_REQUEST['categorie'])) $Kategorie = $_REQUEST['categorie'];
						if (isset($_REQUEST['Page'])) $page = $_REQUEST['Page'];
						
						$title = array("Name","Kuerzel","Klasse","Buch","Titel", "Autor", "Signatur", "Buchreihe", "ISBN", "Schlagwörter", "Verlag");
						
						
					?>
					<div class="searchbar">
						<form name="suche" id="suche" action="start.php">
						
						<select name="categorie" id="categorie" size="1" class="selects" onChange='this.options[this.selectedIndex].value'>
							<optgroup label=Schüler>	
								<?php 
									for($i=0;$i<11;$i++){ 
										if ($i == 3) { ?> </optgroup><optgroup label="Bücher"> <?php } ?>
										<OPTION	<?php if($title[$i] == $Kategorie) echo " selected" ;?> > <?php echo $title[$i] ; ?></OPTION> 
									<?php }	?>
							</optgroup>		
    					</select>
	                   
	                   
	                   <input name=suchenach class="autocomplete" id="suchenach" type=text value="<?php echo $suchtext ?>"/>
	                  	<!-- <input id="suchen"type="submit"><i class="fa fa-search"></i> </> -->
	                  	<button id="suchen"><i class="fa fa-search"></i></button></>

					   
						</form>
					
					</div>	
				

					
					<?php 
					 
						
					
						if ($suchtext != "") {
						
					 
						   	// Get cURL resource
							$curl = curl_init();
							// Set some options - we are passing in a useragent too here
							$suchtext = str_replace(" ","+",$suchtext) ;
							curl_setopt_array($curl, array(
							    CURLOPT_RETURNTRANSFER => 1,
							    CURLOPT_URL => "http://localhost/bib/api/search?cat=$Kategorie&page=$page&term=$suchtext"
							   
							));
							// Send the request & save response to $resp
							$resp = curl_exec($curl);
							// Close request to clear up some resources
							curl_close($curl);

							$result = json_decode($resp) ;
							show_results($result) ;
							
						}

						
					
					 ?>
					 

						
		
					
							
						
				</div>
				
				
				
			</div>
		
	
</body>
</html>