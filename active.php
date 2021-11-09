<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "notes");
if (mysqli_connect_error()) {
    die("Sorry we failed to connect: " . mysqli_connect_error());
  }
if(isset($_GET['token'])){
    $token=$_GET['token'];
  // echo $token;
    $sql="UPDATE `logindetail` SET `status` = 'active' WHERE `logindetail`.`token` = '$token'";
   // $sql = "UPDATE `logindetail` SET `status` = 'active'  WHERE `logindetails`.`token` = $token";
   $result = mysqli_query($conn, $sql);
    if($result){
$_SESSION['loggedin']=true;
//echo $_SESSION['username'];
//echo $_SESSION['email'];
// header("location: /PhpLearning/cruduserafterlogin.php" );
header("location: inotesaftercreate.php" );
    }
    else{
        echo $token;
        //header("location: inotes.php" );
    }
}
?>