<?php 

class Database{
    private $host      = "localhost";
    private $user      = "WGS1";
    private $pass      = "WGS1";
    private $dbname    = "bibliothek";
    
    
    private $CHAR = 'utf8';
    private $stmt;
    private $dbh;
    private $error;
 
    public function __construct(){
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8';
        // Set options
        $options = array(
            PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );
        // Create a new PDO instanace
        try{
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        }
        // Catch any errors
        catch(PDOException $e){
            $this->error = $e->getMessage();
        }
    }
    public function query($query){
    	$this->stmt = $this->dbh->prepare($query);
    }
	public function bind($param, $value, $type = null){
	    if (is_null($type)) {
	        switch (true) {
	            case is_int($value):
	                $type = PDO::PARAM_INT;
	                break;
	            case is_bool($value):
	                $type = PDO::PARAM_BOOL;
	                break;
	            case is_null($value):
	                $type = PDO::PARAM_NULL;
	                break;
	            default:
	                $type = PDO::PARAM_STR;
	        }
	    }
	    $this->stmt->bindValue($param, $value, $type);
	}
	public function execute(){
		return $this->stmt->execute();
	}
	public function resultset(){
		$this->execute();
		return $this->stmt->fetchAll(PDO::FETCH_OBJ);
	}
	public function single(){
		$this->execute();
		return $this->stmt->fetch(PDO::FETCH_OBJ);
	}
	public function rowCount(){
		return $this->stmt->rowCount();
	}
	public function lastInsertId(){
		return $this->dbh->lastInsertId();
	}
	public function beginTransaction(){
		return $this->dbh->beginTransaction();
	}
	public function endTransaction(){
		return $this->dbh->commit();
	}
	public function cancelTransaction(){
		return $this->dbh->rollBack();
	}
	public function debugDumpParams(){
		return $this->stmt->debugDumpParams();
	}
	public function create_full_text_search_string($suchbegriff){
		
		$Suchtext =	trim($suchbegriff);
		$Suchtext = "+".str_replace(" "," +",$Suchtext);
		$pos = strrpos ($Suchtext," ") + 1;
		if ((strlen($Suchtext) - $pos) > 4 ) $Suchtext = $Suchtext . "*";
		
		return $Suchtext ;
	}
		
	function buch_anlegen($buchid = 0, $Titel, $Autor, $ISBN, $Verlag, $Jahr, $Buchreihe, $Beschreibung, $Anmerkung, $Schlagwörter,
			$Inhalt, $Signatur, $Kaufdatum, $Rechnung, $Alter, $Stufe, $Ausleihfrist, $Preis)
	{
	
		
		if (!empty($Titel) and !empty($Autor) and !empty($ISBN) and !empty($Verlag) and !empty($Signatur)) {
				
		 If ($buchid == 0)
			$sql = "INSERT INTO buecher
				(Signatur, Titel, Autor, ISBN, Verlag,
				Jahr, Buchreihe, Beschreibung,
				Anmerkung, Schlagwörter, Inhalt,
				Kaufdatum, Rechnung, abAlter,
				Stufe, Ausleihfrist, Preis)
				VALUES
				(:Signatur, :Titel, :Autor, :ISBN, :Verlag,
				 :Jahr, :Buchreihe, :Beschreibung,
				:Anmerkung, :Schlagwoerter, :Inhalt,
				:Kaufdatum, :Rechnung, :abAlter,
				:Stufe, :Ausleihfrist, :Preis)";
			
			else If ($buchid != 0) {
		 		$sql = "UPDATE buecher SET 
					Signatur = :Signatur , Titel = :Titel , Autor = :Autor , ISBN = :ISBN , Verlag = :Verlag , Jahr = :Jahr ,
	   				Buchreihe = :Buchreihe , Beschreibung = :Beschreibung , Anmerkung = :Anmerkung , Schlagwörter = :Schlagwoerter,  
					Inhalt = :Inhalt , Kaufdatum = :Kaufdatum , Rechnung = :Rechnung , abAlter = :abAlter,
					Stufe = :Stufe , Ausleihfrist = :Ausleihfrist , Preis = :Preis WHERE ID = :ID " ;
			}
			$this->query($sql);
			$this->bind(':Signatur', $Signatur) ;
			$this->bind(':Titel', $Titel) ;
			$this->bind(':Autor', $Autor) ;
			$this->bind(':ISBN', $ISBN) ;
			$this->bind(':Verlag', $Verlag ) ;
			$this->bind(':Jahr', $Jahr) ;
			$this->bind(':Buchreihe', $Buchreihe ) ;
			$this->bind(':Beschreibung', $Beschreibung ) ;
			$this->bind(':Anmerkung', $Anmerkung ) ;
			$this->bind(':Schlagwoerter', $Schlagwörter ) ;
			$this->bind(':Inhalt', $Inhalt ) ;
			$this->bind(':Kaufdatum', $Kaufdatum ) ;
			$this->bind(':Rechnung', $Rechnung ) ;
			$this->bind(':abAlter', $Alter) ;
			$this->bind(':Stufe', $Stufe ) ;
			$this->bind(':Ausleihfrist', $Ausleihfrist) ;
			$this->bind(':Preis', $Preis ) ;
			If ($buchid != 0) $this->bind(':ID', $buchid ) ;
			return 	$this->execute();
		}
	
	}
	function check_login ($username, $password_ver)
	{
		
		$sql = "SELECT * FROM user WHERE username = :username AND passwort = :password ";
		$this->query($sql) ;
		$this->bind(':username', $username) ;
		$this->bind(':password', $password_ver) ;
		$this->execute();
			
		return $this->single();
	}
	
