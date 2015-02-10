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
	

			$("#clear").click(function() {parent.$.fn.colorbox.close();});

			$("#Titel").focus();

			$("#reset").click(function() {
				
				$("#Titel").val("");
				$("#Autor").val("");
				$("#ISBN").val("");
				$("#Jahr").val("");
				$("#Buchreihe").val("");
				$("#Schlagwörter").val("");
				$("#Anmerkung").val("");
				$("#Stufe").val("");
				$("#Alter").val("");
				$("#Verlag").val("");
				$("#Signatur").val("");
				$("#Beschreibung").val("");
			});
			
			
			function create_signature() {
				
				var $icon = $('#signature_icon');
				$icon.attr('src', "./img/not_ok.png" );
				var Signatur = $("#Signatur").val() ;
				if (Signatur.length > 1 ) {
					var str = Signatur.split(/-| |_/);
										
					if (str.length == 3 && str[2] != "" ) {
						$icon.attr('src','./img/loading.gif');
						
					
						$.ajax({
							type: "GET",
							url: "/bib/api/signature/" + Signatur,
							dataType : "json" ,				
							success: function(response)
							{
								if (response.result) {var bild ="./img/not_ok.png" ;} else {var bild ="./img/ok.png";}
								$icon.attr('src', bild );
							},
							error: function(jqXHR, textStatus, errorThrown){
		
		          				alert(jqXHR.responseText);
        						// console.log(jqXHR);					
							}
						});
					
							return false;
							
						}
				}
			};
			
							
			$("#Signatur").keyup(function(event){create_signature(); });

						

			$("#save").click(function() {
				
				
				var book = {
					Titel: $("#Titel").val(),
					Autor: $("#Autor").val(),
					Buchreihe: $("#Buchreihe").val(),
					Anmerkung: $("#Anmerkung").val(),
					Beschreibung: $("#Beschreibung").val(),
					Verlag: $("#Verlag").val(),
					Isbn: $("#ISBN").val(),
					Signatur: $("#Signatur").val(),
					Alter: $("#Alter").val(),
					Stufe: $("#Stufe").val(),
					Ausleihfrist: $("#Ausleihfrist").val(),
					Jahr: $("#Jahr").val(),
					Schlagwoerter: $("#Schlagwörter").val(),
					Id : $("#Titel").attr('data-buchid'),
					
				};
				book = JSON.stringify(book) ;
				
				$.ajax({
					type: 'POST',
					url: "/bib/api/book",
					dataType: "json",
					data: book,
					// success: function(response, textStatus, jqXHR){
					success: function(response){
						
						// console.log(response);
						alert(response.Titel + ' wurde gespeichert.');
						if (book.Id == 0) {
								$("#reset").click();	
								
							} else  {
								  parent.$.fn.colorbox.close();
								 // location.reload();
							}
						
							
					},
					error: function(jqXHR, textStatus, errorThrown){
		
		          		alert(jqXHR.responseText);
        				// console.log(jqXHR);					
					}
				});

			
			});

			$("#vorname").focus();
		
			


});
</script>

</head>
<body>




<?php 
	if (isset($_REQUEST['buchid'])) $buchid = intval($_REQUEST['buchid']); else $buchid = 0 ;
	
		
		
	if ($buchid != '0' ) {
		$db = new Database();
		$row = $db->get_buch_by_id($buchid);
		$Titel = $row->Titel ;
		$Autor = $row->Autor ;
		$ISBN = $row->ISBN;
		$Verlag = $row->Verlag;
		$Signatur = $row->Signatur;
		$Jahr = $row->Jahr;
		$Buchreihe = $row->Buchreihe;
		$Beschreibung= $row->Beschreibung;
		$Anmerkung = $row->Anmerkung;
		$Schlagwörter= $row->Schlagwörter;
		$Alter = $row->abAlter;
		$Stufe = $row->Stufe;
		$Ausleihfrist= $row->Ausleihfrist;
	} else {
		$Titel = "" ;
		$Autor = "";
		$ISBN = "";
		$Verlag = "";
		$Jahr = "";
		$Buchreihe = "";
		$Signatur = "";
		$Beschreibung = "";
		$Anmerkung = "";
		$Schlagwörter ="";
		$Alter = "";
		$Stufe = "";
		$Ausleihfrist = 14;
	}
	
	?>
						
	<div id="buch_formular" class="formular">
		<h2>Neues Buch eingeben</h2>
		<form name="neuesbuch" id="neuesbuch" action="./inc/buch_anlegen.php" method="post">
		
			<dl>          
				<dt>Titel: </dt>
				<dd><input id="Titel" data-buchid="<?php echo $buchid ;?>"value="<?php echo $Titel ;?>"  type="text" size="60" maxlength="100"></dd>
					
				<dt>ISBN: </dt>
				<dd> <input id="ISBN" value="<?php echo  $ISBN ; ?>" type=text size="17" maxlength="17"> </dd>
							
				<dt>Signatur:</dt>
				<dd><input  id="Signatur" value="<?php echo  $Signatur ; ?>" class="form_5" type=text size="8" maxlength="10"> <img id="signature_icon" src="./img/blank.png" title='Ok / not ok'></dd>
					
				<dt>Buchreihe: </dt>
				<dd><input id="Buchreihe" value="<?php echo $Buchreihe ;?>" type="text" size="60" maxlength="50"></dd>
						
				<dt>Autor: </dt>
				<dd><input id="Autor" value="<?php echo $Autor ; ?>" type="text" size="50" maxlength="50"></dd>
							
				<dt>Verlag:</dt>
				<dd><input name="Verlag" id=Verlag value="<?php echo $Verlag ; ?>" type="text" size="50" maxlength="50"></dd>
							
				<dt>Jahr: </dt>
				<dd><input id="Jahr" value="<?php echo $Jahr ; ?>" class="form_4" type="text" size="4" maxlength="4"></dd>
							
				<dt>Beschreibung: </dt>
				<dd> <textarea id="Beschreibung" cols="24" rows="3"><?php echo $Beschreibung ; ?> </textarea></dd>
							
				<dt>Anmerkung: </dt>
				<dd><textarea id="Anmerkung" cols="24" rows="3"><?php echo $Anmerkung ?></textarea></dd>
							
				<dt>Schlagwörter: </dt>
				<dd><textarea id="Schlagwörter" cols="24" rows="3"><?php echo $Schlagwörter ; ?> </textarea></dd>
							
				<dt>Alter:</dt>
				<dd><input name="Alter" id="Alter" type="text" class="form_4" value="<?php echo $Alter ; ?>" size="3" maxlength="3"></dd>
							
				<dt>Leserstufe: </dt>
				<dd><input id="Stufe" type="text" class="form_4" value="<?php echo $Stufe ; ?>" size="3" maxlength="3"></dd>
							
				<dt>Ausleihfrist: </dt>
				<dd><input id="Ausleihfrist" class="form_4" type="text" value="<?php echo $Ausleihfrist ; ?>" size="3" maxlength="3"> Tage</dd>
			</dl>	
				<div class="formular_buttons">
					<input id="save" type="button" value="Speichern" class="button">
					<input id="reset" type="reset" value="Reset" class="button">	
				</div>
		</form>
							
	</div>	



  

</body>
</html>