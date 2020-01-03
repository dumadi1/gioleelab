<?php
require_once('connection.php');
    $aResult ="";
    $_SESSION['customer']=array();
    $sql="select * from customer";
    // $sql="select * from customer where username='".$user."' limit 1";
     $result=mysqli_query($con,$sql);
     $my_array=array();
     if(mysqli_num_rows($result)>0){
        while($row = $result->fetch_assoc()) {
            array_push($my_array,array($row["c_id"],$row["name"],$row["email"],$row["number"]));
        }
        $_SESSION['customer']=$my_array;
     }
  
    for($i=0;$i<count($_SESSION['customer']);$i++){
        if($_SESSION['customer'][$i][1]==$_POST['customer']){
            $aResult=$_SESSION['customer'][$i][3];
        }
        
          }
    
echo $aResult;


?>