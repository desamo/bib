<?php
function show_pages ($menge)
{
	$url = curPageURL();
	$akt_page_nr = Page_number() ;



	if ($menge > ANZAHL_PRO_SEITE) {
		
		$seitenanzahl = ($menge / ANZAHL_PRO_SEITE) ;
		$seitenanzahl = ceil($seitenanzahl)  ;

		echo "<div class='showpages'>";
		
		if ($seitenanzahl < 6) {
			for($i=1;$i<$seitenanzahl+1;$i++){
				if ($i <= $seitenanzahl) {
					if ($i == $akt_page_nr) { echo "<a>$i</a>";	} else 	{echo "<a href=\"/$url&Page=$i\">$i</a>";}

					//if ($i == $akt_page_nr) { echo "<a>$i</a>";	} else 	{echo "<a onclick=\"searchFor('$suchtext','$feld','$i');\">$i</a>";}
				}
				if ($i < $seitenanzahl) echo "&nbsp | &nbsp";
			}
		} else {
			if ($akt_page_nr == 1 ) {echo "<a>1</a> &nbsp";}	else {echo "<a href=\"/$url&Page=1\">1</a> &nbsp";	}
			if ($akt_page_nr > 4 and $akt_page_nr < $seitenanzahl - 3) {
				echo "... &nbsp";
				for($i=$akt_page_nr -2 ;$i<$akt_page_nr+3;$i++){
					if ($i < $seitenanzahl) {
						if ($i == $akt_page_nr) { echo "<a>$i</a>";	} else 	{echo "<a href=\"/$url&Page=$i\">$i</a>";}
					}

					if ($i < $akt_page_nr+2) echo "&nbsp | &nbsp";
				}
				echo "&nbsp ... &nbsp";
			} else if ($akt_page_nr <= 4) {
				echo "| &nbsp";
				for($i=2 ;$i<$akt_page_nr+3;$i++){

					if ($i < $seitenanzahl) {
						if ($i == $akt_page_nr) { echo "<a>$i</a>";	} else 	{echo "<a href=\"/$url&Page=$i\">$i</a>";}
					}


					if ($i < $akt_page_nr+2) echo "&nbsp | &nbsp";
				}
				echo "&nbsp ... &nbsp";
			} else if ($akt_page_nr > 4 and $akt_page_nr >= $seitenanzahl - 3) {
				echo "&nbsp ... &nbsp";
				for($i=$akt_page_nr -2  ;$i<$seitenanzahl+1;$i++){

					if ($i < $seitenanzahl) {
						if ($i == $akt_page_nr) { echo "<a>$i</a>";	} else 	{echo "<a href=\"/$url&Page=$i\">$i</a>";}
					}

					if ($i < $seitenanzahl) echo "&nbsp | &nbsp";
				}
			}
			if ($akt_page_nr == $seitenanzahl ) {echo "<a>$seitenanzahl</a> &nbsp";}	else {echo "<a href=\"/$url&Page=$seitenanzahl\">$seitenanzahl</a> &nbsp";	}

		}
		echo "</div>";


	}
	
}
function curPageURL() {
	$pageURL = "/" . $_SERVER["SERVER_NAME"];
	$pageURL .= $_SERVER["REQUEST_URI"];

	$kommtvor = preg_match ("/Page=/" , $pageURL) ;
	if ($kommtvor > 0 ) {
		$pos = strrpos ($pageURL,"&");
		$pageURL = substr ( $pageURL, 0, $pos) ;
	}
	return $pageURL;
}
function Page_number()
{
	$pageURL = "/" . $_SERVER["SERVER_NAME"];
	$pageURL .= $_SERVER["REQUEST_URI"];

	$kommtvor = preg_match ("/Page=/" , $pageURL) ;
	if ($kommtvor > 0 ) {
		$pos = strrpos ($pageURL,"=");
		$page_nr = substr($pageURL, $pos + 1, strlen($pageURL)) ;
	} else if ($kommtvor == 0 ) $page_nr = 1;
	//echo "Hier sollte die nummer stehen : " . $page_nr ;
	return $page_nr ;
}
?>