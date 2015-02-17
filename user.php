<?php
session_start();
	if (!isset($_SESSION['USER'])) {
				header("location:index.php");
				exit ;
	}
?>
<html>
<head>
<title>Bibliothek WGS</title>
<script type="text/javascript">

$(document).ready(function() {
	
	$("#passold").focus();
	$("#save").click(function() {

		
		var user = {
		
			username: username,
			email: $("#email").val(),
			old_password: Sha256.hash($("#passold").val()),
			new_password1: Sha256.hash($("#pass2").val()),
			new_password2: Sha256.hash($("#pass1").val()),
			
		};
		console.log(user) ;
	 	$.ajax({
	 		type: "POST",
	 		url: "/bib/api/user",
	 		data: JSON.stringify(user),
	 		success: function(response)
	 		{
				
	 				alert(response);
	 				$("#passold").val("");
	 				 parent.$.fn.colorbox.close();
								
	 		}
	 	});
		
	
	 });
	
});
</script>
</head>
<body> 
	
		
				
				
					<?php			
							$email =""; 
							$username = $_SESSION['USER']->username ;
							if (isset($_SESSION['USER']->email)) $email = $_SESSION['USER']->email;
							if (isset($_REQUEST['email'])) $email = trim($_REQUEST['email']); 
							if (isset($_REQUEST['pass1'])) $pass1 = trim($_REQUEST['pass1']);
							if (isset($_REQUEST['pass2'])) $pass2 = trim($_REQUEST['pass2']);
							if (isset($_REQUEST['passold'])) $passold = trim($_REQUEST['passold']); else $passold = "";
					?>
						

						<div id="user_formular" class="formular">
							
							<h2>Benutzer-Daten ändern</h2>
							<form name="form" id="form" action="./inc/user_edit.php" method="post">	
								<dl>          
				    				<dt>E-Mail Adresse: </dt>
				    				<dd><input name="email" id="email" value="<?php echo $email ; ?>" type="text" size="30" maxlength="40"></dd>
				              		<dt>Altes Passwort: </dt>
				             		<dd><input name="passold" id="passold" value="" type="password" size="30" maxlength="10"></dd>
				    				<dt>Neues Passwort:</dt>
				    				<dd><input name="pass1" id="pass1" value="" type="password" size="30" maxlength="10"></dd>
				    				<dt>Passwort wiederholen: </dt>
				    				<dd><input name="pass2" id="pass2" value="" type="password" size="30" maxlength="10"></dd>
				    			</dl> 
				    			<div class="formular_buttons">
				    				<input name="save" id="save" type="button" value="Speichern" class="button">
									<input name="Reset" id="reset" type="reset" value="Reset" class="button">
	                 			</div>		
							</form>
				
							
						</div>
			
 
 <script type="text/javascript">
var username =  '<?php echo $username; ?>' ;
</script> 
 

</body>
</html>