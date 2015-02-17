<!DOCTYPE html>
      
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"> 

<link rel="stylesheet" href="./css/stylesheet.css"/>
<link rel="stylesheet" href="./css/searchbar.css"/>
<link rel="stylesheet" href="./css/formular.css" />
<link rel="stylesheet" href="./css/container.css" />

<link rel="stylesheet" href="./css/colorbox/colorbox.css" />
<link rel="stylesheet" href="./css/jqueryui/jquery-ui.css" />
<link rel="stylesheet" href="./bower_components/font-awesome/css/font-awesome.min.css">
<link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900,300italic,400italic,700italic,900italic&subset=latin,latin-ext' rel='stylesheet' type='text/css'>

<link rel="icon" href="./img/favicon.ico" type="image/x-icon">

<script type="text/javascript" src="./bower_components/handlebars/handlebars.js"></script>
<script type="text/javascript" src="./bower_components/js-sha256/build/sha256.min.js"></script> 
<script type="text/javascript" src="./bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="./bower_components/colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="./bower_components/jquery.autocomplete/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="./bower_components/jqueryui/jquery-ui.js"></script> 
<script type="text/javascript" src="./js/functions.js"></script>


<title>Bibliothekensystem</title>
</head>
<?php 
	 require "vendor/autoload.php";
  
 ?>
 
	<header>
   		<div id="head-links">
			<p>Version 1.0 beta<br>
			2012-201x<br>
			written by Herr Jung ;)<br><br>
			<?php If (isset ($_SESSION['USER']))  echo "Benutzer: " .$_SESSION['USER']->username ;?></p>
		</div>
		<div id="head-mitte">	
			<h1>Büchereiverwaltung</h1>
			<h2><?php echo SCHULNAME ?></h2>
		</div>	
			<!-- <div id="head-rechts">
				<img src="<?php echo ROOT_DIR ."/img/buecher.png";?>" class="zentriert" alt="BIBLIOTHEK WGS" height="84" width="100"> 
			</div> -->
		</div>
		
	</header>	

