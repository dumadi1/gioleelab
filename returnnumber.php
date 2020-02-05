<?php


 
session_start();

$_SESSION['customer']=array();
class connect {
  // Connect to main database
  public static function toMainDatabase() {
    // NOTE: Most of this is just configurations and you don't really need to memorize

    // Connection info
    $GLOBALS['connected'] = false;
    $host = 'gioleelab-db.comsldfmuxpw.us-east-2.rds.amazonaws.com';
    $username = 'gioleelab';
    $password = 'Leesimaol2';
    $database = 'gioleelab';
    $port = '3306';

    try {
      // Attempt to connect to database
      $GLOBALS['connect'] = new PDO('mysql:host='.$host.';dbname='.$database, $username, $password);
      $GLOBALS['connect']->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $GLOBALS['connect']->setAttribute(PDO::ATTR_PERSISTENT, TRUE);

    } catch (Exception $e) {
      // What to do if connection fails
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        //die();
      } else {
        die();
      }
    }

    $GLOBALS['connected'] = true;
  }

  public static function exampleInsertPDO($name, $email, $number) {

    // $GLOBALS['connect'] is a global variable created in the function above this one
    $insert = $GLOBALS['connect']->prepare('INSERT INTO customer (name,email, number)
    VALUES (:cname, :cemail, :cnumber)');

    // By using the prepare function and binding query variables like below we can prevent SQL injection
    // NOTE: If you ever have an Integer variable instead of String use "PDO::PARAM_INT" instead of "PDO::PARAM_STR"
   // $insert->bindValue(":cid", $c_id, PDO::PARAM_STR);
    $insert->bindValue(":cname", $name, PDO::PARAM_STR);
    $insert->bindValue(":cemail", $email, PDO::PARAM_STR);
    $insert->bindValue(":cnumber", $number, PDO::PARAM_STR);
   

    // This will execute the prepared SQL statement with all the variables we binded
    $insert->execute();

  }
  public static function selectPDO() {
    $my_array=array();  
    $stmt=$GLOBALS['connect']->query("SELECT * FROM customer");
    while ($row = $stmt->fetch()) {
      array_push($my_array,array($row["c_id"],$row["name"],$row["email"],$row["number"]));
  }
  $_SESSION['customer']=$my_array;
  }

}





$db= new connect();
$db->toMainDatabase();
$db->selectPDO();


    $aResult ="";
 
    
  
    for($i=0;$i<count($_SESSION['customer']);$i++){
        if($_SESSION['customer'][$i][1]==$_POST['customer']){
            $aResult=$_SESSION['customer'][$i][3];
        }
        
          }
    
echo $aResult;


?>