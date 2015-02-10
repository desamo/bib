<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require '../vendor/autoload.php';



// \Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();


/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */

// GET route

$app->get('/book/:id', 'get_book' );
$app->get('/login', 'login' );
$app->get('/save_info', 'save_info' );
$app->get('/books_from_leser/:id', 'get_books_from_leser' );
$app->get('/zurueckgeben', 'book_zurueck' );
$app->get('/verlaengern', 'book_verlaengern' );
$app->get('/ausleihen', 'book_ausleihen' );
$app->get('/kuerzel/:name', 'create_kuerzel' );
$app->get('/leser/:id', 'get_leser' );
$app->get('/search', 'search' );
$app->get('/quick_search', 'quick_search' );
$app->get('/signature/:signature', 'check_signatur' );
$app->delete('/book/:id', 'book_delete' );

// POST route
$app->post('/book', 'add_book');
$app->post('/leser', 'add_leser');
$app->post('/user', 'add_user');

// PUT route
$app->put(
    '/put',
    function () {
        echo 'This is a PUT route';
    }
);

// PATCH route
$app->patch('/patch', function () {
    echo 'This is a PATCH route';
});

// DELETE route
$app->delete('/delete',
    function () {
        echo 'This is a DELETE route';
    }
);

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */

$app->run();
function save_info (){
  $app = \Slim\Slim::getInstance();
  //$request = $app->request();
  $id = $app->request()->get('id');
  $buch = $app->request()->get('buch');
  $comment = $app->request()->get('comment');
 
    
     try {
        $db = getConnection();
        if ($buch == 0 ) $sql = "UPDATE leser SET info = :comment WHERE ID = :ID" ; else if ($buch == 1) $sql = "UPDATE buecher SET info = :comment WHERE ID = :ID " ;
        $stmt = $db->prepare($sql);  
        $stmt->bindParam(':comment', $comment) ;
        $stmt->bindParam(':ID', $id) ;
        $result = $stmt->execute() ;
        
        
         echo json_encode($result) ;
       
       // echo ($categorie . " ". $suchparameter . " " . $page) ;
       
             
    } catch(PDOException $e) {
          echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
   
  
}
function book_delete($buchid) {
   
    
    $sql =  "DELETE FROM buecher WHERE ID = :id";
    try {
        $db = getConnection();
        
        $stmt = $db->prepare($sql);  
        $stmt->bindParam("id", $buchid);
        $result = $stmt->execute();
              
        $db = null;
        echo json_encode($result); 
        
             
    } catch(PDOException $e) { echo $e->getMessage(); }
 
}
function login(){
    $app = \Slim\Slim::getInstance();
    $username = $app->request()->get('username');
    $password = $app->request()->get('password');
    try {
        $db = getConnection();
        $sql = "SELECT username, passwort, email, Gruppe, ID FROM user where username = :username ";
        $stmt = $db->prepare($sql);  
        $stmt->bindParam("username", $username);
        $stmt->execute();
        $user = $stmt->fetchObject();

        if (!empty($user)) {


          if ($user->passwort == $password) {
        

            $_SESSION['USER'] = $user;
            echo json_encode($user);

           } else echo json_encode("Falsches Passwort") ;

        } else echo json_encode("Der User existiert nicht.") ;

        


        
          
     } catch(PDOException $e) { echo $e->getMessage(); }

     

}
function book_verlaengern(){
     $app = \Slim\Slim::getInstance();
     $buchid = $app->request()->get('buchid');
     $datum = date("Y-m-d",time());
     
    try {
        $db = getConnection();
        $sql = "SELECT a.ID, a.Vormerker, a.Datum, a.buchid, b.Name, b.Vorname, b.ID as leserid FROM vorgemerkt as a LEFT JOIN leser as b ON a.Vormerker = b.ID where buchid = :buchid ";
        $stmt = $db->prepare($sql);  
        $stmt->bindParam("buchid", $buchid);
        $stmt->execute();
        $vormerker = $stmt->fetchObject();
        

        if (empty($vormerker)) {
          
          $sql = "SELECT ID, Rückgabedatum, Ausleihdatum, Ausleiher, buchid, Verlängern, Vormerken FROM verliehen where buchid = :buchid ";
          $stmt = $db->prepare($sql);  
          $stmt->bindParam("buchid", $buchid);
          $stmt->execute();
          $book = $stmt->fetchObject();
          $ver = $book->Verlängern;
          $ver = $ver +1 ;
        
          if ($book->Verlängern < 5) {
      
            $rdatum = strtotime($book->Rückgabedatum . " +1 week");
            $rdatum = date("Y-m-d",$rdatum);
              
            $eintrag = "UPDATE verliehen SET Verlängern = :ver , Rückgabedatum = :rdatum  WHERE buchid = :buchid ";
            $stmt = $db->prepare($eintrag);
            $stmt->bindParam(':ver', $ver);
            $stmt->bindParam(':rdatum', $rdatum);
            $stmt->bindParam(':buchid', $buchid);
            $stmt->execute();
            $rdatum = date("d.m.Y", strtotime($rdatum)) ;
          
            echo json_encode($rdatum); 

          } else echo "Buch wurde schon 5 mal verlängert.";
        } else {
           $vor_datum = date("d.m.Y",strtotime($vormerker->Datum));
           echo "Das Buch wurde von $vormerker->Vorname $vormerker->Name am $vor_datum vorgemerkt und kann deswegen nicht mehr verlängert werden." ;
          
        }  
          $db = null;
         
        
             
    } catch(PDOException $e) { echo $e->getMessage(); }
 


}
function book_zurueck() {
   
   
    
    $app = \Slim\Slim::getInstance();
    $id = $app->request()->get('buchid');
    $datum = date("Y-m-d",time());
    $sql = "SELECT ID, Rückgabedatum, Ausleihdatum, Ausleiher, buchid, Vormerken FROM verliehen where buchid = :id";
    try {
        $db = getConnection();
        
        $stmt = $db->prepare($sql);  
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $book = $stmt->fetchObject();

        
        if (isset($book)) {
      
          $eintrag = "INSERT INTO statistik (Ausleihdatum, Rückgabedatum, Ausleiher, buchid)
          VALUES (?, ?, ?, ?)"; // in andere tabelle für statistikzwecke übertragen
          $stmt = $db->prepare($eintrag);
          $stmt->bindParam(1, $book->Ausleihdatum ) ;
          $stmt->bindParam(2, $datum) ;
          $stmt->bindParam(3, $book->Ausleiher) ;
          $stmt->bindParam(4, $book->buchid) ;
          $result = $stmt->execute();
         
          if ($result == 1)  {
            $sql = "DELETE FROM verliehen WHERE buchid = :id ";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id) ;
            $stmt->execute();
          }
        
      
      }
        $db = null;
         echo json_encode($book); 
        
             
    } catch(PDOException $e) { echo $e->getMessage(); }
 
}
function book_ausleihen() {
    $app = \Slim\Slim::getInstance();
    $datum = date("Y-m-d",time());
    $buchid = $app->request()->get('buchid');
    $Ausleiher = $app->request()->get('leserid');
    
    
    try {
          $db = getConnection();
          $sql = "SELECT gesperrt FROM leser WHERE ID = :leserid ";
          $stmt = $db->prepare($sql);
          $stmt->bindParam(":leserid", $Ausleiher ) ;    
          $stmt->execute();
          $leser = $stmt->fetchObject();
          $result = $leser ;
          if ($leser->gesperrt == 0) {
            
            $sql = "SELECT * FROM buecher WHERE ID = :buchid";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":buchid", $buchid ) ;    
            $stmt->execute();
            $book = $stmt->fetchObject();
            $result = $book ;
            if ($book->Ausleihfrist == 0) {
               echo "Diese Buch darf nicht ausgeliehen werden.";
            } else {

              $datum = date("Y-m-d");
              $rdatum = strtotime("+" .$book->Ausleihfrist . " days");
              $rdatum = date("Y-m-d", $rdatum);
              $sql = "INSERT INTO verliehen (buchid, Ausleiher, Ausleihdatum, Rückgabedatum ) VALUES (? , ? , ? , ?)";
              $stmt = $db->prepare($sql);
              $stmt->bindParam(1, $buchid ) ;
              $stmt->bindParam(2, $Ausleiher) ;
              $stmt->bindParam(3, $datum) ;
              $stmt->bindParam(4, $rdatum) ;     
              $stmt->execute();
               
            }

        } else if ($leser->gesperrt == 1) {
          echo "Der Leser ist gesperrt.";
        }
        echo json_encode($result);
        $db = null;
                     
    } catch(PDOException $e) { echo $e->getMessage(); }





}
function create_kuerzel($name){
      
    $sql = "SELECT Ausweiscode FROM leser WHERE Ausweiscode LIKE :NAME ";
       $name1 = $name . "%" ;
        
    try {
        $db = getConnection();
        
        $stmt = $db->prepare($sql);  
        $stmt->bindParam(":NAME", $name1);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_OBJ);
        $anzahl = $stmt->rowCount();
        $db = null;
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
          
           echo $kuerzel; 
     
             
    } catch(PDOException $e) { echo $e->getMessage(); }
}

