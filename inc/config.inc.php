<?php
// die Konstanten auslagern in eigene Datei
// die dann per require_once ('config.inc.php'); 
// geladen wird.
 
// Damit alle Fehler angezeigt werden
define ( 'SCHULNAME', 'Wiesengrundschule Leihgestern' );
define ( 'ROOT_DIR', "http://" . $_SERVER["SERVER_NAME"]. '/bib' );

define ('IMG_KIND', './img/kind.png');
define ('IMG_LEHRER', './img/lehrer.png');
define ('IMG_GESPERRT', './img/gesperrt.png');
define ('IMG_NICHT_GESPERRT', './img/nicht_gesperrt.png');
define ('IMG_INFO_LEER', './img/info_grau.png');
define ('IMG_INFO', './img/info.png');
define ('ANZAHL_PRO_SEITE', '10');

?>