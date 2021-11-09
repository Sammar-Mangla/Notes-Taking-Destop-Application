<?php
error_reporting(0);


$insert = false;

$singup=false;
$singin=false;
$showerror="";
$error=false;

$conn = mysqli_connect("localhost", "root", "", "notes");

if (mysqli_connect_error()) {
  die("Sorry we failed to connect: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if($_POST['signup'] != 0){
    $signupusername = $_POST['signupusername'];
    $signuppassword = $_POST['signuppassword'];
    $signupemail=$_POST['signupemail'];
    $signupconfirm=$_POST['signupconfirm'];
$pass=password_hash("$signuppassword", PASSWORD_DEFAULT);


$exist=false;
$sql="SELECT * FROM logindetail Where email='$signupemail' ";

    $result = mysqli_query($conn, $sql);
    $num=mysqli_num_rows($result);
    if($num>0){
      $exist=true;
    }
    else{
      $exist=false;
    }

$token=bin2hex(random_bytes(15));
if($signuppassword==$signupconfirm AND $exist==false){
    $sql="INSERT INTO `logindetail` (`username`, `email`, `password1`, `date`, `token`, `status`) VALUES ('$signupusername', '$signupemail', '$pass', current_timestamp(), '$token', 'inactive')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      

$subject = "Email Activation!!";
$body = "Hi, $signupusername. Click here to active your account
http://localhost/PhpLearning/inotes/active.php?token=$token";

$headers = "From: khanhatsh@gmail.com";

if (mail($signupemail, $subject, $body, $headers)) {
session_start();
      $_SESSION['username']=$signupusername;
$_SESSION['email']=$signupemail;
     $showerror="check your mail to active account";
     $error=true;
     
        

} else {
  $showerror="please enter a existing email id";
  $error=true;
    }
  }
    
  }
  else if($signuppassword!=$signupconfirm){
    $showerror="you entered different confirm password";
    $error=true;
  }
  else{
    $showerror="You have Entered existing email  please use different email id";
    $error=true;
  }
    
  
}
else if($_POST['signin']!=0){
  $signinusername = $_POST['signinusername'];
    $signinpassword = $_POST['signinpassword'];
    $signinemail=$_POST['signinemail'];
   $pass=password_hash("$signinpassword", PASSWORD_DEFAULT);
    
    $sql="SELECT * FROM `logindetail` WHERE username='$signinusername' AND email='$signinemail' AND status='active' ";

    $result = mysqli_query($conn, $sql);
    $num=mysqli_num_rows($result);
    if ($num==1) {
      while($row=mysqli_fetch_assoc($result)){
        if(password_verify($signinpassword,$row['password1'])){
          $singin= true;
      session_start();
      $_SESSION['loggedin']=true;
      $_SESSION['username']=$signinusername;
      $_SESSION['email']=$signinemail;
      header("location: inotesuserafterlogin.php" );
        }
      }
      
      }
    
    else{
      $showerror="User Do not exist";
      $error=true;
    } 
}
  
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartNotes</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/stylelogin.css" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
</head>
<body>
<!------------------------------------login modal--------------------------------->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <h5 class="modal-title" id="loginModalLabel" >Sign-up</h5>-->
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" >
          <div class="container" >
            <form action="inotes.php" method="post" class="login123" style="text-align: center; justify-content:center;">
              <input type="hidden" name="signin" id="signin">
              <h2 class="tittle12" style="text-align: center; font-size:3rem" >Sign in</h2>
              <div class="input-field">
                <i class="fas fa-user"></i>
                <input type="text" id="signinusername" name="signinusername" placeholder="Username" />
              </div>
              <div class="input-field" >
                <i class="fas fa-envelope"></i>
                <input type="text" id="signinemail" name="signinemail" placeholder="Email" />
              </div>
              <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" id="signinpassword" name="signinpassword" placeholder="Password" />
              </div>
              <a href="#" id="forget"style="color:cornsilk; font-size:1.5rem;">forgetpassword</a></p>
              <button type="submit" class="btn123 ">Login</button>
              <br>
             <p style="font-size: 1.5rem;"> Why Create an Account?<a href="#" id="login1"style="color:cornsilk;">Create</a></p> 
              <p class="social-text" >Or Sign in with social platforms</p>
              <div class="social-media">
                <a href="#" class="social-icon">
                  <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-icon">
                  <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="social-icon">
                  <i class="fab fa-google"></i>
                </a>
                <a href="#" class="social-icon">
                  <i class="fab fa-linkedin-in"></i>
                </a>
              </div>
            </form>
          </div>

        </div>

      </div>
    </div>
  </div>
  </div>
                                             <!--loginout modal-->
  <!-- Button trigger modal -->

  <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <h5 class="modal-title" id="loginModalLabel" >Sign-up</h5>-->
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="text-align: center;">
          <div class="container" style="text-align: center;">
            <form action="inotes.php" method="post" class="login123" style="text-align: center; justify-content:center;">
              <input type="hidden" name="signup" id="signup">
              <h2 class="tittle12">Sign Up</h2>
              <div class="input-field">
                <i class="fas fa-user"></i>
                <input type="text" id="signupusername" name="signupusername" placeholder="Username" />
              </div>
              <div class="input-field">
                <i class="fas fa-envelope"></i>
                <input type="text" id="signupemail" name="signupemail" placeholder="Email" />
              </div>
              <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" id="signuppassword" name="signuppassword" placeholder="Password" />
              </div>
              <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" id="signupconfirm" name="signupconfirm" placeholder="ComfirmPassword" />
              </div>
              <button type="submit" class="btn123">Create</button>
              <br>
              <p style="font-size: 1.5rem;">Login if you have account<a href="#" id="login2" style="color:cornsilk;">Login</a></p>
              <p class="social-text">Or Sign Up with social platforms</p>
              <div class="social-media">
                <a href="#" class="social-icon">
                  <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-icon">
                  <i class="fab fa-twitter"></i>
                </a>
                <a href="#" class="social-icon">
                  <i class="fab fa-google"></i>
                </a>
                <a href="#" class="social-icon">
                  <i class="fab fa-linkedin-in"></i>
                </a>
              </div>
            </form>
          </div>

        </div>

      </div>
    </div>
  </div>
  </div>
    <!-----------------------------------------------main html------------------------------------->
