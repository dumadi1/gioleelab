
<?php
session_start();
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
}
$db= new connect();
$db->toMainDatabase();

if(isset($_POST['save'])){
    $username=trim($_POST['uname']);
    
    $username=preg_replace('/\s+/', '', $username);
   
    $number=trim($_POST['fname']);
    $email=trim($_POST['lname']);
   
    $db->exampleInsertPDO($username,$email,$number);
    header("Location: sendupdate.php");
    // $sql="insert into customer (name,email,number) values('$username','$email','$number')";
    // $result=mysqli_query($con,$sql);
    // if($result){
    //   $_SESSION['username']=$user;
    //     sleep(1.5);
    //     header("Location: /dashboard/sendupdate.php");
    //     exit();
    // }else{
    //     echo 'Failed';
    // }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register Customer</title>
    <link href="css/signup.css" rel="stylesheet" />
    <script src="login.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
  </head>
  <body>
    <form class="form" action="register.php" method="POST" enctype="multipart/form-data">
    <div class="imgcontainer">
        <img src="images/gleeimg.png" alt="Logo" class="Logo" width="150" height="150"/>
      </div>
      <div class="addcust">
    <a href="sendupdate.php" alt="account" height="80">Send Update</a>
  </div>
      <div class="container">
        <label for="uname"><b>Company Name</b></label>
        <input
          type="text"
          id="name"
          placeholder="Enter Company Name"
          name="uname"
          required
        />

      
    

          <label for="fame"><b>Phone Number</b></label>
          <input
            type="text"
            id="fname"
            placeholder="Enter Phone Number"
            name="fname"
            required
          />
          
            <label for="lname"><b>Email</b></label>
            <input
              type="text"
              id="lname"
              placeholder="Enter Email"
              name="lname"
              required
            />
       
        <button type="submit" id="sign" name='save'>Register</button>
        
      </div>
    </form>
    </body>
    </html>