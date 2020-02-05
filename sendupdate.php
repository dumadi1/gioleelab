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



?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Send Update</title>
    <link href="css/signup.css" rel="stylesheet" />
    <script src="script.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
  <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">

  </head>
  <body>
    <form class="form" action="sendit.php" method="POST" enctype="multipart/form-data">
    <div class="imgcontainer">
        <img src="images/gleeimg.png" alt="Logo" class="Logo" width="150" height="150"/>
      </div>
      <div class="addcust">
    <a href="register.php" alt="account" height="80">Add Customer</a>
  </div>

      <div class="container">
      <label for="cust"><b>Customer</b></label>
      <input type="text" list="customers" name="customer" required onblur="myFunction(this.value)">
        <datalist id="customers" name="customer" class="customer">

          <?php
          for($i=0;$i<count($_SESSION['customer']);$i++){
          echo "
          <option value=".$_SESSION['customer'][$i][1]." >
          ";
          }
           ?>
          
        </datalist>
          
    <br>
    
    


    <label for="sample"><b>Sample SiteName/Incident-Number</b></label>
        <input
          type="text"
          id="sample"
          placeholder="Enter Sample ID"
          name="id"
          required
        />

        <label for="stage"><b>Stage</b></label>
    <select name="stage" class="stage" onchange="compose(this.value)">
        <option selected>Please select one option</option>
        <option>Sample Received</option>
        <option>Sample Prep</option>
        <option>GC Analysis</option>
        <option>Heavy Metals Analysis</option>
        <option>Nutrient Analysis</option>
        <option>Insitu Analysis</option>
        <option>Microbiology Analysis</option>
        <option>Toxicological Analysis</option>
        <option>Quality Control Check</option>
        <option>Result Compilation</option>
        <option>Result Review</option>
        <option>Ready for Collection</option>
          
    </select>


        <label for="method"><b>Method of update</b></label>
        <select name="method" class="method" onchange="if (this.selectedIndex != -1) doSomething(this.selectedIndex);" onfocus="this.selectedIndex = -1;">
        <option selected>Please select one option</option>
    <option value="1">Email</option>
    <option value="2">Text Message</option>
    <option value="3">Both</option>
   
</select>

<label id="emailholder" for="email" hidden><b>Email</b></label>
        <input
          type="hidden"
          id="email"
          placeholder="Enter Email"
          name="email"
          required
         value="help"
        />
    
        <label id="phoneholder" for="number" hidden><b>Phone Number</b></label>
       
        <input
          type="hidden"
          id="phone"
          placeholder="Enter Phone number"
          name="number"
          required
    
        />
        <label id="phoneholder" for="number"><b>Message</b></label>
        <br>
        <textarea name="message" cols="40" rows="6" id="message"></textarea>
        <!-- <input
          type="text"
          id="message"
          placeholder="Enter Message"
          name="Message"
        /> -->

        

        <button type="submit" id="sign" name='save'>Send Update</button>
        
      </div>
    </form>
    </body>
    </html>