<?php

	function show_buttons($row) {
		
		if (isset ($row->Ausleiher)) { 
			
			if($row->Ausleiher == $_SESSION['LESERID']) {
				?>	<a class="back_button" data-buchid="<?php echo $row->ID ;?>"><button class="button">Zurückgeben</button></a> 
					<a class="ver_button" data-buchid="<?php echo $row->ID ;?>"><button class="button">Verlängern</button></a>
				<?php
			} else { 
						if(!isset ($row->Vormerker) OR (isset($row->Vormerker) AND ($row->Vormerker != $_SESSION['LESERID'])))  {
							?><a class="vormerk_button" data-buchid="<?php echo $row->ID ;?>" data-leserid="<?php echo $_SESSION['LESERID']; ?>"><button class=button>Vormerken</button></a> <?php
						} else { 
						?>	<a class="del_vormerken" data-leserid="<?php echo $_SESSION['LESERID']; ?> title="Vorgemerkt von <?php echo $row->Name_des_Vormerkers ." ". $row->Vorname_des_Vormerkers ; ?>" data-buchid="<?php echo $row->ID ;?>""><button class="button">Vormerkung löschen</button></a> <?php ; }
						}
		} else { 
			
			?>	<a class="ausleih_button" data-buchid="<?php echo $row->ID ;?>" data-leserid="<?php echo $_SESSION['LESERID']; ?>"><button class=button>Ausleihen</button></a> <?php
			
			
		}
		
		
		
		
	}
	
	
	function ausgeben($row) {
	
		
	  	// echo "<pre>";
	  	// print_r ($row);
	  	// echo "</pre>";
			
		
			if (isset ($row->Titel)) {            // Abfrage ob es sich um ein Buch handelt
		
				// ################### Hier beginnt die Buch Ausgabe ###########################
				
				if (trim($row->info)!= "") $bildinfo = IMG_INFO ; else $bildinfo = IMG_INFO_LEER ;?>
										<div class="contain">					
										 		
										 		<?php if ($row->Buchreihe =="") $Titel = $row->Titel; else $Titel = $row->Buchreihe . " - " . $row->Titel ;
										 		if (isset($row->Ausleiher)) $bild = "./img/rot.png"; else $bild = "./img/grün.png";
										 		
										 		
										 		?>
										 		<dl> 
										 		 <dt><a class="buchdata"> <?php echo $Titel .  " (" . $row->Jahr .")" ;?></a></dt>
										 				<dd><a class="label">von </a><a class="buchdata"><?php echo $row->Autor ;?></a></dd>
										 				<dd><a class="label">Verlag: </a><a class="buchdata"> <?php echo $row->Verlag . ", ".  $row->Beschreibung ;?></a></dd>
										 				<dd><a class="label">ISBN: </a><a class="buchdata"><?php echo $row->ISBN ;?></a><a class="label"> Signatur:</a><a class="buchdata"> <?php echo $row->Signatur ; ?> </a></dd>
										 				
										 		</dl>
										 		<?php 
										 		if (isset( $row->Ausleiher)){
										 			$datum1 = date("d.m.y", strtotime($row->Rückgabedatum));
													$datum2 = date("d.m.y", strtotime($row->Ausleihdatum));
												
																						
													// Bei der Leserseite wird der Name des Lesers bei "Ausgeliehen von " nicht angezeigt, bei normaler suche schon												
													$str ="<p>Ausgeliehen am $datum2 bis zum $datum1" ; 
													
													if (isset ($row->Vorname)) { $str = $str . " von <a href=\"./leser.php?Leserid=$row->Ausleiher\">". $row->Vorname ." " . $row->Name ;}
													$str = $str . "</a></p>";
													// Bei der Leserseite wird der Name des Lesers bei "Ausgeliehen von " nicht angezeigt, bei normaler suche schon
													
													
										 		} else $str = "<p>Status: nicht ausgeliehen</p>" ;
	
										 		echo $str;
										 		?> <div id=buttons> <?php 
										 		If (isset ($_SESSION['LESERID'])) { show_buttons($row) ;}
										 		
										 		?>	</div>			
																	 		
										  
										  	<div class="symbols">
												<a class="book_symbol" href="./inc/showbox_buch.php?buchid=<?php echo $row->ID ; ?>"><img src="./img/buch.png" title='Buch ändern'></a>
											    <a class="info_symbol" href="./inc/showbox_info.php?buchid=<?php echo $row->ID ; ?>" title="Info über <?php echo $row->Titel ;?>"><img id="<?php echo $row->ID ;?>" src="<?php echo $bildinfo ;?>"></a>
											    <a href="./buch.php?Buchid=<?php echo $row->ID ; ?>"><img src=<?php echo $bild ; ?> title='Hier kannst du sehen wer da buch bereits ausgeliehen hatte'></a>	
											</div>
										</div>
			<?php } else { 	
				
				// ################### Hier beginnt die Leser Ausgabe ###########################
							
					if (trim($row->info)!= "") $bildinfo = IMG_INFO ; else $bildinfo = IMG_INFO_LEER ;
					if ($row->gesperrt == 1) $bildsperre = IMG_GESPERRT ; else $bildsperre = IMG_NICHT_GESPERRT ;
					if ($row->Gruppe == 2) $bild_gruppe = IMG_LEHRER ; else $bild_gruppe = IMG_KIND ;
				
			?>
								<div class="contain">					
								 	
								 		<dl>
											<dt><?php if (empty($row->Name) ) {?><a class=lesername>"Ehemaliger Schüler oder Schülerrin ;)"</a><?php  }	else { ?><a class=lesername href="./leser.php?Leserid=<?php echo $row->ID ;?>"><?php echo $row->Name .', ' . $row->Vorname ;?></a><?php } ?></dt>
											<dd><?php if (!empty($row->Strasse) ) { ?><a class=leserdata> <?php echo $row->Strasse . " " . $row->Hausnr ;?> </a> <?php }?></dd> 
											<dd><?php if (!empty($row->Name) ) { ?><a class=label>Kürzel: </a><a class=leserdata><?php echo $row->Ausweiscode;?> </a><a class=label>Klasse: </a><a class=leserdata><?php echo $row->Klasse; ?></a></dd> <?php }?>
									    </dl>
									    
										<?php 
										if (isset( $row->Ausleiher)){
											$datum1 = date("d.m.y", strtotime($row->Rückgabedatum));
											$datum2 = date("d.m.y", strtotime($row->Ausleihdatum));
											echo "<p> Ausgeliehen am $datum2 bis zum $datum1 </p>" ;
										} ?> 
									 
										<?php 
										if (!empty($row->Name) ) { ?>
											<div class="symbols">
												<a class="leser_symbol"  href="./inc/showbox_leser.php?leserid=<?php echo $row->ID ?>"><img src="<?php echo $bild_gruppe;?>"></a>
												<a class="info_symbol" href="./inc/showbox_info.php?leserid=<?php echo $row->ID ;?>" title="Info über <?php echo $row->Vorname ." " . $row->Name ;?>"><img id="<?php echo $row->ID ;?>" src="<?php echo $bildinfo ;?>"></a>
												<a class="schloss_symbol" data-leserid="<?php echo $row->ID ;?>"><img id="info<?php echo $row->ID ;?>" title="Leser sperren/entsperren" src=<?php echo $bildsperre;?>></a>
											</div>
								</div>
									<?php }	
									
					
				} 
	}


