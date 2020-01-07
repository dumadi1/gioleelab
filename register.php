
<?php
session_start();
//$_SESSION['username']='';
require_once('connection.php');
if(isset($_POST['save'])){
    $username=mysqli_real_escape_string($con,trim($_POST['uname']));
    //$username=trim($username," ");
    $username=preg_replace('/\s+/', '', $username);
   // $useralias=mysqli_real_escape_string($con,trim($_POST['alias']));
    $number=mysqli_real_escape_string($con,trim($_POST['fname']));
    $email=mysqli_real_escape_string($con,trim($_POST['lname']));
   
    //list($month, $day, $year) = preg_split('[,]', $address);
    //echo "Month: $month; Day: $day; Year: $year<br />\n";
  
    $sql="insert into customer (name,email,number) values('$username','$email','$number')";
    $result=mysqli_query($con,$sql);
    if($result){
      $_SESSION['username']=$user;
        sleep(1.5);
        header("Location: /dashboard/sendupdate.php");
        exit();
    }else{
        echo 'Failed';
    }
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