	function create_kuerzel($name){
			
		$sql = "SELECT Ausweiscode FROM leser WHERE Ausweiscode LIKE :NAME ";
			$this->query($sql);
			
			$this->bind(':NAME', "$name%") ;
			$this->execute();
			$rows = $this->resultset();
			$anzahl = $this->rowCount();
		
			if ($anzahl>0) {
				foreach($rows as $row) {
				
					$pos = strrpos ($row->Ausweiscode,"-");
					$arr[] = intval(substr($row->Ausweiscode,$pos+1,strlen($row->Ausweiscode)));
		
				}
				sort($arr);
				for ($i=0;$i<count($arr);$i++) {
		
					if (($i+1) != $arr[$i]) break ;
				}
				$kuerzel = $name . "-" . strval($i+1);
			} else $kuerzel = $name ."-1";
			return $kuerzel;
	}
	
	function get_buch_info($ID)
	{
			
		$sql = "SELECT ID, info FROM buecher WHERE ID = :ID";
		$this->query($sql) ;
		$this->bind(':ID', $ID) ;
		$this->execute();
			
		return $this->single();
	}
	function get_buch_by_id ($ID, $INFO="STANDARD")
	{
	
		
		$sql = "SELECT * FROM buecher WHERE ID = :ID";
		if ($INFO == "ALLE") {
			$sql = "SELECT 
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
						FROM buecher as a LEFT JOIN verliehen as b ON a.ID = b.buchid LEFT JOIN leser as c ON b.Ausleiher = c.ID WHERE a.ID = :ID ";
		}
		$this->query($sql);
		$this->bind(':ID', $ID) ;
		$this->execute();
	
		return $this->single();
	}
	function get_leser_info($ID)
	{
		$sql = "SELECT ID, info FROM leser WHERE ID = :ID";
		$this->query($sql) ;
		$this->bind(':ID', $ID) ;
		$this->execute();
			
		return $this->single();
	}
	
	function get_leser_by_id($ID)
	{
			
		$sql = "SELECT * FROM leser WHERE ID = :ID";
		$this->query($sql) ;
		$this->bind(':ID', $ID) ;
		$this->execute();
			
		return $this->single();
	}
	function save_leser_info($leserid, $comment)
	{
		$sql = "UPDATE leser SET info = :comment WHERE ID = :ID" ;
		$this->query($sql) ;
		$this->bind(':comment', $comment) ;
		$this->bind(':ID', $leserid) ;
		
			
		return $this->execute();
	}
	function save_buch_info($buchid, $comment)
	{
		$sql = "UPDATE buecher SET info = :comment WHERE ID = :ID" ;
		$this->query($sql) ;
		$this->bind(':comment', $comment) ;
		$this->bind(':ID', $buchid) ;
		
			
		return $this->execute();
	}
   