<div class="container12">

<header>
  
    <a href="#" class="logo" style="text-decoration: none;"><img src="images/inoteslogo.png" alt="" style="width: 60px; height: 60px; " > Smart<span >Notes</span></a>

    <div id="menu" class="fas fa-bars"></div>

    <nav class="navbar">
        <a href="inotesuserafterlogin.php" style="text-decoration: none;">Home</a>
        <a href="mynotesafterlogin.php" style="text-decoration: none;">My Notes</a>
        <a href="reviewafterlogin.php" style="text-decoration: none;">Features</a>
        <a href="contactafterlogin.php" style="text-decoration: none;">Contact</a>
        <a href="logout.php" style="text-decoration: none;">Logout</a>
    </nav>

</header>

  
  <?php
  if ($singin) {
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
      <strong>You are successfully login</strong>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

  </div>';
  }
  ?>
  <?php
  if ($singup) {
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
      <strong>You are successfully sign up</strong>
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
  <div class="container12" style="border: 2px solid; margin:1.5rem 1.5rem;">
  <h2 class="heading1">Getting Started</h2>
<strong style="font-size: 2rem; padding-left:1rem;">Anonymous / Guest User:</strong> <h4 style="padding-left: 1rem;">Stay anonymous and save notes without signing in. Your notes are saved to your browser, so you can come back and edit your notes anytime as long as you don't delete your browser cookies.</h4>
<strong style="font-size: 2rem; padding-left:1rem;">Registered Free User:</strong> <h4 style="padding-left: 1rem;">ign up for a free account so you can save your notes as private and login from anywhere to edit them.</h4>
  

<p class="heading1">Here is a neat list of things you can do with aNotepad</p>
<h4 style="padding-left: 1rem;">* Save notes even without creating an account. All your notes will be "Public" and you can only edit them from the same web browser</h4>
<h4 style="padding-left: 1rem;">* Register a free account and save notes as either "Private" or "Public"</h4>
<h4 style="padding-left: 1rem;">* You can easily share notes via Twitter, Facebook etc.</h4>
<h4 style="padding-left: 1rem;">* You can use our powerful HTML editor to enhance the way your notes look</h4>
<h4 style="padding-left: 1rem;">* You can create multiple folders to organize your notes and sort your notes by date or title</h4>
<h4 style="padding-left: 1rem;">* You can download your notes in PDF, MS Word, ODT, and Text format</h4>
<h4 style="padding-left: 1rem;">* Auto-save feature enables you to focus on your work rather than keep saving your notes</h4>
<h4 style="padding-left: 1rem;">* You can now quickly browse through your notes with the note preview button</h4>
<h4 style="padding-left: 1rem;">* You can make your note Password Protected so only people with password can read your note</h4>
<h4 style="padding-left: 1rem;">* You can set an editor's password on your note so multiple people can edit a single note</h4>
<h4 style="padding-left: 1rem;">* You can set customized time zone, auto-save note option, and new note default access in Settings page</h4>
<h4 style="padding-left: 1rem;">* You can download all notes in Zip archive from Settings page after logging in</h4>
<h4 style="padding-left: 1rem;">* You can enable or disable note commenting in Settings page</h4>
<h4 style="padding-left: 1rem;">* You can set color theme to Dark and Warm in Settings page</h4>
<h4 style="padding-left: 1rem;">* You can import from Word document and turn it into a note</h4>
<h4 style="padding-left: 1rem;">* You can set fixed width font for plain text note in Settings page</h4>
<h4 style="padding-left: 1rem;"><strong style="color: red;">[New] </strong>You can create an easy to use checklist with note type dropdown</h4>
<strong style="font-size: 2rem; padding-left:1rem; color:royalblue;">Feedback Forum</strong><h4 style="padding-left: 1rem;"> - We'd love to hear your ideas, thoughts, and suggestions. Please submit your feedback or feature requests here.</h4>

<p class="heading1">Why use aNotepad? Here are some ideas</p>
<h4 style="padding-left: 1rem;">* Save notes and access it from any location, any time</h4>
<h4 style="padding-left: 1rem;">* Create To-Do Lists</h4>
<h4 style="padding-left: 1rem;">* Use it as a daily Diary to note each day's events</h4>
<h4 style="padding-left: 1rem;">* Store Christmas Lists</h4>
<h4 style="padding-left: 1rem;">* Take Quick Notes during training sessions that you can easily share with others</h4>
<p class="heading1">More on How To Use aNotepad.com - a free online notes editor</p>
<h4 style="padding-left: 1rem;">aNotepad.com is an online editor that provides the user simple tools to save notes. In this, the notes are saved to your browser which you can access and change as many times as you want. You can also treat it as an online diary. You can continue editing your notes and share them if you want to. aNotepad.com is free and can be accessed from anywhere.</h4>

<h4 style="padding-left: 1rem;">While using this online diary, you can stay anonymous and save notes without signing in and can come back and access your notes anytime. Also, signing up to aNotepad is completely free. You can save your notes as "Private" or "Public". You can use rich text editor to enhance the way your notes look and can easily share them via Twitter, Facebook etc.</h4>

<h4 style="padding-left: 1rem;">aNotepad eliminates all formatting from text that is pasted into it and you get the plain text instead; you cannot use any special font. When you’re copying and pasting in some applications, there’s a "Paste Special - Unformatted" option.</h4>

<h4 style="padding-left: 1rem;">aNotepad is also convenient to store a quick copy of any text. It’s easy to quickly open Notepad and save a copy of your content into it before hitting the "Submit" button. That way if something goes wrong, you have a copy saved. Notepad starts up more quickly and uses far fewer system resources than Word.</h4>

<h4 style="padding-left: 1rem;">Because aNotepad makes no changes to files that it opens unless you make changes, it’s useful for examining and editing files which could be screwed up by more advanced programs. It can be used on HTML code as well. Likewise, if you have a file of unknown type because the extension has been deleted, opening it in Notepad is a good way to make sure that you don’t ruin it up merely by opening it.</h4>

<h4 style="padding-left: 1rem;">For each visitor to the notepad online page, our Web server automatically recognizes the consumer's web browser, but not the e-mail address making it more secure.</h4>

<h4 style="padding-left: 1rem;">You can also advertise with the use of this online notepad as it has a wide-spread, global audience across all demographics. This is primarily due to the fact that this site is being used in a large number of state-of-the-art ways in which even we couldn't have imagined.</h4>

<h4 style="padding-left: 1rem;">This list is in no way complete. So if you know of any other innovative ways to use aNotepad, please send a mail to support@anotepad.com. We would love to hear from you.</h4>
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
           <p> <i class="fas fa-envelope"></i> studynotes@gmail.com </p>
           <p> <i class="fas fa-map-marker-alt"></i> mumbai, india - 400104 </p>
        </div>

    </div>

    <div class="credit"> created by <span> Sammar Mangla </span> | all rights reserved </div>

</section>

</div>
<script>
    logins = document.querySelector('#login');
    logins.addEventListener('click', () => {
      signin.value = 1;
      $('#loginModal').modal('toggle');
    });
    logins1 = document.querySelector('#login1');
    logins1.addEventListener('click', () => {
      signup.value = 1;
      signin.value = 0;
      $('#loginModal').modal('hide');
      $('#signupModal').modal('toggle');
    });
    logins2 = document.querySelector('#login2');
    logins2.addEventListener('click', () => {
      signup.value = 0;
      signin.value = 1;
      $('#signupModal').modal('hide');
      $('#loginModal').modal('toggle');
    });
  
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
<!-- custom js file link -->
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
  

</body>
</html>