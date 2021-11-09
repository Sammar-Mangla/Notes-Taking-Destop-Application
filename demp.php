<?php
error_reporting(0);
session_start();
if (isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == true) {
    header("location: inotesuserafterlogin.php");
    exit;
}
$delete = false;
$insert = false;
$update = false;
$singup = false;
$singin = false;
$showerror = "";
$error = false;

$conn = mysqli_connect("localhost", "root", "", "notes");

if (mysqli_connect_error()) {
    die("Sorry we failed to connect: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    if ($_POST['snoEdit'] != 0) {
        // Update the record
        $sno = $_POST["snoEdit"];
        $title = $_POST["tittleedit"];
        $description = $_POST["descedit"];

        // Sql query to be executed
        $sql = "UPDATE `notes` SET `tittle` = '$title' , `description` = '$description' WHERE `notes`.`sno` = $sno";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $update = true;
        } else {
            echo "We could not update the record successfully";
        }
    } else if ($_POST['snodelete'] != 0) {
        $sno = $_POST['snodelete'];
        //echo $sno;
        $sql = "DELETE FROM `notes` WHERE `notes`.`sno` = $sno ";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $delete = true;
        } else {
            echo "We could not delete the record successfully";
        }
    }
    //-----------------------------------------login logout work-----------------------------------------------------

    else if ($_POST['signup'] != 0) {
        $signupusername = $_POST['signupusername'];
        $signuppassword = $_POST['signuppassword'];
        $signupemail = $_POST['signupemail'];
        $signupconfirm = $_POST['signupconfirm'];


        $exist = false;
        $sql = "SELECT * FROM logindetail Where email='$signupemail' ";

        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        if ($num > 0) {
            $exist = true;
        } else {
            $exist = false;
        }


        if ($signuppassword == $signupconfirm and $exist == false) {
            $sql = "INSERT INTO `logindetail` ( `username`, `email`, `password1`) VALUES ( '$signupusername', '$signupemail', '$signuppassword')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $singup = true;
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $signupusername;
                $_SESSION['email'] = $signupemail;
                // header("location: /PhpLearning/cruduserafterlogin.php" );
                header("location: /PhpLearning/crudaftercreate.php");
            }
        } else if ($signuppassword != $signupconfirm) {
            $showerror = "you entered different confirm password";
            $error = true;
        } else {
            $showerror = "You have Entered existing email  please use different email id";
            $error = true;
        }
    } else if ($_POST['signin'] != 0) {
        $signinusername = $_POST['signinusername'];
        $signinpassword = $_POST['signinpassword'];
        $signinemail = $_POST['signinemail'];
        $sql = "SELECT * FROM logindetail Where username='$signinusername' AND email='$signinemail' AND password1='$signinpassword' ";

        $result = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($result);
        if ($num == 1) {
            $singin = true;
            session_start();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $signinusername;
            $_SESSION['email'] = $signinemail;
            header("location: /PhpLearning/cruduserafterlogin.php");
        } else {
            $showerror = "User Do not exist";
            $error = true;
        }
    } else {
        $tittle = $_POST['tittle'];
        $description = $_POST['desc'];




        $sql = "INSERT INTO `notes` ( `tittle`, `description`) VALUES ( '$tittle', '$description')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $insert = true;
        } else {
            echo "The record was not inserted successfully because of this error ---> " . mysqli_error($conn);
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyNotes</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
         .container12{
  background:#fff;
  border-radius: .5rem;
  box-shadow: 0 .5rem 1rem rgba(0,0,0,.2);
}
    </style>

</head>

<body>

    <div class="container12">

    <header>
  
  <a href="#" class="logo"><img src="/img/inoteslogo.png" alt="" style="width: 60px; height: 60px;"> Smart<span>Notes</span></a>

  <div id="menu" class="fas fa-bars"></div>

  <nav class="navbar">
      <a href="inotes.php">Home</a>
      <a href="mynotes.php">My Notes</a>
      <a href="review.php">Features</a>
      <a href="contact.php">Contact</a>
      <a href="">Login/Register</a>
  </nav>

</header>

        <h1 class="heading"> Your Notes </h1>

        
    
    
    <div class="row row-cols-auto">
      <?php

      $sql = "SELECT * FROM `notes`";
      $result = mysqli_query($conn, $sql);

      // Find the number of records returned
      $num = mysqli_num_rows($result);
      //echo $num;
      if ($num > 0) {
        // $row = mysqli_fetch_assoc($result);
        $sno = 1;
        while ($row = mysqli_fetch_assoc($result)) {
          if ($row['tittle'] == "") {
            $tittle1 = "Untitled";
        } else {
            $tittle1 = $row['tittle'];
        }
        $content = $row['description'];
        $len = strlen($content);
          echo '<div class="col my-2 mx-3">
          <div class="card" >
  
            <div class="card-body">
             
              <h3 class="card-tittle">' . $tittle1 . '</h3>
              <span>' . $row['date'] . '</span>
             
              <p class="card-text">'.$row['description'].'
              </p>
              <button class="edit btn12" id='. $row['sno'] .'>Edit</button>  <button class="delete btn12" id=d' . $row['sno'] . '>Delete</button>
            </div>
          </div>
  
        </div>';
        }
      }
      ?>

      



    </div>

        <!-- footer section  -->

        <section class="footer">

            <div class="box-container">

                <div class="box">
                    <h3>about us</h3>
                    <p>iNotes.com is your online notepad. It allows you to take and share notes online without having to login.
                        You can use a rich text editor, sort notes by date or title and make notes private.
                        Best of all - iNote is a fast, clean, simple to use and FREE notepad online.</p>
                </div>

                <div class="box">
                    <h3>quick links</h3>
                    <a href="#" style="text-decoration: none;">Home</a>
                    <a href="#" style="text-decoration: none;">My Notes</a>
                    <a href="#" style="text-decoration: none;">Review</a>
                    <a href="#" style="text-decoration: none;">Features</a>
                    <a href="#" style="text-decoration: none;">Contact</a>
                </div>

                <div class="box">
                    <h3>follow us</h3>
                    <a href="#" style="text-decoration: none;">facebook</a>
                    <a href="#" style="text-decoration: none;">twitter</a>
                    <a href="#" style="text-decoration: none;">instagram</a>
                    <a href="#" style="text-decoration: none;">linkedin</a>
                </div>

                <div class="box">
                    <h3>contact us</h3>
                    <p> <i class="fas fa-phone"></i> +123-456-7890 </p>
                    <p> <i class="fas fa-envelope"></i> studysmart@gmail.com </p>
                    <p> <i class="fas fa-map-marker-alt"></i> mumbai, india - 400104 </p>
                </div>

            </div>

            <div class="credit"> created by <span> mr. web designer </span> | all rights reserved </div>

        </section>

    </div>


    <!-- custom js file link -->
    <!-- custom js file link -->
<script>
let menu = document.querySelector('#menu');
let navbar = document.querySelector('.navbar');

menu.onclick = () =>{
  menu.classList.toggle('fa-times');
  navbar.classList.toggle('active');
}

window.onscroll = () =>{
  menu.classList.remove('fa-times');
  navbar.classList.remove('active');
}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>