   function leser_anlegen($leserid = 0, $name, $Vorname, $Strasse, $Hausnr, $Geburtsjahr, $Ausweiscode, $geschlecht, $Klasse, $gruppe)
   {
	   	if ($leserid != 0) {
	   		$sql = "UPDATE leser SET Name = ? , Vorname = ? , Strasse = ? , Hausnr = ? , Geburtsjahr = ? ,
	   		Ausweiscode = ? , Geschlecht = ? , Klasse = ? , Gruppe = ?  WHERE ID = ? " ;
	   	} else {
	   		$sql = "INSERT INTO leser (Name, Vorname, Strasse, Hausnr, Geburtsjahr, Ausweiscode, Geschlecht, Klasse, Gruppe) 
							VALUES (? , ? , ? , ? , ? , ? , ? , ? , ? )";
	   		
	   	}
   		$this->query($sql);
	   	$this->bind(1, $name ) ;
	   	$this->bind(2, $Vorname) ;
	   	$this->bind(3, $Strasse) ;
	   	$this->bind(4, $Hausnr) ;
	   	$this->bind(5, $Geburtsjahr ) ;
	   	$this->bind(6, $Ausweiscode) ;
	   	$this->bind(7, $geschlecht ) ;
	   	$this->bind(8, $Klasse ) ;
	   	$this->bind(9, $gruppe ) ;
	   	
	   	If ($leserid != 0) $this->bind(10, $leserid ) ;
	   	   	
	   
   		
   		return 	$this->execute();
   }
   function leser_finder($buchid){
   	 
	   	$sql = "SELECT * FROM statistik as a
		   	LEFT JOIN leser as b
		   	ON a.Ausleiher = b.ID WHERE a.buchid = :ID ORDER BY a.Ausleihdatum desc";
	   	$this->query($sql) ;
	   	$this->bind(':ID', $buchid) ;
	   	
	   
	   	return $this->resultset();
     
   }
   
