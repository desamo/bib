<?php
header("Content-Type: text/html; charset=utf-8");
session_start();

if (isset($_SESSION['USER'])) {
	header("location:start.php");
	exit ;
}

include ("header.php");

	if (isset($_REQUEST['username'])) $username = $_REQUEST['username']; else $username = "";
	if (isset($_REQUEST['password'])) $password = $_REQUEST['password']; else $password = "";
	if (isset($_REQUEST['reset'])) {
		$username = "";
		$password = "";
	}
?>

<html>
	<head>
		<script type="text/javascript">
			$(document).ready(function() {
				$( "#login_form" ).submit(function( event ) {
					event.preventDefault();
			
					
					var action = "/bib/api/login";
					var form_data = {
							username: $("#username").val(),
							password : sha256($("#password").val())
						};


					console.log(form_data.password);
					$.ajax({
						type: "GET",
						url: action,
						dataType: "json",
						data: form_data,

						success: function(response)
						{
							  console.log(response.username);
									$('#login_kasten').remove();
								if (response.username !== undefined) {
							 		
							 		$("#loginbox").append('<div id="login_kasten" class="success"><p class="success">Anmeldung erfolgreich.</p></div>');
									// $("#form").slideUp('slow', function() {(top.location.href ="./start.php");});
									top.location.href ="./start.php" ;
								} else {
								 	
								 	$("#loginbox").append('<div id="login_kasten" class="fail"><p class="fail">'+ response + '</p></div>'); 		
								}
							 	
								
						
						},
						error: function(jqXHR, textStatus, errorThrown){
							
							alert(jqXHR.responseText);
					}

					});
					return false;
				});
				
			});
		</script>
	</head>
	<body>
		<div id="main" class="clearfix">
			
				<div id="Info"> <p>Hier musst du dich einloggen um das Programm zu verwenden.
								Der Benutzername ist WGS1 und das Passwort auch.</p><?php $infotext_1 ?> </div>    
								
		
				<div id="loginbox" class="formular">
					<form name="form"id="login_form" action="./inc/login.php" method="post">
						<dl>	
							<dd><input class="form_normal" name='username' id='username' type='text'  placeholder='Benutzername' size='15' maxlength='20'></dd>
							<dd><input class="form_normal" type="password" id='password' name='password'  placeholder="Passwort"></dd>
													
						
						
						</dl>
						<div class="formular_buttons">
							<input name="login" id="login" type="submit" value="Login" class="button" ></input>
							<input name="reset" id="reset" type="reset" value="Reset" class="button" ></input>
						</div>
					</form>
			 	
		  
				</div>
				
				
		</div>  
		</body>
</html>