function create_sql_string ($categorie) {
	$buch_sql ="SELECT DISTINCT
				b.Ausleihdatum,
		    	b.Ausleiher,
		    	b.Rückgabedatum,
		    	b.buchid,
		    	b.Vormerken,
		    	b.Verlängern,
		    	b.Mahnstufe,
   				a.ID,
		    	a.Titel,
		    	a.Verlag,
		    	a.Anmerkung,
		    	a.Autor,
		    	a.Buchreihe,
		    	a.Inhalt,
		    	a.Beschreibung,
		    	a.Schlagwörter,
		    	a.Signatur,
		    	a.ISBN,
		    	a.Jahr,
		    	a.info,
		    	c.Name,
				c.Vorname,
				c.ID as Leserid,
   				d.buchid as vor_buchid,
   				d.Vormerker,
   				e.Name as Name_des_Vormerkers,
   				e.Vorname as Vorname_des_Vormerkers,
   				e.ID as Vormerkid
  				FROM buecher as a
	   				LEFT JOIN verliehen as b
	 			  	ON b.buchid = a.ID LEFT JOIN leser as c ON b.Ausleiher = c.ID LEFT JOIN vorgemerkt as d ON d.buchid = b.buchid LEFT JOIN leser as e ON d.Vormerker = e.ID
   				WHERE ";
$buch_sqll ="SELECT 
    	a.ID,
    	a.Titel,
    	a.Verlag,
    	a.Anmerkung,
    	a.Autor,
    	a.Buchreihe,
    	a.Inhalt,
    	a.Beschreibung,
    	a.Schlagwörter,
    	a.Signatur,
    	a.ISBN,
    	a.Jahr,
    	a.info,
		b.Ausleihdatum,
    	b.Ausleiher,
    	b.Rückgabedatum,
    	b.buchid,
    	b.Vormerken,
    	b.Verlängern,
    	b.Mahnstufe,
    	c.Name,
		c.Vorname,
		c.ID as Leserid		 
			FROM buecher as a LEFT JOIN verliehen as b ON a.ID = b.buchid LEFT JOIN leser as c ON b.Ausleiher = c.ID WHERE ";



	switch ($categorie) {
    case "Name":
        $sql_str = "SELECT * FROM leser WHERE MATCH(Name, Vorname) AGAINST(:suchparameter IN BOOLEAN MODE)";
        break;
    case "Kuerzel":
    	$sql_str = "SELECT * FROM leser WHERE Ausweiscode LIKE :suchparameter ORDER by Ausweiscode asc" ;
        break;
    case "Klasse":
    	$sql_str = "SELECT * FROM leser WHERE Klasse LIKE :suchparameter ORDER by Name asc";
        break;
        
    case "Buch":
    	$sql_str = $buch_sql . "MATCH(Titel, Autor, Verlag, Buchreihe, Schlagwörter) AGAINST(:suchparameter IN BOOLEAN MODE) ORDER BY Titel asc";
      	break;
    case "Titel":
    	$sql_str = $buch_sql . "$categorie LIKE :suchparameter ORDER BY Titel asc";
       	break;
    case "Autor":
    	 $sql_str = $buch_sql . "$categorie LIKE :suchparameter ORDER BY Titel asc";
    	break;
    case "Signatur":
    	$sql_str = $buch_sql . "$categorie LIKE :suchparameter ORDER BY Titel asc";
    	break;
    case "Buchreihe":
    	$sql_str = $buch_sql . "$categorie LIKE :suchparameter ORDER BY Titel asc";
    	break;
    case "ISBN":
    	$sql_str = $buch_sql . "$categorie LIKE :suchparameter ORDER BY Titel asc";
    	break;
    case "Schlagwörter":
    	$sql_str = $buch_sql . "$categorie LIKE :suchparameter ORDER BY Titel asc";
    	break;
    case "Verlag":
    	$sql_str = $buch_sql . "$categorie LIKE :suchparameter ORDER BY Titel asc";
    	break;
    }

  
    //$sql_str = $sql_str . " LIMIT $a, $b" ;
    
    return $sql_str;   	
}
	function show_results ($rows) {
		
		$anzahl = $rows->info->anzahl;
		$page = Page_number();
								

						$html = new simple_html_dom();
						// $html->load_file('./templates/template.tmpl');
						$html->load_file('./inc/template.php');
						// $html->load_file('./start.php');
						$template_leser = $html->find('div.leser',0);//get value plaintext each html
						$template_buch = $html->find('div.buch',0);//get value plaintext each html
					 

						// $template_leser->plaintext;
						 // echo  $template_buch ;

						$phpStr = LightnCandy::compile($template_leser, Array( 'flags' => LightnCandy::FLAG_ERROR_LOG | LightnCandy::FLAG_STANDALONE | LightnCandy::FLAG_RENDER_DEBUG));  // compiled PHP code in $phpStr
						
						$php_inc = "my.php";
						file_put_contents($php_inc, $phpStr);
						$renderer = include($php_inc);


													
								if ($anzahl > ANZAHL_PRO_SEITE) {show_pages($anzahl); }

									?> <a class='Trefferanzeige'>Die Suche lieferte <?php echo $anzahl; ?> Treffer.</a> <?php
									foreach($rows->data as $row) {
									$array = (array) $row;
									echo $renderer($array);			
									}
								if ($anzahl > ANZAHL_PRO_SEITE) {show_pages($anzahl); }
									
								
									
	
	}


?>







