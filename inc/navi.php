<?php	 
	If (isset($_SESSION['USER']))	{ ?>
			<nav>
				<ul>
					<li><a href="start.php">Startseite </a></li>
					<li><a href="./inc/formular_buch.php" id="buch_eingabe">Neues Buch eingeben</a></li>
					<li><a href="./inc/formular_leser.php" id="leserlink">Neuen Leser anlegen</a><li>
					<?php If ($_SESSION['USER']->username=="admin") {?>
						<li><a href="neuer_user.php">Neuen User anlegen</a></li>
					<?php } ?>
					<li><a href="./inc/formular_user.php" id="user_edit">Passwort ändern</a></li>
					<!-- echo "<a href=\"datum.php\">Datum test</a><br> --> 
					<li><a href="#" id="reminder">Erinnerungen erstellen</a></li>
					<li><a href="./inc/logout.php">Ausloggen</a></li> 
				</ul>
			</nav>
	<?php }?>