function search (){
  $app = \Slim\Slim::getInstance();
  //$request = $app->request();
  $categorie = $app->request()->get('cat');
  $suchparameter = $app->request()->get('term');
  $page = 1 ;
  $page = $app->request()->get('page');
    
    if ($categorie == "Name" or $categorie == "Buch") {
      $suchparameter = create_full_text_search_string($suchparameter) ; 
    } else {
      $suchparameter = $suchparameter . "%" ;
    }
  
      $sql_str = create_sql_string($categorie) ;
   
    
     try {
        $db = getConnection();
        $stmt = $db->prepare($sql_str);  
        $stmt->bindParam("suchparameter", $suchparameter);
        $stmt->execute() ;
        $anzahl = $stmt->rowcount() ;
        
        $b = ($page * ANZAHL_PRO_SEITE) - ANZAHL_PRO_SEITE   ;
        $a = ANZAHL_PRO_SEITE ;
        $sql_str = $sql_str . "  LIMIT $b, $a" ;
        
        $stmt = $db->prepare($sql_str);  
        $stmt->bindParam("suchparameter", $suchparameter);
        $stmt->execute() ;
        
        $info = new stdClass() ;
        $info->anzahl = $anzahl;
        $info->sql_string = $sql_str;
        $ergebnis = new stdClass() ;
        $ergebnis->data = $stmt->fetchAll(PDO::FETCH_OBJ);
        $ergebnis->info = $info ;




        
        // $array1 = array("0" => $menge);
        // $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        // $result = array_merge($array1, $result);
        $result = json_encode($ergebnis) ;

       echo ($result); 
       // echo ($categorie . " ". $suchparameter . " " . $page) ;
       
             
    } catch(PDOException $e) {
          echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
   
  
}
function quick_search (){
  $app = \Slim\Slim::getInstance();
  //$request = $app->request();
  $cat = $app->request()->get('cat');
  $suchbegriff = $app->request()->get('term');
 
   if ($cat == 'Buch') {
    $sql = "SELECT  Titel, Verlag, Autor, Buchreihe, Schlagwörter FROM buecher WHERE MATCH(Titel, Autor, Verlag, Buchreihe, Schlagwörter) AGAINST( :suchparameter IN BOOLEAN MODE) LIMIT 0,15";
  } else if ($cat == 'Name') {
    $sql = "SELECT  Name, Vorname FROM leser WHERE MATCH(Name, Vorname) AGAINST(:suchparameter IN BOOLEAN MODE) LIMIT 0,15";
  }
    
    
      $suchparameter = create_full_text_search_string($suchbegriff) ; 
      
   
    
     try {
        $db = getConnection();
        $stmt = $db->prepare($sql);  
        $stmt->bindParam("suchparameter", $suchparameter);
        $stmt->execute() ;
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        

        $data = array();
        if (!empty($result)) {
        foreach( $result as $row )
            {
              if ($cat == 'Buch') {$result = $row->Buchreihe . " " .$row->Titel ; } else if ($cat == 'Name') {$result = $row->Name . " " .$row->Vorname ;}
              $data[] = array(
                    'label' => $result,
                    'value' => $result
              
              );
            }
        }


        
       echo json_encode($data);
  
       flush();

      
       // echo ($categorie . " ". $suchparameter . " " . $page) ;
       
             
    } catch(PDOException $e) {
          echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
   
  
}



function getConnection() {
    $dbhost= DB_HOST;
    $dbuser= DB_USER;
    $dbpass= DB_PASS;
    $dbname= DB_NAME;
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass);  
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}

function add_book() {
    //error_log('addbook\n', 3, '/var/tmp/php.log');
    $app = \Slim\Slim::getInstance();
    $request = $app->request();
    // $id = $request->post('Titel');
    $body = $request->getBody();
    $book = json_decode($body);
    $datum = date("Y-m-d");
            
    //$error = check_book($book) ;
     if (!empty($book->Titel) and !empty($book->Autor) and !empty($book->Isbn) and !empty($book->Verlag) and !empty($book->Signatur)) {
          if ($book->Id == 0 ) {
            $sql = "INSERT INTO buecher
                (Signatur, Titel, Autor, ISBN, Verlag,
                Jahr, Buchreihe, Beschreibung,
                Anmerkung, Schlagwörter, Kaufdatum, abAlter, Stufe, Ausleihfrist)
                VALUES
                (:Signatur, :Titel, :Autor, :Isbn, :Verlag,
                 :Jahr, :Buchreihe, :Beschreibung,
                :Anmerkung, :Schlagwoerter, :Kaufdatum, :abAlter, :Stufe, :Ausleihfrist)";

          } else If ($book->Id != 0) {
             $sql = "UPDATE buecher SET 
              Signatur = :Signatur , Titel = :Titel , Autor = :Autor , ISBN = :Isbn , Verlag = :Verlag , Jahr = :Jahr ,
              Buchreihe = :Buchreihe , Beschreibung = :Beschreibung , Anmerkung = :Anmerkung , Schlagwörter = :Schlagwoerter,  
              Kaufdatum = :Kaufdatum , abAlter = :abAlter,
              Stufe = :Stufe , Ausleihfrist = :Ausleihfrist WHERE Id = :Id " ;
      }
     
       try {
           $db = getConnection();
           $stmt = $db->prepare($sql);  
           $stmt->bindParam('Signatur', $book->Signatur);
           $stmt->bindParam('Titel', $book->Titel);
           $stmt->bindParam('Autor', $book->Autor);
           $stmt->bindParam('Isbn', $book->Isbn);
           $stmt->bindParam('Verlag', $book->Verlag);
           $stmt->bindParam('Jahr', $book->Jahr);
           $stmt->bindParam('Buchreihe', $book->Buchreihe);
           $stmt->bindParam('Beschreibung', $book->Beschreibung);
           $stmt->bindParam('Anmerkung', $book->Anmerkung);
           $stmt->bindParam('Schlagwoerter', $book->Schlagwoerter);
           $stmt->bindParam('Kaufdatum', $datum);
           $stmt->bindParam('abAlter', $book->Alter);
           $stmt->bindParam('Stufe', $book->Stufe);
           $stmt->bindParam('Ausleihfrist', $book->Ausleihfrist);
           If ($book->Id != 0) {$stmt->bindParam('Id', $book->Id); }
           $stmt->execute();
           $book->id = $db->lastInsertId();
           $db = null;
           echo json_encode($book); 
      } catch(PDOException $e) {
     //     // error_log($e->getMessage(), 3, '/var/tmp/php.log');
          //echo  '{"error":{"text":'. $e->getMessage() .'}}';

          echo $e->getMessage() ;

      }
     } else  echo "Titel, ISBN, Autor, Verlag und Signatur müssen angegen werdern"; 
}
function add_leser() {
    //error_log('addbook\n', 3, '/var/tmp/php.log');
    $app = \Slim\Slim::getInstance();
    $request = $app->request();
    // $id = $request->post('Titel');
    $body = $request->getBody();
    $leser = json_decode($body);
    //$leser = $body ;
    
    
     if (!empty($leser->name) and !empty($leser->vorname) and !empty($leser->kuerzel)) {
         if ($leser->id != 0) {
          $sql = "UPDATE leser SET Name = ? , Vorname = ? , Strasse = ? , Hausnr = ? , Geburtsjahr = ? ,
          Ausweiscode = ? , Geschlecht = ? , Klasse = ? , Gruppe = ?  WHERE ID = ? " ;
         } else {
          $sql = "INSERT INTO leser (Name, Vorname, Strasse, Hausnr, Geburtsjahr, Ausweiscode, Geschlecht, Klasse, Gruppe) 
              VALUES (? , ? , ? , ? , ? , ? , ? , ? , ? )";
          }
     
     
        try {
             $db = getConnection();
             $stmt = $db->prepare($sql);  
             $stmt->bindParam(1, $leser->name);
             $stmt->bindParam(2, $leser->vorname);
             $stmt->bindParam(3, $leser->strasse);
             $stmt->bindParam(4, $leser->hausnr);
             $stmt->bindParam(5, $leser->geburtsjahr);
             $stmt->bindParam(6, $leser->kuerzel);
             $stmt->bindParam(7, $leser->geschlecht);
             $stmt->bindParam(8, $leser->klasse);
             $stmt->bindParam(9, $leser->gruppe);
             If ($leser->id != 0) {$stmt->bindParam(10, $leser->id); }
             $stmt->execute();
             $leser->id = $db->lastInsertId();
             $db = null;
             echo json_encode($leser); 
        } catch(PDOException $e) {
             echo $e->getMessage() ;
        }
     } else  echo "Vorname und Name müssen angegeben werden"; 
}
function add_user() {
    //error_log('addbook\n', 3, '/var/tmp/php.log');
    $app = \Slim\Slim::getInstance();
    $request = $app->request();
    // $old_password = $request->post('old_password');
    $body = $request->getBody();
    $user = json_decode($body);
    
    
    
     // if (!empty($user->name) and !empty($user->vorname) and !empty($user->gruppe)) {
     //    if ($user->id != 0) {
     //      $sql = "UPDATE user SET username = ? , passwort = ?, email = ?, Gruppe = ? WHERE ID = ? ";
     //    } else {
     //      $sql = "INSERT INTO user (username, passwort, email, Gruppe) VALUES (? , ? , ? , ? , ? )";
     //     }
              
         try {
              $db = getConnection();
              $sql = "SELECT * FROM user WHERE username = :username " ;
              $stmt = $db->prepare($sql);  
              $stmt->bindParam(":username", $user->username);
              $stmt->execute();
              $result = $stmt->fetchObject();
              
              if (!empty($result)) {
                
                if ($user->old_password == $result->passwort ) {
                    
                    if ($user->new_password1 == $user->new_password2) {
                    
                      if ($user->new_password1 =="e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855") $password = $user->old_password ; else $password = $user->new_password1 ;
                     
                      $sql = "UPDATE user SET username = ? , passwort = ?, email = ?, Gruppe = ? WHERE ID = ? ";
                      $stmt = $db->prepare($sql);  
                      $stmt->bindParam(1, $user->username);
                      $stmt->bindParam(2, $password);
                      $stmt->bindParam(3, $user->email);
                      $stmt->bindParam(4, $user->Gruppe);
                      $stmt->bindParam(5, $result->ID);

                      $stmt->execute();
                       $sql = "SELECT * FROM user WHERE username = :username " ;
                       $stmt = $db->prepare($sql);  
                       $stmt->bindParam(":username", $user->username);
                       $stmt->execute();
                       $_SESSION['USER'] = $stmt->fetchObject();
                    } else echo "Die beiden Passwörter stimmen nicht überein." ;

                } else echo "Falsches Passwort" ;

              } else {
                      $sql = "INSERT INTO user (username, passwort, email, Gruppe) VALUES (? , ? , ? , ? )" ;
                      $stmt = $db->prepare($sql);  
                      $stmt->bindParam(1, $user->username);
                      $stmt->bindParam(2, $user->new_password1);
                      $stmt->bindParam(3, $user->email);
                      $stmt->bindParam(4, $user->Gruppe);
                      $stmt->execute();
                      echo json_encode($user); 
              }
      
          } catch(PDOException $e) {
              echo $e->getMessage() ;
         }
     
}

function check_signatur ($signatur)     //diese Funktion prüft ob eine Signatur bereits vorhanden ist
  {
    
    $split = preg_split ("/[ _-]/", $signatur);
  
    $signatur1 = $split[0] . " ". $split[1] . " " . $split[2] ;
    $signatur2 = $split[0] . "-". $split[1] . "-" . $split[2] ;
    $signatur3 = $split[0] . " ". $split[1] . "-" . $split[2] ;
    $signatur4 = $split[0] . "-". $split[1] . " " . $split[2] ;
   
    
      
     $sql = "SELECT Signatur FROM buecher WHERE Signatur LIKE :sig1 or Signatur LIKE :sig2 or Signatur LIKE :sig3 or Signatur LIKE :sig4 ";
    
    try {
           $db = getConnection();
           $stmt = $db->prepare($sql);  
           $stmt->bindParam('sig1', $signatur1);
           $stmt->bindParam('sig2', $signatur2);
           $stmt->bindParam('sig3', $signatur3);
           $stmt->bindParam('sig4', $signatur4);
                             
           $stmt->execute();
           $sig = new stdClass() ;
           
           if($stmt->fetchObject()) $exists = true ; else $exists = false ; 

           $sig->result = $exists;
           $sig->signatur = $signatur ;
           $db = null;
           echo json_encode($sig); 
      } catch(PDOException $e) {
     //     // error_log($e->getMessage(), 3, '/var/tmp/php.log');
          echo $e->getMessage(); 
      }
}
function get_book($id) {
   
    $sql = "SELECT * FROM buecher WHERE id=:id";
    try {
        $db = getConnection();
        
        $stmt = $db->prepare($sql);  
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $book = $stmt->fetchObject();
        $db = null;
        echo json_encode($book); 
             
    } catch(PDOException $e) { echo $e->getMessage(); }
}
function get_leser($id) {

    $sql = "SELECT Name, Vorname, Strasse, Geburtsjahr, Klasse, ID, Ausweiscode, Hausnr, gesperrt, Gruppe FROM leser WHERE id=:id";
    
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);  
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $leser = $stmt->fetchObject();  
        
        
        
        $db = null;
        echo json_encode($leser); 
    } catch(PDOException $e) {echo $e->getMessage(); }
}
function get_books_from_leser($id) {

    
    $sql_book = "SELECT * FROM verliehen as a LEFT JOIN buecher as b ON a.buchid = b.ID WHERE Ausleiher=:id";
    try {
        $db = getConnection();
            
        $stmt = $db->prepare($sql_book);  
        $stmt->bindParam("id", $id);
        $stmt->execute();
        // $books = $stmt->fetchAll();

        $info = new stdClass() ;
        $info->anzahl = $stmt->rowCount() ;
        
        $ergebnis = new stdClass() ;
        $ergebnis->data = $stmt->fetchAll(PDO::FETCH_OBJ);
        $ergebnis->info = $info ;
    
        
        $db = null;
        echo json_encode($ergebnis); 
    } catch(PDOException $e) {echo $e->getMessage(); }
}

function debug_to_console( $data ) {

    if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;
}
function create_sql_string($categorie, $page = 1){
    
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
    LEFT JOIN verliehen as b ON b.buchid = a.ID 
    LEFT JOIN leser as c ON b.Ausleiher = c.ID 
    LEFT JOIN vorgemerkt as d ON d.buchid = b.buchid 
    LEFT JOIN leser as e ON d.Vormerker = e.ID 
    WHERE ";


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
    default:
      $sql_str = $buch_sql . "$categorie LIKE :suchparameter ORDER BY Titel asc";
        break;
     }
       $sql_str = str_replace("\n", "", $sql_str);
    return $sql_str ;
}
function create_full_text_search_string($suchbegriff){
    
    $Suchtext = trim($suchbegriff);
    $Suchtext = "+".str_replace(" "," +",$Suchtext);
    $pos = strrpos ($Suchtext," ") + 1;
    if ((strlen($Suchtext) - $pos) > 4 ) $Suchtext = $Suchtext . "*";

    return $Suchtext ;
}

 
?>