   function leser_sperren($leserid, $options="STANDARD"){
   
	   if ($options != "SICHER_SPERREN") {
	   	$sql = "SELECT ID, gesperrt FROM leser WHERE ID = :ID ";
	   	$this->query($sql) ;
	   	$this->bind(':ID', $leserid) ;
	   	$this->execute();
	   	$row2 = $this->single();
	   	if ($row2->gesperrt == 1) $sperre = 0 ; else $sperre = 1 ;
	   } else $sperre = 1 ;
	   	$sql = "UPDATE leser SET gesperrt = :SPERRE WHERE ID = :ID " ;
	   	$this->query($sql) ;
	   	$this->bind(':SPERRE', $sperre) ;
	   	$this->bind(':ID', $leserid) ;
	   	$this->execute();
	   	
	   		
	   	return $sperre;
   }
   function buch_ausleihen($buchid, $Ausleiher)
   {
   	$leser = $this->get_leser_by_id($Ausleiher);
   	$gesperrt = $leser->gesperrt ;
   		
   	if ($gesperrt == 1) {
   		$ausgabe = "Der Leser ist gesperrt." ;
   
   	} else {
   			
   		$buch = $this->get_buch_by_id($buchid);
   		$frist = $buch->Ausleihfrist ;
   
   		if ($frist == 0) {
   			$ausgabe = "Diese Buch darf nicht ausgeliehen werden." ;
   				
   		} else {
   
   			$datum = date("Y-m-d");
   			$rdatum = strtotime("+" .$frist . " days");
   			$rdatum = date("Y-m-d", $rdatum);
   				
   			$sql = "INSERT INTO verliehen (buchid, Ausleiher, Ausleihdatum, Rückgabedatum ) VALUES (? , ? , ? , ?)";
   			$this->query($sql);
   			$this->bind(1, $buchid ) ;
   			$this->bind(2, $Ausleiher) ;
   			$this->bind(3, $datum) ;
   			$this->bind(4, $rdatum) ;
   			$ausgabe = 	$this->execute();
   		}
   	}
   	return $ausgabe ;
   }
   function buch_zurückgeben($buchid)
   {
   		
   	
   	$datum = date("Y-m-d",time());
   	$sql = "SELECT ID, Rückgabedatum, Ausleihdatum, Ausleiher, buchid, Vormerken
   	FROM verliehen where buchid = :buchid";
   	$this->query($sql);
   	$this->bind(':buchid', $buchid) ;
   	$row = $this->single();
   	
   	if (isset($row->buchid)) {
   		
	   		$eintrag = "INSERT INTO statistik (Ausleihdatum, Rückgabedatum, Ausleiher, buchid)
	   		VALUES (?, ?, ?, ?)";	// in andere tabelle für statistikzwecke übertragen
	   		$this->query($eintrag);
	   		$this->bind(1, $row->Ausleihdatum ) ;
	   		$this->bind(2, $datum) ;
	   		$this->bind(3, $row->Ausleiher) ;
	   		$this->bind(4, $row->buchid) ;
	   		$result = $this->execute();
   		   
   		if ($result == 1)  $sql = "DELETE FROM verliehen WHERE buchid = :buchid ";
   			$this->query($sql);
   			$this->bind(':buchid', $buchid) ;
   			
   		return $this->execute();
   	}
   		
   	}
   	function buch_verlängern($buchid)
   	{
   	
   		$sql = "SELECT ID, Rückgabedatum, Ausleihdatum, Ausleiher, buchid, Verlängern, Vormerken
   		FROM verliehen where buchid = :buchid ";
   		$this->query($sql);
   		$this->bind(':buchid', $buchid);
   	    $row =$this->single() ;		
   		$ver = $row->Verlängern + 1;
    	    			   			
   			  					
   				$rdatum = strtotime($row->Rückgabedatum . " +1 week");
   				$rdatum = date("Y-m-d",$rdatum);
   					
   				$eintrag = "UPDATE verliehen SET Verlängern = :ver , Rückgabedatum = :rdatum  WHERE buchid = :buchid ";
   				$result = $this->query($eintrag);
   				$this->bind(':ver', $ver);
   				$this->bind(':rdatum', $rdatum);
   				$this->bind(':buchid', $buchid);
   				$ausgabe = $this->execute();
   				$rdatum = date("d.m.y", strtotime($rdatum)) ;
   				//$ausgabe = "Das Buch wurde bis zum $rdatum verlängert!";
   	
   			
   		
   		return $ausgabe;
   	}
   	function buch_vormerken($buchid, $leserid, $Info = "")
   	{
   	
 		$sql = "INSERT INTO vorgemerkt (buchid, Vormerker, Info ) VALUES (? , ? , ?)";
   			$this->query($sql);
   			$this->bind(1, $buchid ) ;
   			$this->bind(2, $leserid) ;
   			$this->bind(3, $Info) ;

   			return $this->execute();
   			  		
   	}
   	function buch_vormerkung_entfernen($buchid, $leserid)
   	{
   		$sql = "DELETE FROM vorgemerkt WHERE buchid = :buchid AND Vormerker = :leserid" ;
   		$this->query($sql);
   		$this->bind(':buchid', $buchid);
   		$this->bind(':leserid', $leserid);
   		return $this->execute();
   		   		
   	}
   	function vormerkung_finder($buchid, $leserid = 0 ) {
   		
   		$sql = "SELECT * FROM vorgemerkt WHERE buchid = :buchid ORDER by Datum asc" ;
   		$this->query($sql);
   		$this->bind(':buchid', $buchid);
   		$this->execute();
   		return $this->resultset();
   		
   		
   	}
   	function buch_finder($Leserid){
   		 
   		$sql = "SELECT DISTINCT
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
  				FROM verliehen as b
	   				LEFT JOIN buecher as a
	 			  	ON b.buchid = a.ID LEFT JOIN leser as c ON b.Ausleiher = c.ID LEFT JOIN vorgemerkt as d ON d.buchid = b.buchid LEFT JOIN leser as e ON d.Vormerker = e.ID
   				WHERE b.Ausleiher = :ID OR d.Vormerker = :ID ORDER BY Ausleihdatum asc";
   		$this->query($sql) ;
   		$this->bind(':ID', $Leserid) ;
   		$this->execute();
   	
   		return $this->resultset();
   	}
   	function buch_findger($Leserid, $INFO="STANDARD"){
   	
   		$sql = "SELECT * FROM verliehen as a
	   	LEFT JOIN buecher as b
	   	ON a.buchid = b.ID WHERE a.Ausleiher = :ID OR a.Vormerken = :ID ORDER BY Ausleihdatum asc";
   		$this->query($sql) ;
   		$this->bind(':ID', $Leserid) ;
   		$this->execute();
   	
   		return $this->resultset();
   	}
   	function user_edit($userid, $username, $pass, $email, $Gruppe)
		{
				
					$sql = "UPDATE user SET username = ? , passwort = ?, email = ?, Gruppe = ? WHERE ID = ? ";
					$this->query($sql);
					$this->bind(1, $username ) ;
	   				$this->bind(2, $pass) ;
	   				$this->bind(3, $email) ;
	   				$this->bind(4, $Gruppe) ;
	   				$this->bind(5, $userid) ;
	   				$result = $this->execute();
									
				
		return $result ;
		}
   	
}
?>