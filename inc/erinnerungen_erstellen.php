<?php
session_start();

if (!isset($_SESSION['USER'])) {
				header("location:start.php");
				exit ;
	}

include("../vendor/autoload.php");

	
	$datum = strtotime("-1 week");
	$datum = date("Y-m-d", $datum);  
	
	$sql	 =	"SELECT a.Ausleihdatum,
						a.Ausleiher,
						a.Rückgabedatum,
						a.buchid,
						a.Vormerken,
						a.Verlängern,
						a.Mahnstufe,
						b.ID, 
						b.Titel, 
						b.Verlag, 
						b.Anmerkung,
						b.Autor,
						b.Beschreibung,
						b.Buchreihe,
						b.Schlagwörter,
						b.Anmerkung,
						b.Inhalt,
						b.Signatur,
						b.ISBN, 
						b.Jahr,
						b.info,
						c.ID,
						c.Vorname,
						c.Name,
						c.Klasse,
						c.Geschlecht,
						c.Gruppe
					FROM verliehen as a 
					LEFT JOIN buecher as b
					ON a.buchid = b.ID LEFT JOIN leser as c ON a.Ausleiher = c.ID WHERE a.Rückgabedatum < :datum AND c.Gruppe <> :2 ORDER BY Klasse, Ausleiher asc" ;

	$db = new database() ;
	
	$db->query($sql);
	$db->bind(":datum", $datum) ;
	$db->bind(":2", "2") ;
	$rows = $db->resultset();

	// echo "<pre>";
	//   	print_r ($rows);
 //  	echo "</pre>";

	$page = 1 ;
	$Ausleiher = 0;
	$timestamp = time();
	$datum = date("Y-m-d",$timestamp);
	$datum1 = strtotime($datum);
	$datum2 = date("d.m.Y", $datum1) ;
	$datum1 = date("Y-m-d", $datum1) ;
	$leserid= 0;
	if (isset($_REQUEST['Leserid'])) $leserid = $_REQUEST['Leserid'];  
	
	
	$counter = 1;
	$counter1 = 1;
	

	$PHPWord = new PHPWord();
	$PHPWord->setDefaultFontName('Comic Sans MS');
	$PHPWord->setDefaultFontSize(12);
	
	$fontStyleBig = array('size'=> 18,'bold'=>false);
	$fontStyleSmallItalic = array('size'=> 9,'bold'=>false,'italic'=>true);
	$fontStyleBigBold = array('size'=> 18,'bold'=>true);
	$fontStyleBold = array('bold'=>true);
	$fontStyleItalic = array('italic'=>true);
	$PHPWord->addParagraphStyle('pStyle', array('align'=>'right','spaceAfter'=>100));
	$sectionStyle = array('orientation' => null,
			    'marginLeft' => 1134,
			    'marginRight' => 1134,
			    'marginTop' => 793,
			    'marginBottom' => 454);

	$section = $PHPWord->createSection($sectionStyle);
	
	
	
	foreach ($rows as $row) {
	 	
		
		If ($row->Ausleiher != $Ausleiher ){
			if ($Ausleiher != 0) {
			$section->addTextBreak(4 - $counter);
														
				$textrun = $section->createTextRun();
				$textrun->addText(utf8_decode('Bitte bringe das Buch / die Bücher innerhalb der '));
				$textrun->addText(utf8_decode('nächsten 3 Tage '), $fontStyleBold);
				$textrun->addText(utf8_decode('zurück, damit auch andere Kinder sich das Buch ausleihen können! '));
				
				$textrun = $section->createTextRun();
				$textrun->addText(utf8_decode('Wenn sich kein anderes Kind das Buch reserviert hat, kannst du die Ausleihzeit noch
einmal um eine Woche verlängern - das müssen wir aber unbedingt wissen! '));
				
				$section->addText(utf8_decode('Dein Bücherei-Team	Leihgestern, den '. (date("d.m.y" ))));
				$textrun = $section->createTextRun();
				$textrun->addText(utf8_decode('Wenn du dich nicht innerhalb der nächsten 7 Tage bei uns meldest und wir dich '));
				$textrun->addText(utf8_decode('nochmal Mal erinnern '),$fontStyleBold);
				$textrun->addText(utf8_decode('müssen, dann müssen wir für jedes überfällige Buch '));
				$textrun->addText(utf8_decode('0,50 EUR Mahngebühren '),$fontStyleBold);
				$textrun->addText(utf8_decode('verlangen!'));
				$counter1++;
				//$textrun = $section->createTextRun();
				
				if ($counter1 % 2 == 0) {$section->addText('------------------------------------------------------------------------------------------------');} 
			
			}
			$Ausleiher = $row->Ausleiher;
			
			$counter = 1;
			$add =" ";
			if ($row->Geschlecht == "m") { $add = "r ";}
			$mahnstufe = $row->Mahnstufe + 1 ;
			if (empty($row->Klasse)) {$Klasse = "__" ;} else {$Klasse = $row->Klasse ;}
			$section->addText(utf8_decode('Erinnerung	   			 	  '. $Klasse ) ,$fontStyleBigBold,'pStyle' );
			$section->addText(utf8_decode('Liebe'.$add . $row->Vorname . " " . $row->Name .","),$fontStyleBold);		
			$section->addText(utf8_decode('du hast dir in der Schülerbücherei folgende Bücher ausgeliehen:'));		
			
		}
	    $rdatum = strtotime($row->Rückgabedatum);
		$rdatum = date("d.m.y",$rdatum );
		$sdatum = strtotime("-3 week");
		$sdatum = date("Y-m-d", $sdatum);

		 if ($row->Rückgabedatum < $sdatum) {$sperre = $db->leser_sperren($row->Ausleiher,"SICHER_SPERREN") ;}  
		//if ($row->Rückgabedatum < $sdatum) $sperre1 = " wird gesperrt";  else $sperre1 = " ist ok!" ;
		//echo $row->Vorname . " " . $row->Titel . " " . $row->Rückgabedatum . " = " . $sdatum . $sperre1 ."<br>";
		if ($row->Buchreihe =="") $Titel = $row->Titel ; else $Titel = $row->Buchreihe . " - " . $row->Titel ;
	    $Titel = substr($Titel,0,58);
				
		$textrun = $section->createTextRun();
		$textrun->addText(utf8_decode('   '.$counter . ". " . $Titel), $fontStyleBold);
		$textrun->addText(utf8_decode('    bis zum '.$rdatum ),$fontStyleSmallItalic);
		$counter++;
	
	}
		
		$section->addTextBreak(4 - $counter);
														
				$textrun = $section->createTextRun();
				$textrun->addText(utf8_decode('Bitte bringe das Buch / die Bücher innerhalb der '));
				$textrun->addText(utf8_decode('nächsten 3 Tage '), $fontStyleBold);
				$textrun->addText(utf8_decode('zurück, damit auch andere Kinder sich das Buch ausleihen können! '));
				
				$textrun = $section->createTextRun();
				$textrun->addText(utf8_decode('Wenn sich kein anderes Kind das Buch reserviert hat, kannst du die Ausleihzeit noch
einmal um eine Woche verlängern - das müssen wir aber unbedingt wissen! '));
				
				$section->addText(utf8_decode('Dein Bücherei-Team	Leihgestern, den '. (date("d.m.y" ))));
				$textrun = $section->createTextRun();
				$textrun->addText(utf8_decode('Wenn du dich nicht innerhalb der nächsten 7 Tage bei uns meldest und wir dich '));
				$textrun->addText(utf8_decode('nochmal Mal erinnern '),$fontStyleBold);
				$textrun->addText(utf8_decode('müssen, dann müssen wir für jedes überfällige Buch '));
				$textrun->addText(utf8_decode('0,50 EUR Mahngebühren '),$fontStyleBold);
				$textrun->addText(utf8_decode('verlangen!'));
				$counter1++;
				//$textrun = $section->createTextRun();
				
				if ($counter1 % 2 == 0) {$section->addText('------------------------------------------------------------------------------------------------');} 
	
	
				
// At least write the document to webspace:
$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');

$leser = $db->get_leser_by_id($leserid);
if ($leserid != 0 ) $filename = $leser->Name . "-" . $leser->Vorname . "-". (date("y-m-d")) . ".docx" ; else $filename = "mahnungen_alle". "-". (date("y-m-d")) . ".docx" ;


$objWriter->save("../Mahnungen/$filename");

echo "./Mahnungen/".$filename ;
?>
