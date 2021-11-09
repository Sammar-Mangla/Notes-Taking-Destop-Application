<?php
error_reporting(0);
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){ 
   header("location: inotes.php");
   exit;
}
$datatable=$_SESSION['email'];
$delete = false;

$update = false;

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
        $sql = "UPDATE `$datatable` SET `tittle` = '$title' , `description` = '$description' WHERE `$datatable`.`sno` = $sno";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $update = true;
        } else {
            echo "We could not update the record successfully";
        }
    } else if ($_POST['snodelete'] != 0) {
        $sno = $_POST['snodelete'];
        //echo $sno;
        $sql = "DELETE FROM `$datatable` WHERE `$datatable`.`sno` = $sno ";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $delete = true;
        } else {
            echo "We could not delete the record successfully";
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

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/stylelogin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>

<body>
<!-- ----------------------------------------------Modal which we get on clicking edit button-------- -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel" style="font-size: 3rem; font-weight:bold;">Edit this Note</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <form action="mynotesaftercreate.php" method="post">
            <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="mb-3">
              <label for="tittleedit" class="form-label" style="font-family:serif; font-weight:800"><img src="images/logo.png" alt="" style="width: 40px;height:40px;">Tittle for your Note :</label>
              <input type="text" name="tittleedit" class="form-control" id="tittleedit" aria-describedby="usernameHelp">

            </div>
            <div class="mb-3">
              <label for="tittle11" class="form-label" style="font-family:serif; font-weight:800"><img src="images/desc.png" alt="" style="width: 40px;height:40px;">Description of your Note :</label>


            </div>
            <div class="form-floating mb-3">
              <label for="descedit"></label>
              <textarea class="form-control" name="descedit" placeholder="Leave a comment here" id="descedit" style="height: 100px"></textarea>
            </div>



        </div>
        <div class="modal-footer d-block mr-auto">
          <button type="button" class="btn12" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn12">Save changes</button>
        </div>
        </form>
      </div>
    </div>
  </div>


  <!--------------------------------------------- Modal we get on clicking  -->
  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel" style="font-size: 3rem; font-weight:bold;">Delete this Note</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="mynotesaftercraete.php" method="post">
            <input type="hidden" name="snodelete" id="snodelete">
            <div class="mb-3">
              <label for="tittle11" class="form-label"  style="font-family:serif;font-size: 2.1rem; font-weight:bold;">Are you sure you want to delete this note!</label>


            </div>
            <div class="modal-footer d-block mr-auto">
              <button type="button" class="btn12" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn12">Delete</button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
  <div class="container12">

        <header>

            <a href="#" class="logo" style="text-decoration: none;"><img src="images/inoteslogo.png" alt="" style="width: 60px; height: 60px;">
                Smart<span>Notes</span></a>

            <div id="menu" class="fas fa-bars"></div>

            <nav class="navbar">
                <a href="inotesaftercreate.php" style="text-decoration: none;">Home</a>
                <a href="mynotesaftercreate.php" style="text-decoration: none;">My Notes</a>
                <a href="review.php" style="text-decoration: none;">Features</a>
                <a href="contact.php" style="text-decoration: none;">Contact</a>
                <a href="logout.php" style="text-decoration: none;">Logout</a>
            </nav>

        </header>
  <?php
  if ($delete) {
    echo '
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
<symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
 <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
</symbol>
<symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
 <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</symbol>
<symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
 <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
</symbol>
</svg>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
    <div>
      <strong>Your note has been deleted successfully</strong>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

  </div>';
  }
  ?>
  <?php
  if ($update) {
    echo '
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
<symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
 <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
</symbol>
<symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
 <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</symbol>
<symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
 <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
</symbol>
</svg>
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
    <div>
      <strong>Your note has been updated successfully</strong>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

  </div>';
  }
  ?>
  <?php
  if ($error) {
    echo '
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
<symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
 <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
</symbol>
<symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
 <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
</symbol>
<symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
 <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
</symbol>
</svg>
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
    <div>
      <strong>' . $showerror . '</strong>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

  </div>';
  }
  ?>

        <h1 class="heading"> Your Notes </h1>

        
    
    
    <div class="row row-cols-auto">
      <?php

      $sql = "SELECT * FROM `$datatable`";
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
      else{
        echo '<p  style="padding-left: 35rem; font-size:3rem;   color:black;"><strong style="color:seagreen;">Sorry,</strong> do not have any notes. </p>';
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

            <div class="credit"> created by <span> Sammar Mangla </span> | all rights reserved </div>

        </section>

    </div>


    
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
<script>
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ",e.target.parentNode);
        div = e.target.parentNode;
        console.log(div.getElementsByTagName("h3")[0].innerHTML);
        tittle = div.getElementsByTagName("h3")[0].innerHTML;
        description =div.getElementsByTagName("p")[0].innerHTML;
        /*div=e.target.parentNode.parentNode;
        tagname=td
        [0].innertext
        [1].innertext
        */
        console.log(tittle, description);
        tittleedit.value = tittle;
        descedit.value = description;
        snoEdit.value = e.target.id;
        console.log(e.target.id);
        $('#exampleModal').modal('toggle');
      })
    })
    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit ");
        snodelete.value = e.target.id.substr(1);

        /* if (confirm("Are you sure you want to delete this note!")) {
           console.log("yes");
           window.location = `/PhpLearning/crud.php?delete=${sno}`;
           // TODO: Create a form and use post request to submit a form
         } else {
           console.log("no");
         }*/
        $('#deleteModal').modal('toggle');
      })
    })
    
  </script>
<!-- custom js file link -->
    <!-- custom js file link -->
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  

</body>

</html>