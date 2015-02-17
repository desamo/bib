<?php
session_start();

	//include ("../msd/msd_cron/crondump.pl?config=mysqldumper");
	if (session_destroy()) {
		header("location:../index.php");
	} else echo "Beim Logout ist ein Fehler aufgetreten!";
?>	