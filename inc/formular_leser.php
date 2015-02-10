<?php
session_start();
if (!isset($_SESSION['USER'])) {
	header("location:index.php");
	exit ;
}
//include ("header.php");
require_once ("../class/mysql_class_pdo.php");
?>
<html>
<head>
	<!-- <link rel="stylesheet" type="text/css" href="../css/stylesheet.css">
	<link rel="stylesheet" type="text/css" href="../css/formular.css"> -->
<title>Bibliothek WGS</title>
<script type="text/javascript">

jQuery(document).ready(function(){
	
		$("#reset").click(function() {parent.$.fn.colorbox.close();});

			
			
							
		$("#name").keyup(function(event){
		
			if ($("#name").attr('data-leserid') == 0){

				var name = $('#name').val();
				var $kuerzel = $("#kuerzel");
				$kuerzel.val("");
				if (name.length > 2 ) {
					$kuerzel.val(name.substring(0,3));					
					var kuerzel = $("#kuerzel").val();

					
					$.ajax({
						type: "GET",
						url: "/bib/api/kuerzel/"+ kuerzel ,
						
												
						success: function(response)	{ 
								$kuerzel.val(response);			
							
							 },
						error: function(jqXHR, textStatus, errorThrown){ alert(jqXHR.responseText); }

					});
				}
			}
		});
		
		$("#save").click(function() {
				
			var leser = {
						id : $("#name").attr('data-leserid'),
						name: $("#name").val(),
						vorname: $("#vorname").val(),
						strasse: $("#strasse").val(),
						hausnr: $("#hausnr").val(),
						kuerzel: $("#kuerzel").val(),
						geburtsjahr: $("#geburtsjahr").val(),
						klasse: $("#klasse").val(),
						gruppe: $("#img_gruppe").attr("data-wert"),
						geschlecht: $("input[name='geschlecht']:checked").val()
					
						};
				leser_json = JSON.stringify(leser) ;
						
				$.ajax({
					type: "POST",
					url: "/bib/api/leser",
					dataType: "json",
					data: leser_json,
					success: function(response)	{
							console.log(response);
							
								alert(response.vorname + " " + response.name + " wurde gespeichert." + leser.id);
								
								if (leser.id == 0) {
									$("#name").val("");
									$("#vorname").val("");
									$("#strasse").val("");
									$("#hausnr").val("");
									$("#kuerzel").val("");
									$("#geburtsjahr").val("");
									$("#klasse").val("");
									$("#img_gruppe").attr("data-wert", "1")
								} else {
									  parent.$.fn.colorbox.close();
									// location.reload();
								}
							
					},
					error: function(jqXHR, textStatus, errorThrown){
		
		          				alert(jqXHR.responseText);
        				
					}
											
					
				});
						
				
		});
		
		$("#img_gruppe").click(function() {
		
			var gruppe = $(this).attr( 'data-wert' );
						
						
				if (gruppe == "1") {
					$(this).attr('src' , "./img/gruppe_2.png") ;
					$(this).attr( 'data-wert', '2' );
				} else if (gruppe == "2") {	
					$(this).attr('src' , "./img/gruppe_1.png") ;
					$(this).attr( 'data-wert', '1' ) ;
				}
						
		
		});

		$("#vorname").focus();
				

			
			
			
});
</script>

</head>
<body>




<?php 
	
	
	$db = new Database();
	if (isset($_REQUEST['leserid'])) {
		$Leserid = $_REQUEST['leserid'];
		$leser = $db->get_leser_by_id($Leserid);
		$name = $leser->Name;
		$Vorname = $leser->Vorname;
		$Ausweiscode =$leser->Ausweiscode;
		$Strasse = $leser->Strasse;
		$geschlecht = $leser->Geschlecht;
		$Hausnr = $leser->Hausnr;
		$Geburtsjahr = $leser->Geburtsjahr;
		$Klasse = $leser->Klasse;
		$gruppe = $leser->Gruppe ;
		$ueberschrift = "Leserdaten ändern";
	} else {
		$Leserid = 0;
		$name = "";
		$Vorname = "";
		$Ausweiscode = "";
		$Strasse = "";
		$geschlecht = "m";
		$Hausnr = "";
		$Geburtsjahr = "";
		$Klasse = "";
		$gruppe = 1 ;
		$ueberschrift ="Neuen Leser anlegen";
	}
	if ($gruppe == 2) $bild = "./img/gruppe_2.png" ; else if ($gruppe == 1) $bild = "./img/gruppe_1.png" ; else {
		$gruppe = 1 ;
		$bild = "./img/gruppe_1.png" ;
	}
	?>
	
		
	
	<div id="leser_formular" class="formular">
											
		<form method="post" name="form" id="form" action="./inc/leser_edit.php" >
			<dl>			  
	           	<dt>Vorname:</dt>
				<dd><input id="vorname" value="<?php echo $Vorname ;?>" type="text" /></dd>
				<dt>Name:</dt>
				<dd><input id="name" data-leserid="<?php echo $Leserid ;?>" value="<?php echo $name ;?>" type="text" /></dd>
				<dt>Strasse:</dt>
				<dd><input id="strasse" value="<?php echo $Strasse ;?>" type="text" /> 
					<label>Hausnr:</label><input id="hausnr" type="text" value="<?php echo $Hausnr ;?>" class="form_4" size="4" maxlength="4" /></dd>
				<dt>Geburtsjahr:</dt>
				<dd><input id="geburtsjahr" type="text" value="<?php echo $Geburtsjahr ;?>" class="form_5" size="5" maxlength="5" />
					<label>Klasse:</label><input  id="klasse" type="text" value="<?php echo $Klasse ;?>" class="form_4"/> 
					<label>Kürzel:</label><input id="kuerzel" type="text" value="<?php echo $Ausweiscode ;?>" class="form_5" readonly />
				</dd>
				
			</dl>
			<div class="radio">  
    			<input name="geschlecht" id="geschlecht_m" type="radio" <?php if ($geschlecht == "m") {echo "checked"; }?> value="m" />
    			<label for="geschlecht_m">Junge</label>  
    			<input name="geschlecht" id="geschlecht_w" type="radio"<?php if ($geschlecht == "w") {echo "checked"; }?> value="w" />
    			<label for="geschlecht_w">Mädchen</label>  
    			<label id="ohne_hintergrund">Schüler / Lehrer </label>
				<img id="img_gruppe" src="<?php echo $bild ; ?>" data-wert="<?php echo $gruppe; ?>" title='Schüler / Lehrer'>
			</div>  
				<div class="formular_buttons">		     
					<input id="save" type="button" value="Speichern" class="button"></>
					<input id="reset" type="reset" value="Reset" class="button"></>
				</div>	
			
		</form>

	</div>
</body>
</html>
