<!DOCTYPE html>
<html>
<head>
  
<title>Bibliothek WGS</title>
<script>
function moveCaretToEnd(el) {
    if (typeof el.selectionStart == "number") {
        el.selectionStart = el.selectionEnd = el.value.length;
    } else if (typeof el.createTextRange != "undefined") {
        el.focus();
        var range = el.createTextRange();
        range.collapse(false);
        range.select();
    }
}
</script>
</head>
<body>







<?php 
include ("../class/mysql_class_pdo.php");
	$db = new Database();
  if (isset($_REQUEST['leserid'])) { 
  	$ID = $_REQUEST['leserid']; 
	$row = $db->get_leser_info($ID) ;
    $buch = 0 ;	
  		
  } else if (isset($_REQUEST['buchid'])) {
	$ID = $_REQUEST['buchid']; 
	$row = $db->get_buch_info($ID) ;
 	$buch = 1 ;
  }
  if (isset($row->info)) $comment = $row->info ; else $comment = "" ;
  ?>
   
  <div id="info_formular">
  	
  	<textarea id="comment" data-buch="<?php echo $buch ;?>" data-id="<?php echo $ID ;?>"cols="50" rows="8" onfocus=moveCaretToEnd(this)><?php echo $comment ;?></textarea>
  
  </div>
  
</body> 

  
  	